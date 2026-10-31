const fs = require('node:fs');
const path = require('node:path');
const chokidar = require('chokidar');
const dotenv = require('dotenv');
const { Client } = require('basic-ftp');

const projectRoot = path.resolve(__dirname, '..');
const envPath = path.join(projectRoot, '.env');
const remoteRoot = '/wp';
const confirmationValue = 'I_CONFIRM_STAGING';
const deployTargets = [
  'wp-content/plugins/brodart-measurement-calculator',
  'wp-content/plugins/brodart-menu-images',
  'wp-content/plugins/brodart-product-importer.php',
  'wp-content/mu-plugins/brodart-shop-tweaks.php',
];

if (fs.existsSync(envPath)) {
  dotenv.config({ path: envPath });
}

function isDeployablePath(filePath) {
  const relativePath = path.relative(projectRoot, path.resolve(filePath)).split(path.sep).join('/');
  if (!relativePath || relativePath.startsWith('../') || path.isAbsolute(relativePath)) {
    return false;
  }

  const segments = relativePath.split('/');
  const fileName = segments.at(-1);
  if (
    segments.some((segment) => ['.git', 'node_modules', 'docs', 'tools'].includes(segment))
    || segments.some((segment) => segment === 'wp-config.php' || segment === 'debug.log')
    || fileName === '.env'
    || fileName.startsWith('.env.')
    || /\.(sql|log|tmp|temp|swp|swo)$/i.test(fileName)
  ) {
    return false;
  }

  return deployTargets.some((target) => (
    relativePath === target || relativePath.startsWith(`${target}/`)
  ));
}

function getConfig() {
  const required = ['FTPS_HOST', 'FTPS_USERNAME', 'FTPS_PASSWORD', 'FTPS_REMOTE_PATH'];
  const missing = required.filter((key) => !process.env[key]?.trim());
  if (missing.length) {
    throw new Error(`Missing required .env settings: ${missing.join(', ')}`);
  }

  const configuredRoot = process.env.FTPS_REMOTE_PATH.trim().replace(/\/+$/, '') || '/';
  if (configuredRoot !== remoteRoot) {
    throw new Error(`FTPS_REMOTE_PATH must be exactly ${remoteRoot}; deployment outside the staging folder is blocked.`);
  }

  const port = Number(process.env.FTPS_PORT || 21);
  if (!Number.isInteger(port) || port < 1 || port > 65535) {
    throw new Error('FTPS_PORT must be an integer between 1 and 65535.');
  }

  return {
    host: process.env.FTPS_HOST.trim(),
    port,
    username: process.env.FTPS_USERNAME.trim(),
    password: process.env.FTPS_PASSWORD,
    remoteRoot,
  };
}

function requireWriteConfirmation() {
  if (process.env.FTPS_STAGING_CONFIRM !== confirmationValue) {
    throw new Error(
      `Remote writes are disabled. After confirming ${remoteRoot} is staging and backing it up, set FTPS_STAGING_CONFIRM=${confirmationValue} in .env.`,
    );
  }
}

async function connect(config) {
  const client = new Client(15000);
  client.ftp.verbose = false;
  try {
    await client.access({
      host: config.host,
      port: config.port,
      user: config.username,
      password: config.password,
      secure: true,
      secureOptions: {
        minVersion: 'TLSv1.2',
        rejectUnauthorized: true,
        servername: config.host,
      },
    });
    return client;
  } catch (error) {
    client.close();
    throw error;
  }
}

async function verifyStaging(config) {
  const client = await connect(config);
  try {
    const entries = await client.list(config.remoteRoot);
    const names = new Set(entries.map((entry) => entry.name));
    const missingMarkers = ['wp-admin', 'wp-content', 'wp-includes'].filter((name) => !names.has(name));
    if (missingMarkers.length) {
      throw new Error(`The remote ${remoteRoot} directory is missing WordPress markers: ${missingMarkers.join(', ')}.`);
    }
    console.log(`Validated FTPS and WordPress directory ${remoteRoot} (${entries.length} entries).`);
  } finally {
    client.close();
  }
}

function getLocalTargets() {
  const files = [];
  for (const target of deployTargets) {
    const absoluteTarget = path.join(projectRoot, ...target.split('/'));
    if (!fs.existsSync(absoluteTarget)) {
      continue;
    }

    const stats = fs.lstatSync(absoluteTarget);
    if (stats.isSymbolicLink()) {
      throw new Error(`Refusing to deploy symbolic link: ${target}`);
    }
    if (stats.isFile()) {
      files.push(absoluteTarget);
      continue;
    }

    for (const entry of fs.readdirSync(absoluteTarget, { withFileTypes: true })) {
      const childPath = path.join(absoluteTarget, entry.name);
      if (entry.isSymbolicLink()) {
        throw new Error(`Refusing to deploy symbolic link: ${path.relative(projectRoot, childPath)}`);
      }
      if (entry.isDirectory()) {
        files.push(...getFilesRecursively(childPath));
      } else if (entry.isFile() && isDeployablePath(childPath)) {
        files.push(childPath);
      }
    }
  }
  return files.sort();
}

function getFilesRecursively(directoryPath) {
  const files = [];
  for (const entry of fs.readdirSync(directoryPath, { withFileTypes: true })) {
    const childPath = path.join(directoryPath, entry.name);
    if (entry.isSymbolicLink()) {
      throw new Error(`Refusing to deploy symbolic link: ${path.relative(projectRoot, childPath)}`);
    }
    if (entry.isDirectory()) {
      files.push(...getFilesRecursively(childPath));
    } else if (entry.isFile() && isDeployablePath(childPath)) {
      files.push(childPath);
    }
  }
  return files;
}

function getRemotePath(filePath, config) {
  if (!isDeployablePath(filePath)) {
    throw new Error(`File is outside the deployment allowlist: ${filePath}`);
  }
  const relativePath = path.relative(projectRoot, filePath).split(path.sep).join('/');
  return `${config.remoteRoot}/${relativePath}`;
}

function assertNoSymlinkPath(filePath) {
  const relativePath = path.relative(projectRoot, path.resolve(filePath));
  let currentPath = projectRoot;
  for (const segment of relativePath.split(path.sep)) {
    if (!segment || segment === '.') {
      continue;
    }
    currentPath = path.join(currentPath, segment);
    if (fs.lstatSync(currentPath).isSymbolicLink()) {
      throw new Error(`Refusing to deploy through symbolic link: ${path.relative(projectRoot, currentPath)}`);
    }
  }
}

async function uploadFile(config, filePath) {
  if (!isDeployablePath(filePath)) {
    throw new Error(`File is outside the deployment allowlist: ${filePath}`);
  }
  assertNoSymlinkPath(filePath);
  const stats = await fs.promises.lstat(filePath);
  if (!stats.isFile() || stats.isSymbolicLink()) {
    throw new Error(`Refusing to upload a non-regular file: ${filePath}`);
  }

  const relativePath = path.relative(projectRoot, filePath).split(path.sep).join('/');
  const remotePath = getRemotePath(filePath, config);
  const client = await connect(config);
  try {
    await client.ensureDir(path.posix.dirname(remotePath));
    await client.uploadFrom(filePath, path.posix.basename(remotePath));
    console.log(`Uploaded ${relativePath}`);
  } finally {
    client.close();
  }
}

async function sync(config, apply) {
  const files = getLocalTargets();
  if (!files.length) {
    throw new Error('No approved local deployment files were found.');
  }
  console.log(`${apply ? 'Publishing' : 'Dry run'}: ${files.length} approved file(s) to ${remoteRoot}.`);
  for (const filePath of files) {
    const relativePath = path.relative(projectRoot, filePath).split(path.sep).join('/');
    console.log(`  ${apply ? 'UPLOAD' : 'WOULD UPLOAD'} ${relativePath}`);
  }
  if (!apply) {
    console.log('No remote files changed. Use npm run deploy:ftps:sync -- --apply to publish.');
    return;
  }

  requireWriteConfirmation();
  await verifyStaging(config);
  for (const filePath of files) {
    await uploadFile(config, filePath);
  }
}

async function watch(config) {
  requireWriteConfirmation();
  await verifyStaging(config);
  console.log(`Watching only approved Brodart plugin files; uploads go to ${remoteRoot}. Remote files are never deleted.`);

  const watchPaths = deployTargets.map((target) => path.join(projectRoot, ...target.split('/')));
  const watcher = chokidar.watch(watchPaths, {
    ignoreInitial: true,
    awaitWriteFinish: { stabilityThreshold: 1000, pollInterval: 100 },
  });

  let uploadQueue = Promise.resolve();
  const enqueueUpload = (filePath) => {
    if (!isDeployablePath(filePath)) {
      return;
    }
    uploadQueue = uploadQueue
      .then(() => uploadFile(config, filePath))
      .catch((error) => {
        console.error(`Upload failed for ${path.relative(projectRoot, filePath)}: ${error.message}`);
      });
  };

  watcher.on('add', enqueueUpload);
  watcher.on('change', enqueueUpload);
  watcher.on('unlink', (filePath) => {
    console.warn(`Local file removed; remote copy was kept: ${path.relative(projectRoot, filePath)}`);
  });
  watcher.on('error', (error) => {
    console.error(`Watcher error: ${error.message}`);
  });

  const stop = async () => {
    await watcher.close();
    await uploadQueue;
  };
  process.once('SIGINT', stop);
  process.once('SIGTERM', stop);
}

async function main() {
  const [command, ...args] = process.argv.slice(2);
  if (!['test', 'sync', 'watch'].includes(command) || args.some((arg) => arg !== '--apply')) {
    throw new Error('Usage: node tools/ftps-deploy-watcher.js <test|sync [--apply]|watch>');
  }
  if (command !== 'sync' && args.length) {
    throw new Error(`The ${command} command does not accept additional arguments.`);
  }

  const config = getConfig();
  if (command === 'test') {
    await verifyStaging(config);
  } else if (command === 'sync') {
    await sync(config, args.includes('--apply'));
  } else {
    await watch(config);
  }
}

if (require.main === module) {
  main().catch((error) => {
    console.error(error.message);
    process.exitCode = 1;
  });
}

module.exports = {
  getConfig,
  getLocalTargets,
  getRemotePath,
  isDeployablePath,
};

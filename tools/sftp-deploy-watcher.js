const crypto = require('node:crypto');
const fs = require('node:fs');
const path = require('node:path');
const chokidar = require('chokidar');
const dotenv = require('dotenv');
const SftpClient = require('ssh2-sftp-client');

const projectRoot = path.resolve(__dirname, '..');
const envPath = path.join(projectRoot, '.env');

if (fs.existsSync(envPath)) {
  dotenv.config({ path: envPath });
}

function normalizeFingerprint(value) {
  const fingerprint = value.trim().replace(/^SHA256:/i, '');
  if (/^[a-f\d]{64}$/i.test(fingerprint)) {
    return fingerprint.toLowerCase();
  }

  const decoded = Buffer.from(fingerprint, 'base64');
  if (decoded.length !== 32) {
    throw new Error('SFTP_HOST_KEY_SHA256 must be a SHA-256 fingerprint in hex or SHA256:base64 format.');
  }
  return decoded.toString('hex');
}

function getConfig() {
  const required = [
    'SFTP_HOST',
    'SFTP_USERNAME',
    'SFTP_PASSWORD',
    'SFTP_REMOTE_PATH',
    'SFTP_HOST_KEY_SHA256',
  ];
  const missing = required.filter((key) => !process.env[key]?.trim());
  if (missing.length) {
    throw new Error(`Missing required .env settings: ${missing.join(', ')}`);
  }

  const remotePath = process.env.SFTP_REMOTE_PATH.trim();
  if (!remotePath.startsWith('/')) {
    throw new Error('SFTP_REMOTE_PATH must be an absolute server path, for example /home/account/public_html.');
  }

  const port = Number(process.env.SFTP_PORT || 22);
  if (!Number.isInteger(port) || port < 1 || port > 65535) {
    throw new Error('SFTP_PORT must be an integer between 1 and 65535.');
  }

  return {
    host: process.env.SFTP_HOST.trim(),
    port,
    username: process.env.SFTP_USERNAME.trim(),
    password: process.env.SFTP_PASSWORD,
    remotePath,
    fingerprint: normalizeFingerprint(process.env.SFTP_HOST_KEY_SHA256),
  };
}

function connect(config) {
  const client = new SftpClient();
  return client.connect({
    host: config.host,
    port: config.port,
    username: config.username,
    password: config.password,
    hostHash: 'sha256',
    hostVerifier: (keyHash) => {
      const received = Buffer.isBuffer(keyHash) ? keyHash.toString('hex') : String(keyHash).toLowerCase();
      const expectedBuffer = Buffer.from(config.fingerprint, 'hex');
      const receivedBuffer = Buffer.from(received, 'hex');
      return receivedBuffer.length === expectedBuffer.length
        && crypto.timingSafeEqual(receivedBuffer, expectedBuffer);
    },
  }).then(() => client);
}

async function testConnection(config) {
  const client = await connect(config);
  try {
    const entries = await client.list(config.remotePath);
    console.log(`SFTP connection verified; remote directory is accessible (${entries.length} entries).`);
  } finally {
    await client.end();
  }
}

function isIgnored(filePath) {
  const relativePath = path.relative(projectRoot, filePath).split(path.sep).join('/');
  if (!relativePath) {
    return false;
  }
  if (relativePath.startsWith('../')) {
    return true;
  }

  const segments = relativePath.split('/');
  if (segments.some((segment) => ['.git', 'node_modules', 'docs', 'tools'].includes(segment))) {
    return true;
  }
  if (segments.includes('wp-content') && segments.some((segment) => [
    'cache',
    'upgrade',
    'backup-db',
    'jetpack-waf',
    'yaymail-logs',
  ].includes(segment))) {
    return true;
  }

  const fileName = segments.at(-1);
  return fileName === '.env'
    || fileName.startsWith('.env.')
    || fileName === 'wp-config.php'
    || fileName === 'debug.log'
    || /\.(sql|log|tmp|temp|swp|swo)$/i.test(fileName);
}

async function uploadFile(config, filePath) {
  const stats = await fs.promises.stat(filePath);
  if (!stats.isFile()) {
    return;
  }

  const relativePath = path.relative(projectRoot, filePath).split(path.sep).join('/');
  const remoteFilePath = `${config.remotePath.replace(/\/+$/, '')}/${relativePath}`;
  const remoteDirectory = path.posix.dirname(remoteFilePath);
  const client = await connect(config);
  try {
    await client.mkdir(remoteDirectory, true);
    await client.put(filePath, remoteFilePath);
    console.log(`Uploaded ${relativePath}`);
  } finally {
    await client.end();
  }
}

async function watch(config) {
  await testConnection(config);
  console.log('Watching for added or changed WordPress files. Remote files are never deleted.');

  const watcher = chokidar.watch(projectRoot, {
    ignored: isIgnored,
    ignoreInitial: true,
    awaitWriteFinish: { stabilityThreshold: 1000, pollInterval: 100 },
  });

  let uploadQueue = Promise.resolve();
  const enqueueUpload = (filePath) => {
    uploadQueue = uploadQueue
      .then(() => uploadFile(config, filePath))
      .catch((error) => {
        console.error(`Upload failed for ${path.relative(projectRoot, filePath)}: ${error.message}`);
      });
  };

  watcher.on('add', enqueueUpload);
  watcher.on('change', enqueueUpload);
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
  const command = process.argv[2];
  if (command !== 'test' && command !== 'watch') {
    throw new Error('Usage: node tools/sftp-deploy-watcher.js <test|watch>');
  }

  const config = getConfig();
  if (command === 'test') {
    await testConnection(config);
  } else {
    await watch(config);
  }
}

main().catch((error) => {
  console.error(error.message);
  process.exitCode = 1;
});

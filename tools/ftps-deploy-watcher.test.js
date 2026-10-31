const test = require('node:test');
const assert = require('node:assert/strict');
const path = require('node:path');
const {
  getConfig,
  getLocalTargets,
  getRemotePath,
  isDeployablePath,
} = require('./ftps-deploy-watcher');

const projectRoot = path.resolve(__dirname, '..');

test('allows only the approved custom Brodart code paths', () => {
  const allowed = [
    'wp-content/plugins/brodart-measurement-calculator/brodart-measurement-calculator.php',
    'wp-content/plugins/brodart-measurement-calculator/assets/measurement.js',
    'wp-content/plugins/brodart-menu-images/brodart-menu-images.php',
    'wp-content/plugins/brodart-product-importer.php',
    'wp-content/mu-plugins/brodart-shop-tweaks.php',
  ];
  for (const relativePath of allowed) {
    assert.equal(isDeployablePath(path.join(projectRoot, ...relativePath.split('/'))), true, relativePath);
  }
});

test('rejects WordPress core, configuration, customer uploads, docs and unrelated files', () => {
  const rejected = [
    'wp-config.php',
    'wp-content/plugins/woocommerce/woocommerce.php',
    'wp-content/uploads/customer-data.csv',
    'wp-content/mu-plugins/other-plugin.php',
    'docs/Brodart-DEPLOYMENT-GUIDE.md',
    '.env',
    'wp-content/plugins/brodart-measurement-calculator/.env.production',
    'wp-content/plugins/brodart-measurement-calculator/private.sql',
  ];
  for (const relativePath of rejected) {
    assert.equal(isDeployablePath(path.join(projectRoot, ...relativePath.split('/'))), false, relativePath);
  }
});

test('maps approved local files under the explicitly configured staging directory', () => {
  const config = { remoteRoot: '/wp' };
  const localFile = path.join(
    projectRoot,
    'wp-content',
    'plugins',
    'brodart-menu-images',
    'brodart-menu-images.php',
  );
  assert.equal(
    getRemotePath(localFile, config),
    '/wp/wp-content/plugins/brodart-menu-images/brodart-menu-images.php',
  );
  assert.throws(() => getRemotePath(path.join(projectRoot, 'wp-config.php'), config), /allowlist/);
});

test('requires FTPS configuration and blocks deployment to a different remote root', () => {
  const keys = ['FTPS_HOST', 'FTPS_USERNAME', 'FTPS_PASSWORD', 'FTPS_REMOTE_PATH', 'FTPS_PORT'];
  const previous = Object.fromEntries(keys.map((key) => [key, process.env[key]]));
  try {
    for (const key of keys) {
      delete process.env[key];
    }
    assert.throws(() => getConfig(), /Missing required \.env settings/);

    Object.assign(process.env, {
      FTPS_HOST: 'server1.webteamconcept.com',
      FTPS_USERNAME: 'test-user',
      FTPS_PASSWORD: 'test-password',
      FTPS_REMOTE_PATH: '/public_html',
    });
    assert.throws(() => getConfig(), /must be exactly \/wp/);

    process.env.FTPS_REMOTE_PATH = '/wp/';
    assert.equal(getConfig().remoteRoot, '/wp');
  } finally {
    for (const key of keys) {
      if (previous[key] === undefined) {
        delete process.env[key];
      } else {
        process.env[key] = previous[key];
      }
    }
  }
});

test('the initial sync set contains approved source files only', () => {
  const files = getLocalTargets();
  assert.ok(files.length > 0);
  assert.ok(files.every(isDeployablePath));
});

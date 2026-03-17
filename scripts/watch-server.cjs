const { config, runLongCommand } = require('./tooling-utils.cjs');

runLongCommand('browser-sync', [
  'start',
  '-p',
  config.browserSync.proxy,
  '--port',
  String(config.browserSync.port),
  '--listen',
  config.browserSync.listen,
  '--no-ui',
  '--no-open',
  '-f',
  config.browserSync.files.join(', ')
]);

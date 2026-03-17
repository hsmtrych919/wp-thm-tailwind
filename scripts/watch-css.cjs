const { config, runLongCommand } = require('./tooling-utils.cjs');

runLongCommand('watch', ['npm run css:build', config.paths.scssDir]);

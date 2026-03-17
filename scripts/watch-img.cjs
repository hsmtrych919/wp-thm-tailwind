const { config, runLongCommand } = require('./tooling-utils.cjs');

runLongCommand('onchange', [config.paths.srcImgDir, '-e', '**/*.DS_Store', '--', 'npm', 'run', 'imagemin']);

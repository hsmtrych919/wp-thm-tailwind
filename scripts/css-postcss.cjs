const { config, resolvePath, runCommand } = require('./tooling-utils.cjs');

const cssFile = resolvePath(config.paths.compiledStyleFile);

runCommand('postcss', [cssFile, '-o', cssFile]);

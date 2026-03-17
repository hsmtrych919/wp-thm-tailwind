const { config, resolvePath, runCommand } = require('./tooling-utils.cjs');

const entryFile = resolvePath(config.paths.styleEntryFile);
const outputFile = resolvePath(config.paths.compiledStyleFile);

runCommand('sass', ['--no-charset', `${entryFile}:${outputFile}`, '--style=compressed']);

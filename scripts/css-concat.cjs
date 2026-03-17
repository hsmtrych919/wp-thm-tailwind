const { config, readFile, writeFile } = require('./tooling-utils.cjs');

const tailwindBaseCss = readFile(config.paths.tailwindBaseFile);
const compiledStyleCss = readFile(config.paths.compiledStyleFile);

writeFile(config.paths.compiledStyleFile, `${tailwindBaseCss}${tailwindBaseCss.endsWith('\n') ? '' : '\n'}${compiledStyleCss}`);

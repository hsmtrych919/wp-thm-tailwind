const fs = require('fs');
const path = require('path');
const { spawn, spawnSync } = require('child_process');
const config = require('../tooling.config.cjs');

const rootDir = path.resolve(__dirname, '..');

function resolvePath(...parts) {
  return path.join(rootDir, ...parts);
}

function runCommand(command, args) {
  const result = spawnSync(command, args, {
    cwd: rootDir,
    stdio: 'inherit'
  });

  if (result.error) {
    throw result.error;
  }

  if (result.status !== 0) {
    process.exit(result.status || 1);
  }
}

function runLongCommand(command, args) {
  const child = spawn(command, args, {
    cwd: rootDir,
    stdio: 'inherit'
  });

  child.on('error', (error) => {
    console.error(error);
    process.exit(1);
  });

  child.on('exit', (code) => {
    process.exit(code || 0);
  });
}

function readFile(relativePath) {
  return fs.readFileSync(resolvePath(relativePath), 'utf8');
}

function writeFile(relativePath, content) {
  fs.writeFileSync(resolvePath(relativePath), content);
}

module.exports = {
  config,
  rootDir,
  resolvePath,
  runCommand,
  runLongCommand,
  readFile,
  writeFile
};

const imagemin = require('imagemin');
const imageminGifsicle = require('imagemin-gifsicle');
const imageminMozjpeg = require('imagemin-mozjpeg');
const imageminPngquant = require('imagemin-pngquant');
const imageminSvgo = require('imagemin-svgo');
const { config, resolvePath } = require('./tooling-utils.cjs');

(async () => {
  try {
    await imagemin([resolvePath(config.paths.srcImgDir, '**/*.{jpg,jpeg,png,gif,svg}')], {
      destination: resolvePath(config.paths.imgDir),
      plugins: [
        imageminGifsicle(),
        imageminMozjpeg(),
        imageminPngquant(),
        imageminSvgo()
      ]
    });
  } catch (error) {
    console.error(error);
    process.exit(1);
  }
})();

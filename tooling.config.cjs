module.exports = {
  paths: {
    scssDir: 'src/scss',
    cssDir: 'css',
    jsDir: 'js',
    srcImgDir: 'src/img',
    imgDir: 'img',
    styleEntryFile: 'src/scss/style.scss',
    tailwindBaseFile: 'src/scss/tailwind-base.css',
    compiledStyleFile: 'css/style.css'
  },
  browserSync: {
    proxy: 'https://xxx.wp',
    port: 3000,
    listen: 'localhost',
    files: [
      'css/style.css',
      'js',
      '**/*.php',
      '**/*.html',
      '!node_modules/**/*'
    ]
  }
};

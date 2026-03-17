const path = require('path');

module.exports = (env, argv = {}) => {
  const mode = argv.mode || 'production';
  const isDevelopment = mode === 'development';

  return {
    watch: isDevelopment,
    watchOptions: {
      ignored: /node_modules/
    },
    mode,
    devtool: 'source-map',

    // メインとなるJavaScriptファイル（エントリーポイント）複数作成
    entry: {
      bundle: './src/js/app.js',
      bundle_form: './src/js/app-form.js',
      // bundle_form_bkg: './src/js/app-form-bkg.js',
    },

    // ファイルの出力設定
    output: {
      //  出力ファイルのディレクトリ名 npm run用
      // path: __dirname + '/js',
      path: path.resolve(__dirname, 'js'),
      filename: '[name].js'
    },
    module: {
      rules: [{
        test: /\.js$/, // 拡張子 .js の場合
        exclude: /node_modules/,
        use: [
          {
            loader: 'babel-loader', // Babel を利用する
            options: {
              // プリセットを指定することで、ES2020 を ES5 に変換
              presets: [ '@babel/preset-env']
            }
          }
        ]
      }]
    },
    //jQueryをCDNから使う場合
    externals: {
      jquery: 'jQuery',
      Swiper: 'Swiper', // Swiperを外部ライブラリとして扱う
      gsap: 'gsap', // GSAPを外部ライブラリとして扱う
      ScrollTrigger: 'ScrollTrigger', // ScrollTriggerも外部ライブラリとして扱う
    }
  };
};
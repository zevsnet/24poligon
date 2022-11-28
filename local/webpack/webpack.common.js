const path = require('path')
const SvgPlugin = require('./plugins/svg')
const HtmlWebpackPlugin = require('html-webpack-plugin')
const HtmlWebpackHarddiskPlugin = require('html-webpack-harddisk-plugin')
const VueLoaderPlugin = require('vue-loader/lib/plugin')
const webpack = require('webpack')

module.exports = {
  entry: ['./src/main.js'],
  plugins: [
    new SvgPlugin({
      src: path.resolve(__dirname, 'src/img/icons'),
      className: 'svg',
      classPrefix: 'svg__'
    }),
    new HtmlWebpackPlugin({
      inject: false,
      template: 'src/index.php',
      filename: 'index.php',
      scripts: [
        'svg-loader.js'
      ],
      alwaysWriteToDisk: true
    }),
    new HtmlWebpackHarddiskPlugin({
      outputPath:path.resolve(__dirname, 'dist')
    }),
    new VueLoaderPlugin(),
    // Если нужна подключить библиотеки глобально
    // new webpack.ProvidePlugin({
    //   $: 'jquery',
    //   jQuery: 'jquery',
    //   'window.jQuery': 'jquery',
    //   Popper: ['popper.js', 'default'],
    //   'window.Popper': ['popper.js', 'default']
    // })
  ],
  resolve: {
    extensions: ['.js', '.vue', '.json'],
    alias: {
      'vue$': 'vue/dist/vue.esm.js',
      '@': path.resolve(__dirname, 'src')
    }
  },
  module: {
    rules: [
      {
        test: /\.vue$/,
        loader: 'vue-loader',
      },
      /** Babel for js and vue */
      {
        test: /\.js$/,
        exclude: file => /node_modules/.test(file) &&
          !/\.vue\.js/.test(file),
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['env'],
          },
        },
      },
    ],
  },
}
const path = require('path')
const merge = require('webpack-merge')
const common = require('./webpack.common.js')
const CleanWebpackPlugin = require('clean-webpack-plugin')
const MiniCssExtractPlugin = require("mini-css-extract-plugin")

/** НУЖНО УКАЗАТЬ ПУТЬ ДО ПАПКИ dist ИЗ КОРНЯ САЙТА (например '/local/webpack/dist/') */
const publicPath = '/local/webpack/dist/'

module.exports = merge.smart(common, {
  mode: 'production',
  output: {
    filename: '[name].[hash].js',
    chunkFilename: '[id].[hash].js',
    path: path.resolve(__dirname, 'dist'),
    publicPath: publicPath
  },
  plugins: [
    new CleanWebpackPlugin('dist', {}),
    new MiniCssExtractPlugin({
      filename: '[name].[hash].css',
      chunkFilename: '[id].[hash].css',
    }),
  ],
  module: {
    rules: [
      {
        test: /\.css$/,
        use: [
          MiniCssExtractPlugin.loader,
          'css-loader',
          {
            loader: 'postcss-loader', // add prefix
            options: {config: {path: './postcss.config.js'}},
          }
        ]
      },
      {
        test:  /\.scss$/,
        use: [
          MiniCssExtractPlugin.loader,
          'css-loader',
          {
            loader: 'postcss-loader', // add prefix
            options: {config: {path: './postcss.config.js'}},
          },
          'resolve-url-loader',
          'sass-loader?sourceMap'
        ]
      },
      {
        test: /\.(eot|woff?2|ttf|svg)$/i,
        use: [
          {
            loader: 'url-loader',
            options: {
              limit: 8064, // Convert images < 8kb to base64 strings
              name: 'font/[hash]-[name].[ext]',
              publicPath: publicPath,
            },
          }],
      },
      {
        test: /\.(gif|png|jpe?g)$/i,
        use: [
          {
            loader: 'url-loader',
            options: {
              limit: 8064, // Convert images < 8kb to base64 strings
              name: 'img/[hash]-[name].[ext]',
              publicPath: publicPath,
            },
          }],
      },
    ]
  }
})
const path = require('path')
const common = require('./webpack.common.js')
const merge = require('webpack-merge')
const webpack = require('webpack')
  const publicPath = 'http://localhost:8080/'

module.exports = merge.smart(common, {
  mode: 'development',
  devtool: 'inline-source-map',
  output: {
    filename: 'bundle.js',
    chunkFilename: '[name].[hash].bundle.js',
    path: path.resolve(__dirname, 'dist'),
    publicPath: publicPath,
  },
  devServer: {
    headers: {'Access-Control-Allow-Origin': '*'},
    allowedHosts: ['24poligon.ru']
  },
  plugins: [
    new webpack.HotModuleReplacementPlugin(),
  ],
  module: {
    rules: [
      {
        test: /\.css$/,
        use: [
          'style-loader',
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
          'style-loader',
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
    ],
  },
})


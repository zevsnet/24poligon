// TODO optimize
module.exports = {
  parser: 'postcss-syntax',
  syntax: 'postcss-syntax',
  stringifier: 'postcss-syntax',
  exec: false,
  plugins: {
    autoprefixer: {
      browsers: '> 0.2%'
    },
  },
}
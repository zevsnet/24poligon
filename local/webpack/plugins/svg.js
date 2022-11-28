const path = require('path')
const _ = require('lodash');
const fs = require('fs')
const webpackSources = require('webpack-sources')

// не понимаю как добавить в общий бандл, чтобы не требовалось его отдельно подключать в HtmlWebpackPlugin
module.exports = class SvgPlugin {
  constructor (options) {
    this.options = _.extend({
      className: 'svg-icon',
      classPrefix: 'svg-',
      separator: ', \n',
      escaper: /\\|'|\r|\n|\t/g,
      escapes: {
        '\'': '"',
        '\\': '\\',
        '\r': ' ',
        '\n': ' ',
        '\t': ' '
      },
    }, options);

    if (!this.options.src) {
      throw new Error('Не указан src для svg')
    }
  }

  apply (compiler) {
    compiler.hooks.emit.tapAsync('SvgPlugin', (compilation, callback) => {
      const concat = new webpackSources.ConcatSource()
      concat.add(fs.readFileSync(path.resolve(__dirname, 'svg-loader.js'), 'utf8'))

      const files = fs.readdirSync(this.options.src)
      this.svgList = files.reduce((result, file) => {
        if (file.substr(-4) === '.svg') {
          const name = file.substr(0, file.length - 4)

          let _this = this
          let source = fs.readFileSync(this.options.src +
            path.sep + file, 'utf8')

          source = source.replace(_this.options.escaper, function (match) {
            return _this.options.escapes[match]
          })
          result[name] = source
        }
        return result
      }, {})

      concat.add(this.getSource())

      compilation.assets['svg-loader.js'] = concat
      callback();
    })

  }

  getSource () {
    const svgListString = JSON.stringify(this.svgList)
    // language=JavaScript
    return `
            window.svgLoader = new SvgLoader({
                icons: ${svgListString},
                className: '${this.options.className}',
                classPrefix: '${this.options.classPrefix}'
            })
            `
  }
}



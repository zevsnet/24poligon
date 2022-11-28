'use strict'

function SvgLoader (options) {
  this.options = options
  this.options.classData = 'data-' + this.options.className
  this.init()
}

SvgLoader.prototype = {
  toArray: function (obj) {
    var array = []

    for (var i = (obj || []).length >>> 0; i--;) {
      array[i] = obj[i]
    }

    return array
  },
  classArray: function (node) {
    if (node.classList) {
      return this.toArray(node.classList)
    } else {
      return (node.getAttribute('class') || '').split(' ').filter(function (i) {
        return i
      })
    }
  },
  init: function () {
    var _this = this
    document.addEventListener('DOMContentLoaded', () => {
      _this.observe()
      _this.onTree(document)
    })
  },
  observe: function () {
    var _this = this
    var observer = new MutationObserver(function (objects) {
      _this.toArray(objects).forEach(function (mutationRecord) {
        if (mutationRecord.type === 'childList' && mutationRecord.addedNodes.length > 0 && !_this.isWatched(mutationRecord.addedNodes[0])) {
          _this.onTree(mutationRecord.target)
        }

        if (mutationRecord.type === 'attributes' && mutationRecord.attributeName === 'class') {
          _this.onNode(mutationRecord.target)
        }
      })
    })

    var config = {
      attributes: true,
      childList: true,
      characterData: false,
      subtree: true
    }

    observer.observe(document.getElementsByTagName('body')[0], config)
  },
  isWatched: function (node) {
    var svgIcon = node.getAttribute ? node.getAttribute(this.options.classData) : null
    return typeof svgIcon === 'string'
  },
  perform: function (mutations) {
    var _this = this
    if (mutations.length === 0) {
      return
    }

    mutations.map(function (mutation) {
      mutation.node.setAttribute(_this.options.classData, '')
      mutation.node.innerHTML = mutation.meta.content
    })
  },
  findSvgData: function (values) {
    var _this = this
    return values.reduce(function (acc, iconName) {
      var result = _this.findIconDataByClass(iconName)
      acc.iconName = result.iconName || acc.iconName
      acc.content = result.content || acc.content

      return acc
    }, {iconName: null, content: null})
  },
  findIconDataByClass: function (className) {
    var iconName = className.replace(this.options.classPrefix, '', className)
    var result = {
      iconName: null,
      content: null
    }
    if (this.options.icons[iconName] !== undefined) {
      result.iconName = iconName
      result.content = this.options.icons[iconName]
    }
    return result
  },
  generateMutation: function (node) {
    var nodeMeta = this.findSvgData(this.classArray(node))

    if (nodeMeta.iconName === null) {
      return
    }

    return {
      node: node,
      meta: nodeMeta
    }
  },
  onTree: function (root) {
    var _this = this
    var nodes = _this.toArray(root.querySelectorAll('.' + this.options.className + ':not([' + this.options.classData + '])'))

    if (nodes.length === 0) {
      return
    }

    var mutations = nodes.reduce(function (acc, node) {
      try {
        var mutation = _this.generateMutation(node)

        if (mutation) {
          acc.push(mutation)
        }
      } catch (e) {
        console.error(e)
      }

      return acc
    }, [])

    _this.perform(mutations)
  },
  onNode: function (node) {
    var mutation = this.generateMutation(node)

    if (mutation) {
      this.perform([mutation])
    }
  }
}

            window.svgLoader = new SvgLoader({
                icons: {"example":"<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>  <!-- Generator: Adobe Illustrator 19.0.0, SVG Export Plug-In . SVG Version: 6.00 Build 0)  -->  <svg version=\"1.1\" id=\"Capa_1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" x=\"0px\" y=\"0px\"    viewBox=\"0 0 356 356\" style=\"enable-background:new 0 0 356 356;\" xml:space=\"preserve\">  <g>   <g>    <path d=\"M178,0C79.693,0,0,79.693,0,178s79.693,178,178,178s178-79.693,178-178C355.868,79.748,276.252,0.132,178,0z M134,18     c-17.622,16.608-30.985,37.216-38.96,60.08c-13.247-3.217-26.22-7.47-38.8-12.72C77.237,42.631,104.171,26.227,134,18z      M48.08,74.8C61.967,81.084,76.367,86.169,91.12,90c-8.352,26.549-12.799,54.171-13.2,82v-0.24h-65.6     C13.553,136.445,26.087,102.458,48.08,74.8z M48.08,280.56c-21.993-27.658-34.527-61.645-35.76-96.96l65.6,0.16     c0.378,27.909,4.825,55.614,13.2,82.24C76.386,269.624,61.988,274.495,48.08,280.56z M56.24,290.48V290     c12.58-5.251,25.553-9.504,38.8-12.72c7.883,23.094,21.252,43.93,38.96,60.72C104.153,329.728,77.217,313.267,56.24,290.48z      M172,343.44c-26.88-3.44-50.4-29.52-65.36-68.24c21.525-4.333,43.405-6.663,65.36-6.96V343.44z M172,255.76     c-23.306,0.297-46.531,2.815-69.36,7.52C94.633,237.599,90.375,210.897,90,184v-0.24h82V255.76z M172,171.76H90     c0.306-27.139,4.564-54.089,12.64-80c22.813,4.866,46.038,7.545,69.36,8V171.76z M172,87.76     c-21.952-0.272-43.832-2.575-65.36-6.88C121.6,42,145.12,16,172,12.56V87.76z M307.92,75.28     c21.993,27.658,34.527,61.645,35.76,96.96h-65.6c-0.362-27.963-4.81-55.723-13.2-82.4C279.614,86.216,294.012,81.345,307.92,75.28     z M299.68,65.52c-12.58,5.25-25.553,9.503-38.8,12.72C252.951,55.326,239.614,34.662,222,18     C251.818,26.285,278.725,42.745,299.68,65.52z M184,12.56c26.88,3.44,50.4,29.52,65.36,68.24     c-21.525,4.332-43.405,6.662-65.36,6.96V12.56z M184,99.76c23.306-0.296,46.532-2.814,69.36-7.52     c8.076,25.911,12.334,52.861,12.64,80h-82V99.76z M184,184.24h82c-0.306,27.139-4.564,54.089-12.64,80     c-22.813-4.867-46.038-7.545-69.36-8V184.24z M184,343.44v-75.2c21.952,0.271,43.832,2.574,65.36,6.88     C234.4,314,210.88,340,184,343.44z M222,338c17.633-16.631,30.997-37.267,38.96-60.16c13.247,3.216,26.22,7.469,38.8,12.72     C278.773,313.318,251.838,329.751,222,338z M307.92,281.2c-13.887-6.284-28.286-11.37-43.04-15.2     c8.352-26.549,12.799-54.171,13.2-82v0.24h65.6C342.447,219.555,329.913,253.542,307.92,281.2z\"/>   </g>  </g>  <g>  </g>  <g>  </g>  <g>  </g>  <g>  </g>  <g>  </g>  <g>  </g>  <g>  </g>  <g>  </g>  <g>  </g>  <g>  </g>  <g>  </g>  <g>  </g>  <g>  </g>  <g>  </g>  <g>  </g>  </svg>  "},
                className: 'svg',
                classPrefix: 'svg__'
            })
            
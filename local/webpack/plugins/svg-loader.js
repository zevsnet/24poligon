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

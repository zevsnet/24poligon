<template>
    <input>
</template>

<script>
  /* eslint-disable no-trailing-spaces,spaced-comment */

  export default {
    name: 'masked-input',
    props: {
      mask: {
        type: String,
        required: false,
        default: '+7 (999) 999-9999'
      },
      parameters: {
        type: Object,
        default () {
          return {}
        }
      },
      value: {
        type: String,
        default: ''
      }
    },
    mounted () {
      this.maskInit()
    },
    data: function () {
      return {
        isComplete: false
      }
    },
    watch: {
      value: function (value) {
        window.$(this.$el).val(value)
        //this.maskInit()
      },
      isComplete: function (value) {
        this.$emit('input', window.$(this.$el).val())
      }
    },
    destroyed: function () {
      this.maskDestroy()
    },
    methods: {
      maskInit () {
        let vm = this
        let parameters = Object.assign({}, vm.parameters, {
          oncomplete () {
            vm.isComplete = true
            vm.$emit('input', window.$(this).val())
          },
          onincomplete () {
            vm.isComplete = false
            vm.$emit('input', window.$(this).val())
          }
        })

        window.$(vm.$el)
          .val(this.value)
          .inputmask(vm.mask, parameters)

        //фикс бага с отображением маски без значения
        if (this.value !== '') {
          window.$(vm.$el).trigger('input')
        }

        vm.isComplete = window.$(this).inputmask('isComplete')
      },
      maskDestroy () {
        window.$(this.$el).off().inputmask('remove')
      }
    }
  }
</script>
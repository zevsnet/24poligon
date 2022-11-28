<template>
    <input/>
</template>

<script>
  export default {
    name: 'jquery-input',
    mounted: function () {
      let vm = this
      window.$(this.$el)
        .trigger('change')
        .on('change', function () {
          vm.$emit('change', this.value)
        })
    }
  }
</script>
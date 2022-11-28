<template>
    <h1><span>Hello</span> {{initialData}} (VUE)</h1>
</template>

<script>
  /** Компонент Vue */
  export default {
    name: 'example',
    props: ['initialData'],
  }
</script >

<style lang="scss" scoped>
    h1 {
        color: #000;
        span {
            color: #fff;
        }
    }
</style>
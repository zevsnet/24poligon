<template>
  <div class="order-delivery_country-message">
    <div class="order-delivery_country-message-icon">!</div>
    <div class="order-delivery_country-message-text">{{ text }}</div>
  </div>
</template>

<script>
  export default {
    name: 'CountryMessage',
    data: function () {
      return {
        text: 'Мы осуществляем доставку в любую точку России'
      }
    }
  }
</script>

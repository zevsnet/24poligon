<template>
  <div v-if="getDeliveryCountrySelected !== undefined" class="order-delivery-country-selector">
    <div class="order-delivery-country-selector_title">
      Доставка по
    </div>
    <div class="order-delivery-country-selector_wrapper">
      <div class="order-delivery-country-selector_active">
        {{ getDeliveryCountrySelected.name }}
      </div>

    </div>
  </div>
</template>

<script>
  import {mapGetters, mapActions} from 'vuex'

  export default {
    name: 'CountrySelector',
    computed: {
      ...mapGetters('order', [
        'getDeliveryCountryList',
        'getDeliveryCountrySelected',
        'getDeliveryCountryUnselected'
      ])
    },
    methods: {
      ...mapActions('order', [
        'setDeliveryCountryById'
      ])
    }
  }
</script>

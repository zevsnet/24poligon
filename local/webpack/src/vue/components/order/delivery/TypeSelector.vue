<template>
  <div class="order-line-selector">
    <div
      v-for="item in getDeliveryTypes"
      :key="item.id"
      :class="{active: item.selected}"
      class="order-line-selector_item"
      @click="onClick(item.id)">
      {{ item.name }}
    </div>
  </div>
</template>

<script>
  import {mapGetters, mapActions} from 'vuex'

  export default {
    name: 'TypeSelector',
    computed: {
      ...mapGetters('order', [
        'getDeliveryTypes',
        'getDeliveryTypeSelected'
      ])
    },
    methods: {
      ...mapActions('order', [
        'setDeliveryTypeById'
      ]),
      onClick: function (id) {
        if (this.getDeliveryTypeSelected.id !== id) {
          this.setDeliveryTypeById(id)
        }
      }
    }
  }
</script>

<template>
  <div
    class="order-delivery-list_item"
    :class="{selected: item.selected}"
    @click="onClick">
    <div class="order-delivery-list_item-col-info">
      <div class="name">{{ item.name }}</div>
      <!--      Отчистить регулярками на сервере-->
      <div class="description">{{ item.description }}</div>
    </div>
    <div class="order-delivery-list_item-col-price">
      {{ getLocalPrice }}
    </div>
  </div>
</template>

<script>
  import localPrice from '../../../utils/localPrice'
  import {mapGetters, mapActions} from 'vuex'

  export default {
    name: 'DeliveryListItem',
    props: {
      item: {
        type: Object,
        default: () => {
        }
      }
    },
    computed: {
      ...mapGetters('order', [
        'getDeliveryItemSelected'
      ]),
      getLocalPrice: function () {
        if (this.item.price > 0) {
          return localPrice(this.item.price)
        } else {
          return 'бесплатно'
        }
      }
    },
    methods: {
      ...mapActions('order', [
        'setDeliveryItemById'
      ]),
      onClick: function () {
        if (this.getDeliveryItemSelected !== this.item.id) {
          this.setDeliveryItemById(this.item.id)
        }
      }
    }
  }
</script>

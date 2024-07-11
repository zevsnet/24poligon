<template>
  <div class="order-delivery-list">
    <el-row :gutter="20" v-if="Object.keys(getDeliveryItems).length > 0">
      <el-col
        v-for="item in getDeliveryItems"
        :sm="8"
        :xs="24"
        :key="item.id">
        <DeliveryListItem :item="item"/>
      </el-col>
    </el-row>
    <div class="order-delivery-list_error" v-else>
      В выбранный город не осуществляется данный тип доставки
    </div>
  </div>
</template>

<script>
  import {mapGetters} from 'vuex'
  import DeliveryListItem from './DeliveryListItem'

  export default {
    name: 'DeliveryList',
    components: {
      DeliveryListItem
    },
    computed: {
      ...mapGetters('order', [
        'getDeliveryItems'
      ])
    }
  }
</script>

<template>
  <el-col
    :xs="24"
    :sm="8">
    <div class="order-delivery-fields_item">
      <div class="order-delivery-fields_item-title">
        Квартира/офис
      </div>
      <el-input
        placeholder="Квартира/офис"
        v-model="display"
        class="order-delivery-fields_item-input"/>
    </div>
  </el-col>
</template>

<script>
  import {mapGetters, mapMutations} from 'vuex'

  export default {
    name: 'Flat',
    computed: {
      ...mapGetters('order', [
        'getDeliveryFieldByCode'
      ]),
      display: {
        get: function () {
          return this.getDeliveryFieldByCode('FLAT').value
        },
        set: function (val) {
          // TODO: validate
          this.setDeliveryFiledValueByCode({
            code: 'FLAT',
            value: val
          })
        }
      }
    },
    methods: {
      ...mapMutations('order', [
        'setDeliveryFiledValueByCode'
      ])
    }
  }
</script>

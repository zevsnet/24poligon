<template>
  <el-col
    :xs="24"
    :sm="24">
    <div class="order-delivery-fields_item">
      <div class="order-delivery-fields_item-title">
        {{ title }}
      </div>
      <textarea
        v-model="address"
        class="order-delivery-fields_item-textarea"
        name="delivery-address"
        id="delivery-address"></textarea>
    </div>
  </el-col>
</template>

<script>
  import {mapActions, mapGetters, mapMutations} from 'vuex'

  export default {
    name: 'Address',
    props: {
      code: {
        type: String,
        required: true
      },
      title: {
        type: String,
        required: true
      }
    },
    computed: {
      ...mapGetters('order', [
        'getDeliveryFieldByCode'
      ]),
      address: {
        get: function () {
          const value = this.getDeliveryFieldByCode(this.code).value
          this.validateRussianPostDelivery()
          return value
        },
        set: function (val) {
          this.setDeliveryFiledValueByCode({
            code: this.code,
            value: val
          })
        }
      }
    },
    methods: {
      ...mapMutations('order', [
        'setDeliveryFiledValueByCode',
        'setValidateDelivery'
      ]),
      ...mapActions('order', [
        'validateRussianPostDelivery'
      ])
    }
  }
</script>

<template>
  <el-col
    :xs="24"
    :sm="8">
    <div class="order-delivery-fields_item">
      <div class="order-delivery-fields_item-title">Желаемая дата доставки</div>
      <el-select v-model="display">
        <el-option
          v-for="item in options"
          :key="item.value"
          :label="item.label"
          :value="item.value"
          class="order-delivery-fields_item-input"
        >
        </el-option>
      </el-select>
    </div>
  </el-col>
</template>

<script>
  import {mapGetters, mapMutations} from 'vuex'

  export default {
    name: 'Datedelivery',
    data: function () {
      return {
        options: [
          { value: 'today', label: 'сегодня' },
          { value: 'tomorrow', label: 'завтра' },
          { value: 'after_tomorrow', label: 'послезавтра' }
        ],
        value: ''
      }
    },
    computed: {
      ...mapGetters('order', [
        'getDeliveryFieldByCode'
      ]),
      display: {
        get: function () {
          return this.getDeliveryFieldByCode('DELIVERY_REQUIRED_DATE').value
        },
        set: function (val) {
          // TODO: validate
          this.setDeliveryFiledValueByCode({
            code: 'DELIVERY_REQUIRED_DATE',
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

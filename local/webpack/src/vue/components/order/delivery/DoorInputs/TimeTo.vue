<template>
  <el-col
    :xs="24"
    :sm="8">
    <div class="order-delivery-fields_item">
      <div class="order-delivery-fields_item-title">Желаемое время доставки от</div>
      <el-select v-model="display" placeholder="Время от">
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
    name: 'TimeTo',
    data: function () {
      return {
        options: [
          { value: '09:00', label: '09:00' },
          { value: '10:00', label: '10:00' },
          { value: '11:00', label: '11:00' },
          { value: '12:00', label: '12:00' },
          { value: '13:00', label: '13:00' },
          { value: '14:00', label: '14:00' },
          { value: '15:00', label: '15:00' },
          { value: '16:00', label: '16:00' },
          { value: '17:00', label: '17:00' },
          { value: '18:00', label: '18:00' },
          { value: '19:00', label: '19:00' },
          { value: '20:00', label: '20:00' },
          { value: '21:00', label: '21:00' },
          { value: '22:00', label: '22:00' },
          { value: '23:00', label: '23:00' }

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
          return this.getDeliveryFieldByCode('DELIVERY_REQUIRED_START_TIME').value
        },
        set: function (val) {
          // TODO: validate
          this.setDeliveryFiledValueByCode({
            code: 'DELIVERY_REQUIRED_START_TIME',
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

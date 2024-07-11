<template>
  <div class="order-delivery-type">
    <div class="order-delivery-fields">
      <el-row :gutter="20">
        <Location/>
        <CheckBoxZip/>
        <Address :title="'Продублируйте индекс и населенный пункт'" :code="'ADDRESS_DOUBLE_RUSSIAN_POST'"/>
        <Address :title="'Введите адрес'" :code="'ADDRESS_RUSSIAN_POST'"/>
      </el-row>
    </div>
    <DeliveryList/>
    <ErrorMessage
      v-if="!getValidateDelivery"
      :errors="error"
      :class-list="['delivery-errors']"/>
  </div>
</template>

<script>
  import Location from './RussianPostInputs/Location'
  import CheckBoxZip from './RussianPostInputs/CheckBoxZip'
  import Address from './RussianPostInputs/Address'
  import DeliveryList from './DeliveryList'
  import ErrorMessage from '../ErrorMessage'
  import {mapGetters} from 'vuex'

  export default {
    name: 'RussianPostType',
    components: {
      ErrorMessage,
      Location,
      CheckBoxZip,
      Address,
      DeliveryList
    },
    data: function () {
      return {
        error: ['<div>Заполните поле: <b>Адрес</b> и <b>Продублируйте индекс и населенный пункт</b></div>']
      }
    },
    computed: {
      ...mapGetters('order', [
        'getValidateDelivery'
      ])
    }
  }
</script>

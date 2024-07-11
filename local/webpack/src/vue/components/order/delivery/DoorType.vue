<template>
  <div class="order-delivery-type">
    <div class="order-delivery-fields">
      <el-row :gutter="20">
        <Street/>
        <House/>
        <Flat/>
      </el-row>
    </div>
    <DeliveryList v-if="isHouseValidate"/>
    <div class="order-delivery-fields" v-if="isDeliveryDostavista">
      <el-row :gutter="20">
        <Datedelivery/>
        <TimeTo/>
        <TimeFor/>
      </el-row>
    </div>
    <ErrorMessage
      v-if="!isValidate"
      :errors="getErrors"
      :class-list="['delivery-errors']"/>
  </div>
</template>

<script>
  import {mapGetters, mapMutations} from 'vuex'
  import DeliveryList from './DeliveryList'
  import Location from './DoorInputs/Location'
  import Street from './DoorInputs/Street'
  import House from './DoorInputs/House'
  import Flat from './DoorInputs/Flat'
  import Datedelivery from './DoorInputs/Datedelivery'
  import TimeTo from './DoorInputs/TimeTo'
  import TimeFor from './DoorInputs/TimeFor'
  import ErrorMessage from './../ErrorMessage'
  import validation from '../../../utils/validation'

  export default {
    name: 'DoorType',
    components: {
      DeliveryList,
      Location,
      Street,
      House,
      Flat,
      Datedelivery,
      TimeTo,
      TimeFor,
      ErrorMessage
    },
    computed: {
      ...mapGetters('order', [
        'getDeliveryFieldByCode',
        'getDeliveryItemSelected'
      ]),
      isLocationValidate: function () {
        let value = this.getDeliveryFieldByCode('LOCATION').value
        let code = this.getDeliveryFieldByCode('LOCATION').data.location
        return validation('strLength')(value, 5) && validation('strLength')(code, 5)
      },
      isStreetValidate: function () {
        let value = this.getDeliveryFieldByCode('STREET').value
        return validation('strLength')(value, 2)
      },
      isHouseValidate: function () {
        let value = this.getDeliveryFieldByCode('HOUSE').value
        return validation('strLength')(value, 1)
      },
      isFlatValidate: function () {
        let value = this.getDeliveryFieldByCode('FLAT').value
        return validation('strLength')(value, 1)
      },
      isDeliveryDostavista: function () {
        let res = false
        if (this.isFlatValidate && this.getDeliveryItemSelected) {
          if (this.getDeliveryItemSelected.id === 31) {
            res = true
          }
        }
        return res
      },
      isValidate: function () {
        let isValidate = this.isLocationValidate &&
            this.isStreetValidate &&
            this.isHouseValidate &&
            this.isFlatValidate
        this.setValidateDelivery(isValidate)
        return isValidate
      },
      getErrors: function () {
        let errors = []
        if (!this.isLocationValidate) {
          errors.push('<div>Заполните поле: <b>Город</b></div>')
        }
        if (!this.isStreetValidate) {
          errors.push('<div>Заполните поле: <b>Улица</b></div>')
        }
        if (!this.isHouseValidate) {
          errors.push('<div>Заполните поле: <b>Дом</b></div>')
        }
        if (!this.isFlatValidate) {
          errors.push('<div>Заполните поле: <b>Квартира/офис</b></div>')
        }
        return errors
      }
    },
    methods: {
      ...mapMutations('order', [
        'setValidateDelivery'
      ])
    }
  }
</script>

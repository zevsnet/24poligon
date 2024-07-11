<template>
  <div class="order-delivery-type">
    <DeliveryList/>
    <PickupTypeMap v-if="typeof getDeliveryPickupItems === 'object'  && getDeliveryItemSelected"/>
<!--    <ErrorMessage v-if="!isHideErrors" :errors="getErrors" :class-list="['delivery-errors']"/>-->
  </div>
</template>

<script>
  import {mapGetters, mapMutations, mapActions} from 'vuex'
  import PickupTypeMap from './PickupTypeMap'
  import DeliveryList from './DeliveryList'
  import debounce from 'lodash/debounce'
  import map from 'lodash/map'
  import axios from 'axios'
  // import ErrorMessage from './../ErrorMessage'
  // import validation from '../../../utils/validation'

  export default {
    name: 'PickUpType',
    components: {
      PickupTypeMap,
      DeliveryList,
      // ErrorMessage
    },
    computed: {
      ...mapGetters('order', [
        'getDeliveryPickupItems',
        'getDeliveryCountrySelected',
        'getDeliveryFieldByCode',
        'getDeliveryItemSelected'
      ]),
      displayCity: {
        get: function () {
          return this.getDeliveryFieldByCode('LOCATION').value
        },
        set: function (val) {
          this.setDeliveryFiledValueByCode({
            code: 'LOCATION',
            value: val
          })
        }
      },
      isHideErrors: function () {
        let value = this.getDeliveryFieldByCode('LOCATION').value
        let code = this.getDeliveryFieldByCode('LOCATION').data.location
        let isValidate = validation('strLength')(value, 5) && validation('strLength')(code, 5)
        this.setValidateDelivery(isValidate)
        return isValidate
      },
      getErrors: function () {
        return ['<div>Заполните поле: <b>Город</b></div>']
      },
      shortName: function () {
        return this.getDeliveryFieldByCode('LOCATION').data.shortName
      }
    },
    data: function () {
      return {
        minLength: 2
      }
    },
    methods: {
      ...mapMutations('order', [
        'setDeliveryFiledDataByCode',
        'setDeliveryFiledValueByCode',
        'setValidateDelivery'
      ]),
      ...mapActions('order', [
        'updateDeliveryFields'
      ]),
      getSuggestions: debounce(async function (queryString, cb) {
        if (queryString.length < this.minLength) {
          cb([])
          return
        }
        const response = await axios.post('/order/getCitySuggestion', {
          query: queryString,
          countryId: this.getDeliveryCountrySelected.id,
          countryIso: this.getDeliveryCountrySelected.iso
        })
        cb(map(response.data, e => {
          return {
            value: e.DISPLAY,
            id: e.ID,
            name: e.LOCATION_NAME,
            code: e.CODE
          }
        }))
      }, 500),
      selectCity: function (item) {
        this.setDeliveryFiledDataByCode({
          code: 'LOCATION',
          data: {
            location: item.code
          }
        })
        this.updateDeliveryFields()
      },
      onClear: function () {
        this.displayCity = ''
      }
    }
  }
</script>

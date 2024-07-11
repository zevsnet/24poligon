<template>
  <el-col
    :xs="24"
    :sm="24">
    <div class="order-delivery-fields_item">
      <div class="order-delivery-fields--title"><img src="https://zoostore.pro/images/order/region.png" alt="region">Выберите город</div>
      <el-autocomplete
        v-model="displayLocation"
        :fetch-suggestions="getSuggestions"
        placeholder="Город"
        class="order-delivery-fields_item-input"
        @select="selectCity"
      >
        <template v-slot:suffix>
          <div class="order-delivery-fields_item-input-clear" @click="onClear">
            ×
          </div>
        </template>
      </el-autocomplete>
      <div class="order-delivery-fields_location-name">Выбран город: <span>{{ shortName }}</span>
      </div>
    </div>
  </el-col>
</template>

<script>
  import {mapGetters, mapMutations, mapActions} from 'vuex'
  import debounce from 'lodash/debounce'
  import map from 'lodash/map'
  import axios from 'axios'

  export default {
    name: 'Location',
    data: function () {
      return {
        minLength: 2
      }
    },
    computed: {
      ...mapGetters('order', [
        'getDeliveryCountrySelected',
        'getDeliveryFieldByCode',
        'getDeliveryTypeSelected'
      ]),
      displayLocation: {
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
      shortName: function () {
        return this.getDeliveryFieldByCode('LOCATION').data.shortName
      }
    },
    methods: {
      ...mapMutations('order', [
        'setDeliveryFiledDataByCode',
        'setDeliveryFiledValueByCode',
        'setDeliveryServiceFiledValueByCode'
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
      isDoorDelivery: function () {
        if (this.getDeliveryTypeSelected) {
          return this.getDeliveryTypeSelected.code === 'door'
        }
        return false
      },
      selectCity: function (item) {
        if (this.isDoorDelivery) {

        } else {
          this.setDeliveryFiledValueByCode({
            code: 'STREET',
            value: ''
          })

          this.setDeliveryFiledValueByCode({
            code: 'HOUSE',
            value: ''
          })

          this.setDeliveryFiledValueByCode({
            code: 'FLAT',
            value: ''
          })
        }
        this.setDeliveryFiledDataByCode({
          code: 'LOCATION',
          data: {
            location: item.code
          }
        })

        this.setDeliveryServiceFiledValueByCode({
          code: 'ZIP',
          value: ''
        })
        this.updateDeliveryFields()
      },
      onClear: function () {
        this.displayLocation = ''
        if (this.isDoorDelivery) {

        } else {
          this.setDeliveryFiledValueByCode({
            code: 'STREET',
            value: ''
          })

          this.setDeliveryFiledValueByCode({
            code: 'HOUSE',
            value: ''
          })

          this.setDeliveryFiledValueByCode({
            code: 'FLAT',
            value: ''
          })
        }

        this.setDeliveryServiceFiledValueByCode({
          code: 'ZIP',
          value: ''
        })
      }
    }
  }
</script>

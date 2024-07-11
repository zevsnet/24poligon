<template>
  <el-col
    :xs="24"
    :sm="8">
    <div class="order-delivery-fields_item">
      <div class="order-delivery-fields_item-title">
        Улица
      </div>
      <el-autocomplete
        v-model="display"
        :fetch-suggestions="getSuggestions"
        placeholder="Улица"
        class="order-delivery-fields_item-input"
        @select="onSelect"
      ></el-autocomplete>
    </div>
  </el-col>
</template>

<script>
  import {mapGetters, mapMutations} from 'vuex'
  import debounce from 'lodash/debounce'
  import map from 'lodash/map'
  import axios from 'axios'

  export default {
    name: 'Street',
    data: function () {
      return {
        minLength: 4
      }
    },
    computed: {
      ...mapGetters('order', [
        'getDeliveryCountrySelected',
        'getDeliveryFieldByCode'
      ]),
      display: {
        get: function () {
          return this.getDeliveryFieldByCode('STREET').value
        },
        set: function (val) {
          this.setDeliveryFiledValueByCode({
            code: 'STREET',
            value: val
          })
        }
      }
    },
    methods: {
      ...mapMutations('order', [
        'setDeliveryFiledValueByCode',
        'setDeliveryServiceFiledValueByCode'
      ]),
      getSuggestions: debounce(async function (queryString, cb) {
        if (queryString.length < this.minLength) {
          cb([])
          return
        }
        const response = await axios.post('/order/getStreetSuggestion', {
          query: queryString,
          countryIso: this.getDeliveryCountrySelected.iso,
          city: this.getDeliveryFieldByCode('LOCATION').data.shortName
        })
        cb(map(response.data, e => {
          let tmpName = ''
          if (e.data.settlement_with_type) {
            tmpName += e.data.settlement_with_type
          }

          tmpName += e.data.city_with_type + ' ' + e.data.street_with_type
          return {
            value: tmpName,
            shortName: e.data.street
          }
        }))
      }, 500),
      onSelect: function (item) {
        this.setDeliveryFiledValueByCode({
          code: 'STREET',
          value: item.shortName
        })

        this.setDeliveryFiledValueByCode({
          code: 'HOUSE',
          value: ''
        })

        this.setDeliveryFiledValueByCode({
          code: 'FLAT',
          value: ''
        })

        this.setDeliveryServiceFiledValueByCode({
          code: 'ZIP',
          value: ''
        })
      }
    }
  }
</script>

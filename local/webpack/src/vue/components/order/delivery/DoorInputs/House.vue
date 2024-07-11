<template>
  <el-col
    :xs="24"
    :sm="8">
    <div class="order-delivery-fields_item">
      <div class="order-delivery-fields_item-title">
        Дом
      </div>
      <el-autocomplete
        v-model="display"
        :fetch-suggestions="getSuggestions"
        placeholder="Дом"
        class="order-delivery-fields_item-input"
        @select="onSelect"
      ></el-autocomplete>
    </div>
  </el-col>
</template>

<script>
  import {mapGetters, mapMutations, mapActions} from 'vuex'
  import debounce from 'lodash/debounce'
  import map from 'lodash/map'
  import axios from 'axios'

  export default {
    name: 'House',
    data: function () {
      return {
        minLength: 1
      }
    },
    computed: {
      ...mapGetters('order', [
        'getDeliveryCountrySelected',
        'getDeliveryFieldByCode'
      ]),
      display: {
        get: function () {
          return this.getDeliveryFieldByCode('HOUSE').value
        },
        set: function (val) {
          this.setDeliveryFiledValueByCode({
            code: 'HOUSE',
            value: val
          })
        }
      }
    },
    methods: {
      ...mapActions('order', [
        'checkUpdateFields'
      ]),
      ...mapMutations('order', [
        'setDeliveryFiledValueByCode',
        'setDeliveryServiceFiledValueByCode'
      ]),
      getSuggestions: debounce(async function (queryString, cb) {
        if (queryString.length < this.minLength) {
          cb([])
          return
        }
        const response = await axios.post('/order/getHouseSuggestion', {
          query: queryString,
          countryIso: this.getDeliveryCountrySelected.iso,
          city: this.getDeliveryFieldByCode('LOCATION').data.shortName,
          street: this.getDeliveryFieldByCode('STREET').value
        })
        cb(map(response.data, e => {
          return {
            value: e.data.house,
            postalCode: e.data.postal_code
          }
        }))
      }, 500),
      onSelect: function (item) {
        this.setDeliveryFiledValueByCode({
          code: 'HOUSE',
          value: item.value
        })

        this.setDeliveryFiledValueByCode({
          code: 'FLAT',
          value: ''
        })

        this.setDeliveryServiceFiledValueByCode({
          code: 'ZIP',
          value: item.postalCode
        })

        this.checkUpdateFields()
      }
    }
  }
</script>

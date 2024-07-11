<template>
  <el-col
    :xs="24"
    :sm="24">
    <div class="order-delivery-fields_item">
      <div class="order-delivery-fields_item-title">
        Введите индекс
      </div>
      <el-autocomplete
        id="russian-post-zip"
        ref="autocomplete"
        v-model="getText"
        :fetch-suggestions="getSuggestions"
        :minlength="3"
        :maxlength="6"
        :triggerOnFocus="false"
        @focus="onFocus"
        placeholder="Индекс"
        class="order-delivery-fields_item-input"
        @select="onSelect"
        @focusout="onUnFocus"
      ></el-autocomplete>
      <div v-if="isShowCheckMessage" class="order-delivery-fields_item-error-location">
        Выберите населенный пункт из выпадающего списка
      </div>
    </div>
  </el-col>
</template>

<script>
import debounce from 'lodash/debounce'
import axios from 'axios'
import { mapGetters, mapMutations, mapActions } from 'vuex'

export default {
  name: 'Location',
  data: function () {
    return {
      locationText: '',
      zip: '',
      saveZip: '',
      isFocus: false,
      isShowCheckMessage: false
    }
  },
  computed: {
    ...mapGetters('order', [
      'getDeliveryFieldByCode'
    ]),
    getText: {
      get: function () {
        if (this.isFocus) {
          return this.zip
        } else {
          return this.locationText
        }
      },
      set: function (val) {
        this.zip = val
      }
    }
  },
  mounted: function () {
    window.$(window.document).on('focusout', '#russian-post-zip', () => this.onUnFocus())
    this.saveZip = this.getDeliveryFieldByCode('ZIP').value
    this.locationText = this.getDeliveryFieldByCode('LOCATION').value
  },
  methods: {
    ...mapMutations('order', [
      'setDeliveryFiledValueByCode',
      'setDeliveryFiledDataByCode'
    ]),
    ...mapActions('order', [
      'updateDeliveryFields'
    ]),
    onSelect: function (item) {
      this.isShowCheckMessage = false
      this.locationText = item.value
      this.zip = item.zip
      this.saveZip = item.zip

      this.setDeliveryFiledValueByCode({
        code: 'ZIP',
        value: item.zip
      })

      this.setDeliveryFiledDataByCode({
        code: 'LOCATION',
        data: {
          location: item.code
        }
      })

      this.updateDeliveryFields()
    },
    getSuggestions: debounce(async function (query, cb) {
      const response = await axios.post('/order/getZipSuggestion', {
        zip: query
      })
      cb(response.data)
    }, 500),
    onFocus: function () {
      this.zip = this.saveZip
      this.isFocus = true
    },
    onUnFocus: function () {
      this.isFocus = false
      this.isShowCheckMessage = true
    }
  }
}
</script>

<template>
    <el-autocomplete style="width: 100%"
                     v-model="text"
                     :fetch-suggestions="onFetch"
                     :placeholder="placeholder"
                     :trigger-on-focus="false"
                     @select="onSelect"
                     auto-complete="false"
                     @blur="onBlur"
    ></el-autocomplete>
</template>

<script>
  /* eslint-disable keyword-spacing */
  import _ from 'lodash'
  import AddressInput from './AddressInput'
  import { mapMutations } from 'vuex'

  export default {
    extends: AddressInput,
    name: 'location-input',
    data () {
      return {
        text: '',
        textInstance: ''
      }
    },
    props: {
      placeholder: {
        type: String,
        default: ''
      },
      valueType: {
        type: String,
        default: 'code',
        validator (value) {
          return ['code', 'id'].indexOf(value) !== -1
        }
      }
    },
    methods: {
      ...mapMutations([
        'setCityName'
      ]),
      getNameByCode: function (code) {
        this.text = this.textInstance = ''
        let params = new FormData()
        params.append('code', code)
        this.$http.post('/ajax/Main/getLocationNameByCode/', params)
          .then((response) => {
            if (response.data.result) {
              this.text = this.textInstance = response.data.result
            } else {
              this.value = ''
            }
          })
          .catch((error) => {
            console.log(error)
          })
      },
      onBlur (ev) {
        if (ev.explicitOriginalTarget === undefined || ev.explicitOriginalTarget.data === undefined) {
          this.text = this.textInstance
        }
      }
    },
    watch: {
      value: {
        handler (value) {
          value = _.toString(value)
          this.getNameByCode(value)
        },
        immediate: true
      },
      textInstance: {
        handler (value) {
          this.setCityName(value)
        },
        immediate: true
      }
    }
  }
</script>
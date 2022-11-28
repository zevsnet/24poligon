<template>
    <el-autocomplete style="width: 100%"
                     v-model="value"
                     :fetch-suggestions="onFetch"
                     :trigger-on-focus="false"
                     @select="onSelect"
    ></el-autocomplete>
</template>

<script>
  /* eslint-disable keyword-spacing */
  import _ from 'lodash'
  import BaseInput from './BaseInput'
  import {mapGetters} from 'vuex'

  export default {
    extends: BaseInput,
    name: 'address-input',
    props: {
      additionalData: {
        type: Object,
        default() {
          return {}
        }
      },
      minLength: {
        type: Number,
        default: 3
      }
    },
    computed: {
      ...mapGetters([
        'countryName'
      ])
    },
    methods: {
      getList: function(query) {
        let params = new FormData()
        params.append('query', query)
        params.append('locations[country]', this.countryName)

        _.forEach(this.additionalData, (element, key) => {
          params.append('locations[' + key + ']', element)
        })
        return this.$http.post('/ajax/daDataAddress/', params)
      },
      onFetch: _.debounce(function(query, callback) {
        if (query.length < this.minLength) {
          callback([])
          return
        }

        this.getList(query).then(function(response) {
          callback(_.reduce(response.data.result.suggestions, (result, suggestion) => {
            result.push({
              id: suggestion.unrestricted_value,
              value: suggestion.value,
              data: suggestion.data
            })
            return result
          }, []))
        }).catch(function(error) {
          console.log(error)
          callback([])
        })
      }, 500),
      onSelect(item) {
        this.$emit('select', item)
      }
    }
  }
</script>
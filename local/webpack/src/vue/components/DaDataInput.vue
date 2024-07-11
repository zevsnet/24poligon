<template>
    <el-autocomplete
            class="sb-input-dadata"
            v-model="value"
            :fetch-suggestions="onFetch"
            @select="onSelect"
            :placeholder="placeholder"
            :id="'ORDER_PROP_' + property.ID"
            :required="isRequired"
            auto-complete="false"
            :trigger-on-focus="false"
    ></el-autocomplete>
</template>

<script>
    import _ from 'lodash'
    import BaseInput from './BaseInput'
    import axios from 'axios'
    import map from 'lodash/map'

    export default {
        extends: BaseInput,
        name: 'da-data-input',
        props: {
            ajaxPath: {
                type: String,
                required: true
            },
            minLength: {
                type: Number,
                default: 4
            },
            placeholder: {
                default: ''
            }
        },
        methods: {

            onFetch: _.debounce(function (queryString, callback) {
                if (queryString.length < this.minLength) {
                    callback([])
                    return
                }
                axios({
                    method: 'post',
                    url: '/bitrix/services/main/ajax.php?action=poligon:core.sale.dadatacity&city=' + queryString,

                }).then(function (response) {
                    callback(map(response.data.data, e => {
                        return {
                            value: e.DISPLAY,
                            id: e.ID,
                            name: e.LOCATION_NAME,
                            code: e.CODE,
                            zip: e.ZIP
                        }
                    }))

                }).catch(function (error) {
                });


            }, 500),
            onSelect(item) {

                this.$emit('select', item)
            }
        }
    }
</script>
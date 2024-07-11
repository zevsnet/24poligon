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
        name: 'da-data-input-address',
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
                var city = $('#ORDER_PROP_60').val();

                axios({
                    method: 'post',
                    url: '/bitrix/services/main/ajax.php?action=poligon:core.sale.dadataaddress&address=' + queryString + '&city=' + city,

                }).then(function (response) {

                    callback(map(response.data.data, e => {
                        return {
                            value: e.value,
                            name: e.value,
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
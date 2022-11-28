<template>
    <div>
        <el-autocomplete style="width: 100%"
                         v-model="text"
                         :fetch-suggestions="onFetch"
                         :placeholder="placeholder"
                         :trigger-on-focus="false"
                         @select="onSelect"
                         auto-complete="false"
                         @blur="onBlur"
        ></el-autocomplete>
    </div>
</template>

<script>
    import _ from 'lodash'
    import axios from 'axios'
    import {mapGetters, mapMutations, mapState} from 'vuex'

    export default {
        name: 'orderLocationInput',
        props: {
            property: {
                type: Object,
                required: true
            },
            placeholder: {
                type: String,
                default: ''
            }
        },
        data: function () {
            return {
                text: '',
                lastLocation: '',
                openModal: false
            }
        },
        computed: {
            ...mapState([
                'service'
            ]),
            ...mapGetters([
                'getPropertyValueByCode',
                'getPropertyIdByCode',
                'countryName',
                'getOrderValuesAddition'
            ])
        },
        methods: {
            getList: function (query) {

                let params = new FormData()
                params.append('query', query)
                params.append('country[id]', this.service.countryId)
                params.append('country[name]', this.countryName)

                _.forEach(this.additionalData, (element, key) => {
                    params.append('locations[' + key + ']', element)
                })

                return axios({
                    method: 'post',
                    url: '/ajax/Main/bitrixAddress/',
                    data: params,
                })
            },
            onFetch: _.debounce(function (query, callback) {
                if (query.length < this.minLength) {
                    callback([])
                    return
                }

                this.getList(query)
                    .then(function (response) {

                        callback(_.reduce(response.data.data.suggestions, (result, suggestion) => {
                            result.push({
                                id: suggestion.CODE,
                                value: suggestion.DISPLAY,
                                data: suggestion
                            })
                            return result
                        }, []))
                    })
                    .catch(function (error) {
                        console.log(error)
                        callback([])
                    })
            }, 500),
            onBlur(ev) {
                if (ev.explicitOriginalTarget === undefined || ev.explicitOriginalTarget.data === undefined) {
                    this.text = this.lastLocation
                }
            },
            getNameByCode(code) {
                console.log(code)
                this.text = this.lastText = ''
                if (code != '') {
                    let params = new FormData()
                    params.append('code', code)

                    axios({
                        method: 'post',
                        url: '/ajax/Main/getLocationNameByCode/',
                        data: params,
                        // config: {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}
                    }).then((response) => {
                        if (response.data.data) {
                            this.text = this.lastText = response.data.data.DISPLAY
                            this.setRegionName(response.data.data.LOCATION_NAME)
                            this.triggeredOrder()
                        } else {
                            this.text = ''
                        }
                    }).catch((error) => {
                        console.log(error)
                    })
                }
            },
            onSelect(item) {
                this.text = item.data.DISPLAY
                this.lastLocation = item.data.DISPLAY

                this.setOrderPropertyValue({
                    id: this.getPropertyIdByCode('LOCATION'),
                    value: item.id
                })
                this.setRegionName(item.data['LOCATION_NAME'])
                this.setOrderPropertyValue({
                    id: this.getPropertyIdByCode('ADDRESS'),
                    value: ''
                })
                if (item.data.ZIP) {
                    this.setOrderPropertyValue({
                        id: this.getPropertyIdByCode('ZIP'),
                        value: item.data.ZIP
                    })
                    this.setOrderValuesAddition({...this.getOrderValuesAddition, ZIP_PROPERTY_CHANGED: 'Y'})
                }
                this.triggeredOrder()
                this.$emit('select', item)
            },
            ...mapMutations([
                'setOrderPropertyValue',
                'triggeredOrder',
                'setOrderValuesAddition',
                'setRegionName'
            ])
        }
    }
</script>

<style scoped lang="scss">
    $green: #00b770;

    .not-found {
        text-decoration: underline;
        font-size: 25px;
        color: $green;
        display: inline-block;
        margin-left: 10px;
        font-weight: 600;
    }
</style>

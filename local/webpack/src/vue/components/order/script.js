import _ from 'lodash'
import common from '../../mixins/common'

import axios from 'axios'

import TypeDelivery from '../TypeDelivery'
import DataDelivery from '../DataDelivery'
import DataDeliveryPickup from '../DataDeliveryPickup'
import PaymentList from '../PaymentList'
import PaymentListCash from '../PaymentListCash'
import PaymentInner from '../PaymentInner'
import UserProperties from '../UserProperties'
import DeliveryProperties from '../DeliveryProperties'
import store from '../store'
import DaDataInput from '../DaDataInput'
import DaDataInputAddress from '../DaDataInputAddress'




import {mapActions, mapState, mapGetters, mapMutations} from 'vuex'


export default {
    name: 'order',
    mixins: [common],
    store,
    components: {
        TypeDelivery,
        DataDelivery,
        DataDeliveryPickup,
        DaDataInput,
        DaDataInputAddress,
        PaymentList,
        PaymentListCash,
        PaymentInner,
        UserProperties,
        DeliveryProperties,


    },
    data() {
        return {
            COUPON: '',
            COUPON_ERROR: '',
            // activeNameDelivery: 'pickup',
            activeNamePay: 'onlain',
        }
    },

    props: {
        application: {
            type: Object,
            required: true
        } // Данные приложения, заполняются в шаблоне компонента
    },
    created: function () {
        this.setOrderInfoDeliveryPickupId(Object.values(this.application.info.deliveryPickupId));
    }, // Действия при создании
    computed: {// Вычисляемые данные
        ...mapState([
            'orderValues',
            'orderValuesAddition',
            'errorList',
            'orderPropertiesValue',
            'info',
            'order',
            'service'
        ]),
        ...mapGetters([
            'deliveryId',
            'getPropertyValueByCode',
            'getOrderValuesAddition',
            'deliveryList',
            'GET_ERROR_PROPERTY',
            'getPropertyIdByCode',
            'getOrderTotal',
            'getIsAuthorizated',
            'getPropertyByCode',
            'isPayCashItem',
            'getDeliveryFieldByCode',
        ]),
        activeNameDelivery: {
            get () {
                return this.service.typeDeliveryId
            },
            set (value) {

                if(value == 'delivery'){
                    this.setOrderValue({name: 'DELIVERY_ID',value:this.application.info.deliveryIdList.delivery[0]});
                }else{
                    this.setOrderValue({name: 'DELIVERY_ID',value:this.application.info.deliveryIdList.deliveryPickup[0]});
                }

                this.setTypeDeliveryId(value)
            }
        },
        basketItems() {
            return this.application.order.GRID.ROWS;
        },
        formData() {
            return this.application.formData
        },
        isOrderSuccess: function () {
            // return this.getValidateUserInfo && this.getValidateDelivery && Object.keys(this.getDeliveryItems).length > 0
            return true;
        },
    },
    watch: {
        'application.order': {
            handler(value) {
                this.$nextTick(() => {
                    this.setOrder(value)
                })
            },
            immediate: true,
            deep: true
        },
        'application.user_info': {
            handler(value) {
                this.$nextTick(() => {
                    // this.setOrderUserInfo(value);
                })

            },
            immediate: true,
            deep: true
        },
        'service.typeDeliveryId'(value) {
            let arDeliver = this.deliveryList;
            let isDeliverySelected = false;
            let deliveryId = false;
            for (let key in arDeliver) {
                if (!deliveryId) {
                    deliveryId = key
                }

                if (arDeliver[key]['CHECKED'] === 'Y') {
                    isDeliverySelected = true
                }
            }
            if (!isDeliverySelected) {
                this.setOrderValue({
                    name: 'DELIVERY_ID',
                    value: deliveryId
                })
            }
            console.log('service.typeDeliveryId');
        },
        orderValues: {
            handler() {
                this.$nextTick(() => {
                    this.refreshOrder();
                })
            },
            deep: true
        },
        'service.step': {
            handler() {
                this.$nextTick(() => {

                    this.refreshOrder();
                    console.log('service.step');
                })
            },
            deep: true
        },
        'service.isDate': {
            handler() {
                this.$nextTick(() => {
                    this.refreshOrder();
                    console.log('service.isDate');
                })
            },
            deep: true
        },
        'service.errorListProperty': {
            handler() {
                this.$nextTick(() => {
                    this.refreshOrder();
                    console.log('service.errorListProperty');
                })
            },
            deep: true
        },

        getOrderValuesAddition: {
            handler(value) {
                this.setOrderValuesAddition(value)
            }
        },
        'service.triggerOrder'() {
            this.$nextTick(() => {
                this.refreshOrder()
            })
        },
        'service.triggerSaveOrder'() {
            this.$nextTick(() => {
                this.saveOrder()
            })
        }
    }, // Наблюдатели
    methods: {
        ...mapActions([
            'setOrder',
            'setDeliveryInit',
            'setOrderUserInfo',
            'service'
        ]),
        ...mapMutations([
            'setTypeDeliveryId',
            'setPropertyGroupIdsCheckByStep',
            'setOrderValue',
            'setOrderValuesAddition',
            'setAjaxProcess',
            'setErrors',
            'setOrderInfoDeliveryPickupId',
            'setOrderPropertyValue',
            'triggeredOrder',
        ]),
        getPriceOrder() {
            let $ORDER_TOTAL_PRICE = this.application.order.TOTAL.ORDER_TOTAL_PRICE
            let $deliveryPrice = this.application.order.TOTAL.DELIVERY_PRICE
            let $Price_order = $ORDER_TOTAL_PRICE - $deliveryPrice;
            return ($Price_order + $deliveryPrice).toFixed(2) + ' ₽'
        },
        getOrderTotalPrice() {
            var tmpSum = this.getOrderTotal.ORDER_TOTAL_PRICE - this.orderValues.PAY_INNER_SUM;
            if (tmpSum > 0) {
                return tmpSum + ' ₽'
            } else {
                return '1 ₽'
            }
        },
        refreshOrder: _.debounce(
            function () {
                this.logToConsole('Обновление заказа');
                this.setAjaxProcess(true);
                let params = new FormData();
                params = this.getFormData();
                params.append('soa-action', 'refreshOrderAjax');
                let vm = this;
                axios({
                    method: 'post',
                    url: vm.application.component.ajaxUrl,
                    data: params
                }).then(function (response) {
                    var propTmp = response.data.order.ORDER_PROP.properties;
                    for (let keyProp in propTmp) {
                        if(propTmp[keyProp].CODE =="CITY"){
                            if(propTmp[keyProp].VALUE == ''){
                                vm.onCitySelect({
                                    'name':vm.application.info.CITY_AUTO_DETECT,
                                    'code':response.data.locations[44].lastValue,
                                    'zip':''
                                });
                                this.setOrderPropertyValue({
                                    id: propTmp[keyProp].ID,
                                    value: vm.application.info.CITY_AUTO_DETECT
                                })
                            }
                        }
                    }

                    if (response.data.success === 'N') {
                        document.location.href = response.data.redirect
                    }
                    vm.orderData = vm.application.order = response.data.order;
                    vm.application.user_info = response.data.user_info;

                    vm.setOrder(vm.application.order);
                    if(response.data.delivery){
                        vm.setDeliveryInit(response);
                    }


                    // if(response.data.locations[44]){
                    //
                    // }
                    vm.$store.dispatch('checkSelectedDelivery')
                    vm.$nextTick(() => {
                        if (vm.deliveryId === vm.application.info.deliveryIdList.pickupSdek) {
                            window.IPOLSDEK_pvz.onLoad(response.data);
                            window.IPOLSDEK_pvz.getPrices();
                        }
                        vm.setAjaxProcess(false)
                    });
                    vm.setAjaxProcess(false)

                }).catch(function (error) {
                    vm.setAjaxProcess(false)
                })

            },
            500
        ),
        saveOrder() {
            this.logToConsole('Сохранение заказа')
            debugger
            var isError = false;
            if($('[name="ORDER_PROP_39"]').val().trim() == ''){
                $('[name="ORDER_PROP_39"]').addClass('error');
                $('html, body').animate({
                    scrollTop: $('#ORDER_PROP_39').offset().top-150
                }, 1000);
                isError = true;
            }else{
                $('[name="ORDER_PROP_39"]').removeClass('error');

            }
            if($('[name="ORDER_PROP_40"]').val().trim() == '' && isError==false){
                $('[name="ORDER_PROP_40"]').addClass('error');
                $('html, body').animate({
                    scrollTop: $('#ORDER_PROP_40').offset().top-150
                }, 1000);
                isError = true;
            }else{
                $('[name="ORDER_PROP_40"]').removeClass('error');
            }
            if($('#ORDER_PROP_41').val().trim() == '' && isError==false){
                $('#ORDER_PROP_41').addClass('error');
                $('html, body').animate({
                    scrollTop: $('#ORDER_PROP_41').offset().top-150
                }, 1000);
                isError = true;
            }else{
                $('[name="ORDER_PROP_41"]').removeClass('error');
            }
            if(isError == false){
                this.setAjaxProcess(true)
                let vm = this
                let params = new FormData()
                params = this.getFormData(true)
                params.append('action', 'saveOrderAjax')
                params.append('soa-action', 'saveOrderAjax')
                axios({
                    method: 'post',
                    url: vm.application.component.ajaxUrl,
                    data: params
                }).then(function (response) {
                    if (response.data.order.REDIRECT_URL) {
                        document.location.href = response.data.order.REDIRECT_URL
                    }
                    vm.setErrors({
                        name: 'properties',
                        value: response.data.order.ERROR || {}
                    })
                    vm.setAjaxProcess(false)
                }).catch(error => {
                    vm.setAjaxProcess(false)
                })
            }



        }, // Сохранение заказа
        getFormData(submit = false) {
            let formData = new FormData()
            let vm = this
            _.each(vm.orderValues, (value, key) => {
                key = submit ? key : 'order[' + key + ']'
                formData.append(key, _.toString(value))
            })
            _.each(vm.orderValuesAddition, (value, key) => {
                key = submit ? key : 'order[' + key + ']'
                formData.append(key, _.toString(value))
            })
            _.each(vm.formData, (value, key) => {
                formData.append(key, value)
            })
            _.each(vm.formData, (value, key) => {
                formData.append('order[' + key + ']', value)
            })
            _.each(vm.orderPropertiesValue, (value, key) => {

                key = submit ? 'ORDER_PROP_' + key : 'order[ORDER_PROP_' + key + ']'
                formData.append(key, value)
            })
            _.each(vm.application.component, (value, key) => {
                formData.append(key, value)
            })
            if(this.getDeliveryFieldByCode('BUYER_STORE')){
                formData.append('order[BUYER_STORE]', this.getDeliveryFieldByCode('BUYER_STORE'));
                formData.append('BUYER_STORE', this.getDeliveryFieldByCode('BUYER_STORE'));
            }

            return formData
        },
        onOrderClick: function () {
            if (this.isOrderSuccess) {
                this.saveOrder()
            }
        },
        tabhandleClick(tab, event) {
            console.log(tab, event);
        },
        onCitySelect (item) {
            this.setOrderPropertyValue({
                id: this.getPropertyIdByCode('CITY'),
                value: item.name
            });
            this.setOrderPropertyValue({
                id: this.getPropertyIdByCode('LOCATION'),
                value: item.code
            });
            this.setOrderPropertyValue({
                id: this.getPropertyIdByCode('ZIP'),
                value: item.zip
            })
            this.triggeredOrder();
        },
        onAddressSelect (item) {
            debugger
            // this.setOrderPropertyValue({
            //     id: this.getPropertyIdByCode('CITY'),
            //     value: item.name
            // });
            // this.setOrderPropertyValue({
            //     id: this.getPropertyIdByCode('LOCATION'),
            //     value: item.code
            // });
            // this.setOrderPropertyValue({
            //     id: this.getPropertyIdByCode('ZIP'),
            //     value: item.zip
            // })
            //this.triggeredOrder();
        },

    }
}

import _ from 'lodash'
import common from '../../mixins/common'

import axios from 'axios'

import TypeDelivery from '../TypeDelivery'
import DataDelivery from '../DataDelivery'
import PaymentList from '../PaymentList'
import UserProperties from '../UserProperties'
import DpdDelivery from '../DpdDelivery'
import store from '../store'
// import BootstrapVue from 'bootstrap-vue'
// import 'bootstrap/dist/css/bootstrap.css'
// import 'bootstrap-vue/dist/bootstrap-vue.css'
import {mapActions, mapState, mapGetters, mapMutations} from 'vuex'

export default {
    name: 'order',
    mixins: [common],
    store,
    components: {
        UserProperties,
        TypeDelivery,
        DataDelivery,
        PaymentList,
        DpdDelivery
    },
    props: {
        application: {
            type: Object,
            required: true
        } // Данные приложения, заполняются в шаблоне компонента
    },
    created: function () {
        this.setPropertyGroupIdsCheckByStep(this.application.propertyGroupIdsCheckByStep)
        this.setOrderInfoDeliveryPickupId(Object.values(this.application.info.deliveryPickupId))
    }, // Действия при создании
    mounted() {

    },
    computed: {// Вычисляемые данные
        ...mapState([
            'orderValues',
            'orderValuesAddition',
            'errorList',
            'orderPropertiesValue',
            'info',
            'service'
        ]),
        ...mapGetters([
            'deliveryId',
            'getPropertyValueByCode',
            'getOrderValuesAddition',
            'deliveryList',
        ]),
        formData() {
            return this.application.formData
        }
    },
    watch: {
        'application.order': {
            handler(value) {
                this.setOrder(value)
                // this.setAjaxProcess(true);
                console.log('application.order');
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
                    //this.refreshOrder();
                    console.log('orderValues');
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
            'setStep',
            'nextStep'
        ]),
        ...mapMutations([
            'setPropertyGroupIdsCheckByStep',
            'setOrderValue',
            'setOrderValuesAddition',
            'setAjaxProcess',
            'setErrors',
            'setOrderInfoDeliveryPickupId',
        ]),
        getPriceOrder() {
            let $ORDER_TOTAL_PRICE = this.application.order.TOTAL.ORDER_TOTAL_PRICE
            let $deliveryPrice = this.application.order.TOTAL.DELIVERY_PRICE
            let $Price_order = $ORDER_TOTAL_PRICE - $deliveryPrice;
            return ($Price_order + $deliveryPrice).toFixed(2) + ' руб.'
        },
        getPayBonus() {
            let $ORDER_TOTAL_PRICE = this.application.order.TOTAL.ORDER_TOTAL_PRICE
            let $deliveryPrice = this.application.order.TOTAL.DELIVERY_PRICE
            let $Price_order = $ORDER_TOTAL_PRICE - $deliveryPrice;


        },
        refreshOrder: _.debounce(
            function () {
                debugger
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

                    if (response.data.success === 'N') {
                        document.location.href = response.data.redirect
                    }
                    vm.orderData = response.data.order;
                    vm.application.order = response.data.order;
                    //vm.initialData()
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
                // console.log(error)
            })
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
            if (vm.deliveryId === vm.application.info.deliveryIdList.pickupDPD) {
                formData.append('IPOLH_DPD_ORDER', 'Y')
                formData.append('IPOLH_DPD_TARIFF[PICKUP]', 'PCL')
                formData.append('IPOLH_DPD_TERMINAL[PICKUP]', this.getPropertyValueByCode('PCL'))
            }
            formData.append('BUYER_STORE_XML', vm.application.info.BUYER_STORE_XML)
            return formData
        }
    }
}

import _ from 'lodash'
import axios from 'axios'
import commonMixin from '../../mixins/common'
import store from '../store'
import BasketInput from '../BasketInput'
import {mapActions, mapState, mapGetters, mapMutations} from 'vuex'

export default {
    name: 'basket',
    components: {
        BasketInput
    },
    mixins: [commonMixin],
    store,
    props: {
        application: {
            type: Object,
            required: true
        } // Данные приложения, заполняются в шаблоне компонента
    },
    created: function () {
        this.setOrderInfoKoronaBalance(this.application.KORONA_INNER)
        this.refreshOrder()
    },
    computed: {
        ...mapState([
            'errorList',
            'basketValues',
            'basketItemUpdate',
            'koronaBalance',
        ]),
        ...mapGetters([
            'getBasket',
            'getBasketItems',
            'getStores',
            'getStoreSelected',
            'getAjaxProcess',
            'getKoronaBalance',
            'getKoronaBalanceCode',
        ]),

        formData() {
            return this.application.formData
        },
        sumBasket() {
            try {
                let res = {}
                res['DISCOUNT_PRICE_FORMATED'] = false
                res['PRICE'] = 0
                res['PRICE_FORMATED'] = 0
                res['PRICE_WITHOUT_DISCOUNT_FORMATED'] = false
                res['BONUS_PRICE_FORMATED'] = false
                res['CURRENCY'] = 'RUB'

                //console.log(basket);
                if (this.getBasket.allSum_FORMATED) {
                    res['PRICE'] = this.getBasket.allSum_FORMATED
                }
                if (this.getBasket.allSum) {
                    res['PRICE_FORMATED'] = this.getBasket.allSum
                }
                if (this.getBasket.DISCOUNT_PRICE_FORMATED) {
                    res['DISCOUNT_PRICE_FORMATED'] = this.getBasket.DISCOUNT_PRICE_FORMATED
                }

                if (this.getBasket.BONUS_PRICE) {
                    res['BONUS_PRICE'] = this.getBasket.BONUS_PRICE
                }
                if (this.getBasket.BONUS_PRICE_FORMATED) {
                    res['BONUS_PRICE_FORMATED'] = this.getBasket.BONUS_PRICE_FORMATED
                }

                // $PAN = $result['PAN'] ?: false;
                // $priceBonus = General::getBonusSaleGetInfo2($totalData['PRICE'] * 100, General::getChequeOrder(), $PAN);
                //
                // if ($priceBonus) {
                //     $totalData['BONUS_PRICE'] = $priceBonus / 100;
                //     $totalData['BONUS_PRICE_FORMATED'] = CurrencyFormat($priceBonus / 100, 'RUB');
                // }


                return res
            } catch (error) {

                console.error(error)
                return error
            }
        }
    },
    watch: {
        basketValues: {
            handler() {
                this.$nextTick(() => {
                    this.refreshOrder()
                })
            },
            deep: true
        },
        koronaBalance: {
            handler() {
                this.$nextTick(() => {
                    this.refreshOrder()
                })
            },
            deep: true
        },
    }, // Наблюдатели
    methods: {
        ...mapActions([
            'setBasket',
        ]),
        ...mapMutations([
            'setBasketValue',
            'setBasketValues',
            'setBasketItems',
            'setBasketItemId',
            'clearBasketItemUpdate',
            'setOrderInfoKoronaBalance',
            'setStores'
        ]),
        isDelayElement: (status) => {
            return status === 'Y'
        },
        setQuantityElement: function (id, method, count) {
            console.log(id, method);
            this.setBasketItemId({
                name: id,
                value: method,
                count: count
            })
            this.refreshOrder()
        },
        selectedStore: function (store_id) {
            this.setBasketValue({
                name: 'selectedStore',
                value: store_id
            })
        },
        refreshOrder: _.debounce(
            function () {
                console.log('Обновление корзины')
                let params = new FormData()
                params = this.getFormData()
                params.append('action', 'recalculateAjax')
                this.clearBasketItemUpdate()
                axios({
                    method: 'post',
                    url: this.application.component.ajaxUrl,
                    data: params
                }).then((response) => {
                    this.setBasket(response.data.BASKET_DATA)
                    this.setBasketItems(response.data.BASKET_DATA.GRID.ROWS)
                    this.setStores(response.data.STORES)

                    if (this.getStoreSelected == false) {
                        this.setBasketValue({
                            name: 'selectedStore',
                            value: response.data.STORE_DEFAULT + '_def'
                        })
                        this.setBasketValue({
                            name: 'defaultStore',
                            value: response.data.STORE_DEFAULT + '_def'
                        })
                    }
                    window.BX.onCustomEvent('OnBasketChange');
                    //vm.setAjaxProcess(false)
                }).catch(function (error) {
                    // vm.setAjaxProcess(false)
                })
            },
            500
        ),
        getFormData(submit = false) {
            let formData = new FormData()
            _.each(this.formData, (value, key) => {
                formData.append(key, value)
            })
            _.each(this.basketItemUpdate, (value, key) => {

                if (typeof (value) === 'object') {
                    for (let coupon in value) {
                        formData.append('basket[' + key + '][' + coupon + ']', coupon)
                    }
                } else {
                    formData.append('basket[' + key + ']', value)
                }
            })
            formData.append('store_id', this.getStoreSelected)
            if (this.getKoronaBalanceCode('PAN')) {
                formData.append('pan', this.getKoronaBalanceCode('PAN'))
            }
            if (this.getKoronaBalanceCode('COUPON')) {
                formData.append("basket[coupon]", this.getKoronaBalanceCode('COUPON'))
            }
            return formData
        },
        nextStep2Order: function () {
            if (this.sumBasket.PRICE_FORMATED > 0) {
                let params = new FormData()
                params.append('PAN', this.getKoronaBalanceCode('PAN'));
                params.append('STORE', this.getStoreSelected);
                axios({
                    method: 'post',
                    url: '/ajax/Main/setSaveBasket',
                    data: params
                }).then((response) => {
                    if(response.data.status){
                        window.location.href = '/order/';
                    }
                    vm.setAjaxProcess(false)
                }).catch(function (error) {
                    // vm.setAjaxProcess(false)
                })
            }
        }

    }
}
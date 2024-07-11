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
    data() {
        return {
            priceAnimationData: {
                start: {},
                finish: {},
                currency: {},
                int: {},
            }
        }
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
        this.loadParams();
    },
    computed: {
        ...mapState([
            'errorList',
            'basketValues',
            'basketItemUpdate',
            'basketSignedTemplate',
            'basketSignedParams',
            'basketSessid',
        ]),
        ...mapGetters([
            'getBasket',
            'getBasketItems',
            'getStores',
            'getStoreSelected',
            'getAjaxProcess',

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
                    res['PRICE_FORMATED'] = (this.getBasket.allSum).toFixed(0) + ' руб.';
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
            'setStores',
            'setBasketSignedTemplate',
            'setBasketSignedParams',
            'setBasketSessid',
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
        loadParams: function () {
            var _this = this;
            axios({
                method: 'get',
                url: '/bitrix/services/main/ajax.php?action=poligon%3Acore.sale.getSignet',
            }).then((response) => {
                _this.setBasketSignedTemplate(response.data.data.signedTemplate);
                _this.setBasketSignedParams(response.data.data.signedParams);
                _this.setBasketSessid(response.data.data.basketSessid);

                _this.refreshOrder();

            }).catch(function (error) {
            })
        },
        selectedStore: function (store_id) {
            this.setBasketValue({
                name: 'selectedStore',
                value: store_id
            })
        },
        clearPriceAnimationData: function () {
            this.priceAnimationData = {
                start: {},
                finish: {},
                currency: {},
                int: {},
            };
        },
        addPriceAnimationData: function (nodeId, start, finish, currency) {
            if (!window.BX.type.isPlainObject(this.priceAnimationData)) {
                this.clearPriceAnimationData();
            }

            this.priceAnimationData.start[nodeId] = parseFloat(start);
            this.priceAnimationData.finish[nodeId] = parseFloat(finish);
            this.priceAnimationData.currency[nodeId] = currency;
            this.priceAnimationData.int[nodeId] =
                parseFloat(start) === parseInt(start) && parseFloat(finish) === parseInt(finish);
        },
        refreshOrder: _.debounce(
            function () {
                console.log('Обновление корзины')
                var _this = this
                let params = new FormData()
                params = this.getFormData()
                params.append('action', 'recalculateAjax')
                params.append('site_template_id', 'aspro_max');
                params.append('sessid', this.basketSessid);
                params.append('via_ajax', 'Y');
                params.append('site_id', 's2');
                params.append('fullRecalculation', 'N');
                params.append('template', this.basketSignedTemplate);
                params.append('signedParamsString', this.basketSignedParams);

                this.clearBasketItemUpdate()
                axios({
                    method: 'post',
                    url: '/local/templates/aspro_max/components/bitrix/sale.basket.basket/main_vue/ajax.php',
                    data: params
                }).then((response) => {
                    this.setBasket(response.data.BASKET_DATA)
                    this.setBasketItems(response.data.BASKET_DATA.GRID.ROWS)

                    var rows = response.data.BASKET_DATA.GRID.ROWS;
                    var idArray = [];
                    for (var key in rows) {
                        if (rows.hasOwnProperty(key)) {
                            var id = rows[key].PRODUCT_ID;
                            idArray.push(id);
                        }
                    }
                    //Делаем запрос на получение для товаров сопутки
                    _this.setPlusProduct(idArray);
                    window.BX.onCustomEvent('OnBasketChange');
                }).catch(function (error) {
                })
            },
            500
        ),
        setPlusProduct(product_id){
            window.BX.ajax.runAction('poligon:core.sale.getPlusProduct',{
               data:{ product_id:product_id}
            }).then(function (res) {
            });

        },
        getFormData(submit = false) {
            let formData = new FormData();

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
            return formData
        },
        nextStep2Order: function () {
            if (this.sumBasket.PRICE_FORMATED > 0) {
                let params = new FormData()
                params.append('STORE', this.getStoreSelected);
                axios({
                    method: 'post',
                    url: '/ajax/Main/setSaveBasket',
                    data: params
                }).then((response) => {
                    if (response.data.status) {
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
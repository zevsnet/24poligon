<template>
    <div>
        <div class="pay-system-list pos-rel" v-if="getBalance() !== false">
            <div class="sb_left_50 ">
                <div class="row">
                    <div class="col-md-12 pb-10" v-for="paySystemInner in paymentListKorona" :key="+paySystemInner.ID"
                         :gutter="10">
                        <div class="row">
                            <div class="col-md-7">
                                <el-checkbox v-model="payInner" :label="+paySystemInner.ID"><span></span></el-checkbox>
                                <span class="sb-name-delivery">{{paySystemInner.NAME}} <b>{{getBalance()}}</b> Можно оплатить ( <b
                                        style="color:green">{{(obKoronaBalance.maxSaleBonus/100).toFixed(2)}} руб.</b> )</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row pb-30" v-if="payInner && obKoronaBalance.tokenRequired === 'Y'" :gutter="10">
                    <div class="col-md-7">
                        <input v-model="payToken" type="text" required>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>
<script>
    import {mapGetters, mapMutations, mapState} from 'vuex'

    export default {
        name: 'payment-inner',

        data() {
            return {
                loading: false,
                maxSaleBonus: 0
            }
        },
        created() {
            this.getBonusSum()
        },
        computed: {
            payToken: {
                get() {
                    return this.koronaBalance.PAY_TOKEN
                },
                set(value) {

                    this.setKoronaBalanceValue({
                        name: 'PAY_TOKEN',
                        value: value
                    })
                }
            },
            payInner: {
                get() {
                    return this.orderValues.PAY_CURRENT_ACCOUNT_SB === 'Y'
                },
                set(value) {
                    this.setOrderValue({
                        name: 'PAY_CURRENT_ACCOUNT_SB',
                        value: value ? 'Y' : 'N'
                    })

                    this.sendCode()

                }
            },
            ...mapGetters({
                storePaymentId: 'paymentId',
                paymentListKorona: 'paymentListKorona',
                obKoronaBalance: 'getKoronaBalance',
            }),
            ...mapState([
                'order',
                'service',
                'orderValues',
                'koronaBalance'
            ])
        },
        methods: {
            ...mapMutations([
                'setOrderValue',
                'setKoronaBalanceValue'
            ]),
            sendCode() {
                if (this.orderValues.PAY_CURRENT_ACCOUNT_SB === 'N') {
                    return;
                }
                let $ORDER_TOTAL_PRICE = this.order.TOTAL.ORDER_TOTAL_PRICE
                let $deliveryPrice = this.order.TOTAL.DELIVERY_PRICE
                let $Price_order = $ORDER_TOTAL_PRICE * 100 - $deliveryPrice * 100
                let params = new FormData()
                params.append('Price_order', $Price_order)
                params.append('PAN', this.obKoronaBalance.PAN)
                this.$http.post('/ajax/Korona/authPoints', params).then(function (response) {
                    this.setKoronaBalanceValue({
                        name: 'tokenRequired',
                        value: response.data.data['tokenRequired'] ? 'Y' : 'N'
                    })
                }).catch(function (error) {
                    console.log(error)
                })
            },
            getBalance() {
                if (this.obKoronaBalance) {
                    return this.obKoronaBalance.BalanceFormat
                }
            },
            getBonusSum() {
                let $ORDER_TOTAL_PRICE = this.order.TOTAL.ORDER_TOTAL_PRICE
                let $deliveryPrice = this.order.TOTAL.DELIVERY_PRICE
                let $Price_order = $ORDER_TOTAL_PRICE * 100 - $deliveryPrice * 100
                let params = new FormData()
                params.append('Price_order', $Price_order)
                params.append('PAN', this.obKoronaBalance.PAN)
                this.loading = true
                this.$http.post('/ajax/Korona/getBonusSaleGetInfo2', params).then((response) => {
                    this.loading = false
                    this.maxSaleBonus = response.data.data / 100
                }).catch(error => {
                    this.loading = false
                    console.log(error)
                })

            },
        }
    }
</script>
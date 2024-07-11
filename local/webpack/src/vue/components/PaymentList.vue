<template>
    <div class="pay-system-list pos-rel ">
        <div class="row">
            <div class="col-md-3 pb-10"
                 v-for="paySystem in paymentList"
                 :key="+paySystem.ID"
                 v-if="paySystem.CODE !== 'CASH'"
                 :gutter="10">
                <el-radio  class="pay_item" v-model="paymentId" :label="+paySystem.ID">
                    <img class="delivery-img" v-if="paySystem.PSA_LOGOTIP_SRC" :src="paySystem.PSA_LOGOTIP_SRC" :alt="paySystem.NAME">
                    <span style="color:#000" v-else>{{paySystem.NAME}}</span>
                    <div class="vue-checkout-payment__slider-icon-container"><i class="el-icon-check"></i></div>
                </el-radio>
            </div>
        </div>
    </div>
</template>
<script>
    import {mapGetters, mapMutations, mapState} from 'vuex'

    export default {
        name: 'payment-list',
        computed: {

            paymentId: {
                get() {
                    return this.storePaymentId
                },
                set(value) {
                    this.setOrderValue({
                        name: 'PAY_SYSTEM_ID',
                        value: value
                    })
                }
            },
            ...mapGetters({
                storePaymentId: 'paymentId',
                paymentList: 'paymentList',
                getPropertyValueByCode: 'getPropertyValueByCode',
                getPropertyIdByCode: 'getPropertyIdByCode'
            }),
            ...mapState([
                'order',
                'service',
                'orderValues'
            ])
        },
        methods: {
            ...mapMutations([
                'setOrderValue'
            ])
        }
    }
</script>
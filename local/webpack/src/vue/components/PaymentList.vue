<template>

    <div class="pay-system-list pos-rel col-md-12">
        <div class="row">
            <div class="col-md-12 pb-10"
                 v-for="paySystem in paymentList" :key="+paySystem.ID"
                 :gutter="10">
                <div class="row">
                    <div class="col-md-7">
                        <el-radio v-model="paymentId" :label="+paySystem.ID">
                            <img class="delivery-img" v-if="paySystem.PSA_LOGOTIP_SRC" :src="paySystem.PSA_LOGOTIP_SRC">
                            <span class="sb-name-delivery">{{paySystem.NAME}}</span>
                        </el-radio>
                    </div>
                </div>
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
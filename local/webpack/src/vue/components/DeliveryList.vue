<template>
    <div class="delivery-list pos-rel col-md-12">
        <div class="row">
            <div class="col-md-12 pb-10" v-for="delivery in deliveryList" :key="+delivery.ID"
                 v-bind:class="{active:delivery.CHECKED == 'Y'}"
                 :gutter="10">
                <div class="row">
                    <div class="col-md-7">
                        <el-radio v-model="value" :label="+delivery.ID">
                            <img class="delivery-img" v-if="delivery.LOGOTIP_SRC" :src="delivery.LOGOTIP_SRC">
                            <span class="sb-name-delivery">{{delivery.NAME}}</span>
                        </el-radio>
                    </div>
                    <div class="col-md-3">
                        <span v-if="false" class="text-muted sb-delivery-time" v-html="delivery.PERIOD_TEXT"></span>
                        <div v-if="delivery.CALCULATE_ERRORS" :span="24"><p
                                style="padding: 10px 0; color:red"
                                v-html="delivery.CALCULATE_ERRORS"></p>
                        </div>
                        <template v-if="isSdek(delivery) || isEnergy(delivery) || isDPD(delivery)" :span="24"
                                  style="margin-top: 20px">
                            <el-button @click="onClick()" class="big_btn button" type="info">Выбрать <span
                                    v-if="addressValue">другой</span> склад
                            </el-button>
                            <jquery-input v-if="isSdek(delivery)" class="order-pickup-input"
                                          @change="changeAddress"
                                          name="PICKUP_ADDRESS" readonly></jquery-input>
                            <template v-else-if="isDPD(delivery)">
                                <jquery-input class="order-pickup-input" @change="changeAddress" name="PICKUP_ADDRESS"
                                              readonly></jquery-input>
                            </template>
                            <p v-else style="margin-top: 10px">{{addressValue}}</p>
                        </template>
                    </div>
                    <div class="col-md-2 text-right">
                        <span class="sb-delivery-price">{{ getPriceDelivery(delivery) }}</span>
                    </div>
                    <div class="col-md-12" v-if="+delivery.ID == 70">
                        <el-select v-model="sb_date_delivery" placeholder="Select"
                                   v-if="getPropertyByCode('DELIVERY_REQUIRED_DATE')">
                            <el-option
                                    v-for="item in options"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                            </el-option>
                        </el-select>
                        <template>
                            <el-time-select
                                    v-if="getPropertyByCode('DELIVERY_REQUIRED_START_TIME')"
                                    placeholder="Start time"
                                    v-model="startTime"
                                    :picker-options="{
      start: '08:30',
      step: '00:30',
      end: '23:30'
    }">
                            </el-time-select>
                            <el-time-select
                                    v-if="getPropertyByCode('DELIVERY_REQUIRED_TIME')"
                                    placeholder="End time"
                                    v-model="endTime"
                                    :picker-options="{
      start: '08:30',
      step: '00:30',
      end: '23:30',
      minTime: startTime
    }">
                            </el-time-select>
                        </template>

                    </div>
                </div>
            </div>
        </div>


    </div>
</template>

<script>
    import JQueryInput from './JQueryInput'
    import {mapState, mapGetters, mapMutations} from 'vuex'

    export default {
        name: 'delivery-list',
        components: {
            'jquery-input': JQueryInput
        },
        props: {
            deliveryInfo: {
                type: Object,
                default: {}
            },
            info: {
                type: Object,
                default: {}
            }
        },
        data() {
            return {
                options: [{
                    value: 'today',
                    label: 'сегодня'
                }, {
                    value: 'tomorrow',
                    label: 'завтра'
                }, {
                    value: 'after_tomorrow',
                    label: 'послезавтра'
                }],
            }
        },
        created: function () {
            //this.setDeliveryPickupId(this.info.delivery)
        }, // Действия при создании
        computed: {
            ...mapGetters([
                'deliveryId',
                'deliveryList',
                'getPropertyValueByCode',
                'getPropertyIdByCode',
                'getPropertyByCode'
            ]),
            ...mapState([
                'service'
            ]),
            value: {
                get() {
                    return this.deliveryId
                },
                set(value) {
                    this.setOrderValue({
                        name: 'DELIVERY_ID',
                        value: value
                    });
                    this.SET_CHANG_DATE();

                }
            },
            sb_date_delivery: {
                get() {
                    return this.getPropertyValueByCode('DELIVERY_REQUIRED_DATE')
                },
                set(value) {
                    //DELIVERY_REQUIRED_DATE
                    this.setOrderPropertyValue({
                        id: this.getPropertyIdByCode('DELIVERY_REQUIRED_DATE'),
                        value: value
                    });
                    this.SET_CHANG_DATE();

                }
            },
            startTime: {
                get() {
                    return this.getPropertyValueByCode('DELIVERY_REQUIRED_START_TIME')
                },
                set(value) {
                    this.setOrderPropertyValue({
                        id: this.getPropertyIdByCode('DELIVERY_REQUIRED_START_TIME'),
                        value: value
                    });
                    this.SET_CHANG_DATE();

                }
            },
            endTime: {
                get() {
                    return this.getPropertyValueByCode('DELIVERY_REQUIRED_TIME')
                },
                set(value) {
                    this.setOrderPropertyValue({
                        id: this.getPropertyIdByCode('DELIVERY_REQUIRED_TIME'),
                        value: value
                    });
                    this.SET_CHANG_DATE();
                }
            },
            addressValue() {
                return this.getPropertyValueByCode('PICKUP_POINT')
            }
        },
        methods: {
            ...mapMutations([
                'setOrderValue',
                'setOrderPropertyValue',
                'setDeliveryPickupId',
                'SET_CHANG_DATE'
            ]),
            isZip: function () {
                return (this.getPropertyValueByCode('ZIP') &&
                    this.getPropertyValueByCode('LOCATION') &&
                    this.getPropertyValueByCode('ADDRESS'))
            },
            getPriceDelivery(delivery) {
                let priceDelivery = 0;
                debugger
                if (delivery.DELIVERY_DISCOUNT_PRICE) {
                    priceDelivery = delivery.DELIVERY_DISCOUNT_PRICE_FORMATED
                } else {
                    priceDelivery = delivery.PRICE_FORMATED
                }
                return priceDelivery;
            },
            onOpenWindow() {
                switch (this.deliveryId) {
                    case this.deliveryInfo.pickupSdek:
                        this.openSdek();
                        break;
                    case this.deliveryInfo.pickupEnergy:
                        this.openEnergy();
                        break;
                    case this.deliveryInfo.pickupDPD:
                        this.openDPD();
                        break;
                }
            },
            isSdek(delivery) {
                return +delivery.ID === this.value && this.value === this.deliveryInfo.pickupSdek
            },
            isDPD(delivery) {
                return +delivery.ID === this.value && this.value === this.deliveryInfo.pickupDPD
            },
            isEnergy(delivery) {
                return +delivery.ID === this.value && this.value === this.deliveryInfo.pickupEnergy
            },
            onClick() {
                this.$emit('click')
            },
            onChange(value) {
                this.$emit('input', value)
            },
            changeAddress(value) {
                this.setOrderPropertyValue({
                    id: this.getPropertyIdByCode('ADDRESS'),
                    value: value
                })
            },

        }
    }
</script>

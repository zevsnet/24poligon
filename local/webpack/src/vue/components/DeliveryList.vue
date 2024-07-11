<template>
    <div class="delivery-list pos-rel col-md-12">
        <div class="row">
            <div class="col-md-12 pb-10" v-for="delivery in deliveryList" :key="+delivery.ID"
                 v-bind:class="{active:delivery.CHECKED == 'Y'}" :gutter="10" v-if="isDelivery(delivery)">
                <div class="row">
                    <div class="col-md-7">
                        <el-radio v-model="value" :label="+delivery.ID">
                            <div class="sb-radio_btn_default"><i class="el-icon-check"></i></div>
                            <span class="sb-name-delivery">{{delivery.NAME}}</span>
                            <p class="f-choice-radio__description" v-if="delivery.ID == 85">Самовывоз из отделения Почты России</p>
                        </el-radio>
                    </div>
                    <div class="col-md-2 text-right"><span class="sb-delivery-price">{{ getPriceDelivery(delivery) }}</span></div>


                    <div class="col-md-12" v-if="+delivery.ID == 86">
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
    import DeliveryProperties from './DeliveryProperties'
    import {mapState, mapGetters, mapMutations} from 'vuex'

    export default {
        name: 'delivery-list',
        components: {
            'jquery-input': JQueryInput,
            'delivery-properties': DeliveryProperties
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
            isDelivery(delivery) {
                return this.deliveryInfo.delivery.indexOf(+delivery.ID) >= 0;
            },
            isDeliveryNoPickup() {
                if (this.deliveryId) {
                    switch (+this.deliveryId) {
                        case 73:
                        case 40:
                            break;
                        default:
                            return true;
                            break;
                    }
                }
                return false;
            },

        }
    }
</script>

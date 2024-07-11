<template>
    <div class="delivery-list pos-rel col-md-12">
        <div class="row">
            <div class="col-md-2 pb-10 sb_btn_delivery" v-for="delivery in deliveryList" :key="+delivery.ID"
                 v-bind:class="{'active':delivery.CHECKED == 'Y'}" :gutter="10"
                 v-if="isDelivery(delivery)"
            >
                <el-radio v-model="value" :label="+delivery.ID">
                    <img class="delivery-img" v-if="delivery.LOGOTIP_SRC" :src="delivery.LOGOTIP_SRC">
                    <span class="sb-name-delivery" v-else>{{delivery.NAME}}</span>
                </el-radio>
                <div v-if="delivery.ID == 85 || delivery.ID == 93 " v-html="delivery.DESCRIPTION" style="display:none"></div>

            </div>
        </div>
        <div class="sb_block_point">
            <div class="sb_Block_other">
                <button class="btn btn-default has-ripple" v-if="deliveryId == 85" @click="onOpenWindow()"><span class="base-ui-button__text">Выбрать точку самовывоза</span></button>
                <button class="btn btn-default has-ripple" v-if="deliveryId == 93" @click="onOpenWindow()"><span class="base-ui-button__text">Выбрать точку самовывоза</span></button>
            </div>
            <PickupTypeMap v-if="typeof getDeliveryPickupItems === 'object'"/>
        </div>
    </div>
</template>

<script>
    import JQueryInput from './JQueryInput'
    import PickupTypeMap from './order/delivery/PickupTypeMap'

    import {mapState, mapGetters, mapMutations} from 'vuex'

    export default {
        name: 'delivery-list-pickup',
        components: {
            PickupTypeMap,
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
                'getPropertyByCode',
                'getDeliveryPickupItems'
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
                    case this.deliveryInfo.pickupPochta:
                        this.openPochta();
                        break;
                        case this.deliveryInfo.pickupBoxbery:
                        this.openBoxbery();
                        break;
                }
            },
            openPochta: function () {
                $('#russianpost_btn_openmap').click()
            },
            openBoxbery: function () {
                $('#boxberrySelectPvzWidget a').click()
            },
            isPochta(delivery) {
                return +delivery.ID === this.value && this.value === this.deliveryInfo.pickupSdek
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
                return this.deliveryInfo.deliveryPickup.indexOf(+delivery.ID) >= 0;
            },

        }
    }
</script>

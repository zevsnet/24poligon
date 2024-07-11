<template>
    <delivery-list-pickup
            :deliveryInfo="deliveryInfo"
            :info="info"
            @click="onOpenWindow"
    ></delivery-list-pickup>
</template>

<script>
    import PropertyText from './PropertyText'
    import FormGroup from './FormGroup'
    import BaseInput from './BaseInput'
    import AddressInput from './AddressInput'
    import _ from 'lodash'
    import {mapState, mapGetters, mapMutations} from 'vuex'
    import DeliveryListPickup from "./DeliveryListPickup";

    export default {
        name: 'data-delivery-pickup',
        components: {
            DeliveryListPickup,
            'property-text': PropertyText,
            FormGroup,
            BaseInput,
            AddressInput,
            typeDeliveryId: {
                get() {
                    return this.service.typeDeliveryId
                }
            }
        },
        props: {
            deliveryInfo: [Object],
            info: {Object}
        },
        computed: {
            ...mapState([
                'service'
            ]),
            ...mapGetters([
                'deliveryId',
                'getPropertyByCode',
                'getPropertyValueByCode',
                'getPropertyIdByCode'
            ])
        },
        methods: {
            ...mapMutations([
                'setOrderPropertyValue',
                'triggeredOrder',
                'setShowEnergy',
                'setShowDPD',
            ]),
            isZip: function () {
                return (this.getPropertyValueByCode('ZIP') &&
                    this.getPropertyValueByCode('LOCATION') &&
                    this.getPropertyValueByCode('STREET') &&
                    this.getPropertyValueByCode('HOUSE'))
            },
            showAddress() {
                return true;
            },
            onOpenWindow() {
                debugger
                switch (this.deliveryId) {
                    case this.deliveryInfo.pickupDPD:
                        this.openDPD()
                        break;
                    case this.deliveryInfo.pickupSdek:
                        this.openSdek()
                        break;
                    case this.deliveryInfo.pickupPochta:
                        this.openPochta()
                        break
                }

            },
            openSdek: function () {
                window.IPOLSDEK_pvz.selectPVZ(this.deliveryInfo.pickupSdek, 'PVZ')
            },
            openPochta: function () {
                window.IPOLSDEK_pvz.selectPVZ(this.deliveryInfo.pickupSdek, 'PVZ')
            },
            openDPD: function () {
                //window.IPOLSDEK_pvz.selectPVZ(this.deliveryInfo.pickupSdek, 'PVZ')
                this.setShowDPD(true)
            },
            openEnergy: function () {
                this.setShowEnergy(true)
            },
            onAddressSelect: function (item) {
                let params = new FormData()
                params.append('COUNTRY_ID', this.service.countryId)

                let data = item.data

                _.forEach(data, (element, key) => {
                    if (_.isNull(element)) {
                        return
                    }
                    params.append(key, element)
                })

                this.$http.post('/local/components/sb/sale.location.selector.search/get.php', params).then((response) => {
                    if (response.data.data) {
                        this.setOrderPropertyValue({
                            id: this.getPropertyIdByCode('LOCATION'),
                            value: response.data.data.CODE
                        })
                    }
                    this.setOrderPropertyValue({
                        id: this.getPropertyIdByCode('ZIP'),
                        value: data.postal_code
                    })
                    this.setOrderPropertyValue({
                        id: this.getPropertyIdByCode('ADDRESS'),
                        value: ''
                    })
                    this.triggeredOrder()
                }).catch(function (error) {
                    console.log(error)
                })
            }
        }
    }
</script>

<template>
    <delivery-list
            :deliveryInfo="deliveryInfo"
            :info="info"
    ></delivery-list>
</template>

<script>
    import PropertyText from './PropertyText'
    import FormGroup from './FormGroup'
    import BaseInput from './BaseInput'
    import AddressInput from './AddressInput'
    import DeliveryList from './DeliveryList'
    import {mapState, mapGetters, mapMutations} from 'vuex'

    export default {
        name: 'data-delivery',
        components: {
            'property-text': PropertyText,
            FormGroup,
            BaseInput,
            AddressInput,
            DeliveryList,
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
        }
    }
</script>

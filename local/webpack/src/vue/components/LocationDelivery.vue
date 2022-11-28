<template>
    <div>
        <div class="order-address-select">
            <div v-if="getPropertyByCode('LOCATION')">
                <orderLocationInput
                        :property="getPropertyByCode('LOCATION')"
                ></orderLocationInput>
            </div>
        </div>
    </div>
</template>

<script>

    import {mapState, mapGetters, mapMutations} from 'vuex'
    import OrderLocationInput from './OrderLocationInput'

    export default {
        name: 'location-delivery',
        components: {
            OrderLocationInput,
        },
        computed: {
            countryId: {
                get() {
                    return this.service.countryId
                },
                set(value) {
                    this.setOrderPropertyValue({
                        id: this.getPropertyIdByCode('LOCATION'),
                        value: ''
                    })
                    this.setCountryId(value)
                }
            },
            ...mapState([
                'service',
                'countyList'
            ]),
            ...mapGetters([
                'getPropertyByCode',
                'getPropertyIdByCode',
            ])
        },
        methods: {
            ...mapMutations([
                'setCountryId',
                'setOrderPropertyValue',
            ])
        }
    }
</script>

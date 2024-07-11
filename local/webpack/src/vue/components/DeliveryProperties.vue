<template>
    <div class="delivery-options-form" style="margin-top:20px">
        <div class="fild skeleton-input" v-if="getPropertyByCode('ADDRESS')">
            <form-group v-if="getPropertyByCode('ADDRESS')" :property="getPropertyByCode('ADDRESS')">
                <base-input :property="getPropertyByCode('ADDRESS')"></base-input>
            </form-group>
        </div>
    </div>
</template>

<script>
    import FormGroup from './FormGroup'
    import BaseInput from './BaseInput'
    import DaDataInput from './DaDataInput'
    import PhoneInput from './PhoneInput'
    import OrderLocationInput from './OrderLocationInput'


    import {mapState, mapGetters, mapMutations} from 'vuex'

    export default {
        name: 'delivery-properties',
        components: {
            FormGroup,
            BaseInput,
            DaDataInput,
            PhoneInput,
            OrderLocationInput
        },
        computed: {
            ...mapState([
                'service'
            ]),
            ...mapGetters([
                'deliveryId',
                'getPropertyByCode',
                'getPropertyValueByCode',
                'getPropertyIdByCode',
                'GET_ERROR_PROPERTY'
            ])
        },
        methods: {
            ...mapMutations([
                'setOrderPropertyValue'
            ]),
            isHideAddress() {

                switch (this.deliveryId) {
                    case 78:
                    case 90:
                    case 2:
                        return true;
                    default:
                        return false;
                }
            },
            onCitySelect(item) {
                debugger
                this.setOrderPropertyValue({
                    id: this.getPropertyIdByCode('CITY'),
                    value: item.name
                });
                this.setOrderPropertyValue({
                    id: this.getPropertyIdByCode('LOCATION'),
                    value: item.code
                });
                this.setOrderPropertyValue({
                    id: this.getPropertyIdByCode('ZIP'),
                    value: item.zip
                })
                this.triggeredOrder();
            },
        }
    }
</script>
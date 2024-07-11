<template>
    <div class="order-property-list col-md-12">
        <div class="row">
            <div class="col-md-12">
                <form-group v-if="getPropertyByCode('FIO')" :property="getPropertyByCode('FIO')">
                    <base-input  v-if="getPropertyByCode('FIO')" :property="getPropertyByCode('FIO')"></base-input >
                </form-group>
            </div>
            <div class="col-md-6">
                <form-group v-if="getPropertyByCode('PHONE')" :property="getPropertyByCode('PHONE')">
                    <phone-input v-if="getPropertyByCode('PHONE')" :property="getPropertyByCode('PHONE')"></phone-input>
                </form-group>
            </div>
            <div class="col-md-6">
                <form-group v-if="getPropertyByCode('EMAIL')" :property="getPropertyByCode('EMAIL')">
                    <base-input slot-scope="{ property }" :property="property"></base-input>
                </form-group>
            </div>
        </div>

        <div class="sb_hide_prop" >
            <base-input  v-if="getPropertyByCode('LOCATION')" :property="getPropertyByCode('LOCATION')"></base-input>
            <base-input v-if="getPropertyByCode('ZIP')" :property="getPropertyByCode('ZIP')"></base-input>
            <base-input v-if="getPropertyByCode('CITY')" :property="getPropertyByCode('CITY')"></base-input>
        </div>
    </div>
</template>

<script>
    import FormGroup from './FormGroup'
    import BaseInput from './BaseInput'
    import PhoneInput from './PhoneInput'

    import {mapState, mapGetters} from 'vuex'

    export default {
        name: 'user-properties',
        components: {
            FormGroup,
            BaseInput,
            PhoneInput
        },
        computed: {
            ...mapState([
                'service'
            ]),
            ...mapGetters([
                'getPropertyByCode',
                'getPropertyValueByCode',
                'getPropertyIdByCode',
                'GET_ERROR_PROPERTY'
            ])
        },

    }
</script>
<template>
    <div class="order-property-list col-md-12">

        <div class="row">
            <div class="col-md-4">
                <form-group v-if="getPropertyByCode('F_LAST_NAME')" :property="getPropertyByCode('F_LAST_NAME')">
                    <base-input slot-scope="{ property }" :property="property"></base-input>
                </form-group>
            </div>
            <div class="col-md-4">
                <form-group v-if="getPropertyByCode('F_NAME')" :property="getPropertyByCode('F_NAME')">
                    <base-input slot-scope="{ property }" :property="property"></base-input>
                </form-group>
            </div>
            <div class="col-md-4">
                <form-group v-if="getPropertyByCode('F_SECOND_NAME')" :property="getPropertyByCode('F_SECOND_NAME')">
                    <base-input slot-scope="{ property }" :property="property"></base-input>
                </form-group>
            </div>
        </div>


        <form-group v-if="getPropertyByCode('PHONE')" :property="getPropertyByCode('PHONE')">
            <phone-input v-if="getPropertyByCode('PHONE')" :property="getPropertyByCode('PHONE')"></phone-input>
        </form-group>

        <form-group v-if="getPropertyByCode('EMAIL')" :property="getPropertyByCode('EMAIL')">
            <base-input slot-scope="{ property }" :property="property"></base-input>
        </form-group>

        <div class="row">
            <div class="col-md-8">
                <form-group v-if="getPropertyByCode('LOCATION')" :property="getPropertyByCode('LOCATION')">
                    <orderLocationInput
                            :property="getPropertyByCode('LOCATION')"

                    ></orderLocationInput>
                </form-group>
            </div>
            <div class="col-md-4">
                <form-group v-if="getPropertyByCode('ZIP')" :property="getPropertyByCode('ZIP')">
                    <base-input slot-scope="{ property }" :property="property"></base-input>
                </form-group>
            </div>
        </div>
        <form-group :property="getPropertyByCode('ADDRESS')" v-if="getPropertyByCode('ADDRESS')">
            <base-input slot-scope="{ property }" :property="property"></base-input>
        </form-group>
        <div class="row p-3" v-if="GET_ERROR_PROPERTY('ADDRESS')">
            <div class="col-12 alert alert-danger" role="alert">{{GET_ERROR_PROPERTY('ADDRESS')}}</div>
        </div>

    </div>
</template>

<script>
    import FormGroup from './FormGroup'
    import BaseInput from './BaseInput'
    import PhoneInput from './PhoneInput'
    import OrderLocationInput from './OrderLocationInput'


    import {mapState, mapGetters, mapMutations} from 'vuex'

    export default {
        name: 'user-properties',
        components: {
            FormGroup,
            BaseInput,
            PhoneInput,
            OrderLocationInput

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
        methods: {
            ...mapMutations([
                'setOrderPropertyValue'
            ])
        }
    }
</script>
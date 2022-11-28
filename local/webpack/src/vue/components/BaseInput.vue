<template>
    <input :class="{'input__input':true,'error':getError()}" :type="type"
           :name="'ORDER_PROP_' + property.ID" :readonly="readonly"
           :id="'ORDER_PROP_' + property.ID" :required="isRequired" v-model="value">
</template>

<script>
    import {mapMutations, mapGetters} from 'vuex'

    export default {
        name: 'base-input',
        props: {
            property: {
                type: Object,
                required: true
            },
            readonly: {
                type: Boolean,
                default: false
            },
            type: {
                type: String,
                default: 'text'
            },
        },
        computed: {
            ...mapGetters([
                'getPropertyValueByCode'
            ]),
            value: {
                get() {
                    return '' + this.getPropertyValueByCode(this.property.CODE)
                },
                set(value) {

                    this.setOrderPropertyValue({
                        id: this.property.ID,
                        value: value
                    })

                }
            },
            isRequired() {

                if (this.property.CODE == 'ZIP') {
                    return false
                }
                return this.property.REQUIRED === 'Y'
            },

        },
        methods: {
            ...mapMutations([
                'setOrderPropertyValue',
                'setCityName'
            ]),
            getError(){

            }

        }
    }
</script>
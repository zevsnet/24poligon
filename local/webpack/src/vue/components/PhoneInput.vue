<template>
        <masked-input :required="isRequired"
                      :value="value"
                      @input="onInput"

                      :parameters="{clearIncomplete: true}"
                      type="tel"></masked-input>
</template>

<script>
    import MaskedInput from './masked/maskedInput'
    import BaseInput from './BaseInput'
    import {mapGetters, mapMutations} from 'vuex'

    export default {
        extends: BaseInput,
        name: 'phone-input',
        components: {MaskedInput},
        data() {
            return {
                phoneRaw: '',
                isPhoneSuccess: true, // Флаг подтверждения номера
                isPhoneComplete: false, // Флаг завершения ввода номера телефона
                errorsList: {}
            }
        },
        computed: {
            ...mapGetters([
                'phone',
                'getPropertyIdByCode'
            ]),
            value() {
                return this.getPropertyValueByCode(this.property.CODE)
            }
        },
        methods: {
            ...mapMutations([
                'setPhoneSuccess'
            ]),
            onInput(value) {
                this.phoneRaw = value.replace(/[^0-9.]/g, '')
                this.isPhoneComplete = this.phoneRaw.length === 11
                this.isPhoneSuccess = !!(this.isPhoneComplete && this.phone && (value === this.phone))

                this.setOrderPropertyValue({
                    id: this.getPropertyIdByCode(this.property.CODE),
                    value: value
                })
            },
        },
        mounted() {
            this.onInput(this.phone)
        },
        watch: {
            'isPhoneSuccess': {
                handler: function (val) {
                    this.setPhoneSuccess(val)
                },
                immediate: true
            }
        }
    }
</script>
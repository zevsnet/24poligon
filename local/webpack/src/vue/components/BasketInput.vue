<template>
    <input class="form-control"
           :type="type"
           :readonly="readonly"
           v-model="value">
</template>

<script>
    import {mapMutations, mapGetters} from 'vuex'

    export default {
        name: 'basket-input',
        props: {
            property: {
                type: String,
                required: true
            },
            readonly: {
                type: Boolean,
                default: false
            },
            type: {
                type: String,
                default: 'text'
            }
        },
        computed: {
            ...mapGetters([
                'getKoronaBalanceCode'
            ]),
            value: {
                get() {
                    let tmpVal = ''//this.getKoronaBalanceCode(this.property)
                    return tmpVal ? tmpVal : ''
                },
                set(value) {
                    this.setKoronaBalanceValue({
                        name: this.property,
                        value: value
                    })
                }
            },
        },
        methods: {
            ...mapMutations([
                'setKoronaBalanceValue',
                'setCityName',
            ])
        }
    }
</script>
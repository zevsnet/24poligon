<template>
    <div class="property-text">
        <el-row type="flex" align="middle">
            <el-col :span="10"><label :for="'ORDER_PROP_' + property.ID">{{property.NAME}}</label></el-col>
            <el-col :span="14">
                <template v-if="isValueEmpty(value)">
                    <p>{{value}}</p>
                </template>
                <template v-else>
                    <el-button @click="onClick" type="info">Выбрать склад</el-button>
                </template>
            </el-col>
        </el-row>
    </div>
</template>

<script>
  import { mapGetters } from 'vuex'

  export default {
    name: 'property-text',
    props: {
      property: {
        type: Object,
        required: true
      },
      readonly: {
        type: Boolean,
        default: false
      }
    },
    computed: {
      ...mapGetters([
        'getPropertyValueByCode'
      ]),
      value () {
        return this.getPropertyValueByCode(this.property.CODE)
      }
    },
    methods: {
      onClick (evt) {
        this.$emit('click', evt)
      },
      isValueEmpty (val) {
        val = this.deleteSpaces(val)
        return (val !== '') ? val : false
      },
      deleteSpaces (str) {
        return str.replace(/\s/g, '')
      }
    }
  }
</script>
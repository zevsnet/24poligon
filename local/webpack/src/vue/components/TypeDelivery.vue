<template>
    <div>
        <h4>Тип доставки</h4>
        <div class="order-address-select" >
            <div>
                <el-radio-group v-model="typeDeliveryId">
                    <el-radio-button v-for="county in typeDeliveryList"
                                     :label="county.id"
                                     :key="county.id">{{county.name}}
                    </el-radio-button>
                </el-radio-group>
            </div>
        </div>
    </div>
</template>

<script>
  import { mapState, mapMutations } from 'vuex'

  export default {
    name: 'type-delivery',
    computed: {
      typeDeliveryId: {
        get () {
          return this.service.typeDeliveryId
        },
        set (value) {
          this.setTypeDeliveryId(value)
        }
      },
      ...mapState([
        'service',
        'typeDeliveryList'
      ])
    },
    methods: {
      ...mapMutations([
        'setTypeDeliveryId',
        'triggeredOrder',
        'setAjaxProcess'
      ])
    }
  }
</script>

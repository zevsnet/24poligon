<template>
  <el-col
    :xs="24"
    :sm="24">
    <div class="order-delivery-fields_zip-not-found">
      <div class="order-delivery-fields_zip-not-found-content">
        <el-tooltip
          content="Bottom center"
          placement="bottom"
          effect="light">
          <template v-slot:content>
            <div class="order-delivery-fields_zip-not-found-tooltip">
              <ol>
                <li>В строке «Введите индекс» выберите ближайший к вам населенный пункт из списка предложенных.</li>
                <li>Проставьте отметку в поле «Не нашли свой индекс/адрес».</li>
              </ol>
            </div>
            <div><b>После оплаты заказа менеджер свяжется с вами для уточнения точного адреса.</b></div>
          </template>
          <label
            class="order-delivery-fields_checkbox-zip"
            for="index-not-fount">
            <input
              v-model="check"
              id="index-not-fount"
              name="index-not-fount"
              type="checkbox">
            <span class="checkbox-mark"></span>
            <div class="checkbox-text">Не нашли свой индекс/адрес</div>
          </label>
        </el-tooltip>
      </div>
    </div>
  </el-col>
</template>

<script>
  import {mapGetters, mapMutations} from 'vuex'

  export default {
    name: 'Tooltip',
    computed: {
      ...mapGetters('order', [
        'getDeliveryServiceFieldByCode'
      ]),
      check: {
        get: function () {
          return this.getDeliveryServiceFieldByCode('ZIP_NOT_FOUNT').value === 'Y'
        },
        set: function (val) {
          this.setDeliveryServiceFiledValueByCode({
            code: 'ZIP_NOT_FOUNT',
            value: val ? 'Y' : 'N'
          })
        }
      }
    },
    methods: {
      ...mapMutations('order', [
        'setDeliveryServiceFiledValueByCode'
      ])
    }
  }
</script>

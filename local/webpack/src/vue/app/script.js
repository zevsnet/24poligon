import Order from '../components/Order'
import Basket from '../components/Basket'

import commonMixin from '../mixins/common'

export default {
  name: 'app',
  mixins: [commonMixin],
  components: {
    Order,
    Basket
  },
  props: ['application'],
  data: function() {
    return {
      form: {
        fields: {
          personType: 0, // Тип плательщика
          personTypeOld: 0, // Тип плательщика старый
          userProfile: 0, // Профиль покупателя
          deliveryType: 0, // Тип доставки
          recentDelivery: 0,
          payType: 0, // Тип оплаты
          store: 0, // Склад для самовывоза
          description: '' // Комментарий к заказу
        }, // Список полей заказа
        properties: [] // Список свойств заказа, индекс - ИД
      } // Данные формы заказа
    }
  },
  methods: {
    getComponentName () {
      try {
        return this.application.type
      } catch (error) {
        return ''
      }
    },
    getComponentData () {
      try {
        return this.application
      } catch (error) {
        return {}
      }
    }
  },
  created: function() {
        this.application = JSON.parse(this.application)
  } // Действия при создании
}

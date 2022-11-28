import Vue from 'vue'
import Router from 'vue-router'
import Basket from '@/vue/components/Basket'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/basket2/',
      name: 'Basket',
      component: Basket
    }
  ]
})
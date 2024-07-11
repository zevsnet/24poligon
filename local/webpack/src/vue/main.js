/** Подключение vue */
import Vue from 'vue'
import Element from 'element-ui'
import locale from 'element-ui/lib/locale/lang/ru-RU'

import VueResource from 'vue-resource'
import * as VueGoogleMaps from 'vue2-google-maps'
import VueRouter from 'vue-router'
/** Подключение компонентов */
import app from './App.vue'

/** Настройки vue */
Vue.config.productionTip = false
Vue.use(Element, {locale})
Vue.use(VueResource)
Vue.use(VueGoogleMaps, {
    load: {
        key: 'AIzaSyAGWUQEWDFlrGmlwNQ2weMWjZNbnEnOmMc',
        libraries: 'places,drawing,visualization'
    }
})

Vue.use(VueRouter)
$(function () {
    /** Получение начальных данных */
    const appElement = document.getElementById('order')

    if (appElement) {
        console.log(appElement.dataset);
        /** Монтирование компонентов в DOM */
        const appRoot = Vue.extend(app)
        new appRoot({
            el: appElement,
            propsData: {...appElement.dataset},
            // router: new VueRouter({
            //     mode: 'history'
            // })
        })
    }

    /** Получение начальных данных */

    const basketElement = document.getElementById('basket')

    if (basketElement) {
        console.log(basketElement.dataset);
        /** Монтирование компонентов в DOM */
        const basketRoot = Vue.extend(app)
        new basketRoot({
            el: basketElement,
            propsData: {...basketElement.dataset}
        })
    }
})
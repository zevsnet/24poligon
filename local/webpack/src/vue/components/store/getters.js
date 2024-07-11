import _ from 'lodash'
import find from 'lodash/find'

export default {
    //Корзина
    getBasketItems: state => {

        if (!_.isEmpty(state.basketItems)) {
            return state.basketItems
        }
        return false
    },
    getStores: state => {
        if (!_.isEmpty(state.stores)) {
            return state.stores
        }
        return false
    },

    getStoreSelected: state => {
        if (state.basketValues.selectedStore) {
            return state.basketValues.selectedStore
        }
        return false
    },
    getBasket: state => state.basket,
    getBasketValue: (state, getters) => state.basketValues.PAN,

    //Оформление заказа
    getIsAuthorizated: state => {
        if (state.order) {
            return state.order.IS_AUTHORIZED
        }
        return false
    },
    orderProperties: state => {
        if (!_.isEmpty(state.order)) {
            return state.order['ORDER_PROP'].properties
        }
        return false
    },
    getProductRows: state => {
        if (!_.isEmpty(state.order)) {
            return state.order['GRID']['ROWS']
        }
        return false
    },
    getOrderTotal: state => {
        if (!_.isEmpty(state.order)) {
            return state.order['TOTAL']
        }
        return false
    },
    getOrderValuesAddition: (state, getters) => {
        return {
            PERSON_TYPE_OLD: getters.personTypeId,
            PROFILE_ID_OLD: getters.profileId,
            profile_change: getters.profileId === state.orderValues.PROFILE_ID
                ? 'N'
                : 'Y',
            ZIP_PROPERTY_CHANGED: getters.getResponsePropertyValueByCode('ZIP') ===
            getters.getPropertyValueByCode('ZIP') ? 'N' : 'Y'
        }
    },
    // данные по типо плательщика
    personTypeList: state => {
        if (!_.isEmpty(state.order)) {
            return state.order['PERSON_TYPE']
        }
        return false
    },
    personType: (state, getters) => getters.getChecked(getters.personTypeList),
    personTypeId: (state, getters) => {
        return +getters.personType.ID || null
    },
    // данные по доставке
    deliveryList: (state, getters) => {
        let arDeliver = state.order['DELIVERY']

        return arDeliver
    },
    delivery: (state, getters) => {
        if(getters.deliveryList){
            let delivery = getters.getChecked(getters.deliveryList)
            let arDelivery = Object.values(getters.deliveryList)

            if (Array.isArray(delivery) && delivery.length === 0 && arDelivery.length >
                0) {
                delivery = null

                for (let deliveryItem of arDelivery) {
                    if (!delivery) {
                        delivery = deliveryItem
                        break
                    }
                }
            }
            return delivery
        }

        return null;
    },
    deliveryId: (state, getters) => {
        if(getters.delivery){
            return +getters.delivery.ID || null
        }
        return  null
    },

    // данные по платежным системам
    paymentList: state => {
        //return state.order['PAY_SYSTEM']
        return _.filter(state.order['PAY_SYSTEM'], (item) => {
            //TODO: вынести в переменную
            return item.ID != '10' ? true : false;
        })
    },
    payment: (state, getters) => getters.getChecked(getters.paymentList),
    paymentId: (state, getters) => {
        return +getters.payment.ID || null
    },
    // данные по профилям
    profileList: state => state.order['USER_PROFILES'],
    profile: (state, getters) => getters.getChecked(getters.profileList),
    profileId: (state, getters) => {
        return +getters.profile.ID || 0
    },
    getAjaxProcess: state => state.service.isAjaxProcess,
    // вспомогательные геттеры
    getChecked: () => data => {
        return _.find(data, item => {
            return item.CHECKED === 'Y'
        }) || []
    },
    getPropertyByCode: (state, getters) => code => _.find(getters.orderProperties, ['CODE', code]),
    getPropertyIdByCode: (state, getters) => code => getters.getPropertyByCode(code) ? getters.getPropertyByCode(code).ID : null,
    getPropertyValueByCode: (state, getters) => code => state.orderPropertiesValue[getters.getPropertyIdByCode(code)],

    getResponsePropertyValueByCode: (state, getters) => code => {
        let property = getters.getPropertyByCode(code)
        return property ? _.result(property, 'VALUE[0]', '') : ''
    },
    phone: (state, getters) => {
        let phone = getters.getPropertyByCode('PHONE')
        return phone ? _.result(phone, 'VALUE[0]', '') : ''
    },
    countryName: (state, getters) => _.find(state.countyList, (item) => {
        return item.id === state.service.countryId
    }).name,
    GET_ERROR_PROPERTY: state => code => state.errorListProperty[code],

    isPayCashItem: state => {
        if (state.order && state.order['PAY_SYSTEM']) {
            for (let payItem of state.order['PAY_SYSTEM']) {
                if (payItem.CODE == 'CASH') {
                    return true;
                }
            }
        }
        return false;
    },

    getDeliveryTypes: state => state.delivery.types,
    getDeliveryTypeSelected: state => find(state.delivery.types, 'selected'),
    getDeliveryPickupItems: state => state.delivery.pickupItems,
    getDeliveryPickupItemSelected: state => {
        var tmpIndex;
        for (tmpIndex in state.delivery.pickupItems) {
            var item = state.delivery.pickupItems[tmpIndex];
            if (item.selected == true) {
                return item
            }
        }
        return {'lat': 0, 'lon': 0};
    },
    getDeliveryItemSelected: state => {
        for (var tmpIndex in state.delivery.items) {
            var item = state.delivery.items[tmpIndex];
            if (item.selected == true) {
                return item
            }
        }
    },
    getDeliveryFieldByCode: state => code => {
        for (var incI in state.delivery.fields) {
            debugger
            if (incI == code) {
                return state.delivery.fields[incI]
            }
        }
    },


}
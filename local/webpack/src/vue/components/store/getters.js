import _ from 'lodash'

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
    getBasket: state => {
        return state.basket
    },
    getBasketValue: (state, getters) => {
        return state.basketValues.PAN
    },

    //Оформление заказа
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
    personType: (state, getters) => {
        return getters.getChecked(getters.personTypeList)
    },
    personTypeId: (state, getters) => {
        return +getters.personType.ID || null
    },
    // данные по доставке
    deliveryList: (state, getters) => {
        let arDeliver = state.order['DELIVERY']
        // let isDeletPickup = true;
        // switch ((state.service.cityName).toUpperCase()) {
        //     case 'КРАСНОЯРСК':
        //     case 'КРАСНОЯРС':
        //     case 'КРАСНОЯР':
        //     case 'RHFCYJZHCR':
        //     case 'RHFCYJZHC':
        //     case 'RHFCYJZH':
        //         isDeletPickup = false;
        //         break;
        // }
        // switch ((state.service.regionName).toUpperCase()) {
        //     case 'КРАСНОЯРСКИЙ КРАЙ':
        //         isDeletPickup = false;
        //         break;
        // }
        // if (isDeletPickup) {
        //     arDeliver = _.pickBy(state.order['DELIVERY'], (element, key) => {
        //         return (state.deliveryPickupId.indexOf(parseInt(key)) !== -1)
        //     })
        //     state.service.typeDeliveryId = 47
        // }else{
        //     // console.log(arDeliver[47])
        // }
        return arDeliver
    },
    delivery: (state, getters) => {
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
    },
    deliveryId: (state, getters) => {
        return +getters.delivery.ID || null
    },

    // данные по платежным системам
    paymentList: state => {
        //return state.order['PAY_SYSTEM']
        return _.filter(state.order['PAY_SYSTEM'], (item) => {
            //TODO: вынести в переменную
            return item.ID != '10' ? true : false;
        })
    },
    payment: (state, getters) => {
        return getters.getChecked(getters.paymentList)
    },
    paymentId: (state, getters) => {
        return +getters.payment.ID || null
    },
    // данные по профилям
    profileList: state => {
        return state.order['USER_PROFILES']
    },
    profile: (state, getters) => {
        return getters.getChecked(getters.profileList)
    },
    profileId: (state, getters) => {
        return +getters.profile.ID || 0
    },
    getAjaxProcess: state => {
        return state.service.isAjaxProcess
    },
    // вспомогательные геттеры
    getChecked: () => data => {
        return _.find(data, item => {
            return item.CHECKED === 'Y'
        }) || []
    },
    getPropertyByCode: (state, getters) => code => {
        return _.find(getters.orderProperties, ['CODE', code])
    },
    getPropertyIdByCode: (state, getters) => code => {
        let property = getters.getPropertyByCode(code)
        return property ? property.ID : null
    },
    getPropertyValueByCode: (state, getters) => code => {
        return state.orderPropertiesValue[getters.getPropertyIdByCode(code)]
    },

    getResponsePropertyValueByCode: (state, getters) => code => {
        let property = getters.getPropertyByCode(code)
        return property ? _.result(property, 'VALUE[0]', '') : ''
    },
    phone: (state, getters) => {
        let phone = getters.getPropertyByCode('PHONE')
        return phone ? _.result(phone, 'VALUE[0]', '') : ''
    },
    countryName: (state, getters) => {
        return _.find(state.countyList, (item) => {
            return item.id === state.service.countryId
        }).name
    },
    paymentListKorona: state => {
        if (state.koronaBalance.Balance !== null && state.koronaBalance.Balance > 0) {
            return _.filter(state.order['PAY_SYSTEM'], (item) => {
                //TODO: вынести в переменную
                return item.ID === '10' ? true : false;
            })
        }
        return []
    },
    getKoronaBalance: state => {
        return state.koronaBalance;
    },
    getKoronaBalanceCode: (state, getters) => code => {
        return state.koronaBalance[code] || false
    },

    GET_ERROR_PROPERTY: state  => code => {
        return  state.errorListProperty[code]
    },


}
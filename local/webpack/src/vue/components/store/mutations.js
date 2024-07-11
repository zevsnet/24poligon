import forEach from 'lodash/forEach'

export default {
    setBasket: (state, payload) => state.basket = payload,
    setStores: (state, payload) => state.stores = payload,
    setBasketSignedTemplate: (state, payload) => state.basketSignedTemplate = payload,
    setBasketSignedParams: (state, payload) => state.basketSignedParams = payload,
    setBasketSessid: (state, payload) => state.basketSessid = payload,
    setBasketItems: (state, payload) => state.basketItems = payload,
    setBasketItemId(state, payload) {
        let $newCount = 0
        console.log(payload)
        switch (payload.value) {
            case 'plus':
                $newCount = state.basketItems[payload.name]['QUANTITY'] + 1
                if ($newCount <= state.basketItems[payload.name]['AVAILABLE_QUANTITY']) {
                    state.basketItems[payload.name]['QUANTITY'] = $newCount
                    state.basketItemUpdate['QUANTITY_' + payload.name] = $newCount
                }
                break;
            case 'minus':
                $newCount = state.basketItems[payload.name]['QUANTITY'] - 1
                if (0 < $newCount && $newCount <= state.basketItems[payload.name]['AVAILABLE_QUANTITY']) {
                    state.basketItems[payload.name]['QUANTITY'] = $newCount
                    state.basketItemUpdate['QUANTITY_' + payload.name] = $newCount
                }
                break;
            case 'del':
                state.basketItemUpdate['DELETE_' + payload.name] = 'Y'
                break;
            case 'change':
                $newCount = payload.count
                if (0 < $newCount && $newCount <= state.basketItems[payload.name]['AVAILABLE_QUANTITY']) {
                    state.basketItemUpdate['QUANTITY_' + payload.name] = $newCount
                }
                break;
            case 'del_coupon':
                state.basketItemUpdate['delete_coupon'] = {}
                state.basketItemUpdate['delete_coupon'][payload.name] = payload.name
                break;
        }

    },
    clearBasketItemUpdate: (state) => state.basketItemUpdate = {},
    setBasketValues: (state, payload) => state.basketValues = payload,
    setBasketValue(state, payload) {
        let tmp = {}
        tmp[payload.name] = payload.value
        state.basketValues = {...state.basketValues, ...tmp}
    },

    SET_STEP: (state, step) => state.service.step = step,
    SET_ERROR_PROPERTY_VALUE: (state, payload) => state.errorListProperty = Object.assign({}, state.errorListProperty, {[payload.name]: payload.value}),
    //Оформление
    setOrder: (state, payload) => state.order = payload,

    setOrderValues: (state, payload) => state.orderValues = payload,
    setOrderValue(state, payload) {
        let tmp = {}
        tmp[payload.name] = payload.value
        state.orderValues = {...state.orderValues, ...tmp}
    },
    setOrderValuesAddition: (state, payload) => state.orderValuesAddition = payload,
    setOrderValueAddition: (state, payload) => state.orderValuesAddition[payload.name] = payload.value,
    setOrderPropertyValue: (state, payload) => state.orderPropertiesValue[payload.id] = payload.value,
    setOrderPropertiesValue: (state, payload) => state.orderPropertiesValue = payload,
    setOrderPropertiesDeliveryValue: (state, payload) => {
        let tmp = {}
        debugger
        tmp[payload.name] = payload.value
        state.orderPropertiesDeliveryValue = {...state.orderPropertiesDeliveryValue, ...tmp}
        },
    setPropertyGroupIdsCheckByStep: (state, payload) => state.propertyGroupIdsCheckByStep = payload,
    setErrors(state, payload) {

        state.errorList = []
        for (let key in payload.value) {
            if (payload.value.hasOwnProperty(key)) {
                console.log(payload.value[key])
                for (let key2 in payload.value[key]) {
                    if (payload.value[key][key2] == 'Склад обязательно для заполнения') {
                        payload.value[key][key2] = 'Необходимо выбрать склад самовывоза'
                    }
                }

                state.errorList = [...state.errorList, ...payload.value[key]]
            }
        }
    },
    addError: (state, payload) => state.errorList[payload.name].push(payload.value),
    setAjaxProcess: (state, value) => state.service.isAjaxProcess = value,
    setShowEnergy: (state, value) => state.service.showEnergy = value,
    setShowDPD: (state, value) => state.service.showDPD = value,
    setPhoneSuccess: (state, value) => state.service.isPhoneSuccess = value,
    setCountryId: (state, value) => state.service.countryId = value,

    triggeredOrder: (state) => state.service.triggerOrder = !state.service.triggerOrder,
    triggeredSaveOrder: (state) => state.service.triggerSaveOrder = !state.service.triggerSaveOrder,
    setCityName: (state, value) => state.service.cityName = value,
    setRegionName: (state, value) => state.service.regionName = value,
    SET_CHANG_DATE: (state) => {
        return state.service.isDate++
    },

    setOrderInfoDeliveryPickupId: (state, payload) => state.deliveryPickupId = payload,
    setTypeDeliveryId: (state, value) => state.service.typeDeliveryId = value,
    setDeliveryPoint: (state, payload) => state.delivery.pickupItems = payload,
    setDeliveryPickupId: (state, type) => state.service.typeDeliveryId = type,
    setDeliveryPickupItems: (state, list) => state.delivery.pickupItems = list,
    setDeliveryServiceFiledValueByCode: (state, {code, value}) => {

        let tmp = {};
        tmp[code] = value
        state.delivery.fields = {...state.delivery.fields, ...tmp}
    },
    setDeliveryPickupItemById: (state, id) => {
        state.delivery.pickupItems = forEach(state.delivery.pickupItems, e => {
            e['selected'] = id === e.id
        })
    },
    setDeliveryItems: (state, list) => {
        state.delivery.items = list;
    },
}
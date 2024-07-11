import _ from 'lodash'

export default {
    setDeliveryInit({commit, state, getters}, result) {
        commit('setDeliveryItems', result.data.delivery.items)
        commit('setDeliveryPickupItems', result.data.delivery.pickup)
    },
    setOrder({commit, state, getters}, order) {
        commit('setOrder', order)
        //commit('setDeliveryPickupItems', result.data.delivery.pickup)
        commit('setOrderPropertiesValue', _.reduce(order.ORDER_PROP.properties, (result, item) => {
            if (item['CODE'] == 'CITY') {
                if (item['VALUE'][0] == 'Красноярск') {
                    state.service.cityName = item['VALUE'][0]
                }
            }
            result[item['ID']] = _.result(item, 'VALUE[0]', '')
            return result
        }, {}))

        let orderValues = Object.assign({}, state.orderValues, {
            PERSON_TYPE: getters.personTypeId,
            DELIVERY_ID: getters.deliveryId,
            PAY_SYSTEM_ID: getters.paymentId,
            PROFILE_ID: getters.profileId
        })

        if (_.isEqual(orderValues, state.orderValues)) {
            return
        }
        commit('setOrderValues', orderValues)
    },
    async checkStep({commit, state, getters, dispatch}, step) {
        let isError = false;
        _.each(state.errorListProperty, function (index, val) {
            commit('SET_ERROR_PROPERTY_VALUE', {
                name: val,
                value: ''
            })
        });
        switch (state.service.step) {
            case 1: {
              if (getters.getPropertyValueByCode('ADDRESS') === '') {
                isError = true;
                commit('SET_ERROR_PROPERTY_VALUE', {
                  name: 'ADDRESS',
                  value: 'Поле '+ getters.getPropertyByCode('ADDRESS').NAME+' не заполненно'
                })
              }
                break
            }
        }
        if (!isError) {
            commit('SET_STEP', step)
        }
    },
    async SET_STEP({commit, dispatch}, step) {
        await dispatch('checkStep', step)
        // commit('SET_STEP', step)

    }, // Установить шаг
    setBasket({commit, state, getters}, basket) {
        commit('setBasket', basket)

        if (_.isEmpty(basket)) {
            return
        }
        commit('setBasketItems', basket.BASKET_ITEM_RENDER_DATA)
        commit('setStores', basket.STORES)
    },
    checkSelectedDelivery({commit, state, dispatch, getters}, payload) {
        let arDeliver = getters.deliveryList
        let isDeliverySelected = false
        let deliveryId = false
        for (let key in arDeliver) {
            if (!deliveryId) {
                deliveryId = key
            }

            if (arDeliver[key]['CHECKED'] === 'Y') {
                isDeliverySelected = true
            }
        }

        if (!isDeliverySelected) {
            commit('setOrderValue', {
                name: 'DELIVERY_ID',
                value: deliveryId
            })
        }
    },

    nextStep({state, dispatch}) {
        dispatch('SET_STEP', state.service.step + 1)
    },  // Установить следующий шаг
    prevStep({state, dispatch}) {
        if (state.service.step !== 1)
            state.service.step = state.service.step - 1
    },  // Установить пред шаг
}

export default {
    basket: {},
    stores: {},
    basketItems: {},
    basketItemUpdate: {},
    basketValues: {
        selectedStore: null,
        defaultStore: null,
        PAN:''
    },

    order: {},
    orderPropertiesValue: {},//Свойства заказа
    propertyGroupIdsCheckByStep: {},//Группы свойств
    orderValues: {
        PERSON_TYPE: null,
        DELIVERY_ID: null,
        PAY_SYSTEM_ID: null,
        PROFILE_ID: null,
        PAY_CURRENT_ACCOUNT: 'N',
        PAY_CURRENT_ACCOUNT_SB: 'N'
    },
    orderValuesAddition: {
        PERSON_TYPE_OLD: null,
        PROFILE_ID_OLD: null,
        profile_change: 'N',
        ZIP_PROPERTY_CHANGED: 'Y'
    },
    deliveryPickupId: [2],
    koronaBalance: {
        COUPON: false,
        PAN: false,
        Balance: null,
        BalanceFormat: null,
        Procent: null,
        maxSaleBonus: 0,
        PAY_TOKEN: '',
        tokenRequired: 'Y'
    },
    service: {
        step: 1,
        isDate: 1,
        countryId: 24,
        typeDeliveryId: 'N',
        cityName: '',
        regionName: '',
        isAjaxProcess: false,
        isPhoneSuccess: true,//Номер подтвержден?
        showEnergy: false,
        showDPD: false,
        triggerOrder: false, // используется для генерации перезагрузки заказа
        triggerSaveOrder: false // используется для генерации сохранения заказа
    },
    countyList: [
        {
            id: 24,
            image: '/bitrix/images/delivery_edost_img/flag/0.gif',
            name: 'Россия'
        }
    ],
    errorList: [],
    errorListProperty: {},
}

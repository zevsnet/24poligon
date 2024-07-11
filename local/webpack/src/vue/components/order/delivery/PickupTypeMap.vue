<template>
    <div class="order-delivery-pickup-selector">
        <div class="order-delivery-pickup-selector_title">
            Выберите пункт выдачи
        </div>
        <div class="order-delivery-pickup-selector_row row">
            <div class="order-delivery-pickup-selector_list col-md-3">
                <VueScrollbar :maxHeight="heightSlider">
                    <div class="sb_filter_block">
                        <input type="text" v-model="filterText" placeholder="Фильтр">
                    </div>
                    <div
                            v-for="item in filteredDeliveryPickupItems"
                            :key="item.id"
                            :class="{active: item.selected}"
                            class="order-delivery-pickup-selector_list-item"
                            @click="onClickItem(item.id)">

                        <div class="title">{{ item.title }}</div>
                        <div class="description">{{ item.description }}</div>
                    </div>
                </VueScrollbar>
            </div>
            <div ref="mapWrapper"
                 class="order-delivery-pickup-selector_map col-md-9">
                <yandexMap
                        ref="map"
                        :setting="mapSettings"
                        :zoom="zoom"
                        :controls="['zoomControl']"
                        :coords="getCoords">
                    <ymapMarker
                            v-for="item in getDeliveryPickupItems"
                            :marker-id="item.id"
                            :key="item.id"
                            :coords="[item.lat, item.lon]"
                            :icon="markerIcon('def')"
                            :balloon-template="getBalloonTemplate(item)"
                    />
                </yandexMap>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters, mapMutations} from 'vuex'
    import VueScrollbar from 'vue-scrollbar-live'
    import {yandexMap, ymapMarker} from 'vue-yandex-maps'
    import 'vue-scrollbar-live/lib/css/index.css'
    import axios from 'axios'

    export default {
        name: 'PickupTypeMap',
        components: {
            VueScrollbar,
            yandexMap,
            ymapMarker
        },
        data: function () {
            return {
                filterText: '',
                zoom: 15,
                heightSlider: 550,
                mapSettings: {
                    apiKey: '58bfd326-1466-476a-b492-5327dfd7d983',
                    lang: 'ru_RU'
                }
            }
        },
        mounted: function () {
            window.$(this.$refs.mapWrapper).on('click', '.balloon-container .button', this.onClickBalloon.bind(this))
            window.addEventListener('resize', this.changeHeightSlider.bind(this))
            this.changeHeightSlider()
        },
        computed: {
            ...mapGetters([
                'getDeliveryPickupItems',
                'getDeliveryPickupItemSelected',
                'getDeliveryItemSelected',
                'getDeliveryFieldByCode',
                'getPropertyByCode'
            ]),
            filteredDeliveryPickupItems() {
                return this.getDeliveryPickupItems.filter(item => {

                    return item.description.toLowerCase().includes(this.filterText.toLowerCase());
                });
            },
            getCoords: function () {
                return [
                    this.getDeliveryPickupItemSelected.lat,
                    this.getDeliveryPickupItemSelected.lon
                ]
            },
            getDeliveryItem: function () {
                debugger
                this.pickupChangeHandler()
                return this.getDeliveryItemSelected
            },
            getDeliveryPickupItemsText: function () {
                debugger
                for (var item in this.getDeliveryPickupItems) {
                    debugger
                }
            }
        },
        methods: {
            ...mapMutations([
                'setDeliveryPickupItemById',
                'setOrderPropertyValue',
                'setOrderPropertiesDeliveryValue',
                'setDeliveryServiceFiledValueByCode'
            ]),
            markerIcon: function (idSelivery) {
                switch (idSelivery) {
                    case 21:
                        return {
                            layout: 'default#imageWithContent',
                            imageHref: '/images/5post.svg',
                            imageSize: [30, 43],
                            imageOffset: [0, 0],
                            iconShape: {
                                type: 'Rectangle',
                                coordinates: [
                                    [-20, -40], [10, 0]
                                ]
                            }
                        }
                    case 18:
                    default:
                        return {
                            layout: 'default#imageWithContent',
                            imageHref: '/images/maps-icon.png',
                            imageSize: [24, 34],
                            imageOffset: [-12, -17]
                        }
                }
            },
            getBalloonTemplate: function (item) {
                return `<div class="balloon-container">
          <div class="name">${item.title}</div>
          <div class="desc">${item.description}</div>
          <div class="button" data-id="${item.id}">Выбрать</div>
          </div>`
            },
            onClickBalloon: function (e) {
                let id = e.target.dataset.id
                this.changeSelectedItem(id)
                this.$refs.map.myMap.balloon.close()
            },
            onClickItem: function (id) {
                this.changeSelectedItem(id)
            },
            changeSelectedItem: function (id) {
                this.setDeliveryPickupItemById(id)
                this.zoom = 15
                // hack set zoom
                this.$refs.map.myMap.setZoom(this.zoom)
                this.pickupChangeHandler()
            },
            changeHeightSlider: function () {
                if (window.innerWidth < 768) {
                    this.heightSlider = 300
                } else {
                    this.heightSlider = 550
                }
            },
            pickupChangeHandler: function () {
                if (!this.getDeliveryItemSelected) {
                    return
                }
                switch (+this.getDeliveryItemSelected.id) {
                    // case 176:
                    //     this.dpdHandler()
                    //     break
                    case 40:
                        this.storePickupHandler()
                        break
                    // case 36:
                    //     this.boxberryHandler()
                    //     break
                    case 81:
                    case 73:

                        this.sdekHandler()
                        break
                    // case 21:
                    //     this.fivepostHandler()
                    //     break
                    case 83://Dostavista
                        // this.fivepostHandler()
                        break
                }
            },
            ozonPostHandler: function () {
                this.setDeliveryServiceFiledValueByCode({
                    code: 'ADDRESS',
                    value: this.getDeliveryPickupItemSelected.title
                })
            },
            russianPostHandler: function () {
                this.setDeliveryServiceFiledValueByCode({
                    code: 'ADDRESS',
                    value: this.getDeliveryPickupItemSelected.title
                })
                this.setDeliveryServiceFiledValueByCode({
                    code: 'ZIP',
                    value: this.getDeliveryPickupItemSelected.description
                })
                this.setDeliveryServiceFiledValueByCode({
                    code: 'RUSSIANPOST_TYPEDLV',
                    value: 'PARCEL_CLASS_1'
                })
            },
            dpdHandler: function () {
                this.setDeliveryServiceFiledValueByCode({
                    code: 'PCL',
                    value: this.getDeliveryPickupItemSelected.id
                })

                this.setDeliveryServiceFiledValueByCode({
                    code: 'ADDRESS',
                    value: this.getDeliveryPickupItemSelected.description
                })
            },
            storePickupHandler: function () {
                const street = this.getDeliveryPickupItemSelected.description
                const address = `${street}`

                if (this.getPropertyByCode('ADDRESS'))
                    this.setOrderPropertyValue({id: this.getPropertyByCode('ADDRESS').ID, value: address})


                //BUYER_STORE
                this.setDeliveryServiceFiledValueByCode({
                    code: 'BUYER_STORE',
                    value: this.getDeliveryPickupItemSelected.id
                })

                if (this.getPropertyByCode('PICKUP_POINT')){


                    this.setOrderPropertyValue({
                        id: this.getPropertyByCode('PICKUP_POINT').ID,
                        value: this.getDeliveryPickupItemSelected.id
                    })

                }


            },
            boxberryHandler: function () {
                const code = this.getDeliveryPickupItemSelected.id
                const location = this.getDeliveryPickupItemSelected.description
                const address = `Boxberry: ${location} #${code}`

                if (this.getPropertyByCode('ADDRESS'))
                    this.setOrderPropertyValue({
                        id: this.getPropertyByCode('ADDRESS').ID,
                        value: this.getDeliveryPickupItemSelected.description
                    })

                const bodyFormData = new FormData()
                bodyFormData.set('save_pvz_id', this.getDeliveryPickupItemSelected.id)
                bodyFormData.set('change_location', this.getDeliveryFieldByCode('LOCATION').data.shortName)
                bodyFormData.set('address', location)
                axios.post('/bitrix/js/up.boxberrydelivery/ajax.php', bodyFormData, {
                    headers: {'Content-Type': 'multipart/form-data'},
                    baseURL: '/'
                })

                this.setDeliveryServiceFiledValueByCode({
                    code: 'ADDRESS',
                    value: address
                })
            },
            energyHandler: function () {
                const location = this.getDeliveryFieldByCode('LOCATION').value
                const street = this.getDeliveryPickupItemSelected.description
                const address = `${location}, ${street}`

                this.setDeliveryServiceFiledValueByCode({
                    code: 'ADDRESS',
                    value: address
                })
            },
            sdekHandler: function () {
                // debugger
                // if(this.getPropertyByCode('ADDRESS')){
                //     const city = this.getPropertyByCode('CITY').VALUE
                // }
                //const city = this.getDeliveryFieldByCode('LOCATION').data.shortName
                const street = this.getDeliveryPickupItemSelected.description
                const code = this.getDeliveryPickupItemSelected.id
                const address = `${street} #S${code}`
                if (address) {
                    if (this.getPropertyByCode('ADDRESS'))
                        this.setOrderPropertyValue({id: this.getPropertyByCode('ADDRESS').ID, value: address})
                }
            },
            fivepostHandler: function () {
                /* Нужно сделать расчет стоимости доставки на эту точку */
                const street = this.getDeliveryPickupItemSelected.description
                const code = this.getDeliveryPickupItemSelected.title
                const address = `${street} ${code}`
                if (address) {
                    if (this.getPropertyByCode('ADDRESS'))
                        this.setOrderPropertyValue({id: this.getPropertyByCode('ADDRESS').ID, value: address})
                    this.setDeliveryServiceFiledValueByCode({
                        code: 'IPOL_FIVEPOST_PVZ',
                        value: this.getDeliveryPickupItemSelected.id
                    })
                }
            }
        }
    }
</script>

import common from '../../mixins/common'
import axios from 'axios'
import {mapState, mapGetters, mapMutations} from 'vuex'

export default {
    name: 'dpd-delivery',
    mixins: [common],
    data: function () {
        return {
            city: [],
            currentPoint: {},
            currentMidx: 0,
            autoCompleteCities: [],
            points: [],
            center: {lat: 10, lng: 10},
            zoom: 7,
            infoContent: '',
            infoWindowPos: {
                lat: 0,
                lng: 0
            },
            infoWinOpen: false,
            oldIndexMarker: 0,
            infoOptions: {
                pixelOffset: {
                    width: 0,
                    height: -35
                }
            }
        }
    }, // Данные приложения
    created() {
    }, // Действия при создании
    mounted() {
        window.$('#modal-dpd').on('hidden.bs.modal', () => {
            this.setShowDPD(false)
        })
    },
    computed: {
        ...mapState([
            'service',
            'order'
        ]),
        ...mapGetters([
            'countryName',
            'getPropertyIdByCode'
        ]),
        isValidPoints() {
            return this.points.length > 0
        }
    }, // Вычисляемые данные
    watch: {
        'service.cityName': {
            handler() {
                this.loadPVZ2City()
                this.selectCity()
            },
            immediate: true
        },
        'service.showDPD'(value) {
            let modal = window.$('#modal-dpd')
            value ? modal.modal('show') : modal.modal('hide')
            this.infoWinOpen = false

        }
    }, // Наблюдатели
    methods: {
        ...mapMutations([
            'setShowDPD',
            'triggeredOrder',
            'setOrderPropertyValue'
        ]),
        loadPVZ2City() {
            let vm = this
            let params = new FormData()

            // switch ((this.service.cityName).toUpperCase()) {
            //     case 'КРАСНОЯРСК':
            //     case 'КРАСНОЯРС':
            //     case 'КРАСНОЯР':
            //     case 'RHFCYJZHCR':
            //     case 'RHFCYJZHC':
            //     case 'RHFCYJZH':
            //         this.service.cityName ='Красноярск';
            //         break;
            // }

            params.append('cityName', this.service.regionName)
            params.append('SUMMA_ORDER', this.order.TOTAL.ORDER_TOTAL_PRICE)

            axios({
                method: 'post',
                url: '/ajax/Main/getPVZ2City',
                data: params
            }).then(function (response) {
                if (response.data.data.CITY) {
                    vm.points = response.data.data.CITY
                    vm.center = vm.getPosition(vm.points[0])
                }
            }).catch(error => {
                console.log(error)
            })
        },
        setCenter(point) {
            this.center = this.getPosition(point)
        },
        selectCity() {

            let latitude = 0
            let longitude = 0
            let count = 0
            for (const index in this.points) {
                if (!this.points.hasOwnProperty(index)) {
                    continue
                }
                latitude += this.points[index]['LAT']
                longitude += this.points[index]['LON']

                count++
            }
            this.center = {lat: latitude, lng: longitude}

        },
        getPosition(point) {
            return {lat: point['LAT'], lng: point['LON']}
        },
        selectPoint(marker, idx) {
            this.infoWindowPos = {lat: marker.LAT, lng: marker.LON}
            this.infoContent = marker.TEXT
            // check if its the same marker that was selected if yes toggle
            if (this.currentMidx === idx) {
                //this.infoWinOpen = !this.infoWinOpen;
            }
            //if different marker set infowindow to open and reset current marker
            // index
            else {
                this.infoWinOpen = true
                this.currentMidx = idx
            }

            this.center = this.getPosition(marker)
            this.currentPoint = marker

            this.setOrderPropertyValue({
                id: this.getPropertyIdByCode('ADDRESS'),
                value: marker.ADDR
            })
            this.setOrderPropertyValue({
                id: this.getPropertyIdByCode('PICKUP_POINT'),
                value: marker.CODE
            })
            this.triggeredOrder()
            if (marker.ZIP) {
              this.setOrderPropertyValue({
                id: this.getPropertyIdByCode('ZIP'),
                value: marker.ZIP
              })
            }

        },
        currentDay() {
            return (new Date()).getDay()
        },
        close_modal() {
            this.infoWinOpen = false
        }
    } // Методы
}

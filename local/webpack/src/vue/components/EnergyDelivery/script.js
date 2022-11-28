import common from '../../mixins/common'
import { mapState, mapGetters, mapMutations } from 'vuex'

export default {
  name: 'energy-pickup-point',
  mixins: [common],
  props: {
    application: {
      type: Object,
      required: true
    }
  },
  data: function () {
    return {
      currentPoint: {},
      autoCompleteCities: [],
      points: [],
      center: {lat: 10.0, lng: 10.0},
      zoom: 16,
    }
  }, // Данные приложения
  created () {
  }, // Действия при создании
  mounted () {
    window.$('#modal-energy')
      .on('hidden.bs.modal', () => {
        this.setShowEnergy(false)
      })
  },
  computed: {
    ...mapState([
      'service'
    ]),
    ...mapGetters([
      'countryName',
      'getPropertyIdByCode'
    ]),
    isValidPoints () {
      return this.points.length > 0
    }
  }, // Вычисляемые данные
  watch: {
    'service.cityName': {
      handler (value) {
        this.selectCity(value)
      },
      immediate: true
    },
    'service.showEnergy' (value) {
      let modal = window.$('#modal-energy')
      value ? modal.modal('show') : modal.modal('hide')
    }
  }, // Наблюдатели
  methods: {
    ...mapMutations([
      'setShowEnergy',
      'setOrderPropertyValue'
    ]),
    setCenter (point) {
      this.center = this.getPosition(point)
    },
    getCityByName (cityName) {
      cityName = cityName.toLowerCase()

      return _.find(this.application['cities'], city => {
        let currentCity = city.name.toLowerCase()
        if (currentCity === cityName) {
          return true
        }
        if (currentCity.indexOf(cityName) >= 0) {
          return true
        }
      })
    },
    async findCity (cityName) {
      let city = this.getCityByName(cityName)
      if(!city) {
        city = await this.$http.get('https://geocode-maps.yandex.ru/1.x/?format=json&geocode=' + this.countryName + '+' + cityName + '&results=1')
          .then(response => {
            return this.getCityByName(response.data.response.GeoObjectCollection.featureMember[0].GeoObject.name)
          })
          .catch(error => {
            console.log(error)
          })
      }
      return city
    },
    selectCity (cityName) {
      this.findCity(cityName)
        .then(city => {
          this.points = []
          if (!city) {
            return
          }
          if (city.warehouses) {
            _.forEach(city.warehouses, warehouse => {
              if (+warehouse.id > 0) {
                this.points.push(warehouse)
              }
            })
          }
          let latitude = 0
          let longitude = 0
          let count = 0
          for (const index in this.points) {
            if (!this.points.hasOwnProperty(index)) {
              continue
            }
            latitude += this.points[index]['latitude']
            longitude += this.points[index]['longitude']
            count++
          }
          this.center = {lat: latitude / count, lng: longitude / count}
        })
        .catch(error => {
          console.log(error)
        })
    },
    getPosition (point) {
      return {lat: point['latitude'], lng: point['longitude']}
    },
    selectPoint (point) {
      this.center = this.getPosition(point)
      this.currentPoint = point
      console.log('Энергия выбор точки', Object.assign({}, point))

      this.setOrderPropertyValue({
        id: this.getPropertyIdByCode('ADDRESS'),
        value: this.service.cityName + ' ' + point.address
      })
      if (point.zipcode) {
        this.setOrderPropertyValue({
          id: this.getPropertyIdByCode('ZIP'),
          value: point.zipcode
        })
      }
    },
    currentDay () {
      return (new Date()).getDay()
    }
  } // Методы
}

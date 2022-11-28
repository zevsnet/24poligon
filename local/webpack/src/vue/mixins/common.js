import _ from 'lodash'
import md5 from 'md5'

export default {
  methods: {
    getCheckedItemId (dataList) {
      for (const index in dataList) {
        if (!dataList.hasOwnProperty(index)) {
          continue
        }
        if (dataList[index].CHECKED === 'Y') {
          return _.toInteger(dataList[index].ID)
        }
      }
      return 0
    }, // Получение ИД элемента по CHECKED = Y
    getCheckedItem (dataList) {
      for (const index in dataList) {
        if (!dataList.hasOwnProperty(index)) {
          continue
        }
        if (dataList[index].CHECKED === 'Y') {
          return dataList[index]
        }
      }
      return false
    },
    logToConsole (...values) {
      if (this.application.info.isDebugMode) {
        console.log(...values)
      }
    },
    getMd5 (string) {
      return md5(string)
    }
  }
}

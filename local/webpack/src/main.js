/** Точка входа в приложение */
/** Подключение стилей */
import './scss/main.scss'
import VueRouter from 'vue-router'

async function loadVue() {
  return import('./vue/main')
}

function loadChunks() {
  loadVue()
}

loadChunks()

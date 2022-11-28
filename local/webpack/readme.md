#Webpack шаблон

```
yarn // (или npm install) Устанавливает завсимости
npm run watch // Смотрит за файлами
npm run start // Смотрит за файлами с hot-reload'ом
npm run build // Билдит для прода
```
>Для работы подключить из папки dist файл 'index.php

#Конфиг
webpack.common.js - Базовый конфиг вебпака

webpack.dev.js - Конфиг для разработки

webpack.prod.js - Конфиг для продакшена

#dist
dist/bundle.js - главный бандл содержащий js и css

dist/svg-loader.js - иконки svg

#src
src/js/ - папка c js

src/scss/ - папка со стилями

src/img/ - папка с картинками

src/img/icons/ - папка с svg иконками

src/vue/ - папка с компонентами vue

src/react/ - папка с компонентами react

**Пример использования svg-loader**
```html
 <span class="svg svg__iconName"></span>
```
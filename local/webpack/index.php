<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<!-- Компонент на Vue -->
<div id="vue-app" data-initial-data='world'></div>
<!-- Компонент на React -->
<div id="react-app"></div>

<div class="svg-icon svg-example"></div>
<div class="image-test"></div>

<!-- На проде (npm run build)  или если используется watching (npm run watch) подключать бандл + svg из папки -->
<!--<script src="dist/bundle.js"></script>-->
<?// include __DIR__ . '/dist/index.php' ?>
<!--<script src="dist/svg-loader.js"></script>-->

<!-- Подключать с dev сервера webpack'а если используется hot-reload (npm run start) -->
<script src="https://localhost:8080/bundle.js"></script>
<script src="https://localhost:8080/svg-loader.js"></script>
</body>
</html>
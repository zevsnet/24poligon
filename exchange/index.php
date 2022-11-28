<?php
@define('NEED_AUTH', true);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Тест обмена</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="/local/templates/.default/css/reset.css">
</head>
<body>
<div class="page">
    <aside class="links">
        <a href="javascript:;" class="links__item" data-type="catalog" data-filename="price.xml" data-mode="import">Импорт price.xml</a>
        <a href="javascript:;" class="links__item" data-type="catalog" data-filename="import.xml" data-mode="import">Импорт import.xml</a>
        <hr>
        <a href="javascript:;" class="links__item" data-type="looks" data-filename="looks.xml" data-mode="reset">Сброс</a>
    </aside>
    <div class="content">
        <div class="respond"></div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="script.js"></script>
</body>
</html>

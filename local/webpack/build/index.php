<? /** В этот файл инжектится JS с хешем, подключать из папки src */
$asset = \Bitrix\Main\Page\Asset::getInstance();
?>

<? $asset->addCss('/local/webpack/build/main.31831f57b8261c89b438.css'); ?>


<? $asset->addJs('/local/webpack/build/main.31831f57b8261c89b438.js'); ?>



<? $asset->addJs('/local/webpack/build/svg-loader.js'); ?>


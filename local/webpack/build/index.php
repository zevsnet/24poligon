<? /** В этот файл инжектится JS с хешем, подключать из папки src */
$asset = \Bitrix\Main\Page\Asset::getInstance();
?>

<? $asset->addCss('/local/webpack/build/main.43c8480492012f597ab2.css'); ?>


<? $asset->addJs('/local/webpack/build/main.43c8480492012f597ab2.js'); ?>



<? $asset->addJs('/local/webpack/build/svg-loader.js'); ?>


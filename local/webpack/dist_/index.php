<? /** В этот файл инжектится JS с хешем, подключать из папки src */
$asset = \Bitrix\Main\Page\Asset::getInstance();
?>

<? $asset->addCss('/local/webpack/dist/main.c62180d0a8685b8cc578.css'); ?>


<? $asset->addJs('/local/webpack/dist/main.c62180d0a8685b8cc578.js'); ?>



<? $asset->addJs('/local/webpack/dist/svg-loader.js'); ?>


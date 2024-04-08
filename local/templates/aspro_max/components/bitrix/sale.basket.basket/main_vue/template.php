<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();


if ($_REQUEST['DEV'] == 'Y') {
    include $_SERVER['DOCUMENT_ROOT'] . '/local/webpack/dist/index.php';
} else {
    include $_SERVER['DOCUMENT_ROOT'] . '/local/webpack/build/index.php';
}

$documentRoot = Bitrix\Main\Application::getDocumentRoot();

$var = [
    'type' => 'basket',
    'component' => [
        'siteId' => $component->getSiteId(),
        'ajaxUrl' => $component->getPath() . '/ajax.php',
    ],
];
?>
<div id="basket" data-application='<?= htmlspecialchars(json_encode($var)) ?>'>
    <div class="sb_preloader"></div>
</div>




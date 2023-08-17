<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if ($templateData['ITEMS'] && ($arParams['SLIDER_MODE'] === 'Y' || $arParams['MENU_BANNER'])):?>
    <?\Aspro\Max\Functions\Extensions::init('owl_carousel');?>
<?endif;?>
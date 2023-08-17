<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if (!isset($arParams["NOT_SLIDER"]) || $arParams["NOT_SLIDER"] != "Y"):?>
    <?\Aspro\Max\Functions\Extensions::init('owl_carousel');?>
<?endif;?>
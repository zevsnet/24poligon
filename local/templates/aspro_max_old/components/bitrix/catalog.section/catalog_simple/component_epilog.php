<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $templateData */
/** @var @global CMain $APPLICATION */
use Bitrix\Main\Loader;
$arScripts = ['bonus_system'];
if (isset($arParams['SLIDE_ITEMS']) && $arParams['SLIDE_ITEMS']):?>
    <?$arScripts[] = 'owl_carousel';?>
<?endif;?>
<?\Aspro\Max\Functions\Extensions::init($arScripts);?>
<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Config\Option,
    Bitrix\Main\Loader,
    Tanais\Order\Theme;

/**
 * @var array $arParams
 * @var array $arResult
 * @var SaleOrderAjax $component
 */

if (!Loader::includeModule("tanais.order")) {
	return;
}

$component = $this->__component;
$component::scaleImages($arResult['JS_DATA'], $arParams['SERVICES_IMAGES_SCALING']);

$arParams['ORDER_THEME'] = Option::get("tanais.order", "ORDER_THEME", "first");

if ($arParams['ORDER_THEME'] == "other")
{
    $firstColor = Option::get("tanais.order", "COLOR_INPUT_FIRST");
    $secondColor = Option::get("tanais.order", "COLOR_INPUT_SECOND");
    $headerColor = Option::get("tanais.order", "COLOR_INPUT_HEADER");

    $arResult['THEME']["COLOR_FIRST"] = Theme::hsl2Array($firstColor);
    $arResult['THEME']["COLOR_SECOND"] = Theme::hsl2Array($secondColor);
    $arResult['THEME']["COLOR_HEADER"] = $headerColor;
}

<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arParams = array_merge(
	[
		"CATEGORY_OTHERS_TITLE" => "OTHER",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"CONTAINER_ID" => "title-search_mobile_search",
		"INPUT_ID" => "title-search-input_mobile_search",
		"PREVIEW_HEIGHT" => "38",
		"PREVIEW_TRUNCATE_LEN" => "50",
		"PREVIEW_WIDTH" => "38",
		"SHOW_ANOUNCE" => "N",
		"SHOW_INPUT" => "Y",
		"SHOW_OTHERS" => "N",
		"SHOW_PREVIEW" => "Y",
		"PRICE_CODE" => array(
			0 => "BASE",
		),
		"PRICE_VAT_INCLUDE" => "Y",
		"CONVERT_CURRENCY" => "N",
		"NUM_CATEGORIES" => "1",
		"COMPONENT_TEMPLATE" => "mobile_search"
	],
	Aspro\Max\SearchTitle::getConfig()
);
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:search.title",
	"mobile_search",
	$arParams,
	false, array("HIDE_ICONS" => "Y")
);?>

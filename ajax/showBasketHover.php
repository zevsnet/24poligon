<?define("STATISTIC_SKIP_ACTIVITY_CHECK", "true");?>
<?define('STOP_STATISTICS', true);
define('PUBLIC_AJAX_MODE', true);?>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
\Bitrix\Main\Loader::includeModule("aspro.max");
include_once("action_basket.php");
$APPLICATION->IncludeComponent("bitrix:sale.basket.basket", "top_hover", array(
	"COLUMNS_LIST" => array(
		0 => "NAME",
		1 => "QUANTITY",
		2 => "DELETE",
		3 => "DELAY",
		4 => "PRICE",
		5 => "TYPE",
		6 => "SUM",
		7 => "PROPS",
		8 => "DISCOUNT",
	),
	"OFFERS_PROPS" => array(
		0 => "SIZES",
		1 => "COLOR_REF",
	),
	"HIDE_COUPON" => "N",
	"PRICE_VAT_SHOW_VALUE" => "Y",
	"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
	"USE_PREPAYMENT" => "N",
	"SET_TITLE" => "N",
	"AJAX_MODE_CUSTOM" => "Y",
	"SHOW_MEASURE" => "Y",
	"PICTURE_WIDTH" => "70",
	"PICTURE_HEIGHT" => "70",
	"PATH_TO_BASKET" => CMax::GetFrontParametrValue("BASKET_PAGE_URL"), 
	"PATH_TO_ORDER" => CMax::GetFrontParametrValue("ORDER_PAGE_URL"), 
	"PATH_TO_AUTH" => SITE_DIR."auth/",
	"PATH_TO_COMPARE" => CMax::GetFrontParametrValue("COMPARE_PAGE_URL"),
	"SHOW_FULL_ORDER_BUTTON" => "N",
	"SHOW_FAST_ORDER_BUTTON" => "Y",
	'TOTAL_BLOCK_DISPLAY' => array('bottom'),
	),
	false
);
?>
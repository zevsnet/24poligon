<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
header('Content-type: application/json');
$APPLICATION->IncludeComponent("bitrix:sale.basket.basket.small", "empty", array(
	"PATH_TO_BASKET" => SITE_DIR."basket/",
	"PATH_TO_ORDER" => SITE_DIR."order/",
	), false, array("HIDE_ICONS" => "Y")
);
?>
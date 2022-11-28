<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("YENISITE_MARKET_TEMPLATE_NAME"),
	"DESCRIPTION" => GetMessage("YENISITE_MARKET_TEMPLATE_DESCRIPTION"),
	"ICON" => "/images/icon-blank.gif",
	"CACHE_PATH" => "Y",
	"SORT" => 30,
	"PATH" => array(
		"ID" => "romza",
		"NAME" => GetMessage("ROMZA_COMPONENTS"),
		"CHILD" => array(
			"ID" => "catalog_rz",
			"NAME" => GetMessage("YENISITE_DESC_CATALOG"),
			"SORT" => 30
		)
	)
);

?>
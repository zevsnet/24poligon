<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arTemplateParameters = array(
	"TITLE_BLOCK" => Array(
		"NAME" => GetMessage("TITLE_BLOCK_NAME"),
		"TYPE" => "STRING",
		"DEFAULT" => GetMessage("BLOCK_NAME"),
	),
	"TITLE_BLOCK_ALL" => Array(
		"NAME" => GetMessage("TITLE_BLOCK_ALL_NAME"),
		"TYPE" => "STRING",
		"DEFAULT" => GetMessage("BLOCK_ALL_NAME"),
	),
	"ALL_URL" => Array(
		"NAME" => GetMessage("ALL_URL_NAME"),
		"TYPE" => "STRING",
		"DEFAULT" => "sale/",
	),
	"IMG_POSITION" => Array(
		"NAME" => GetMessage("IMG_POSITION_NAME"),
		"TYPE" => "LIST",
		"VALUES" => array("right" => GetMessage("RIGHT_POS"), "left" => GetMessage("LEFT_POS")),
		"DEFAULT" => "right",
	),
	"NO_MARGIN" => Array(
		"NAME" => GetMessage("NO_MARGIN_NAME"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	),
	"FILLED" => Array(
		"NAME" => GetMessage("FILLED_NAME"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	),
	"BG_POSITION" => Array(
		"NAME" => GetMessage("BG_POSITION_NAME"),
		"TYPE" => "LIST",
		"VALUES" => array(
			"top left" => GetMessage("TOP_LEFT"),
			"top center" => GetMessage("TOP_CENTER"),
			"top right" => GetMessage("TOP_RIGHT"),
			"center left" => GetMessage("CENTER_LEFT"),
			"center" => GetMessage("CENTER_CENTER"),
			"center right" => GetMessage("CENTER_RIGHT"),
			"bottom left" => GetMessage("BOTTOM_LEFT"),
			"bottom center" => GetMessage("BOTTOM_CENTER"),
			"bottom right" => GetMessage("BOTTOM_RIGHT")
		),
		"DEFAULT" => "",
	),
);
?>

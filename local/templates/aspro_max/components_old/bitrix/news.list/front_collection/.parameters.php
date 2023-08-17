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
	"VIEW_TYPE" => Array(
		"NAME" => GetMessage("VIEW_TYPE_NAME"),
		"TYPE" => "LIST",
		"VALUES" => array("bg_img" => GetMessage("BG_PICT_NAME"), "grey_pict" => GetMessage("GREY_PICT_NAME"), "normal" => GetMessage("OLD_NAME"),"wd_pict" => GetMessage("WD_PICT_NAME")),
		"DEFAULT" => "grey_pict",
	),
	"SIZE_IN_ROW" => Array(
		"NAME" => GetMessage("SIZE_IN_ROW_NAME"),
		"TYPE" => "LIST",
		"VALUES" => array(5 => 5, 4 => 4, 3 => 3),
		"DEFAULT" => 5,
	),
	"INCLUDE_FILE" => Array(
		"NAME" => GetMessage("INCLUDE_FILE_NAME"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
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
	"MOBILE_TEMPLATE" => Array(
		"NAME" => GetMessage("T_MOBILE_TEMPLATE"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	),
);
?>

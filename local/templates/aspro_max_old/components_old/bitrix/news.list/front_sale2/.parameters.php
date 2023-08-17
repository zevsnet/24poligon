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
	"INCLUDE_FILE" => Array(
		"NAME" => GetMessage("INCLUDE_FILE_NAME"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	),
	"TYPE_IMG" => Array(
		"NAME" => GetMessage("TYPE_IMG_NAME"),
		"TYPE" => "LIST",
		"VALUES" => array("bg" => GetMessage("BG_IMG"), "sm" => GetMessage("SM_IMG"), "md" => GetMessage("MD_IMG"), "lg" => GetMessage("BIG_IMG")),
		"DEFAULT" => "bg",
	),
	"SIZE_IN_ROW" => Array(
		"NAME" => GetMessage("SIZE_IN_ROW_NAME"),
		"TYPE" => "LIST",
		"VALUES" => array(4 => 4, 3 => 3),
		"DEFAULT" => 4,
	),
	"NO_MARGIN" => Array(
		"NAME" => GetMessage("NO_MARGIN_NAME"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"TRANSPARENT" => Array(
		"NAME" => GetMessage("TRANSPARENT_NAME"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
		"REFRESH" => "Y",
	),
	"FILLED" => Array(
		"NAME" => GetMessage("FILLED_NAME"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
		"HIDDEN" => ($arCurrentValues["TRANSPARENT"] != "Y" ? "N" : "Y"),
	),
);
?>

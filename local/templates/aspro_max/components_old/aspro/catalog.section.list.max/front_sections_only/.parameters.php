<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arTemplateParameters = array(
	"TITLE_BLOCK" => Array(
		"NAME" => GetMessage("TITLE_BLOCK_NAME"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	),
	"TITLE_BLOCK_ALL" => Array(
		"NAME" => GetMessage("TITLE_BLOCK_ALL_NAME"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	),
	"ALL_URL" => Array(
		"NAME" => GetMessage("ALL_URL_NAME"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	),
	"SHOW_ICONS" => Array(
		"NAME" => GetMessage("SHOW_ICONS_NAME"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	),
	"NO_MARGIN" => Array(
		"NAME" => GetMessage("NO_MARGIN_NAME"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"FILLED" => Array(
		"NAME" => GetMessage("FILLED_NAME"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"SHOW_SUBSECTIONS" => Array(
		"NAME" => GetMessage("SHOW_SUBSECTIONS_NAME"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
		"REFRESH" => "Y",
	),
	"SCROLL_SUBSECTIONS" => Array(
		"NAME" => GetMessage("SCROLL_SUBSECTIONS_NAME"),
		"TYPE" => "CHECKBOX",
		"HIDDEN" => ($arCurrentValues["SHOW_SUBSECTIONS"] == "Y" ? "N" : "Y"),
		"DEFAULT" => "N",
	),
	"INCLUDE_FILE" => Array(
		"NAME" => GetMessage("INCLUDE_FILE_NAME"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	),
);
?>

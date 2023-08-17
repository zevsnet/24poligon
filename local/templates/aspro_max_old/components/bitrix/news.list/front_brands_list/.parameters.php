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
		"DEFAULT" => "company/news/",
	),
	"INCLUDE_FILE" => Array(
		"NAME" => GetMessage("INCLUDE_FILE_NAME"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	),
);
$arTemplateParameters["MOBILE_BORDERED"] = array(
	"NAME" => GetMessage("T_MOBILE_BORDERED"),
	"TYPE" => "CHECKBOX",
	"DEFAULT" => "N",
);
$arTemplateParameters["COLORED_IMG"] = array(
	"NAME" => GetMessage("T_COLORED_IMG"),
	"TYPE" => "CHECKBOX",
	"DEFAULT" => "N",
);
?>

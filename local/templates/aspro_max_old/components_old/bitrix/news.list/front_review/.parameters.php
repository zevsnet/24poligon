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
	"SHOW_ADD_REVIEW" => Array(
		"NAME" => GetMessage("SHOW_ADD_REVIEW_NAME"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
		"REFRESH" => "Y",
	),
	"TITLE_ADD_REVIEW" => Array(
		"NAME" => GetMessage("TITLE_ADD_REVIEW_NAME"),
		"TYPE" => "STRING",
		"DEFAULT" => GetMessage("TITLE_ADD_REVIEW_NAME"),
		'HIDDEN' => ((isset($arCurrentValues['SHOW_ADD_REVIEW']) && $arCurrentValues['SHOW_ADD_REVIEW'] == 'Y') ? 'N' : 'Y'),
	),
	"COMPACT" => Array(
		"NAME" => GetMessage("COMPACT_NAME"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	),
	"SIZE_IN_ROW" => Array(
		"NAME" => GetMessage("SIZE_IN_ROW_NAME"),
		"TYPE" => "LIST",
		"VALUES" => array(1 => 1, 2 => 2, 3 => 3),
		"DEFAULT" => 1,
	),
	"INCLUDE_FILE" => Array(
		"NAME" => GetMessage("INCLUDE_FILE_NAME"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	),
);
?>

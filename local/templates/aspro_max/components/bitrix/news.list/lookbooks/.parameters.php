<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$arTemplateParameters = array(
	"SIZE_IN_ROW" => Array(
		"PARENT" => "LIST_SETTINGS",
		"NAME" => GetMessage("SIZE_IN_ROW_NAME"),
		"TYPE" => "LIST",
		"VALUES" => array(5 => 5, 4 => 4, 3 => 3),
		"DEFAULT" => 4,
	),	
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
		"DEFAULT" => "lookbooks/",
	),
	"MOBILE_TEMPLATE" => Array(
		"NAME" => GetMessage("T_MOBILE_TEMPLATE"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	),
);
?>
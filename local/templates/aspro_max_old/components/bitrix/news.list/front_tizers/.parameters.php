<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arTemplateParameters = array(
	"INCLUDE_FILE" => Array(
		"NAME" => GetMessage("INCLUDE_FILE_NAME"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	),
	"SIZE_IN_ROW" => Array(
		"NAME" => GetMessage("SIZE_IN_ROW_NAME"),
		"TYPE" => "LIST",
		"VALUES" => array(5 => 5, 4 => 4, 3 => 3),
		"DEFAULT" => 4,
	),
	"CENTERED" => Array(
		"NAME" => GetMessage("CENTERED_NAME"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
		"REFRESH" => "Y",
	),
	"TYPE_IMG" => Array(
		"NAME" => GetMessage("IMG_POSITION_NAME"),
		"TYPE" => "LIST",
		"VALUES" => array("top" => GetMessage("TOP"), "left" => GetMessage("LEFT"), "right" => GetMessage("RIGHT")),
		"DEFAULT" => "top",
		"HIDDEN" => ($arCurrentValues["CENTERED"] != "Y" ? "N" : "Y"),
	),
	"MOBILE_TEMPLATE" => Array(
		"NAME" => GetMessage("T_MOBILE_TEMPLATE"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	)
);
?>

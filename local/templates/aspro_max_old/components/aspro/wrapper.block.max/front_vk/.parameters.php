<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
	return;

$arSort = CIBlockParameters::GetElementSortFields(
	array('SHOWS', 'SORT', 'TIMESTAMP_X', 'NAME', 'ID', 'ACTIVE_FROM', 'ACTIVE_TO'),
	array('KEY_LOWERCASE' => 'Y')
);

$arAscDesc = array(
	"asc" => GetMessage("IBLOCK_SORT_ASC"),
	"desc" => GetMessage("IBLOCK_SORT_DESC"),
);

$arTemplateParameters= Array(
	"PAGE_ELEMENT_COUNT" => array(
		"NAME" => GetMessage("T_PAGE_ELEMENT_COUNT"),
		"TYPE" => "STRING",
		"DEFAULT" => "4",
	),
	"NO_MARGIN" => array(
		"NAME" => GetMessage("T_NO_MARGIN"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
		"HIDDEN" => (isset($arCurrentValues["WIDE_FIRST_BLOCK"]) && $arCurrentValues["WIDE_FIRST_BLOCK"] == "Y" ? "Y" : "N"),
	),
	"WIDE_BLOCK" => array(
		"NAME" => GetMessage("T_WIDE_BLOCK"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	),
	"WIDE_FIRST_BLOCK" => array(
		"NAME" => GetMessage("T_WIDE_FIRST_BLOCK"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
		"REFRESH" => "Y",
	),
	"LINE_ELEMENT_COUNT" => array(
		"NAME" => GetMessage("T_LINE_ELEMENT_COUNT"),
		"TYPE" => "LIST",
		"VALUES" => array(2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 8 => 8),
		"DEFAULT" => "4",
	),
	"VIEW_TYPE" => array(
		"NAME" => GetMessage("T_VIEW_TYPE"),
		"TYPE" => "LIST",
		"VALUES" => array(
			"HORIZONTAL" => GetMessage("T_VIEW_TYPE_HORIZONTAL"),
			"SQUARE" => GetMessage("T_VIEW_TYPE_SQUARE"),
		),
		"DEFAULT" => "HORIZONTAL"
	),
);?>
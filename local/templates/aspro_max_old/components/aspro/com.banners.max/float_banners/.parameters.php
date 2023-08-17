<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arTemplateParameters = array(
	"SECTION_ITEM_CODE" => Array(
		"NAME" => GetMessage("T_SECTION_CODE"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	),
	"TYPE_BLOCK" => Array(
		"NAME" => GetMessage("T_TYPE_BLOCK"),
		"TYPE" => "LIST",
		"VALUES" => array('type1' => 1, 'type2' => 2),
		"DEFAULT" => 'type1',
		"REFRESH" => "Y",
	),
	"SIZE_IN_ROW" => Array(
		"NAME" => GetMessage("SIZE_IN_ROW_NAME"),
		"TYPE" => "LIST",
		"VALUES" => array(2 => 2, 3 => 3, 4 => 4),
		"DEFAULT" => 4,
		"HIDDEN" => ($arCurrentValues["TYPE_BLOCK"] != "type2" ? "N" : "Y"),
	),
	"USE_TYPE_BLOCK" => Array(
		"NAME" => GetMessage("T_USE_TYPE_BLOCK"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
		"HIDDEN" => ($arCurrentValues["TYPE_BLOCK"] == "type2" ? "N" : "Y"),
	),
	/*"SHOW_LONG_FIRST_ROW" => Array(
		"NAME" => GetMessage("T_SHOW_LONG_FIRST_ROW"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
		"HIDDEN" => ($arCurrentValues["TYPE_BLOCK"] == "type2" ? "N" : "Y"),
	),*/
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
		"DEFAULT" => "center",
	),
);

if($arCurrentValues["TYPE_BLOCK"])
{

}
?>

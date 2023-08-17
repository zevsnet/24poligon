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
	"SHOW_SECTION_NAME" => Array(
		"NAME" => GetMessage("SHOW_SECTION_NAME"),
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
	"SHOW_SUBSCRIBE" => Array(
		"NAME" => GetMessage("SHOW_SUBSCRIBE_NAME"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
		"REFRESH" => "Y",
	),
	"TITLE_SUBSCRIBE" => Array(
		"NAME" => GetMessage("TITLE_SUBSCRIBE_NAME"),
		"TYPE" => "STRING",
		"DEFAULT" => GetMessage("TITLE_SUBSCRIBE_NAME"),
		'HIDDEN' => ((isset($arCurrentValues['SHOW_SUBSCRIBE']) && $arCurrentValues['SHOW_SUBSCRIBE'] == 'Y') ? 'N' : 'Y'),
	),
	"HALF_BLOCK" => Array(
		"NAME" => GetMessage("HALF_BLOCK_NAME"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
		"REFRESH" => "Y",
	),
	"ALL_BLOCK_BG" => Array(
		"NAME" => GetMessage("ALL_BLOCK_BG_NAME"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	)
);
if((isset($arCurrentValues['HALF_BLOCK']) && $arCurrentValues['HALF_BLOCK'] != 'Y') || !isset($arCurrentValues['HALF_BLOCK']))
{
	$arTemplateParameters2 = array(
		"TYPE_IMG" => Array(
			"NAME" => GetMessage("TYPE_IMG_NAME"),
			"TYPE" => "LIST",
			"VALUES" => array("md" => GetMessage("MD_IMG"), "lg" => GetMessage("BIG_IMG")),
			"DEFAULT" => "md",
		),
		"SIZE_IN_ROW" => Array(
			"NAME" => GetMessage("SIZE_IN_ROW_NAME"),
			"TYPE" => "LIST",
			"VALUES" => array(5 => 5, 4 => 4, 3 => 3),
			"DEFAULT" => 4,
		),
		"BORDERED" => Array(
			"NAME" => GetMessage("BORDERED_NAME"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"FON_BLOCK_2_COLS" => Array(
			"NAME" => GetMessage("FON_BLOCK_2_COLS_NAME"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
			"REFRESH" => "Y",
		),
		"USE_BG_IMAGE_ALTERNATE" => Array(
			"NAME" => GetMessage("USE_BG_IMAGE_ALTERNATE_NAME"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"TITLE_SHOW_FON" => Array(
			"NAME" => GetMessage("TITLE_SHOW_FON_NAME"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
			'HIDDEN' => ((isset($arCurrentValues['FON_BLOCK_2_COLS']) && $arCurrentValues['FON_BLOCK_2_COLS'] == 'Y') ? 'N' : 'Y'),
		)
	);
	$arTemplateParameters = array_merge($arTemplateParameters, $arTemplateParameters2);
}
?>

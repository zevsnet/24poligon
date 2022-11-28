<?
global $arTheme;
$APPLICATION->IncludeComponent(
	"bitrix:catalog.section.list",
	"sections_compact",
	Array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
		"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
		"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],		
		"ADD_SECTIONS_CHAIN" => ((!$iSectionsCount || $arParams['INCLUDE_SUBSECTIONS'] !== "N") ? 'N' : 'Y'),
		"SHOW_SECTION_LIST_PICTURES" => $arParams["SHOW_SECTION_PICTURES"],
		"TOP_DEPTH" => "1",
		"FILTER_NAME" => "arSubSectionFilter",
		"CACHE_FILTER" => "Y",
		"SHOW_ICONS" => "Y",
		"COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
		"SECTION_USER_FIELDS" => array("UF_CATALOG_ICON"),
		"SECTION_FIELDS" => array('ID', 'IBLOCK_ID', 'PICTURE'),
		"NO_MARGIN" => "Y",
		"COUNT_ELEMENTS_FILTER" => ($arParams["HIDE_NOT_AVAILABLE"] == "Y" ? "CNT_AVAILABLE" : "CNT_ACTIVE"),
	),
	$component
);?>
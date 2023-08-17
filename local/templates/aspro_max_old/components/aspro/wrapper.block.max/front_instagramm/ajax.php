<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arParams['LINE_ELEMENT_COUNT'] = $arParams['LINE_ELEMENT_COUNT'] ?? 4;
$arParams['PAGE_ELEMENT_COUNT'] = $arParams['PAGE_ELEMENT_COUNT'] ?? 4;
$arParams['NO_MARGIN'] = $arParams['NO_MARGIN'] ?? 'N';
$arParams['WIDE_BLOCK'] = $arParams['WIDE_BLOCK'] ?? 'N';
$arParams['WIDE_FIRST_BLOCK'] = $arParams['WIDE_FIRST_BLOCK'] ?? 'N';
$arParams['INCLUDE_FILE'] = $arParams['INCLUDE_FILE'] ?? '';
?>
<?$APPLICATION->IncludeComponent(
	"aspro:instargam.max",
	"main",
	Array(
		"COMPOSITE_FRAME_MODE" => $arParams['COMPOSITE_FRAME_MODE'],
		"COMPOSITE_FRAME_TYPE" => $arParams['COMPOSITE_FRAME_TYPE'],
		"NO_MARGIN" => $arParams['NO_MARGIN'],
		"LINE_ELEMENT_COUNT" => $arParams['LINE_ELEMENT_COUNT'],
		"PAGE_ELEMENT_COUNT" => $arParams['PAGE_ELEMENT_COUNT'],
		"INCLUDE_FILE" => $arParams['INCLUDE_FILE'],
		"WIDE_BLOCK" => $arParams['WIDE_BLOCK'],
		"WIDE_FIRST_BLOCK" => $arParams['WIDE_FIRST_BLOCK'],
		"CACHE_TYPE" => $arParams['CACHE_TYPE'],
		"CACHE_TIME" => $arParams['CACHE_TIME'],
		"CACHE_GROUPS" => $arParams['CACHE_GROUPS'],
	)
);?>
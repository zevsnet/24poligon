<?
function getBaseCurrency()
{
	if ( CModule::IncludeModule('currency') )
	{
		$res = CCurrency::GetList( ($by="name"), ($order="asc"), "RU" );
		while( $arRes = $res->Fetch() )
		{
			if ( $arRes["AMOUNT"] == 1 )
				return $arRes["CURRENCY"];
		}
	}
}

$baseCur = getBaseCurrency();
if ( !CModule::IncludeModule('currency') ) $baseCur = $arParams["CURRENCY"];
$arCur = array();
$arCur[0] = $baseCur;
foreach( $arResult["CURRENCIES"] as $cur )
{
	if ($cur == 'RUR')
	{
		$cur = 'RUB';
	}
	
	if ( !in_array( $cur, $arCur ) )
		$arCur[] = $cur;
}

$arResult["CURRENCIES"] = $arCur;

foreach($arParams['COND_PARAMS'] as $k=>$code) {
	if (empty($code)) continue;
	if ($code == "EMPTY") continue;

	foreach($arResult["OFFER"] as &$arOffer) {
		$props = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"), Array("CODE" => $code))->GetNext();
	
		$arOffer["CONDITION_PROPERTIES"][$code] = CIBlockFormatProperties::GetDisplayValue($arOffer, $props, "ym_out");
		$arOffer["CONDITION_PROPERTIES"][$code]["DISPLAY_VALUE"] = $arOffer["CONDITION_PROPERTIES"][$code]["VALUE_ENUM"] ? $arOffer["CONDITION_PROPERTIES"][$code]["VALUE_ENUM"] : strip_tags($arOffer["CONDITION_PROPERTIES"][$code]["DISPLAY_VALUE"]);
		$arOffer["CONDITION_PROPERTIES"][$code]["DISPLAY_NAME"] = $props["NAME"];
		$arOffer["CONDITION_PROPERTIES"][$code]["DISPLAY_DESCRIPTION"] = $props["DESCRIPTION"];
		unset($props);

		if (empty($arOffer['CONDITION_PROPERTIES'][$code]['DISPLAY_VALUE']) && !empty($arOffer['GROUP_ID'])) {
			$props = CIBlockElement::GetProperty($arOffer["IBLOCK_ID_CATALOG"], $arOffer["GROUP_ID"], array("sort" => "asc"), Array("CODE" => $code))->GetNext();
			$arOffer["CONDITION_PROPERTIES"][$code] = CIBlockFormatProperties::GetDisplayValue($arOffer, $props, "ym_out");
			$arOffer["CONDITION_PROPERTIES"][$code]["DISPLAY_VALUE"] = $arOffer["CONDITION_PROPERTIES"][$code]["VALUE_ENUM"] ? $arOffer["CONDITION_PROPERTIES"][$code]["VALUE_ENUM"] : strip_tags($arOffer["CONDITION_PROPERTIES"][$code]["DISPLAY_VALUE"]);
			$arOffer["CONDITION_PROPERTIES"][$code]["DISPLAY_NAME"] = $props["NAME"];
			$arOffer["CONDITION_PROPERTIES"][$code]["DISPLAY_DESCRIPTION"] = $props["DESCRIPTION"];
			unset($props);
		}
		$arOffer["CONDITION_PROPERTIES"][$code]["DISPLAY_DESCRIPTION"] = htmlspecialcharsBx($arOffer["CONDITION_PROPERTIES"][$code]["DISPLAY_DESCRIPTION"]);
		$arOffer["CONDITION_PROPERTIES"][$code]["DISPLAY_VALUE"] = htmlspecialcharsBx($arOffer["CONDITION_PROPERTIES"][$code]["DISPLAY_VALUE"]);
		$arOffer["CONDITION_PROPERTIES"][$code]["DISPLAY_NAME"] = htmlspecialcharsBx($arOffer["CONDITION_PROPERTIES"][$code]["DISPLAY_NAME"]);
	}
}

foreach($arParams['PARAMS'] as $k=>$code) {
	if (empty($code)) continue;
	if ($code == "EMPTY") continue;
	
	foreach($arResult["OFFER"] as &$arOffer) {
		$props = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"), Array("CODE"=>$code))->GetNext();
		$arOffer["DISPLAY_PROPERTIES"][$code] = CIBlockFormatProperties::GetDisplayValue($arOffer, $props, "ym_out");
		$arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"] = $arOffer["DISPLAY_PROPERTIES"][$code]["VALUE_ENUM"]?$arOffer["DISPLAY_PROPERTIES"][$code]["VALUE_ENUM"]:strip_tags($arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]);
		$arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_NAME"] = $props["NAME"];
		unset($props);

		if (empty($arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]) && !empty($arOffer['GROUP_ID'])) {
			$props = CIBlockElement::GetProperty($arOffer["IBLOCK_ID_CATALOG"], $arOffer["GROUP_ID"], array("sort" => "asc"), Array("CODE"=>$code))->GetNext();
			$arOffer["DISPLAY_PROPERTIES"][$code] = CIBlockFormatProperties::GetDisplayValue($arOffer, $props, "ym_out");
			$arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"] = $arOffer["DISPLAY_PROPERTIES"][$code]["VALUE_ENUM"]?$arOffer["DISPLAY_PROPERTIES"][$code]["VALUE_ENUM"]:strip_tags($arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]);
			$arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_NAME"] = $props["NAME"];
			unset($props);
		}
		$arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"] = htmlspecialcharsBx($arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]);
		$arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_NAME"] = htmlspecialcharsBx($arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_NAME"]);
	}
}
?>
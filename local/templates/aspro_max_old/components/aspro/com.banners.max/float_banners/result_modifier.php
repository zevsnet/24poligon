<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arParams['SIZE_IN_ROW'] = $arParams['SIZE_IN_ROW'] ?? 4;

if($arResult['ITEMS'] && $arParams['TYPE_BLOCK'] == 'type2')
{
	$arResult['MIXED_BLOCKS'] = $arResult['COUNT_LONG_ITEMS'] = false;
	if($arParams['USE_TYPE_BLOCK'] == 'Y')
	{
		foreach($arResult['ITEMS'] as $key => $arItem)
		{
			if($arItem['PROPERTIES']['TYPE_BLOCK']['VALUE_XML_ID'])
			{

				if($arItem['PROPERTIES']['TYPE_BLOCK']['VALUE_XML_ID'] != 3)
					$arResult['MIXED_BLOCKS'] = true;
			}
		}
	}
}

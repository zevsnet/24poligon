<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arParams['USE_TYPE_BLOCK'] = $arParams['USE_TYPE_BLOCK'] ?? 'N';
$arParams['BG_BLOCK_POSITION'] = $arParams['BG_BLOCK_POSITION'] ?? 'top';

if($arResult['ITEMS'])
{
	if($arParams['BG_BLOCK_POSITION'] == 'top')
	{
		foreach($arResult['ITEMS'] as $key => $arItem)
		{
			if(isset($arItem['DISPLAY_PROPERTIES']['TOP_IMG']) && $arItem['DISPLAY_PROPERTIES']['TOP_IMG']['VALUE'])
			{
				$arResult['ITEMS'][$key]['PREVIEW_PICTURE2'] = $arItem['PREVIEW_PICTURE'];
				$arResult['ITEMS'][$key]['PREVIEW_PICTURE'] = CFile::GetFileArray($arItem['DISPLAY_PROPERTIES']['TOP_IMG']['VALUE']);
			}
		}
	}
	if($arParams['USE_TYPE_BLOCK'] == 'Y')
	{
		$arResult['MIXED_BLOCKS'] = false;
		foreach($arResult['ITEMS'] as $key => $arItem)
		{
			if(isset($arItem['PROPERTIES']['TYPE_BLOCK']) && $arItem['PROPERTIES']['TYPE_BLOCK']['VALUE_XML_ID'])
			{
				if($arItem['PROPERTIES']['TYPE_BLOCK']['VALUE_XML_ID'] != 3)
					$arResult['MIXED_BLOCKS'] = true;
			}
		}
	}
}
?>
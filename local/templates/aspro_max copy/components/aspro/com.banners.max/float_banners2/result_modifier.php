<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?
if($arResult['ITEMS'])
{
	if($arParams['BG_BLOCK_POSITION'] == 'top')
	{
		foreach($arResult['ITEMS'] as $key => $arItem)
		{
			if($arItem['DISPLAY_PROPERTIES']['TOP_IMG']['VALUE'])
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
			if($arItem['PROPERTIES']['TYPE_BLOCK']['VALUE_XML_ID'])
			{
				if($arItem['PROPERTIES']['TYPE_BLOCK']['VALUE_XML_ID'] != 3)
					$arResult['MIXED_BLOCKS'] = true;
			}
		}
	}
}
?>
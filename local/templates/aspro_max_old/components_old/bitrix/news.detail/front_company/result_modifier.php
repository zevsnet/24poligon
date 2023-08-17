<?
if($arParams['TYPE_IMG'] == 'bg')
{
	$arImage = ((isset($arResult['FIELDS']['DETAIL_PICTURE']) && $arResult['FIELDS']['DETAIL_PICTURE']) ? $arResult['FIELDS']['DETAIL_PICTURE'] : '');

	if($arImage)
	{
		$arResult['BG_IMG'] = $arImage['SRC'];
		if($arResult['DISPLAY_PROPERTIES']['IMG3']['VALUE'])
		{
			$arResult['FIELDS']['PREVIEW_PICTURE'] = CFile::getFileArray($arResult['DISPLAY_PROPERTIES']['IMG3']['VALUE']);
		}
	}
}

if($arParams['TYPE_IMG'] == 'md')
{
	if($arResult['DISPLAY_PROPERTIES']['IMG2']['VALUE'])
	{
		$arResult['FIELDS']['PREVIEW_PICTURE'] = CFile::getFileArray($arResult['DISPLAY_PROPERTIES']['IMG2']['VALUE']);
	}
}

if($arParams['TYPE_BLOCK'] == 'type2')
{
	if($arResult['DISPLAY_PROPERTIES']['IMG4']['VALUE'])
	{
		$arResult['FIELDS']['PREVIEW_PICTURE'] = CFile::getFileArray($arResult['DISPLAY_PROPERTIES']['IMG4']['VALUE']);
	}
	if($arParams['TIZERS_IBLOCK_ID'] && $arResult['DISPLAY_PROPERTIES']['LINK_BENEFIT']['VALUE'])
	{
		$arResult['BENEFITS'] = true;
	}
}

?>
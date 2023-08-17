<?
if($arResult['ITEMS'])
{
	$arTmpItems = array();
	foreach($arResult['ITEMS'] as $key => $arItem)
	{
		$arTmpItems[$key] = array(
			'NAME' => $arItem['NAME'],
			'IBLOCK_ID' => $arItem['IBLOCK_ID'],
			'DETAIL_PAGE_URL' => $arItem['DETAIL_PAGE_URL'],
			'FIELDS' => $arItem['FIELDS'],
			'DISPLAY_PROPERTIES' => $arItem['DISPLAY_PROPERTIES'],
		);
	}
	$arResult['MAP_ITEMS'] = $arTmpItems;
}

?>
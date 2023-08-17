<?
if($arResult['ITEMS'])
{
	$arTmpItems = array();
	foreach($arResult['ITEMS'] as $key => $arItem)
	{
		$arResult['ITEMS'][$key]['URL'] = $arItem['DETAIL_PAGE_URL'];
		$arResult['ITEMS'][$key]['ADDRESS'] = $arItem['NAME'].($arItem['DISPLAY_PROPERTIES']['ADDRESS']['VALUE'] ? ', '.$arItem['DISPLAY_PROPERTIES']['ADDRESS']['VALUE'] : '');
		$arResult['ITEMS'][$key]['EMAIL'] = $arItem['DISPLAY_PROPERTIES']['EMAIL']['VALUE'];
		$arResult['ITEMS'][$key]['PHONE'] = $arItem['DISPLAY_PROPERTIES']['PHONE']['VALUE'];
		$arResult['ITEMS'][$key]['METRO'] = $arItem['DISPLAY_PROPERTIES']['METRO']['VALUE'];
		if($arItem['DISPLAY_PROPERTIES']['METRO']['VALUE'])
		{
			if(!is_array($arItem['DISPLAY_PROPERTIES']['METRO']['VALUE']))
				$arItem['DISPLAY_PROPERTIES']['METRO']['VALUE'] = array($arItem['DISPLAY_PROPERTIES']['METRO']['VALUE']);
			
			foreach($arItem['DISPLAY_PROPERTIES']['METRO']['VALUE'] as $metro){
				$arResult['ITEMS'][$key]['METRO_PLACEMARK_HTML'] .= '<div class="metro"><i></i>'.$metro.'</div>';
			}
		}
		if($arItem['DISPLAY_PROPERTIES']['SCHEDULE']['VALUE']['TYPE'] == 'html')
			$arResult['ITEMS'][$key]['SCHEDULE'] = htmlspecialchars_decode($arItem['DISPLAY_PROPERTIES']['SCHEDULE']['~VALUE']['TEXT']);
		else
			$arResult['ITEMS'][$key]['SCHEDULE'] = nl2br($arItem['DISPLAY_PROPERTIES']['SCHEDULE']['~VALUE']['TEXT']);



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
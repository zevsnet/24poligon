<?
if($arResult['STORES'])
{
	$arTmpItems = $arTmpItems2 = array();
	$arExtFilter = ($GLOBALS["arRegionality"] ? $GLOBALS["arRegionality"] : array());
	$dbRes = CCatalogStore::GetList(array('ID' => 'ASC'), array_merge(array('ACTIVE' => 'Y'), $arExtFilter), false, false, array('ID', 'EMAIL', 'UF_METRO'));
	while($arStore = $dbRes->Fetch())
	{
		$arTmp = array();
		$arTmp['EMAIL'] = htmlspecialchars_decode($arStore['EMAIL']);
		$arTmp['METRO_PLACEMARK_HTML'] = '';
		if($arTmp['METRO'] = unserialize($arStore['UF_METRO']))
		{
			$arTmp['METRO_PLACEMARK_HTML'] = implode(', ', $arTmp['METRO']);
		}
		$arTmpItems2[$arStore['ID']] = $arTmp;
	}

	foreach($arResult['STORES'] as $key => $arItem)
	{
		if($arExtFilter)
		{
			if(!in_array($arItem['ID'], $arExtFilter['ID']))
				continue;
		}
		$arTmpItems[$key] = array(
			'NAME' => $arItem['TITLE'],
			'ADDRESS' => $arItem['TITLE'],
			'URL' => $arItem['URL'],
			'PHONE' => $arItem['PHONE'],
			'EMAIL' => $arTmpItems2[$arItem['ID']]['EMAIL'],
			'SCHEDULE' => $arItem['SCHEDULE'],
			'METRO' => $arTmpItems2[$arItem['ID']]['METRO'],
			'GPS_N' => $arItem['GPS_N'],
			'GPS_S' => $arItem['GPS_S'],
			'METRO_PLACEMARK_HTML' => $arTmpItems2[$arItem['ID']]['METRO_PLACEMARK_HTML'],
		);
	}
	$arResult['MAP_ITEMS'] = $arTmpItems;
}

?>
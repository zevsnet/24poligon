<?
if($arResult['ITEMS'])
{
	$arSectionsIDs = array();
	
	foreach($arResult['ITEMS'] as $key => $arItem){
		if($SID = $arItem['IBLOCK_SECTION_ID']){
			$arSectionsIDs[] = $SID;
		}
		$arResult['ITEMS'][$key]['DETAIL_PAGE_URL'] = CMax::FormatNewsUrl($arItem);

		if(isset($arItem['DISPLAY_PROPERTIES']['REDIRECT']) && strlen($arItem['DISPLAY_PROPERTIES']['REDIRECT']['VALUE']))
			unset($arResult['ITEMS'][$key]['DISPLAY_PROPERTIES']['REDIRECT']);
		
		if($arItem['DISPLAY_PROPERTIES'])
		{
			foreach($arItem['DISPLAY_PROPERTIES'] as $key2 => $arProp)
			{
				if(($key2 == 'EMAIL' || $key2 == 'PHONE'|| $key2 == 'SITE') && $arProp['VALUE'])
					$arResult['ITEMS'][$key]['MIDDLE_PROPS'][$key2] = $arProp;
				
			}
		}
		//echo '<pre>',var_dump($arItem['MIDDLE_PROPS']),'</pre>';

		CMax::getFieldImageData($arResult['ITEMS'][$key], array('PREVIEW_PICTURE'));

		if($arParams['USE_SECTIONS_TABS']=='Y'){
			if($arItem['IBLOCK_SECTION_ID']){
				$resGroups = CIBlockElement::GetElementGroups($arItem['ID'], true, array('ID'));
				while($arGroup = $resGroups->Fetch())
				{
					$arResult['ITEMS'][$key]['SECTIONS'][$arGroup['ID']] = $arGroup['ID'];
					$arGoodsSectionsIDs[$arGroup['ID']] = $arGroup['ID'];
				}
			}
		}
	}
	
	//echo '<pre>',var_dump($arResult['ITEMS'][0]['MIDDLE_PROPS']),'</pre>';

	/*if($arSectionsIDs){
		$arResult['SECTIONS'] = CMaxCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => array('ID'), 'MULTI' => 'N')), array('ID' => $arSectionsIDs));
	}*/
	if($arSectionsIDs && $arParams['USE_SECTIONS_TABS']!='Y')
	{
		$arResult['SECTIONS'] = CMaxCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => 'ID', 'MULTI' => 'N')), array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ID' => $arSectionsIDs, 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'), false, array('ID', 'NAME'));		
	} elseif(isset($arGoodsSectionsIDs) && $arGoodsSectionsIDs && $arParams['USE_SECTIONS_TABS']=='Y'){
		$arResult['SECTIONS'] = CMaxCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => 'ID', 'MULTI' => 'N')), array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ID' => $arGoodsSectionsIDs, 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'), false, array('ID', 'NAME'));
	}
}
?>
<?
$linkedMode = (isset($arParams['LINKED_MODE']) && $arParams['LINKED_MODE'] == "Y");

if($arResult['ITEMS'])
{
	$arSectionsIDs = array();
	
	foreach($arResult['ITEMS'] as $key => $arItem){
		if(!$linkedMode){
			if($SID = $arItem['IBLOCK_SECTION_ID']){
				$arSectionsIDs[] = $SID;
			}
		}

		$arResult['ITEMS'][$key]['DETAIL_PAGE_URL'] = CMax::FormatNewsUrl($arItem);

		if(strlen($arItem['DISPLAY_PROPERTIES']['REDIRECT']['VALUE']))
			unset($arResult['ITEMS'][$key]['DISPLAY_PROPERTIES']['REDIRECT']);

		CMax::getFieldImageData($arResult['ITEMS'][$key], array('PREVIEW_PICTURE'));

		/*if($arParams['USE_SECTIONS_TABS']=='Y'){
			if($arItem['IBLOCK_SECTION_ID']){
				$resGroups = CIBlockElement::GetElementGroups($arItem['ID'], true, array('ID'));
				while($arGroup = $resGroups->Fetch())
				{
					$arResult['ITEMS'][$key]['SECTIONS'][$arGroup['ID']] = $arGroup['ID'];
					$arGoodsSectionsIDs[$arGroup['ID']] = $arGroup['ID'];
				}
			}
		}*/
	}

	/*if($arSectionsIDs){
		$arResult['SECTIONS'] = CMaxCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => array('ID'), 'MULTI' => 'N')), array('ID' => $arSectionsIDs));
	}*/
	/*if($arSectionsIDs && $arParams['USE_SECTIONS_TABS']!='Y')
	{
		$arResult['SECTIONS'] = CMaxCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => 'ID', 'MULTI' => 'N')), array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ID' => $arSectionsIDs, 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'), false, array('ID', 'NAME'));		
	} elseif($arGoodsSectionsIDs && $arParams['USE_SECTIONS_TABS']=='Y'){
		$arResult['SECTIONS'] = CMaxCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => 'ID', 'MULTI' => 'N')), array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ID' => $arGoodsSectionsIDs, 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'), false, array('ID', 'NAME'));
	}*/


	if($arSectionsIDs){
		$arResult['SECTIONS'] = CMaxCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => array('ID'), 'MULTI' => 'N')), array('ID' => $arSectionsIDs, 'ACTIVE' => 'Y'));
	}

	// group elements by sections
	foreach($arResult['ITEMS'] as $arItem){
		$SID = ($arItem['IBLOCK_SECTION_ID']  && !$linkedMode ? $arItem['IBLOCK_SECTION_ID'] : 0);
		$arResult['SECTIONS'][$SID]['ITEMS'][$arItem['ID']] = $arItem;
	}

	// unset empty sections
	if(is_array($arResult['SECTIONS'])){
		foreach($arResult['SECTIONS'] as $i => $arSection){
			if(!$arSection['ITEMS']){
				unset($arResult['SECTIONS'][$i]);
			}
		}
	}

}
?>
<?
global $arTheme, $arRegion;
// get all subsections of PARENT_SECTION or root sections
$arSectionsFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y');
$start_level = $arParams['DEPTH_LEVEL'];
$end_level = $arParams['DEPTH_LEVEL']+1;

if($arParams['PARENT_SECTION'])
{
	$arParentSection = CMaxCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'MULTI' => 'N', "CACHE_GROUP" => array($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()))), array('ID' => $arParams['PARENT_SECTION']), false, array('ID', 'IBLOCK_ID', 'LEFT_MARGIN', 'RIGHT_MARGIN'));

	$arSectionsFilter = array_merge($arSectionsFilter, array('>LEFT_MARGIN' => $arParentSection['LEFT_MARGIN'], '<RIGHT_MARGIN' => $arParentSection['RIGHT_MARGIN'], '>DEPTH_LEVEL' => '1'));
	if($arParams['SHOW_CHILD_SECTIONS'] == 'Y')
	{
		$arSectionsFilter['INCLUDE_SUBSECTIONS'] = 'Y';
		$arSectionsFilter['<=DEPTH_LEVEL'] = $end_level;
	}
}
else
{
	if($arParams['SHOW_CHILD_SECTIONS'] == 'Y')
		$arSectionsFilter['<=DEPTH_LEVEL'] = $end_level;
	else
		$arSectionsFilter['DEPTH_LEVEL'] = '1';
}
$arResult['SECTIONS'] = CMaxCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => array('ID'), 'MULTI' => 'N', "CACHE_GROUP" => array($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()))), $arSectionsFilter, false, array('ID', 'NAME', 'IBLOCK_ID', 'DEPTH_LEVEL', 'IBLOCK_SECTION_ID', 'SECTION_PAGE_URL', 'PICTURE', 'DETAIL_PICTURE', 'UF_TOP_SEO', 'DESCRIPTION'));


if($arResult['SECTIONS'])
{
	$arSections = array();
	foreach($arResult['SECTIONS'] as $key => $arSection)
	{
		$ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($arSection['IBLOCK_ID'], $arSection['ID']);
		$arResult['SECTIONS'][$key]['IPROPERTY_VALUES'] = $ipropValues->getValues();
		CMax::getFieldImageData($arResult['SECTIONS'][$key], array('PICTURE'), 'SECTION');
	}
	
	if($arParams['SHOW_CHILD_SECTIONS'] == 'Y')
	{
		foreach($arResult['SECTIONS'] as $arItem)
		{
			
			if( $arItem['DEPTH_LEVEL'] == $start_level ){
				if(!$arSections[$arItem['ID']]){
					$arSections[$arItem['ID']] = $arItem;
				}else{
					$arSections[$arItem['ID']] = array_merge($arSections[$arItem['ID']], $arItem);
				}
				
			}
			elseif( $arItem['DEPTH_LEVEL'] == $end_level ){
				$arSections[$arItem['IBLOCK_SECTION_ID']]['CHILD'][$arItem['ID']] = $arItem;//echo '<pre>',var_dump($arSections ),'</pre>';
			}
		}

		// add filter elements by region
		$arItemsRegionFilter = array();
		if($arTheme['USE_REGIONALITY']['VALUE'] === 'Y' && $arRegion && $arTheme['USE_REGIONALITY']['DEPENDENT_PARAMS']['REGIONALITY_FILTER_ITEM']['VALUE'] === 'Y'){
			$arItemsRegionFilter['PROPERTY_LINK_REGION'] = $arRegion['ID'];
		}

		// fill elements
		foreach($arSections as $key => $arSection)
		{
			$arItems = CMaxCache::CIBlockElement_GetList(array('SORT' => 'ASC', 'ID' => 'DESC', 'CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), "CACHE_GROUP" => array($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()))), array_merge($arSectionsFilter, array('SECTION_ID' => $arSection['ID']), $arItemsRegionFilter), false, false, array('ID', 'NAME', 'IBLOCK_ID', 'DETAIL_PAGE_URL'));
			if($arItems)
			{
				if(!$arSections[$key]['CHILD'])
					$arSections[$key]['CHILD'] = $arItems;
				else
					$arSections[$key]['CHILD'] = array_merge($arSections[$key]['CHILD'], $arItems);
			}
		}
		$arResult['SECTIONS'] = $arSections;
	}
}
?>
<?
$arResult['SECTIONS'] = array();
//group landings
if($arParams["USE_LANDINGS_GROUP"] === "Y"){
	foreach($arResult['ITEMS'] as $arItem){
		if($SID = $arItem['IBLOCK_SECTION_ID']){
			$arSectionsIDs[] = $SID;
		}
	}
	if($arSectionsIDs){
		$arResult['SECTIONS'] = CMaxCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => array('ID'), 'MULTI' => 'N')), array('ID' => $arSectionsIDs, 'ACTIVE' => 'Y'), false, array("ID", "NAME", "IBLOCK_ID"));
	}

	// group elements by sections
    $arRootSectionItems = array();
	foreach($arResult['ITEMS'] as $arItem){
		$SID = ($arItem['IBLOCK_SECTION_ID'] ? $arItem['IBLOCK_SECTION_ID'] : 0);
        if($SID === 0){
            $arRootSectionItems[$SID]['ITEMS'][$arItem['ID']] = $arItem;
        } else {
            $arResult['SECTIONS'][$SID]['ITEMS'][$arItem['ID']] = $arItem;
        }	
	}
    if(!empty($arRootSectionItems)){
        $arResult['SECTIONS'] = (array)$arRootSectionItems + (array)$arResult['SECTIONS'];        
    }

	//get name from SEO
	$arSectionSeo = array();
	$bSeoSectionName = $arParams["LANDINGS_GROUP_FROM_SEO"] === "Y";
	if($bSeoSectionName){
		foreach($arResult['SECTIONS'] as $keySection => $arSection){
			$ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($arSection["IBLOCK_ID"], $arSection["ID"]);
			$arSectionSeo['IPROPERTY_VALUES'] = $ipropValues->getValues();
			if( $arSectionSeo['IPROPERTY_VALUES']['SECTION_PAGE_TITLE']){
				$arResult['SECTIONS'][$keySection]['NAME'] = $arSectionSeo['IPROPERTY_VALUES']['SECTION_PAGE_TITLE'];
			}
		}
	}

} else {
	$arResult['SECTIONS'][0]['ITEMS'] = $arResult['ITEMS'];
}

?>
<?
if($arResult){
	global $arTheme;
	$MENU_TYPE = $arTheme['MEGA_MENU_TYPE']['VALUE'];

	$arResult = CMax::getChilds($arResult);
	$isCatalog=false;
	$arCatalogItem=array();
	foreach($arResult as $key=>$arItem){
		if(isset($arItem["PARAMS"]["CLASS"]) && $arItem["PARAMS"]["CLASS"]=="catalog"){
			$isCatalog=true;
			$arCatalogItem=$arItem;
			unset($arResult[$key]);
		}
		if(isset($arItem["PARAMS"]["NOT_SHOW"]) && $arItem["PARAMS"]["NOT_SHOW"]=="Y"){
			unset($arResult[$key]);
		}
		if($arItem["CHILD"]){
			foreach($arItem["CHILD"] as $key2=>$arChild){
				if(isset($arChild["PARAMS"]["NOT_SHOW"]) && $arChild["PARAMS"]["NOT_SHOW"]=="Y"){
					unset($arResult[$key]["CHILD"][$key2]);
				}
				if($arChild["PARAMS"]["PICTURE"]){
					$img=CFile::ResizeImageGet($arChild["PARAMS"]["PICTURE"], Array('width'=>50, 'height'=>50), BX_RESIZE_IMAGE_PROPORTIONAL, true);
					$arResult[$key]["CHILD"][$key2]["PARAMS"]["IMAGES"]=$img;
				}
			}
		}
	}
	if($isCatalog){
		$catalog_id=\Bitrix\Main\Config\Option::get("aspro.max", "CATALOG_IBLOCK_ID", CMaxCache::$arIBlocks[SITE_ID]['aspro_max_catalog']['aspro_max_catalog'][0]);
		$arSections = CMaxCache::CIBlockSection_GetList(array('SORT' => 'ASC', 'ID' => 'ASC', 'CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($catalog_id), 'GROUP' => array('ID'))), array('IBLOCK_ID' => $catalog_id, 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y', '<DEPTH_LEVEL' =>\Bitrix\Main\Config\Option::get("aspro.max", "MAX_DEPTH_MENU", 2)), false, array("ID", "NAME", "PICTURE", "LEFT_MARGIN", "RIGHT_MARGIN", "DEPTH_LEVEL", "SECTION_PAGE_URL", "IBLOCK_SECTION_ID"));
		$arTmpResult = array();
		if($arSections){

			$cur_page = $GLOBALS['APPLICATION']->GetCurPage(true);
			$cur_page_no_index = $GLOBALS['APPLICATION']->GetCurPage(false);

			foreach($arSections as $ID => $arSection){
				$arSections[$ID]['SELECTED'] = CMenu::IsItemSelected($arSection['SECTION_PAGE_URL'], $cur_page, $cur_page_no_index);
				if($arSection['IBLOCK_SECTION_ID']){
					if(!isset($arSections[$arSection['IBLOCK_SECTION_ID']]['CHILD'])){
						$arSections[$arSection['IBLOCK_SECTION_ID']]['CHILD'] = array();
					}
					$arSections[$arSection['IBLOCK_SECTION_ID']]['CHILD'][] = &$arSections[$arSection['ID']];
				}

				if($arSection['DEPTH_LEVEL'] == 1){
					$arTmpResult[] = &$arSections[$arSection['ID']];
				}
			}
		}
		array_unshift($arResult, $arCatalogItem);
		$arResult[0]["CHILD"]=$arTmpResult;
	}

	if($MENU_TYPE == 3) {
		CMax::replaceMenuChilds($arResult, $arParams);
	}
}?>
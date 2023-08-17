<?
$arResult = CMax::getChilds($arResult);
global $arRegion, $arTheme;

if($arResult){
	$MENU_TYPE = $arTheme['MEGA_MENU_TYPE']['VALUE'];
	$bRightSide = $arTheme['SHOW_RIGHT_SIDE']['VALUE'] == 'Y';
	$bManyItemsMenu = ($MENU_TYPE == '4');

	$bRightBanner = $bRightSide && $arTheme['SHOW_RIGHT_SIDE']['DEPENDENT_PARAMS']['RIGHT_CONTENT']['VALUE'] == 'BANNER';
	$bRightBrand = $bRightSide && $arTheme['SHOW_RIGHT_SIDE']['DEPENDENT_PARAMS']['RIGHT_CONTENT']['VALUE'] == 'BRANDS';

	if($bRightBanner) {
		$bannerIblockId = CMaxCache::$arIBlocks[SITE_ID]["aspro_max_adv"]["aspro_max_banners_inner"][0];
		$arBannerFilter = array('!PROPERTY_SHOW_MENU' => false, 'ACTIVE' => 'Y', 'IBLOCK_ID' => $bannerIblockId);
		$arBannerSelect = array('ID', 'PROPERTY_SHOW_MENU');
		$arBanners = CMaxCache::CIblockElement_GetList(array("SORT" => "ASC", "CACHE" => array("TAG" => CMaxCache::GetIBlockCacheTag($bannerIblockId))), $arBannerFilter, $arBannerSelect);
	}

	if($bRightBrand) {
		$brandIblockId = CMaxCache::$arIBlocks[SITE_ID]["aspro_max_content"]["aspro_max_brands"][0];
		$arBrandFilter = array('PROPERTY_SHOW_TOP_MENU_VALUE' => 'Y', 'ACTIVE' => 'Y', 'IBLOCK_ID' => $brandIblockId);
		$arBrandSelect = array('ID', 'PROPERTY_SHOW_TOP_MENU', 'PREVIEW_PICTURE', 'NAME', 'DETAIL_PAGE_URL', 'IBLOCK_ID');
		$arResult['BRANDS'] = CMaxCache::CIblockElement_GetList(array("SORT" => "ASC", "CACHE" => array("TAG" => CMaxCache::GetIBlockCacheTag($brandIblockId))), $arBrandFilter, false, false, $arBrandSelect);
	}

	if($MENU_TYPE == 3) {
		CMax::replaceMenuChilds($arResult, $arParams);
	}

	foreach($arResult as $key=>$arItem)
	{
		$bWideMenu = (isset($arItem['PARAMS']['CLASS']) && strpos($arItem['PARAMS']['CLASS'], 'wide_menu') !== false);

		if($arBanners && $bWideMenu) {
			foreach ($arBanners as $banner) {
				if(is_array($banner['PROPERTY_SHOW_MENU_VALUE'])) {
					foreach ($banner['PROPERTY_SHOW_MENU_VALUE'] as $link) {
						if($link == $arItem['LINK']) {
							$arResult[$key]["BANNERS"][] = $banner['ID'];
						}
					}
				} else {
					if($banner['PROPERTY_SHOW_MENU_VALUE'] == $arItem['LINK']) {
						$arResult[$key]["BANNERS"][] = $banner['ID'];
					}
				}
			}
		}

		if(isset($arItem['CHILD']))
		{
			foreach($arItem['CHILD'] as $key2=>$arItemChild)
			{
				if($bManyItemsMenu) {
					if($bRightBrand && $arItemChild['PARAMS']['BRANDS']) {
						$brandIblockId = CMaxCache::$arIBlocks[SITE_ID]["aspro_max_content"]["aspro_max_brands"][0];
						$arBrandFilter = array('ID' => $arItemChild['PARAMS']['BRANDS'], 'ACTIVE' => 'Y', 'IBLOCK_ID' => $brandIblockId);
						$arBrandSelect = array('ID', 'PREVIEW_PICTURE', 'NAME', 'DETAIL_PAGE_URL', 'IBLOCK_ID');
						$arResult[$key]['CHILD'][$key2]['BRANDS'] = CMaxCache::CIblockElement_GetList(array("SORT" => "ASC", "NAME" => "ASC", "CACHE" => array("TAG" => CMaxCache::GetIBlockCacheTag($brandIblockId))), $arBrandFilter, false, false, $arBrandSelect);
					}
				}
				if(isset($arItemChild['PARAMS']) && $arRegion && $arTheme['USE_REGIONALITY']['VALUE'] === 'Y' && $arTheme['USE_REGIONALITY']['DEPENDENT_PARAMS']['REGIONALITY_FILTER_ITEM']['VALUE'] === 'Y')
				{
					// filter items by region
					if(isset($arItemChild['PARAMS']['LINK_REGION']))
					{
						if($arItemChild['PARAMS']['LINK_REGION'])
						{
							if(!in_array($arRegion['ID'], $arItemChild['PARAMS']['LINK_REGION']))
								unset($arResult[$key]['CHILD'][$key2]);
						}
						else
							unset($arResult[$key]['CHILD'][$key2]);
					}
				}
			}
		}
	}
}
?>
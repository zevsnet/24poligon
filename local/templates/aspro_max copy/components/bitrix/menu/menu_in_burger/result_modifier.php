<?
$arResult = CMax::getChilds($arResult);
global $arRegion, $arTheme;
$catalogLink = $arTheme['CATALOG_PAGE_URL']['VALUE'];

$MENU_TYPE = $arTheme['MEGA_MENU_TYPE']['VALUE'];
if($MENU_TYPE == 3) {
	CMax::replaceMenuChilds($arResult, $arParams);
}

if($arResult){
	foreach($arResult as $key=>$arItem)
	{
		if(isset($arItem['CHILD']))
		{
			foreach($arItem['CHILD'] as $key2=>$arItemChild)
			{
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

		if($arItem['LINK'] == $catalogLink) {
			$arResult['EXPANDED'] = $arItem;
			unset($arResult[$key]);
		}
	}
}

?>
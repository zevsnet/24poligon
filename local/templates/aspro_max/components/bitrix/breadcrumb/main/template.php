<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$strReturn = '';
if($arResult){
	\Bitrix\Main\Loader::includeModule("iblock");
	global $NextSectionID, $APPLICATION;
	$cnt = count($arResult);
	$lastindex = $cnt - 1;
	$visibleMobile = 0;
	if(\Bitrix\Main\Loader::includeModule('aspro.max'))
	{
		global $arTheme;
		$bShowCatalogSubsections = ($arTheme["SHOW_BREADCRUMBS_CATALOG_SUBSECTIONS"]["VALUE"] == "Y");
		$bMobileBreadcrumbs = ($arTheme["MOBILE_CATALOG_BREADCRUMBS"]["VALUE"] == "Y" && $NextSectionID);
	}
	if ($bMobileBreadcrumbs) {
		$visibleMobile = $lastindex - 1;
	}
	for($index = 0; $index < $cnt; ++$index){
		$arSubSections = array();
		$bShowMobileArrow = false;
		$arItem = $arResult[$index];
		$title = htmlspecialcharsex($arItem["TITLE"]);
		$bLast = $index == $lastindex;
		if ($NextSectionID) {
			if ($bMobileBreadcrumbs && $visibleMobile == $index) {
				$bShowMobileArrow = true;
			}
			if ($bShowCatalogSubsections) {
				$arSubSections = CMax::getChainNeighbors($NextSectionID, $arItem['LINK']);
			}
		}
		if($index){
			$strReturn .= '<span class="breadcrumbs__separator">&mdash;</span>';
		}
		if($arItem["LINK"] <> "" && $arItem['LINK'] != GetPagePath() && $arItem['LINK']."index.php" != GetPagePath() || $arSubSections){
			$strReturn .= '<div class="breadcrumbs__item'.($bMobileBreadcrumbs ? ' breadcrumbs__item--mobile' : '').($bShowMobileArrow ? ' breadcrumbs__item--visible-mobile' : '').($arSubSections ? ' breadcrumbs__item--with-dropdown colored_theme_hover_bg-block' : '').($bLast ? ' cat_last' : '').'" id="bx_breadcrumb_'.$index.'" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
			if($arSubSections){
				if($index == ($cnt-1)):
					$strReturn .= '<link href="'.GetPagePath().'" itemprop="item" /><span>';
				else:
					$strReturn .= '<a class="breadcrumbs__link colored_theme_hover_bg-el-svg" href="'.$arItem["LINK"].'" itemprop="item">';
				endif;
				if ($bShowMobileArrow) {
					$strReturn .= CMax::showIconSvg('colored_theme_hover_bg-el-svg', SITE_TEMPLATE_PATH.'/images/svg/catalog/arrow_breadcrumbs.svg');
				}
				$strReturn .=($arSubSections ? '<span itemprop="name" class="breadcrumbs__item-name font_xs">'.$title.'</span><span class="breadcrumbs__arrow-down '.(!$bLast ? 'colored_theme_hover_bg-el-svg' : '').'">'.CMax::showIconSvg("arrow", SITE_TEMPLATE_PATH."/images/svg/trianglearrow_down.svg").'</span>' : '<span>'.$title.'</span>');
				$strReturn .= '<meta itemprop="position" content="'.($index + 1).'">';
				if($index == ($cnt-1)):
					$strReturn .= '</span>';
				else:
					$strReturn .= '</a>';
				endif;
				$strReturn .= '<div class="breadcrumbs__dropdown-wrapper"><div class="breadcrumbs__dropdown rounded3">';
					foreach($arSubSections as $arSubSection){
						if ($arSubSection["LINK"] !== $arItem["LINK"]) {
							$strReturn .= '<a class="breadcrumbs__dropdown-item dark_link font_xs" href="'.$arSubSection["LINK"].'">'.$arSubSection["NAME"].'</a>';
						}
					}
				$strReturn .= '</div></div>';
			}
			else{
				$strReturn .= '<a class="breadcrumbs__link" href="'.$arItem["LINK"].'" title="'.$title.'" itemprop="item">';
				if ($bShowMobileArrow) {
					$strReturn .= CMax::showIconSvg('colored_theme_hover_bg-el-svg', SITE_TEMPLATE_PATH.'/images/svg/catalog/arrow_breadcrumbs.svg');
				}
				$strReturn .= '<span itemprop="name" class="breadcrumbs__item-name font_xs">'.$title.'</span><meta itemprop="position" content="'.($index + 1).'"></a>';
			}
			$strReturn .= '</div>';
		}
		else{
			$strReturn .= '<span class="breadcrumbs__item'.($bMobileBreadcrumbs ? ' breadcrumbs__item--mobile' : '').'" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><link href="'.GetPagePath().'" itemprop="item" /><span><span itemprop="name" class="breadcrumbs__item-name font_xs">'.$title.'</span><meta itemprop="position" content="'.($index + 1).'"></span></span>';
		}
	}

	return '<div class="breadcrumbs swipeignore" itemscope="" itemtype="http://schema.org/BreadcrumbList">'.$strReturn.'</div>';
	//return $strReturn;
}
else{
	return $strReturn;
}
?>
<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?global $bLongBanner, $bLightHeader, $bBigBannersIndexClass, $arTheme, $dopBodyClass;

if(strpos($bBigBannersIndexClass, 'hidden') === false && !$templateData['EMPTY_ITEMS'])
{
	$bLongBanner = true;

	if(isset($templateData["BANNER_LIGHT"]) && $templateData["BANNER_LIGHT"])
		$bLightHeader = true;

	$bTopHeaderOpacity = false;

	if( isset($arTheme['HEADER_TYPE']['LIST'][ $arTheme['HEADER_TYPE']['VALUE'] ]['ADDITIONAL_OPTIONS'])  && isset($arTheme['HEADER_TYPE']['LIST'][ $arTheme['HEADER_TYPE']['VALUE'] ]['ADDITIONAL_OPTIONS']['TOP_HEADER_OPACITY']) ) {
		$bTopHeaderOpacity = $arTheme['HEADER_TYPE']['LIST'][ $arTheme['HEADER_TYPE']['VALUE'] ]['ADDITIONAL_OPTIONS']['TOP_HEADER_OPACITY']['VALUE'] == 'Y';
	}

	if($bTopHeaderOpacity) {
		$dopBodyClass .= ' top_header_opacity';
	}
}
?>
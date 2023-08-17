<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?global $bLongBanner, $bLightHeader, $bBigBannersIndexClass, $arTheme, $dopBodyClass;

$arScripts = ['swiper', 'swiper_main_styles', 'top_banner', 'countdown'];
if(strpos($bBigBannersIndexClass, 'hidden') === false && !$templateData['EMPTY_ITEMS'])
{
	$bLongBanner = true;

	if(isset($templateData["BANNER_LIGHT"]) && $templateData["BANNER_LIGHT"])
		$bLightHeader = true;

	$bTopHeaderOpacity = $bSearchOpacity = false;

	if (isset($arTheme['HEADER_TYPE']['LIST'][ $arTheme['HEADER_TYPE']['VALUE'] ]['ADDITIONAL_OPTIONS'])) {
		if (isset($arTheme['HEADER_TYPE']['LIST'][ $arTheme['HEADER_TYPE']['VALUE'] ]['ADDITIONAL_OPTIONS']['TOP_HEADER_OPACITY'])) {
			$bTopHeaderOpacity = $arTheme['HEADER_TYPE']['LIST'][ $arTheme['HEADER_TYPE']['VALUE'] ]['ADDITIONAL_OPTIONS']['TOP_HEADER_OPACITY']['VALUE'] == 'Y';
		}
		if (isset($arTheme['HEADER_TYPE']['LIST'][ $arTheme['HEADER_TYPE']['VALUE'] ]['ADDITIONAL_OPTIONS']['SEARCH_HEADER_OPACITY'])) {
			$bSearchOpacity = $arTheme['HEADER_TYPE']['LIST'][ $arTheme['HEADER_TYPE']['VALUE'] ]['ADDITIONAL_OPTIONS']['SEARCH_HEADER_OPACITY']['VALUE'] == 'Y';
		}
	}

	if ($bTopHeaderOpacity) {
		$dopBodyClass .= ' top_header_opacity';
	}
	if ($bSearchOpacity || $bTopHeaderOpacity) {
		$arScripts[] = 'banners';
	}
}


if ($templateData['HAS_VIDEO']) {
	$arScripts[] = 'video_banner';
}
\Aspro\Max\Functions\Extensions::init($arScripts);
?>
<?include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/components/aspro/com.banners.max/common_files/epilog_action.php');?>
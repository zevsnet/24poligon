<?
global $is404, $isIndex, $isForm, $isWidePage, $isBlog, $isCatalog, $isHideLeftBlock, $bActiveTheme, $bShowCallBackBlock, $bShowQuestionBlock, $bShowReviewBlock, $isBasketPage, $bHideLeftBlockByHeader;

$is404 = (defined("ERROR_404") && ERROR_404 === "Y");
$isIndex = CMax::IsMainPage();
$isForm = CMax::IsFormPage();
//$isBlog = false;//(CSite::inDir(SITE_DIR.'blog/') || $APPLICATION->GetProperty("BLOG_PAGE") == "Y");
$isWidePage = ($APPLICATION->GetProperty("WIDE_PAGE") == "Y");
$isHideLeftBlock = ($APPLICATION->GetProperty("HIDE_LEFT_BLOCK") == "Y");
$isCatalog = CSite::InDir($arTheme['CATALOG_PAGE_URL']['VALUE']);
// for fast view navigation
$_SESSION['FAST_VIEW_IS_CATALOG'] = $isCatalog;

$callbackExploded = explode(',', $arTheme['SHOW_CALLBACK']['VALUE']);
$bShowCallBackBlock = (in_array('INNER_MENU', $callbackExploded));
$questionExploded = explode(',', $arTheme['SHOW_QUESTION']['VALUE']);
$bShowQuestionBlock = (in_array('INNER_MENU', $questionExploded));
$reviewExploded = explode(',', $arTheme['SHOW_REVIEW']['VALUE']);
$bShowReviewBlock = (in_array('INNER_MENU', $reviewExploded));

$indexType = $arTheme["INDEX_TYPE"]["VALUE"];
$isShowIndexLeftBlock = ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["WITH_LEFT_BLOCK"]["VALUE"] == "Y");
$bHideLeftBlockByHeader = ($arTheme['HEADER_TYPE']['VALUE'] == 28 || $arTheme['HEADER_TYPE']['VALUE'] == 29);

$isBasketPage = CSite::InDir($arTheme["BASKET_PAGE_URL"]['VALUE']);

global $bBigBannersIndexClass, $bTizersIndexClass, $bCatalogSectionsIndexClass, $bCatalogTabIndexClass, $bMiddleAdvIndexClass, $bTopAdvIndexClass, $bFloatBannerIndexClass, $bSaleIndexClass, $bBlogIndexClass, $bBottomBannersIndexClass, $bCompanyTextIndexClass, $bBrandsIndexClass, $bNewsIndexClass, $bMapsIndexClass, $bReviewIndexClass, $bCollectionIndexClass, $bLookbookIndexClass, $bStoriesIndexClass, $bInstagrammIndexClass, $bFloatBannersIndexClass, $bFavoritItemIndexClass;
global $bShowBigBanners, $bShowTizers, $bShowCatalogSections, $bShowCatalogTab, $bShowMiddleAdvBottomBanner, $bShowTopAdvBanner, $bShowFloatBanner, $bShowSale, $bShowReview, $bShowCollection, $bShowLookbook, $bShowStories, $bShowBlog, $bShowBottomBanner, $bShowCompany, $bShowBrands, $bShowNews, $bShowMaps, $bShowInstagramm, $bShowFloatBanners, $bShowFavoritItem, $bShowSimple;

$bActiveTheme = ($arTheme["THEME_SWITCHER"]["VALUE"] == 'Y');

$bShowBigBanners = ($bActiveTheme || ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["BIG_BANNER_INDEX"]["VALUE"] != "N"));
$bBigBannersIndexClass = ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["BIG_BANNER_INDEX"]["VALUE"] == 'Y' ? '' : 'hidden');
$bBigBannersIndexClass .= ($arTheme["FON_PARAMS"]["fon".$indexType."BIG_BANNER_INDEX"] == 'Y' ? ' grey_block' : '');

$bShowTizers = ($bActiveTheme || ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["TIZERS"]["VALUE"] != "N"));
$bTizersIndexClass = ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["TIZERS"]["VALUE"] == 'Y' ? '' : 'hidden');
$bTizersIndexClass .= ($arTheme["FON_PARAMS"]["fon".$indexType."TIZERS"] == 'Y' ? ' grey_block' : '');

$bShowCatalogSections = ($bActiveTheme || ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["CATALOG_SECTIONS"]["VALUE"] != "N"));
$bCatalogSectionsIndexClass = ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["CATALOG_SECTIONS"]["VALUE"] == 'Y' ? '' : 'hidden');
$bCatalogSectionsIndexClass .= ($arTheme["FON_PARAMS"]["fon".$indexType."CATALOG_SECTIONS"] == 'Y' ? ' grey_block' : '');

$bShowCatalogTab = ($bActiveTheme || ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["CATALOG_TAB"]["VALUE"] != "N"));
$bCatalogTabIndexClass = ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["CATALOG_TAB"]["VALUE"] == 'Y' ? '' : 'hidden');
$bCatalogTabIndexClass .= ($arTheme["FON_PARAMS"]["fon".$indexType."CATALOG_TAB"] == 'Y' ? ' grey_block' : '');

$bShowMiddleAdvBottomBanner = ($bActiveTheme || ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["MIDDLE_ADV"]["VALUE"] != "N"));
$bMiddleAdvIndexClass = ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["MIDDLE_ADV"]["VALUE"] == 'Y' ? '' : 'hidden');
$bMiddleAdvIndexClass .= ($arTheme["FON_PARAMS"]["fon".$indexType."MIDDLE_ADV"] == 'Y' ? ' grey_block' : '');

$bShowFloatBanners = ($bActiveTheme || ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["FLOAT_BANNERS"]["VALUE"] != "N"));
$bFloatBannersIndexClass = ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["FLOAT_BANNERS"]["VALUE"] == 'Y' ? '' : 'hidden');
$bFloatBannersIndexClass .= ($arTheme["FON_PARAMS"]["fon".$indexType."FLOAT_BANNERS"] == 'Y' ? ' grey_block' : '');

$bShowSale = ($bActiveTheme || ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["SALE"]["VALUE"] != "N"));
$bSaleIndexClass = ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["SALE"]["VALUE"] == 'Y' ? '' : 'hidden');
$bSaleIndexClass .= ($arTheme["FON_PARAMS"]["fon".$indexType."SALE"] == 'Y' ? ' grey_block' : '');

$bShowReview = ($bActiveTheme || ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["REVIEWS"]["VALUE"] != "N"));
$bReviewIndexClass = ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["REVIEWS"]["VALUE"] == 'Y' ? '' : 'hidden');
$bReviewIndexClass .= ($arTheme["FON_PARAMS"]["fon".$indexType."REVIEWS"] == 'Y' ? ' grey_block' : '');

$bShowCollection = ($bActiveTheme || ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["COLLECTIONS"]["VALUE"] != "N"));
$bCollectionIndexClass = ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["COLLECTIONS"]["VALUE"] == 'Y' ? '' : 'hidden');
$bCollectionIndexClass .= ($arTheme["FON_PARAMS"]["fon".$indexType."COLLECTIONS"] == 'Y' ? ' grey_block' : '');

$bShowLookbook = ($bActiveTheme || ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["LOOKBOOKS"]["VALUE"] != "N"));
$bLookbookIndexClass = ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["LOOKBOOKS"]["VALUE"] == 'Y' ? '' : 'hidden');
$bLookbookIndexClass .= ($arTheme["FON_PARAMS"]["fon".$indexType."LOOKBOOKS"] == 'Y' ? ' grey_block' : '');

$bShowStories = ($bActiveTheme || ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["STORIES"]["VALUE"] != "N"));
$bStoriesIndexClass = ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["STORIES"]["VALUE"] == 'Y' ? '' : 'hidden');
$bStoriesIndexClass .= ($arTheme["FON_PARAMS"]["fon".$indexType."STORIES"] == 'Y' ? ' grey_block' : '');

$bShowBlog = ($bActiveTheme || ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["BLOG"]["VALUE"] != "N"));
$bBlogIndexClass = ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["BLOG"]["VALUE"] == 'Y' ? '' : 'hidden');
$bBlogIndexClass .= ($arTheme["FON_PARAMS"]["fon".$indexType."BLOG"] == 'Y' ? ' grey_block' : '');

$bShowNews = ($bActiveTheme || ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["NEWS"]["VALUE"] != "N"));
$bNewsIndexClass = ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["NEWS"]["VALUE"] == 'Y' ? '' : 'hidden');
$bNewsIndexClass .= ($arTheme["FON_PARAMS"]["fon".$indexType."NEWS"] == 'Y' ? ' grey_block' : '');

$bShowBottomBanner = ($bActiveTheme || ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["BOTTOM_BANNERS"]["VALUE"] != "N"));
$bBottomBannersIndexClass = ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["BOTTOM_BANNERS"]["VALUE"] == 'Y' ? '' : 'hidden');
$bBottomBannersIndexClass .= ($arTheme["FON_PARAMS"]["fon".$indexType."BOTTOM_BANNERS"] == 'Y' ? ' grey_block' : '');

$bShowCompany = ($bActiveTheme || ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["COMPANY_TEXT"]["VALUE"] != "N"));
$bCompanyTextIndexClass = ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["COMPANY_TEXT"]["VALUE"] == 'Y' ? '' : 'hidden');
$bCompanyTextIndexClass .= ($arTheme["FON_PARAMS"]["fon".$indexType."COMPANY_TEXT"] == 'Y' ? ' grey_block' : '');

$bShowBrands = ($bActiveTheme || ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["BRANDS"]["VALUE"] != "N"));
$bBrandsIndexClass = ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["BRANDS"]["VALUE"] == 'Y' ? '' : 'hidden');
$bBrandsIndexClass .= ($arTheme["FON_PARAMS"]["fon".$indexType."BRANDS"] == 'Y' ? ' grey_block' : '');

$bShowMaps = ($bActiveTheme || ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["MAPS"]["VALUE"] != "N"));
$bMapsIndexClass = ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["MAPS"]["VALUE"] == 'Y' ? '' : 'hidden');
$bMapsIndexClass .= ($arTheme["FON_PARAMS"]["fon".$indexType."MAPS"] == 'Y' ? ' grey_block' : '');

$bShowFavoritItem = ($bActiveTheme || ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["FAVORIT_ITEM"]["VALUE"] != "N"));
$bFavoritItemIndexClass = ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["FAVORIT_ITEM"]["VALUE"] == 'Y' ? '' : 'hidden');
$bFavoritItemIndexClass .= ($arTheme["FON_PARAMS"]["fon".$indexType."FAVORIT_ITEM"] == 'Y' ? ' grey_block' : '');

$bShowInstagramm = ($bActiveTheme || ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["INSTAGRAMM"]["VALUE"] != "N"));
$bInstagrammIndexClass = ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["INSTAGRAMM"]["VALUE"] == 'Y' ? '' : 'hidden');
$bInstagrammIndexClass .= ($arTheme["FON_PARAMS"]["fon".$indexType."INSTAGRAMM"] == 'Y' ? ' grey_block' : '');

$bShowSimple = (CMax::IsBasketPage() || CMax::IsOrderPage()) && $arTheme['SIMPLE_BASKET']['VALUE'] == 'Y';

global $arRegion;
if($isIndex)
	$_SESSION['ASPRO_FILTER']['arRegionLinkFront'] = $GLOBALS['arRegionLinkFront'] = array('PROPERTY_SHOW_ON_INDEX_PAGE_VALUE' => 'Y');


if($arRegion && $arTheme['USE_REGIONALITY']['DEPENDENT_PARAMS']['REGIONALITY_FILTER_ITEM']['VALUE'] == 'Y')
{
	$_SESSION['ASPRO_FILTER']['arRegionLink'] = $GLOBALS['arRegionLink'] = $GLOBALS['arSideRegionLink'] = array('PROPERTY_LINK_REGION' => $arRegion['ID']);
	if($isIndex)
		$_SESSION['ASPRO_FILTER']['arRegionLinkFront'] = $GLOBALS['arRegionLinkFront']['PROPERTY_LINK_REGION'] = $arRegion['ID'];
}
else
{
	unset($_SESSION['ASPRO_FILTER']);
}

$GLOBALS['arSideRegionLink']['PROPERTY_SHOW_SIDE_BLOCK_VALUE'] = 'Y';

/*filter for contacts*/
if($arRegion)
{
	if($arRegion['LIST_STORES'] && !in_array('component', $arRegion['LIST_STORES']))
	{
		if($arTheme['STORES_SOURCE']['VALUE'] != 'IBLOCK')
			$_SESSION['ASPRO_FILTER']['arRegionality'] = $GLOBALS['arRegionality'] = array('ID' => $arRegion['LIST_STORES']);
		else
			$_SESSION['ASPRO_FILTER']['arRegionality'] = $GLOBALS['arRegionality'] = array('PROPERTY_STORE_ID' => $arRegion['LIST_STORES']);
	}
	else
	{
		unset($_SESSION['ASPRO_FILTER']['arRegionality']);
	}
}
if($isIndex)
{
	$_SESSION['ASPRO_FILTER']['arrPopularSections'] = $GLOBALS['arrPopularSections'] = array('UF_POPULAR' => 1);
	$GLOBALS['arrFrontElements'] = array('PROPERTY_SHOW_ON_INDEX_PAGE_VALUE' => 'Y');
}
?>
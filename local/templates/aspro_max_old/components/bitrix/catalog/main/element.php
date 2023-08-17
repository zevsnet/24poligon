<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<?

use Bitrix\Main\Loader,
	Bitrix\Main\ModuleManager;

Loader::includeModule("iblock");
Loader::includeModule("highloadblock");

global $arTheme, $NextSectionID, $arRegion;
$arSection = $arElement = array();
$bFastViewMode = (isset($_REQUEST['FAST_VIEW']) && $_REQUEST['FAST_VIEW'] == 'Y');
$bReviewsSort = (isset($_REQUEST['reviews_sort']) && $_REQUEST['reviews_sort'] == 'Y');

// get current section & element
if($arResult["VARIABLES"]["SECTION_ID"] > 0)
{
	$arSection = CMaxCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "ID" => $arResult["VARIABLES"]["SECTION_ID"], "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, array("ID", "IBLOCK_ID", "UF_SECTION_TIZERS", "NAME", "IBLOCK_SECTION_ID", "DEPTH_LEVEL", "LEFT_MARGIN", "RIGHT_MARGIN", "UF_OFFERS_TYPE", "UF_ELEMENT_DETAIL", "UF_HELP_TEXT", 'UF_LINKED_BLOG', "UF_PICTURE_RATIO"));
}
elseif(strlen(trim($arResult["VARIABLES"]["SECTION_CODE"])) > 0)
{
	$arSection = CMaxCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "=CODE" => $arResult["VARIABLES"]["SECTION_CODE"], "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, array("ID", "IBLOCK_ID", "UF_SECTION_TIZERS", "NAME", "IBLOCK_SECTION_ID", "DEPTH_LEVEL", "LEFT_MARGIN", "RIGHT_MARGIN", "UF_OFFERS_TYPE", "UF_ELEMENT_DETAIL", "UF_HELP_TEXT", 'UF_LINKED_BLOG', "UF_PICTURE_RATIO"));
}

if($arResult["VARIABLES"]["ELEMENT_ID"] > 0)
	$arElementFilter = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ID" => $arResult["VARIABLES"]["ELEMENT_ID"]);
elseif(strlen(trim($arResult["VARIABLES"]["ELEMENT_CODE"])) > 0)
	$arElementFilter = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "=CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"]);

if($arParams['SHOW_DEACTIVATED'] !== 'Y')
	$arElementFilter['ACTIVE'] = 'Y';

if($GLOBALS[$arParams['FILTER_NAME']])
	$arElementFilter = array_merge($arElementFilter, $GLOBALS[$arParams['FILTER_NAME']]);

if($arRegion)
{
	if(CMax::GetFrontParametrValue('REGIONALITY_FILTER_ITEM') == 'Y' && CMax::GetFrontParametrValue('REGIONALITY_FILTER_CATALOG') == 'Y'){
		$GLOBALS[$arParams['FILTER_NAME']]['PROPERTY_LINK_REGION'] = $arRegion['ID'];
	}
}

$arElement = CMaxCache::CIBLockElement_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), CMax::makeElementFilterInRegion($arElementFilter), false, false, array("ID", "IBLOCK_ID", "IBLOCK_SECTION_ID", "NAME", "PREVIEW_TEXT", "PREVIEW_PICTURE", "DETAIL_PICTURE", "PROPERTY_ASSOCIATED_FILTER", "PROPERTY_EXPANDABLES_FILTER", "PROPERTY_ASSOCIATED", "PROPERTY_EXPANDABLES"));

if(!$arElement)
{
	\Bitrix\Iblock\Component\Tools::process404(
		""
		,($arParams["SET_STATUS_404"] === "Y")
		,($arParams["SET_STATUS_404"] === "Y")
		,($arParams["SHOW_404"] === "Y")
		,$arParams["FILE_404"]
	);
}

if($arParams['STORES'])
{
	foreach($arParams['STORES'] as $key => $store)
	{
		if(!$store)
			unset($arParams['STORES'][$key]);
	}
}
if(!$arSection)
{
	if($arElement["IBLOCK_SECTION_ID"])
	{
		$sid = ((isset($arElement["IBLOCK_SECTION_ID_SELECTED"]) && $arElement["IBLOCK_SECTION_ID_SELECTED"]) ? $arElement["IBLOCK_SECTION_ID_SELECTED"] : $arElement["IBLOCK_SECTION_ID"]);
		$arSection = CMaxCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "ID" => $sid, "IBLOCK_ID" => $arElement["IBLOCK_ID"]), false, array("ID", "IBLOCK_ID", "UF_SECTION_TIZERS", "NAME", "IBLOCK_SECTION_ID", "DEPTH_LEVEL", "LEFT_MARGIN", "RIGHT_MARGIN", "UF_OFFERS_TYPE", "UF_HELP_TEXT", 'UF_LINKED_BLOG', "UF_PICTURE_RATIO"));
	}
}

if($arRegion)
{
	if($arRegion['LIST_PRICES'])
	{
		if(reset($arRegion['LIST_PRICES']) != 'component')
			$arParams['PRICE_CODE'] = array_keys($arRegion['LIST_PRICES']);
	}
	if($arRegion['LIST_STORES'])
	{
		if(reset($arRegion['LIST_STORES']) != 'component')
			$arParams['STORES'] = $arRegion['LIST_STORES'];
	}
	
}

$typeSKU = $sectionHelpText = '';
$typeTmpSKU = $sectionTizers = 0;
if ($arSection['UF_OFFERS_TYPE']) {
	$typeTmpSKU = $arSection['UF_OFFERS_TYPE'];
}

if ($arSection['UF_SECTION_TIZERS']) {
	$sectionTizers = $arSection['UF_SECTION_TIZERS'];
}

if (strlen($arSection['UF_HELP_TEXT'])) {
	$sectionHelpText = $arSection['UF_HELP_TEXT'];
}

if ($arSection['UF_LINKED_BLOG']) {
	$linkedArticles = $arSection['UF_LINKED_BLOG'];
}

if (!$typeTmpSKU || $sectionTizers || !$sectionHelpText || !$linkedArticles) {
	if($arSection["DEPTH_LEVEL"] > 2){
		$arSectionParent = CMaxCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "ID" => $arSection["IBLOCK_SECTION_ID"], "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, array("ID", "IBLOCK_ID", "UF_OFFERS_TYPE", "UF_SECTION_TIZERS", 'UF_LINKED_BLOG', "UF_HELP_TEXT"));
		if ($arSectionParent['UF_OFFERS_TYPE'] && !$typeTmpSKU) {
			$typeTmpSKU = $arSectionParent['UF_OFFERS_TYPE'];
		}
		if ($arSectionParent['UF_SECTION_TIZERS'] && !$sectionTizers) {
			$sectionTizers = $arSectionParent['UF_SECTION_TIZERS'];
		}
		if (strlen($arSectionParent['UF_HELP_TEXT']) && !$sectionHelpText) {
			$sectionHelpText = $arSectionParent['UF_HELP_TEXT'];
		}
		if ($arSectionParent['UF_LINKED_BLOG'] && !$linkedArticles) {
			$linkedArticles = $arSectionParent['UF_LINKED_BLOG'];
		}

		if(!$typeTmpSKU || !$sectionTizers || !$linkedArticles){
			$arSectionRoot = CMaxCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "<=LEFT_BORDER" => $arSection["LEFT_MARGIN"], ">=RIGHT_BORDER" => $arSection["RIGHT_MARGIN"], "DEPTH_LEVEL" => 1, "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, array("ID", "IBLOCK_ID", "UF_OFFERS_TYPE", "UF_SECTION_TIZERS", 'UF_LINKED_BLOG', "UF_HELP_TEXT"));
			if ($arSectionRoot['UF_OFFERS_TYPE'] && !$typeTmpSKU) {
				$typeTmpSKU = $arSectionRoot['UF_OFFERS_TYPE'];
			}
			if ($arSectionRoot['UF_SECTION_TIZERS']) {
				$sectionTizers = $arSectionRoot['UF_SECTION_TIZERS'];
			}
			if (strlen($arSectionRoot['UF_HELP_TEXT']) && !$sectionHelpText) {
				$sectionHelpText = $arSectionRoot['UF_HELP_TEXT'];
			}
			if ($arSectionRoot['UF_LINKED_BLOG'] && !$linkedArticles) {
				$linkedArticles = $arSectionRoot['UF_LINKED_BLOG'];
			}
		}
	}
	else{
		$arSectionRoot = CMaxCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "<=LEFT_BORDER" => $arSection["LEFT_MARGIN"], ">=RIGHT_BORDER" => $arSection["RIGHT_MARGIN"], "DEPTH_LEVEL" => 1, "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, array("ID", "IBLOCK_ID", "UF_OFFERS_TYPE", "UF_SECTION_TIZERS", 'UF_LINKED_BLOG', "UF_HELP_TEXT"));
		if ($arSectionRoot['UF_OFFERS_TYPE'] && !$typeTmpSKU) {
			$typeTmpSKU = $arSectionRoot['UF_OFFERS_TYPE'];
		}
		if ($arSectionRoot['UF_SECTION_TIZERS']) {
			$sectionTizers = $arSectionRoot['UF_SECTION_TIZERS'];
		}
		if (strlen($arSectionRoot['UF_HELP_TEXT']) && !$sectionHelpText) {
			$sectionHelpText = $arSectionRoot['UF_HELP_TEXT'];
		}
		if ($arSectionRoot['UF_LINKED_BLOG'] && !$linkedArticles) {
			$linkedArticles = $arSectionRoot['UF_LINKED_BLOG'];
		}
	}
}
if ($typeTmpSKU) {
	$rsTypes = CUserFieldEnum::GetList(array(), array("ID" => $typeTmpSKU));
	if ($arType = $rsTypes->GetNext()) {
		$typeSKU = $arType['XML_ID'];
	}
}
$arSection['UF_SECTION_TIZERS'] = $sectionTizers;
$arSection['UF_HELP_TEXT'] = $sectionHelpText;

$NextSectionID = $arSection["ID"];
$arParams["GRUPPER_PROPS"] = $arTheme["GRUPPER_PROPS"]["VALUE"];
if($arTheme["GRUPPER_PROPS"]["VALUE"] != "NOT")
{
	$arParams["PROPERTIES_DISPLAY_TYPE"] = "TABLE";

	if($arParams["GRUPPER_PROPS"] == "GRUPPER" && !\Bitrix\Main\Loader::includeModule("redsign.grupper"))
		$arParams["GRUPPER_PROPS"] = "NOT";
	if($arParams["GRUPPER_PROPS"] == "WEBDEBUG" && !\Bitrix\Main\Loader::includeModule("webdebug.utilities"))
		$arParams["GRUPPER_PROPS"] = "NOT";
	if($arParams["GRUPPER_PROPS"] == "YENISITE_GRUPPER" && !\Bitrix\Main\Loader::includeModule("yenisite.infoblockpropsplus"))
		$arParams["GRUPPER_PROPS"] = "NOT";
}

/* hide compare link from module options */
if(CMax::GetFrontParametrValue('CATALOG_COMPARE') == 'N')
	$arParams["USE_COMPARE"] = 'N';
/**/

$arParams['DISPLAY_WISH_BUTTONS'] = CMax::GetFrontParametrValue('CATALOG_DELAY');

$_SESSION['BLOG_MAX_IMAGE_SIZE'] = ($arParams['MAX_IMAGE_SIZE'] ? $arParams['MAX_IMAGE_SIZE'] : '0.5');

if(!isset($arParams['REVIEW_COMMENT_REQUIRED']) || $arParams['USE_RATING'] === 'N')
	$arParams['REVIEW_COMMENT_REQUIRED'] = "Y";

$arParams["DETAIL_OFFERS_PROPERTY_CODE"] = array_filter($arParams["DETAIL_OFFERS_PROPERTY_CODE"]);
//set params for props from module
$detailOfferPropsParamCode = $arParams["DETAIL_OFFERS_PROPERTY_CODE"] ? "DETAIL_OFFERS_PROPERTY_CODE_RAW" : "DETAIL_OFFERS_PROPERTY_CODE";
\Aspro\Functions\CAsproMax::replacePropsParams($arParams, ["DETAIL_OFFERS_PROPERTY_CODE" => $detailOfferPropsParamCode]);	

$arParams['BIG_DATA_FILTER_IDS'] = $arElement['ID'];

if($bFastViewMode)
	include_once('element_fast_view.php');
else if($bReviewsSort)
	include_once('element_reviews.php');
else
	include_once('element_normal.php');
?>

<? CJSCore::Init(array('ls')); ?>
	<script>//$(document).ready(function(){$(".buy_block .counter_block input[type=text]").change()})</script>

<?
$arExt = [
	'owl_carousel', 
	'catalog_element',
	'detail_gallery',
	'bonus_system',
];

if (!$bFastViewMode) {
	$arExt[] = 'fancybox';
}
if (CMax::GetFrontParametrValue('DETAIL_PICTURE_MODE') === 'MAGNIFIER') {
	$arExt[] = 'xzoom';
}

\Aspro\Max\Functions\Extensions::init($arExt);?>
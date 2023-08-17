<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitronic2\Mobile;
use Bitrix\Main\Loader;
// ##### EDIT PARAMS

if ($arParams['LIST_BRAND_USE'] !== 'N') $arParams['LIST_BRAND_USE'] = 'Y';
if (empty($arParams['LIST_BRAND_PROP_CODE'])) $arParams['LIST_BRAND_PROP_CODE'] = 'BRANDS_REF';

if (empty($arParams["SECTION_HITS_RCM_TYPE"])) $arParams["SECTION_HITS_RCM_TYPE"] = 'bestsell';
if ($arParams["SECTION_HITS_HIDE_NOT_AVAILABLE"] !== 'N') $arParams["SECTION_HITS_HIDE_NOT_AVAILABLE"] = 'Y';

// ##### Set params from setting.panel
global $rz_b2_options;
$arResult['FILTER_PLACE'] = $rz_b2_options['filter-placement'];
$arResult['HOVER-MODE'] = $rz_b2_options['product-hover-effect'];
$arResult['DETAIL_INFO_MODE'] = $rz_b2_options['detail-info-mode'];
$arResult['MENU_CATALOG'] = $rz_b2_options['catalog-placement'];

// hack for fill $arResult["ITEMS"][]["PROPERTIES"] in component bitrix:catalog.section
if(is_array($arParams['LIST_PROPERTY_CODE']))
	foreach($arParams['LIST_PROPERTY_CODE'] as $k => $v)
		if($v==="")
			unset($arParams["LIST_PROPERTY_CODE"][$k]);

if(empty($arParams["LIST_PROPERTY_CODE"]))     $arParams["LIST_PROPERTY_CODE"]     = array(true);
if(empty($arParams["LIST_OFFERS_FIELD_CODE"])) $arParams["LIST_OFFERS_FIELD_CODE"] = array('NAME');
if(empty($arParams["DETAIL_OFFERS_FIELD_CODE"])) $arParams["DETAIL_OFFERS_FIELD_CODE"] = array('NAME');

if(!in_array('DETAIL_PAGE_URL',$arParams["LIST_OFFERS_FIELD_CODE"]))   $arParams["LIST_OFFERS_FIELD_CODE"][]   = 'DETAIL_PAGE_URL';
if(!in_array('DETAIL_PAGE_URL',$arParams["DETAIL_OFFERS_FIELD_CODE"])) $arParams["DETAIL_OFFERS_FIELD_CODE"][] = 'DETAIL_PAGE_URL';
if(!in_array('NAME',           $arParams["DETAIL_OFFERS_FIELD_CODE"])) $arParams["DETAIL_OFFERS_FIELD_CODE"][] = 'NAME';
 
// fill offers params
if(is_array($arParams['OFFER_TREE_PROPS']) && !empty($arParams['OFFER_TREE_PROPS']))
{
	foreach($arParams['OFFER_TREE_PROPS'] as $propCode)
	{
		if(!in_array($propCode,$arParams["DETAIL_OFFERS_PROPERTY_CODE"]))
		{
			$arParams["DETAIL_OFFERS_PROPERTY_CODE"][] = $propCode;
		}
	}
}
if (empty($arParams['OFFER_VAR_NAME'])) $arParams['OFFER_VAR_NAME'] = 'pid';

$arParams["COMPARE_PROPERTY_CODE"] =
$arParams["DETAIL_PROPERTY_CODE"] = 
CRZBitronic2CatalogUtils::getDetailPropShowList($arParams['IBLOCK_ID'], $arParams['SETTINGS_HIDE']);

// resizer sets
$arParams['RESIZER_DETAIL_SMALL'] = intval($arParams['RESIZER_DETAIL_SMALL']) ? $arParams['RESIZER_DETAIL_SMALL'] : 2;
$arParams['RESIZER_DETAIL_BIG'] = intval($arParams['RESIZER_DETAIL_BIG']) ? $arParams['RESIZER_DETAIL_BIG'] : 1;
$arParams['RESIZER_DETAIL_ICON'] = intval($arParams['RESIZER_DETAIL_ICON']) ? $arParams['RESIZER_DETAIL_ICON'] : 6;
$arParams['RESIZER_DETAIL_FLY_BLOCK'] = intval($arParams['RESIZER_DETAIL_FLY_BLOCK']) ? $arParams['RESIZER_DETAIL_FLY_BLOCK'] : 3;
$arParams['RESIZER_SET_CONTRUCTOR'] = intval($arParams['RESIZER_SET_CONTRUCTOR']) ? $arParams['RESIZER_SET_CONTRUCTOR'] : 3;
$arParams['RESIZER_SECTION'] = intval($arParams['RESIZER_SECTION']) ? $arParams['RESIZER_SECTION'] : 3;
$arParams['RESIZER_SECTION_ICON'] = intval($arParams['RESIZER_SECTION_ICON']) ? $arParams['RESIZER_SECTION_ICON'] : 5;
$arParams['RESIZER_COMMENT_AVATAR'] = intval($arParams['RESIZER_COMMENT_AVATAR']) ? $arParams['RESIZER_COMMENT_AVATAR'] : 5;
$arParams['RESIZER_RECOMENDED'] = intval($arParams['RESIZER_RECOMENDED']) ? $arParams['RESIZER_RECOMENDED'] : 3;
$arParams['RESIZER_FILTER'] = intval($arParams['RESIZER_FILTER']) ? $arParams['RESIZER_FILTER'] : 5;

// COMPARE META
if (empty($arParams['COMPARE_META_H1'])) $arParams['COMPARE_META_H1'] = GetMessage('BITRONIC2_CATALOG_COMPARE_H1_DEFAULT');
if (empty($arParams['COMPARE_META_TITLE'])) $arParams['COMPARE_META_TITLE'] = GetMessage('BITRONIC2_CATALOG_COMPARE_TITLE_DEFAULT');
if (empty($arParams['COMPARE_META_KEYWORDS'])) $arParams['COMPARE_META_KEYWORDS'] = GetMessage('BITRONIC2_CATALOG_COMPARE_KEYWORDS_DEFAULT');
if (empty($arParams['COMPARE_META_DESCRIPTION'])) $arParams['COMPARE_META_DESCRIPTION'] = GetMessage('BITRONIC2_CATALOG_COMPARE_DESCRIPTION_DEFAULT');

if (Loader::IncludeModule('yenisite.bitronic2lite') && Loader::IncludeModule('yenisite.market')) {
	$arBasePrice = CMarketPrice::GetBasePrice()->Fetch();
	if (is_array($arBasePrice)) {
		$arParams['LIST_PRICE_SORT'] = 'PROPERTY_'.$arBasePrice['code'];
	}
}

if($_REQUEST["rz_ajax"] !== "y" && $_REQUEST["ajax_basket"] !== "Y")
{
	$arParams['FILTER_VISIBLE_PROPS_COUNT'] = intval($arParams['FILTER_VISIBLE_PROPS_COUNT']);
	if(mobile::isMobile())
	{
		$arParams['FILTER_HIDE'] = true;
	}
	
	if($arParams['FILTER_VISIBLE_PROPS_COUNT'] <= 0)
	{
		$arParams['FILTER_VISIBLE_PROPS_COUNT'] = 3;
	}
		
	// ##### FOR AJAX
	// @var $moduleCode
	include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/include/module_code.php';

	if (Loader::IncludeModule('yenisite.core')) {
		\Yenisite\Core\Ajax::saveParams($this, $arParams);
	}

	if ($_REQUEST['rz_update_catalog_parameters_cache'] === 'Y') {
		$APPLICATION->RestartBuffer();
		die('update');
	}
	
	$arAjaxParams = array(
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"REQUEST_URI" => $_SERVER["REQUEST_URI"],
		"SCRIPT_NAME" => $_SERVER["SCRIPT_NAME"],
	);
	?><script type="text/javascript">$.extend(RZB2.ajax.params, <?=CUtil::PhpToJSObject($arAjaxParams, false, true)?>);</script><?
}

$arParams['DISPLAY_FAVORITE'] = Loader::includeModule('yenisite.favorite') && $arParams['DISPLAY_FAVORITE'] !== 'N';
$arParams['DISPLAY_ONECLICK'] = Loader::includeModule('yenisite.oneclick') && $arParams['DISPLAY_ONECLICK'] === 'Y';
//update parameters to set needed currency (only after CPHPCache check, changes through cookies)
if ($rz_b2_options['currency-switcher'] == 'Y') {
	$arParams['CONVERT_CURRENCY'] = 'Y';
	$arParams['CURRENCY_ID'] = $rz_b2_options['active-currency'];
}

/*
if(COption::GetOptionString("to_smart_filter", "smart_filter_{$arParams['IBLOCK_ID']}", 'N') != 'Y')
{
   $error_convert_to_smart = false ;
   foreach ($arParams['FILTER_PROPERTY_CODE'] as $prop_code)
   {
      if(strlen($prop_code) && $arParams['IBLOCK_ID'])
      {
         $arProp = CIBlockProperty::GetByID($prop_code, $arParams['IBLOCK_ID'])->Fetch() ;
         if($arProp['ID'] && $arProp['CODE'] == $prop_code && $arProp['SMART_FILTER'] != 'Y')
         {
            $arFields = Array('SMART_FILTER' => 'Y', 'IBLOCK_ID' => $arProp['IBLOCK_ID']) ; 
            $ibp = new CIBlockProperty();
            if(!$ibp->Update($arProp['ID'], $arFields))
            {
               if($USER->IsAdmin()) { echo '<br/>'.$ibp->LAST_ERROR;  }
               $error_convert_to_smart= true ;
            }
         }
      }
   }
   if(!$error_convert_to_smart)
   {
      COption::SetOptionString("to_smart_filter", "smart_filter_{$arParams['IBLOCK_ID']}", 'Y');
   }
}*/
?>

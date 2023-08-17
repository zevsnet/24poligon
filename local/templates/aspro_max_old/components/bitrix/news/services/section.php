<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>

<?global $isHideLeftBlock, $arTheme;?>
<?
if(isset($arParams["TYPE_LEFT_BLOCK"]) && $arParams["TYPE_LEFT_BLOCK"]!='FROM_MODULE'){
	$arTheme['LEFT_BLOCK']['VALUE'] = $arParams["TYPE_LEFT_BLOCK"];
}

if(isset($arParams["SIDE_LEFT_BLOCK"]) && $arParams["SIDE_LEFT_BLOCK"]!='FROM_MODULE'){
	$arTheme['SIDE_MENU']['VALUE'] = $arParams["SIDE_LEFT_BLOCK"];
}
?>

<?
if(!$isHideLeftBlock && $APPLICATION->GetProperty("HIDE_LEFT_BLOCK_LIST") == "Y"){
	$APPLICATION->SetPageProperty("HIDE_LEFT_BLOCK", "Y");
}
?>

<?
// get section items count and subsections
$arSectionFilter = CMax::GetCurrentSectionFilter($arResult["VARIABLES"], $arParams);
$arSection = CMaxCache::CIblockSection_GetList(array("CACHE" => array("TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]), "CACHE_GROUP" => array($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()), "MULTI" => "N")), $arSectionFilter, false, array('ID', 'DESCRIPTION', 'PICTURE', 'DETAIL_PICTURE', 'IBLOCK_ID', 'UF_TOP_SEO'));
$arItemFilter = CMax::GetCurrentSectionElementFilter($arResult["VARIABLES"], $arParams);
$arItemFilter["SECTION_ID"] = $arSection["ID"];
global $NavNum; 
$context = \Bitrix\Main\Application::getInstance()->getContext();
if($NavNum){
	$pagen = $NavNum;
}else{
	$pagen = 2;
}
$numPage = $context->getRequest()->get("PAGEN_".$pagen) ?? 1;
$arGroup =  array("iNumPage" => $numPage, "nPageSize" => $arParams['NEWS_COUNT']);
$arSelect = array('ID', 'NAME','PREVIEW_TEXT');
$arItemFilter['INCLUDE_SUBSECTIONS'] ='Y';
$arElement = CMaxCache::CIblockElement_GetList(array("CACHE" => array("TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]), "MULTI" => "Y"), $arParams['SORT_BY1'] => $arParams['SORT_ORDER1'], $arParams['SORT_BY2'] => $arParams['SORT_ORDER2']), $arItemFilter, false, $arGroup, $arSelect);
if($pagen != $NavNum){
	$NavNum = $pagen - 1;
}
?>

<?foreach ($arElement as $element):
	$arSchema[] = array(
		"@context" => "https://schema.org",
		"@type" => "Service",
		"name" => $element["NAME"],
		"description" => $element["PREVIEW_TEXT"]
	);
endforeach;?>
<script type="application/ld+json"><?=str_replace("'", "\"", CUtil::PhpToJSObject($arSchema, false, true));?></script>
<?
if($arSection && !$arItemFilter['SECTION_ID'])
{
	$arItemFilter['SECTION_ID'] = $arSection['ID'];
}
$itemsCnt = CMaxCache::CIblockElement_GetList(array("CACHE" => array("TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]), "CACHE_GROUP" => array($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()))), $arItemFilter, array());
CMax::AddMeta(
	array(
		'og:description' => $arSection['DESCRIPTION'],
		'og:image' => (($arSection['PICTURE'] || $arSection['DETAIL_PICTURE']) ? CFile::GetPath(($arSection['PICTURE'] ? $arSection['PICTURE'] : $arSection['DETAIL_PICTURE'])) : false),
	)
);
$arSubSectionFilter = CMax::GetCurrentSectionSubSectionFilter($arResult["VARIABLES"], $arParams, $arSection['ID']);
$arSubSections = CMaxCache::CIblockSection_GetList(array("CACHE" => array("TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]), "MULTI" => "Y")), $arSubSectionFilter, false, array("ID", "DEPTH_LEVEL"));

global $arRegion;
?>
<?if(!$arSection && $arParams['SET_STATUS_404'] !== 'Y'):?>
	<div class="alert alert-warning"><?=GetMessage("SECTION_NOTFOUND")?></div>
<?elseif(!$arSection && $arParams['SET_STATUS_404'] === 'Y'):?>
	<?CMax::goto404Page();?>
<?else:?>

	<?$this->SetViewTarget('product_share');?>
		<?if($arParams['USE_RSS'] !== 'N'):?>
			<div class="colored_theme_hover_bg-block">
				<?=CMax::ShowRSSIcon(CComponentEngine::makePathFromTemplate($arResult['FOLDER'].$arResult['URL_TEMPLATES']['rss_section'], array_map('urlencode', $arResult['VARIABLES'])));?>
			</div>
		<?endif;?>
	<?$this->EndViewTarget();?>
	
	<?/* start tags */?>
	<?
	if(isset($arItemFilter['CODE']))
	{
		unset($arItemFilter['CODE']);
		unset($arItemFilter['SECTION_CODE']);
	}
	if(isset($arItemFilter['ID']))
	{
		unset($arItemFilter['ID']);
		unset($arItemFilter['SECTION_ID']);
	}
	?>
	<?
	$arTags = array();
	
	$arElements = CMaxCache::CIblockElement_GetList(array('CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'MULTI' => 'Y')), $arItemFilter, false, false, array('ID', 'TAGS'));

	foreach($arElements as $arElement)
	{
		if($arElement['TAGS'])
		{
			$arTags[] = explode(',', $arElement['TAGS']);
		}
	}
	?>
	<?$this->__component->__template->SetViewTarget('under_sidebar_content');?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:search.tags.cloud",
			"main",
			Array(
				"CACHE_TIME" => "86400",
				"CACHE_TYPE" => "A",
				"CHECK_DATES" => "Y",
				"COLOR_NEW" => "3E74E6",
				"COLOR_OLD" => "C0C0C0",
				"COLOR_TYPE" => "N",
				"TAGS_ELEMENT" => $arTags,
				"FILTER_NAME" => $arParams["FILTER_NAME"],
				"FONT_MAX" => "50",
				"FONT_MIN" => "10",
				"PAGE_ELEMENTS" => "150",
				"PERIOD" => "",
				"PERIOD_NEW_TAGS" => "",
				"SHOW_CHAIN" => "N",
				"SORT" => "NAME",
				"TAGS_INHERIT" => "Y",
				"URL_SEARCH" => SITE_DIR."search/index.php",
				"WIDTH" => "100%",
				"arrFILTER" => array("iblock_aspro_max_content"),
				"arrFILTER_iblock_aspro_max_content" => array($arParams["IBLOCK_ID"])
			), $component, array('HIDE_ICONS' => 'Y')
		);?>
	<?$this->__component->__template->EndViewTarget();?>
	<?/* end tags */?>
	
	<?if(!$arSubSections && !$itemsCnt):?>
		<div class="alert alert-warning"><?=GetMessage("SECTION_EMPTY")?></div>
	<?endif;?>

	<?//seo
	$arParams["LANDING_IBLOCK_ID"] = (!isset($arParams["LANDING_IBLOCK_ID"]) || !$arParams["LANDING_IBLOCK_ID"] ? CMaxCache::$arIBlocks[SITE_ID]["aspro_max_catalog"]["aspro_max_catalog_info"][0] : $arParams["LANDING_IBLOCK_ID"]);
	$arParams["TIZERS_IBLOCK_ID"] = (!isset($arParams["TIZERS_IBLOCK_ID"]) || !$arParams["TIZERS_IBLOCK_ID"] ? CMaxCache::$arIBlocks[SITE_ID]["aspro_max_content"]["aspro_max_tizers"][0] : $arParams["TIZERS_IBLOCK_ID"]);

	/*fix*/
	$current_url =  $APPLICATION->GetCurDir();
	$real_url = $current_url;
	$current_url =  str_replace(array('%25', '&quot;', '&#039;'), array('%', '"', "'"), $current_url); // for utf-8 fix some problem
	$encode_current_url = urlencode($current_url);
	$gaps_encode_current_url = str_replace(' ', '%20', $current_url);
	$encode_current_url_slash = str_replace(array('%2F', '+'), array('/', '%20'), $encode_current_url);
	$urldecodedCP = iconv("windows-1251", "utf-8//IGNORE", $current_url);
	$urldecodedCP_slash = str_replace(array('%2F'), array('/'), rawurlencode($urldecodedCP));
	$replacements = array('"' ,'%27', '%20', '%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%3F', '%23', '%5B', '%5D');// for fix some problem  with spec chars in prop
	$entities = array("&quot;", '&#039;', ' ', '!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "?", "#", "[", "]");
	$replacedSpecChar = str_replace($entities, $replacements, $current_url);
	/**/

	$arSeoFilter = array(
		"IBLOCK_ID" => $arParams["LANDING_IBLOCK_ID"], 
		"ACTIVE"=>"Y",
		"PROPERTY_FILTER_URL" => array($real_url, $current_url, $gaps_encode_current_url, $urldecodedCP_slash, $encode_current_url_slash, $replacedSpecChar),
	);
	if($arRegion) {
		$arSeoFilter[] = array(
			'LOGIC' => 'OR',
			array('PROPERTY_LINK_REGION_VALUE' => $arRegion['ID']),
			array('PROPERTY_LINK_REGION_VALUE' => false),
		);	
	}
	$arSeoItems = CMaxCache::CIBLockElement_GetList(array('CACHE' => array("MULTI" =>"Y", "TAG" => CMaxCache::GetIBlockCacheTag($arParams["LANDING_IBLOCK_ID"]))), $arSeoFilter, false, false, array("ID", "IBLOCK_ID", "PROPERTY_FILTER_URL","PROPERTY_LINK_REGION"));
	$arSeoItem = array();
	if($arSeoItems)
	{
		$iLandingItemID = 0;
		$current_url =  $APPLICATION->GetCurDir();
		$url = urldecode(str_replace(' ', '+', $current_url));
		foreach($arSeoItems as $seoKey => $arItem)
		{
			if(!$arSeoItem)
			{
				$urldecoded = urldecode($arItem["PROPERTY_FILTER_URL_VALUE"]);
				$urldecodedCP = iconv("utf-8","windows-1251", $urldecoded);
				if($urldecoded == $url || $urldecoded == $current_url || $urldecodedCP == $current_url)
				{
					$iLandingItemID = $arItem['ID'];
					$arSeoItem = CMaxCache::CIBLockElement_GetList(array('SORT' => 'ASC', 'CACHE' => array("MULTI" => "N", "TAG" => CMaxCache::GetIBlockCacheTag($catalogInfoIblockId))), array("IBLOCK_ID" => $arParams["LANDING_IBLOCK_ID"], "ID" => $iLandingItemID), false, false, array("ID", "IBLOCK_ID", "NAME", "PREVIEW_TEXT", "DETAIL_PICTURE", "PROPERTY_FILTER_URL", "PROPERTY_LINK_REGION", "PROPERTY_FORM_QUESTION", "PROPERTY_SECTION_SERVICES", "PROPERTY_TIZERS", "PROPERTY_SECTION", "DETAIL_TEXT", "PROPERTY_I_ELEMENT_PAGE_TITLE", "PROPERTY_I_ELEMENT_PREVIEW_PICTURE_FILE_ALT", "PROPERTY_I_ELEMENT_PREVIEW_PICTURE_FILE_TITLE", "PROPERTY_I_SKU_PAGE_TITLE", "PROPERTY_I_SKU_PREVIEW_PICTURE_FILE_ALT", "PROPERTY_I_SKU_PREVIEW_PICTURE_FILE_TITLE", "ElementValues"));

					$arIBInheritTemplates = array(
						"ELEMENT_PAGE_TITLE" => $arSeoItem["PROPERTY_I_ELEMENT_PAGE_TITLE_VALUE"],
						"ELEMENT_PREVIEW_PICTURE_FILE_ALT" => $arSeoItem["PROPERTY_I_ELEMENT_PREVIEW_PICTURE_FILE_ALT_VALUE"],
						"ELEMENT_PREVIEW_PICTURE_FILE_TITLE" => $arSeoItem["PROPERTY_I_ELEMENT_PREVIEW_PICTURE_FILE_TITLE_VALUE"],
						"SKU_PAGE_TITLE" => $arSeoItem["PROPERTY_I_SKU_PAGE_TITLE_VALUE"],
						"SKU_PREVIEW_PICTURE_FILE_ALT" => $arSeoItem["PROPERTY_I_SKU_PREVIEW_PICTURE_FILE_ALT_VALUE"],
						"SKU_PREVIEW_PICTURE_FILE_TITLE" => $arSeoItem["PROPERTY_I_SKU_PREVIEW_PICTURE_FILE_TITLE_VALUE"],
					);
				}
			}
			$arSeoItems[$seoKey] = $arItem['ID'];
		}
	}
	?>
	
	<div class="main-section-wrapper">
		<?if($arSection['UF_TOP_SEO'] && strpos($_SERVER['REQUEST_URI'], 'PAGEN') === false):?>
			<div class="text_before_items">
				<p class="introtext"><?=$arSection['UF_TOP_SEO'];?></p>				
			</div>
		<?endif;?>

		<?if($arSubSections):?>
			<?// sections list?>
			<?$sViewSectionTemplate = $arParams["SECTION_TYPE_VIEW"] === 'FROM_MODULE' ?  $arTheme['SERVICES_PAGE_SECTION']['VALUE'] : $arParams["SECTION_TYPE_VIEW"];?>
			<?@include_once('page_blocks/'.$sViewSectionTemplate.'.php');?>
		<?endif;?>
		<?// section elements?>
		<?$sViewElementsTemplate = $arParams["SECTION_ELEMENTS_TYPE_VIEW"] === 'FROM_MODULE' ? $arTheme['SERVICES_PAGE']['VALUE'] : $arParams["SECTION_ELEMENTS_TYPE_VIEW"];?>
		<?@include_once('page_blocks/'.$sViewElementsTemplate.'.php');?>
		
		<?if($arSection['DESCRIPTION'] && strpos($_SERVER['REQUEST_URI'], 'PAGEN') === false):?>
			<div class="text_after_items">
				<?=$arSection['DESCRIPTION'];?>
			</div>
		<?endif;?>
	</div>
<?endif;?>

<?if(\Bitrix\Main\Loader::includeModule("sotbit.seometa")):?>
	<?$APPLICATION->IncludeComponent(
		"sotbit:seo.meta",
		".default",
		array(
			"FILTER_NAME" => $arParams["FILTER_NAME"],
			"SECTION_ID" => $arSection['ID'],
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
		)
	);?>
<?endif;?>
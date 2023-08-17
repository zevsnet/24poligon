<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<?

use Bitrix\Main\Loader,
	Bitrix\Main\Localization\Loc,
	Bitrix\Main\ModuleManager;

Loader::includeModule("iblock");

global $arTheme, $NextSectionID, $arRegion;
$arPageParams = $arSection = $section = array();
$_SESSION['SMART_FILTER_VAR'] = $arParams['FILTER_NAME'];

$APPLICATION->SetPageProperty("HIDE_LEFT_BLOCK", (($arTheme["LEFT_BLOCK_CATALOG_SECTIONS"]["VALUE"] == "Y" && !($arTheme['HEADER_TYPE']['VALUE'] == 28 || $arTheme['HEADER_TYPE']['VALUE'] == 29)  ? "N" : "Y")));
?>
<?$APPLICATION->AddViewContent('right_block_class', 'catalog_page ');?>
<?if(CMax::checkAjaxRequest2()):?>
	<div>
<?endif;?>
<div class="top-content-block"><?$APPLICATION->ShowViewContent('top_content');?><?$APPLICATION->ShowViewContent('top_content2');?></div>
<?if(CMax::checkAjaxRequest2()):?>
	</div>
<?endif;?>


<?// get current section ID
if($arResult["VARIABLES"]["SECTION_ID"] > 0){
	$arSectionFilter = array('GLOBAL_ACTIVE' => 'Y', "ID" => $arResult["VARIABLES"]["SECTION_ID"], "IBLOCK_ID" => $arParams["IBLOCK_ID"]);
}
elseif(strlen(trim($arResult["VARIABLES"]["SECTION_CODE"])) > 0){
	$arSectionFilter = array('GLOBAL_ACTIVE' => 'Y', "=CODE" => $arResult["VARIABLES"]["SECTION_CODE"], "IBLOCK_ID" => $arParams["IBLOCK_ID"]);
}
$section = CMaxCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), CMax::makeSectionFilterInRegion($arSectionFilter), false, array("ID", "IBLOCK_ID", "NAME", "DESCRIPTION", "UF_SECTION_DESCR", "UF_OFFERS_TYPE", 'UF_FILTER_VIEW', 'UF_LINE_ELEMENT_CNT', 'UF_SECTION_BG_DARK', 'UF_LINKED_BLOG', 'UF_BLOG_BOTTOM', 'UF_BLOG_WIDE', 'UF_BLOG_MOBILE', $arParams["SECTION_DISPLAY_PROPERTY"], $arParams["SECTION_BG"], "IBLOCK_SECTION_ID", "DEPTH_LEVEL", "LEFT_MARGIN", "RIGHT_MARGIN"));

$typeSKU = '';
$bSetElementsLineRow = false;

if ($section) {
	$arSection["ID"] = $section["ID"];
	$arSection["NAME"] = $section["NAME"];
	$arSection["IBLOCK_SECTION_ID"] = $section["IBLOCK_SECTION_ID"];
	$arSection["DEPTH_LEVEL"] = $section["DEPTH_LEVEL"];
	if ($section[$arParams["SECTION_DISPLAY_PROPERTY"]]) {
		$arDisplayRes = CUserFieldEnum::GetList(array(), array("ID" => $section[$arParams["SECTION_DISPLAY_PROPERTY"]]));
		if ($arDisplay = $arDisplayRes->GetNext()) {
			$arSection["DISPLAY"] = $arDisplay["XML_ID"];
		}
	}
	if ($section["UF_LINE_ELEMENT_CNT"]) {
		$arCntRes = CUserFieldEnum::GetList(array(), array("ID" => $section["UF_LINE_ELEMENT_CNT"]));
		if ($arLineCnt = $arCntRes->GetNext()) {
			$arParams["LINE_ELEMENT_COUNT"] = $arLineCnt["XML_ID"];
			$bSetElementsLineRow = true;
		}
	}

	$posSectionDescr = COption::GetOptionString("aspro.max", "SHOW_SECTION_DESCRIPTION", "BOTTOM", SITE_ID);
	if(strlen($section["DESCRIPTION"])){
		$arSection["DESCRIPTION"] = $section["DESCRIPTION"];
	}
	if(strlen($section["UF_SECTION_DESCR"])){
		$arSection["UF_SECTION_DESCR"] = $section["UF_SECTION_DESCR"];
	}

	global $arSubSectionFilter;
	$arSubSectionFilter = array(
		"SECTION_ID" => $arSection["ID"],
		"IBLOCK_ID" => $arParams['IBLOCK_ID'],
		"ACTIVE" => "Y",
		"GLOBAL_ACTIVE" => "Y",
	);
	$iSectionsCount = CMaxCache::CIBlockSection_GetCount(array('CACHE' => array("TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), CMax::makeSectionFilterInRegion($arSubSectionFilter));

	$catalog_available = $arParams['HIDE_NOT_AVAILABLE'];
	if (!isset($arParams['HIDE_NOT_AVAILABLE'])) {
		$catalog_available = 'N';
	}
	if ($arParams['HIDE_NOT_AVAILABLE'] != 'Y' && $arParams['HIDE_NOT_AVAILABLE'] != 'L') {
		$catalog_available = 'N';
	}
	if ($arParams['HIDE_NOT_AVAILABLE'] == 'Y') {
		$catalog_available = 'Y';
	}
	$arElementFilter = array("SECTION_ID" => $arSection["ID"], "ACTIVE" => "Y", "INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"], "IBLOCK_ID" => $arParams["IBLOCK_ID"]);
	if ($arParams["INCLUDE_SUBSECTIONS"] == "A") {
		$arElementFilter["INCLUDE_SUBSECTIONS"] = "Y";
		$arElementFilter["SECTION_GLOBAL_ACTIVE"] = "Y";
		$arElementFilter["SECTION_ACTIVE "] = "Y";
	}
	if ($arParams['HIDE_NOT_AVAILABLE'] == 'Y') {
		$arElementFilter["CATALOG_AVAILABLE"] = $catalog_available;
	}

	$itemsCnt = CMaxCache::CIBlockElement_GetList(array("CACHE" => array("TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), CMax::makeElementFilterInRegion($arElementFilter), array());

	// set offer type & smartfilter view
	$typeTmpSKU = $viewTmpFilter = 0;
	if ($section['UF_OFFERS_TYPE']) {
		$typeTmpSKU = $section['UF_OFFERS_TYPE'];
	}
	if ($section['UF_FILTER_VIEW']) {
		$viewTmpFilter = $section['UF_FILTER_VIEW'];
	}
	if ($section['UF_LINKED_BLOG']) {
		$linkedArticles = $section['UF_LINKED_BLOG'];
	}
	if ($section['UF_BLOG_BOTTOM']) {
		$linkedArticlesPos = 'bottom';
	}
	if ($section['UF_BLOG_WIDE']) {
		$linkedArticlesRows = $section['UF_BLOG_WIDE'];
	}
	if ($section['UF_BLOG_MOBILE']) {
		$linkedArticlesRowsMobile = $section['UF_BLOG_MOBILE'];
	}

	if (!$typeTmpSKU || !$viewTmpFilter || !$arSection["DISPLAY"] || !$bSetElementsLineRow || !$linkedArticles
		|| !$linkedArticlesPos || $linkedArticlesRows || $linkedArticlesRowsMobile) {
		if ($section['DEPTH_LEVEL'] > 1) {
			$sectionParent = CMaxCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "ID" => $section["IBLOCK_SECTION_ID"], "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, array("ID", "IBLOCK_ID", "NAME", "UF_OFFERS_TYPE", 'UF_FILTER_VIEW', $arParams["SECTION_DISPLAY_PROPERTY"], "UF_LINE_ELEMENT_CNT", "UF_LINKED_BLOG", 'UF_BLOG_BOTTOM', 'UF_BLOG_WIDE', 'UF_BLOG_MOBILE',));
			if ($sectionParent['UF_OFFERS_TYPE'] && !$typeTmpSKU) {
				$typeTmpSKU = $sectionParent['UF_OFFERS_TYPE'];
			}
			if ($sectionParent['UF_FILTER_VIEW'] && !$viewTmpFilter) {
				$viewTmpFilter = $sectionParent['UF_FILTER_VIEW'];
			}
			if ($sectionParent['UF_LINKED_BLOG'] && !$linkedArticles) {
				$linkedArticles = $sectionParent['UF_LINKED_BLOG'];
			}
			if ($sectionParent['UF_BLOG_BOTTOM'] && !$linkedArticlesPos) {
				$linkedArticlesPos = 'bottom';
			}
			if ($sectionParent['UF_BLOG_WIDE'] && !$linkedArticlesRows) {
				$linkedArticlesRows = $sectionParent['UF_BLOG_WIDE'];
			}
			if ($sectionParent['UF_BLOG_MOBILE'] && !$linkedArticlesRowsMobile) {
				$linkedArticlesRowsMobile = $sectionParent['UF_BLOG_MOBILE'];
			}
			if ($sectionParent[$arParams["SECTION_DISPLAY_PROPERTY"]] && !$arSection["DISPLAY"]) {
				$arDisplayRes = CUserFieldEnum::GetList(array(), array("ID" => $sectionParent[$arParams["SECTION_DISPLAY_PROPERTY"]]));
				if ($arDisplay = $arDisplayRes->GetNext()) {
					$arSection["DISPLAY"] = $arDisplay["XML_ID"];
				}
			}
			if ($sectionParent["UF_LINE_ELEMENT_CNT"] && !$bSetElementsLineRow) {
				$arCntRes = CUserFieldEnum::GetList(array(), array("ID" => $sectionParent["UF_LINE_ELEMENT_CNT"]));
				if ($arLineCnt = $arCntRes->GetNext()) {
					$arParams["LINE_ELEMENT_COUNT"] = $arLineCnt["XML_ID"];
					$bSetElementsLineRow = true;
				}
			}

			if ($section['DEPTH_LEVEL'] > 2) {
				if (!$typeTmpSKU || !$viewTmpFilter || !$arSection["DISPLAY"] || !$bSetElementsLineRow) {
					$sectionRoot = CMaxCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "<=LEFT_BORDER" => $section["LEFT_MARGIN"], ">=RIGHT_BORDER" => $section["RIGHT_MARGIN"], "DEPTH_LEVEL" => 1, "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, array("ID", "IBLOCK_ID", "NAME", "UF_OFFERS_TYPE", 'UF_FILTER_VIEW', $arParams["SECTION_DISPLAY_PROPERTY"], "UF_LINE_ELEMENT_CNT", "UF_LINKED_BLOG", 'UF_BLOG_BOTTOM', 'UF_BLOG_WIDE', 'UF_BLOG_MOBILE',));
					if ($sectionRoot['UF_OFFERS_TYPE'] && !$typeTmpSKU) {
						$typeTmpSKU = $sectionRoot['UF_OFFERS_TYPE'];
					}
					if ($sectionRoot['UF_FILTER_VIEW'] && !$viewTmpFilter) {
						$viewTmpFilter = $sectionRoot['UF_FILTER_VIEW'];
					}
					if ($sectionRoot['UF_LINKED_BLOG'] && !$linkedArticles) {
						$linkedArticles = $sectionRoot['UF_LINKED_BLOG'];
					}
					if ($sectionRoot['UF_BLOG_BOTTOM'] && !$linkedArticlesPos) {
						$linkedArticlesPos = 'bottom';
					}
					if ($sectionRoot['UF_BLOG_WIDE'] && !$linkedArticlesRows) {
						$linkedArticlesRows = $sectionRoot['UF_BLOG_WIDE'];
					}
					if ($sectionRoot['UF_BLOG_MOBILE'] && !$linkedArticlesRowsMobile) {
						$linkedArticlesRowsMobile = $sectionRoot['UF_BLOG_MOBILE'];
					}
					if ($sectionRoot[$arParams["SECTION_DISPLAY_PROPERTY"]] && !$arSection["DISPLAY"]) {
						$arDisplayRes = CUserFieldEnum::GetList(array(), array("ID" => $sectionRoot[$arParams["SECTION_DISPLAY_PROPERTY"]]));
						if ($arDisplay = $arDisplayRes->GetNext()) {
							$arSection["DISPLAY"] = $arDisplay["XML_ID"];
						}
					}
					if ($sectionRoot["UF_LINE_ELEMENT_CNT"] && !$bSetElementsLineRow) {
						$arCntRes = CUserFieldEnum::GetList(array(), array("ID" => $sectionRoot["UF_LINE_ELEMENT_CNT"]));
						if ($arLineCnt = $arCntRes->GetNext()) {
							$arParams["LINE_ELEMENT_COUNT"] = $arLineCnt["XML_ID"];
							$bSetElementsLineRow = true;
						}
					}
				}
			}
		}
	}
	if($typeTmpSKU){
		$rsTypes = CUserFieldEnum::GetList(array(), array("ID" => $typeTmpSKU));
		if($arType = $rsTypes->Fetch()){
			$typeSKU = $arType['XML_ID'];
			$arTheme['TYPE_SKU']['VALUE'] = $typeSKU;
		}
	}
	if($viewTmpFilter){
		$rsViews = CUserFieldEnum::GetList(array(), array('ID' => $viewTmpFilter));
		if($arView = $rsViews->Fetch()){
			$viewFilter = $arView['XML_ID'];
			$arTheme['FILTER_VIEW']['VALUE'] = strtoupper($viewFilter);
		}
	}
}
$linerow = $arParams["LINE_ELEMENT_COUNT"];

if (!isset($linkedArticlesPos) || !$linkedArticlesPos) {
	$linkedArticlesPos = 'content';
}
if (!isset($linkedArticlesRows) || !$linkedArticlesRows) {
	$linkedArticlesRows = 1;
}
if (!isset($linkedArticlesRowsMobile) || !$linkedArticlesRowsMobile) {
	$linkedArticlesRowsMobile = 1;
}

$bSimpleSectionTemplate = (isset($arSection["DISPLAY"]) && $arSection["DISPLAY"] == "simple");

if ($bSimpleSectionTemplate) {
	$APPLICATION->SetPageProperty("HIDE_LEFT_BLOCK", "Y");
	$APPLICATION->AddViewContent('right_block_class', 'simple_page ');
	unset($arParams['LANDING_POSITION']);

	$template = 'catalog_'.$arSection["DISPLAY"];

	$arParams["USE_PRICE_COUNT"] = "N";
	$bSetElementsLineRow = true;

	$arTheme['MOBILE_CATALOG_LIST_ELEMENTS_COMPACT']['VALUE'] = 'Y';
	$arTheme['TYPE_SKU']['VALUE'] = 'TYPE_2';
}?>

<?$bHideSideSectionBlock = ($arParams["SHOW_SIDE_BLOCK_LAST_LEVEL"] == "Y" && $iSectionsCount && $arParams["INCLUDE_SUBSECTIONS"] == "N");
if ($bHideSideSectionBlock) {
	$APPLICATION->SetPageProperty("HIDE_LEFT_BLOCK", "Y");
}?>

<?$bShowLeftBlock = (!$bSimpleSectionTemplate && ($APPLICATION->GetProperty("HIDE_LEFT_BLOCK") != "Y" && !($arTheme['HEADER_TYPE']['VALUE'] == 28 || $arTheme['HEADER_TYPE']['VALUE'] == 29)));?>

<div class="main-catalog-wrapper clearfix">
	<div class="section-content-wrapper <?=($bShowLeftBlock ? 'with-leftblock' : '');?>">
		<?
		if($section)
		{
			?>

			<?$this->SetViewTarget("section_bnr_h1_content");?>
				<?if($section[$arParams['SECTION_BG']]):?>
					<div class="section-banner-top">
						<div class="section-banner-top__picture" style="background: url(<?=CFile::GetPath($section[$arParams['SECTION_BG']])?>) center/cover no-repeat;"></div>
					</div>
				<?endif;?>
			<?$this->EndViewTarget();?>

			<?if($section[$arParams['SECTION_BG']]):?>
				<?global $dopClass;
					$dopClass .= ' has-secion-banner';
					if(!$section['UF_SECTION_BG_DARK'])
						$dopClass .= ' light-menu-color';?>
				<div class="js-banner" data-class="<?=$dopClass?>"></div>
			<?endif;?>
		<?}
		else{
			\Bitrix\Iblock\Component\Tools::process404(
				""
				,($arParams["SET_STATUS_404"] === "Y")
				,($arParams["SET_STATUS_404"] === "Y")
				,($arParams["SHOW_404"] === "Y")
				,$arParams["FILE_404"]
			);
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

		if($arParams['LIST_PRICES'])
		{
			foreach($arParams['LIST_PRICES'] as $key => $price)
			{
				if(!$price)
					unset($arParams['LIST_PRICES'][$key]);
			}
		}

		if($arParams['STORES'])
		{
			foreach($arParams['STORES'] as $key => $store)
			{
				if(!$store)
					unset($arParams['STORES'][$key]);
			}
		}

		$NextSectionID = $arSection["ID"];?>

		<?
		//seo
		$catalogInfoIblockId = CMaxCache::$arIBlocks[SITE_ID]["aspro_max_catalog"]["aspro_max_catalog_info"][0];
		if($catalogInfoIblockId && !$bSimpleSectionTemplate){
			$arSeoItems = CMaxCache::CIBLockElement_GetList(array('SORT' => 'ASC', 'CACHE' => array("MULTI" => "Y", "TAG" => CMaxCache::GetIBlockCacheTag($catalogInfoIblockId))), array("IBLOCK_ID" => $catalogInfoIblockId, "ACTIVE" => "Y"), false, false, array("ID", "IBLOCK_ID", "PROPERTY_FILTER_URL", "PROPERTY_LINK_REGION"));
			$arSeoItem = $arTmpRegionsLanding = array();
			if($arSeoItems)
			{
				$iLandingItemID = 0;
				$current_url =  $APPLICATION->GetCurDir();
				$url = urldecode(str_replace(' ', '+', $current_url));
				foreach($arSeoItems as $arItem)
				{
					if(!is_array($arItem['PROPERTY_LINK_REGION_VALUE']))
						$arItem['PROPERTY_LINK_REGION_VALUE'] = (array)$arItem['PROPERTY_LINK_REGION_VALUE'];

					if(!$arSeoItem)
					{
						$urldecoded = urldecode($arItem["PROPERTY_FILTER_URL_VALUE"]);
						$urldecodedCP = iconv("utf-8", "windows-1251//IGNORE", $urldecoded);
						if($urldecoded == $url || $urldecoded == $current_url || $urldecodedCP == $current_url)
						{
							if($arItem['PROPERTY_LINK_REGION_VALUE'])
							{
								if($arRegion && in_array($arRegion['ID'], $arItem['PROPERTY_LINK_REGION_VALUE']))
									$arSeoItem = $arItem;
							}
							else
							{
								$arSeoItem = $arItem;
							}

							if($arSeoItem)
							{
								$iLandingItemID = $arSeoItem['ID'];
								$arSeoItem = CMaxCache::CIBLockElement_GetList(array('SORT' => 'ASC', 'CACHE' => array("MULTI" => "N", "TAG" => CMaxCache::GetIBlockCacheTag($catalogInfoIblockId))), array("IBLOCK_ID" => $catalogInfoIblockId, "ID" => $iLandingItemID), false, false, array("ID", "IBLOCK_ID", "NAME", "PREVIEW_TEXT", "DETAIL_PICTURE", "PROPERTY_FILTER_URL", "PROPERTY_LINK_REGION", "PROPERTY_FORM_QUESTION", "PROPERTY_SECTION_SERVICES", "PROPERTY_TIZERS", "PROPERTY_SECTION", "DETAIL_TEXT", "PROPERTY_I_ELEMENT_PAGE_TITLE", "PROPERTY_I_ELEMENT_PREVIEW_PICTURE_FILE_ALT", "PROPERTY_I_ELEMENT_PREVIEW_PICTURE_FILE_TITLE", "PROPERTY_I_SKU_PAGE_TITLE", "PROPERTY_I_SKU_PREVIEW_PICTURE_FILE_ALT", "PROPERTY_I_SKU_PREVIEW_PICTURE_FILE_TITLE", "ElementValues"));

								$arIBInheritTemplates = array(
									"ELEMENT_PAGE_TITLE" => $arSeoItem["PROPERTY_I_ELEMENT_PAGE_TITLE_VALUE"],
									"ELEMENT_PREVIEW_PICTURE_FILE_ALT" => $arSeoItem["PROPERTY_I_ELEMENT_PREVIEW_PICTURE_FILE_ALT_VALUE"],
									"ELEMENT_PREVIEW_PICTURE_FILE_TITLE" => $arSeoItem["PROPERTY_I_ELEMENT_PREVIEW_PICTURE_FILE_TITLE_VALUE"],
									"SKU_PAGE_TITLE" => $arSeoItem["PROPERTY_I_SKU_PAGE_TITLE_VALUE"],
									"SKU_PREVIEW_PICTURE_FILE_ALT" => $arSeoItem["PROPERTY_I_SKU_PREVIEW_PICTURE_FILE_ALT_VALUE"],
									"SKU_PREVIEW_PICTURE_FILE_TITLE" => $arSeoItem["PROPERTY_I_SKU_PREVIEW_PICTURE_FILE_TITLE_VALUE"],
								);

								\Aspro\Max\Smartseo\General\Smartseo::disallowNoindexRule(true);
							}
						}
					}

					if($arItem['PROPERTY_LINK_REGION_VALUE'])
					{
						if(!$arRegion || !in_array($arRegion['ID'], $arItem['PROPERTY_LINK_REGION_VALUE']))
							$arTmpRegionsLanding[] = $arItem['ID'];
					}
				}
			}

			if ($arSeoItems && $bHideSideSectionBlock) {
				$arSeoItems = [];
			}
		}

		if($arRegion)
		{
			if($arRegion["LIST_STORES"] && $arParams["HIDE_NOT_AVAILABLE"] == "Y")
			{
				if($arParams['STORES']){					
					if(CMax::checkVersionModule('18.6.200', 'iblock')){
						$arStoresFilter = array(
							'STORE_NUMBER' => $arParams['STORES'],
							'>STORE_AMOUNT' => 0,
						);						
					}
					else{
						if(count($arParams['STORES']) > 1){
							$arStoresFilter = array('LOGIC' => 'OR');
							foreach($arParams['STORES'] as $storeID)
							{
								$arStoresFilter[] = array(">CATALOG_STORE_AMOUNT_".$storeID => 0);
							}
						}
						else{
							foreach($arParams['STORES'] as $storeID)
							{
								$arStoresFilter = array(">CATALOG_STORE_AMOUNT_".$storeID => 0);
							}
						}
					}

					$arTmpFilter = array('!TYPE' => array('2', '3'));
					if($arStoresFilter){
						if(!CMax::checkVersionModule('18.6.200', 'iblock') && count($arStoresFilter) > 1){
							$arTmpFilter[] = $arStoresFilter;
						}
						else{
							$arTmpFilter = array_merge($arTmpFilter, $arStoresFilter);
						}

						$GLOBALS[$arParams["FILTER_NAME"]][] = array(
							'LOGIC' => 'OR',
							array('TYPE' => array('2','3')),
							$arTmpFilter,
						);
						
					}
				}
			}
			$arParams["USE_REGION"] = "Y";

			$GLOBALS[$arParams['FILTER_NAME']]['IBLOCK_ID'] = $arParams['IBLOCK_ID'];
			CMax::makeElementFilterInRegion($GLOBALS[$arParams['FILTER_NAME']]);
		}

		/* hide compare link from module options */
		if(CMax::GetFrontParametrValue('CATALOG_COMPARE') == 'N')
			$arParams["USE_COMPARE"] = 'N';
		/**/

		$arParams['DISPLAY_WISH_BUTTONS'] = CMax::GetFrontParametrValue('CATALOG_DELAY');
		?>
		<?if(!in_array("DETAIL_PAGE_URL", (array)$arParams["LIST_OFFERS_FIELD_CODE"]))
			$arParams["LIST_OFFERS_FIELD_CODE"][] = "DETAIL_PAGE_URL";?>

		<?$arTransferParams = array(
			"SHOW_ABSENT" => $arParams["SHOW_ABSENT"],
			"HIDE_NOT_AVAILABLE_OFFERS" => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],
			"PRICE_CODE" => $arParams["PRICE_CODE"],
			"OFFER_TREE_PROPS" => $arParams["OFFER_TREE_PROPS"],
			"OFFER_SHOW_PREVIEW_PICTURE_PROPS" => $arParams["OFFER_SHOW_PREVIEW_PICTURE_PROPS"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
			"CURRENCY_ID" => $arParams["CURRENCY_ID"],
			"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
			"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
			"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
			"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
			"LIST_OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			"LIST_OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
			"SHOW_DISCOUNT_TIME" => $arParams["SHOW_DISCOUNT_TIME"],
			"SHOW_COUNTER_LIST" => $arParams["SHOW_COUNTER_LIST"],
			"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
			"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
			"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
			"SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"],
			"SHOW_DISCOUNT_PERCENT" => $arParams["SHOW_DISCOUNT_PERCENT"],
			"SHOW_DISCOUNT_PERCENT_NUMBER" => $arParams["SHOW_DISCOUNT_PERCENT_NUMBER"],
			"USE_REGION" => $arParams["USE_REGION"],
			"STORES" => $arParams["STORES"],
			"DEFAULT_COUNT" => $arParams["DEFAULT_COUNT"],
			"BASKET_URL" => $arParams["BASKET_URL"],
			"SHOW_GALLERY" => $arParams["SHOW_GALLERY"],
			"MAX_GALLERY_ITEMS" => $arParams["MAX_GALLERY_ITEMS"],
			"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
			"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
			"PARTIAL_PRODUCT_PROPERTIES" => $arParams["PARTIAL_PRODUCT_PROPERTIES"],
			"ADD_PROPERTIES_TO_BASKET" => $arParams["ADD_PROPERTIES_TO_BASKET"],
			"SHOW_ONE_CLICK_BUY" => $arParams["SHOW_ONE_CLICK_BUY"],
			"SHOW_DISCOUNT_TIME_EACH_SKU" => $arParams["SHOW_DISCOUNT_TIME_EACH_SKU"],
			"SHOW_ARTICLE_SKU" => $arParams["SHOW_ARTICLE_SKU"],
			"SHOW_POPUP_PRICE" => CMax::GetFrontParametrValue('SHOW_POPUP_PRICE'),
			"ADD_PICT_PROP" => $arParams["ADD_PICT_PROP"],
			"ADD_DETAIL_TO_SLIDER" => $arParams["DETAIL_ADD_DETAIL_TO_SLIDER"],
			"OFFER_ADD_PICT_PROP" => $arParams["OFFER_ADD_PICT_PROP"],
			"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
			"IBINHERIT_TEMPLATES" => $arSeoItem ? $arIBInheritTemplates : array(),
			"DISPLAY_COMPARE" => CMax::GetFrontParametrValue('CATALOG_COMPARE'),
			"DISPLAY_WISH_BUTTONS" => $arParams["DISPLAY_WISH_BUTTONS"],
		);?>

		<?$bContolAjax = (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest" && isset($_GET["control_ajax"]) && $_GET["control_ajax"] == "Y" );?>
		<?// section elements?>
		<div class="js_wrapper_items<?=($arTheme["LAZYLOAD_BLOCK_CATALOG"]["VALUE"] == "Y" ? ' with-load-block' : '')?>" data-params='<?=str_replace('\'', '"', CUtil::PhpToJSObject($arTransferParams, false))?>'>
			<div class="js-load-wrapper">
				<?if($bContolAjax):?>
					<?$APPLICATION->RestartBuffer();?>
				<?endif;?>

				<?@include_once('page_blocks/'.$arParams["SECTION_ELEMENTS_TYPE_VIEW"].'.php');?>

				<?if($bContolAjax):?>
					<?die();?>
				<?endif;?>
			</div>
		</div>

		<?CMax::checkBreadcrumbsChain($arParams, $arSection);?>
		<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.history.js');?>
	</div>
	<?if($bShowLeftBlock):?>
		<?CMax::ShowPageType('left_block');?>
	<?endif;?>
</div>
<?
$bTopHeaderOpacity = false;

if( isset($arTheme['HEADER_TYPE']['LIST'][ $arTheme['HEADER_TYPE']['VALUE'] ]['ADDITIONAL_OPTIONS'])  && isset($arTheme['HEADER_TYPE']['LIST'][ $arTheme['HEADER_TYPE']['VALUE'] ]['ADDITIONAL_OPTIONS']['TOP_HEADER_OPACITY']) ) {
	$bTopHeaderOpacity = $arTheme['HEADER_TYPE']['LIST'][ $arTheme['HEADER_TYPE']['VALUE'] ]['ADDITIONAL_OPTIONS']['TOP_HEADER_OPACITY']['VALUE'] == 'Y';
}

if ($bTopHeaderOpacity && $section[$arParams['SECTION_BG']]) {
	global $dopBodyClass;
	$dopBodyClass .= ' top_header_opacity';
}

CMax::setCatalogSectionDescription(
	array(
		'FILTER_NAME' => $arParams['FILTER_NAME'],
		'CACHE_TYPE' => $arParams['CACHE_TYPE'],
		'CACHE_TIME' => $arParams['CACHE_TIME'],
		'SECTION_ID' => $arSection['ID'],
		'SHOW_SECTION_DESC' => $arParams['SHOW_SECTION_DESC'],
		'SEO_ITEM' => $arSeoItem,
	)
);
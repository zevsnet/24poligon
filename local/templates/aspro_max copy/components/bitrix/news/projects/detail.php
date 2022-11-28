<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>

<?global $arTheme, $isHideLeftBlock, $isWidePage;?>
<?
if(isset($arParams["TYPE_LEFT_BLOCK_DETAIL"]) && $arParams["TYPE_LEFT_BLOCK_DETAIL"]!='FROM_MODULE'){
	$arTheme['LEFT_BLOCK']['VALUE'] = $arParams["TYPE_LEFT_BLOCK_DETAIL"];
}

if(isset($arParams["SIDE_LEFT_BLOCK_DETAIL"]) && $arParams["SIDE_LEFT_BLOCK_DETAIL"]!='FROM_MODULE'){
	$arTheme['SIDE_MENU']['VALUE'] = $arParams["SIDE_LEFT_BLOCK_DETAIL"];
}

if($arTheme['HIDE_SUBSCRIBE']['VALUE'] == 'Y'){
	$arParams["USE_SUBSCRIBE_IN_TOP"] = "N";
}
?>

<?

if(!$isHideLeftBlock && $APPLICATION->GetProperty("HIDE_LEFT_BLOCK_DETAIL") == "Y"){
	$APPLICATION->SetPageProperty("HIDE_LEFT_BLOCK", "Y");
	$APPLICATION->AddViewContent('container_inner_class', ' contents_page ');
	$APPLICATION->AddViewContent('wrapper_inner_class', ' wide_page ');
	if(!$isWidePage){
		$APPLICATION->AddViewContent('right_block_class', ' maxwidth-theme ');		
	}
}

?>

<?
// get element
$arItemFilter = CMax::GetCurrentElementFilter($arResult['VARIABLES'], $arParams);

global $APPLICATION, $arRegion, $bLongBanner;
$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/animation/animate.min.css');
//$bLongBanner = true;
//var_dump($bLongBanner);

if($arParams['CACHE_GROUPS'] == 'Y')
{
	$arItemFilter['CHECK_PERMISSIONS'] = 'Y';
	$arItemFilter['GROUPS'] = $GLOBALS["USER"]->GetGroups();
}

$arElement = CMaxCache::CIblockElement_GetList(array('CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'MULTI' => 'N')), $arItemFilter, false, false, array('ID', 'PREVIEW_TEXT', 'IBLOCK_SECTION_ID', 'PREVIEW_PICTURE', 'ACTIVE_FROM', 'ACTIVE_TO', 'DETAIL_PICTURE', 'DETAIL_PAGE_URL', 'LIST_PAGE_URL', 'PROPERTY_LINK_PROJECTS', 'PROPERTY_LINK_GOODS', 'PROPERTY_LINK_REVIEWS', 'PROPERTY_LINK_STAFF', 'PROPERTY_LINK_SERVICES', 'PROPERTY_FORM_QUESTION', 'PROPERTY_LINK_REGION', 'PROPERTY_LINK_GOODS_FILTER', 'PERIOD', 'SALE_NUMBER'));


if($arParams["SHOW_MAX_ELEMENT"] == "Y")
{
	$arSort=array($arParams["SORT_BY1"] => $arParams["SORT_ORDER1"], $arParams["SORT_BY2"] => $arParams["SORT_ORDER2"]);
	$arElementNext = array();

	$arAllElements = CMaxCache::CIblockElement_GetList(array($arParams["SORT_BY1"] => $arParams["SORT_ORDER1"], $arParams["SORT_BY2"] => $arParams["SORT_ORDER2"], 'CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'MULTI' => 'Y')), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "SECTION_ID" => $arElement["IBLOCK_SECTION_ID"]/*, ">ID" => $arElement["ID"]*/ ), false, false, array('ID', 'DETAIL_PAGE_URL', 'IBLOCK_ID', 'SORT'));
	if($arAllElements)
	{
		$url_page = $APPLICATION->GetCurPage();
		$key_item = 0;
		foreach($arAllElements as $key => $arItemElement)
		{
			if($arItemElement["DETAIL_PAGE_URL"] == $url_page)
			{
				$key_item = $key;
				break;
			}
		}
		if(strlen($key_item))
		{
			$arElementNext = $arAllElements[$key_item+1];
		}
		if($arElementNext)
		{
			if($arElementNext["DETAIL_PAGE_URL"] && is_array($arElementNext["DETAIL_PAGE_URL"])){
				$arElementNext["DETAIL_PAGE_URL"]=current($arElementNext["DETAIL_PAGE_URL"]);
			}
		}
	}
}
?>

<?if(!$arElement && $arParams['SET_STATUS_404'] !== 'Y'):?>
	<div class="alert alert-warning"><?=GetMessage("ELEMENT_NOTFOUND")?></div>
<?elseif(!$arElement && $arParams['SET_STATUS_404'] === 'Y'):?>
	<?CMax::goto404Page();?>
<?else:?>
	<?if(($arParams["LINKED_ELEMENT_TAB_SORT_FIELD"] == "REGION_PRICE" || $arParams["LINKED_ELEMENT_TAB_SORT_FIELD2"] == "REGION_PRICE")
		&& $arParams["SORT_REGION_PRICE"]) {
		$arPriceSort = [];
		global $arRegion;
		if ($arRegion) {
			if (!$arRegion["PROPERTY_SORT_REGION_PRICE_VALUE"] || $arRegion["PROPERTY_SORT_REGION_PRICE_VALUE"] == "component") {
				$price = CCatalogGroup::GetList(array(), array("NAME" => $arParams["SORT_REGION_PRICE"]), false, false, array("ID", "NAME"))->GetNext();
				$arPriceSort = array("CATALOG_PRICE_".$price["ID"]);
			} else {
				$arPriceSort = array("CATALOG_PRICE_".$arRegion["PROPERTY_SORT_REGION_PRICE_VALUE"]);
			}
		}
		if ($arPriceSort) {
			if ($arParams["LINKED_ELEMENT_TAB_SORT_FIELD"] == "REGION_PRICE") {
				$arParams["LINKED_ELEMENT_TAB_SORT_FIELD"] = $arPriceSort[0];
			}
			if ($arParams["LINKED_ELEMENT_TAB_SORT_FIELD2"] == "REGION_PRICE") {
				$arParams["LINKED_ELEMENT_TAB_SORT_FIELD2"] = $arPriceSort[0];
			}
		}
	}?>
	<?CMax::AddMeta(
		array(
			'og:description' => $arElement['PREVIEW_TEXT'],
			'og:image' => (($arElement['PREVIEW_PICTURE'] || $arElement['DETAIL_PICTURE']) ? CFile::GetPath(($arElement['PREVIEW_PICTURE'] ? $arElement['PREVIEW_PICTURE'] : $arElement['DETAIL_PICTURE'])) : false),
		)
	);?>
	<?
	/* hide compare link from module options */
	if(CMax::GetFrontParametrValue('CATALOG_COMPARE') == 'N')
		$arParams["DISPLAY_COMPARE"] = 'N';
	/**/
	?>
	<?
	$bActiveDate = (strlen($arElement['PERIOD']['VALUE'] )&& in_array('PERIOD', $arParams['DETAIL_PROPERTY_CODE'])) || ($arElement['ACTIVE_FROM'] && in_array('DATE_ACTIVE_FROM', $arParams['DETAIL_FIELD_CODE']));
	$bDiscountCounter = ($arElement['ACTIVE_TO'] && in_array('ACTIVE_TO', $arParams['DETAIL_FIELD_CODE']));
	$bShowDopBlock = (($arElement['SALE_NUMBER']['VALUE'] && in_array('SALE_NUMBER', $arParams['DETAIL_PROPERTY_CODE'])) || $bDiscountCounter);
	$bShowPeriodLine = $bActiveDate || $bShowDopBlock;
	//var_dump($bShowPeriodLine);
	?>

	<div class="detail <?=($templateName = $component->{'__template'}->{'__name'})?> fixed_wrapper ">
		<?if($arElement):?>

			<?if(!$bShowPeriodLine):?>
				<?$this->SetViewTarget('product_share');?>
					<?if($arParams["USE_SHARE"] == "Y"):?>
						<?\Aspro\Functions\CAsproMax::showShareBlock('top')?>
					<?endif;?>

					<?if($arParams['USE_RSS'] !== 'N'):?>
						<div class="colored_theme_hover_bg-block">
							<?=CMax::ShowRSSIcon($arResult['FOLDER'].$arResult['URL_TEMPLATES']['rss']);?>
						</div>
					<?endif;?>

					<?if($arParams["USE_SUBSCRIBE_IN_TOP"] == "Y"):?>
						<div><div class="colored_theme_hover_bg-block dark_link animate-load" data-event="jqm" data-param-type="subscribe" data-name="subscribe" title="<?=GetMessage('SUBSCRIBE_TEXT')?>">
							<?=CMax::showIconSvg("subscribe", SITE_TEMPLATE_PATH."/images/svg/subscribe_insidepages.svg", "", "colored_theme_hover_bg-el-svg", true, false);?>
						</div></div>
					<?endif;?>
				<?$this->EndViewTarget();?>
			<?else:?>
				<?$this->SetViewTarget('share_in_contents');?>

					<?if($arParams["USE_SHARE"] == "Y"):?>
						<?\Aspro\Functions\CAsproMax::showShareBlock('top')?>
					<?endif;?>
					
					<?if($arParams['USE_RSS'] !== 'N'):?>
						<div class="colored_theme_hover_bg-block">
							<?=CMax::ShowRSSIcon($arResult['FOLDER'].$arResult['URL_TEMPLATES']['rss']);?>
						</div>
					<?endif;?>

					<?if($arParams["USE_SUBSCRIBE_IN_TOP"] == "Y"):?>
						<div><div class="colored_theme_hover_bg-block dark_link animate-load" data-event="jqm" data-param-type="subscribe" data-name="subscribe" title="<?=GetMessage('SUBSCRIBE_TEXT')?>">
							<?=CMax::showIconSvg("subscribe", SITE_TEMPLATE_PATH."/images/svg/subscribe_insidepages.svg", "", "colored_theme_hover_bg-el-svg", true, false);?>
						</div></div>
					<?endif;?>
				<?$this->EndViewTarget();?>
			<?endif;?>

		<?endif;?>


		<?/*goods block filter*/?>
		<?//$list_view = ($arParams['LIST_VIEW'] ? $arParams['LIST_VIEW'] : 'slider');?>
		<?// goods links?>
		<?if(in_array('LINK_GOODS', $arParams['DETAIL_PROPERTY_CODE'])):?>
			<?
			$catalogID = \Bitrix\Main\Config\Option::get('aspro.max', 'CATALOG_IBLOCK_ID', CMaxCache::$arIBlocks[SITE_ID]["aspro_max_catalog"]["aspro_max_catalog"][0]);
		    $dbProperty = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $catalogID, "CODE" => "LINK_PROJECTS"));
		    if($dbProperty->SelectedRowsCount() && $arElement['ID'])
		    {
			    $arTmpElement = CMaxCache::CIblockElement_GetList(array('CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($catalogID), 'MULTI' => 'Y')), array('IBLOCK_ID' => $catalogID, 'PROPERTY_LINK_PROJECTS' => $arElement['ID']), false, false, array('ID'));
			    if($arTmpElement)
			    {
				    foreach($arTmpElement as $key => $arItem)
					    $arFilterElements[] = $arItem['ID'];

				    if($arElement['PROPERTY_LINK_GOODS_VALUE'])
					    $arElement['PROPERTY_LINK_GOODS_VALUE'] = array_merge((array)$arElement['PROPERTY_LINK_GOODS_VALUE'], $arFilterElements);
				    else
					    $arElement['PROPERTY_LINK_GOODS_VALUE'] = $arFilterElements;
			    }
		    }
		    $arTmpGoods = json_decode($arElement["~PROPERTY_LINK_GOODS_FILTER_VALUE"], true);
		    ?>
		    <?if($arElement['PROPERTY_LINK_GOODS_VALUE'] || ($arElement['PROPERTY_LINK_GOODS_FILTER_VALUE'] && $arTmpGoods['CHILDREN'])):?>
				<?
				if(!isset($arParams["PRICE_CODE"]))
				    $arParams["PRICE_CODE"] = array(0 => "BASE", 1 => "OPT");
			    if(!isset($arParams["STORES"]))
				    $arParams["STORES"] = array(0 => "1", 1 => "2");

			    if(!($arElement['PROPERTY_LINK_GOODS_FILTER_VALUE'] && $arTmpGoods['CHILDREN']))
				    $GLOBALS['arrProductsFilter'] = array('ID' => $arElement['PROPERTY_LINK_GOODS_VALUE']);

			    global $arRegion;
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
							    if(count($arStoresFilter) > 1){
								    $arTmpFilter[] = $arStoresFilter;
							    }
							    else{
								    $arTmpFilter = array_merge($arTmpFilter, $arStoresFilter);
							    }

							    $GLOBALS['arrProductsFilter'][] = array(
								    'LOGIC' => 'OR',
								    array('TYPE' => array('2', '3')),
								    $arTmpFilter,
							    );
						    }
					    }
				    }
			    }
			    ?>
			<?endif;?>
		<?endif;?>
		<?/*end goods filter*/?>

		<?//element?>
		<?$sViewElementTemplate = ($arParams["ELEMENT_TYPE_VIEW"] == "FROM_MODULE" ? $arTheme["NEWS_PAGE_DETAIL"]["VALUE"] : $arParams["ELEMENT_TYPE_VIEW"]);?>
		<?@include_once('page_blocks/'.$sViewElementTemplate.'.php');?>

	</div>
	<?/*
	if(is_array($arElement['IBLOCK_SECTION_ID']) && count($arElement['IBLOCK_SECTION_ID']) > 1){
		CMax::CheckAdditionalChainInMultiLevel($arResult, $arParams, $arElement);
	}*/
	?>
	<?//global $isHideLeftBlock;?>


	<div class="bottom-links-block">
		<a class="muted back-url url-block" href="<?=$arResult['FOLDER'].$arResult['URL_TEMPLATES']['news']?>">
			<?=CMax::showIconSvg("return_to_the_list", SITE_TEMPLATE_PATH."/images/svg/return_to_the_list.svg", "");?>
		<span class="font_upper back-url-text"><?=($arParams["T_PREV_LINK"] ? $arParams["T_PREV_LINK"] : GetMessage('BACK_LINK'));?></span></a>

		<?if($arParams["SHOW_MAX_ELEMENT"] == "Y" && $arElementNext):?>
			<a class="muted next-url url-block" href="<?=$arElementNext['DETAIL_PAGE_URL']?>">
			<span class="font_upper next-url-text"><?=($arParams["T_MAX_LINK"] ? $arParams["T_MAX_LINK"] : GetMessage('MAX_LINK'));?></span>
			<?=CMax::showIconSvg("next_element", SITE_TEMPLATE_PATH."/images/svg/return_to_the_list.svg", "");?>
			</a>
		<?endif;?>

		<?if($arParams["USE_SHARE"] == "Y" && $arElement):?>
			<?\Aspro\Functions\CAsproMax::showShareBlock('bottom')?>
		<?endif;?>
	</div>

<?endif;?>
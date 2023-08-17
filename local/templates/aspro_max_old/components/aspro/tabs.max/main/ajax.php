<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */

$this->setFrameMode(true);

$class_block="s_".$this->randString();

\Aspro\Functions\CAsproMax::replacePropsParams($arParams);
$arParams["OFFERS_PROPERTY_CODE"] = $arParams['DETAIL_OFFERS_PROPERTY_CODE'];

$arTab=array();
$arParams["DISPLAY_BOTTOM_PAGER"] = "Y";
$arParams['SET_TITLE'] = 'N';
$arTmp = reset($arResult["TABS"]);
$arParams["FILTER_HIT_PROP"] = $arTmp["CODE"];
$arParamsTmp = urlencode(serialize($arParams));

$isAjax = isset($_REQUEST['ajax']) && strtolower($_REQUEST['ajax']) === 'y';

if($arResult["SHOW_SLIDER_PROP"]):?>
	<?
	$arTransferParams = array(
		"SHOW_ABSENT" => $arParams["SHOW_ABSENT"],
		"HIDE_NOT_AVAILABLE_OFFERS" => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"OFFER_TREE_PROPS" => $arParams["OFFER_TREE_PROPS"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
		"CURRENCY_ID" => $arParams["CURRENCY_ID"],
		"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
		"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
		"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
		"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
		"LIST_OFFERS_LIMIT" => $arParams["OFFERS_LIMIT"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"LIST_OFFERS_PROPERTY_CODE" => $arParams["OFFERS_PROPERTY_CODE"],
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
		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
		"PARTIAL_PRODUCT_PROPERTIES" => $arParams["PARTIAL_PRODUCT_PROPERTIES"],
		"ADD_PROPERTIES_TO_BASKET" => ($arParams["ADD_PROPERTIES_TO_BASKET"] != "N" ? "Y" : "N"),
		"SHOW_DISCOUNT_TIME_EACH_SKU" => $arParams["SHOW_DISCOUNT_TIME_EACH_SKU"],
		"SHOW_ARTICLE_SKU" => $arParams["SHOW_ARTICLE_SKU"],
		"OFFER_ADD_PICT_PROP" => $arParams["OFFER_ADD_PICT_PROP"],
		"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
		"SHOW_ONE_CLICK_BUY" => $arParams["SHOW_ONE_CLICK_BUY"],
		"DISPLAY_COMPARE" => $arParams["DISPLAY_COMPARE"],
		"DISPLAY_WISH_BUTTONS" => $arParams["DISPLAY_WISH_BUTTONS"],
		"MAX_GALLERY_ITEMS" => $arParams["MAX_GALLERY_ITEMS"],
		"SHOW_GALLERY" => $arParams["SHOW_GALLERY"],
		"SHOW_PROPS" => $arParams["SHOW_PROPS"],
		"SHOW_POPUP_PRICE" => CMax::GetFrontParametrValue('SHOW_POPUP_PRICE'),
		"ADD_PICT_PROP" => $arParams["ADD_PICT_PROP"],
		"ADD_DETAIL_TO_SLIDER" => $arParams["ADD_DETAIL_TO_SLIDER"],
		"DISPLAY_COMPARE" => CMax::GetFrontParametrValue('CATALOG_COMPARE'),
	);
	?>
	<div class="js_wrapper_items" data-params='<?=str_replace('\'', '"', CUtil::PhpToJSObject($arTransferParams, false))?>'>
		<div class="content_wrapper_block <?=$templateName;?>">
			<div class="maxwidth-theme">
				<div class="tab_slider_wrapp specials <?=$class_block;?> best_block clearfix" itemscope itemtype="http://schema.org/WebPage">
					<span class='request-data' data-value='<?=$arParamsTmp?>'></span>
					<div class="top_block">
						<?if($arParams['TITLE_BLOCK']):?>
							<h3><?=$arParams['TITLE_BLOCK'];?></h3>
						<?endif;?>
						<div class="right_block_wrapper">
							<div class="tabs_wrapper <?=$arParams['TITLE_BLOCK_ALL'] && $arParams['ALL_URL'] ? 'with_link' : ''?>">
								<ul class="tabs ajax">
									<?$i=1;
									foreach($arResult["TABS"] as $code => $arTab):?>
										<li data-code="<?=$code?>" class="font_xs <?=($i==1 ? "cur clicked" : "")?>"><span class="muted777"><?=$arTab["TITLE"];?></span></li>
										<?$i++;?>
									<?endforeach;?>
								</ul>
							</div>
							<?if($arParams['TITLE_BLOCK_ALL'] && $arParams['ALL_URL']):?>
								<a href="<?=$arParams['ALL_URL'];?>" class="font_upper muted"><?=$arParams['TITLE_BLOCK_ALL'];?></a>
							<?endif;?>
						</div>
					</div>
					<ul class="tabs_content">
						<?$j=1;?>
						<?foreach($arResult["TABS"] as $code => $arTab):?>
							<li class="tab <?=$code?>_wrapp <?=($j == 1 ? "cur opacity1" : "");?>" data-code="<?=$code?>" data-filter="<?=($arTab["FILTER"] ? urlencode(serialize($arTab["FILTER"])) : '');?>">
								<div class="tabs_slider <?=$code?>_slides wr">
									<?if($isAjax)
										$APPLICATION->RestartBuffer();?>
									<?if($j++ == 1)
									{
										if($arTab["FILTER"])
											$GLOBALS[$arParams["FILTER_NAME"]] = $arTab["FILTER"];

										include(str_replace("//", "/", $_SERVER["DOCUMENT_ROOT"].SITE_DIR."include/mainpage/comp_catalog_ajax.php"));
									}?>
									<?if($isAjax)
										CMax::checkRestartBuffer(true, 'catalog_tab');?>
								</div>
							</li>
						<?endforeach;?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<script>try{window.tabsInitOnReady();}catch{}</script>
<?endif;?>
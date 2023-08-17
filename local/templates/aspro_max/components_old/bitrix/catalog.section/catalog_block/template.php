<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?use \Bitrix\Main\Localization\Loc,
	  \Bitrix\Main\Web\Json;?>
<?if($arResult["ITEMS"]):?>
	<?
	$currencyList = '';
	if (!empty($arResult['CURRENCIES'])){
		$templateLibrary[] = 'currency';
		$currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
	}
	$templateData = array(
		'TEMPLATE_LIBRARY' => $templateLibrary,
		'CURRENCIES' => $currencyList
	);
	unset($currencyList, $templateLibrary);

	// params for catalog elements compact view
	$arParamsCE_CMP = $arParams;
	$arParamsCE_CMP['TYPE_SKU'] = 'N';

	$bHasBottomPager = $arParams["DISPLAY_BOTTOM_PAGER"] == "Y" && $arResult["NAV_STRING"];
	?>
	<?if($arParams["AJAX_REQUEST"] != "Y"):?>
		<?$bSlide = (isset($arParams['SLIDE_ITEMS']) && $arParams['SLIDE_ITEMS']);?>
		<?$bGiftblock = (isset($arParams['GIFT_ITEMS']) && $arParams['GIFT_ITEMS']);?>
	<div class="top_wrapper items_wrapper <?=$templateName;?>_template <?=$arParams['IS_COMPACT_SLIDER'] ? 'compact-catalog-slider' : ''?>">
		<div class="fast_view_params" data-params="<?=urlencode(serialize($arTransferParams));?>"></div>
		<div class="catalog_block items row <?=$arParams['IS_COMPACT_SLIDER'] ? 'swipeignore mobile-overflow' : ''?> margin0 <?=$bHasBottomPager ? 'has-bottom-nav' : ''?> js_append ajax_load block flexbox<?=($bSlide ? ' owl-carousel owl-theme owl-bg-nav visible-nav short-nav hidden-dots swipeignore ' : '');?>"<?if($bSlide):?>data-plugin-options='{"nav": true, "autoplay" : false,  "autoplayTimeout" : "1000", "smartSpeed":1000, <?=(count($arResult["ITEMS"]) > 4 ? "\"loop\": true," : "")?> "responsiveClass": true, "responsive":{"0":{"items": 2},"600":{"items": 2},"768":{"items": 3},"1200":{"items": 4}}}'<?endif;?>>
	<?endif;?>
		<?
		if(is_array($arParams['OFFERS_CART_PROPERTIES'])){
			$arOfferProps = implode(';', $arParams['OFFERS_CART_PROPERTIES']);
		} else {
			$arOfferProps = '';
		}
		?>
		<?foreach($arResult["ITEMS"] as $arItem){?>
			<?$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));

			if(!empty($arItem['PRODUCT_PROPERTIES_FILL']))
			{
				foreach ($arItem['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo)
				{
					if(isset($arItem['PRODUCT_PROPERTIES'][$propID]))
						unset($arItem['PRODUCT_PROPERTIES'][$propID]);
				}
			}

			$emptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);
			$arItem["EMPTY_PROPS_JS"]=(!$emptyProductProperties ? "N" : "Y");

			$item_id = $arItem["ID"];
			$strMeasure = '';
			$arCurrentSKU = array();

			$currentSKUID = $currentSKUIBlock = '';

			$totalCount = CMax::GetTotalCount($arItem, $arParams);
			$arQuantityData = CMax::GetQuantityArray($totalCount, array('ID' => $item_id), 'N', (($arItem["OFFERS"] || $arItem['CATALOG_TYPE'] == CCatalogProduct::TYPE_SET || $bSlide || !$arResult['STORES_COUNT']) ? false : true));

			if(isset($arParams['ID_FOR_TABS']) && $arParams['ID_FOR_TABS'] == 'Y') {
				$arItem["strMainID"] = $this->GetEditAreaId($arItem['ID'])."_".$arParams["FILTER_HIT_PROP"];
			} else {
				$arItem["strMainID"] = $this->GetEditAreaId($arItem['ID']);
			}

			$arItemIDs=CMax::GetItemsIDs($arItem);

			if($arParams["SHOW_MEASURE"] == "Y" && $arItem["CATALOG_MEASURE"]){
				if(isset($arItem["ITEM_MEASURE"]) && (is_array($arItem["ITEM_MEASURE"]) && $arItem["ITEM_MEASURE"]["TITLE"]))
				{
					$strMeasure = $arItem["ITEM_MEASURE"]["TITLE"];
				}
				else
				{
					$arMeasure = CCatalogMeasure::getList(array(), array("ID" => $arItem["CATALOG_MEASURE"]), false, false, array())->GetNext();
					$strMeasure = $arMeasure["SYMBOL_RUS"];
				}
			}
			$bBigBlock = ($arItem['PROPERTIES']['BIG_BLOCK']['VALUE'] == 'Y' && $arParams['SHOW_BIG_BLOCK'] != 'N');

			$bUseSkuProps = ($arItem["OFFERS"] && !empty($arItem['OFFERS_PROP']) && !$bBigBlock && $arParams['TYPE_SKU'] != 'N');			

			$elementName = ((isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arItem['NAME']);

			if($bUseSkuProps)
			{
				if(!$arItem["OFFERS"])
				{
					$arAddToBasketData = CMax::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false,  $arItemIDs["ALL_ITEM_IDS"], 'btn-exlg', $arParams);
				}
				elseif($arItem["OFFERS"])
				{

					$currentSKUIBlock = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["IBLOCK_ID"];
					$currentSKUID = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["ID"];

					$strMeasure = $arItem["MIN_PRICE"]["CATALOG_MEASURE_NAME"];
					$totalCount = CMax::GetTotalCount($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]], $arParams);
					$arQuantityData = CMax::GetQuantityArray($totalCount, array('ID' => $currentSKUID), 'N', (($arItem['CATALOG_TYPE'] == CCatalogProduct::TYPE_SET || $bSlide || !$arResult['STORES_COUNT']) ? false : true), 'ce_cmp_hidden');

					if($arItem["OFFERS"])
					{
						$totalCountCMP = CMax::GetTotalCount($arItem, $arParamsCE_CMP);
						$arQuantityDataCMP = CMax::GetQuantityArray($totalCountCMP, array('ID' => $item_id), 'N', false, 'ce_cmp_visible');
					}


					$arItem["DETAIL_PAGE_URL"] = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DETAIL_PAGE_URL"];
					if($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PREVIEW_PICTURE"])
						$arItem["PREVIEW_PICTURE"] = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PREVIEW_PICTURE"];
					if($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PREVIEW_PICTURE"])
						$arItem["DETAIL_PICTURE"] = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DETAIL_PICTURE"];

					if($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['IPROPERTY_VALUES']){
						$arItem['SELECTED_SKU_IPROPERTY_VALUES'] = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['IPROPERTY_VALUES'];
					}

					if($arParams["SET_SKU_TITLE"] == "Y")
						$arItem["NAME"] = $elementName = ((isset($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['NAME']);
					$item_id = $currentSKUID;

					// ARTICLE
					if($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DISPLAY_PROPERTIES"]["ARTICLE"]["VALUE"])
					{
						$arItem["ARTICLE"]["NAME"] = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DISPLAY_PROPERTIES"]["ARTICLE"]["NAME"];
						$arItem["ARTICLE"]["VALUE"] = (is_array($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DISPLAY_PROPERTIES"]["ARTICLE"]["VALUE"]) ? reset($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DISPLAY_PROPERTIES"]["ARTICLE"]["VALUE"]) : $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DISPLAY_PROPERTIES"]["ARTICLE"]["VALUE"]);
						unset($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DISPLAY_PROPERTIES"]["ARTICLE"]);
					}

					$arCurrentSKU = $arItem["JS_OFFERS"][$arItem["OFFERS_SELECTED"]];
					$strMeasure = $arCurrentSKU["MEASURE"];

					$arAddToBasketData = CMax::GetAddToBasketArray($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]], $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'btn-exlg', $arParams);
				}
			}
			else
			{
				$arItem['OFFERS_PROP'] = '';
				if($arItem["OFFERS"])
					$arItem["OFFERS_MORE"] = "Y";

				$arAddToBasketData = CMax::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, array(), 'btn-exlg', $arParams);
			}

			switch ($arParams["LINE_ELEMENT_COUNT"])
			{
				case '4':
					$col = ($bBigBlock ? 6 : 3);
					break;
				case '3':
					$col = 4;
					break;
				default:
					$col = ($bBigBlock ? 40 : 20);
					break;
			}
			if ($arParams["SET_LINE_ELEMENT_COUNT"]) {
				if ($arParams["LINE_ELEMENT_COUNT"] == 5) {
					$col = '5_2';
				}
			}
			?>
			<?if($bBigBlock):?>
				<div class="item hidden">
					<div class="catalog_item_wrapp catalog_item">
						<div class="item_info">
							<div class="item-title"></div>
							<div class="sa_block"></div>
						</div>
					</div>
				</div>
			<?endif;?>

			<?$bFonImg = false;?>
			<?if($bBigBlock):?>
				<?if($arItem["PROPERTIES"]["BIG_BLOCK_PICTURE"]["VALUE"])
				{
					$img = \CFile::ResizeImageGet($arItem["PROPERTIES"]["BIG_BLOCK_PICTURE"]["VALUE"], array( "width" => 900, "height" => 900 ), BX_RESIZE_IMAGE_PROPORTIONAL,true );
					$imageSrc = $img["src"];
					$bFonImg = true;
				}
				elseif(!empty($arItem["DETAIL_PICTURE"]))
				{
					$img = \CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], array( "width" => 600, "height" => 600 ), BX_RESIZE_IMAGE_PROPORTIONAL,true );
					$imageSrc = $img["src"];
				}
				elseif(!empty($arItem["PREVIEW_PICTURE"]) )
				{
					$imageSrc = $arItem["PREVIEW_PICTURE"]["SRC"];
				}
				else
				{
					$imageSrc = SITE_TEMPLATE_PATH.'/images/svg/noimage_product.svg';
				}?>
			<?endif;?>

			<?ob_start();?>
				<?if($arParams["SHOW_RATING"] == "Y"):?>
					<div class="rating">
						<?$frame = $this->createFrame('dv_'.$arItem["ID"])->begin('');?>
							<?
							global $arTheme;
							if( $arParams['REVIEWS_VIEW'] ):?>
								<div class="blog-info__rating--top-info">
									<div class="votes_block nstar with-text">
										<div class="ratings">
											<?$message = $arItem['PROPERTIES']['EXTENDED_REVIEWS_COUNT']['VALUE'] ? GetMessage('VOTES_RESULT', array('#VALUE#' => $arItem['PROPERTIES']['EXTENDED_REVIEWS_RAITING']['VALUE'])) : GetMessage('VOTES_RESULT_NONE')?>
											<div class="inner_rating" title="<?=$message?>">
												<?for($i=1;$i<=5;$i++):?>
													<div class="item-rating <?=$i<=$arItem['PROPERTIES']['EXTENDED_REVIEWS_RAITING']['VALUE'] ? 'filed' : ''?>"><?=CMax::showIconSvg("star", SITE_TEMPLATE_PATH."/images/svg/catalog/star_small.svg");?></div>
												<?endfor;?>
											</div>
										</div>
									</div>
									<?if($arItem['PROPERTIES']['EXTENDED_REVIEWS_COUNT']['VALUE']):?>
										<span class="font_sxs"><?=$arItem['PROPERTIES']['EXTENDED_REVIEWS_COUNT']['VALUE']?></span>
									<?endif;?>
								</div>
							<?else:?>
								<?$APPLICATION->IncludeComponent(
								"bitrix:iblock.vote",
								"element_rating_front",
								Array(
									"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
									"IBLOCK_ID" => $arItem["IBLOCK_ID"],
									"ELEMENT_ID" =>$arItem["ID"],
									"MAX_VOTE" => 5,
									"VOTE_NAMES" => array(),
									"CACHE_TYPE" => $arParams["CACHE_TYPE"],
									"CACHE_TIME" => $arParams["CACHE_TIME"],
									"DISPLAY_AS_RATING" => 'vote_avg'
								),
								$component, array("HIDE_ICONS" =>"Y")
								);?>
							<?endif;?>
						<?$frame->end();?>
					</div>
				<?endif;?>
			<?$itemRating = ob_get_clean();?>


			<?ob_start();?>
				<div class="sa_block" data-fields='<?=Json::encode($arParams["FIELDS"])?>' data-stores='<?=Json::encode($arParams["STORES"])?>' data-user-fields='<?=Json::encode($arParams["USER_FIELDS"])?>'>
					<?=$arQuantityData["HTML"];?>
					<?if(isset($arQuantityDataCMP) && $arQuantityDataCMP && $arItem['OFFERS']):?>
						<?=$arQuantityDataCMP["HTML"];?>
					<?endif;?>
					<?$bHasArticle = isset($arItem['ARTICLE']) && $arItem['ARTICLE']['VALUE'];?>
					<div class="article_block" <?if($bHasArticle):?>data-name="<?=Loc::getMessage('T_ARTICLE_COMPACT');?>" data-value="<?=$arItem['ARTICLE']['VALUE'];?>"<?endif;?>><?if($bHasArticle){?><div class="muted font_sxs"><?=Loc::getMessage('T_ARTICLE_COMPACT');?>: <?=$arItem['ARTICLE']['VALUE'];?></div><?}?></div>
				</div>
			<?$itemSaBlock = ob_get_clean();?>

			<?ob_start();?>
				<div class="item-title">
					<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="<?=$bBigBlock && $bFonImg ? '' : 'dark_link'?> option-font-bold font_sm"><span><?=$elementName;?></span></a>
				</div>
			<?$itemTitle = ob_get_clean();?>

			<?ob_start();?>
				<div class="cost prices clearfix">
					<?if($arParams["TYPE_VIEW_BASKET_BTN"] == "TYPE_2" && !$bBigBlock):?>
						<div class="icons-basket-wrapper offer_buy_block ce_cmp_hidden">
							<div class="button_block">
								<!--noindex-->
									<?=$arAddToBasketData["HTML"]?>
								<!--/noindex-->
							</div>
						</div>
					<?endif;?>
					<?if($arItem["OFFERS"]):?>
						<?if($arCurrentSKU):?>
							<div class="ce_cmp_hidden">
						<?endif;?>
							<?=\Aspro\Functions\CAsproMaxItem::showItemPricesDefault($arParams);?>
							<div class="js_price_wrapper">
								<?if($arCurrentSKU):?>
									<?$item_id = $arCurrentSKU["ID"];
									$arCurrentSKU['PRICE_MATRIX'] = $arCurrentSKU['PRICE_MATRIX_RAW'];
									$arCurrentSKU['CATALOG_MEASURE_NAME'] = $arCurrentSKU['MEASURE'];
									if(isset($arCurrentSKU['PRICE_MATRIX']) && $arCurrentSKU['PRICE_MATRIX']): // USE_PRICE_COUNT?>
										<?if($arCurrentSKU['ITEM_PRICE_MODE'] == 'Q' && count($arCurrentSKU['PRICE_MATRIX']['ROWS']) > 1):?>
											<?=CMax::showPriceRangeTop($arCurrentSKU, $arParams, Loc::getMessage("CATALOG_ECONOMY"));?>
										<?endif;?>
										<?=CMax::showPriceMatrix($arCurrentSKU, $arParams, $strMeasure, $arAddToBasketData);?>
									<?else:?>
										<?\Aspro\Functions\CAsproMaxItem::showItemPrices($arParams, $arCurrentSKU["PRICES"], $strMeasure, $min_price_id, ($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] == "Y" ? "N" : "Y"));?>
									<?endif;?>
								<?else:?>
										<?\Aspro\Functions\CAsproMaxSku::showItemPrices($arParams, $arItem, $item_id, $min_price_id, array(), ($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] == "Y" ? "N" : "Y"));?>
								<?endif;?>
							</div>
						<?if($arCurrentSKU):?>
						</div>
							<div class="ce_cmp_visible">
								<?\Aspro\Functions\CAsproMaxSku::showItemPrices($arParamsCE_CMP, $arItem, $item_id, $min_price_id, $arItemIDs, ($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] == "Y" ? "N" : "Y"));?>
							</div>
						<?endif;?>
					<?else:?>
						<?if($bGiftblock):?>
							<?foreach($arItem["PRICES"] as $priceCode => $arTmpPrice)
							{
								$arItem["PRICES"][$priceCode]["DISCOUNT_VALUE"] = $arItem["PRICES"][$priceCode]["DISCOUNT_DIFF"];
								$arItem["PRICES"][$priceCode]["PRINT_DISCOUNT_VALUE"] = $arItem["PRICES"][$priceCode]["PRINT_DISCOUNT_DIFF"];
							}?>
							<?\Aspro\Functions\CAsproMaxItem::showItemPrices($arParams, $arItem["PRICES"], $strMeasure, $min_price_id, ($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] == "Y" ? "N" : "Y"));?>
						<?else:?>
							<?if(isset($arItem['PRICE_MATRIX']) && $arItem['PRICE_MATRIX']): // USE_PRICE_COUNT?>
								<?if(\CMax::GetFrontParametrValue('SHOW_POPUP_PRICE') == 'Y' || $arItem['ITEM_PRICE_MODE'] == 'Q' || (\CMax::GetFrontParametrValue('SHOW_POPUP_PRICE') != 'Y' && $arItem['ITEM_PRICE_MODE'] != 'Q' && count($arItem['PRICE_MATRIX']['COLS']) <= 1)):?>
									<?=CMax::showPriceRangeTop($arItem, $arParams, Loc::getMessage("CATALOG_ECONOMY"));?>
								<?endif;?>
								<?if(count($arItem['PRICE_MATRIX']['ROWS']) > 1 || count($arItem['PRICE_MATRIX']['COLS']) > 1):?>
									<?=CMax::showPriceMatrix($arItem, $arParams, $strMeasure, $arAddToBasketData);?>
								<?endif;?>
							<?elseif($arItem["PRICES"]):?>
								<?\Aspro\Functions\CAsproMaxItem::showItemPrices($arParams, $arItem["PRICES"], $strMeasure, $min_price_id, ($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] == "Y" ? "N" : "Y"));?>
							<?endif;?>
						<?endif;?>
					<?endif;?>
				</div>
			<?$itemPrice = ob_get_clean();?>

			<?ob_start();?>
				<div class="footer_button <?=($arItem["OFFERS"] && $arItem['OFFERS_PROP'] ? 'has_offer_prop' : '');?> inner_content js_offers__<?=$arItem['ID'];?>_<?=$arParams["FILTER_HIT_PROP"]?><?=($arParams["TYPE_VIEW_BASKET_BTN"] == "TYPE_2" ? ' n-btn' : '')?>">
					<?if($arParams["TYPE_VIEW_BASKET_BTN"] != "TYPE_2" || $bBigBlock):?>
						<?if($arParams["TYPE_VIEW_BASKET_BTN"] == "TYPE_3"):?>
							<div class="basket-icons-wrapper clearfix offer_buy_block<?=(($arAddToBasketData["ACTION"] == "NOTHING") ? ' n-btn' : '');?> ce_cmp_hidden">
								<?\Aspro\Functions\CAsproMaxItem::showDelayCompareBtn($arParams, $arItem, $arAddToBasketData, $totalCount, $bUseSkuProps, 'block static', false, ($arParams['SHOW_ONE_CLICK_BUY'] == 'Y'), '', $currentSKUID, $currentSKUIBlock);?>
								<div class="basket-icons-wrapper__btn button_block">
									<!--noindex-->
										<?=$arAddToBasketData["HTML"]?>
									<!--/noindex-->
								</div>
							</div>
							<div class="counter_wrapp clearfix offer_buy_block<?=(($arAddToBasketData["ACTION"] == "NOTHING") ? ' n-btn' : '');?> ce_cmp_visible">
								<div class="button_block">
									<?if($totalCountCMP):?>
										<?$arItem["OFFERS_MORE"] = "Y";?>
										<?$arAddToBasketData = CMax::GetAddToBasketArray($arItem, $totalCountCMP, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'btn-exlg', $arParams);?>
									<?endif;?>
									<!--noindex-->
										<?=$arAddToBasketData["HTML"]?>
									<!--/noindex-->
								</div>
							</div>
						<?else:?>
							<?if(!$arItem["OFFERS"]):?>
								<div class="counter_wrapp <?=($arItem["OFFERS"] && $arParams["TYPE_SKU"] == "TYPE_1" ? 'woffers' : '')?> clearfix rounded3">
									<?=\Aspro\Functions\CAsproMax::showItemCounter($arAddToBasketData, $arItem["ID"], $arItemIDs, $arParams, 'big');?>
									<div id="<?=$arItemIDs["ALL_ITEM_IDS"]['BASKET_ACTIONS']; ?>" class="button_block <?=($arAddToBasketData["ACTION"] == "ORDER"  || !$arAddToBasketData["CAN_BUY"] || !$arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_LIST"] || $arAddToBasketData["ACTION"] == "SUBSCRIBE" || $arAddToBasketData["ACTION"] == "MORE" ? "wide" : "");?>">
										<!--noindex-->
											<?=$arAddToBasketData["HTML"]?>
										<!--/noindex-->
									</div>
								</div>
								<?
								if(isset($arItem['PRICE_MATRIX']) && $arItem['PRICE_MATRIX']): // USE_PRICE_COUNT?>
									<?if($arItem['ITEM_PRICE_MODE'] == 'Q' && count($arItem['PRICE_MATRIX']['ROWS']) > 1):?>
										<?$arOnlyItemJSParams = array(
											"ITEM_PRICES" => $arItem["ITEM_PRICES"],
											"ITEM_PRICE_MODE" => $arItem["ITEM_PRICE_MODE"],
											"ITEM_QUANTITY_RANGES" => $arItem["ITEM_QUANTITY_RANGES"],
											"MIN_QUANTITY_BUY" => $arAddToBasketData["MIN_QUANTITY_BUY"],
											"SHOW_DISCOUNT_PERCENT_NUMBER" => $arParams["SHOW_DISCOUNT_PERCENT_NUMBER"],
											"ID" => $arItemIDs["strMainID"],
										)?>
										<script type="text/javascript">
											var <? echo $arItemIDs["strObName"]; ?>el = new JCCatalogSectionOnlyElement(<? echo CUtil::PhpToJSObject($arOnlyItemJSParams, false, true); ?>);
										</script>
									<?endif;?>
								<?endif;?>
							<?elseif($arItem["OFFERS"]):?>
								<?if(empty($arItem['OFFERS_PROP'])){?>
									<div class="offer_buy_block buys_wrapp woffers">
										<?$arItem["OFFERS_MORE"] = "Y";
										$arAddToBasketData = CMax::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'btn-exlg', $arParams);?>
										<!--noindex-->
											<?=$arAddToBasketData["HTML"]?>
										<!--/noindex-->
									</div>
								<?}else{?>
									<div class="offer_buy_block">
										<div class="counter_wrapp clearfix ce_cmp_hidden">
											<?=\Aspro\Functions\CAsproMax::showItemCounter($arAddToBasketData, $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["ID"], $arItemIDs, $arParams, 'big');?>
											<div id="<?=$arItemIDs["ALL_ITEM_IDS"]['BASKET_ACTIONS']; ?>" class="button_block <?=($arAddToBasketData["ACTION"] == "ORDER" || !$arAddToBasketData["CAN_BUY"] || !$arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_LIST"] || $arAddToBasketData["ACTION"] == "SUBSCRIBE" ? "wide" : "");?>">
												<!--noindex-->
													<?=$arAddToBasketData["HTML"]?>
												<!--/noindex-->
											</div>
										</div>
										<div class="counter_wrapp ce_cmp_visible">
											<div id="<?=$arItemIDs["ALL_ITEM_IDS"]['BASKET_ACTIONS']; ?>" class="button_block wide">
												<a class="btn btn-default basket read_more" rel="nofollow" data-item="<?=$arItem['ID']?>" href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=Loc::getMessage('CATALOG_READ_MORE')?></a>
											</div>
										</div>
									</div>
									<?if(isset($arCurrentSKU['PRICE_MATRIX']) && $arCurrentSKU['PRICE_MATRIX']) // USE_PRICE_COUNT
									{?>
										<?if($arCurrentSKU['ITEM_PRICE_MODE'] == 'Q' && count($arCurrentSKU['PRICE_MATRIX']['ROWS']) > 1):?>
											<?$arOnlyItemJSParams = array(
												"ITEM_PRICES" => $arCurrentSKU["ITEM_PRICES"],
												"ITEM_PRICE_MODE" => $arCurrentSKU["ITEM_PRICE_MODE"],
												"ITEM_QUANTITY_RANGES" => $arCurrentSKU["ITEM_QUANTITY_RANGES"],
												"MIN_QUANTITY_BUY" => $arAddToBasketData["MIN_QUANTITY_BUY"],
												"SHOW_DISCOUNT_PERCENT_NUMBER" => $arParams["SHOW_DISCOUNT_PERCENT_NUMBER"],
												"ID" => $arItemIDs["strMainID"],
												"NOT_SHOW" => "Y",
											)?>
											<script type="text/javascript">
												var <? echo $arItemIDs["strObName"]; ?>el = new JCCatalogSectionOnlyElement(<? echo CUtil::PhpToJSObject($arOnlyItemJSParams, false, true); ?>);
											</script>
										<?endif;?>
									<?}?>
								<?}?>
							<?endif;?>
						<?endif;?>
					<?else:?>
						<div class="counter_wrapp clearfix offer_buy_block<?=(($arAddToBasketData["ACTION"] == "NOTHING") ? ' n-btn' : '');?> ce_cmp_visible">
							<div class="button_block">
								<?if($totalCountCMP):?>
									<?if($bUseSkuProps)
										{
											if(!$arItem["OFFERS"])
											{
												$arAddToBasketData = CMax::GetAddToBasketArray($arItem, $totalCountCMP, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'btn-exlg', $arParams);
											}
											elseif($arItem["OFFERS"])
											{
												$arAddToBasketData = CMax::GetAddToBasketArray($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]], $totalCountCMP, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'btn-exlg', $arParams);

											}
										}
									?>
								<?endif;?>
								<!--noindex-->
									<?=$arAddToBasketData["HTML"]?>
								<!--/noindex-->
							</div>
						</div>
					<?endif;?>
					<div class="sku_props ce_cmp_hidden">
						<?if($arItem["OFFERS"])
						{
							if(!empty($arItem['OFFERS_PROP'])){?>
								<div class="bx_catalog_item_scu wrapper_sku" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['PROP_DIV']; ?>" data-site_id="<?=SITE_ID;?>" data-id="<?=$arItem["ID"];?>" data-offer_id="<?=$arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["ID"];?>" data-propertyid="<?=$arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PROPERTIES"]["CML2_LINK"]["ID"];?>" data-offer_iblockid="<?=$arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["IBLOCK_ID"];?>">
									<?$arSkuTemplate = array();?>
									<?$arSkuTemplate=CMax::GetSKUPropsArray($arItem['OFFERS_PROPS_JS'], $arResult["SKU_IBLOCK_ID"], $arParams["DISPLAY_TYPE"], $arParams["OFFER_HIDE_NAME_PROPS"], "N", $arItem, $arParams['OFFER_SHOW_PREVIEW_PICTURE_PROPS'], $arParams['MAX_SCU_COUNT_VIEW']);?>
									<?foreach ($arSkuTemplate as $code => $strTemplate){
										if (!isset($arItem['OFFERS_PROP'][$code]))
											continue;
										echo '<div class="item_wrapper">', str_replace('#ITEM#_prop_', $arItemIDs["ALL_ITEM_IDS"]['PROP'], $strTemplate), '</div>';
									}?>
								</div>
								<?$arItemJSParams=CMax::GetSKUJSParams($arResult, $arParams, $arItem);?>
							<?}
						}?>
					</div>
				</div>
			<?$itemFooterButton = ob_get_clean();?>

			<?ob_start();?>
				<?if($arParams["SHOW_DISCOUNT_TIME"]=="Y" && $arParams['SHOW_COUNTER_LIST'] != 'N'){?>
					<?$min_price_id=0;
					if($arItem["OFFERS"])
					{
						if($arCurrentSKU && isset($arCurrentSKU['PRICE_MATRIX']) && $arCurrentSKU['PRICE_MATRIX']) // USE_PRICE_COUNT
						{
							if(isset($arCurrentSKU['PRICE_MATRIX']['MATRIX']) && is_array($arCurrentSKU['PRICE_MATRIX']['MATRIX']))
							{
								$arMatrixKey = array_keys($arCurrentSKU['PRICE_MATRIX']['MATRIX']);
								$min_price_id=current($arMatrixKey);
							}
						}
					}
					else
					{
						if(isset($arItem['PRICE_MATRIX']) && $arItem['PRICE_MATRIX']) // USE_PRICE_COUNT
						{
							$arMatrixKey = array_keys($arItem['PRICE_MATRIX']['MATRIX']);
							$min_price_id=current($arMatrixKey);
						}
					}?>
					<?$arDiscount = []?>
					<?\Aspro\Functions\CAsproMax::showDiscountCounter($totalCount, $arDiscount, $arQuantityData, $arItem, $strMeasure, 'v2 grey', $item_id, true);?>
				<?}?>
			<?$itemDiscountTime = ob_get_clean();?>

			<div class="col-lg-<?=$col;?><?if($bBigBlock):?> col-md-8 col-sm-12 col-xs-12<?else:?> col-md-4 col-sm-6 col-xs-6<?endif;?> col-xxs-12 item item-parent item_block<?=($bBigBlock ? ' big' : '');?> <?=($arParams['SET_LINE_ELEMENT_COUNT'] ? 'custom-line' : '');?>">
				<div class="basket_props_block" id="bx_basket_div_<?=$arItem["ID"];?>_<?=$arParams["FILTER_HIT_PROP"]?>" style="display: none;">
					<?if (!empty($arItem['PRODUCT_PROPERTIES_FILL']))
					{
						foreach ($arItem['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo):?>
							<input type="hidden" name="<?=$arParams['PRODUCT_PROPS_VARIABLE'];?>[<?=$propID;?>]" value="<?=htmlspecialcharsbx($propInfo['ID']);?>">
						<?endforeach;
					}
					if (!$emptyProductProperties){?>
						<div class="wrapper">
							<table>
								<?foreach ($arItem['PRODUCT_PROPERTIES'] as $propID => $propInfo){?>
									<tr>
										<td><? echo $arItem['PROPERTIES'][$propID]['NAME']; ?></td>
										<td>
											<?if('L' == $arItem['PROPERTIES'][$propID]['PROPERTY_TYPE']	&& 'C' == $arItem['PROPERTIES'][$propID]['LIST_TYPE']){
												foreach($propInfo['VALUES'] as $valueID => $value){?>
													<label>
														<input type="radio" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo $valueID; ?>" <? echo ($valueID == $propInfo['SELECTED'] ? '"checked"' : ''); ?>><? echo $value; ?>
													</label>
												<?}
											}else{?>
												<select name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"><?
													foreach($propInfo['VALUES'] as $valueID => $value){?>
														<option value="<? echo $valueID; ?>" <? echo ($valueID == $propInfo['SELECTED'] ? '"selected"' : ''); ?>><? echo $value; ?></option>
													<?}?>
												</select>
											<?}?>
										</td>
									</tr>
								<?}?>
							</table>
						</div>
					<?}?>
				</div>

				<div class="catalog_item_wrapp catalog_item item_wrap main_item_wrapper<?=($bBigBlock ? ' big' : '');?> <?=($bFonImg ? '' : ' product_image')?> <?=($arItem["OFFERS"] ? 'has-sku' : '')?>" id="<?=$arItem["strMainID"]?>">
					<div class="inner_wrap <?=$arParams["TYPE_VIEW_BASKET_BTN"]?>">
						<?if($arParams['SHOW_GALLERY'] == 'Y' && $arItem['OFFERS']):?>
							<div class="js-item-gallery hidden"><?\Aspro\Functions\CAsproMaxItem::showSectionGallery( array('ITEM' => $arItem, 'RESIZE' => $arResult['CUSTOM_RESIZE_OPTIONS']) );?></div>
						<?endif;?>
						<?if($bBigBlock):?>
							<?if($bFonImg):?>
								<a class="absolute-full-block lazy absolute-full-block_bg_center darken-bg-animate" href="<?=$arItem['DETAIL_PAGE_URL'];?>" data-src="<?=$imageSrc?>" data-bg="<?=$imageSrc?>" style="background-image:url(<?=\Aspro\Functions\CAsproMax::showBlankImg($imageSrc);?>)"><span></span></a>
							<?endif;?>
						<?endif;?>
						<div class="image_wrapper_block<?=($arParams['SHOW_PROPS'] == 'Y' && $arItem['DISPLAY_PROPERTIES'] && !$bBigBlock ? ' with-props' : '');?>">
							<?\Aspro\Functions\CAsproMaxItem::showStickers($arParams, $arItem, true);?>
							<?if($arParams['TYPE_VIEW_BASKET_BTN'] == 'TYPE_3'):?>
								<div class="like_icons block ce_cmp_hidden">
									<?if($fast_view_text_tmp = \CMax::GetFrontParametrValue('EXPRESSION_FOR_FAST_VIEW'))
										$fast_view_text = $fast_view_text_tmp;
									else
										$fast_view_text = Loc::getMessage('FAST_VIEW');?>
									<div class="fast_view_button">
										<span title="<?=$fast_view_text?>" class="rounded3 colored_theme_hover_bg" data-event="jqm" data-param-form_id="fast_view" data-param-iblock_id="<?=$arParams["IBLOCK_ID"];?>" data-param-id="<?=$arItem["ID"];?>" data-param-item_href="<?=urlencode($arItem["DETAIL_PAGE_URL"]);?>" data-name="fast_view"><?=\CMax::showIconSvg("fw ncolor colored", SITE_TEMPLATE_PATH."/images/svg/quickview".$typeSvg.".svg");?></span>
									</div>
								</div>
								<div class="ce_cmp_visible">
									<?\Aspro\Functions\CAsproMaxItem::showDelayCompareBtn($arParams, $arItem, $arAddToBasketData, $totalCount, $bUseSkuProps, 'block', ($arParams['USE_FAST_VIEW'] != 'N'), ($arParams['SHOW_ONE_CLICK_BUY'] == 'Y'), '_small', $currentSKUID, $currentSKUIBlock);?>
								</div>
							<?else:?>
								<?\Aspro\Functions\CAsproMaxItem::showDelayCompareBtn($arParams, $arItem, $arAddToBasketData, $totalCount, $bUseSkuProps, 'block', ($arParams['USE_FAST_VIEW'] != 'N'), ($arParams['SHOW_ONE_CLICK_BUY'] == 'Y'), '_small', $currentSKUID, $currentSKUIBlock);?>
							<?endif;?>
							<?if(!$bBigBlock):?>
								<?if($arParams['SHOW_PROPS'] == 'Y' && $arItem['DISPLAY_PROPERTIES']):?>
									<div class="properties properties_absolute scrollbar scroll-deferred">
										<div class="properties__container">
											<?foreach($arItem['DISPLAY_PROPERTIES'] as $arProp):?>
												<div class="properties__item">
													<div class="properties__title font_sxs muted">
														<?=$arProp['NAME']?>
														<?if($arProp["HINT"] && $arParams["SHOW_HINTS"]=="Y"):?>
															<div class="hint"><span class="icon colored_theme_hover_bg"><i>?</i></span><div class="tooltip"><?=$arProp["HINT"]?></div></div>
														<?endif;?>
													</div>

													<div class="properties__value font_sm darken">
														<?
														if(is_array($arProp["DISPLAY_VALUE"])) { foreach($arProp["DISPLAY_VALUE"] as $key => $value) { if ($arProp["DISPLAY_VALUE"][$key+1]) {echo $value.", ";} else {echo $value;} }}
														else { echo $arProp["DISPLAY_VALUE"]; }
														?>
													</div>
												</div>
											<?endforeach;?>
										</div>
										<div class="properties__container properties__container_js">
											<?if($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['DISPLAY_PROPERTIES']):?>
												<?foreach($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['DISPLAY_PROPERTIES'] as $arProp):?>
													<div class="properties__item">
														<div class="properties__title font_sxs muted"><?=$arProp['NAME']?></div>
														<div class="properties__value font_sm darken">
															<?
															if(is_array($arProp["DISPLAY_VALUE"])) { foreach($arProp["DISPLAY_VALUE"] as $key => $value) { if ($arProp["DISPLAY_VALUE"][$key+1]) {echo $value.", ";} else {echo $value;} }}
															else { echo $arProp["DISPLAY_VALUE"]; }
															?>
														</div>
													</div>
												<?endforeach;?>
											<?endif;?>
										</div>
									</div>
								<?endif;?>
								<?=$itemDiscountTime?>
								<?if($arParams['SHOW_GALLERY'] == 'Y' && $arParams['SHOW_PROPS'] != 'Y'):?>
									<?if($bUseSkuProps && $arItem["OFFERS"]):?>
										<?\Aspro\Functions\CAsproMaxItem::showSectionGallery( array('ITEM' => $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]], 'RESIZE' => $arResult['CUSTOM_RESIZE_OPTIONS']) );?>
									<?else:?>
										<?\Aspro\Functions\CAsproMaxItem::showSectionGallery( array('ITEM' => $arItem, 'RESIZE' => $arResult['CUSTOM_RESIZE_OPTIONS']) );?>
									<?endif;?>
								<?else:?>
									<?\Aspro\Functions\CAsproMaxItem::showImg($arParams, $arItem, false);?>
								<?endif;?>
							<?else:?>
								<?if($bFonImg):?>
									<div class="ce_cmp_visible">
								<?endif;?>
									<?\Aspro\Functions\CAsproMaxItem::showImg($arParams, $arItem, false);?>
								<?if($bFonImg):?>
									</div>
								<?endif;?>
								<?=$itemDiscountTime?>
							<?endif;?>
						</div>

						<?if($bBigBlock):?>
							<div class="item_info flexbox flexbox--row justify-content-between flex-wrap">
								<div class="item_info--left_block">
									<div class="top_info flexbox flexbox--row flex-wrap">
										<?=$itemRating?>
										<?=$itemSaBlock?>
										<?=$itemTitle?>
									</div>
								</div>
								<div class="item_info--right_block">
									<?=$itemPrice?>
								</div>
							</div>
						<?else:?>
							<div class="item_info">
								<?=$itemRating?>
								<?=$itemTitle?>
								<?=$itemSaBlock?>
								<?=$itemPrice?>
							</div>
						<?endif;?>

						<?if(!$bBigBlock):?>
							<?=$itemFooterButton?>
						<?endif;?>
					</div>

					<?if($bBigBlock):?>
						<?=$itemFooterButton?>
					<?endif;?>
				</div>
			</div>
		<?}?>

		<?if($arParams['IS_COMPACT_SLIDER'] && $bHasBottomPager):?>
			<?if($arParams["AJAX_REQUEST"]=="Y"):?>
				<div class="wrap_nav bottom_nav_wrapper">
			<?endif;?>

			<div class="bottom_nav mobile_slider animate-load-state block-type round-ignore" data-parent=".tabs_slider" data-append=".items" <?=($arParams["AJAX_REQUEST"]=="Y" ? "style='display: none; '" : "");?>>
				<?=CMax::showIconSvg('bottom_nav-icon colored_theme_svg', SITE_TEMPLATE_PATH.'/images/svg/mobileBottomNavLoader.svg');?>
				<?=$arResult["NAV_STRING"]?>
			</div>

			<?if($arParams["AJAX_REQUEST"]=="Y"):?>
				</div>
			<?endif;?>
		<?endif;?>

	<?if($arParams["AJAX_REQUEST"] != "Y"):?>
		</div>
	</div>
	<?endif;?>

	<?if($arParams["AJAX_REQUEST"]=="Y"):?>
		<div class="wrap_nav bottom_nav_wrapper">
	<?endif;?>

	<?$showAllCount = false;?>
	<?if($arParams['IS_CATALOG_PAGE'] == 'Y' && $arParams['SECTION_COUNT_ELEMENTS'] == 'Y'):?>
		<?if((int)$arResult['NAV_RESULT']->NavRecordCount > 0):?>
			<?$this->SetViewTarget("more_text_title");?>
				<span class="element-count-wrapper"><span class="element-count muted font_xs rounded3"><?=$arResult['NAV_RESULT']->NavRecordCount;?></span></span>
			<?$this->EndViewTarget();?>
			<?
			$showAllCount = true;
			$allCount = $arResult['NAV_RESULT']->NavRecordCount;
			?>
		<?endif;?>
	<?endif;?>

	<div class="bottom_nav animate-load-state block-type" <?=($showAllCount ? 'data-all_count="'.$allCount.'"' : '')?> data-parent=".tabs_slider" data-append=".items" <?=($arParams["AJAX_REQUEST"]=="Y" ? "style='display: none; '" : "");?>>
		<?if( $arParams["DISPLAY_BOTTOM_PAGER"] == "Y" ){?><?=$arResult["NAV_STRING"]?><?}?>
	</div>
	

	<?if($arParams["AJAX_REQUEST"]=="Y"):?>
		</div>
	<?endif;?>

	<script>
		// lazyLoadPagenBlock();
		<?if($bSlide):?>
			sliceItemBlockSlide();
		<?else:?>
			sliceItemBlock();
		<?endif;?>
	</script>
<?elseif($arParams['IS_CATALOG_PAGE'] == 'Y'):?>
	<div class="no_goods catalog_block_view">
		<div class="no_products">
			<div class="wrap_text_empty">
				<?if($_REQUEST["set_filter"]){?>
					<?$APPLICATION->IncludeFile(SITE_DIR."include/section_no_products_filter.php", Array(), Array("MODE" => "html",  "NAME" => GetMessage('EMPTY_CATALOG_DESCR')));?>
				<?}else{?>
					<?$APPLICATION->IncludeFile(SITE_DIR."include/section_no_products.php", Array(), Array("MODE" => "html",  "NAME" => GetMessage('EMPTY_CATALOG_DESCR')));?>
				<?}?>
			</div>
		</div>
	</div>
<?endif;?>
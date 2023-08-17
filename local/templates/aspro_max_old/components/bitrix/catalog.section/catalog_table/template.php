<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?use \Bitrix\Main\Localization\Loc,
	  \Bitrix\Main\Web\Json;?>
<?
$bShowOfferTree = $arParams["SHOW_OFFER_TREE_IN_TABLE"] === "Y";
$bHideProps = $arParams['SHOW_PROPS_TABLE'] == 'not' || !isset($arParams['SHOW_PROPS_TABLE']);
$bRowProps = $arParams['SHOW_PROPS_TABLE'] == 'rows';
$bColProps = $arParams['SHOW_PROPS_TABLE'] == 'cols';

?>
<?if( $arResult["ITEMS"] && count( $arResult["ITEMS"] ) >= 1 ){?>
	<?$arParams["BASKET_ITEMS"]=($arParams["BASKET_ITEMS"] ? $arParams["BASKET_ITEMS"] : array());?>
	<?$bOptBuy = ($arParams['MANY_BUY_CATALOG_SECTIONS'] == 'Y');?>
	<?if($arParams["AJAX_REQUEST"]=="N"):?>
		<div class="table-view-outer <?=($bShowOfferTree ? ' table-view-offer-tree' : '');?> <?=($bColProps ? ' table-view-outer--hidden' : '');?>" >
			<?if($bOptBuy):?>
				<div class="flexbox flexbox--row align-items-center justify-content-between flex-wrap product-info-headnote opt-buy <?=$bColProps ? ' opt-buy--transparent' : ''?>">
					<div class="col-auto">
						<div class="product-info-headnote__inner">
							<div class="product-info-headnote__check">
								<div class="filter label_block">
									<input type="checkbox" name="select_all_items" id="select_all_items" value="Y">
									<label for="select_all_items"><?=Loc::getMessage("SELECT_ALL_ITEMS");?></label>
								</div>
							</div>
						</div>
					</div>
					<div class="col-auto">
						<div class="product-info-headnote__inner">
							<div class="product-info-headnote__buy">
								<span data-value="2500" data-currency="RUB" class="opt_action btn btn-default btn-sm no-action" data-action="buy" data-iblock_id="<?=$arParams["IBLOCK_ID"]?>"><span><?=\Bitrix\Main\Config\Option::get("aspro.max", "EXPRESSION_ADDTOBASKET_BUTTON_DEFAULT", GetMessage("EXPRESSION_ADDTOBASKET_BUTTON_DEFAULT"));?></span></span>
							</div>
							<div class="product-info-headnote__toolbar">
								<?if ($arParams["DISPLAY_WISH_BUTTONS"] == "Y" || $arParams["DISPLAY_COMPARE"] == "Y"):?>
									<div class="like_icons list static icons long table-icons" data-size="2">
										<?if ($arParams["DISPLAY_WISH_BUTTONS"] == "Y"):?>
											<div class="wish_item_button">
												<span title="<?=Loc::getMessage('CATALOG_WISH');?>" class="opt_action rounded3 btn btn-xs font_upper_xs btn-transparent no-action" data-action="wish" data-iblock_id="<?=$arParams["IBLOCK_ID"]?>">
													<?=CMax::showIconSvg("op", SITE_TEMPLATE_PATH.'/images/svg/chosen_small.svg', '', '', true, false);?>
												</span>
											</div>
										<?endif;?>
										<?if ($arParams["DISPLAY_COMPARE"] == "Y"):?>
											<div class="compare_item_button">
												<span title="<?=Loc::getMessage('CATALOG_COMPARE');?>" class="opt_action rounded3 btn btn-xs font_upper_xs btn-transparent no-action" data-action="compare" data-iblock_id="<?=$arParams["IBLOCK_ID"]?>">
													<?=CMax::showIconSvg("op", SITE_TEMPLATE_PATH.'/images/svg/compare_small.svg', '', '', true, false);?>
												</span>
											</div>
										<?endif;?>
									</div>
								<?endif;?>
							</div>
						</div>
					</div>
				</div>
			<?endif;?>
		<div id="table-scroller-wrapper" class="table-view js_append flexbox flexbox--row<?=($bOptBuy ? ' with-opt-buy' : '');?> <?=$bColProps ? 'table-props-cols scroller horizontal-scroll bordered' : ''?>">
			<?if($bColProps):?>
				<div id="table-scroller-wrapper__header" class="hide-600">
					<?if ($arResult['SHOW_COLS_PROP'] && $bColProps):?>
						<div class="product-info-head bordered rounded-4 grey-bg hide-991">
							<div class="flexbox flexbox--row">
								<?if ($bOptBuy):?>
									<div class="table-view__item-wrapper-head">
										<div class="item-check">
											<label class="form-checkbox__label form-checkbox__label--no-text"></label>
										</div>
									</div>
								<?endif;?>
								<div class="table-view__item-wrapper-head"><div class="item-foto"></div></div>
								<div class="flex-1 flexbox flexbox--row">
									<div class="table-view__info-top">
										<div class="table-view__item-wrapper-head">
											<div class="font_xs muted"><?=Loc::getMessage('NAME_PRODUCT')?></div>
										</div>
									</div>
									<?foreach ($arResult['COLS_PROP'] as $arProp):?>
										<div class="table-view__item-wrapper-head props hide-991">
											<div class="font_xs muted font_short"><?=$arProp['NAME'];?></div>
										</div>
									<?endforeach;?>
									<div class="table-view__item-actions">
										<div class="table-view__item-wrapper-head">
											<div class="font_xs muted"><?=Loc::getMessage('PRICE_PRODUCT')?></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?endif;?>
				</div>
			<?endif;?>
	<?endif?>
		<?$currencyList = '';
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
		?>
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
			
				$bOutOfProduction = isset($arItem['PROPERTIES']['OUT_OF_PRODUCTION']) && $arItem['PROPERTIES']['OUT_OF_PRODUCTION']['VALUE'] === 'Y';

				if ($bOutOfProduction && $arItem['OFFERS']) {
					$arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['PROPERTIES']['OUT_OF_PRODUCTION'] = ['VALUE' => $arItem['PROPERTIES']['OUT_OF_PRODUCTION']['VALUE']];
					
					if (isset($arItem['PROPERTIES']['PRODUCT_ANALOG_FILTER']) && $arItem['PROPERTIES']['PRODUCT_ANALOG_FILTER']['VALUE']) {
						$arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['PROPERTIES']['PRODUCT_ANALOG_FILTER'] = ['VALUE' => $arItem['PROPERTIES']['PRODUCT_ANALOG_FILTER']['VALUE']];;
					}
				}
	
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
				
				$bComplect = $arItem["PROPERTIES"]["PRODUCT_SET"]["VALUE"] === "Y";
				$addParams = array();
				if($bComplect){
					$addParams = array("DISPLAY_WISH_BUTTONS" => "N", "MESSAGE_FROM" => Loc::getMessage('FROM').' ');
					$arItem["SHOW_FROM_LANG"] = "Y";
				}


				$currentSKUID = $currentSKUIBlock = '';

				$totalCount = CMax::GetTotalCount($arItem, $arParams);
				$arQuantityData = CMax::GetQuantityArray($totalCount, array('ID' => $item_id), 'N', (($arItem["OFFERS"] || $arItem['CATALOG_TYPE'] == CCatalogProduct::TYPE_SET || $bColProps || !$arResult['STORES_COUNT']) ? false : true));

				$arItem["strMainID"] = $this->GetEditAreaId($arItem['ID']);

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
			$bUseSkuProps = ($arItem["OFFERS"] && !empty($arItem['OFFERS_PROP']) && $bShowOfferTree && $arParams['TYPE_SKU'] != 'N');	
			
				$elementName = ((isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arItem['NAME']);

				if($bUseSkuProps)
				{
					if(!$arItem["OFFERS"])
					{
						$arAddToBasketData = CMax::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false,  $arItemIDs["ALL_ITEM_IDS"], 'small', $arParams);
					}
					elseif($arItem["OFFERS"])
					{

						$currentSKUIBlock = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["IBLOCK_ID"];
						$currentSKUID = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["ID"];
						$arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["IS_OFFER"] = "Y";

						//$totalCountCMP = CMax::GetTotalCount($arItem, $arParamsCE_CMP);
						$totalCountCMP = $totalCount;
						$arQuantityDataCMP = CMax::GetQuantityArray($totalCountCMP, array('ID' => $item_id), 'N', false, 'ce_cmp_visible');

						$strMeasure = $arItem["MIN_PRICE"]["CATALOG_MEASURE_NAME"];
						$totalCount = CMax::GetTotalCount($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]], $arParams);
						$arQuantityData = CMax::GetQuantityArray($totalCount, array('ID' => $currentSKUID), 'N', (($arItem['CATALOG_TYPE'] == CCatalogProduct::TYPE_SET || $bColProps || !$arResult['STORES_COUNT']) ? false : true), 'ce_cmp_hidden');				

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
						
						/* need for add basket props */
						$arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["IBLOCK_ID"] = $arItem['IBLOCK_ID'];
						/* */

						$arAddToBasketData = CMax::GetAddToBasketArray($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]], $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'small', $arParams);

						/* restore IBLOCK_ID */
						$arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["IBLOCK_ID"] = $currentSKUIBlock;
						/* */
					}
				}
				else
				{
					$arItem['OFFERS_PROP'] = '';
					if($arItem["OFFERS"])
						$arItem["OFFERS_MORE"] = "Y";
					if($bComplect){
						$arItem["SHOW_MORE_BUTTON"] = "Y";
					}
					$arAddToBasketData = CMax::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, array(), 'small', $arParams);
				}
				?>
				<?//$arAddToBasketData = $arItem['ADD_TO_BASKET_DATA'];?>
				<div class="table-view__item item bordered box-shadow main_item_wrapper js-notice-block" id="<?=$this->GetEditAreaId($arItem['ID']);?>" data-id="<?=$arItem["ID"]?>" data-product_type="<?=$arItem["CATALOG_TYPE"]?>">
					<div class="table-view__item-wrapper item_info catalog-adaptive flexbox flexbox--row">
						<?if($bOptBuy):?>
							<div class="item-check">
								<div class="filter label_block">
									<input type="checkbox" name="chec_item" id="chec_item<?=$arItem['ID']?>" value="Y">
									<label for="chec_item<?=$arItem['ID']?>"></label>
								</div>
							</div>
						<?endif;?>

						<?//image-block?>
						<div class="item-foto">
							<div class="item-foto__picture js-notice-block__image">
								<?\Aspro\Functions\CAsproMaxItem::showImg($arParams, $arItem, !$bComplect);?>
							</div>
							<div class="adaptive">
								<?\Aspro\Functions\CAsproMaxItem::showDelayCompareBtn(array_merge($arParams, $addParams), $arItem, $arAddToBasketData, $totalCount, $bUseSkuProps, 'block', ($arParams['USE_FAST_VIEW'] != 'N' && !$bComplect), false, '_small');?>
							</div>
						</div>
						<div class="table-view__info flexbox inner_content js_offers__<?=$arItem['ID'];?>_<?=$arParams["FILTER_HIT_PROP"]?>">
							<div class="table-view__info-wrapper flexbox flexbox--row">
								<?//text-block?>
								<div class="item-info table-view__info-top">
									<div class="item-title"><a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="dark_link js-notice-block__title"><span><?=$elementName?></span></a></div>
									<div class="wrapp_stockers sa_block" data-fields='<?=Json::encode($arParams["FIELDS"])?>' data-stores='<?=Json::encode($arParams["STORES"])?>' data-user-fields='<?=Json::encode($arParams["USER_FIELDS"])?>'>
										<?if($arParams["SHOW_RATING"] == "Y"):?>
											<div class="rating sm-stars">
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
											</div>
										<?endif;?>
										<?=$arQuantityData["HTML"];?>
										<?if(isset($arQuantityDataCMP) && $arQuantityDataCMP && $arItem['OFFERS'] && !empty($arItem['OFFERS_PROP'])):?>
											<?=$arQuantityDataCMP["HTML"];?>
										<?endif;?>
										<?$bHasArticle = isset($arItem['ARTICLE']) && $arItem['ARTICLE']['VALUE'];?>
										<div class="article_block" <?if($bHasArticle):?>data-name="<?=Loc::getMessage('T_ARTICLE_COMPACT');?>" data-value="<?=$arItem['ARTICLE']['VALUE'];?>"<?endif;?>><?if($bHasArticle){?><div class="muted font_sxs"><?=Loc::getMessage('T_ARTICLE_COMPACT');?>: <?=$arItem['ARTICLE']['VALUE'];?></div><?}?></div>
										
									</div>
								</div>

								<?if ($arItem['DISPLAY_PROPERTIES'] && $bColProps):?>
									<?foreach ($arResult['COLS_PROP'] as $key => $arProp):?>
										<div class="table-view__item-wrapper-prop props hide-991">
											<?if ($arItem['DISPLAY_PROPERTIES'] && $arItem['DISPLAY_PROPERTIES'][$key]):?>
												<div class="properties__value darken font_sm font_short">
													<?if(is_array($arItem['DISPLAY_PROPERTIES'][$key]["DISPLAY_VALUE"]) && count($arItem['DISPLAY_PROPERTIES'][$key]["DISPLAY_VALUE"]) > 1):?>
														<?=implode(', ', $arItem['DISPLAY_PROPERTIES'][$key]["DISPLAY_VALUE"]);?>
													<?else:?>
														<?=$arItem['DISPLAY_PROPERTIES'][$key]["DISPLAY_VALUE"];?>
													<?endif;?>
												</div>
											<?endif;?>
										</div>
									<?endforeach;?>
								<?endif;?>

								<div class="item-actions flexbox flexbox--row">
									<?//prices-block?>
									<div class="item-price">
										<div class="cost prices clearfix">
											<?if($arItem["OFFERS"]):?>
												<?if($arCurrentSKU):?>
													<div class="ce_cmp_hidden">
												<?endif;?>
												<?=\Aspro\Functions\CAsproMaxItem::showItemPricesDefault($arParams);?>
												<div class="js_price_wrapper">
													<?if($arCurrentSKU && !$bOutOfProduction):?>
														<?$arParams['HIDE_PRICE'] = false?>
														<?$item_id = $arCurrentSKU["ID"];
														$arCurrentSKU['PRICE_MATRIX'] = $arCurrentSKU['PRICE_MATRIX_RAW'];
														$arCurrentSKU['CATALOG_MEASURE_NAME'] = $arCurrentSKU['MEASURE'];
														if(isset($arCurrentSKU['PRICE_MATRIX']) && $arCurrentSKU['PRICE_MATRIX'] && $arCurrentSKU['ITEM_PRICE_MODE'] == 'Q'): // USE_PRICE_COUNT?>
														<?if ($arParams['USE_PRICE_COUNT'] != 'Y'):?>
															<?$arParams['HIDE_PRICE'] = true?>
															<?\Aspro\Functions\CAsproMaxItem::showItemPrices($arParams, $arCurrentSKU["PRICES"], $strMeasure, $min_price_id, ($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] == "Y" ? "N" : "Y"));?>
														<?endif;?>
															<?if($arCurrentSKU['ITEM_PRICE_MODE'] == 'Q' && count($arCurrentSKU['PRICE_MATRIX']['ROWS']) > 1):?>
																<?=CMax::showPriceRangeTop($arCurrentSKU, $arParams, Loc::getMessage("CATALOG_ECONOMY"));?>
															<?endif;?>
															<?if ($arParams['USE_PRICE_COUNT'] == 'Y'):?>
																<?=CMax::showPriceMatrix($arCurrentSKU, $arParams, $strMeasure, $arAddToBasketData);?>
															<?endif;?>
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
												<?if(isset($arItem['PRICE_MATRIX']) && $arItem['PRICE_MATRIX']): // USE_PRICE_COUNT?>
													<?if(\CMax::GetFrontParametrValue('SHOW_POPUP_PRICE') == 'Y' || $arItem['ITEM_PRICE_MODE'] == 'Q' || (\CMax::GetFrontParametrValue('SHOW_POPUP_PRICE') != 'Y' && $arItem['ITEM_PRICE_MODE'] != 'Q' && count($arItem['PRICE_MATRIX']['COLS']) <= 1)):?>
														<?=CMax::showPriceRangeTop($arItem, $arParams, Loc::getMessage("CATALOG_ECONOMY"));?>
													<?endif;?>
													<?if(count($arItem['PRICE_MATRIX']['ROWS']) > 1 || count($arItem['PRICE_MATRIX']['COLS']) > 1):?>
														<?=CMax::showPriceMatrix($arItem, $arParams, $strMeasure, $arAddToBasketData);?>
													<?endif;?>
												<?elseif(isset($arResult["PRICES"])):?>
													<?\Aspro\Functions\CAsproMaxItem::showItemPrices(array_merge($arParams, $addParams), $arItem["PRICES"], $strMeasure, $min_price_id, ($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] == "Y" ? "N" : "Y"));?>
												<?endif;?>
											<?endif;?>
										</div>
										<?\Aspro\Functions\CAsproMax::showBonusBlockList($arCurrentSKU ?: $arItem);?>

										<div class="basket_props_block" id="bx_basket_div_<?=$arItem["ID"];?>" style="display: none;">
											<?if (!empty($arItem['PRODUCT_PROPERTIES_FILL'])){
												foreach ($arItem['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo){?>
													<input type="hidden" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo htmlspecialcharsbx($propInfo['ID']); ?>">
												<?}
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
												<?
											}?>
										</div>
									</div>

									<?//buttons-block?>							
									<div class="item-buttons item_<?=$arItem["ID"]?>">
										<?if(!$arItem["OFFERS"] || $arParams['TYPE_SKU'] !== 'TYPE_1' || !$bShowOfferTree):?>
											<div class="small-block counter_wrapp <?=($arItem["OFFERS"] && $arParams["TYPE_SKU"] == "TYPE_1" && $bShowOfferTree ? 'woffers' : '')?> list clearfix n-mb">
												<?if($arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_LIST"] && !count($arItem["OFFERS"]) && $arAddToBasketData["ACTION"] == "ADD" && $arAddToBasketData["CAN_BUY"]):?>
													<?=\Aspro\Functions\CAsproMax::showItemCounter($arAddToBasketData, $arItem["ID"], $arItemIDs, $arParams, '', '', true);?>
												<?endif;?>
												<div id="<?=$arItemIDs["ALL_ITEM_IDS"]['BASKET_ACTIONS']; ?>" class="button_block <?=($arAddToBasketData['ACTION'] === 'OUT_OF_PRODUCTION' || in_array($arItem["ID"], $arParams["BASKET_ITEMS"]) || $arAddToBasketData["ACTION"] == "ORDER"|| $arAddToBasketData["ACTION"] == "SUBSCRIBE" || ($arAddToBasketData["ACTION"] == 'MORE' || !$arAddToBasketData["CAN_BUY"]) || !$arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_LIST"] ? "wide" : "");?>">
													<!--noindex-->
														<?=$arAddToBasketData["HTML"]?>
													<!--/noindex-->
												</div>
											</div>
											<?
											if(isset($arItem['PRICE_MATRIX']) && $arItem['PRICE_MATRIX']) // USE_PRICE_COUNT
											{?>
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
											<?}?>
										<?elseif($arItem["OFFERS"]):?>
											<?if(empty($arItem['OFFERS_PROP'])){?>
												<div class="offer_buy_block buys_wrapp <?=($arItem["OFFERS"] && $arParams["TYPE_SKU"] == "TYPE_1" && $bShowOfferTree ? 'woffers' : '')?>">
													<div class="small-block counter_wrapp list clearfix">
													<?
													$arItem["OFFERS_MORE"] = "Y";
													$arAddToBasketData = CMax::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'small read_more1', $arParams);?>
													<!--noindex-->
														<?=$arAddToBasketData["HTML"]?>
													<!--/noindex-->
													</div>
												</div>
											<?}else{?>
												<div class="offer_buy_block">
													<div class="small-block counter_wrapp list clearfix ce_cmp_hidden">
														<?if(($arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_LIST"] && $arAddToBasketData["ACTION"] == "ADD") && $arAddToBasketData["CAN_BUY"]):?>		
															<?=\Aspro\Functions\CAsproMax::showItemCounter($arAddToBasketData, $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["ID"], $arItemIDs, $arParams, '', '', true);?>
														<?endif;?>
														<div id="<?=$arItemIDs["ALL_ITEM_IDS"]['BASKET_ACTIONS']; ?>" class="button_block <?=($arAddToBasketData['ACTION'] === 'OUT_OF_PRODUCTION' || ($arAddToBasketData["ACTION"] == "ORDER"/*&& !$arItem["CAN_BUY"]*/)  || !$arAddToBasketData["CAN_BUY"] || !$arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_LIST"] || $arAddToBasketData["ACTION"] == "SUBSCRIBE" ? "wide" : "");?>">
															<!--noindex-->
																<?=$arAddToBasketData["HTML"]?>
															<!--/noindex-->
														</div>
													</div>
													<div class="counter_wrapp list clearfix ce_cmp_visible">
														<div id="<?=$arItemIDs["ALL_ITEM_IDS"]['BASKET_ACTIONS']; ?>" class="button_block wide">
															<?if ($bOutOfProduction && $arItem['PROPERTIES']['PRODUCT_ANALOG_FILTER']['VALUE']):?>
																<a  class='btn btn-transparent-border-color btn-wide has-ripple btn--wrap-text fill-dark-light-block' 
																	href='<?=$arItem['PROPERTIES']['PRODUCT_ANALOG_FILTER']['VALUE'];?>' 
																	title='<?=GetMessage('EXPRESSION_OUT_OF_PRODUCTION_TEXT');?>'
																>
																	<span><?=GetMessage('EXPRESSION_OUT_OF_PRODUCTION_TEXT');?></span>
																</a>
															<?else:?>
																<a class="btn btn-default basket read_more" rel="nofollow" data-item="<?=$arItem['ID']?>" href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=Loc::getMessage('CATALOG_READ_MORE')?></a>
															<?endif;?>
														</div>
													</div>
												</div>
												<?/*=\Aspro\Functions\CAsproMax::showItemOCB($arAddToBasketData, $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]], $arParams);*/?>
												<?
												if(isset($arCurrentSKU['PRICE_MATRIX']) && $arCurrentSKU['PRICE_MATRIX']) // USE_PRICE_COUNT
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
									</div>
								</div>

								<?//icons-block?>
								<?if($arResult['ICONS_SIZE']):?>
									<div class="item-icons s_<?=$arResult['ICONS_SIZE']?>">
										<?\Aspro\Functions\CAsproMaxItem::showDelayCompareBtn(array_merge($arParams, $addParams), $arItem, $arAddToBasketData, $totalCount, $bUseSkuProps, 'list static icons long table-icons', false, false, '_small', $currentSKUID, $currentSKUIBlock);?>
									</div>
								<?endif;?>
							</div>
							
							<?if($arItem["OFFERS"] && !$bOutOfProduction){?>
								<?if(!empty($arItem['OFFERS_PROP'])){?>
									<div class="table-view__sku-info-wrapper flexbox flexbox--row hide-600">
										<div class="sku_props list ce_cmp_hidden ">
											<div class="bx_catalog_item_scu wrapper_sku sku_in_section" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['PROP_DIV']; ?>" data-site_id="<?=SITE_ID;?>" data-id="<?=$arItem["ID"];?>" data-offer_id="<?=$arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["ID"];?>" data-propertyid="<?=$arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PROPERTIES"]["CML2_LINK"]["ID"];?>" data-offer_iblockid="<?=$arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["IBLOCK_ID"];?>" data-iblockid="<?=$arItem["IBLOCK_ID"];?>">
												<?$arSkuTemplate = array();?>
												<?$arSkuTemplate=CMax::GetSKUPropsArray($arItem['OFFERS_PROPS_JS'], $arResult["SKU_IBLOCK_ID"], $arParams["DISPLAY_TYPE"], $arParams["OFFER_HIDE_NAME_PROPS"], "N", $arItem, $arParams['OFFER_SHOW_PREVIEW_PICTURE_PROPS'], $arParams['MAX_SCU_COUNT_VIEW']);?>
												<?foreach ($arSkuTemplate as $code => $strTemplate){
													if (!isset($arItem['OFFERS_PROP'][$code]))
														continue;
													echo '<div class="item_wrapper">', str_replace('#ITEM#_prop_', $arItemIDs["ALL_ITEM_IDS"]['PROP'], $strTemplate), '</div>';
												}?>
											</div>
											<?$arItemJSParams=CMax::GetSKUJSParams($arResult, $arParams, $arItem);?>
										</div>
									</div>
								<?}?>
							<?}?>
							
							<div class="table-view__props-wrapper flexbox flexbox--row hide-600 <?=($bColProps ? 'visible-991' : '')?>">
								<?$boolShowOfferProps = ($arItem['OFFERS_PROPS_DISPLAY']);
								$boolShowProductProps = (isset($arItem['DISPLAY_PROPERTIES']) && !empty($arItem['DISPLAY_PROPERTIES']));?>
								<?if (!$bHideProps && ($boolShowProductProps || $boolShowOfferProps)):?>
									<div class="properties flexbox flexbox--row js-offers-prop">
										<?if ($boolShowProductProps):?>
											<?foreach ($arItem['DISPLAY_PROPERTIES'] as $arProp):?>
												<div class="properties-table-item flexbox js-prop-replace">
													<div class="properties__title font_sxs muted js-prop-title">
														<?=$arProp['NAME']?>
													</div>
													<div class="properties__value darken font_sm js-prop-value">
														<?if(is_array($arProp["DISPLAY_VALUE"]) && count($arProp["DISPLAY_VALUE"]) > 1):?>
															<?=implode(', ', $arProp["DISPLAY_VALUE"]);?>
														<?else:?>
															<?=$arProp["DISPLAY_VALUE"];?>
														<?endif;?>
													</div>
												</div>
											<?endforeach;?>
										<?endif;?>
										<?if ($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['DISPLAY_PROPERTIES']):?>
											<?foreach ($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['DISPLAY_PROPERTIES'] as $arProp):?>
												<div class="properties-table-item flexbox  js-prop">
													<div class="properties__title font_sxs muted">
														<?=$arProp['NAME']?>
													</div>
													<div class="properties__value darken font_sm">
														<?if(is_array($arProp["VALUE"]) && count($arProp["VALUE"]) > 1):?>
															<?=implode(', ', $arProp["VALUE"]);?>
														<?else:?>
															<?=$arProp["VALUE"];?>
														<?endif;?>
													</div>
												</div>
											<?endforeach;?>
										<?endif;?>
									</div>
								<?endif;?>
							</div>

						</div>
					</div>
				</div>
			<?}?>
	<?if($arParams["AJAX_REQUEST"]=="N"):?>
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

	<div class="bottom_nav <?=$arParams["DISPLAY_TYPE"];?>" <?=($showAllCount ? 'data-all_count="'.$allCount.'"' : '')?> <?=($arParams["AJAX_REQUEST"]=="Y" ? "style='display: none; '" : "");?>>
		<?if( $arParams["DISPLAY_BOTTOM_PAGER"] == "Y" ){?><?=$arResult["NAV_STRING"]?><?}?>
	</div>
	<?if($arParams["AJAX_REQUEST"]=="Y"){?>
		</div>
	<?}?>

	<?//if($arParams["AJAX_REQUEST"]=="N"):?>
	<script><?if ($bColProps):?>var tableScrollerOb= new TableScroller('table-scroller-wrapper');<?endif;?></script>
	<?//endif;?>

<?}else{?>
	<div class="module_products_list_b">
		<div class="no_goods">
			<div class="no_products">
				<div class="wrap_text_empty">
					<?if($_REQUEST["set_filter"]){?>
						<?$APPLICATION->IncludeFile(SITE_DIR."include/section_no_products_filter.php", Array(), Array("MODE" => "html",  "NAME" => GetMessage('EMPTY_CATALOG_DESCR')));?>
					<?}else{?>
						<?$APPLICATION->IncludeFile(SITE_DIR."include/section_no_products.php", Array(), Array("MODE" => "html",  "NAME" => GetMessage('EMPTY_CATALOG_DESCR')));?>
					<?}?>
				</div>
			</div>
			<?if($_REQUEST["set_filter"]){?>
				<span class="button wide btn btn-default"><?=GetMessage('RESET_FILTERS');?></span>
			<?}?>
		</div>
	</div>
<?}?>

<?\Aspro\Functions\CAsproMax::showBonusComponentList($arResult);?>
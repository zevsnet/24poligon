<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?use \Bitrix\Main\Localization\Loc,
	  \Bitrix\Main\Web\Json;?>
<?if( count( $arResult["ITEMS"] ) >= 1 ){?>
	<?$arParams["BASKET_ITEMS"]=($arParams["BASKET_ITEMS"] ? $arParams["BASKET_ITEMS"] : array());?>
	<?$bOptBuy = ($arParams['MANY_BUY_CATALOG_SECTIONS'] == 'Y');?>
	<?if($arParams["AJAX_REQUEST"]=="N"):?>
		<div class="table-view js_append flexbox flexbox--row<?=($bOptBuy ? ' with-opt-buy' : '');?>">
			<?if($bOptBuy):?>
				<div class="flexbox flexbox--row align-items-center justify-content-between flex-wrap product-info-headnote opt-buy">
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
		?>
			<?$arOfferProps = implode(';', $arParams['OFFERS_CART_PROPERTIES']);?>
			<?foreach($arResult["ITEMS"]  as $arItem){
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
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
				$totalCount = $arItem['TOTAL_COUNT'];
				$arQuantityData = CMax::GetQuantityArray($totalCount, array('ID' => $item_id), 'N', (($arItem["OFFERS"] || $arItem['CATALOG_TYPE'] == CCatalogProduct::TYPE_SET || $bSlide || !$arResult['STORES_COUNT']) ? false : true));

				$strMeasure = '';
				if(!$arItem["OFFERS"] || $arParams['TYPE_SKU'] === 'TYPE_2'){
					if($arParams["SHOW_MEASURE"] == "Y" && $arItem["CATALOG_MEASURE"]){
						$arMeasure = CCatalogMeasure::getList(array(), array("ID" => $arItem["CATALOG_MEASURE"]), false, false, array())->GetNext();
						$strMeasure = $arMeasure["SYMBOL_RUS"];
					}
					$arItem["OFFERS_MORE"]="Y";
				}
				elseif($arItem["OFFERS"]){
					$strMeasure = $arItem["MIN_PRICE"]["CATALOG_MEASURE_NAME"];
					$arItem["OFFERS_MORE"]="Y";
				}
				$elementName = ((isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arItem['NAME']);
				?>
				<?$arAddToBasketData = $arItem['ADD_TO_BASKET_DATA'];?>
				<div class="table-view__item item bordered box-shadow main_item_wrapper" id="<?=$this->GetEditAreaId($arItem['ID']);?>" data-id="<?=$arItem["ID"]?>" data-product_type="<?=$arItem["CATALOG_TYPE"]?>">
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
							<div class="item-foto__picture">
								<?\Aspro\Functions\CAsproMaxItem::showImg($arParams, $arItem);?>
							</div>
							<div class="adaptive">
								<?\Aspro\Functions\CAsproMaxItem::showDelayCompareBtn($arParams, $arItem, $arAddToBasketData, $totalCount, $bUseSkuProps, 'block', ($arParams['USE_FAST_VIEW'] != 'N'), false, '_small');?>
							</div>
						</div>

						<?//text-block?>
						<div class="item-info">
							<div class="item-title"><a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="dark_link"><?=$elementName?></a></div>
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
							</div>
						</div>

						<div class="item-actions flexbox flexbox--row">
							<?//prices-block?>
							<div class="item-price">
								<div class="cost prices clearfix">
									<?if($arItem["OFFERS"]):?>
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
								</div>

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
								<div class="small-block counter_wrapp list clearfix n-mb">
									<?if($arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_LIST"] && !count($arItem["OFFERS"]) && $arAddToBasketData["ACTION"] == "ADD" && $arAddToBasketData["CAN_BUY"]):?>
										<?=\Aspro\Functions\CAsproMax::showItemCounter($arAddToBasketData, $arItem["ID"], $arItemIDs, $arParams, '', '', true);?>
									<?endif;?>
									<div class="button_block <?=(in_array($arItem["ID"], $arParams["BASKET_ITEMS"]) || $arAddToBasketData["ACTION"] == "ORDER"|| $arAddToBasketData["ACTION"] == "SUBSCRIBE" || ($arAddToBasketData["ACTION"] == 'MORE' || !$arAddToBasketData["CAN_BUY"]) || !$arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_LIST"] ? "wide" : "");?>">
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
											"ID" => $this->GetEditAreaId($arItem["ID"]),
										)?>
										<script type="text/javascript">
											var ob<? echo $this->GetEditAreaId($arItem["ID"]); ?>el = new JCCatalogSectionOnlyElement(<? echo CUtil::PhpToJSObject($arOnlyItemJSParams, false, true); ?>);
										</script>
									<?endif;?>
								<?}?>
							</div>
						</div>

						<?//icons-block?>
						<?if($arResult['ICONS_SIZE']):?>
							<div class="item-icons s_<?=$arResult['ICONS_SIZE']?>">
								<?\Aspro\Functions\CAsproMaxItem::showDelayCompareBtn($arParams, $arItem, $arAddToBasketData, $totalCount, $bUseSkuProps, 'list static icons long table-icons', false, false, '_small', $currentSKUID, $currentSKUIBlock);?>
							</div>
						<?endif;?>
					</div>
				</div>
			<?}?>
	<?if($arParams["AJAX_REQUEST"]=="N"):?>
		</div>
	<?endif;?>
	
	<?if($arParams["AJAX_REQUEST"]=="Y"){?>
		<div class="wrap_nav bottom_nav_wrapper">
	<?}?>

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
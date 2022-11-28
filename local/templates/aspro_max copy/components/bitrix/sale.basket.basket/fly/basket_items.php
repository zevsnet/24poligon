<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//echo ShowError($arResult["ERROR_MESSAGE"]);

$bDelayColumn  = false;
$bDeleteColumn = false;
$bWeightColumn = false;
$bPropsColumn  = false;
$rowCols = 0;

if ($normalCount > 0):
	global $arBasketItems;?>

	<?
	foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader)
	{
		if ($arHeader["id"] == "DELETE"){$bDeleteColumn = true;}
		if ($arHeader["id"] == "TYPE"){$bTypeColumn = true;}
		if ($arHeader["id"] == "QUANTITY"){$bQuantityColumn = true;}
		if ($arHeader["id"] == "DISCOUNT"){$bDiscountColumn = true;}
	}
	?>
	<?foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader):
		if (in_array($arHeader["id"], array("TYPE", "DISCOUNT"))) {continue;} // some header columns are shown differently
		elseif ($arHeader["id"] == "PROPS"){$bPropsColumn = true; continue;}
		elseif ($arHeader["id"] == "DELAY"){$bDelayColumn = true; continue;}
		elseif ($arHeader["id"] == "WEIGHT"){ $bWeightColumn = true;}
		elseif ($arHeader["id"] == "DELETE"){ continue;}?>
	<?endforeach;?>

	<div class="basket_wrap">
		<div class="items_wrap srollbar-custom">
			<?if(isset($arResult["ITEMS_IBLOCK_ID"])){?>
				<div class="iblockid" data-iblockid="<?=$arResult["ITEMS_IBLOCK_ID"];?>"></div>
			<?}?>
			<div class="items">
				<?foreach ($arResult["GRID"]["ROWS"] as $k => $arItem):
					$currency = $arItem["CURRENCY"];
					if ($arItem["DELAY"] == "N" && $arItem["CAN_BUY"] == "Y"):
						$arBasketItems[]=$arItem["PRODUCT_ID"];?>
						<div class="item" data-id="<?=$arItem["ID"]?>" product-id="<?=$arItem["PRODUCT_ID"]?>" data-iblockid="<?=$arItem["IBLOCK_ID"]?>" <?if($arItem["QUANTITY"]>$arItem["AVAILABLE_QUANTITY"]):?>data-error="no_amounth"<?endif;?>>
							<div class="wrap clearfix">
								<div class="image">
									<?if( strlen($arItem["PREVIEW_PICTURE"]["SRC"])>0 ){?>
										<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?><a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="thumb"><?endif;?>
											<img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=(is_array($arItem["PREVIEW_PICTURE"]["ALT"])?$arItem["PREVIEW_PICTURE"]["ALT"]:$arItem["NAME"]);?>" title="<?=(is_array($arItem["PREVIEW_PICTURE"]["TITLE"])?$arItem["PREVIEW_PICTURE"]["TITLE"]:$arItem["NAME"]);?>" />
										<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?>
									<?}elseif( strlen($arItem["DETAIL_PICTURE"]["SRC"])>0 ){?>
										<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?><a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="thumb"><?endif;?>
											<img src="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>" alt="<?=(is_array($arItem["DETAIL_PICTURE"]["ALT"])?$arItem["DETAIL_PICTURE"]["ALT"]:$arItem["NAME"]);?>" title="<?=(is_array($arItem["DETAIL_PICTURE"]["TITLE"])?$arItem["DETAIL_PICTURE"]["TITLE"]:$arItem["NAME"]);?>" />
										<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?>
									<?}else{?>
										<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?><a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="thumb"><?endif;?>
											<img src="<?=SITE_TEMPLATE_PATH?>/images/svg/noimage_product.svg" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" width="70" height="70" />
										<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?>
									<?}?>
									<?if (!empty($arItem["BRAND"])):?><div class="ordercart_brand"><img src="<?=$arItem["BRAND"]?>" /></div><?endif;?>
								</div>
								<div class="body-info">
									<div class="description">
										<div class="name">
											<?if(strlen($arItem["DETAIL_PAGE_URL"]) > 0):?><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?endif;?><?=$arItem["NAME"]?><?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?>
										</div>
										<?if($bPropsColumn && $arItem["PROPS"] && !$arItem["SKU_DATA"]):?>
											<div class="props">
												<?foreach ($arItem["PROPS"] as $val) {
														if (is_array($arItem["SKU_DATA"])) {
															$bSkip = false;
															foreach ($arItem["SKU_DATA"] as $propId => $arProp) { if ($arProp["CODE"] == $val["CODE"]) { $bSkip = true; break; } }
															if ($bSkip) continue;
														} echo '<div class="item_prop"><span class="titles">'.$val["NAME"].':</span><span class="property_value">'.$val["VALUE"].'</span></div>';
												}?>
											</div>
										<?endif;?>
										<?if (is_array($arItem["SKU_DATA"]) && $arItem["PROPS"]):?>
											<div class="props">
												<?foreach ($arItem["SKU_DATA"] as $propId => $arProp):
													$isImgProperty = false; // is image property
													foreach ($arProp["VALUES"] as $id => $arVal) { if (isset($arVal["PICT"]) && !empty($arVal["PICT"])) { $isImgProperty = true; break; } }
													$full = (count($arProp["VALUES"]) > 5) ? "full" : "";
													if ($isImgProperty): // iblock element relation property
													?>
														<div class="bx_item_detail_scu_small_noadaptive <?=$full?>">
															<span class="titles"><?=$arProp["NAME"]?>:</span>
															<div class="bx_scu_scroller_container">
																<div class="bx_scu values">
																	<ul id="prop_<?=$arProp["CODE"]?>_<?=$arItem["ID"]?>">
																	<?foreach ($arProp["VALUES"] as $valueId => $arSkuValue){
																		$selected = "";
																		foreach ($arItem["PROPS"] as $arItemProp) {
																			if ($arItemProp["CODE"] == $arItem["SKU_DATA"][$propId]["CODE"])
																				{ if ($arItemProp["VALUE"] == $arSkuValue["NAME"] || $arItemProp["VALUE"] == $arSkuValue["XML_ID"]) $selected = "class=\"bx_active\""; }
																		};?>
																		<li <?=$selected?>>
																			<span><?=$arSkuValue["NAME"]?></span>
																		</li>
																	<?}?>
																	</ul>
																</div>
															</div>
														</div>
													<?else:?>
														<div class="bx_item_detail_size_small_noadaptive <?=$full?>">
															<span class="titles">
																<?=$arProp["NAME"]?>:
															</span>

															<div class="bx_size_scroller_container">
																<div class="bx_size values">
																	<ul id="prop_<?=$arProp["CODE"]?>_<?=$arItem["ID"]?>">
																		<?foreach ($arProp["VALUES"] as $valueId => $arSkuValue) {
																			$selected = "";
																			foreach ($arItem["PROPS"] as $arItemProp) {
																				if ($arItemProp["CODE"] == $arItem["SKU_DATA"][$propId]["CODE"])
																				{ if ($arItemProp["VALUE"] == $arSkuValue["NAME"]) $selected = "class=\"bx_active\""; }
																			}?>
																			<li <?=$selected?>><span><?=$arSkuValue["NAME"]?></span></li>
																		<?}?>
																	</ul>
																</div>
															</div>
														</div>
													<?endif;?>
												<?endforeach;?>
											</div>
										<?endif;?>
									</div>
									<div class="bottom">
										<div class="prices <?=( $bTypeColumn ? 'notes' : '' );?>">
											<div class="cost prices clearfix">
												<?if( doubleval($arItem["DISCOUNT_PRICE_PERCENT"]) > 0 && $bDiscountColumn){?>
													<div class="price"><?=$arItem["PRICE_FORMATED"]?></div>
													<div class="price discount"><span><?=$arItem["FULL_PRICE_FORMATED"]?></span></div>

													<input type="hidden" name="item_price_<?=$arItem["ID"]?>" value="<?=$arItem["PRICE"]?>" />
													<input type="hidden" name="item_price_discount_<?=$arItem["ID"]?>" value="<?=$arItem["FULL_PRICE"]?>" />
												<?}else{?>
													<div class="price price_new"><?=$arItem["PRICE_FORMATED"];?></div>
													<input type="hidden" name="item_price_<?=$arItem["ID"]?>" value="<?=$arItem["PRICE"]?>" />
												<?}?>
												<?if (strlen($arItem["NOTES"]) > 0 && $bTypeColumn):?>
													<div class="price_name"><?=$arItem["NOTES"]?></div>
												<?endif;?>
												<input type="hidden" name="item_summ_<?=$arItem["ID"]?>" value="<?=$arItem["PRICE"]*$arItem["QUANTITY"]?>" />
											</div>
										</div>
										<div class="buy_block">
											<div class="counter_block basket">
												<?
													$ratio = isset($arItem["MEASURE_RATIO"]) ? $arItem["MEASURE_RATIO"] : 1;
													$tmp_ratio=0;
													$tmp_ratio+=$ratio;
													$float_ratio=is_double($tmp_ratio);

													if ($arItem['CHECK_MAX_QUANTITY'] == 'Y') {
														$max = isset($arItem["AVAILABLE_QUANTITY"]) ? "max=\"".$arItem["AVAILABLE_QUANTITY"]."\"" : "";

														global $arRegion;
														if ($arRegion) {
															$arStores = [];
															if ($arRegion['LIST_STORES']) {
																if(reset($arRegion['LIST_STORES']) != 'component')
																	$arStores = $arRegion['LIST_STORES'];
															}
															if ($arStores) {
																
																$arSelect = array('ID', 'PRODUCT_AMOUNT');
																$arFilter = array('ID' => $arStores);

																$rsStore = CCatalogStore::GetList(array(), array_merge($arFilter, array('PRODUCT_ID' => $arItem['PRODUCT_ID'])), false, false, $arSelect);
																while($arStore = $rsStore->Fetch()){
																	$quantity += $arStore['PRODUCT_AMOUNT'];
																}

																$max = "max=\"".$quantity."\"";
															}
														}
													}

													if (!isset($arItem["MEASURE_RATIO"])){
														$arItem["MEASURE_RATIO"] = 1;
													}
												?>
												<?if (isset($arItem["AVAILABLE_QUANTITY"]) /*&& floatval($arItem["AVAILABLE_QUANTITY"]) != 0*/ /*&& !CSaleBasketHelper::isSetParent($arItem)*/):?><span onclick="setQuantityFly('<?=$arItem["ID"]?>', '<?=$arItem["MEASURE_RATIO"]?>', 'down')" class="minus"></span><?endif;?>
												<input
													type="text"
													class="text"
													id="QUANTITY_INPUT_<?=$arItem["ID"]?>"
													name="QUANTITY_INPUT_<?=$arItem["ID"]?>"
													size="2"
													data-id="<?=$arItem["ID"];?>"
													data-float_ratio="<?=$float_ratio;?>"
													maxlength="18"
													min="0"
													<?=$max?>
													step="<?=$ratio?>"
													value="<?=$arItem["QUANTITY"]?>"
													onchange="updateQuantityFly('QUANTITY_INPUT_<?=$arItem["ID"]?>', '<?=$arItem["ID"]?>', '<?=$ratio?>')"
												>
												<?if (isset($arItem["AVAILABLE_QUANTITY"]) /*&& floatval($arItem["AVAILABLE_QUANTITY"]) != 0*/ /*&& !CSaleBasketHelper::isSetParent($arItem)*/):?><span onclick="setQuantityFly('<?=$arItem["ID"]?>', '<?=$arItem["MEASURE_RATIO"]?>', 'up')" class="plus"></span><?endif;?>
											</div>
											<input type="hidden" id="QUANTITY_<?=$arItem['ID']?>" name="QUANTITY_<?=$arItem['ID']?>" value="<?=$arItem["QUANTITY"]?>" />
											<?if($arItem["QUANTITY"]>$arItem["AVAILABLE_QUANTITY"]):?><div class="error"><?=GetMessage("NO_NEED_AMMOUNT")?></div><?endif;?>
										</div>
										<div class="summ">
											<div class="cost prices"><div class="price"><?=$arItem["SUMM_FORMATED"];?></div></div>
										</div>
										<?if($bDelayColumn):?>
											<div class="delay-cell delay">
												<a class="action_item" href="<?=str_replace("#ID#", $arItem["ID"], $arUrls["delay"])?>">
													<span class="icon" title="<?=GetMessage("SALE_DELAY");?>"><?=CMax::showIconSvg("wish colored_theme_hover_text", SITE_TEMPLATE_PATH.'/images/svg/chosen_small.svg', '', '', true, false);?></span>
												</a>
											</div>
										<?endif;?>
									</div>
									<?if($bDeleteColumn):?>
										<div class="remove-cell"><a class="remove" href="<?=str_replace("#ID#", $arItem["ID"], $arUrls["delete"])?>" title="<?=GetMessage("SALE_DELETE")?>"><?=CMax::showIconSvg("remove colored_theme_hover_text", SITE_TEMPLATE_PATH.'/images/svg/catalog/cancelfilter.svg', '', '', true, false);?></a></div>
									<?endif;?>
								</div>
							</div>
						</div>
					<?endif;?>
				<?endforeach;?>
			</div>
		</div>

		<?
		$arTotal = array();
		if ($bWeightColumn) { $arTotal["WEIGHT"]["NAME"] = GetMessage("SALE_TOTAL_WEIGHT"); $arTotal["WEIGHT"]["VALUE"] = $arResult["allWeight_FORMATED"];}
		if ($arParams["PRICE_VAT_SHOW_VALUE"] == "Y")
		{
			$arTotal["VAT_EXCLUDED"]["NAME"] = GetMessage("SALE_VAT_EXCLUDED"); $arTotal["VAT_EXCLUDED"]["VALUE"] = $arResult["allSum_wVAT_FORMATED"];
			$arTotal["VAT_INCLUDED"]["NAME"] = GetMessage("SALE_VAT_INCLUDED"); $arTotal["VAT_INCLUDED"]["VALUE"] = $arResult["allVATSum_FORMATED"];
		}
		if (doubleval($arResult["DISCOUNT_PRICE_ALL"]) > 0)
		{
			$arTotal["PRICE"]["NAME"] = GetMessage("SALE_TOTAL");
			$arTotal["PRICE"]["VALUES"]["ALL"] = $arResult["allSum_FORMATED"];
			$arTotal["PRICE"]["VALUES"]["WITHOUT_DISCOUNT"] = $arResult["PRICE_WITHOUT_DISCOUNT"];
		}
		else
		{
			$arTotal["PRICE"]["NAME"] = GetMessage("SALE_TOTAL");
			$arTotal["PRICE"]["VALUES"]["ALL"] = $arResult["allSum_FORMATED"];
		}
		?>
		
		<div class="foot clearfix">
			<div class="pull-left">
				<span class="wrap_remove_button basket_action">
					<?if($normalCount){?>
						<span class="colored_theme_hover_text remove_all_basket AnDelCanBuy cur" data-type="basket">
							<?=CMax::showIconSvg("closes", SITE_TEMPLATE_PATH.'/images/svg/catalog/cancelfilter.svg', '', '', true, false);?>
							<?=GetMessage('CLEAR_BASKET')?>
						</span>
					<?}?>
					<?if($delayCount){?>
						<span class="colored_theme_hover_text remove_all_basket DelDelCanBuy" data-type="delay">
							<?=CMax::showIconSvg("closes", SITE_TEMPLATE_PATH.'/images/svg/catalog/cancelfilter.svg', '', '', true, false);?>
							<?=GetMessage('CLEAR_BASKET')?>
						</span>
					<?}?>
					<?if($naCount){?>
						<span class="colored_theme_hover_text remove_all_basket nAnCanBuy" data-type="na">
							<?=CMax::showIconSvg("closes", SITE_TEMPLATE_PATH.'/images/svg/catalog/cancelfilter.svg', '', '', true, false);?>
							<?=GetMessage('CLEAR_BASKET')?>
						</span>
					<?}?>
				</span>
			</div>
			<div class="total pull-right<?=($arResult["DISCOUNT_PRICE_ALL"] ? ' w-discount' : '');?>">
				<?foreach($arTotal as $key => $value):?>
					<?if ($value["VALUES"] && $value["NAME"]):?><div class="item_title"><?=$value["NAME"]?></div><?endif;?>
				<?endforeach;?>
				<div class="wrap_prices">
					<?foreach($arTotal as $key => $value):?>
						<?if ($value["VALUES"] && $value["NAME"]):?>
							<?if ($key=="PRICE"):?>
								<?if ($arResult["DISCOUNT_PRICE_ALL"]):?>
									<div data-type="price_discount">
										<div class="price"><?=$value["VALUES"]["ALL"];?></div>
										<div class="price discount"><strike><?=$value["VALUES"]["WITHOUT_DISCOUNT"];?></strike></div>
									</div>
								<?else:?>
									<div  data-type="price_normal"><div class="price"><?=$arResult["allSum_FORMATED"];?></div></div>
								<?endif;?>
							<?elseif ($value["VALUE"]):?>
								<div data-type="<?=strToLower($key)?>"><div class="price"><?=$value["VALUE"]?></div></div>
							<?endif;?>
						<?endif;?>
					<?endforeach;?>
				</div>
			</div>
		</div>
		<?if($arError["ERROR"]):?>
			<div class="error_block">
				<span class="icon_error_block">
					<?=CMax::showIconSvg("price colored_theme_svg", SITE_TEMPLATE_PATH.'/images/svg/catalog/warning_minimalprice.svg', '', '', true, false);?>
					<?=$arError["TEXT"];?>
				</span>
			</div>
		<?endif;?>
		<div class="buttons clearfix">
			<?if($arParams["AJAX_MODE_CUSTOM"]!="Y"):?>
				<div class="basket_update pull-left">
					<button type="submit"  name="BasketRefresh" class="btn btn-default white grey refresh btn-lg"><span><?=GetMessage("SALE_REFRESH")?></span></button>
					<div class="description"><?=GetMessage("SALE_REFRESH_DESCRIPTION");?></div>
				</div>
			<?endif;?>

			<?if(!$arError["ERROR"]):?>
				<?if (\Bitrix\Main\Config\Option::get("aspro.max", "SHOW_ONECLICKBUY_ON_BASKET_PAGE", "N") == "Y"):?>
					<div class="basket_fast_order pull-right">
						<a onclick="oneClickBuyBasket()" class="btn btn-default fast_order btn-lg"><span><?=GetMessage("SALE_FAST_ORDER")?></span></a>
						<div class="description"><?=GetMessage("SALE_FAST_ORDER_DESCRIPTION");?></div>
					</div>
				<?else:?>
					<div class="wrap_button pull-right">
						<a href="<?=$arParams["PATH_TO_BASKET"]?>" class="btn btn-transparent-border-color btn-lg"><span><?=GetMessage("GO_TO_BASKET")?></span></a>
						<div class="description"><?=GetMessage("SALE_TO_BASKET_DESCRIPTION");?></div>
					</div>
				<?endif;?>
			<?else:?>
				<div class="basket_back pull-right">
					<div class="wrap_button">
						<a href="<?=$arParams["PATH_TO_BASKET"]?>" class="btn btn-transparent-border-color btn-lg"><span><?=GetMessage("GO_TO_BASKET")?></span></a>
					</div>
					<div class="description"><?=GetMessage("SALE_TO_BASKET_DESCRIPTION");?></div>
				</div>
			<?endif;?>

			<?if(!$arError["ERROR"] && \Bitrix\Main\Config\Option::get("aspro.max", "SHOW_ONECLICKBUY_ON_BASKET_PAGE", "N") == "Y"){?>
				<div class="basket_back pull-right">
					<div class="wrap_button">
						<a href="<?=$arParams["PATH_TO_BASKET"]?>" class="btn btn-transparent-border-color btn-lg"><span><?=GetMessage("GO_TO_BASKET")?></span></a>
					</div>
					<div class="description"><?=GetMessage("SALE_TO_BASKET_DESCRIPTION");?></div>
				</div>
			<?}?>

			<?if($arParams["SHOW_FULL_ORDER_BUTTON"]=="Y" && !$arError["ERROR"]):?>
				<div class="basket_checkout pull-right">
					<a data-href="<?=$arParams["PATH_TO_ORDER"];?>" href="<?=$arParams["PATH_TO_ORDER"];?>" class="btn btn-transparent-border-color checkout btn-lg"><span><?=GetMessage("SALE_ORDER")?></span></a>
					<div class="description"><?=GetMessage("SALE_ORDER_DESCRIPTION");?></div>
				</div>
			<?endif;?>
		</div>
	</div>
<?else:?>
	<div class="cart-empty">
		<div class="cart-empty__picture"><div class="img"></div></div>
		<div class="cart-empty__info">
			<?$APPLICATION->IncludeFile(SITE_DIR."include/empty_fly_cart.php", Array(), Array("MODE"      => "html", "NAME"      => GetMessage("SALE_BASKET_EMPTY"),));?>
		</div>
	</div>
<?endif;?>
<div class="one_click_buy_basket_frame"></div>
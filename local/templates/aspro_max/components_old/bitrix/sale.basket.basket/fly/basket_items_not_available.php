<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$bDelayColumn  = false;
$bDeleteColumn = false;
$bWeightColumn = false;
$bPropsColumn  = false;
?>

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

<div class="basket_wrap delayed">
	<div class="items_wrap srollbar-custom">
		<?if(isset($arResult["ITEMS_IBLOCK_ID"])){?>
			<div class="iblockid" data-iblockid="<?=$arResult["ITEMS_IBLOCK_ID"];?>"></div>
		<?}?>
		<div class="items">
			<?foreach ($arResult["GRID"]["ROWS"] as $k => $arItem):
				$currency = $arItem["CURRENCY"];
				if (isset($arItem["NOT_AVAILABLE"]) && $arItem["NOT_AVAILABLE"] == true):?>
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
									<input type="hidden" name="DELAY_<?=$arItem["ID"]?>" value="Y"/>
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
										<div class="counter_block basket delay">
											<?=$arItem["QUANTITY"]?><?if (isset($arItem["MEASURE_TEXT"]) && $arParams["SHOW_MEASURE"]=="Y"):?> <?=$arItem["MEASURE_TEXT"];?>.<?endif;?>
											<?
												$ratio = isset($arItem["MEASURE_RATIO"]) ? $arItem["MEASURE_RATIO"] : 1;
												$tmp_ratio=0;
												$tmp_ratio+=$ratio;
												$float_ratio=is_double($tmp_ratio);

												$max = isset($arItem["AVAILABLE_QUANTITY"]) ? "max=\"".$arItem["AVAILABLE_QUANTITY"]."\"" : "";
												if (!isset($arItem["MEASURE_RATIO"])){
													$arItem["MEASURE_RATIO"] = 1;
												}
											?>
											<input
												type="hidden"
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
											>
										</div>
										<input type="hidden" id="QUANTITY_<?=$arItem['ID']?>" name="QUANTITY_<?=$arItem['ID']?>" value="<?=$arItem["QUANTITY"]?>" />
										<?if($arItem["QUANTITY"]>$arItem["AVAILABLE_QUANTITY"]):?><div class="error"><?=GetMessage("NO_NEED_AMMOUNT")?></div><?endif;?>
									</div>
									<div class="summ">
										<div class="cost prices"><div class="price"><?=$arItem["SUMM_FORMATED"];?></div></div>
									</div>
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
	<div class="foot clearfix">
		<div class="pull-left">
			<span class="wrap_remove_button basket_action">
				<?if($delayCount){?>
					<span class="colored_theme_hover_text remove_all_basket nAnCanBuy cur" data-type="na">
						<?=CMax::showIconSvg("closes", SITE_TEMPLATE_PATH.'/images/svg/catalog/cancelfilter.svg', '', '', true, false);?>
						<?=GetMessage('CLEAR_BASKET')?>
					</span>
				<?}?>
			</span>
		</div>
		<?if($arResult["NA_PRICE"]["SUMM_FORMATED"]):?>
			<div class="total pull-right">
				<div class="item_title"><?=GetMessage("SALE_TOTAL");?></div>
				<div class="wrap_prices">
					<div class="price"><?=$arResult["NA_PRICE"]["SUMM_FORMATED"]?></div>
				</div>
			</div>
		<?endif;?>
	</div>
</div>
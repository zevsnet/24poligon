<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Sale\DiscountCouponsManager;
//echo ShowError($arResult["ERROR_MESSAGE"]);
$bDelayColumn  = false;
$bDeleteColumn = false;
$bWeightColumn = false;
$bPropsColumn  = false;
?>
<?if($normalCount > 0):?>
	<div class="module-cart">
		<table class="colored">
			<thead>
				<tr>
					<?
						foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader){
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
						elseif ($arHeader["id"] == "DELETE"){ continue;}
						if ($arHeader["id"] == "NAME"):?>
							<td class="thumb-cell"></td><td class="name-th">
						<?else:?><td class="<?=strToLower($arHeader["id"])?>-th"><?endif;?><?=getColumnName($arHeader)?></td>
					<?endforeach;?>
					<?if ($bDelayColumn):?><td class="delay-cell"></td><?endif;?>
					<?if ($bDeleteColumn):?><td class="remove-cell"></td><?endif;?>
				</tr>
			</thead>
			<tbody>
				<?$currency = CSaleLang::GetLangCurrency(SITE_ID);?>
				<?foreach ($arResult["GRID"]["ROWS"] as $k => $arItem):
					if ($arItem["DELAY"] == "N" && $arItem["CAN_BUY"] == "Y"):?>
					<tr data-id="<?=$arItem["ID"]?>"  <?if($arItem["QUANTITY"]>$arItem["AVAILABLE_QUANTITY"]):?>data-error="no_amounth"<?endif;?> data-product_id="<?=$arItem["PRODUCT_ID"]?>" >

						<?foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader):
							if (in_array($arHeader["id"], array("PROPS", "DELAY", "DELETE", "TYPE", "DISCOUNT"))) continue; // some values are not shown in columns in this template
							if ($arHeader["id"] == "NAME"):
							?>
								<td class="thumb-cell">
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
											<img src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" width="80" height="80" />
										<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?>
									<?}?>
									<?if (!empty($arItem["BRAND"])):?><div class="ordercart_brand"><img src="<?=$arItem["BRAND"]?>" /></div><?endif;?>
								</td>
								<td class="name-cell">
									<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?endif;?><?=$arItem["NAME"]?><?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?><br />
									<?if ($bPropsColumn):?>
										<div class="item_props">
											<? foreach ($arItem["PROPS"] as $val) {
													if (is_array($arItem["SKU_DATA"])) {
														$bSkip = false;
														foreach ($arItem["SKU_DATA"] as $propId => $arProp) { if ($arProp["CODE"] == $val["CODE"]) { $bSkip = true; break; } }
														if ($bSkip) continue;
													} echo '<span class="item_prop"><span class="name">'.$val["NAME"].':&nbsp;</span><span class="property_value">'.$val["VALUE"].'</span></span>';
												}?>
										</div>
									<?endif;?>
									<?if (is_array($arItem["SKU_DATA"]) && $arItem["PROPS"]):
										foreach ($arItem["SKU_DATA"] as $propId => $arProp):
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
															<?	foreach ($arProp["VALUES"] as $valueId => $arSkuValue){
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
											<?endif;
										endforeach;
									endif;
									?>
								</td>
							<?elseif ($arHeader["id"] == "QUANTITY"):?>
								<td class="count-cell">
									<div class="counter_block basket big_basket">
										<?
											$ratio = isset($arItem["MEASURE_RATIO"]) ? $arItem["MEASURE_RATIO"] : 1;
											$max = isset($arItem["AVAILABLE_QUANTITY"]) ? "max=\"".$arItem["AVAILABLE_QUANTITY"]."\"" : "";
											if (!isset($arItem["MEASURE_RATIO"])){
												$arItem["MEASURE_RATIO"] = 1;
											}
										?>
										<?if (isset($arItem["AVAILABLE_QUANTITY"])/*&& floatval($arItem["AVAILABLE_QUANTITY"]) != 0*/ && !CSaleBasketHelper::isSetParent($arItem)):?><span onclick="setQuantity('<?=$arItem["ID"]?>', '<?=$arItem["MEASURE_RATIO"]?>', 'down')" class="minus">-</span><?endif;?>
										<input
											type="text"
											class="text"
											id="QUANTITY_INPUT_<?=$arItem["ID"]?>"
											name="QUANTITY_INPUT_<?=$arItem["ID"]?>"
											size="2"
											data-id="<?=$arItem["ID"];?>"
											maxlength="18"
											min="0"
											<?=$max?>
											step="<?=$ratio?>"
											value="<?=$arItem["QUANTITY"]?>"
											onchange="updateQuantity('QUANTITY_INPUT_<?=$arItem["ID"]?>', '<?=$arItem["ID"]?>', '<?=$ratio?>')"
										>
										<?if (isset($arItem["AVAILABLE_QUANTITY"])/*&& floatval($arItem["AVAILABLE_QUANTITY"]) != 0*/ && !CSaleBasketHelper::isSetParent($arItem)):?><span onclick="setQuantity('<?=$arItem["ID"]?>', '<?=$arItem["MEASURE_RATIO"]?>', 'up')" class="plus">+</span><?endif;?>
									</div>
									<?if($arItem["QUANTITY"]>$arItem["AVAILABLE_QUANTITY"]):?><div class="error"><?=GetMessage("NO_NEED_AMMOUNT")?></div><?endif;?>
									<input type="hidden" id="QUANTITY_<?=$arItem['ID']?>" name="QUANTITY_<?=$arItem['ID']?>" value="<?=$arItem["QUANTITY"]?>" />
									<?//=getQuantitySelectControl("QUANTITY_SELECT_".$arItem["ID"], "QUANTITY_SELECT_".$arItem["ID"], $arItem["QUANTITY"], $arItem["AVAILABLE_QUANTITY"], $arItem["MEASURE_RATIO"], $arItem["MEASURE_TEXT"]); // quantity selector for mobile ?>
								</td>
							<?elseif ($arHeader["id"] == "SUM"):?>
								<td class="summ-cell"><div class="cost prices"><div class="price"><?=$arItem["SUMM_FORMATED"];?></div></div></td>
							<?elseif ($arHeader["id"] == "PRICE"):?>
								<td class="cost-cell <?=( $bTypeColumn ? 'notes' : '' );?>">
									<div class="cost prices clearfix">
										<?if (strlen($arItem["NOTES"]) > 0 && $bTypeColumn):?>
											<div class="price_name"><?=$arItem["NOTES"]?></div>
										<?endif;?>
										<?if( doubleval($arItem["DISCOUNT_PRICE_PERCENT"]) > 0 && $bDiscountColumn ){?>
											<div class="price"><?=$arItem["PRICE_FORMATED"]?></div>
											<div class="price discount"><strike><?=$arItem["FULL_PRICE_FORMATED"]?></strike></div>
											<div class="sale_block">
												<?if($arItem["DISCOUNT_PRICE_PERCENT"] && $arItem["DISCOUNT_PRICE_PERCENT"]<100){?>
													<div class="value">-<?=$arItem["DISCOUNT_PRICE_PERCENT_FORMATED"];?></div>
												<?}?>
												<div class="text"><?=GetMessage("ECONOMY")?> <?=SaleFormatCurrency(round($arItem["DISCOUNT_PRICE"]), $arItem["CURRENCY"]);?></div>
												<div class="clearfix"></div>
											</div>
											<input type="hidden" name="item_price_<?=$arItem["ID"]?>" value="<?=$arItem["PRICE"]?>" />
											<input type="hidden" name="item_price_discount_<?=$arItem["ID"]?>" value="<?=$arItem["FULL_PRICE"]?>" />
										<?}else{?>
											<div class="price"><?=$arItem["PRICE_FORMATED"];?></div>
											<input type="hidden" name="item_price_<?=$arItem["ID"]?>" value="<?=$arItem["PRICE"]?>" />
										<?}?>
										<input type="hidden" name="item_summ_<?=$arItem["ID"]?>" value="<?=$arItem["PRICE"]*$arItem["QUANTITY"]?>" />
									</div>
								</td>
							<?elseif ($arHeader["id"] == "WEIGHT"):?>
								<td class="weight-cell"><?=$arItem["WEIGHT_FORMATED"]?></td>
							<?else:?>
								<td class="cell"><?=$arItem[$arHeader["id"]]?></td>
							<?endif;?>
						<?endforeach;?>

						<?if ($bDelayColumn ):?>
							<td class="delay-cell delay">
								<a class="wish_item" href="<?=str_replace("#ID#", $arItem["ID"], $arUrls["delay"])?>" title="<?=GetMessage("SALE_DELAY")?>">
									<span class="icon"><i></i></span>
								</a>
							</td>
						<?endif;?>
						<?if ($bDeleteColumn):?>
							<td class="remove-cell"><a class="remove" href="<?=str_replace("#ID#", $arItem["ID"], $arUrls["delete"])?>" title="<?=GetMessage("SALE_DELETE")?>"><i></i></a></td>
						<?endif;?>
					</tr>
					<?endif;?>
				<?endforeach;?>
				<?
				$arTotal = array();
				if($bWeightColumn) { $arTotal["WEIGHT"]["NAME"] = GetMessage("SALE_TOTAL_WEIGHT"); $arTotal["WEIGHT"]["VALUE"] = $arResult["allWeight_FORMATED"];}
				if($arParams["PRICE_VAT_SHOW_VALUE"] == "Y"){
					$arTotal["VAT_EXCLUDED"]["NAME"] = GetMessage("SALE_VAT_EXCLUDED"); $arTotal["VAT_EXCLUDED"]["VALUE"] = $arResult["allSum_wVAT_FORMATED"];
					$arTotal["VAT_INCLUDED"]["NAME"] = GetMessage("SALE_VAT_INCLUDED"); $arTotal["VAT_INCLUDED"]["VALUE"] = $arResult["allVATSum_FORMATED"];
				}
				if(doubleval($arResult["DISCOUNT_PRICE_ALL"]) > 0){
					$arTotal["PRICE"]["NAME"] = GetMessage("SALE_TOTAL");
					$arTotal["PRICE"]["VALUES"]["ALL"] = str_replace(" ", "&nbsp;", $arResult["allSum_FORMATED"]);
					$arTotal["PRICE"]["VALUES"]["WITHOUT_DISCOUNT"] = $arResult["PRICE_WITHOUT_DISCOUNT"];
				}
				else{
					$arTotal["PRICE"]["NAME"] = GetMessage("SALE_TOTAL");
					$arTotal["PRICE"]["VALUES"]["ALL"] = $arResult["allSum_FORMATED"];
				}
				?>
			</tbody>
		</table>
		<?$arError = COptimus::checkAllowDelivery($arResult["allSum"], $currency);?>
		<table class="bottom middle <?=($arError["ERROR"] ? 'error' : '');?>">
			<tfoot>
				<tr data-id="total_row" class="top_total_row">
					<td colspan="4" class="row_titles">
						<?if($arParams["HIDE_COUPON"] != "Y" ){?>
							<div class="coupon<?if ($arParams["AJAX_MODE_CUSTOM"]!="Y"):?> b16<?endif;?> form-control bg">
								<div class="input_coupon">
									<span class="coupon-t"><?=GetMessage("STB_COUPON_LABEL");?></span>
									<? $class=""; //if ($_REQUEST["COUPON"]) { if ($arResult["COUPON"]) $class=' class="good"'; else $class=' class="good"'; } ?>
									<span class="coupon_wrap">
										<input type="text" id="COUPON" size="21" value="<?//=$arResult["COUPON"]?>" name="COUPON"<?=$class?>>
									</span><?if ($arParams["AJAX_MODE_CUSTOM"]=="Y"){?><button class="button transparent big_btn long apply-button"><?=GetMessage("SALE_APPLY")?></button><?}?>
								</div>
								<?if (!empty($arResult['COUPON_LIST'])){?>
									<div class="coupons_list">
										<?foreach ($arResult['COUPON_LIST'] as $oneCoupon){
											$couponClass = 'disabled';
											switch ($oneCoupon['STATUS']){
												case DiscountCouponsManager::STATUS_NOT_FOUND:
													$couponClass = 'bad not_found';
													break;
												case DiscountCouponsManager::STATUS_FREEZE:
													$couponClass = 'bad not_apply';
													break;
												case DiscountCouponsManager::STATUS_APPLYED:
													$couponClass = 'good';
													break;
											}?>
											<div class="bx_ordercart_coupon <? echo $couponClass; ?>">
												<input disabled readonly type="hidden" name="OLD_COUPON[]" data-coupon="<?=htmlspecialcharsbx($oneCoupon['COUPON']);?>" value="<?=htmlspecialcharsbx($oneCoupon['COUPON']);?>" class="<? echo $couponClass; ?>">
												<span class="coupon_text"><?=htmlspecialcharsbx($oneCoupon['COUPON']);?></span>
												<span class="del_btn remove <? echo $couponClass; ?>" data-coupon="<? echo htmlspecialcharsbx($oneCoupon['COUPON']); ?>"><i></i></span>
												<div class="bx_ordercart_coupon_notes">
													<?if (isset($oneCoupon['CHECK_CODE_TEXT'])){
														echo (is_array($oneCoupon['CHECK_CODE_TEXT']) ? implode('<br>', $oneCoupon['CHECK_CODE_TEXT']) : $oneCoupon['CHECK_CODE_TEXT']);
													}?>
												</div>
											</div>
										<?}?>
										<?unset($couponClass, $oneCoupon);?>
									</div>
								<?}?>
							</div>
						<?}?>
						<div class="total item_title">
							<?if ($bWeightColumn && floatval($arResult['allWeight']) > 0){?>
								<div class="w_title">
									<?=GetMessage("SALE_TOTAL_WEIGHT");?>
								</div>
							<?}?>
							<div class="s_title">
								<?=GetMessage("SALE_TOTAL");?>
							</div>
						</div>
					</td>
					<td class="row_values">
						<div class="total item_title">
							<?if ($bWeightColumn && floatval($arResult['allWeight']) > 0){?>
								<div class="w_title">
									<?=GetMessage("SALE_TOTAL_WEIGHT");?>
								</div>
							<?}?>
							<div class="s_title">
								<?=GetMessage("SALE_TOTAL");?>
							</div>
						</div>
						<?if ($bWeightColumn && floatval($arResult['allWeight']) > 0){?>
							<div class="wrap_weight">
								<?=$arResult["allWeight_FORMATED"]?>
							</div>
						<?}?>
						<div class="wrap_prices">
							<?foreach($arTotal as $key => $value):?>
								<?if ($value["VALUES"] && $value["NAME"]):?>
									<?if ($key=="PRICE"):?>
										<?if ($arResult["DISCOUNT_PRICE_ALL"]):?>
											<div data-type="price_discount">
												<span class="price"><?=$value["VALUES"]["ALL"];?></span>
												<div class="price discount"><strike><?=$value["VALUES"]["WITHOUT_DISCOUNT"];?></strike></div>
											</div>
										<?else:?>
											<div  data-type="price_normal"><span class="price"><?=$arResult["allSum_FORMATED"];?></span></div>
										<?endif;?>
									<?elseif ($value["VALUE"]):?>
										<div data-type="<?=strToLower($key)?>"><span class="price"><?=$value["VALUE"]?></span></div>
									<?endif;?>
								<?endif;?>
							<?endforeach;?>
						</div>
					</td>
				</tr>
				<tr data-id="total_buttons" class="bottom_btn <?=($arError["ERROR"] ? 'error' : '')?>">
					<td class="backet_back_wrapp">
						<div class="iblock back_btn">
							<div class="basket_back">
								<a class="button transparent big_btn grey_br" href="<?=SITE_DIR?>catalog/"><span><?=GetMessage("SALE_BACK")?></span></a>
								<div class="description"><?=GetMessage("SALE_BACK_DESCRIPTION");?></div>
							</div>
						</div>
					</td>
					<td class="backet_update_wrapp<?=($arParams["AJAX_MODE_CUSTOM"] != "Y" ? '' : ' empty')?>">
						<?if($arParams["AJAX_MODE_CUSTOM"] != "Y"):?>
							<div class="iblock upd_btn">
								<div class="basket_update clearfix">
									<button type="submit"  name="BasketRefresh" class="button big_btn refresh"><span><?=GetMessage("SALE_REFRESH")?></span></button>
									<div class="description"><?=GetMessage("SALE_REFRESH_DESCRIPTION");?></div>
								</div>
							</div>
						<?endif;?>
					</td>
					<td class="basket_print_wrapp<?=($bShowBasketPrint ? '' : ' empty')?>">
						<?if($bShowBasketPrint):?>
							<a href="<?=$hrefBasketPrint?>" target="_blank" rel="nofollow" class="basket_print" title="<?=GetMessage("SALE_PRINT")?>">
								<i></i><span><?=GetMessage("SALE_PRINT")?></span>
							</a>
						<?endif;?>
					</td>
					<?if($arError["ERROR"]):?>
						<td class="basket_error_wrapp last_blockk" colspan="2">
							<div class="icon_error_block"><?=$arError["TEXT"];?>                            <? $APPLICATION->IncludeFile(SITE_DIR . "/include/info_all.php", [], []); ?>
                            </div>
						</td>
					<?else:?>
						<?if($arParams["SHOW_FAST_ORDER_BUTTON"] == "Y"):?>
							<td class="basket_fast_order_wrapp last_blockk" colspan="<?=(1 + ($arParams["SHOW_FULL_ORDER_BUTTON"] == "Y" ? 0 : 1))?>">
								<div class="basket_fast_order clearfix">
									<span onclick="oneClickBuyBasket()" class="button big_btn fast_order"><span><?=GetMessage("SALE_FAST_ORDER")?></span></span>
									<div class="description"><?=GetMessage("SALE_FAST_ORDER_DESCRIPTION");?></div>
								</div>
							</td>
						<?endif;?>
						<?if($arParams["SHOW_FULL_ORDER_BUTTON"] == "Y"):?>
							<td class="basket_checkout_wrapp last_blockk" colspan="<?=(1 + ($arParams["SHOW_FAST_ORDER_BUTTON"] == "Y" ? 0 : 1))?>">
								<div class="basket_checkout clearfix">
									<a class="button transparent big_btn checkout" data-text="<?=GetMessage("ORDER_START");?>" data-href="<?=$arParams["PATH_TO_ORDER"];?>" href="<?=$arParams["PATH_TO_ORDER"];?>" onclick="yaCounter43218779.reachGoal('oformit');checkOut(event);"><span><?=GetMessage("SALE_ORDER")?></span></a>
									<div class="description"><?=GetMessage("SALE_ORDER_DESCRIPTION");?><? $APPLICATION->IncludeFile(SITE_DIR . "/include/info_all.php", [], []); ?></div>
									<input type="hidden" value="BasketOrder" name="BasketOrder">
								</div>
							</td>
						<?endif;?>
					<?endif;?>
				</tr>
			</tfoot>
		</table>
		<script type="text/javascript">
		$(window).resize(function() {
			if($('.middle .module-cart table td.summ-cell').is(':visible')){
				var summWidth = $('.middle .module-cart table td.summ-cell').outerWidth()
				var delayWidth = $('.middle .module-cart table td.delay-cell').length ? $('.middle .module-cart table td.delay-cell').outerWidth() : 0;
				var removeWidth = $('.middle .module-cart table td.remove-cell').length ? $('.middle .module-cart table td.remove-cell').outerWidth() : 0;
				var leftPadding = parseInt($('.middle .module-cart table td.summ-cell').css('padding-left'));
				$('.basket_wrapp .module-cart table.bottom.middle td.row_values').width(summWidth + delayWidth + removeWidth - leftPadding);
			}
			else{
				$('.basket_wrapp .module-cart table.bottom.middle td.row_values').removeAttr('style');
			}

			$('.basket_wrapp .module-cart table.bottom.middle td.row_titles .item_title').show();
			if($('.basket_wrapp .module-cart table.bottom.middle tr.top_total_row').is(':visible')){
				$('.basket_wrapp .module-cart table.bottom.middle td.row_titles').width($('.basket_wrapp .module-cart table.bottom.middle tr.top_total_row').width() - $('.basket_wrapp .module-cart table.bottom.middle td.row_values').outerWidth() - parseInt($('.basket_wrapp .module-cart table.bottom.middle td.row_titles').css('padding-right')));
			}

			if($('.bottom.middle td > .coupon #COUPON').is(':visible')){
				if($('.bottom.middle td > .coupon .coupon-t').css('float') === 'left'){

					var fullWidth = $('.basket_wrapp .module-cart table.bottom.middle td.row_titles').width();
					var textWidth = $('.bottom.middle td > .coupon .coupon-t').css('float') === 'left' ? ($('.bottom.middle td > .coupon .coupon-t').outerWidth() + parseInt($('.bottom.middle td > .coupon .coupon-t').css('margin-right'))) : 0;
					var buttonWidth = $('.bottom.middle td > .coupon .button').css('display') === 'inline-block' ? ($('.bottom.middle td > .coupon .button').outerWidth() + parseInt($('.bottom.middle td > .coupon #COUPON').css('margin-right'))) : 0;
					var totalWidth = $('.bottom.middle .total.item_title').first().is(':visible') ? $('.bottom.middle .total.item_title').first().outerWidth() : 0;
					var paddingAndBorder = parseInt($('.bottom.middle td > .coupon #COUPON').css('padding-left')) + parseInt($('.bottom.middle td > .coupon #COUPON').css('padding-right')) + 2 + 50;
					$('.bottom.middle td > .coupon #COUPON').width(fullWidth - textWidth - buttonWidth - totalWidth - paddingAndBorder);
					
				}
				else{
					$('.bottom.middle td > .coupon #COUPON').removeAttr('style');
				}
			}

			var trWidth = $('.basket_wrapp .module-cart table.bottom.middle .bottom_btn').width();
			var tdSumWidth = 0, maxHeight = 0;
			var tdCount = $('.basket_wrapp .module-cart table.bottom.middle .bottom_btn td').length;
			$('.basket_wrapp .module-cart table.bottom.middle .bottom_btn td').each(function(i) {
				$(this).height('');
				if(!$(this).find('>*').length){
					$(this).addClass('empty');
				}
				if((tdSumWidth += $(this).outerWidth()) <= trWidth){
					maxHeight = $(this).outerHeight() > maxHeight ? $(this).outerHeight() : maxHeight;
					if(i == (tdCount - 1)){
						$('.basket_wrapp .module-cart table.bottom.middle .bottom_btn td').removeClass('to_leftside').attr('style', 'height:' + maxHeight + 'px');
					}
				}
				else{
					i = i > 1 ? i : 0;
					for(var j = 0; j < i; ++j){
						$('.basket_wrapp .module-cart table.bottom.middle .bottom_btn td').eq(j).removeClass('to_leftside').attr('style', 'height:' + maxHeight + 'px');
					}
					for(var j = i; j < tdCount; ++j){
						$('.basket_wrapp .module-cart table.bottom.middle .bottom_btn td').eq(j).addClass('to_leftside');
					}
					return false;
				}
			});
		});
		$(window).resize();
		$(".tabs > li").live("click", function(){
			if (!$(this).is(".cur")){
				$(window).resize();
			}
		})
		</script>
	</div>
<?else:?>
	<div class="cart_empty">
		<table cellspacing="0" cellpadding="0" width="100%" border="0">
			<tr>
				<td class="img_wrapp">
					<div class="img">
						<img src="<?=SITE_TEMPLATE_PATH?>/images/empty_cart.png" alt="<?=GetMessage("BASKET_EMPTY")?>" />
					</div>
				</td>
				<td>
					<div class="text">
						<?$APPLICATION->IncludeFile(SITE_DIR."include/empty_cart.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("SALE_BASKET_EMPTY")));?>
					</div>
				</td>
			</tr>
		</table>
		<div class="clearboth"></div>
	</div>
<?endif;?>
<div class="one_click_buy_basket_frame"></div>
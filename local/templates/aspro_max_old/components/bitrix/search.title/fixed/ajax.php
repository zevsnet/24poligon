<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?if (empty($arResult["CATEGORIES"])) return;?>
<div class="search-maxwidth-wrapper">
	<div class="bx_searche scrollblock scrollblock--thick">
		<?foreach($arResult["CATEGORIES"] as $category_id => $arCategory):?>
			<?foreach($arCategory["ITEMS"] as $i => $arItem):?>
				<?//=$arCategory["TITLE"]?>
				<?if($category_id !== "all"):?>
					<?if(
						$arItem["MODULE_ID"] === 'iblock'
						&& $arItem["ITEM_ID"]
					):?>
						<?if (strpos($arItem["ITEM_ID"], "S") === false):?>
							<?if(isset($arResult["ELEMENTS"][$arItem["ITEM_ID"]]) && $category_id !== "all"):?>
								<?$arElement = $arResult["ELEMENTS"][$arItem["ITEM_ID"]];?>
								<a class="bx_item_block" href="<?=$arItem["URL"]?>">
									<div class="maxwidth-theme">
										<div class="bx_img_element">
											<?if (isset($arElement["PICTURE"]) && is_array($arElement["PICTURE"])):?>
												<img src="<?=$arElement["PICTURE"]["src"]?>">
											<?else:?>
												<img src="<?=SITE_TEMPLATE_PATH?>/images/svg/noimage_product.svg" width="38" height="38">
											<?endif;?>
										</div>
										<div class="bx_item_element">
											<span class="font_sm"><?=$arItem["NAME"]?></span>
											<?if (
												(isset($arElement["MIN_PRICE"]) && $arElement["MIN_PRICE"]) 
												|| (isset($arElement["PRICES"]) && $arElement["PRICES"])
											):?>
												<div class="price cost prices font_sxs">
													<div class="title-search-price">
														<?if(isset($arElement["MIN_PRICE"]) && $arElement["MIN_PRICE"]){?>
															<?if($arElement["MIN_PRICE"]["DISCOUNT_VALUE"] < $arElement["MIN_PRICE"]["VALUE"]):?>
																<div class="price"><?=$arElement["MIN_PRICE"]["PRINT_DISCOUNT_VALUE"]?></div>
																<div class="price discount">
																	<strike><?=$arElement["MIN_PRICE"]["PRINT_VALUE"]?></strike>
																</div>
															<?else:?>
																<div class="price"><?=$arElement["MIN_PRICE"]["PRINT_VALUE"]?></div>
															<?endif;?>
														<?}else{?>
															<?foreach($arElement["PRICES"] as $code=>$arPrice):?>
																<?if($arPrice["CAN_ACCESS"]):?>
																	<?if (count($arElement["PRICES"])>1):?>
																		<div class="search_price_wrap">
																		<div class="price_name"><?=$arResult["PRICES"][$code]["TITLE"];?></div>
																	<?endif;?>
																	<?if($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]):?>
																		<div class="price"><?=$arPrice["PRINT_DISCOUNT_VALUE"]?></div>
																		<div class="price discount">
																			<strike><?=$arPrice["PRINT_VALUE"]?></strike>
																		</div>
																	<?else:?>
																		<div class="price"><?=$arPrice["PRINT_VALUE"]?></div>
																	<?endif;?>
																	<?if (count($arElement["PRICES"])>1):?>
																		</div>
																	<?endif;?>
																<?endif;?>
															<?endforeach;?>
														<?}?>
													</div>
												</div>
											<?elseif($arItem['PARENT']):?>
												<div class="item-parent font_sxs"><?=$arItem['PARENT']?></div>
											<?endif;?>
										</div>
									</div>
								</a>
							<?endif;?>
						<?else:?>
							<?$sectionId = str_replace('S', '', $arItem["ITEM_ID"]);?>
							<?if(isset($arResult["SECTIONS"][$sectionId])):?>
								<?$arSection = $arResult["SECTIONS"][$sectionId];?>
								<a class="bx_item_block" href="<?=$arItem["URL"]?>">
									<div class="maxwidth-theme">
										<div class="bx_img_element">
											<?if(is_array($arSection["PICTURE"])):?>
												<img src="<?=$arSection["PICTURE"]["src"]?>">
											<?else:?>
												<img src="<?=SITE_TEMPLATE_PATH?>/images/svg/noimage_product.svg" width="38" height="38">
											<?endif;?>
										</div>
										<div class="bx_item_element">
											<span class="font_sm"><?=$arItem["NAME"]?></span>
											<?if($arItem['PARENT']):?>
												<div class="item-parent font_sxs"><?=$arItem['PARENT']?></div>
											<?endif;?>
										</div>
									</div>
								</a>
							<?endif;?>
						<?endif;?>
					<?elseif ($arItem['TYPE'] !== 'all'):?>
						<a class="bx_item_block others_result" href="<?=$arItem["URL"]?>">
							<div class="maxwidth-theme">
								<div class="bx_item_element">
									<span><?=$arItem["NAME"]?></span>
									<?if($arItem['PARENT']):?>
										<div class="item-parent font_sxs"><?=$arItem['PARENT']?></div>
									<?endif;?>
								</div>
							</div>
						</a>
					<?endif;?>
				<?endif;?>
			<?endforeach;?>
		<?endforeach;?>
	</div>

	<?if(isset($arResult["CATEGORIES"]['all']) ):?>
		<?foreach($arResult["CATEGORIES"]['all']["ITEMS"] as $i => $arItem):?>
			<div class="bx_item_block all_result">
				<div class="bx_item_element">
					<a class="all_result_title btn btn-transparent btn-wide round-ignore" href="<?=$arItem["URL"]?>"><?=$arItem["NAME"]?></a>
				</div>
				<div style="clear:both;"></div>
			</div>
		<?endforeach;?>
	<?endif;?>
</div>
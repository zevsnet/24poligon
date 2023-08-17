<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?$this->setFrameMode(true);?>
<?use \Bitrix\Main\Localization\Loc,
	  \Bitrix\Main\Web\Json;?>
<?$arParams["SHOW_HINTS"] = "N";?>
<?if($arResult["ITEMS"]):?>
	<!-- items-container -->
	<?$bRow = (isset($arParams['ROW']) && $arParams['ROW'] == 'Y');?>
	<?$bSlide = (isset($arParams['SLIDER']) && $arParams['SLIDER'] == 'Y');?>
	<?$bShowBtn = (isset($arParams['SHOW_BTN']) && $arParams['SHOW_BTN'] == 'Y');?>
		<?if($arParams['TITLE_SLIDER']):?>
			<div class="font_md darken subtitle option-font-bold"><?=$arParams['TITLE_SLIDER'];?></div>
		<?endif;?>
		<div class="block-items<?=($bRow ? ' flexbox flexbox--row flex-wrap' : '');?><?=($bSlide ? ' owl-carousel owl-theme owl-bg-nav short-nav hidden-dots' : '');?> swipeignore"<?if($bSlide):?>data-plugin-options='{"nav": true, "autoplay" : false, "autoplayTimeout" : "3000", "margin": -1, "smartSpeed":1000, <?=(count($arResult["ITEMS"]) > 4 ? "\"loop\": true," : "")?> "responsiveClass": true, "responsive":{"0":{"items": 1},"600":{"items": 2},"768":{"items": 3},"992":{"items": 4}}}'<?endif;?>>
			<?foreach ($arResult['ITEMS'] as $key => $arItem){?>
				<?$strMainID = $this->GetEditAreaId($arItem['ID'] . $key);?>
				<div class="block-item bordered rounded3<?=($bSlide ? '' : ' box-shadow-sm');?>">
					<div class="block-item__wrapper<?=($bShowBtn ? ' w-btn' : '');?> colored_theme_hover_bg-block" id="<?=$strMainID;?>" data-bigdata=Y data-id="<?=$arItem['ID']?>">
						<div class="block-item__inner flexbox flexbox--row">
							<?
							$totalCount = CMax::GetTotalCount($arItem, $arParams);
							$arQuantityData = CMax::GetQuantityArray($totalCount);
							$arItem["FRONT_CATALOG"]="Y";
							$arAddToBasketData = CMax::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], true);

							$elementName = ((isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arItem['NAME']);

							$strMeasure='';
							if($arItem["OFFERS"])
							{
								$strMeasure=$arItem["MIN_PRICE"]["CATALOG_MEASURE_NAME"];
							}
							else
							{
								if (($arParams["SHOW_MEASURE"]=="Y")&&($arItem["CATALOG_MEASURE"]))
								{
									$arMeasure = CCatalogMeasure::getList(array(), array("ID"=>$arItem["CATALOG_MEASURE"]), false, false, array())->GetNext();
									$strMeasure=$arMeasure["SYMBOL_RUS"];
								}
							}
							?>

							<div class="block-item__image block-item__image--wh80">
								<?\Aspro\Functions\CAsproMaxItem::showImg($arParams, $arItem, false);?>
							</div>
							<div class="block-item__info item_info">
								<div class="block-item__title">
									<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="dark-color font_xs"><span><?=$elementName?></span></a>
								</div>
								<div class="block-item__cost cost prices clearfix">
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
											<?elseif(isset($arResult["PRICES"])):?>
												<?\Aspro\Functions\CAsproMaxItem::showItemPrices($arParams, $arItem["PRICES"], $strMeasure, $min_price_id, ($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] == "Y" ? "N" : "Y"));?>
											<?endif;?>
										<?endif;?>
									<?endif;?>
								</div>
								<?\Aspro\Functions\CAsproMax::showBonusBlockList($arItem);?>

								<?if($bShowBtn):?>
									<div class="more-btn"><a class="btn btn-transparent-border-color btn-xs colored_theme_hover_bg-el" rel="nofollow" href="<?=$arItem["DETAIL_PAGE_URL"]?>" data-item="<?=$arItem["ID"]?>"><?=Getmessage("CVP_TPL_MESS_BTN_DETAIL")?></a></div>
								<?endif;?>
							</div>
						</div>
					</div>
				</div>
			<?}?>
		</div>
	</div>
<?endif;?>
<?\Aspro\Functions\CAsproMax::showBonusComponentList($arResult);?>
<!-- items-container -->

<?$signer = new \Bitrix\Main\Security\Sign\Signer;?>
<script>
	setBigData({
		siteId: '<?=CUtil::JSEscape($component->getSiteId())?>',
		componentPath: '<?=CUtil::JSEscape($componentPath)?>',
		params: <?=CUtil::PhpToJSObject($arParams)?>,
		bigData: <?=CUtil::PhpToJSObject($arResult['BIG_DATA'])?>,
		template: '<?=CUtil::JSEscape($signer->sign($templateName, 'catalog.section'))?>',
		parameters: '<?=CUtil::JSEscape($signer->sign(base64_encode(serialize($arResult['ORIGINAL_PARAMETERS'])), 'catalog.section'))?>',
		wrapper: '.bigdata-wrapper',
		countBigdata: '<?=CUtil::JSEscape($arParams['BIGDATA_COUNT'])?>'
	});
</script>

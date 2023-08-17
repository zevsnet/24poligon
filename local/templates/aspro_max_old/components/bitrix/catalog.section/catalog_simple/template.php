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
	?>
	<?if($arParams["AJAX_REQUEST"] != "Y"):?>
		<?$bSlide = (isset($arParams['SLIDE_ITEMS']) && $arParams['SLIDE_ITEMS']);?>
		<?$bGiftblock = (isset($arParams['GIFT_ITEMS']) && $arParams['GIFT_ITEMS']);?>
	<div class="top_wrapper items_wrapper simple <?=$templateName;?>_template">
		<div class="fast_view_params" data-params="<?=urlencode(serialize($arTransferParams));?>"></div>
		<div class="catalog_block items row margin0 js_append ajax_load block flexbox<?=($bSlide ? ' owl-carousel owl-theme owl-bg-nav visible-nav short-nav hidden-dots swipeignore ' : '');?>"<?if($bSlide):?>data-plugin-options='{"nav": true, "autoplay" : false, "autoplayTimeout" : "3000", "smartSpeed":1000, <?=(count($arResult["ITEMS"]) > 4 ? "\"loop\": true," : "")?> "responsiveClass": true, "responsive":{"0":{"items": 2},"600":{"items": 2},"768":{"items": 3},"1200":{"items": 4}}}'<?endif;?>>
	<?endif;?>
		<?$arOfferProps = implode(';', $arParams['OFFERS_CART_PROPERTIES']);?>
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

			$bComplect = $arItem["PROPERTIES"]["PRODUCT_SET"]["VALUE"] === "Y";
			$addParams = array();
			if($bComplect){
				$addParams = array("DISPLAY_WISH_BUTTONS" => "N", "MESSAGE_FROM" => Loc::getMessage('FROM').' ');
				$arItem["SHOW_FROM_LANG"] = "Y";
			}

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

			$bUseSkuProps = ($arItem["OFFERS"] && !empty($arItem['OFFERS_PROP']) && !$bBigBlock);

			$elementName = ((isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arItem['NAME']);

			if($bUseSkuProps)
			{
				if($arItem["OFFERS"])
				{

					$currentSKUIBlock = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["IBLOCK_ID"];
					$currentSKUID = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["ID"];

					$strMeasure = $arItem["MIN_PRICE"]["CATALOG_MEASURE_NAME"];

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
				}
			}
			else
			{
				$arItem['OFFERS_PROP'] = '';
				if($arItem["OFFERS"])
					$arItem["OFFERS_MORE"] = "Y";
			}

			$col = ($arParams["LINE_ELEMENT_COUNT"] == "4" ? 3 : 4);?>
			<div class="col-lg-<?=$col;?> col-md-4 col-sm-6 col-xs-6 col-xxs-12 item item-parent item_block <?=($arParams['SET_LINE_ELEMENT_COUNT'] ? 'custom-line' : '');?>">
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

				<div class="catalog_item_wrapp catalog_item item_wrap main_item_wrapper <?=($arItem["OFFERS"] ? 'has-sku' : '')?>" id="<?=$arItem["strMainID"]?>">
					<div class="inner_wrap">
						<?if($arParams['SHOW_GALLERY'] == 'Y' && $arItem['OFFERS']):?>
							<div class="js-item-gallery hidden"><?\Aspro\Functions\CAsproMaxItem::showSectionGallery( array('ITEM' => $arItem, 'RESIZE' => $arResult['CUSTOM_RESIZE_OPTIONS']) );?></div>
						<?endif;?>
						<div class="image_wrapper_block">
							<?\Aspro\Functions\CAsproMaxItem::showStickers($arParams, $arItem, true);?>
							<?if($arParams['SHOW_GALLERY'] == 'Y'):?>
								<?if($bUseSkuProps && $arItem["OFFERS"]):?>
									<?\Aspro\Functions\CAsproMaxItem::showSectionGallery( array('ITEM' => $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]], 'RESIZE' => $arResult['CUSTOM_RESIZE_OPTIONS']) );?>
								<?else:?>
									<?\Aspro\Functions\CAsproMaxItem::showSectionGallery( array('ITEM' => $arItem, 'RESIZE' => $arResult['CUSTOM_RESIZE_OPTIONS']) );?>
								<?endif;?>
							<?else:?>
								<?\Aspro\Functions\CAsproMaxItem::showImg($arParams, $arItem, false);?>
							<?endif;?>
						</div>
						<div class="item_info">
							<div class="item-title">
								<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="dark_link option-font-bold font_sm"><span><?=$elementName;?></span></a>
							</div>
							<div class="cost prices clearfix">
								<?$arParams["ONLY_ONE_PRICE"] = "Y";?>
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
													<?=CMax::showPriceMatrix($arCurrentSKU, $arParams, $strMeasure);?>
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
											<?=CMax::showPriceMatrix($arItem, $arParams, $strMeasure);?>
										<?endif;?>
									<?elseif(isset($arItem["PRICES"])):?>
										<?\Aspro\Functions\CAsproMaxItem::showItemPrices(array_merge($arParams, $addParams), [$arItem["MIN_PRICE"]], $strMeasure, $min_price_id, ($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] == "Y" ? "N" : "Y"));?>
									<?endif;?>
								<?endif;?>
							</div>
							<?\Aspro\Functions\CAsproMax::showBonusBlockList($arCurrentSKU ?: $arItem);?>
						</div>
						<div class="footer_button"></div>
					</div>
				</div>
			</div>
		<?}?>
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

<script>
	$('.section-content-wrapper').removeClass('with-leftblock');
	$('.right_block.wide_N.catalog_page').removeClass('wide_N').addClass('wide_Y');
	console.log($('.right_block.wide_N.catalog_page'))
</script>

<?\Aspro\Functions\CAsproMax::showBonusComponentList($arResult);?>
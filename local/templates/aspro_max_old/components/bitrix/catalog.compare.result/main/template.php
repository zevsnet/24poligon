<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$isAjax = ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["ajax_action"]) && $_POST["ajax_action"] == "Y");?>
<div class="bx_compare" id="bx_catalog_compare_block">
<?if ($isAjax){
	$APPLICATION->RestartBuffer();
}?>
<?
$bUseGroups = $arParams["USE_COMPARE_GROUP"] === "Y";
$bDelCookie = false;
if($bUseGroups){
	$activeTabCookie = isset($_COOKIE['compare_section']) && $_COOKIE['compare_section'] > 0 ? $_COOKIE['compare_section'] : '0';
	$arSectionsIds = array_column($arResult['SECTIONS'], 'ID');
	if(!in_array($activeTabCookie, $arSectionsIds) && $activeTabCookie){
		$bDelCookie = true;
	}
	$activeTabId = in_array($activeTabCookie, $arSectionsIds) ? $activeTabCookie : reset($arResult['SECTIONS'])['ID'];
} else {
	$activeTabId = '0';
}
?>
<div class="catalog-compare swipeignore">
	<?if($bUseGroups && count($arResult['SECTIONS']) > 1):?>
		<div class="tabs arrow_scroll tabs--in-section compare-sections__tabs">
			<ul class="nav nav-tabs font_14 font_weight--600 ">
				<? foreach ($arResult['SECTIONS'] as $arSection): ?>
					<li class="bordered rounded-4 compare-sections__tab-item <?= $arSection['ID'] === $activeTabId ? 'active' : ''; ?>">
						<span data-section-id="<?=$arSection['ID']?>">
							<?= $arSection['NAME']; ?>
							<span class="muted compare-sections__tab-count"><?= count($arSection["ITEMS"]); ?></span>
						</span>						
					</li>
				<? endforeach; ?>
			</ul>
		</div>
	<?endif;?>
	<div class="catalog-compare__top flexbox flexbox--row justify-content-between align-items-normal">
		<!-- noindex -->
		<ul class="tabs-head nav nav-tabs hidden">
			<li <?=(!$arResult["DIFFERENT"] ? 'class="active"' : '');?>>
				<a rel="nofollow" class="sortbutton<? echo (!$arResult["DIFFERENT"] ? ' active' : ''); ?>" data-href="?DIFFERENT=N" rel="nofollow"><?=GetMessage("CATALOG_ALL_CHARACTERISTICS")?></a>
			</li>
			<li <?=($arResult["DIFFERENT"] ? 'class="active"' : '');?>>
				<a rel="nofollow" class="sortbutton diff <? echo ($arResult["DIFFERENT"] ? ' active' : ''); ?>" data-href="?DIFFERENT=Y" rel="nofollow"><?=GetMessage("CATALOG_ONLY_DIFFERENT")?></a>
			</li>
		</ul>
		<div class="catalog-compare__switch form__check form__check--switch form__check--switch--secondary">
			<div class="onoff filter sm">
				<input type="checkbox" id="compare_diff" <?=($arResult['DIFFERENT'] ? 'checked' : '');?>>
				<label for="compare_diff" class="dark">
					<?=GetMessage("CATALOG_ONLY_DIFFERENT")?>
				</label>
			</div>
		</div>
		<!-- /noindex -->
		<?$arStr=$arCompareIDs=array();
		if($arResult["ITEMS"])
		{
			foreach($arResult["ITEMS"] as $arItem)
			{
				$arCompareIDs[]=$arItem["ID"];
			}
		}
		$arStr=implode("&ID[]=", $arCompareIDs)?>
		<span class="catalog-compare__clear colored_theme_hover_text font_upper muted" onclick="CatalogCompareObj.MakeAjaxAction('<?=$GLOBALS['arTheme']['COMPARE_PAGE_URL']['VALUE']?>?action=DELETE_FROM_COMPARE_RESULT&ID[]=<?=$arStr?>', 'Y');">
			<?=CMax::showIconSvg("remove_item", SITE_TEMPLATE_PATH.'/images/svg/catalog/cancelfilter.svg', '', '', true, false);?>
			<?=GetMessage("CLEAR_ALL_COMPARE")?>
		</span>
	</div>
	<div class="catalog-compare__inner loading">
		<div class="catalog-compare loader_circle"></div>
		<div class="table_compare catalog_block_template">
			<? foreach ($arResult['SECTIONS'] as $arSection): ?>
				<div class="compare-sections__item <?=$arSection['ID'] === $activeTabId ? 'active' : ''?>" data-section-id="<?=$arSection['ID']?>">
					<?if($arResult["SHOW_FIELDS"]):?>
						<div class="catalog-compare__items catalog_block items block flexbox flexbox--row owl-carousel owl-theme owl-bg-nav visible-nav" data-plugin-options='{"nav": true, "autoplay" : false, "dots": false, "autoplayTimeout" : "3000", "smartSpeed":500, "responsiveClass": true, "withSlide": "catalog-compare__props-slider", "rewind": true, "responsive":{"0":{"items": 2},"768":{"items": 3},"992":{"items": 4},"1200":{"items": 5}}}'>
							<?foreach($arSection["ITEMS"] as &$arElement){?>
								<?
								$totalCount = CMax::GetTotalCount($arElement, $arParams);
								$bComplect = $arElement["PROPERTIES"]["PRODUCT_SET"]["VALUE"] === "Y";
								
								$arParams["MESSAGE_FROM"] = '';
								if($arElement["MESSAGE_FROM"])
								{
									$arParams["MESSAGE_FROM"] = $arElement["MESSAGE_FROM"];
									$arElement["FRONT_CATALOG"] = "Y";
								}

								if($bComplect){
									$arElement["SHOW_MORE_BUTTON"] = "Y";
								}

								$arAddToBasketData = CMax::GetAddToBasketArray($arElement, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], true, array(), 'btn-exlg', $arParams);?>
								<div class="item item-parent item_block">
									<div class="catalog_item_wrapp catalog_item item_wrap main_item_wrapper<?=($arElement["OFFERS"] ? 'has-sku' : '')?>" id="<?=$this->GetEditAreaId($arElement['ID']);?>">
										<div class="inner_wrap TYPE_2">
											<span onclick="CatalogCompareObj.MakeAjaxAction('<?=CUtil::JSEscape($arElement['~DELETE_URL'])?>', 'Y');" class="remove colored_theme_hover_text" title="<?=GetMessage("CATALOG_REMOVE_PRODUCT")?>">
												<?=CMax::showIconSvg("remove_item", SITE_TEMPLATE_PATH.'/images/svg/catalog/cancelfilter.svg', '', '', true, false);?>
											</span>
											
											<?$name = (isset($arElement["OFFER_FIELDS"]["NAME"]) ? $arElement["OFFER_FIELDS"]["NAME"] : $arElement["NAME"]);?>
											<?if($arParams['SKU_DETAIL_ID'] && isset($arElement["OFFER_FIELDS"]["ID"]))
												$arElement["DETAIL_PAGE_URL"] .= '?oid='.$arElement["OFFER_FIELDS"]["ID"];?>
											
											<div class="image_wrapper_block">
												<?\Aspro\Functions\CAsproMaxItem::showImg($arParams, $arElement, false);?>
											</div>
											
											<div class="item_info">
												<div class="item-title">
													<a href="<?=$arElement["DETAIL_PAGE_URL"]?>" class="dark_link option-font-bold font_sm"><span><?=$name;?></span></a>
												</div>
												
												<div class="cost prices clearfix">
													<?
													$frame = $this->createFrame()->begin('');
													$frame->setBrowserStorage(true);
													?>
													<div class="icons-basket-wrapper offer_buy_block">
														<div class="button_block">
															<!--noindex-->
																<?=$arAddToBasketData["HTML"]?>
															<!--/noindex-->
														</div>
													</div>

													<?if($arElement["PRICES"]):?>
														<?\Aspro\Functions\CAsproMaxItem::showItemPrices($arParams, $arElement["PRICES"], '', $priceID, "N");?>
													<?endif;?>
													<?$frame->end();?>
												</div>
											</div>
										</div>
									</div>
								</div>
								<?unset($arElement);?>
							<?}?>
							
							<? if (count($arSection["ITEMS"]) === 1): ?>
								<div class="item item-parent item_block">
									<div class="catalog_item_wrapp catalog_item item_wrap main_item_wrapper">
										<div class="inner_wrap inner_wrap--placeholder TYPE_2">
											<a class="btn btn-link has-ripple inner_wrap__link" href="<?= $arSection['SECTION_PAGE_URL']; ?>"><?= GetMessage('CATALOG_COMPARE_ADD_NEW'); ?></a>
											<span class="muted font_xs inner_wrap__description"><?= GetMessage('CATALOG_COMPARE_ADD_NEW_DESCRIPTION'); ?></span>
										</div>
									</div>
								</div>
							<? endif; ?>
						</div>
					<?endif;?>
					
					<?if($arResult["ALL_FIELDS"] || $arResult["ALL_PROPERTIES"] || $arResult["ALL_OFFER_FIELDS"] || $arResult["ALL_OFFER_PROPERTIES"]):?>
						<?$bShowDeletedProps = false;
						if(!empty($arResult["ALL_FIELDS"]))
						{
							foreach ($arResult["ALL_FIELDS"] as $propCode => $arProp)
							{
								if(!isset($arResult['FIELDS_REQUIRED'][$propCode]))
								{
									if($arProp["IS_DELETED"] != "N")
									{
										$bShowDeletedProps = true;
										break;
									}
								}
							}
						}
						if(!$bShowDeletedProps)
						{
							if(!empty($arResult["ALL_OFFER_FIELDS"]))
							{
								foreach ($arResult["ALL_OFFER_FIELDS"] as $propCode => $arProp)
								{
									if($arProp["IS_DELETED"] != "N")
									{
										$bShowDeletedProps = true;
										break;
									}
								}
							}
						}
						if(!$bShowDeletedProps)
						{
							if(!empty($arResult["ALL_PROPERTIES"]))
							{
								foreach ($arResult["ALL_PROPERTIES"] as $propCode => $arProp)
								{
									if($arProp["IS_DELETED"] != "N")
									{
										$bShowDeletedProps = true;
										break;
									}
								}
							}
						}
						if(!$bShowDeletedProps)
						{
							if(!empty($arResult["ALL_OFFER_PROPERTIES"]))
							{
								foreach ($arResult["ALL_OFFER_PROPERTIES"] as $propCode => $arProp)
								{
									if($arProp["IS_DELETED"] != "N")
									{
										$bShowDeletedProps = true;
										break;
									}
								}
							}
						}
						?>
						<?if($bShowDeletedProps):?>
							<div class="swipeignore compare_wr_inner">
								<div class="bx_filtren_container ">
									<ul>
										<?if(!empty($arResult["ALL_FIELDS"])){
											foreach ($arResult["ALL_FIELDS"] as $propCode => $arProp){
												if (!isset($arResult['FIELDS_REQUIRED'][$propCode])){?>
													<li class="btn btn-transparent-border-color <?=($arProp["IS_DELETED"] != "N" ? 'visible' : '');?>">
														<span onclick="CatalogCompareObj.MakeAjaxAction('<?=CUtil::JSEscape($arProp["ACTION_LINK"])?>')">+<?=GetMessage("IBLOCK_FIELD_".$propCode)?></span>
													</li>
												<?}
											}
										}
										if(!empty($arResult["ALL_OFFER_FIELDS"])){
											foreach($arResult["ALL_OFFER_FIELDS"] as $propCode => $arProp){?>
												<li class="btn btn-transparent-border-color <?=($arProp["IS_DELETED"] != "N" ? 'visible' : '');?>">
													<span onclick="CatalogCompareObj.MakeAjaxAction('<?=CUtil::JSEscape($arProp["ACTION_LINK"])?>')">+<?=GetMessage("IBLOCK_FIELD_".$propCode)?></span>
												</li>
											<?}
										}
										if (!empty($arResult["ALL_PROPERTIES"])){
											foreach($arResult["ALL_PROPERTIES"] as $propCode => $arProp){?>
												<li class="btn btn-transparent-border-color <?=($arProp["IS_DELETED"] != "N" ? 'visible' : '');?>">
													<span onclick="CatalogCompareObj.MakeAjaxAction('<?=CUtil::JSEscape($arProp["ACTION_LINK"])?>')">+<?=$arProp["NAME"]?></span>
												</li>
											<?}
										}
										if (!empty($arResult["ALL_OFFER_PROPERTIES"])){
											foreach($arResult["ALL_OFFER_PROPERTIES"] as $propCode => $arProp){?>
												<li class="btn btn-transparent-border-color <?=($arProp["IS_DELETED"] != "N" ? 'visible' : '');?>">
													<span onclick="CatalogCompareObj.MakeAjaxAction('<?=CUtil::JSEscape($arProp["ACTION_LINK"])?>')">+<?=$arProp["NAME"]?></span>
												</li>
											<?}
										}?>
									</ul>
								</div>
							</div>
						<?endif;?>
					<?endif;?>

					<?$arUnvisible = array("NAME", "PREVIEW_PICTURE", "DETAIL_PICTURE");?>

					<?//make conditions array?>
					<?$arShowFileds = $arShowOfferFileds = $arShowProps = $arShowOfferProps = array();?>
					<?if($arResult["SHOW_FIELDS"])
					{
						foreach ($arResult["SHOW_FIELDS"] as $code => $arProp)
						{
							if(!in_array($code, $arUnvisible))
							{
								$showRow = true;
								if(!isset($arResult['FIELDS_REQUIRED'][$code]) || $arResult['DIFFERENT'])
								{
									$arCompare = array();
									foreach($arSection["ITEMS"] as &$arElement)
									{
										$arPropertyValue = $arElement["FIELDS"][$code];
										if(is_array($arPropertyValue))
										{
											sort($arPropertyValue);
											$arPropertyValue = implode(" , ", $arPropertyValue);
										}
										$arCompare[] = $arPropertyValue;
									}
									unset($arElement);
									$showRow = (count(array_unique($arCompare)) > 1);
								}
								if($showRow)
									$arShowFileds[$code] = $arProp;
							}
						}
					}
					if($arResult["SHOW_OFFER_FIELDS"])
					{
						foreach ($arResult["SHOW_OFFER_FIELDS"] as $code => $arProp)
						{
							$showRow = true;
							if ($arResult['DIFFERENT'])
							{
								$arCompare = array();
								foreach($arSection["ITEMS"] as &$arElement)
								{
									$Value = $arElement["OFFER_FIELDS"][$code];
									if(is_array($Value))
									{
										sort($Value);
										$Value = implode(" , ", $Value);
									}
									$arCompare[] = $Value;
								}
								unset($arElement);
								$showRow = (count(array_unique($arCompare)) > 1);
							}
							if ($showRow)
								$arShowOfferFileds[$code] = $arProp;
						}
					}
					if($arResult["SHOW_PROPERTIES"])
					{
						foreach($arResult["SHOW_PROPERTIES"] as $code => $arProperty)
						{
							$showRow = true;
							if ($arResult['DIFFERENT']) {
								$arCompare = array();
								foreach($arSection["ITEMS"] as &$arElement)
								{
									$arPropertyValue = $arElement["DISPLAY_PROPERTIES"][$code]["VALUE"];
									if(is_array($arPropertyValue))
									{
										sort($arPropertyValue);
										$arPropertyValue = implode(" , ", $arPropertyValue);
									}
									$arCompare[] = $arPropertyValue;
								}
								unset($arElement);
								$showRow = (count(array_unique($arCompare)) > 1);
							} else {
								$bNotEmptyProp = false;
								foreach($arSection["ITEMS"] as &$arElement)
								{
									if($arElement["DISPLAY_PROPERTIES"][$code]["VALUE"] !== NULL){
										$bNotEmptyProp = true;
										break;
									}
								}
								unset($arElement);
								$showRow = $bNotEmptyProp;
							}
							if($showRow)
								$arShowProps[$code] = $arProperty;
						}
					}
					if($arResult["SHOW_OFFER_PROPERTIES"])
					{
						foreach($arResult["SHOW_OFFER_PROPERTIES"] as $code=>$arProperty)
						{
							$showRow = true;
							if ($arResult['DIFFERENT']) 
							{
								$arCompare = array();
								foreach($arSection["ITEMS"] as &$arElement)
								{
									$arPropertyValue = $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["VALUE"];
									if(is_array($arPropertyValue))
									{
										sort($arPropertyValue);
										$arPropertyValue = implode(" , ", $arPropertyValue);
									}
									$arCompare[] = $arPropertyValue;
								}
								unset($arElement);
								$showRow = (count(array_unique($arCompare)) > 1);
							} else {
								$bNotEmptyProp = false;
								foreach ($arSection["ITEMS"] as &$arElement) {
									if($arElement["OFFER_DISPLAY_PROPERTIES"][$code]["VALUE"] !== NULL){
										$bNotEmptyProp = true;
										break;
									}
								}
								unset($arElement);
								$showRow = $bNotEmptyProp;
							}
							if($showRow)
								$arShowOfferProps[$code] = $arProperty;
						}
					}
					?>

					<?if($arShowFileds || $arShowOfferFileds || $arShowProps || $arShowOfferProps):?>
						<div class="catalog-compare__props-slider owl-carousel owl-theme" data-plugin-options='{"nav": false, "dots": false, "autoplay" : false, "autoplayTimeout" : "3000", "smartSpeed":500, "responsiveClass": true, "withSlide1": "catalog-compare__items", "rewind": true, "responsive":{"0":{"items": 2},"768":{"items": 3},"992":{"items": 4},"1200":{"items": 5}}}'>
							<?foreach($arSection["ITEMS"] as $arElement){?>
								<div class="catalog-compare__item-props" data-id="<?=$arElement["ID"];?>">
									<?if($arShowFileds):?>
										<?foreach($arShowFileds as $code => $arProp):?>
											<div class="catalog-compare__prop-line font_xs">
												<span class="catalog-compare__prop-name muted"><?=GetMessage("IBLOCK_FIELD_".$code);?></span>
												<?if($arResult["ALL_FIELDS"][$code]){?>
													<span onclick="CatalogCompareObj.MakeAjaxAction('<?=CUtil::JSEscape($arResult["ALL_FIELDS"][$code]["ACTION_LINK"])?>')" class="remove colored_theme_hover_text">
														<?=CMax::showIconSvg("remove_item", SITE_TEMPLATE_PATH.'/images/svg/catalog/cancelfilter.svg', '', '', true, false);?>
													</span>
												<?}?>
												<?=$arElement["FIELDS"][$code];?>
											</div>
										<?endforeach;?>
									<?endif;?>
									<?if($arShowOfferFileds):?>
										<?foreach($arShowOfferFileds as $code => $arProp):?>
											<div class="catalog-compare__prop-line font_xs">
												<span class="catalog-compare__prop-name muted"><?=GetMessage("IBLOCK_OFFER_FIELD_".$code);?></span>
												<?if($arResult["ALL_OFFER_FIELDS"][$code]){?>
													<span onclick="CatalogCompareObj.MakeAjaxAction('<?=CUtil::JSEscape($arResult["ALL_OFFER_FIELDS"][$code]["ACTION_LINK"])?>')" class="remove colored_theme_hover_text">
														<?=CMax::showIconSvg("remove_item", SITE_TEMPLATE_PATH.'/images/svg/catalog/cancelfilter.svg', '', '', true, false);?>
													</span>
												<?}?>
												<?=(is_array($arElement["OFFER_FIELDS"][$code])? implode(", ", $arElement["OFFER_FIELDS"][$code]): $arElement["OFFER_FIELDS"][$code])?>
											</div>
										<?endforeach;?>
									<?endif;?>
									<?if($arShowProps):?>
										<?foreach($arShowProps as $code => $arProp):?>
											<div class="catalog-compare__prop-line font_xs">
												<span class="catalog-compare__prop-name muted"><?=$arProp["NAME"]?></span>
												<?if($arResult["ALL_PROPERTIES"][$code]){?>
													<span onclick="CatalogCompareObj.MakeAjaxAction('<?=CUtil::JSEscape($arResult["ALL_PROPERTIES"][$code]["ACTION_LINK"])?>')" class="remove colored_theme_hover_text">
														<?=CMax::showIconSvg("remove_item", SITE_TEMPLATE_PATH.'/images/svg/catalog/cancelfilter.svg', '', '', true, false);?>
													</span>
												<?}?>
												<?=(is_array($arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? implode(", ", $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]): $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])?>
											</div>
										<?endforeach;?>
									<?endif;?>
									<?if($arShowOfferProps):?>
										<?foreach($arShowOfferProps as $code => $arProp):?>
											<div class="catalog-compare__prop-line font_xs">
												<span class="catalog-compare__prop-name muted"><?=$arProp["NAME"]?></span>
												<?if($arResult["ALL_OFFER_PROPERTIES"][$code]){?>
													<span onclick="CatalogCompareObj.MakeAjaxAction('<?=CUtil::JSEscape($arResult["ALL_OFFER_PROPERTIES"][$code]["ACTION_LINK"])?>')" class="remove colored_theme_hover_text">
														<?=CMax::showIconSvg("remove_item", SITE_TEMPLATE_PATH.'/images/svg/catalog/cancelfilter.svg', '', '', true, false);?>
													</span>
												<?}?>
												<?=(is_array($arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? implode(", ", $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]): $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])?>
											</div>
										<?endforeach;?>
									<?endif;?>
								</div>
							<?}
							unset($arElement);?>
						</div>
					<?endif;?>
				</div>
			<? endforeach; ?>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		setTimeout(function(){
			$('.catalog_block .catalog_item_wrapp.catalog_item .item_info .item-title').sliceHeight({item:'.catalog_item', mobile: true, autoslicecount: false, slice: 999});
		}, 1);
		InitOwlSlider();

		var $sliderProducts = $('.catalog-compare__items'),
			$sliderProps = $('.catalog-compare__props-slider'),
			$propsLines = $sliderProps.find(".catalog-compare__prop-line"),
			$sliderProductsItems = $sliderProducts.find(".owl-item"),
			$sliderPropsItems = $sliderProps.find(".owl-item");

		//change products slider
		$sliderProducts.on('change.owl.carousel', function(event){
			if(event.namespace && event.property.name === 'position')
			{
				var target = event.relatedTarget.relative(event.property.value, true);

				$sliderProductsItems.removeClass("sync");
				$sliderProductsItems.eq(target).addClass("sync");

				if(target != $sliderProps.find(".owl-item.sync").index())
					$sliderProps.owlCarousel('to', target, 500, true);
			}
		});

		//change props slider
		$sliderProps.on('change.owl.carousel', function(event) {
			if (event.namespace && event.property.name === 'position')
			{
				var target = event.relatedTarget.relative(event.property.value, true);

				//show props title
				$sliderPropsItems.removeClass("active-title sync");
				$sliderProps.find(".owl-item:nth-child(" + (target + 1) + ")").addClass("active-title");
				$sliderPropsItems.eq(target).addClass("sync");

				if($sliderProducts.find(".owl-item.sync").index() != target)
					$sliderProducts.owlCarousel('to', target, 500, true);
			}
		});


		tableEqualHeight($sliderProps, $sliderPropsItems);
		$(window).on('resize', function(){
			tableEqualHeight($sliderProps, $sliderPropsItems);
		});

		$propsLines.hover(
			function() {
				var owlItemsActive = $sliderProps.find(".owl-item.active"),
					index = $(this).index();

				$sliderPropsItems.each(function(i, element) {
					$(this).find(".catalog-compare__prop-line").eq(index).addClass("hover-prop");
				});

				owlItemsActive.each(function(i, element) {
					// set border-left
					if (i === 0) {
						$(this).find(".catalog-compare__prop-line").eq(index).addClass("border-left");
					}
					// set border-right
					if (i === owlItemsActive.length - 1) {
						$(this).find(".catalog-compare__prop-line").eq(index).addClass("border-right");
					}
				});
			},
			function(){
				$propsLines.removeClass("hover-prop border-left border-right");
			}
		);
	})
</script>
<?if ($isAjax){
	die();
}?>
</div>
<script type="text/javascript">
	var CatalogCompareObj = new BX.Iblock.Catalog.CompareClass("bx_catalog_compare_block");
</script>

<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */

$frame = $this->createFrame()->begin();

use \Bitrix\Main\Localization\Loc;

$injectId = 'sale_gift_product_'.rand();

$currentProductId = (int)$arResult['POTENTIAL_PRODUCT_TO_BUY']['ID'];

if (isset($arResult['REQUEST_ITEMS']))
{
	CJSCore::Init(array('ajax'));

	// component parameters
	$signer = new \Bitrix\Main\Security\Sign\Signer;
	$signedParameters = $signer->sign(
		base64_encode(serialize($arResult['_ORIGINAL_PARAMS'])),
		'bx.sale.gift.product'
	);
	$signedTemplate = $signer->sign($arResult['RCM_TEMPLATE'], 'bx.sale.gift.product');

	?>

	<div id="<?=$injectId?>" class="sale_gift_product_container"></div>

	<script type="text/javascript">
		BX.ready(function(){

			var currentProductId = <?=CUtil::JSEscape($currentProductId)?>;
			var giftAjaxData = {
				'parameters':'<?=CUtil::JSEscape($signedParameters)?>',
				'template': '<?=CUtil::JSEscape($signedTemplate)?>',
				'site_id': '<?=CUtil::JSEscape(SITE_ID)?>'
			};

			bx_sale_gift_product_load(
				'<?=CUtil::JSEscape($injectId)?>',
				giftAjaxData
			);

			BX.addCustomEvent('onCatalogStoreProductChange', function(offerId){
				if(currentProductId == offerId)
				{
					return;
				}
				currentProductId = offerId;
				bx_sale_gift_product_load(
					'<?=CUtil::JSEscape($injectId)?>',
					giftAjaxData,
					{offerId: offerId}
				);
			});
		});
	</script>

	<?
	$frame->end();
	return;
}

if (!empty($arResult['ITEMS'])){
	$templateData = array(
		'TEMPLATE_CLASS' => 'bx_'.$arParams['TEMPLATE_THEME']
	);
	$arParams['IS_GIFT'] = 'Y';
	$arParams["SHOW_DISCOUNT_PERCENT"] = "N";
	$bFastViewFull = \CMax::GetFrontParametrValue('SHOW_FULL_FAST_VIEW') == "Y" && $arParams['SHOW_GALLERY'] !== 'Y' && $arParams['SHOW_PROPS'] !== 'Y';
	if($fast_view_text_tmp = \CMax::GetFrontParametrValue('EXPRESSION_FOR_FAST_VIEW'))
		$fast_view_text = $fast_view_text_tmp;
	else
		$fast_view_text = Loc::getMessage('FAST_VIEW');

	$arSkuTemplate = array();
	if (!empty($arResult['SKU_PROPS'])){
		$arSkuTemplate=CMax::GetSKUPropsArray($arResult['SKU_PROPS'], $arResult["SKU_IBLOCK_ID"], "block", $arParams["OFFER_HIDE_NAME_PROPS"], "Y");
	}?>
	<script type="text/javascript">
		BX.message({
			CVP_MESS_BTN_BUY: '<? echo ('' != $arParams['MESS_BTN_BUY'] ? CUtil::JSEscape($arParams['MESS_BTN_BUY']) : GetMessageJS('CVP_TPL_MESS_BTN_BUY_GIFT')); ?>',
			CVP_MESS_BTN_ADD_TO_BASKET: '<? echo ('' != $arParams['MESS_BTN_ADD_TO_BASKET'] ? CUtil::JSEscape($arParams['MESS_BTN_ADD_TO_BASKET']) : GetMessageJS('CVP_TPL_MESS_BTN_ADD_TO_BASKET')); ?>',

			CVP_MESS_BTN_DETAIL: '<? echo ('' != $arParams['MESS_BTN_DETAIL'] ? CUtil::JSEscape($arParams['MESS_BTN_DETAIL']) : GetMessageJS('CVP_TPL_MESS_BTN_DETAIL')); ?>',

			CVP_MESS_NOT_AVAILABLE: '<? echo ('' != $arParams['MESS_BTN_DETAIL'] ? CUtil::JSEscape($arParams['MESS_BTN_DETAIL']) : GetMessageJS('CVP_TPL_MESS_BTN_DETAIL')); ?>',
			CVP_BTN_MESSAGE_BASKET_REDIRECT: '<? echo GetMessageJS('CVP_CATALOG_BTN_MESSAGE_BASKET_REDIRECT'); ?>',
			CVP_BASKET_URL: '<? echo $arParams["BASKET_URL"]; ?>',
			CVP_ADD_TO_BASKET_OK: '<? echo GetMessageJS('CVP_ADD_TO_BASKET_OK'); ?>',
			CVP_TITLE_ERROR: '<? echo GetMessageJS('CVP_CATALOG_TITLE_ERROR') ?>',
			CVP_TITLE_BASKET_PROPS: '<? echo GetMessageJS('CVP_CATALOG_TITLE_BASKET_PROPS') ?>',
			CVP_TITLE_SUCCESSFUL: '<? echo GetMessageJS('CVP_ADD_TO_BASKET_OK'); ?>',
			CVP_BASKET_UNKNOWN_ERROR: '<? echo GetMessageJS('CVP_CATALOG_BASKET_UNKNOWN_ERROR') ?>',
			CVP_BTN_MESSAGE_SEND_PROPS: '<? echo GetMessageJS('CVP_CATALOG_BTN_MESSAGE_SEND_PROPS'); ?>',
			CVP_BTN_MESSAGE_CLOSE: '<? echo GetMessageJS('CVP_CATALOG_BTN_MESSAGE_CLOSE') ?>'
		});
	</script>
	<div class="ordered-block gifts">
		<div class="bx_item_list_you_looked_horizontal detail <? echo $templateData['TEMPLATE_CLASS']; ?>">
			<div class="common_product wrapper_block s_<?=$injectId;?> <?=($arParams["SHOW_UNABLE_SKU_PROPS"] != "N" ? "show_un_props" : "unshow_un_props");?>">
				<?if(empty($arParams['HIDE_BLOCK_TITLE']) || $arParams['HIDE_BLOCK_TITLE'] == 'N'){?>
					<div class="ordered-block__title font_lg">
						<?=($arParams['BLOCK_TITLE'] ? htmlspecialcharsbx($arParams['BLOCK_TITLE']) : GetMessage('SGP_TPL_BLOCK_TITLE_DEFAULT'));?>
					</div>
				<?}?>

				<div class="all_wrapp">
					<div class="content_inner tab owl-carousel owl-theme owl-bg-nav short-nav hidden-dots catalog_block" data-plugin-options='{"nav": true, "autoplay" : false, "autoplayTimeout" : "3000", "smartSpeed":1000, <?=(count($arResult["ITEMS"]) > 4 ? "\"loop\": true," : "")?> "responsiveClass": true, "responsive":{"0":{"items": 1},"600":{"items": 2},"768":{"items": 3},"992":{"items": 4}}}'>
						
							<?
							$elementEdit = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_EDIT');
							$elementDelete = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_DELETE');
							$elementDeleteParams = array('CONFIRM' => GetMessage('CVP_TPL_ELEMENT_DELETE_CONFIRM'));
							?>
							<?foreach($arResult['ITEMS'] as $key => $arItem){
								$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $elementEdit);
								$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $elementDelete, $elementDeleteParams);
								$arItem["strMainID"] = $this->GetEditAreaId($arItem['ID'])."g";
								$arItemIDs=CMax::GetItemsIDs($arItem);

								$strMeasure = '';
								$item_id = $arItem["ID"];

								$totalCount = CMax::GetTotalCount($arItem, $arParams);
								$arQuantityData = CMax::GetQuantityArray($totalCount, $arItemIDs["ALL_ITEM_IDS"]);
								if(!$arItem["OFFERS"]){
									if($arParams["SHOW_MEASURE"] == "Y" && $arItem["CATALOG_MEASURE"]){
										$arMeasure = CCatalogMeasure::getList(array(), array("ID" => $arItem["CATALOG_MEASURE"]), false, false, array())->GetNext();
										$strMeasure = $arMeasure["SYMBOL_RUS"];
									}
									$arAddToBasketData = CMax::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'small', $arParams);
								}
								elseif($arItem["OFFERS"]){
									$strMeasure = $arItem["MIN_PRICE"]["CATALOG_MEASURE_NAME"];
									if(!$arItem['OFFERS_PROP']){

										$arAddToBasketData = CMax::GetAddToBasketArray($arItem["OFFERS"][0], $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'small', $arParams);
									}
								}
								$elementName = ((isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arItem['NAME']);
								?>
								<div class="catalog_item item visible main_item_wrapper" id="<?=$arItem["strMainID"];?>">
									<div class="inner_wrap TYPE_2">
										<div class="image_wrapper_block">
											<?\Aspro\Functions\CAsproMaxItem::showStickers($arParams, $arItem, true);?>
											<?\Aspro\Functions\CAsproMaxItem::showImg($arParams, $arItem, false);?>
											<?if($bFastViewFull && $arParams['USE_FAST_VIEW'] != 'N' ):?>
												<div class="fast_view_button fast_view_button--full">
													<span title="<?=$fast_view_text?>" class="rounded2 font_upper_xs" data-event="jqm" data-param-form_id="fast_view" data-param-iblock_id="<?=$arParams["IBLOCK_ID"];?>" data-param-id="<?=$arItem["ID"];?>" data-param-item_href="<?=urlencode($arItem["DETAIL_PAGE_URL"]);?>" data-name="fast_view"><?=\CMax::showIconSvg("fw ", SITE_TEMPLATE_PATH."/images/svg/quickview".$typeSvg.".svg");?><?=$fast_view_text?></span>
												</div>
											<?endif;?>
											<?\Aspro\Functions\CAsproMaxItem::showDelayCompareBtn($arParams, $arItem, $arAddToBasketData, $totalCount, $bUseSkuProps, 'block', !$bFastViewFull, true, '_small', $currentSKUID, $currentSKUIBlock);?>
											<?if($arParams["SHOW_DISCOUNT_TIME"]=="Y"){?>
												<?$arDiscount=[]?>
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
												<?\Aspro\Functions\CAsproMax::showDiscountCounter($totalCount, $arDiscount, $arQuantityData, $arItem, $strMeasure, 'v2 grey', $item_id, true);?>
											<?}?>
										</div>
										<div class="item_info <?=$arParams["TYPE_SKU"]?>">
											<?if($arParams["SHOW_RATING"] == "Y"):?>
												<div class="rating">
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
												</div>
											<?endif;?>
											<div class="item-title">
												<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="dark_link option-font-bold font_sm"><span><?=$elementName?></span></a>
											</div>
											<div class="sa_block">
												<?=$arQuantityData["HTML"];?>
											</div>
											<div class="cost prices clearfix">
												<div class="icons-basket-wrapper offer_buy_block">
													<div class="button_block">
														<!--noindex-->
															<?=$arAddToBasketData["HTML"]?>
														<!--/noindex-->
													</div>
												</div>
												<?if( $arItem["OFFERS"]){?>
													<?\Aspro\Functions\CAsproMaxSku::showItemPrices($arParams, $arItem, $item_id, $min_price_id, $arItemIDs, ($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] == "Y" ? "N" : "Y"));?>
												<?}else{?>
													<?
													$item_id = $arItem["ID"];
													if(isset($arItem['PRICE_MATRIX']) && $arItem['PRICE_MATRIX']) // USE_PRICE_COUNT
													{?>
														<?if($arItem['ITEM_PRICE_MODE'] == 'Q' && count($arItem['PRICE_MATRIX']['ROWS']) > 1):?>
															<?=CMax::showPriceRangeTop($arItem, $arParams, GetMessage("CATALOG_ECONOMY"));?>
														<?endif;?>
														<?=CMax::showPriceMatrix($arItem, $arParams, $strMeasure, $arAddToBasketData);?>
													<?
													}
													elseif ( $arItem["PRICES"] )
													{
														$arCountPricesCanAccess = 0;
														$min_price_id=0;?>
														<?foreach($arItem["PRICES"] as $priceCode => $arTmpPrice)
														{
															$arItem["PRICES"][$priceCode]["DISCOUNT_VALUE"] = $arItem["PRICES"][$priceCode]["DISCOUNT_DIFF"];
															$arItem["PRICES"][$priceCode]["PRINT_DISCOUNT_VALUE"] = $arItem["PRICES"][$priceCode]["PRINT_DISCOUNT_DIFF"];
														}?>
														<?\Aspro\Functions\CAsproMaxItem::showItemPrices($arParams, $arItem["PRICES"], $strMeasure, $min_price_id, ($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] == "Y" ? "N" : "Y"));?>
													<?}?>
												<?}?>
											</div>
										</div>
										<div class="footer_button n-btn">
											<?if(!empty($arItem['OFFERS']) && isset($arSkuTemplate[$arItem['IBLOCK_ID']])){?>
												<?if(!empty($arItem['OFFERS_PROP'])){?>
													<div class="sku_props">
														<div class="bx_catalog_item_scu wrapper_sku sku_in_section" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['PROP_DIV']; ?>">
															<?foreach ($arSkuTemplate[$arItem['IBLOCK_ID']] as $code => $strTemplate){
																if (!isset($arItem['OFFERS_PROP'][$code]))
																	continue;
																echo '<div>', str_replace('#ITEM#_prop_', $arItemIDs["ALL_ITEM_IDS"]['PROP'], $strTemplate), '</div>';
															}?>
														</div>
														<?$arItemJSParams=CMax::GetSKUJSParams($arResult, $arParams, $arItem, "N", "Y");?>

														<script type="text/javascript">
															var <? echo $arItemIDs["strObName"]; ?> = new JCSaleGiftProduct(<? echo CUtil::PhpToJSObject($arItemJSParams, false, true); ?>);
														</script>
													</div>
												<?}?>
											<?}?>
											<?if(!$arItem["OFFERS"] || ($arItem["OFFERS"] && !$arItem['OFFERS_PROP'])):?>
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
											<?elseif($arItem["OFFERS"] && $arItem['OFFERS_PROP']):?>
												<div class="offer_buy_block buys_wrapp woffers" style="display:none;">
													<div class="counter_wrapp"></div>
												</div>
											<?endif;?>
										</div>
									</div>
								</div>
							<?}?>
						
					</div>
				</div>
			</div>
		</div>
	</div>
<?}?>
<?$frame->beginStub();?>
<?$frame->end();?>
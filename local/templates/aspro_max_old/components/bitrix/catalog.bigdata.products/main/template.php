<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$frame = $this->createFrame()->begin("");
$templateData = array(
	//'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css',
	'TEMPLATE_CLASS' => 'bx_'.$arParams['TEMPLATE_THEME']
);
$injectId = $arParams['UNIQ_COMPONENT_ID'];

if (isset($arResult['REQUEST_ITEMS']))
{
	// code to receive recommendations from the cloud
	CJSCore::Init(array('ajax'));

	// component parameters
	$signer = new \Bitrix\Main\Security\Sign\Signer;
	$signedParameters = $signer->sign(
		base64_encode(serialize($arResult['_ORIGINAL_PARAMS'])),
		'bx.bd.products.recommendation'
	);
	$signedTemplate = $signer->sign($arResult['RCM_TEMPLATE'], 'bx.bd.products.recommendation');

	?>

	<div id="<?=$injectId?>"></div>

	<script type="text/javascript">
		BX.ready(function(){
			bx_rcm_get_from_cloud(
				'<?=CUtil::JSEscape($injectId)?>',
				<?=CUtil::PhpToJSObject($arResult['RCM_PARAMS'])?>,
				{
					'parameters':'<?=CUtil::JSEscape($signedParameters)?>',
					'template': '<?=CUtil::JSEscape($signedTemplate)?>',
					'site_id': '<?=CUtil::JSEscape(SITE_ID)?>',
					'rcm': 'yes'
				}
			);
		});
	</script>
	<?
	$frame->end();
	return;

	// \ end of the code to receive recommendations from the cloud
}
if($arResult['ITEMS']){?>
	<?$arResult['RID'] = ($arResult['RID'] ? $arResult['RID'] : (\Bitrix\Main\Context::getCurrent()->getRequest()->get('RID') != 'undefined' ? \Bitrix\Main\Context::getCurrent()->getRequest()->get('RID') : '' ));?>
	<input type="hidden" name="bigdata_recommendation_id" value="<?=htmlspecialcharsbx($arResult['RID'])?>">
	<?$bRow = (isset($arParams['ROW']) && $arParams['ROW'] == 'Y');?>
	<?$bSlide = (isset($arParams['SLIDER']) && $arParams['SLIDER'] == 'Y');?>
	<?$bShowBtn = (isset($arParams['SHOW_BTN']) && $arParams['SHOW_BTN'] == 'Y');?>
	<div id="<?=$injectId?>_items" class="bigdata_recommended_products_items">
		<?if($arParams['TITLE_SLIDER']):?>
			<div class="font_md darken subtitle option-font-bold"><?=$arParams['TITLE_SLIDER'];?></div>
		<?endif;?>
		<div class="block-items<?=($bRow ? ' flexbox flexbox--row flex-wrap' : '');?><?=($bSlide ? ' owl-carousel owl-theme owl-bg-nav short-nav hidden-dots' : '');?> swipeignore"<?if($bSlide):?>data-plugin-options='{"nav": true, "autoplay" : false, "autoplayTimeout" : "3000", "margin": -1, "smartSpeed":1000, <?=(count($arResult["ITEMS"]) > 4 ? "\"loop\": true," : "")?> "responsiveClass": true, "responsive":{"0":{"items": 1},"600":{"items": 2},"768":{"items": 3},"992":{"items": 4}}}'<?endif;?>>
			<?foreach ($arResult['ITEMS'] as $key => $arItem){?>
				<?$strMainID = $this->GetEditAreaId($arItem['ID'] . $key);?>
				<div class="block-item bordered rounded3<?=($bSlide ? '' : ' box-shadow');?>">
					<div class="block-item__wrapper<?=($bShowBtn ? ' w-btn' : '');?> colored_theme_hover_bg-block" id="<?=$strMainID;?>">
						<div class="block-item__inner flexbox flexbox--row">
							<?
							$totalCount = CMax::GetTotalCount($arItem, $arParams);
							$arQuantityData = CMax::GetQuantityArray($totalCount);
							$arItem["FRONT_CATALOG"]="Y";
							$arItem["RID"]=$arResult["RID"];
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
							$arItem["DETAIL_PAGE_URL"] .= ($arResult["RID"] ? '?RID='.$arResult["RID"] : '');
							?>

							<div class="block-item__image block-item__image--wh80">
								<?$arItem["BIG_DATA"] = "Y";?>
								<?\Aspro\Functions\CAsproMaxItem::showImg($arParams, $arItem, false);?>
							</div>
							<div class="block-item__info item_info">
								<div class="block-item__title">
									<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="dark-color font_xs"><span><?=$elementName?></span></a>
								</div>
								<div class="block-item__cost cost prices clearfix">
									<?if($arItem["OFFERS"]):?>
										<?\Aspro\Functions\CAsproMaxSku::showItemPrices($arParams, $arItem, $item_id, $min_price_id, array(), 'Y');?>
									<?else:?>
										<?
										if(isset($arItem['PRICE_MATRIX']) && $arItem['PRICE_MATRIX']) // USE_PRICE_COUNT
										{?>
											<?if($arItem['ITEM_PRICE_MODE'] == 'Q' && count($arItem['PRICE_MATRIX']['ROWS']) > 1):?>
												<?=CMax::showPriceRangeTop($arItem, $arParams, GetMessage("CATALOG_ECONOMY"));?>
											<?endif;?>
											<?=CMax::showPriceMatrix($arItem, $arParams, $strMeasure, $arAddToBasketData);?>
										<?
										}
										elseif(isset($arItem["PRICES"]))
										{?>
											<?\Aspro\Functions\CAsproMaxItem::showItemPrices($arParams, $arItem["PRICES"], $strMeasure, $min_price_id, 'Y');?>
										<?}?>
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
	<?\Aspro\Functions\CAsproMax::showBonusComponentList($arResult);?>
<?}
$frame->end();?>

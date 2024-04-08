<?
global $arTheme, $arRegion, $arBasketPrices;
// get actual basket counters from session
$arCounters = CMax::getBasketCounters();;?>
<div class="basket_fly">
	<div class="wrap_cont">
		<div class="opener">
			<?if(CMax::getShowBasket()):?>
			<div title="<?=$arBasketPrices['BASKET_SUMM_TITLE']?>" data-type="AnDelCanBuy" class="colored_theme_hover_text basket_count small clicked empty">
				<a href="<?=(is_array($arCounters['READY']['HREF']) ? $arCounters['READY']['HREF']['VALUE'] : $arCounters['READY']['HREF']);?>"></a>
				<div class="wraps_icon_block basket">
					<?=CMax::showSpriteIconSvg(SITE_TEMPLATE_PATH."/images/svg/header_icons_srite.svg#basket", "down ", ['WIDTH' => 20,'HEIGHT' => 16]);?>
					<div class="count <?=($arCounters['READY']['COUNT'] ? '' : 'empty_items');?>">
						<span class="colored_theme_bg">
							<span class="items">
								<span><?=$arCounters['READY']['COUNT'];?></span>
							</span>
						</span>
					</div>
				</div>
			</div>
			<?endif;?>

			<?if(CMax::GetFrontParametrValue('CATALOG_DELAY') != 'N'):?>
				<div title="<?=$arCounters['FAVORITE']['TITLE']?>" class="colored_theme_hover_text wish_count small empty">
					<a href="<?=$arCounters['FAVORITE']['HREF'];?>"></a>
					<div class="wraps_icon_block delay">
						<?=CMax::showSpriteIconSvg(SITE_TEMPLATE_PATH."/images/svg/header_icons_srite.svg#chosen", "down ", ['WIDTH' => 20,'HEIGHT' => 16]);?>
						<div class="count basket-link delay <?=($arCounters['FAVORITE']['COUNT'] ? '' : 'empty_items');?>">
							<span  class="colored_theme_bg">
								<span class="items">
									<span class="js-count"><?=$arCounters['FAVORITE']['COUNT'];?></span>
								</span>
							</span>
						</div>
					</div>
				</div>
			<?endif;?>

			<?if(CMax::GetFrontParametrValue('CATALOG_COMPARE') != 'N'):?>
				<div title="<?=$arCounters['COMPARE']['TITLE']?>" class="colored_theme_hover_text compare_count small">
					<a href="<?=(is_array($arCounters['COMPARE']['HREF']) ? $arCounters['COMPARE']['HREF']['VALUE'] : $arCounters['COMPARE']['HREF']);?>"></a>
					<div id="compare_fly" class="wraps_icon_block compare <?=($arCounters['COMPARE']['COUNT'] ? '' : 'empty_block');?>">
						<?=CMax::showSpriteIconSvg(SITE_TEMPLATE_PATH."/images/svg/header_icons_srite.svg#compare", "down ", ['WIDTH' => 18,'HEIGHT' => 17]);?>
						<div class="count <?=($arCounters['COMPARE']['COUNT'] ? '' : 'empty_items');?>">
							<span class="colored_theme_bg">
								<span class="items">
									<span><?=$arCounters['COMPARE']['COUNT'];?></span>
								</span>
							</span>
						</div>
					</div>
				</div>
			<?endif;?>

			<?=\Aspro\Functions\CAsproMax::showSideFormLinkIcons()?>
		</div>
		<div class="basket_sort">
			<span class="basket_title"><?=GetMessage('T_BASKET')?></span>
		</div>
	</div>
</div>
<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("header-cart");?>
	<?$basketType = (isset($arTheme['ORDER_BASKET_VIEW']['VALUE']) ? $arTheme['ORDER_BASKET_VIEW']['VALUE'] : $arTheme['ORDER_BASKET_VIEW']);?>
	<?if($basketType != "NORMAL"):?>
		<script type="text/javascript">
			arBasketAsproCounters = <?=CUtil::PhpToJSObject($arCounters, false)?>;
			SetActualBasketFlyCounters();
		</script>
	<?endif;?>
<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("header-cart", "");?>

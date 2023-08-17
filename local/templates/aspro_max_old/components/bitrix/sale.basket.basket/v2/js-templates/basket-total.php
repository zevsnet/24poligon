<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 */
?>
<script id="basket-total-template" type="text/html">
	<div class="basket-checkout-container" data-entity="basket-checkout-aligner">
		
		<div class="basket-checkout-section">
			<div class="basket-checkout-section-inner">
				<div class="basket-checkout-section-left">
					<div class="basket-checkout-block basket-checkout-total">
						<div class="basket-checkout-block-total-inner">
							<div class="basket-checkout-total-title"><?=Loc::getMessage('SBB_TOTAL')?>:</div>
							<div class="basket-coupon-block-total-price-current" data-entity="basket-total-price">
								{{{PRICE_FORMATED}}}
							</div>
							<div class="basket-checkout-block-total-description hidden">
								{{#WEIGHT_FORMATED}}
									<?=Loc::getMessage('SBB_WEIGHT')?>: {{{WEIGHT_FORMATED}}}
									{{#SHOW_VAT}}<br>{{/SHOW_VAT}}
								{{/WEIGHT_FORMATED}}
								{{#SHOW_VAT}}
									<?=Loc::getMessage('SBB_VAT')?>: {{{VAT_SUM_FORMATED}}}
								{{/SHOW_VAT}}
							</div>
						</div>
					</div>

					<div class="basket-checkout-block basket-checkout-total-price">
						<div class="basket-checkout-total-price-inner">
							{{#SERVICES_COUNT}}
								<div class="basket-checkout-info">
									<div class="basket-checkout-info__name font_xs"><?=Loc::getMessage('SBB_BASKET_ITEMS_CNT')?> {{ITEMS_COUNT}} <?=Loc::getMessage('SBB_BASKET_MEASURE')?></div>
									<div class="basket-checkout-info__value font_xs">{{{ITEMS_SUMM}}}</div>
								</div>
								<div class="basket-checkout-info">
									<div class="basket-checkout-info__name font_xs"><?=Loc::getMessage('SBB_BASKET_SERVICES_CNT')?> {{SERVICES_COUNT}} <?=Loc::getMessage('SBB_BASKET_MEASURE')?></div>
									<div class="basket-checkout-info__value font_xs">{{{SERVICES_SUMM}}}</div>
								</div>
							{{/SERVICES_COUNT}}

							{{#DISCOUNT_PRICE_FORMATED}}
								<div class="basket-checkout-info basket-coupon-total-price-difference">
									<div class="basket-checkout-info__name font_xs"><?=Loc::getMessage('SBB_BASKET_ITEM_ECONOMY')?></div>
									<div class="basket-checkout-info__value font_xs">{{{DISCOUNT_PRICE_FORMATED}}}</div>
								</div>
							{{/DISCOUNT_PRICE_FORMATED}}
						</div>
					</div>

					<?if ($arParams['HIDE_COUPON'] !== 'Y'){?>
						<div class="basket-coupon-section">
							<div class="basket-coupon-block-field">
								<div class="form">
									<div class="form-group" style="position: relative;">
										<input type="text" placeholder="<?=Loc::getMessage('SBB_COUPON_ENTER')?>" class="form-control" id="" placeholder="" data-entity="basket-coupon-input">
										<span class="basket-coupon-block-coupon-btn"><?=CMax::showIconSvg("coupon", SITE_TEMPLATE_PATH.'/images/svg/catalog/arrow_coupon.svg');?></span>
									</div>
								</div>
							</div>
						</div>
					<?}?>
					<?if ($arParams['HIDE_COUPON'] !== 'Y'){?>
						<div class="basket-coupon-alert-section">
							<div class="basket-coupon-alert-inner {{#HAS_COUPON}}has_coupon{{/HAS_COUPON}}">
								{{#COUPON_LIST}}
								<div class="basket-coupon-alert text-{{CLASS}}">
									<span class="basket-coupon-text">
										<strong>{{COUPON}}</strong> {{JS_CHECK_CODE}}
										{{#DISCOUNT_NAME}}({{DISCOUNT_NAME}}){{/DISCOUNT_NAME}}
									</span>
									<span class="close-link" data-entity="basket-coupon-delete" data-coupon="{{COUPON}}">
										<?=Loc::getMessage('SBB_DELETE')?>
									</span>
								</div>
								{{/COUPON_LIST}}
							</div>
						</div>
					<?}?>
				</div>

				<div class="basket-checkout-section-right">

					<?if (CMax::GetFrontParametrValue('SHOW_ONECLICKBUY_ON_BASKET_PAGE') === 'Y'):?>
						<div class="basket-checkout-block basket-checkout-btn ocb">
							<span class="oneclickbuy btn btn-lg fast_order btn-transparent-border-color btn-wide" onclick="oneClickBuyBasket()">
								<?=Loc::getMessage('SBB_ONE_CLICK_BUY')?>
							</span>
						</div>
					<?endif;?>

					<div class="basket-checkout-block basket-checkout-btn checkout-order">
						<button class="btn btn-lg btn-default basket-btn-checkout{{#DISABLE_CHECKOUT}} disabled{{/DISABLE_CHECKOUT}}"
							data-entity="basket-checkout-button">
							<?=Loc::getMessage('SBB_ORDER')?>
						</button>
					</div>
				</div>
			</div>
		</div>

		
	</div>
</script>
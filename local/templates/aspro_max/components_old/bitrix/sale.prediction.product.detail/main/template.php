<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */

$this->setFrameMode(true);

if (isset($arResult['REQUEST_ITEMS']) || isset($arParams['REQUEST_ITEMS']))
{
	CJSCore::Init(array('ajax'));
	$injectId = 'sale_gift_product_'.rand();

	// component parameters
	$signer = new \Bitrix\Main\Security\Sign\Signer;
	$signedTemplate = $signer->sign($arResult['RCM_TEMPLATE'] ? $arResult['RCM_TEMPLATE'] : $arParams['RCM_TEMPLATE'], 'bx.sale.prediction.product.detail');

	$arParams['INJECT_ID'] = $arResult['_ORIGINAL_PARAMS']['INJECT_ID'] = $injectId;
	unset($arParams['REQUEST_ITEMS'], $arParams['RCM_TEMPLATE'], $arResult['_ORIGINAL_PARAMS']['REQUEST_ITEMS'], $arResult['_ORIGINAL_PARAMS']['RCM_TEMPLATE']);

	$signedParameters = $signer->sign(
		base64_encode(serialize($arResult['_ORIGINAL_PARAMS'])),
		'bx.sale.prediction.product.detail'
	);

	$frame = $this->createFrame()->begin("");
	?>
	<span id="<?=$injectId?>" class="sale_prediction_product_detail_container"></span>
	<script>
	if(typeof obMaxPredictions === 'undefined'){
		var CMaxPredictions = function(){
			this.arData = {};
			this.bindEvents();
		}

		CMaxPredictions.prototype.bindEvents = function(){
			var _this = this;

			BX.ready(function(){
				BX.addCustomEvent('onHasNewPrediction', function(html, injectId){
					
					// $('#simple-prediction').remove();
					// predictionWindow = false;

					if (predictionWindow) {
						predictionWindow.destroy();
					}
					if(predictionHideTimeout){
						clearTimeout(predictionHideTimeout);
						predictionHideTimeout = false;
					}

					if(_this.arData && typeof _this.arData[injectId] !== 'undefined'){
						_this.arData[injectId].html = html;
						if(html.length){
							_this.show(injectId);
						}
						else{
							_this.hide(injectId);
						}
					}
				});

				$(document).on('mouseenter', '#simple-prediction', function(){
					if(predictionHideTimeout){
						clearTimeout(predictionHideTimeout);
						predictionHideTimeout = false;
					}
				});

				$(document).on('mouseleave', '#simple-prediction', function(){
					if (predictionWindow) {
						predictionWindow.destroy();
					}
					if(predictionHideTimeout){
						clearTimeout(predictionHideTimeout);
						predictionHideTimeout = false;
					}
				});
			});
		}

		CMaxPredictions.prototype.add = function(data){
			if(typeof data === 'object' && typeof data.injectId === 'string' && typeof data.giftAjaxData === 'object'){
				this.arData[data.injectId] = {
					injectId: data.injectId,
					giftAjaxData: data.giftAjaxData,
					html: ''
				}

				//console.log('added prediction', data.injectId);

				return data.injectId;
			}

			return false;
		}

		CMaxPredictions.prototype.remove = function(i){
			if(this.arData && typeof this.arData[i] !== 'undefined'){
				this.hide(i);
				delete(this.arData[i]);

				//console.log('removed prediction', i);
			}
		}

		CMaxPredictions.prototype.removeAll = function(){
			if(this.arData){
				var keys = Object.keys(this.arData);
				for(i in keys){
					this.remove(keys[i]);
				}
			}

			this.arData = {};
		}

		CMaxPredictions.prototype.get = function(i){
			if(this.arData && typeof this.arData[i] !== 'undefined'){
				return this.arData[i];
			}
		}

		CMaxPredictions.prototype.getAll = function(i){
			return this.arData;
		}

		CMaxPredictions.prototype.show = function(i){
			var _this = this;

			if(this.arData && typeof this.arData[i] !== 'undefined'){
				var $inject = $('#' + i);

				if($inject.length && _this.arData[i].html.length){
					var $element = $inject.closest('.catalog_detail');
					if($element.length){
						// console.log('show prediction', i);

						var bFastView = $element.closest('#fast_view_item').length > 0;
						if(!bFastView){
							$('#headerfixed .btn.has_prediction').removeClass('has_prediction');
						}
						$element.find('.has_prediction').removeClass('has_prediction');

						if(bFastView){
							var $buttons1 = $element.find('.counter_wrapp .button_block .btn.to-cart,.counter_wrapp .button_block .btn.in-cart');				
							_show($buttons1);
						}
						else{
							if($element.find('.list-offers,.offer_buy_block').length > 0){
								var $buttons1 = $element.find('.list-offers .counter_wrapp .btn.to-cart,.list-offers .counter_wrapp .btn.in-cart,.btn.slide_offer,.offer_buy_block .counter_wrapp .btn.to-cart,.offer_buy_block .counter_wrapp .btn.in-cart');
								_show($buttons1);

								var $buttons2 = $('#headerfixed .btn.more, #headerfixed .btn.slide_offer');
								_show($buttons2);
							}
							else{
								var $buttons1 = $element.find('.info_item .middle-info-wrapper .buy_block .button_block .btn.to-cart,.info_item .middle-info-wrapper .buy_block .button_block .btn.to-cart, .main_item_wrapper .js-prices-in-side .buy_block .button_block .btn.to-cart, .main_item_wrapper .js-prices-in-side .buy_block .button_block .btn.in-cart');
								_show($buttons1);

								var $buttons2 = $('#headerfixed .btn.to-cart,#headerfixed .btn.in-cart');
								_show($buttons2);
							}
						}

						function _show($buttons){
							if($buttons.length){
								$buttons.each(function(index, button) {
									button = $(button);
									if( !button.children('.svg-inline-prediction').length && !button.hasClass('in-cart') ) {
										var  _thisIcon = this;
										var isShadow = button.closest('.shadowed-block').length;
										var isSquare = (isMobile && arAsproOptions.THEME.FIXED_BUY_MOBILE == 'Y') || isShadow;
										var predictionIconHTML = '';
										if(isSquare) {
											predictionIconHTML = $(<?=CUtil::PhpToJSObject(CMax::showIconSvg("prediction", SITE_TEMPLATE_PATH."/images/svg/prediction_square.svg"));?>);
											predictionIconHTML.css({
												right: '-2px',
												top:   '-1px',
												padding: '0 0 8px 8px',
											});
										} else {
											predictionIconHTML = $(<?=CUtil::PhpToJSObject(CMax::showIconSvg("prediction", SITE_TEMPLATE_PATH."/images/svg/prediction.svg"));?>);
											predictionIconHTML.css({
												right: '-15px',
												top: '-15px',
												padding: '5px',
											});
										}
										button.append(predictionIconHTML);
			
										button.on('click', '.svg-inline-prediction', function(e) {
											if( isMobile ) {
												if (predictionWindow) {
													predictionWindow.destroy();
												}

												if(predictionHideTimeout){
													clearTimeout(predictionHideTimeout);
													predictionHideTimeout = false;
												}
												predictionWindow = new BX.PopupWindow('simple-prediction', _thisIcon, {
													offsetTop: (isSquare ? -5 : -15),
													bindOptions: {
														position: 'top',
													},
													content:
													'<div class="catalog-element-popup-inner">' +
													_this.arData[i].html +
													'</div>',
													closeIcon: true,
													closeByEsc: false,
													angleMinRight: 0,
													angleMaxRight: 0,
													angle: {
														position: 'bottom',
													},
													events: {
														onAfterPopupShow: function() {
															var popup = $(predictionWindow.popupContainer);
															if(arAsproOptions.THEME.FIXED_BUY_MOBILE == 'Y') {
																popup.css({
																	left: '16px',
																	right: '16px',
																});
																$(predictionWindow.angle.element).css({
																	left: 'auto',
																	right: '10px',
																});
															} else {
																var parent = button.closest('.buy_block');
																if( !parent.length ){
																	parent = button.closest('.counter_wrapp'); // sku 2
																}
																var parentOffset = parent[0].getBoundingClientRect();
																popup.css({
																	left: parentOffset.left + (isShadow ? -14 : 0),
																	right: 'calc(100% - ' + (parentOffset.left + parentOffset.width + (isShadow ? 14 : 0)) + 'px)',
																});
																$(predictionWindow.angle.element).css({
																	left: 'auto',
																	right: '10px',
																});
															}

															var angleOffset = predictionWindow.angle.element.getBoundingClientRect();
															var anglePosition = angleOffset.top + angleOffset.height - 11;
															var iconOffset = _thisIcon.getBoundingClientRect();
															var needChange = iconOffset.top - (isSquare ? 6 : 6) - anglePosition;
															
															var popupTop = popup.css('top').replace('px', '');
															if(needChange != 0) {
																popup.css({
																	top: +popupTop + needChange + 'px',
																});
															}
														},
													}
												});											

												predictionWindow.show();
												e.stopPropagation();
												e.preventDefault();
											}
										});
									}
								});
								
								$buttons.addClass('has_prediction');

								$buttons.unbind('mouseenter');
								$buttons.unbind('mouseleave');
								$buttons.mouseenter(function(){
									if( !isMobile ) {
										if (predictionWindow) {
											predictionWindow.destroy();
										}

										if(predictionHideTimeout){
											clearTimeout(predictionHideTimeout);
											predictionHideTimeout = false;
										}

										predictionWindow = new BX.PopupWindow('simple-prediction', this, {
											offsetLeft: 40,
											offsetTop: -5,
											bindOptions: {
												position: 'top',
											},
											content:
											'<div class="catalog-element-popup-inner">' +
											_this.arData[i].html +
											'</div>',
											closeIcon: false,
											closeByEsc: false,
											angle: {
												position: 'bottom'
											}
										});

										predictionWindow.show();
									}
								}).mouseleave(function(){
									if( !isMobile ) {
										if(predictionWindow){
											if(predictionHideTimeout){
												clearTimeout(predictionHideTimeout);
												predictionHideTimeout = false;
											}

											predictionHideTimeout = setTimeout(function(){
												predictionWindow.destroy();
											}, 500);
										}
									}
								});
							}
						}
					}
				}
				else{
					this.hide(i);
				}
			}
		}

		CMaxPredictions.prototype.showAll = function(){
			if(this.arData){
				var keys = Object.keys(this.arData);
				for(i in keys){
					this.show(keys[i]);
				}
			}
		}

		CMaxPredictions.prototype.hide = function(i){
			if(this.arData && typeof this.arData[i] !== 'undefined'){
				var $inject = $('#' + i);

				if($inject.length){
					var $element = $inject.closest('.catalog_detail');
					if($element.length){
						var bFastView = $element.closest('#fast_view_item').length > 0;
						if(!bFastView){
							$('#headerfixed .btn.has_prediction').unbind('mouseenter');
							$('#headerfixed .btn.has_prediction').unbind('mouseleave');
							$('#headerfixed .btn.has_prediction').removeClass('has_prediction');
						}
						$element.find('.has_prediction').unbind('mouseenter');
						$element.find('.has_prediction').unbind('mouseleave');
						$element.find('.has_prediction').removeClass('has_prediction');
					}
				}

				//console.log('hided prediction', i);
			}
		}

		CMaxPredictions.prototype.hideAll = function(){
			if(this.arData){
				var keys = Object.keys(this.arData);
				for(i in keys){
					this.hide(keys[i]);
				}
			}
		}

		CMaxPredictions.prototype.update = function(i){
			if(this.arData && typeof this.arData[i] !== 'undefined'){
				var $inject = $('#' + i);

				if($inject.length){
					bx_sale_prediction_product_detail_load(
						this.arData[i].injectId,
						this.arData[i].giftAjaxData
					);

					//console.log('sended prediction', i);
				}
				else{
					this.remove(i);
				}
			}
		}

		CMaxPredictions.prototype.updateAll = function(){
			if(this.arData){
				var keys = Object.keys(this.arData);
				for(i in keys){
					this.update(keys[i]);
				}
			}
		}

		var obMaxPredictions = new CMaxPredictions();
		var predictionWindow = false;
		var predictionHideTimeout = false;
		var showPredictions = function(){
			obMaxPredictions.showAll();
		}
		var updatePredictions = function(){
			obMaxPredictions.updateAll();
		}
	}

	BX.ready(function(){
		var injectId = '<?=CUtil::JSEscape($injectId)?>';
		var giftAjaxData = {
			'parameters':'<?=CUtil::JSEscape($signedParameters)?>',
			'template': '<?=CUtil::JSEscape($signedTemplate)?>',
			'site_id': '<?=CUtil::JSEscape($component->getSiteId())?>'
		};

		obMaxPredictions.add({
			injectId: injectId,
			giftAjaxData: giftAjaxData,
		});

		obMaxPredictions.update(injectId);
	});
	</script>
	<?
	$frame->end();
	return;
}
else
{
	// fix &#8381; currency
	if(preg_match_all('/\&((\d+\.\d+)|(\d+))(8381);/', $arResult['PREDICTION_TEXT'], $arMatches)){
		foreach($arMatches[0] as $i => $match){
			$arResult['PREDICTION_TEXT'] = str_replace(
				// '&'.$arMatches[3][$i].'8381;',
				$match,
				'&#8381;',
				$arResult['PREDICTION_TEXT']
			);
		}
	}
	
	?>
	<script>
	BX.ready(function () {
		BX.onCustomEvent('onHasNewPrediction', ['<?=(!empty($arResult['PREDICTION_TEXT']) ? \CUtil::JSEscape($arResult['PREDICTION_TEXT']) : '')?>', '<?=CUtil::JSEscape($arParams['INJECT_ID'])?>']);
	});
	</script>
	<?
}
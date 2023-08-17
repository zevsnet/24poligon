<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<?use \Bitrix\Main\Localization\Loc,
		\Aspro\Functions\CAsproMax;?>
<?if($arResult['ITEMS']):?>
	<?$isAjax = $arParams['IS_AJAX'];?>
	<?$bSlider = ($arParams['SLIDER'] && $arParams['SLIDER'] == 'Y');
	$bSliderWait = $bSlider && isset($arParams['SLIDER_WAIT']) && $arParams['SLIDER_WAIT'] == 'Y';
	$bCarousel = ($arParams['MOBILE_CAROUSEL'] && $arParams['MOBILE_CAROUSEL'] == 'Y');
	?>
	<?$basketUrl = CMax::GetFrontParametrValue('BASKET_PAGE_URL');
	$servicesMode = isset($arParams["SERVICES_MODE"]) && $arParams["SERVICES_MODE"] === 'Y';
	?>
	<?if(!$isAjax):?>
	<div class="item-views list list-type-block wide_img list-news1 <?=($servicesMode ? 'services-mode' : '')?> <?=($arParams['IMAGE_POSITION'] ? 'image_'.$arParams['IMAGE_POSITION'] : '')?><?=($arParams['LINKED_MODE'] == 'Y' ? ' compact-view' : '')?> <?=($arParams['LINKED_MODE'] != 'Y' &&  $arParams['SMALL_IMAGE_MODE'] ? ' small-image-view' : '')?> <?=$templateName;?>-template">

		<?
		$bHasSection = false;
		if($arParams['PARENT_SECTION'] && (array_key_exists('SECTIONS', $arResult) && $arResult['SECTIONS']))
		{
			if(isset($arResult['SECTIONS'][$arParams['PARENT_SECTION']]) && $arResult['SECTIONS'][$arParams['PARENT_SECTION']])
				$bHasSection = true;
		}
		if($bHasSection)
		{
			// edit/add/delete buttons for edit mode
			$arSectionButtons = CIBlock::GetPanelButtons($arResult['SECTIONS'][$arParams['PARENT_SECTION']]['IBLOCK_ID'], 0, $arResult['SECTIONS'][$arParams['PARENT_SECTION']]['ID'], array('SESSID' => false, 'CATALOG' => true));
			$this->AddEditAction($arResult['SECTIONS'][$arParams['PARENT_SECTION']]['ID'], $arSectionButtons['edit']['edit_section']['ACTION_URL'], CIBlock::GetArrayByID($arResult['SECTIONS'][$arParams['PARENT_SECTION']]['IBLOCK_ID'], 'SECTION_EDIT'));
			$this->AddDeleteAction($arResult['SECTIONS'][$arParams['PARENT_SECTION']]['ID'], $arSectionButtons['edit']['delete_section']['ACTION_URL'], CIBlock::GetArrayByID($arResult['SECTIONS'][$arParams['PARENT_SECTION']]['IBLOCK_ID'], 'SECTION_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			?>
			<div class="section" id="<?=$this->GetEditAreaId($arResult['SECTIONS'][$arParams['PARENT_SECTION']]['ID'])?>">
			<?
		}?>
		<?if($bSlider):?>
			<div class="items row margin0 <?=($bSliderWait ? ' owl-carousel-wait loader_circle ' : ' owl-carousel ')?> owl-theme owl-sm-nav owl-bg-nav swipeignore" data-plugin-options='{"nav": true, "autoplay" : false, "rewind": true, "smartSpeed":1000, "responsiveClass": true, "responsive":{"0":{"items": 1},"600":{"items": 2}}}'>
		<?else:?>
			<?if($arParams['SHOW_TITLE'] == 'Y' && $arParams['TITLE']):?>
				<div class="ordered-block goods cur with-title1">
					<div class="ordered-block__title option-font-bold font_lg"><?=$arParams['TITLE']?></div>
			<?endif;?>
			<div class="items row <?=$bCarousel ? "swipeignore mobile-overflow mobile-margin-16 mobile-compact" : ""?>">
		<?endif?>
			<?// show section items?>
	<?endif;?>
			<?
				$count=count($arResult['ITEMS']);
				$current=0;
			?>
			<?foreach($arResult['ITEMS'] as $i => $arItem):?>		    
				<?
				$current++;
				// edit/add/delete buttons for edit mode
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				// use detail link?
				$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen($arItem['DETAIL_TEXT']) ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1) : true);
				$bImage = isset($arItem['FIELDS']['PREVIEW_PICTURE']) && strlen($arItem['PREVIEW_PICTURE']['SRC']);
				$bImageDetail = isset($arItem['FIELDS']['DETAIL_PICTURE']) && strlen($arItem['DETAIL_PICTURE']['SRC']);
				$imageSrc = ($bImage ? $arItem['PREVIEW_PICTURE']['SRC'] : false);
				$imageDetailSrc = ($bImageDetail ? $arItem['DETAIL_PICTURE']['SRC'] : false);
				// show active date period
				$bActiveDate = ( isset($arItem['DISPLAY_PROPERTIES']['PERIOD']) && strlen($arItem['DISPLAY_PROPERTIES']['PERIOD']['VALUE']) ) || ($arItem['DISPLAY_ACTIVE_FROM'] && in_array('DATE_ACTIVE_FROM', $arParams['FIELD_CODE']));
				$bDiscountCounter = ($arItem['ACTIVE_TO'] && in_array('ACTIVE_TO', $arParams['FIELD_CODE']));
				$bShowDopBlock = (isset($arItem['DISPLAY_PROPERTIES']['SALE_NUMBER']) && $arItem['DISPLAY_PROPERTIES']['SALE_NUMBER']['VALUE'] || $bDiscountCounter);
				$bHideSectionName = isset($arParams['HIDE_SECTION_NAME']) && ($arParams['HIDE_SECTION_NAME'] == "Y");
				$bShowSectionName = isset($arResult['SECTIONS'][$arItem['IBLOCK_SECTION_ID']]) && strlen($arResult['SECTIONS'][$arItem['IBLOCK_SECTION_ID']]['NAME']) && !$bHideSectionName;
				$noProps = true;
				?>
				
				<?
				$dop_class = '';
				if($arParams['USE_SECTIONS_TABS']=='Y'){
					if(isset($arItem['SECTIONS']) && $arItem['SECTIONS'])
					{
						foreach($arItem['SECTIONS'] as $id => $name)
						{
							$dop_class .= ' s-'.$id;
						}
					}
				}

				if($arParams['USE_DATE_MIX_TABS']=='Y'){
					if($arItem['ACTIVE_FROM'])
					{
						if($arDateTime = ParseDateTime($arItem['ACTIVE_FROM'], FORMAT_DATETIME))
							$dop_class .= ' d-'.$arDateTime['YYYY'];
					}
				}
				?>
				<?if($bSlider):?>
					<div class="item_wrap colored_theme_hover_bg-block">
				<?else:?>
					<div class="col-md-12 <?=$dop_class;?> <?=($bSlider ? '' : '');?> <?=$bCarousel ? "item-width-261" : ""?>" data-ref="mixitup-target">
						<div class="item_wrap colored_theme_hover_bg-block box-shadow rounded3 <?=($arParams['BORDERED']=='Y' ? 'bordered-block ' : '')?> <?=$bCarousel ? "item" : ""?>" >
				<?endif;?>
						<div class="item <?=$servicesMode ? 'js-notice-block' : ''?> noborder<?=($bImage ? '' : ' wti')?><?=($bActiveDate ? ' wdate' : '')?> clearfix" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
							<?if($bImage):?>
								<div class="image shine nopadding <?=$servicesMode ? 'js-notice-block__image' : ''?>">
									<?if($bDetailLink):?>
										<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
									<?endif;?>
										<img src="<?=\Aspro\Functions\CAsproMax::showBlankImg($imageSrc);?>" alt="<?=($bImage ? $arItem['PREVIEW_PICTURE']['ALT'] : $arItem['NAME'])?>" title="<?=($bImage ? $arItem['PREVIEW_PICTURE']['TITLE'] : $arItem['NAME'])?>" class="img-responsive lazy" data-src="<?=$imageSrc?>" />
									<?if($bDetailLink):?>
										</a>
									<?endif;?>
								</div>
							<?endif;?>
							<div class="body-info <?=$bShowSectionName? 'with-section': '';?> <?=($bDetailLink) ? 'has-link':'';?>">
								<?if($bShowSectionName || $bActiveDate):?>
									<div class="top_title_wrap">
										<?// section title?>
										<?if($bShowSectionName):?>
											<span class="section-name-block muted font_upper">
												<?=$arResult['SECTIONS'][$arItem['IBLOCK_SECTION_ID']]['NAME']?>
											</span>
											<?if($arParams['LINKED_MODE'] == 'Y' && $bActiveDate):?>
												<span class="date-separate font_upper muted">&mdash;</span>
											<?endif;?>
										<?endif;?>

										<?// date active period?>
										<?if($bActiveDate):?>
											<div class="<?=(strlen($bShowSectionName) && $arParams['LINKED_MODE'] != 'Y') ? ' period-block-bottom darken ' : ' period-block muted ';?> <?=($arParams['LINKED_MODE'] == 'Y' ? 'font_upper' : 'font_xs')?> ncolor <?=($arItem['ACTIVE_TO'] ? 'red' : '');?> ">
												<?if($arParams['SALE_MODE'] == 'Y'):?>
													<?=CMax::showIconSvg("sale", SITE_TEMPLATE_PATH.'/images/svg/icon_discount.svg', '', '', true, false);?>
												<?endif;?>
												<?if(array_key_exists('PERIOD', $arItem['DISPLAY_PROPERTIES']) && strlen($arItem['DISPLAY_PROPERTIES']['PERIOD']['VALUE'])):?>
													<span class="date"><?=$arItem['DISPLAY_PROPERTIES']['PERIOD']['VALUE']?></span>
												<?else:?>
													<span class="date"><?=$arItem['DISPLAY_ACTIVE_FROM']?></span>
												<?endif;?>
											</div>
										<?endif;?>
									</div>
								<?endif;?>
								
								<?// element name?>
								<?if(strlen($arItem['FIELDS']['NAME'])):?>
									<div class="title <?=$servicesMode ? 'js-notice-block__title' : ''?> <?=($bSlider ? 'font_sm' : 'font_mlg');?>">
										<?if($bDetailLink):?><a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="dark-color"><?endif;?>
											<?=$arItem['NAME']?>
										<?if($bDetailLink):?></a><?endif;?>
									</div>
								<?endif;?>

								<?// element preview text?>
								<?if(strlen($arItem['FIELDS']['PREVIEW_TEXT'])):?>
									<div class="previewtext font_sm muted777 line-h-165">
										<?if($arItem['PREVIEW_TEXT_TYPE'] == 'text'):?>
											<p><?=$arItem['FIELDS']['PREVIEW_TEXT']?></p>
										<?else:?>
											<?=$arItem['FIELDS']['PREVIEW_TEXT']?>
										<?endif;?>
									</div>
								<?endif;?>

								<?// element display properties?><?//echo '<pre>',var_dump($arItem['MIDDLE_PROPS']),'</pre>';?>
								<?if($arItem['DISPLAY_PROPERTIES']):?>
									<?if(array_key_exists('MIDDLE_PROPS', $arItem) && $arItem['MIDDLE_PROPS']):?>
										<div class="middle_properties">
											<?foreach($arItem['MIDDLE_PROPS'] as $PCODE => $arProperty):?>
												<div class="middle_prop">
													<div class="title-prop font_upper muted777"><?=$arProperty['NAME']?></div>
													<div class="value font_sm darken">
														<?if($PCODE == 'SITE'):?>
															<!--noindex-->
															<a href="<?=(strpos($arProperty['VALUE'], 'http') === false ? 'http://' : '').$arProperty['VALUE'];?>" rel="nofollow" target="_blank" class="dark-color">
																<?=strpos($arProperty['VALUE'], '?') === false ? $arProperty['VALUE'] : explode('?', $arProperty['VALUE'])[0]?>
															</a>
															<!--/noindex-->
														<?elseif($PCODE == 'EMAIL'):?>
															<a href="mailto:<?=$arProperty['VALUE']?>"><?=$arProperty['VALUE']?></a>
														<?elseif($PCODE == 'PHONE'):?>
															<a href="tel:<?=str_replace(array(' ', ',', '-', '(', ')'), '', $arProperty['VALUE']);?>" class="dark-color"><?=$arProperty['VALUE']?></a>
														<?else:?>
															<?=$arProperty['VALUE']?>
														<?endif;?>										
														
													</div>
												</div>
											<?endforeach;?>
										</div>
									<?endif?>
									<div class="properties">
										<?foreach($arItem['FILTERED_PROPERTIES'] as $PCODE => $arProperty):?>
											<?
												$noProps = false;
												$val = is_array($arProperty['DISPLAY_VALUE'])
													? implode('&nbsp;/&nbsp;', $arProperty['DISPLAY_VALUE'])
													: $arProperty['DISPLAY_VALUE'];
											?>
											<div class="inner-wrapper">
												<div class="property <?= strtolower($PCODE); ?>">													
													<span class="muted777"><?= $arProperty['NAME']; ?>:&nbsp;</span>													
													<span class="darken"><?= $val; ?></span>
												</div>
											</div>
										<?endforeach;?>
									</div>
									<?if(array_key_exists('PRICE', $arItem['DISPLAY_PROPERTIES']) && $arItem['DISPLAY_PROPERTIES']['PRICE']['VALUE'] && !$arItem["SHOW_BUY_BUTTON"]):?>
										<div class="prices">
											<div class="price font_mxs darken font-bold"><?=$arItem['DISPLAY_PROPERTIES']['PRICE']['VALUE'];?></div>
											<?if(array_key_exists('PRICE_OLD', $arItem['DISPLAY_PROPERTIES']) && $arItem['DISPLAY_PROPERTIES']['PRICE_OLD']['VALUE']):?>
												<div class="price_old muted font_xs"><?=$arItem['DISPLAY_PROPERTIES']['PRICE_OLD']['VALUE'];?></div>
											<?endif;?>
										</div>										
									<?endif;?>
								<?endif;?>

								<?if($servicesMode):?>
									<?if(isset($arItem["SHOW_BUY_BUTTON"]) && $arItem["SHOW_BUY_BUTTON"]):?>
										<div class="services_buy_block">
											<div class="prices">
												<div class="price font_mxs darken font-bold"><?=CurrencyFormat($arItem["BUTTON_RESULT_PRICE"]['PRICE'], $arItem["BUTTON_RESULT_PRICE"]['CURRENCY']);?></div>
												<?if(isset($arItem["BUTTON_RESULT_PRICE"]['BASE_PRICE']) && $arItem["BUTTON_RESULT_PRICE"]['BASE_PRICE'] !== $arItem["BUTTON_RESULT_PRICE"]['PRICE']):?>
													<div class="price_old muted font_xs"><?=CurrencyFormat($arItem["BUTTON_RESULT_PRICE"]['BASE_PRICE'], $arItem["BUTTON_RESULT_PRICE"]['CURRENCY']);?></div>
												<?endif;?>
												<?CAsproMax::showBonusBlockList($arItem);?>
											</div>
											<div class="counter_wrapp">
												<?
												$arAddToBasketData = array();
												$totalCount = 999;
												$arItem["CAN_BUY"] = true;
												$arItem["MIN_PRICE"]["VALUE"] = $arItem["BUTTON_RESULT_PRICE"];
												$arItem["strMainID"] = $this->GetEditAreaId($arItem['ID']);
												$arItemIDs=CMax::GetItemsIDs($arItem, "Y");												
												$arAddToBasketData = CMax::GetAddToBasketArray($arItem, $totalCount, 1/*$arParams["DEFAULT_COUNT"]*/, $basketUrl, true, $arItemIDs["ALL_ITEM_IDS"], '', $arParams);
																								
												?>
												<?if(($arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_LIST"] && $arAddToBasketData["ACTION"] == "ADD") && $arAddToBasketData["CAN_BUY"]):?>
													<?=\Aspro\Functions\CAsproMax::showItemCounter($arAddToBasketData, $arItem["ID"], $arItemIDs, $arParams, '', '', true);?>
												<?endif;?>

												<div id="<? echo $arItemIDs["ALL_ITEM_IDS"]['BASKET_ACTIONS']; ?>" class="button_block ">
													<!--noindex-->
														<?=$arAddToBasketData["HTML"]?>
													<!--/noindex-->
												</div>
											</div>
										</div>
									<?else:?>
										<?if(isset($arItem["DISPLAY_PROPERTIES"]['FORM_ORDER']) && $arItem["DISPLAY_PROPERTIES"]['FORM_ORDER']['VALUE_XML_ID'] === 'YES'):?>
											<div class="order_service_in_list <?=($noProps ? 'no-props' : '1')?>"><span>
												<span class="btn btn-default animate-load" data-event="jqm" data-param-form_id="<?=($arParams["FORM_ID_ORDER_SERVISE"] ? $arParams["FORM_ID_ORDER_SERVISE"] : 'SERVICES');?>" data-name="order_services" data-autoload-service="<?=CMax::formatJsName($arItem['NAME']);?>" data-autoload-project="<?=CMax::formatJsName($arItem['NAME']);?>"><span><?=(strlen($arParams['S_ORDER_SERVISE']) ? $arParams['S_ORDER_SERVISE'] : Loc::getMessage('S_ORDER_SERVISE'))?></span></span>
											</span></div>
											<?endif;?>
									<?endif;?>
								<?endif;?>								
								

								<?if($bDetailLink):?>								
									<a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="arrow_link option-round colored_theme_hover_bg-el bordered-block rounded3 muted" title="<?=GetMessage('TO_ALL')?>"><?=CMax::showIconSvg("right-arrow", SITE_TEMPLATE_PATH.'/images/svg/arrow_right_list.svg', '', '');?></a>
								<?endif;?>
									
								<?if($bShowDopBlock && $arParams['SALE_MODE'] == 'Y'):?>
									<div class="info-sticker-block static-block">
										<?if($arItem['DISPLAY_PROPERTIES']['SALE_NUMBER']['VALUE']):?>
											<div class="sale-text font_sxs rounded2"><?=$arItem['DISPLAY_PROPERTIES']['SALE_NUMBER']['VALUE'];?></div>
										<?endif;?>
										<?if($bDiscountCounter):?>
											<?\Aspro\Functions\CAsproMax::showDiscountCounter(0, $arItem, array(), array(), '', 'compact');?>
										<?endif;?>
									</div>
								<?endif;?>
							</div>							
						</div>
				<?if($bSlider):?>
					</div>
				<?else:?>
					</div>
				</div>
				<?endif;?>
			<?endforeach;?>
		<?if(!$isAjax):?>
			<?if($arParams['SHOW_TITLE'] == 'Y' && $arParams['TITLE'] && !$bSlider):?>
				</div>
			<?endif;?>
		</div>
		<?if($bHasSection):?>
			</div>
		<?endif;?>
		<?endif;?>

		<?// bottom pagination?>
		<?if($arParams['DISPLAY_BOTTOM_PAGER']):?>
			<div class="bottom_nav_wrapper">
				<div class="bottom_nav animate-load-state<?=($arResult['NAV_STRING'] ? ' has-nav' : '');?>" <?=($isAjax ? "style='display: none; '" : "");?> data-parent=".item-views" data-append=".items.row">
					<?=$arResult['NAV_STRING']?>
				</div>
			</div>
		<?endif;?>
	<?if(!$isAjax):?>
	</div>
	<?endif;?>
	<?if($servicesMode){
		CAsproMax::showBonusComponentList($arResult);
	}?>
<?endif;?>
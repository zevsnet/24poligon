<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<?use \Bitrix\Main\Localization\Loc;?>
<?if($arResult['ITEMS']):?>

	<?$sTemplateMobile = (isset($arParams['MOBILE_TEMPLATE']) ? $arParams['MOBILE_TEMPLATE'] : '')?>
	<?$bSlider = ($sTemplateMobile === 'normal')?>
	<?$bHasBottomPager = $arParams["DISPLAY_BOTTOM_PAGER"] == "Y" && $arResult["NAV_STRING"];?>

	<?if(!$arParams['IS_AJAX']):?>
		<div class="content_wrapper_block <?=$templateName;?> <?=$arResult['NAV_STRING'] ? '' : 'without-border'?>">
			<div class="maxwidth-theme only-on-front">
			<?if($arParams['TITLE_BLOCK'] || $arParams['TITLE_BLOCK_ALL']):?>
				<div class="top_block">
					<h3><?=$arParams['TITLE_BLOCK'];?></h3>
					<a href="<?=SITE_DIR.$arParams['ALL_URL'];?>" class="pull-right font_upper muted"><?=$arParams['TITLE_BLOCK_ALL'] ;?></a>
				</div>
			<?endif;?>
			<div class="item-views sales <?=$sTemplateMobile;?>">
				<div class="items">
					<div class="row flexbox<?=($arParams['NO_MARGIN'] == 'Y' ? ' margin0' : '');?> <?=$sTemplateMobile;?><?=($bSlider ? ' swipeignore mobile-overflow mobile-margin-16 mobile-compact' : '');?><?=$bHasBottomPager ? ' has-bottom-nav' : ''?>">
	<?endif;?>
		<?$position = ($arParams['BG_POSITION'] ? ' set-position '.$arParams['BG_POSITION'] : '');?>
			<?foreach($arResult['ITEMS'] as $i => $arItem):?>
				<?
				// edit/add/delete buttons for edit mode
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => Loc::getMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				// use detail link?
				$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen($arItem['DETAIL_TEXT']) ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1) : true);
				
				// preview image
				$imageSrc = ($arItem['FIELDS']['PREVIEW_PICTURE'] ? $arItem['FIELDS']['PREVIEW_PICTURE']['SRC'] : '');

				// show active date period
				$bActiveDate = strlen($arItem['DISPLAY_PROPERTIES']['PERIOD']['VALUE']) || ($arItem['DISPLAY_ACTIVE_FROM'] && in_array('DATE_ACTIVE_FROM', $arParams['FIELD_CODE']));
				$bDiscountCounter = ($arItem['ACTIVE_TO'] && in_array('ACTIVE_TO', $arParams['FIELD_CODE']));
				$bShowDopBlock = ($arItem['DISPLAY_PROPERTIES']['SALE_NUMBER']['VALUE'] || $bDiscountCounter);
				?>
				<div class="item-wrapper col-md-6 col-sm-6 col-xs-6 col-xxs-12 <?=($bSlider ? ' item-width-261' : '');?>">
					<div class="item<?=($arParams['FILLED'] == 'Y' ? ' bg-fill-grey' : ' bg-fill-white');?> bordered box-shadow rounded3 clearfix <?=($bShowDopBlock && $arParams['IMG_POSITION'] != 'left' ? 'wdate' : '');?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
						<?if($imageSrc):?>
							<div class="image pull-<?=($arParams['IMG_POSITION'] ? $arParams['IMG_POSITION'] : 'right');?> shine">
								<?if($bDetailLink):?>
									<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
								<?endif;?>
									<span class="rounded bg-fon-img lazy<?=$position;?>" data-src="<?=$imageSrc?>" style="background-image:url(<?=\Aspro\Functions\CAsproMax::showBlankImg($imageSrc);?>)"></span>
								<?if($bDetailLink):?>
									</a>
								<?endif;?>
							</div>
						<?endif;?>
						<div class="inner-text">
							<?// date active period?>
							<?if($bActiveDate):?>
								<div class="period-block muted ncolor font_xs <?=($arItem['ACTIVE_TO'] ? 'red' : '');?>">
									<?=CMax::showIconSvg("sale", SITE_TEMPLATE_PATH.'/images/svg/icon_discount.svg', '', '', true, false);?>
									<?if(strlen($arItem['DISPLAY_PROPERTIES']['PERIOD']['VALUE'])):?>
										<span class="date"><?=$arItem['DISPLAY_PROPERTIES']['PERIOD']['VALUE']?></span>
									<?else:?>
										<span class="date"><?=$arItem['DISPLAY_ACTIVE_FROM']?></span>
									<?endif;?>
								</div>
							<?endif;?>

							<div class="title font_mlg">
								<?if($bDetailLink):?>
									<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
								<?endif;?>
								<?=$arItem['NAME'];?>
								<?if($bDetailLink):?>
									</a>
								<?endif;?>
							</div>

							<?// element preview text?>
							<?if(strlen($arItem['FIELDS']['PREVIEW_TEXT'])):?>
								<div class="previewtext muted777 font_xs">
									<?=$arItem['FIELDS']['PREVIEW_TEXT']?>
								</div>
							<?endif;?>
						</div>
						<?if($bShowDopBlock):?>
							<div class="info-sticker-block <?=($arParams['IMG_POSITION'] == 'left' ? 'top' : 'bottom');?>">
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
			<?endforeach;?>

			<?if ($bSlider && $bHasBottomPager):?>
				<?if($arParams['IS_AJAX']):?>
					<div class="wrap_nav bottom_nav_wrapper">
				<?endif;?>
					<?$bHasNav = (strpos($arResult["NAV_STRING"], 'more_text_ajax') !== false);?>
						<div class="bottom_nav mobile_slider animate-load-state block-type<?=($bHasNav ? '' : ' hidden-nav');?> round-ignore" data-parent=".item-views"  data-append=".items > .row" <?=($arParams["IS_AJAX"] ? "style='display: none; '" : "");?>>
						<?if ($bHasNav):?>
							<?=CMax::showIconSvg('bottom_nav-icon colored_theme_svg', SITE_TEMPLATE_PATH.'/images/svg/mobileBottomNavLoader.svg');?>
							<?=$arResult["NAV_STRING"]?>
						<?endif;?>
						</div>

				<?if($arParams['IS_AJAX']):?>
					</div>
				<?endif;?>
			<?endif;?>


	<?if(!$arParams['IS_AJAX']):?>
			</div>
		</div>
	<?endif;?>
		
		<?// bottom pagination?>
		<div class="bottom_nav_wrapper <?=($bSlider ? ' hidden-slider-nav' : '');?>">
			<div class="bottom_nav animate-load-state" <?=($arParams['IS_AJAX'] ? "style='display: none; '" : "");?> data-parent=".item-views" data-append=".items > .row">
				<?if($arParams['DISPLAY_BOTTOM_PAGER']):?>
					<?=$arResult['NAV_STRING']?>
				<?endif;?>
			</div>
		</div>

	<?if(!$arParams['IS_AJAX']):?>
		</div></div></div>
	<?endif;?>
<?endif;?>
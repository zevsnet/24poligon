<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<?use \Bitrix\Main\Localization\Loc;?>
<? global $arTheme;?>
<?if($arResult['ITEMS']):?>
	<?
	$count = count($arResult['ITEMS']);
	$bBordered = ($arParams['BORDERED'] == 'Y');
	$bgSmallPlate = ($arParams['ALL_BLOCK_BG']=='Y' && $arParams['FON_BLOCK_2_COLS'] != 'Y' && $arParams['TITLE_SHOW_FON'] != 'Y' && $arParams['USE_BG_IMAGE_ALTERNATE']!="Y");
	
	if($arTheme['HIDE_SUBSCRIBE']['VALUE'] == 'Y'){
		$arParams['SHOW_SUBSCRIBE'] = "N";
	}
	?>
	<?$sTemplateMobile = (isset($arParams['MOBILE_TEMPLATE']) ? $arParams['MOBILE_TEMPLATE'] : '')?>
	<?$bSlider = ($sTemplateMobile === 'normal')?>
	<?$bHasBottomPager = $arParams["DISPLAY_BOTTOM_PAGER"] == "Y" && $arResult["NAV_STRING"];?>
	<?if(!$arParams['IS_AJAX']):?>
		<div class="content_wrapper_block <?=$templateName;?> content_news2 <?=$arResult['NAV_STRING'] ? '' : 'without-border'?>">
		<div class="maxwidth-theme only-on-front">
		<?if($arParams['TITLE_BLOCK'] || $arParams['TITLE_BLOCK_ALL']):?>
			<?if($arParams['INCLUDE_FILE']):?>
				<div class="with-text-block-wrapper">
					<div class="row">
						<div class="col-md-3">
							<?if($arParams['TITLE_BLOCK'] || $arParams['TITLE_BLOCK_ALL']):?>
								<h3><?=$arParams['TITLE_BLOCK'];?></h3>
								<?// intro text?>
								<?if($arParams['INCLUDE_FILE']):?>
									<div class="text_before_items font_xs">
										<?$APPLICATION->IncludeComponent(
											"bitrix:main.include",
											"",
											Array(
												"AREA_FILE_SHOW" => "file",
												"PATH" => SITE_DIR."include/mainpage/inc_files/".$arParams['INCLUDE_FILE'],
												"EDIT_TEMPLATE" => ""
											)
										);?>
									</div>
								<?endif;?>
								<div class="block-links">
									<span>
										<a href="<?=SITE_DIR.$arParams['ALL_URL'];?>" class="btn btn-transparent-border-color btn-sm"><?=$arParams['TITLE_BLOCK_ALL'] ;?></a>
									</span>
									<?if($arParams['SHOW_SUBSCRIBE'] == 'Y' && $arParams['TITLE_SUBSCRIBE']):?>
										<span>
											<span class="btn btn-transparent-border-color btn-sm animate-load" data-event="jqm" data-param-type="subscribe" data-name="subscribe">
												<?=CMax::showIconSvg("subscribe colored", SITE_TEMPLATE_PATH."/images/svg/subscribe_thin.svg", "", "", true, false);?>
											</span>
										</span>
									<?endif;?>
								</div>
							<?endif;?>
						</div>
						<div class="col-md-9">
			<?else:?>
				<div class="top_block clearfix">
					<h3 class="pull-left"><?=$arParams['TITLE_BLOCK'];?></h3>
					<?if($arParams['SHOW_SUBSCRIBE'] == 'Y' && $arParams['TITLE_SUBSCRIBE']):?>
						<span class="pull-left subscribe">
							<span class="font_upper muted dark_link animate-load" data-event="jqm" data-param-type="subscribe" data-name="subscribe">
								<?=CMax::showIconSvg("subscribe", SITE_TEMPLATE_PATH."/images/svg/subscribe_small_footer.svg", "", "", true, false);?>
								<span><?=$arParams['TITLE_SUBSCRIBE'] ;?></span>
							</span>
						</span>
					<?endif;?>
					<?if($arParams['TITLE_BLOCK_ALL']):?>
						<a href="<?=SITE_DIR.$arParams['ALL_URL'];?>" class="pull-right font_upper muted"><?=$arParams['TITLE_BLOCK_ALL'] ;?></a>
					<?endif;?>
				</div>
			<?endif;?>
		<?endif;?>
		<div class="item-views news2 <?=$arParams['TYPE_IMG'];?><?=(!$bBordered ? '' : ' with-border');?><?=($arParams['HALF_BLOCK'] == 'Y' ? ' half-block' : '');?> <?=($bgSmallPlate ? 'small-bg-plate' : '');?> <?=$sTemplateMobile;?>">
			<div class="items<?=(!$arParams['INCLUDE_FILE'] ? '' : ' list');?> s_<?=$arParams['SIZE_IN_ROW'];?>">
				<div class="row flexbox <?=$sTemplateMobile;?><?=($bSlider ? ' swipeignore mobile-overflow mobile-margin-16 mobile-compact' : '');?><?=$bHasBottomPager ? ' has-bottom-nav' : ''?>">
	<?endif;?>
			<?
			$indexItem = 0;
			$useImageAlternate = ($arParams['USE_BG_IMAGE_ALTERNATE']=="Y");			
			?>
			<?foreach($arResult['ITEMS'] as $i => $arItem):?>
				<?
				
				// edit/add/delete buttons for edit mode
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => Loc::getMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				// use detail link?
				$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen($arItem['DETAIL_TEXT']) ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1) : true);
				// preview image
				$imageSrc = ($arItem['FIELDS']['PREVIEW_PICTURE'] ? $arItem['FIELDS']['PREVIEW_PICTURE']['SRC'] : '');
				$bImage = ($imageSrc ? true : false);
				$noImageSrc = SITE_TEMPLATE_PATH.'/images/svg/noimage_content.svg';
				
				$shortBigBlock = ($arParams['ALL_BLOCK_BG']!='Y' && $arParams['FON_BLOCK_2_COLS'] == 'Y' && $arParams['TITLE_SHOW_FON'] != 'Y');

				$bShowSection = ($arParams['SHOW_SECTION_NAME'] == 'Y' && ($arItem['IBLOCK_SECTION_ID'] && $arResult['SECTIONS'][$arItem['IBLOCK_SECTION_ID']]));

				if($useImageAlternate){					
					
					if($arParams['SIZE_IN_ROW'] == 4 || $arParams['SIZE_IN_ROW'] == 3){
						$bBgImage = ($indexItem == 0 || $indexItem%5 == 0 ? true : false);
					} else {
						$bBgImage = ($indexItem == 0 || $indexItem == (((int)$arParams['SIZE_IN_ROW'])*2 - 3) ? true : false);
					}
					
					if($bBgImage){
						$arItem['PROPERTIES']['TYPE_BLOCK']['VALUE_XML_ID'] = 'fon_text_fon';
					}
					
				}else{
					if(!$indexItem && $arParams['FON_BLOCK_2_COLS'] == 'Y') {
						$arItem['PROPERTIES']['TYPE_BLOCK']['VALUE'] = true;
						$arItem['PROPERTIES']['TYPE_BLOCK']['VALUE_XML_ID'] = 'fon_text_fon';
					}
					$bBgImage = ($arItem['PROPERTIES']['TYPE_BLOCK']['VALUE'] != '' && $arParams['HALF_BLOCK'] != 'Y');
				}
				

				$position = ($arParams['BG_POSITION'] ? ' set-position '.$arParams['BG_POSITION'] : '');
				$bBigBlock = false;

				if( $bBgImage && $arParams['FON_BLOCK_2_COLS'] == 'Y' )
				{
					$bBigBlock = true;
					$col = '3 merged';
					if($arParams['SIZE_IN_ROW'] == 5)
						$col = '6 merged col-lg-40';
					elseif($arParams['SIZE_IN_ROW'] == 4)
						$col = '8 merged col-lg-6';
					elseif($arParams['SIZE_IN_ROW'] == 3)
						$col = '8 merged col-lg-8';
					else
					{
						$col .= ' col-lg-20';
						$bBigBlock = false;
					}
				}
				else
				{
					$col = (12/$arParams['SIZE_IN_ROW']);
					if($arParams['SIZE_IN_ROW'] == 5)
						$col = '3 col-lg-20';
					elseif($arParams['SIZE_IN_ROW'] == 4)
						$col = '4 col-lg-3';
				}

				$bLineImg = false;
				if($arParams['HALF_BLOCK'] == 'Y')
				{
					if($arParams['IS_AJAX'] != 'Y')
					{
						if(!$indexItem)
						{
							$arItem['PROPERTIES']['TYPE_BLOCK']['VALUE_XML_ID'] = 'fon_text_fon';
							$bBgImage = true;
						}
						else
						{
							$arItem['PROPERTIES']['TYPE_BLOCK']['VALUE_XML_ID'] = 'line_img';
							$bLineImg = true;
						}
						$col = 6;
					}
					else
					{
						$col = 12;
						$bLineImg = true;
					}
				}

				if($bLineImg)
					$bBordered = false;

				$bHalfWrapper = false;
				
				if($arParams['ALL_BLOCK_BG']=='Y'){
					$bBgImage = true;
					$arItem['PROPERTIES']['TYPE_BLOCK']['VALUE_XML_ID'] = 'fon_text_fon';
				}
				

				// show active date period
				$bActiveDate = strlen($arItem['DISPLAY_PROPERTIES']['PERIOD']['VALUE']) || ($arItem['DISPLAY_ACTIVE_FROM'] && in_array('DATE_ACTIVE_FROM', $arParams['FIELD_CODE']));
				?>
				    
				<?
				$dop_class = '';
				if($arParams['USE_SECTIONS_TABS']=='Y'){
					// var_dump($arItem);
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

				<?if($arParams['HALF_BLOCK'] != 'Y' || ($arParams['HALF_BLOCK'] == 'Y' && $arParams['IS_AJAX'] != 'Y' && $indexItem < 2)):?>
					<div class="item-wrapper col-md-<?=$col;?> col-sm-6 col-xs-6 col-xxs-12 clearfix <?=$arItem['PROPERTIES']['TYPE_BLOCK']['VALUE_XML_ID'];?> <?=$dop_class;?><?=($bSlider ? ' item-width-261' : '');?>" data-ref="mixitup-target">
				<?endif;?>

					<?if($arParams['HALF_BLOCK'] == 'Y' && $arParams['IS_AJAX'] != 'Y' && $indexItem == 1):?>
						<div class="half-wrapper">
						<?$bHalfWrapper = true;?>
					<?endif;?>

					<?if($bBgImage):?>
					<div class="item with-fon <?=($arParams['TITLE_SHOW_FON'] == 'Y' ? ' with-title-fon' : ' darken-bg-animate ');?> box-shadow rounded3  <?=($useImageAlternate ? ' bg-img-alternate ' : '');?> <?=($shortBigBlock && $bBigBlock ? ' short-big-block ' : '');?> <?=($bBigBlock ? 'big-block': ($arParams['TALL_BG_BLOCKS']=='Y'? 'tall-block':''));?> lazy<?=$position;?>" <?=($bImage ? 'data-src="'.$imageSrc.'"' : 'data-src="'.$noImageSrc.'"');?>   style="background-image:url('<?=\Aspro\Functions\CAsproMax::showBlankImg($imageSrc);?>')" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
						<?if (!$bSlider):?>
							<div class="hidden compact-img lazy" <?=($bImage ? 'data-src="'.$imageSrc.'"' : 'data-src="'.$noImageSrc.'"');?>   style="background-image:url('<?=\Aspro\Functions\CAsproMax::showBlankImg($imageSrc);?>')"></div>
						<?endif;?>
						<?if($bDetailLink):?><a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="full_bg_link"></a><?endif;?>
					<?else:?>
					<div class="item <?=$bHalfWrapper ? '' : 'bg-white'?> <?=($bBordered ? ' bordered box-shadow rounded3' : '');?><?=(!$bImage ? ' no-img' : '');?><?=(($arResult['HAS_TITLE_FON'] == 'Y' || $useImageAlternate) ? ' long' : '');?> clearfix" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
						<?if($bImage):?>
							<div class="image shine<?=($bLineImg ? ' pull-left' : '');?>">
								<?if($bDetailLink):?>
									<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
								<?endif;?>
									<span class="<?=(!$bBordered ? 'rounded3' : '');?> bg-fon-img lazy<?=$position;?>" data-src="<?=$imageSrc?>" style="background-image:url(<?=\Aspro\Functions\CAsproMax::showBlankImg($imageSrc);?>)"></span>
								<?if($bDetailLink):?>
									</a>
								<?endif;?>
							</div>
						<?endif;?>
					<?endif;?>
						<div class="inner-text<?=($bShowSection ? ' with-section' : '');?><?=($bActiveDate ? ' with-date' : '');?><?=($arParams['TITLE_SHOW_FON'] == 'Y' ? ' with-fon' : '');?>">
							<?if($arParams['TITLE_SHOW_FON'] == 'Y' && $bBgImage):?>
								<div class="inner-text-wr bordered">
							<?endif;?>
							<?if($bBgImage):?>
								<div class="inner-block-text">
							<?endif;?>
							<?if($bShowSection):?>
								<div class="section muted font_upper"><?=$arResult['SECTIONS'][$arItem['IBLOCK_SECTION_ID']]['NAME'];?></div>
							<?endif;?>

							<?// date active period?>
							<?if($bActiveDate):?>
								<div class="period-block<?=($bShowSection && !$bLineImg ? '' : ' muted');?> <?=($arParams['HALF_BLOCK'] == 'Y' && $indexItem ? 'font_xxs' : 'font_xs');?>">
									<?if(strlen($arItem['DISPLAY_PROPERTIES']['PERIOD']['VALUE'])):?>
										<span class="date"><?=$arItem['DISPLAY_PROPERTIES']['PERIOD']['VALUE']?></span>
									<?else:?>
										<span class="date"><?=$arItem['DISPLAY_ACTIVE_FROM']?></span>
									<?endif;?>
								</div>
							<?endif;?>

							<div class="title<?=(($bBigBlock || $arParams['ALL_BLOCK_BG']=='Y') && $arParams['TITLE_SHOW_FON'] != 'Y' ? ' font_mlg' : '' /*(!$bBordered || $arParams['SIZE_IN_ROW'] == 5 ? '' : ' font_md')*/);?>">
								<?if($bDetailLink):?>
									<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
								<?endif;?>
								<?=$arItem['NAME'];?>
								<?if($bDetailLink):?>
									</a>
								<?endif;?>
							</div>

							<?if($arItem['FIELDS']['PREVIEW_TEXT'] && !$bImage):?>
								<div class="preview-text muted777 font_xs"><?=$arItem['PREVIEW_TEXT'];?></div>
							<?endif;?>

							<?if($bBgImage):?>
								</div>
							<?endif;?>

							<?if($arParams['TITLE_SHOW_FON'] == 'Y' && $bBgImage):?>
								</div>
							<?endif;?>
						</div>
					</div>

				<?if($arParams['HALF_BLOCK'] != 'Y' || ($arParams['HALF_BLOCK'] == 'Y' && ($arParams['IS_AJAX'] == 'Y' || ($arParams['IS_AJAX'] != 'Y' && !$indexItem)))):?>
				</div>
				<?endif;?>
				<?
				//$indexItem = ($indexItem == $alternateNumber ? 1 : ++$indexItem);
				$indexItem++; 
				?>
			<?endforeach;?>

			<?if ($bSlider && $bHasBottomPager):?>
				<?if($arParams['IS_AJAX']):?>
					<div class="wrap_nav bottom_nav_wrapper">
				<?endif;?>
					<?$bHasNav = (strpos($arResult["NAV_STRING"], 'more_text_ajax') !== false);?>
						<div class="bottom_nav mobile_slider animate-load-state block-type<?=($bHasNav ? '' : ' hidden-nav');?>" data-parent=".item-views" data-scroll-class=".swipeignore.mobile-overflow" data-append="<?=($arParams['HALF_BLOCK'] != 'Y' ? '.items > .row' : '.items > .row > .item-wrapper.line_img .half-wrapper');?>" <?=($arParams["IS_AJAX"] ? "style='display: none; '" : "");?>>
						<?if ($bHasNav):?>
							<?=CMax::showIconSvg('bottom_nav-icon colored_theme_svg', SITE_TEMPLATE_PATH.'/images/svg/mobileBottomNavLoader.svg');?>
							<?=$arResult["NAV_STRING"]?>
						<?endif;?>
						</div>

				<?if($arParams['IS_AJAX']):?>
					</div>
				<?endif;?>
			<?endif;?>

			<?if($arParams['HALF_BLOCK'] == 'Y' && $arParams['IS_AJAX'] != 'Y'):?>
					</div>
				</div>
			<?endif;?>

	<?if(!$arParams['IS_AJAX']):?>
			</div>
		</div>
	<?endif;?>
		
		<?// bottom pagination?>
		<div class="bottom_nav_wrapper<?=($bSlider ? ' hidden-slider-nav' : '');?>">
			<div class="bottom_nav animate-load-state<?=($arResult['NAV_STRING'] ? ' has-nav' : '');?>" <?=($arParams['IS_AJAX'] ? "style='display: none; '" : "");?> data-parent=".item-views" data-scroll-class=".swipeignore.mobile-overflow" data-append="<?=($arParams['HALF_BLOCK'] != 'Y' ? '.items > .row' : '.items > .row > .item-wrapper.line_img .half-wrapper .mCSB_container');?>">
				<?if($arParams['DISPLAY_BOTTOM_PAGER']):?>
					<?=$arResult['NAV_STRING']?>
				<?endif;?>
			</div>
		</div>

	<?if(!$arParams['IS_AJAX']):?>
		</div>
		<?if($arParams['INCLUDE_FILE']):?>
			</div></div></div>
		<?endif;?>
	</div></div>
	<?endif;?>
<?endif;?>
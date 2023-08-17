<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<?if($arResult['ITEMS']):?>
	<?
	$inRow = $arParams['SIZE_IN_ROW'] ? $arParams['SIZE_IN_ROW'] : 4;
	$col = (round(12/$inRow));
	$bNameCenter = $arParams['NAME_CENTER'] == 'Y';
	$bTextInside = $arParams['TEXT_INSIDE'] == 'Y';
	$bBordered = $arParams['BORDERED'] == 'Y';
	$bFrontPage = $arParams['FRONT_PAGE'] == 'Y';
	?>
	<?$bSlider = (isset($arParams['MOBILE_TEMPLATE']) && $arParams['MOBILE_TEMPLATE'] === 'Y')?>
	<?$bHasBottomPager = $arParams["DISPLAY_BOTTOM_PAGER"] == "Y" && $arResult["NAV_STRING"];?>
	<div class="content_wrapper_block <?=$templateName;?> <?=$bTextInside ? 'text-inside' : ''?> <?=$bBordered ? 'with-border' : ''?>">
		<?if($bFrontPage):?>
			<div class="maxwidth-theme">
			<div class="top_block">
				<h3><?=$arParams['TITLE_BLOCK'];?></h3>
				<a href="<?=SITE_DIR.$arParams['ALL_URL'];?>" class="pull-right font_upper muted"><?=$arParams['TITLE_BLOCK_ALL'] ;?></a>
			</div>
		<?endif;?>
		<div class="item-views <?=$bTextInside ? 'text_inside' : ''?><?=($bSlider ? ' mobile-adaptive' : '');?> <?=$bBordered ? 'without-space' : ''?>">
			<div class="items row <?=$bBordered ? 'margin0' : ''?> flexbox c_<?=count($arResult['ITEMS']);?> <?=($bSlider ? ' swipeignore mobile-overflow mobile-margin-16 mobile-compact' : '');?>">

				<?foreach($arResult['ITEMS'] as $arItem)
				{
					$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
					$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
					$sUrl = $arItem['DETAIL_PAGE_URL'];
					?>
					<div class="col-md-<?=$col;?> col-sm-6 col-xs-6 col-12--500 <?=$bBordered ? 'bordered box-shadow' : ''?><?=($bSlider ? ' item-width-261' : '');?>">
						<div id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="item <?=$bBordered ? '' : 'hover_zoom'?>">
							<?if(is_array($arItem['PREVIEW_PICTURE']) ):?>
								<div class="image rounded3 shine">
									<div class="img_inner">
										<?if($sUrl):?>
											<a href="<?=$sUrl;?>" title="<?=($arItem['PREVIEW_PICTURE']['TITLE']?$arItem['PREVIEW_PICTURE']['TITLE']:$arItem['NAME']);?>">
										<?endif;?>
										<span class="lazy rounded3 set-position center<?=($bTextInside ? ' bg-fon-img darken-bg-animate' : '');?>" data-src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" style="background-image:url(<?=\Aspro\Functions\CAsproMax::showBlankImg($arItem['PREVIEW_PICTURE']['SRC']);?>)"></span>
										<?if($sUrl):?>
											</a>
										<?endif;?>
									</div>
								</div>
							<?endif;?>
							<div class="inner-text<?=($bNameCenter ? ' text-center' : '');?>">
								<div class="title-inner">
									<?if($arItem['SECTION_PATH'] && $arItem['SECTION_PATH']):?>
										<div class="font_upper top-text"><?=$arItem['SECTION_PATH'];?></div>
									<?endif;?>
									<div class="title font_md">
										<?if($sUrl):?>
											<a href="<?=$sUrl;?>" title="<?=($arItem['PREVIEW_PICTURE']['TITLE']?$arItem['PREVIEW_PICTURE']['TITLE']:$arItem['NAME']);?>">
										<?endif;?>
											<?=$arItem['NAME'];?>
										<?if($sUrl):?>
											</a>
										<?endif;?>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?}?>

			</div>
			
			<?// bottom pagination?>
			<?if($arParams['DISPLAY_BOTTOM_PAGER'] && $arResult['NAV_STRING']):?>
				<div class="bottom_nav_wrapper">
					<div class="bottom_nav animate-load-state<?=($arResult['NAV_STRING'] ? ' has-nav' : '');?>" <?=(CMax::checkAjaxRequest() ? "style='display: none; '" : "");?> data-parent=".item-views" data-append=".items.row" data-target=".items.row > div">
						<?=$arResult['NAV_STRING']?>
					</div>
				</div>
			<?endif;?>
		</div>
		
		<?if($bFrontPage):?>
			</div> <!-- maxwidth-theme -->
		<?endif;?>
	</div>
<?endif;?>
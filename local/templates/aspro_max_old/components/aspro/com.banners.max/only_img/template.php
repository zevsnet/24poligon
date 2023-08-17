<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<?if($arResult['ITEMS']):?>
	<?
	$bWide = ($arParams['WIDE'] == 'Y');
	$bBannerWithText = ($arParams['BANNER_TYPE_THEME'] == 'BANNER_IMG_TEXT');

	$col = (round(12/$arParams['SIZE_IN_ROW']));
	if($arParams['BANNER_TYPE_THEME'] == 'BANNER_IMG_WIDE')
		$col = 12;

	$position = ($arParams['BG_POSITION'] ? ' set-position '.$arParams['BG_POSITION'] : '');
	?>
	<div class="content_wrapper_block <?=$templateName;?>">
		<div class="maxwidth-theme<?=($bWide ? ' wide' : '');?> <?=$arParams['BANNER_TYPE_THEME']?>">
			<div class="item-views bottom_banners hover_blink">
				<div class="items">
					<div class="row flexbox<?=($bWide || $arParams['NO_MARGIN'] == 'Y' ? ' margin0' : '');?> justify-center">
						<?foreach($arResult['ITEMS'] as $arItem)
						{
							$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
							$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
							$bUrl = (isset($arItem['DISPLAY_PROPERTIES']['URL']) && $arItem['DISPLAY_PROPERTIES']['URL']['VALUE']);
							$sUrl = ($bUrl ? str_replace('//', '/', SITE_DIR.$arItem['DISPLAY_PROPERTIES']['URL']['VALUE']) : '');
							?>
							<?if(is_array($arItem['PREVIEW_PICTURE']) ):?>
								<div class="col-md-<?=$col;?> col-sm-<?=($col != 4 ? $col : 6);?> col-xs-12">
									<div id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="item">
										<div class="img shine<?=($bBannerWithText ? ' pull-left' : '')?>">
											<div class="img_inner">
												<?if($sUrl):?>
													<a href="<?=$sUrl;?>" title="<?=($arItem['PREVIEW_PICTURE']['TITLE']?$arItem['PREVIEW_PICTURE']['TITLE']:$arItem['NAME']);?>">
												<?endif;?>
												<span class="lazy<?=($bBannerWithText ? ' rounded' : '')?><?=$position;?><?=(!$bWide && !$bBannerWithText ? ($arParams['SIZE_IN_ROW'] == 2 ? ' rounded4' : ' rounded3') : '');?>" data-src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" style="background-image:url(<?=\Aspro\Functions\CAsproMax::showBlankImg($arItem['PREVIEW_PICTURE']['SRC']);?>)"></span>
												<?if($sUrl):?>
													</a>
												<?endif;?>
											</div>
										</div>
										<?if($bBannerWithText):?>
											<div class="inner-text">
												<div class="title option-font-ignore font_upper muted">
													<?if($sUrl):?>
														<a href="<?=$sUrl;?>" title="<?=($arItem['PREVIEW_PICTURE']['TITLE']?$arItem['PREVIEW_PICTURE']['TITLE']:$arItem['NAME']);?>">
													<?endif;?>
													<?=$arItem['NAME'];?>
													<?if($sUrl):?>
														</a>
													<?endif;?>
												</div>
												<?if($arItem['PREVIEW_TEXT']):?>
													<div class="previewtext option-font-bold font_sm">
														<?=$arItem['PREVIEW_TEXT'];?>
													</div>
												<?endif;?>
											</div>
										<?endif;?>
									</div>
								</div>
							<?endif;?>
						<?}?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?endif;?>
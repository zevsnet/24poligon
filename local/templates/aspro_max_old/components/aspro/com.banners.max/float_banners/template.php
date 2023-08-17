<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

$arParams['SIZE_IN_ROW'] = $arParams['SIZE_IN_ROW'] ?? 4;
?>
<?if($arResult['ITEMS']):?>
	<?
	$col = (round(12/$arParams['SIZE_IN_ROW']));
	$position = ($arParams['BG_POSITION'] ? ' set-position '.$arParams['BG_POSITION'] : '');
	$bType2 = ($arParams['TYPE_BLOCK'] == 'type2');
	?>
	<div class="content_wrapper_block <?=$templateName;?> <?=$bType2 ? 'text-inside' : ''?>">
		<div class="maxwidth-theme">
			<div class="item-views float_banners <?=$arParams['TYPE_BLOCK'];?><?=($arResult['MIXED_BLOCKS'] && $bType2 ? ' sm-block' : '');?>">
				<div class="items">
					<div class="row flexbox justify-center swipeignore mobile-overflow mobile-margin-16 mobile-compact c_<?=count($arResult['ITEMS'])?>">
						<?foreach($arResult['ITEMS'] as $arItem)
						{
							$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
							$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
							$bUrl = (isset($arItem['DISPLAY_PROPERTIES']['URL']) && $arItem['DISPLAY_PROPERTIES']['URL']['VALUE']);
							$sUrl = ($bUrl ? $arItem['DISPLAY_PROPERTIES']['URL']['VALUE'] : '');

							if($bType2)
							{
								if($arResult['MIXED_BLOCKS'])
								{
									$col = ($arItem['PROPERTIES']['TYPE_BLOCK']['VALUE_XML_ID'] ? $arItem['PROPERTIES']['TYPE_BLOCK']['VALUE_XML_ID'] : 3);
								}
								else
								{
									$col = 3;
								}
							}
							?>
							<div class="col-md-<?=$col;?> col-sm-6 col-xs-6 col-xxs-12 item-width-261">
								<div id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="item hover_zoom">
									<?if(is_array($arItem['PREVIEW_PICTURE']) ):?>
										<div class="image shine rounded3">
											<div class="img_inner">
												<?if($sUrl):?>
													<a href="<?=$sUrl;?>" title="<?=($arItem['PREVIEW_PICTURE']['TITLE']?$arItem['PREVIEW_PICTURE']['TITLE']:$arItem['NAME']);?>">
												<?endif;?>
												<span class="lazy<?=$position;?><?=($bType2 ? ' bg-fon-img darken-bg-animate' : '');?>" data-src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" style="background-image:url(<?=\Aspro\Functions\CAsproMax::showBlankImg($arItem['PREVIEW_PICTURE']['SRC']);?>)"></span>
												<?if($sUrl):?>
													</a>
												<?endif;?>
											</div>
										</div>
									<?endif;?>
									<div class="inner-text<?=($bType2 ? '' : ' text-center');?><?=($arItem['PREVIEW_TEXT'] ? ' animated' : '');?>">
										<div class="title-inner">
											<?if($arItem['DISPLAY_PROPERTIES']['TOP_TEXT'] && $arItem['DISPLAY_PROPERTIES']['TOP_TEXT']['VALUE']):?>
												<div class="font_upper top-text"><?=$arItem['DISPLAY_PROPERTIES']['TOP_TEXT']['VALUE'];?></div>
											<?endif;?>
											<div class="title font_mlg">
												<?if($sUrl):?>
													<a href="<?=$sUrl;?>" title="<?=($arItem['PREVIEW_PICTURE']['TITLE']?$arItem['PREVIEW_PICTURE']['TITLE']:$arItem['NAME']);?>">
												<?endif;?>
												<?=$arItem['NAME'];?>
												<?if($sUrl):?>
													</a>
												<?endif;?>
											</div>
										</div>
										<?if($arItem['PREVIEW_TEXT']):?>
											<div class="previewtext font_xs muted777">
												<?=$arItem['PREVIEW_TEXT'];?>
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
<?endif;?>
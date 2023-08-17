<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);

$arResult['MIXED_BLOCKS'] = $arResult['MIXED_BLOCKS'] ?? '';
$arParams['TYPE_BLOCK'] = $arParams['TYPE_BLOCK'] ?? '';
$arParams['USE_TYPE_BLOCK'] = $arParams['USE_TYPE_BLOCK'] ?? 'N';
$arParams['BG_BLOCK_POSITION'] = $arParams['BG_BLOCK_POSITION'] ?? 'top';
?>
<?if($arResult['ITEMS']):?>
	<?
	$col = (round(12/$arParams['SIZE_IN_ROW']));
	$position = ($arParams['BG_POSITION'] ? ' set-position '.$arParams['BG_POSITION'] : '');
	$bType2 = ($arParams['TYPE_BLOCK'] == 'type2');
	$bOnlyOne = count($arResult['ITEMS']) == 1;
	?>
	<div class="content_wrapper_block <?=$templateName;?>">
		<div class="maxwidth-theme">
			<div class="item-views float_banners2 swipeignore <?=($arResult['MIXED_BLOCKS'] ? ' sm-block' : '');?> <?=$arParams['BG_BLOCK_POSITION'];?> <?=$bOnlyOne ? 'one-item' : ''?>">
				<div class="items">
					<div class="row flexbox mobile-overflow">
						<?foreach($arResult['ITEMS'] as $arItem)
						{
							$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
							$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
							$bUrl = (isset($arItem['DISPLAY_PROPERTIES']['URL']) && $arItem['DISPLAY_PROPERTIES']['URL']['VALUE']);
							$sUrl = ($bUrl ? $arItem['DISPLAY_PROPERTIES']['URL']['VALUE'] : '');

							$bTopBgBlock = ($arParams['BG_BLOCK_POSITION'] == 'top');

							if($arResult['MIXED_BLOCKS'])
							{
								$col = (isset($arItem['PROPERTIES']['TYPE_BLOCK']) && $arItem['PROPERTIES']['TYPE_BLOCK']['VALUE_XML_ID'] ? $arItem['PROPERTIES']['TYPE_BLOCK']['VALUE_XML_ID'] : 3);
								if($col != 3)
								{
									$bTopBgBlock = false;
									if($arItem['PREVIEW_PICTURE2'])
										$arItem['PREVIEW_PICTURE'] = $arItem['PREVIEW_PICTURE2'];
								}
								elseif($arParams['BG_BLOCK_POSITION'] == 'bottom')
									unset($arItem['PREVIEW_TEXT']);
							}
							?>
							<div class="col-md-<?=$col;?> col-xs-6 col-xxs-12">
								<div id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="item <?=($col == 6 || $col == 12 ? 'big' : 'sm');?> <?=$arParams['BG_BLOCK_POSITION'];?> hover_zoom rounded3 lazy flexbox" <?if($arItem['DETAIL_PICTURE']['SRC'] ?? ''):?>data-src="<?=$arItem['DETAIL_PICTURE']['SRC']?>" style="background-image:url(<?=\Aspro\Functions\CAsproMax::showBlankImg($arItem['DETAIL_PICTURE']['SRC']);?>)"<?endif;?>>
									<?if(is_array($arItem['PREVIEW_PICTURE'])):?>
										<div class="image">
											<div class="img_inner">
												<?if($sUrl):
													$title = isset($arItem['PREVIEW_PICTURE']['TITLE']) && $arItem['PREVIEW_PICTURE']['TITLE'] ? $arItem['PREVIEW_PICTURE']['TITLE'] : $arItem['~NAME'];
													$title = str_replace(array('<br>','<br/>','<br />'), ' ', $title);?>
													<a href="<?=$sUrl;?>" title="<?=$title?>">
												<?endif;?>
												<span class="lazy<?=$position;?><?=($bType2 ? ' bg-fon-img darken-bg-animate' : '');?>" data-src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" style="background-image:url(<?=\Aspro\Functions\CAsproMax::showBlankImg($arItem['PREVIEW_PICTURE']['SRC']);?>)"></span>
												<?if($sUrl):?>
													</a>
												<?endif;?>
											</div>
										</div>
									<?endif;?>
									<div class="inner-text<?=(!$bType2 ? '' : ' text-center');?> flexbox">
										<div class="title-inner<?=($bTopBgBlock ? ' text-center' : '');?>">
											<?if($arItem['DISPLAY_PROPERTIES']['TOP_TEXT']['VALUE'] ?? ''):?>
												<div class="font_upper top-text"><?=$arItem['DISPLAY_PROPERTIES']['TOP_TEXT']['VALUE'];?></div>
											<?endif;?>
											<div class="title <?=($bTopBgBlock ? 'font_md' : ($col == 6 || $col == 12 ? 'font_exlg' : 'font_mlg'));?>">
												<?if($sUrl):
													$title = isset($arItem['PREVIEW_PICTURE']['TITLE']) && $arItem['PREVIEW_PICTURE']['TITLE'] ? $arItem['PREVIEW_PICTURE']['TITLE'] : $arItem['~NAME'];
													$title = str_replace(array('<br>','<br/>','<br />'), ' ', $title);?>
													<a href="<?=$sUrl;?>" title="<?=$title;?>">
												<?endif;?>
												<?=$arItem['~NAME'];?>
												<?if($sUrl):?>
													</a>
												<?endif;?>
											</div>
										</div>
										<?if($arItem['PREVIEW_TEXT'] && !$bTopBgBlock):?>
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
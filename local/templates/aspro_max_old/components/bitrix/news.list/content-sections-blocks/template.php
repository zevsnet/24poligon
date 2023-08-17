<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

$iMaxServicesCountVisible = isset($arParams['MAX_SERVICES_COUNT_VISIBLE']) ? intval($arParams['MAX_SERVICES_COUNT_VISIBLE']) : false;
?>
<?if($arResult['SECTIONS']):?>
	<div class="item-views content-sections2">
		<div class="items row list_block flexbox">
			<?foreach($arResult['SECTIONS'] as $arItem):?>
				<?
					// edit/add/delete buttons for edit mode
					$arSectionButtons = CIBlock::GetPanelButtons($arItem['IBLOCK_ID'], 0, $arItem['ID'], array('SESSID' => false, 'CATALOG' => true));
					$this->AddEditAction($arItem['ID'], $arSectionButtons['edit']['edit_section']['ACTION_URL'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'SECTION_EDIT'));
					$this->AddDeleteAction($arItem['ID'], $arSectionButtons['edit']['delete_section']['ACTION_URL'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'SECTION_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
					
					// preview picture
					if($bShowSectionImage = in_array('PREVIEW_PICTURE', $arParams['FIELD_CODE'])){
						$bImage = strlen($arItem['~PICTURE']);
						$arSectionImage = ($bImage ? CFile::ResizeImageGet($arItem['~PICTURE'], array('width' => 429, 'height' => 10000), BX_RESIZE_IMAGE_PROPORTIONAL, true) : array());
						$imageSectionSrc = ($bImage ? $arSectionImage['src'] : SITE_TEMPLATE_PATH.'/images/svg/noimage_content.svg');
					}
				?>
				<div class="item__column col-lg-4 col-md-6 col-sm-6 col-xs-12">
					<div class="item_wrap colored_theme_hover_bg-block box-shadow rounded3 bordered-block " >
						<div class="item noborder <?=($bShowSectionImage ? '' : ' wti')?>  <?=$arParams['IMAGE_CATALOG_POSITION'];?> clearfix" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
							<?// icon or preview picture?>
							<?if($bShowSectionImage):?>
								<div class="image shine nopadding">
									<a href="<?=$arItem['SECTION_PAGE_URL']?>">
										<img src="<?=\Aspro\Functions\CAsproMax::showBlankImg($imageSectionSrc);?>" data-src="<?=$imageSectionSrc?>" alt="<?=( $arItem['PICTURE']['ALT'] ? $arItem['PICTURE']['ALT'] : $arItem['NAME']);?>" title="<?=( $arItem['PICTURE']['TITLE'] ? $arItem['PICTURE']['TITLE'] : $arItem['NAME']);?>" class="img-responsive lazy" />
									</a>
								</div>
							<?endif;?>

							<div class="body-info">
								<?// section name?>
								<?if(in_array('NAME', $arParams['FIELD_CODE'])):?>
									<div class="title font_md">
										<a href="<?=$arItem['SECTION_PAGE_URL']?>" class="dark-color">
											<?=$arItem['NAME']?>
										</a>
									</div>
								<?endif;?>

								<?// section preview text?>
								<?if(strlen($arItem['UF_TOP_SEO']) && $arParams['SHOW_SECTION_PREVIEW_DESCRIPTION'] != 'N'):?>
									<div class="item__preview-text font_xs muted777 line-h-165">
										<?=$arItem['UF_TOP_SEO']?>
									</div>
								<?elseif(strlen($arItem['DESCRIPTION']) && $arParams['SHOW_SECTION_PREVIEW_DESCRIPTION'] != 'N'):?>
									<div class="item__preview-text font_xs muted777 line-h-165">
										<?if($arParams['PREVIEW_TRUNCATE_LEN']):?>
											<?=CMax::truncateLengthText($arItem['DESCRIPTION'], $arParams['PREVIEW_TRUNCATE_LEN'])?>
										<?else:?>
											<?=$arItem['DESCRIPTION'];?>
										<?endif;?>
									</div>
								<?endif;?>
								<?// section child?>
								<?if($arItem['CHILD']):?>
									<? 
										$iTotalServices = count($arItem['CHILD']);
									?>
									<div class="text childs">
										<ul class="item__text-list clearfix">
											<? foreach( $arItem['CHILD'] as $key => $arSubItem ): ?>
												<?
													if(is_array($arSubItem['DETAIL_PAGE_URL'])){
														$arSubItem['DETAIL_PAGE_URL'] = isset($arSubItem['CANONICAL_PAGE_URL']) && !empty($arSubItem['CANONICAL_PAGE_URL'])
															? $arSubItem['CANONICAL_PAGE_URL']
															: $arSubItem['DETAIL_PAGE_URL'][key($arSubItem['DETAIL_PAGE_URL'])];
													}
												?>
												<li class="text-list__element font_xs<?= $iMaxServicesCountVisible && $key >= $iMaxServicesCountVisible ? ' text-list__element--hidden' : null; ?>">
													<a class="colored" href="<?= $arSubItem['SECTION_PAGE_URL'] ?: $arSubItem['DETAIL_PAGE_URL'] ;?>">
														<?= $arSubItem['NAME']; ?>
													</a>
												</li>
											<? endforeach; ?>
										</ul>
									</div>

									<? if( $iMaxServicesCountVisible > 0 && $iTotalServices-$iMaxServicesCountVisible > 0): ?>
										<div class="button_opener colored">
											<?= CMax::showIconSvg("arrow", SITE_TEMPLATE_PATH.'/images/svg/arrow_down_accordion.svg', '', '', true, false); ?>
											
											<span class="opener font_upper" data-open_text="<?=GetMessage('CLOSE_TEXT');?>" data-close_text="<?=GetMessage('OPEN_TEXT');?>">
												<?= GetMessage('OPEN_TEXT'); ?>
											</span>
										</div>
									<? endif; ?>

								<?endif;?>
							</div>
						</div>
					</div>
				</div>
			<?endforeach;?>
		</div>
	</div>
<?endif;?>
<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>
<?if($arResult['SECTIONS']):?>
	<div class="item-views content-sections1 ">
		<div class="items row margin0 list_block">
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
				<div class="col-md-12 col-sm-12">
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
									<div class="title font_mlg">
										<a href="<?=$arItem['SECTION_PAGE_URL']?>" class="dark-color">
											<?=$arItem['NAME']?>
										</a>
									</div>
								<?endif;?>

								<?// section preview text?>
								<?if(strlen($arItem['UF_TOP_SEO']) && $arParams['SHOW_SECTION_PREVIEW_DESCRIPTION'] != 'N'):?>
									<div class="previewtext  font_sm muted777 line-h-165">
										<?=$arItem['UF_TOP_SEO']?>
									</div>
								<?elseif(strlen($arItem['DESCRIPTION']) && $arParams['SHOW_SECTION_PREVIEW_DESCRIPTION'] != 'N'):?>
									<div class="previewtext  font_sm muted777 line-h-165">
										<?if($arParams['PREVIEW_TRUNCATE_LEN']):?>
											<?=CMax::truncateLengthText($arItem['DESCRIPTION'], $arParams['PREVIEW_TRUNCATE_LEN'])?>
										<?else:?>
											<?=$arItem['DESCRIPTION'];?>
										<?endif;?>
									</div>
								<?endif;?>
								<?// section child?>
								<?if($arItem['CHILD']):?>
									<div class="text childs">
										<ul>
											<?foreach($arItem['CHILD'] as $arSubItem):?>
												<?
												if(is_array($arSubItem['DETAIL_PAGE_URL'])){
													if(isset($arSubItem['CANONICAL_PAGE_URL']) && !empty($arSubItem['CANONICAL_PAGE_URL'])){
														$arSubItem['DETAIL_PAGE_URL'] = $arSubItem['CANONICAL_PAGE_URL'];
													}
													else{
														$arSubItem['DETAIL_PAGE_URL'] = $arSubItem['DETAIL_PAGE_URL'][key($arSubItem['DETAIL_PAGE_URL'])];
													}
												}
												?>
												<li class="font_sm"><a class="colored" href="<?=($arSubItem['SECTION_PAGE_URL'] ? $arSubItem['SECTION_PAGE_URL'] : $arSubItem['DETAIL_PAGE_URL'] );?>"><?=$arSubItem['NAME']?></a></li>
											<?endforeach;?>
										</ul>
									</div>
								<?endif;?>
								<?if($arItem['CHILD']):?>
									<div class="button_opener colored"><i class="fa fa-angle-down" aria-hidden="true"></i><?//=CMax::showIconSvg("arrow", SITE_TEMPLATE_PATH.'/images/svg/arrow_down_accordion.svg', '', '', true, false);?><span class="opener font_upper" data-open_text="<?=GetMessage('CLOSE_TEXT');?>" data-close_text="<?=GetMessage('OPEN_TEXT');?>"><?=GetMessage('OPEN_TEXT');?></span></div>
								<?endif;?>
								
												
								<a href="<?=$arItem['SECTION_PAGE_URL']?>" class="arrow_link colored_theme_hover_bg-el bordered-block rounded3 muted" title="<?=GetMessage('TO_ALL')?>"><?=CMax::showIconSvg("right-arrow", SITE_TEMPLATE_PATH.'/images/svg/arrow_right_list.svg', '', '');?></a>
								
							</div>
						</div>
					</div>
				</div>
			<?endforeach;?>
		</div>
	</div>
<?endif;?>
<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<?
$templateData = array(
	'MAP_ITEMS' => $arResult['MAP_ITEMS']
);
?>
<?if($arResult['ITEMS']):?>
	<div class="content_wrapper_block map_type_3 <?=$templateName;?>">
		<div class="maxwidth-theme wide">
			<div class="wrapper_block with_title title_right">
				<?$nCountItems = count($arResult['ITEMS']);?>
				<div class="block_container bordered <?=($nCountItems == 1 ? 'one' : '');?>">
					<div class="block_container_inner">
						<?if($arParams['TITLE_BLOCK'] || $arParams['TITLE_BLOCK_ALL']):?>
							<div class="top_block" <?=($nCountItems == 1 ? "style='display:none;'" : "")?>>
								<h3><?=$arParams['TITLE_BLOCK'];?></h3>
								<a href="<?=SITE_DIR.$arParams['ALL_URL'];?>" class="pull-right font_upper muted"><?=$arParams['TITLE_BLOCK_ALL'] ;?></a>
							</div>
						<?endif;?>
						<div class="items bordered" <?=($nCountItems == 1 ? "style='display:none;'" : "")?>>
							<div class="items-inner">
								<?foreach($arResult['ITEMS'] as $arItem):?>
									<?
									// edit/add/delete buttons for edit mode
									$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
									$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
									?>
									<div class="item" data-coordinates="<?=$arItem['DISPLAY_PROPERTIES']['MAP']['VALUE'];?>" id="<?=$this->GetEditAreaId($arItem['ID'])?>" data-id="<?=$arItem['ID']?>">
										<?=CMax::showIconSvg("addr ncolor", SITE_TEMPLATE_PATH."/images/svg/address.svg");?>
										<div class="title option-font-bold font_sm"><?=$arItem['NAME'].(($arItem['DISPLAY_PROPERTIES']['ADDRESS']['VALUE']) ? ', '.$arItem['DISPLAY_PROPERTIES']['ADDRESS']['VALUE'] : '')?></div>
										<?if($arItem['DISPLAY_PROPERTIES']['PHONE']['VALUE']):?>
											<div class="phones">
												<?if(is_array($arItem['DISPLAY_PROPERTIES']['PHONE']['VALUE'])):?>
													<?foreach($arItem['DISPLAY_PROPERTIES']['PHONE']['VALUE'] as $value):?>
														<div class="value"><a class="muted font_xs" rel= "nofollow" href="tel:<?=str_replace(array(' ', ',', '-', '(', ')'), '', $value)?>"><?=$value;?></a></div>
													<?endforeach;?>
												<?else:?>
													<div class="value"><a class="muted font_xs" rel= "nofollow" href="tel:<?=str_replace(array(' ', ',', '-', '(', ')'), '', $arItem['DISPLAY_PROPERTIES']['PHONE']['VALUE'])?>"><?=$arItem['DISPLAY_PROPERTIES']['PHONE']['VALUE'];?></a></div>
												<?endif;?>
											</div>
										<?endif;?>
									</div>
								<?endforeach;?>
							</div>
						</div>
						<div class="detail_items" <?=($nCountItems == 1 ? "style='display:block;'" : "")?>>
							<?foreach($arResult['ITEMS'] as $arItem):?>
								<div class="item" <?=($nCountItems == 1 ? "style='display:block;'" : "")?> data-coordinates="<?=$arItem['DISPLAY_PROPERTIES']['MAP']['VALUE'];?>" data-id="<?=$arItem['ID']?>">
									<div class="big_info">
										<?=CMax::prepareItemMapHtml($arItem, "N", $arParams, "Y");?>
									</div>
									<div class="top-close muted svg">
										<svg class="svg-close" width="14" height="14" viewBox="0 0 14 14"><path data-name="Rounded Rectangle 568 copy 16" class="cls-1" d="M1009.4,953l5.32,5.315a0.987,0.987,0,0,1,0,1.4,1,1,0,0,1-1.41,0L1008,954.4l-5.32,5.315a0.991,0.991,0,0,1-1.4-1.4L1006.6,953l-5.32-5.315a0.991,0.991,0,0,1,1.4-1.4l5.32,5.315,5.31-5.315a1,1,0,0,1,1.41,0,0.987,0.987,0,0,1,0,1.4Z" transform="translate(-1001 -946)"></path></svg>
									</div>
									<div class="buttons_block">
										<span class="btn btn-transparent-border-color animate-load" data-event="jqm" data-param-form_id="ASK" data-name="question"><?=GetMessage('SEND_MESSAGE_BUTTON');?>
										</span>
									</div>
								</div>
							<?endforeach;?>
						</div>
					</div>
				</div>
			</div>
<?endif;?>
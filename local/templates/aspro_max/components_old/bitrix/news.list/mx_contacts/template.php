<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<div class="item-views-wrapper <?=$templateName;?>">
	
	<?if($arResult['SECTIONS']):?>
		<div class="maxwidth-theme">
			<div class="row">
				<div class="col-md-12">
					<div class="contacts-stores ">
						<?foreach($arResult['SECTIONS'] as $si => $arSection):?>
							<?$bHasSection = (isset($arSection['SECTION']) && $arSection['SECTION'])?>
							<?if($bHasSection):?>
								<?// edit/add/delete buttons for edit mode
								$arSectionButtons = CIBlock::GetPanelButtons($arSection['SECTION']['IBLOCK_ID'], 0, $arSection['SECTION']['ID'], array('SESSID' => false, 'CATALOG' => true));
								$this->AddEditAction($arSection['SECTION']['ID'], $arSectionButtons['edit']['edit_section']['ACTION_URL'], CIBlock::GetArrayByID($arSection['SECTION']['IBLOCK_ID'], 'SECTION_EDIT'));
								$this->AddDeleteAction($arSection['SECTION']['ID'], $arSectionButtons['edit']['delete_section']['ACTION_URL'], CIBlock::GetArrayByID($arSection['SECTION']['IBLOCK_ID'], 'SECTION_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
								<!-- <div class="section_name" id="<?=$this->GetEditAreaId($arSection['SECTION']['ID'])?>">
									<h4><?=$arSection['SECTION']['NAME'];?></h4>
								</div> -->
							<?endif;?>
							<?foreach($arSection['ITEMS'] as $i => $arItem):?>
									
								<?
								// edit/add/delete buttons for edit mode
								$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
								$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
								// use detail link?
								$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen($arItem['DETAIL_TEXT']) ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1) : true);
								// preview picture
								$bImage = (isset($arItem['FIELDS']['PREVIEW_PICTURE']) && strlen($arItem['PREVIEW_PICTURE']['SRC']));
								$imageSrc = ($bImage ? $arItem['PREVIEW_PICTURE']['SRC'] : false);
								$imageDetailSrc = ($bImage ? $arItem['DETAIL_PICTURE']['SRC'] : false);
								$address = ($arItem['PROPERTIES']['ADDRESS']['VALUE'] ? ", ".$arItem['PROPERTIES']['ADDRESS']['VALUE'] : "");
								?>


								<div class="item properties clearfix sb_border_block mx_contact_item" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
									
									<div class="row">
										<div class="col-md-6 col-sm-6 col-xs-12 left-block-contacts">
											<?if($imageSrc):?>
												<div class="image pull-left">
													<a href="<?=$arItem["DETAIL_PAGE_URL"];?>">
														<img src="<?=\Aspro\Functions\CAsproMax::showBlankImg($imageSrc);?>" data-src="<?=$imageSrc;?>" alt="<?=$arItem['NAME'];?>" title="<?=$arItem['NAME'];?>" class="img-responsive lazy"/>
													</a>
												</div>
											<?endif;?>
											<div class="top-wrap">
												<div class="title font_mxs darken">
													<a href="<?=$arItem["DETAIL_PAGE_URL"];?>" class="darken">
														<?=$arItem['NAME'];?><?=$address;?>
													</a>
												</div>
												<div class="middle-prop">
													<?if($arItem['PROPERTIES']['MAP']['VALUE']):?>
														<div class="show_on_map font_upper colored_theme_text">
															<span class="text_wrap" data-coordinates="<?=$arItem['PROPERTIES']['MAP']['VALUE'];?>">
																<?=CMax::showIconSvg("on_map colored", SITE_TEMPLATE_PATH.'/images/svg/show_on_map.svg');?>
																<span class="text"><?=GetMessage('SHOW_ON_MAP')?></span>
															</span>
														</div>
													<?endif;?>
													
													<?if($arItem['PROPERTIES']['METRO']['VALUE']):?>
														<?foreach($arItem['PROPERTIES']['METRO']['VALUE'] as $metro):?>
															<div class="metro font_upper"><?=CMax::showIconSvg("metro colored", SITE_TEMPLATE_PATH."/images/svg/Metro.svg");?><span class="text muted777"><?=$metro;?></span></div>
														<?endforeach;?>
													<?endif;?>
												</div>
												<?if($arItem['PROPERTIES']['SCHEDULE']['VALUE']):?>
													<div class="schedule"><?=CMax::showIconSvg("clock colored", SITE_TEMPLATE_PATH."/images/svg/WorkingHours.svg");?><span class="text font_xs muted777"><?=$arItem['PROPERTIES']['SCHEDULE']['~VALUE']['TEXT'];?></span></div>
												<?endif;?>
												<?if($arItem['DISPLAY_PROPERTIES']):?>
													<div>
														<?foreach($arItem["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
															<?if($arProperty["DISPLAY_VALUE"]):?>
																<div class="muted custom_prop <?=strtolower($pid);?>">
																	<div class="icons-text schedule grey s25">
																		<i class="fa"></i>
																		<span class="text_custom">
																			<span class="name"><?=$arProperty["NAME"]?>:&nbsp;</span>
																			<span class="value">
																				<?if(is_array($arProperty["DISPLAY_VALUE"])):?>
																					<?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
																				<?else:?>
																					<?=$arProperty["DISPLAY_VALUE"];?>
																				<?endif?>
																			</span>
																		</span>
																	</div>
																</div>
															<?endif?>
														<?endforeach;?>
													</div>
												<?endif;?>
											</div>
										</div>
										<div class="col-md-6 col-sm-6 col-xs-12 right-block-contacts">
											<div class="item-body">
												<div class="row">
													<?if($arItem['PROPERTIES']['PHONE']['VALUE']):?>
														<div class="phones col-md-12 col-sm-12 col-xs-12">
															<div class="title font_upper muted">Телефон</div>
															<?foreach($arItem['PROPERTIES']['PHONE']['VALUE'] as $phone):?>
																<div class="phone font_sm darken">
																	<a href="tel:+<?=str_replace(array(' ', ',', '-', '(', ')'), '', $phone);?>" class="black"><?=$phone;?></a>
																</div>
															<?endforeach;?>
														</div>
													<?endif?>
													<?if($arItem['PROPERTIES']['EMAIL']['VALUE']):?>
														<div class="emails col-md-12 col-sm-12 col-xs-12">
															<div class="title font_upper muted">E-mail</div>
															<div class="email font_sm">
																<a class="dark-color" href="mailto:<?=$arItem['DISPLAY_PROPERTIES']['EMAIL']['VALUE'];?>"><?=$arItem['PROPERTIES']['EMAIL']['VALUE'];?></a>
															</div>
														</div>
													<?endif?>
													
													
													
												</div>
											</div>
										</div>

									</div>
								</div>
							<?endforeach;?>
						<?endforeach;?>
					</div>
				</div>
			</div>
		</div>
	<?endif;?>
</div>
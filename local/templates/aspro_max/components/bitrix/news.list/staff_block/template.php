<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die();?>
<?$this->setFrameMode(true);?>
<?use \Bitrix\Main\Localization\Loc;?>
<?if($arResult['SECTIONS']):?>
	<div class="item-views staff1 <?=($arParams['LINKED_MODE'] == 'Y' ? 'linked' : '')?> within">
		<div class="row">
			<div class="col-md-12">
				<?if($arParams['TITLE_BLOCK'] || $arParams['TITLE_BLOCK_ALL']):?>
					<div class="top_block">
						<h3><?=$arParams['TITLE_BLOCK'];?></h3>
						<?if($arParams['TITLE_BLOCK_ALL']):?>
							<a href="<?=SITE_DIR.$arParams['ALL_URL'];?>" class="pull-right font_upper muted"><?=$arParams['TITLE_BLOCK_ALL'] ;?></a>
						<?endif;?>
					</div>
				<?endif;?>
				<?
				global $arTheme;
				/*$slideshowSpeed = abs(intval($arTheme['PARTNERSBANNER_SLIDESSHOWSPEED']['VALUE']));
				$animationSpeed = abs(intval($arTheme['PARTNERSBANNER_ANIMATIONSPEED']['VALUE']));
				$bAnimation = (bool)$slideshowSpeed;
				$isNormalBlock = (isset($arParams['NORMAL_BLOCK']) && $arParams['NORMAL_BLOCK'] == 'Y');*/
				
				$col = ($arParams['COUNT_IN_LINE'] ? $arParams['COUNT_IN_LINE'] : 3);
				$size = floor(12/$col);
				$size_md = floor(12/($col-1));
				?>
				<?if($arParams["DISPLAY_TOP_PAGER"]):?>
					<div class="pagination_nav">	
						<?=$arResult["NAV_STRING"]?>
					</div>
				<?endif;?>
				
				<div class="group-content">
					<?foreach($arResult['SECTIONS'] as $SID => $arSection):?>
						<div class="tab-pane">
							<?if($arParams['SHOW_SECTION_PREVIEW_DESCRIPTION'] == 'Y'):?>
								<?if($arParams['SHOW_SECTION_NAME'] != 'N'):?>
									<?// section name?>
									<?if(strlen($arSection['NAME'])):?>
										<h3><?=$arSection['NAME']?></h3>
									<?endif;?>
								<?endif;?>

								<?// section description text/html?>
								<?if(strlen($arSection['DESCRIPTION']) && strpos($_SERVER['REQUEST_URI'], 'PAGEN') === false):?>
									<div class="text_before_items">
										<?=$arSection['DESCRIPTION']?>
									</div>
								<?endif;?>
							<?endif;?>

							<div class="items row flexbox">
								<?foreach($arSection['ITEMS'] as $i => $arItem):?>
									<?
									// edit/add/delete buttons for edit mode
									$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
									$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => Loc::getMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
									// use detail link?
									$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen($arItem['DETAIL_TEXT']) ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1) : true);
									// preview image
									$bImage = strlen($arItem['FIELDS']['PREVIEW_PICTURE']['SRC']);
									$imageSrc = ($bImage ? $arItem['FIELDS']['PREVIEW_PICTURE']['SRC'] : SITE_TEMPLATE_PATH.'/images/svg/noimage_content.svg');

									$bShowMessButton = isset($arItem['DISPLAY_PROPERTIES']['SEND_MESSAGE_BUTTON']) && $arItem['DISPLAY_PROPERTIES']['SEND_MESSAGE_BUTTON']['VALUE_XML_ID'] == 'Y';
									
									// show active date period
									$bActiveDate = strlen($arItem['DISPLAY_PROPERTIES']['PERIOD']['VALUE']) || ($arItem['DISPLAY_ACTIVE_FROM'] && in_array('DATE_ACTIVE_FROM', $arParams['FIELD_CODE']));
									?>
									<div class="col-lg-<?=$size;?> col-md-<?=$size_md;?> col-sm-4 col-xs-6 item-wrap">
										<div class="item " data-id="<?=$arItem['ID'];?>">
											<div class="wrap  rounded3 box-shadow clearfix" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
												<?if($imageSrc):?>
													<div class="image<?=($bImage ? "" : " wti" );?>">
														<div class="wrap">
															<?if($bDetailLink):?><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?endif;?>
																<?$img = ($bImage ? CFile::ResizeImageGet($arItem['PREVIEW_PICTURE']['ID'], array('width' => 560, 'height' => 10000), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true) : array());?>
																<?$img['src'] = (strlen($img['src']) ? $img['src'] : SITE_TEMPLATE_PATH.'/images/svg/noimage_content.svg');?>
																<img class="img-responsive lazy" data-src="<?=$img['src']?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($img['src']);?>" alt="<?=($bImage ? $arItem['PREVIEW_PICTURE']['ALT'] : $arItem['NAME'])?>" title="<?=($bImage ? $arItem['PREVIEW_PICTURE']['TITLE'] : $arItem['NAME'])?>" />
															<?if($bDetailLink):?></a><?endif;?>
														</div>
													</div>
												<?endif;?>
												
												<div class="body-info staff-srollbar-custom bordered <?=($bShowMessButton ? 'with-mess-button' : '')?>" style="height: 30%;">
													<div class="top-block-wrapper">
														<?// post?>
														<?if((isset($arItem['PROPERTIES']['POST']) && $arItem['PROPERTIES']['POST']) && (isset($arItem['PROPERTIES']['POST']['VALUE']) && $arItem['PROPERTIES']['POST']['VALUE'])):?>
															<div class="post muted font_upper"><?=$arItem['PROPERTIES']['POST']['VALUE'];?></div>
														<?endif;?>
														<?// element name?>
														<?if(strlen($arItem['FIELDS']['NAME'])):?>
															<div class="title font_md">
																<?if($bDetailLink):?><a class="dark-color" href="<?=$arItem['DETAIL_PAGE_URL']?>"><?endif;?>
																	<?=$arItem['NAME']?>
																<?if($bDetailLink):?></a><?endif;?>
															</div>
														<?endif;?>
													</div>

													<?// props?>
													<?if((isset($arItem['MIDDLE_PROPS']) && $arItem['MIDDLE_PROPS']) || isset($arItem['SOCIAL_PROPS']) && $arItem['SOCIAL_PROPS'] || (isset($arItem['DISPLAY_PROPERTIES']['SEND_MESSAGE_BUTTON']) && $arItem['DISPLAY_PROPERTIES']['SEND_MESSAGE_BUTTON']['VALUE_XML_ID'] == 'Y')):?>
														<div class="middle-props bottom-block">
															<div class="props">
																<?if(isset($arItem['MIDDLE_PROPS']) && $arItem['MIDDLE_PROPS']):?>
																	<?foreach($arItem['MIDDLE_PROPS'] as $key => $arProp):?>
																		<div class="prop">
																			<div class="title-prop font_upper"><?=$arProp['NAME']?></div>
																			<div class="value font_sm"><?if($key == 'EMAIL'):?><!-- noindex --><a class="dark-color" href="mailto:<?=$arProp['VALUE'];?>" target="_blank" rel="nofollow"><?endif;?><?=$arProp['VALUE'];?><?if($key == 'EMAIL'):?></a><!-- /noindex --><?endif;?></div>
																		</div>
																	<?endforeach;?>
																<?endif?>

																<?if(isset($arItem['SOCIAL_PROPS']) && $arItem['SOCIAL_PROPS']):?>
																	<div class="bottom-soc-props social_props">
																		<!-- noindex -->
																			<?foreach($arItem['SOCIAL_PROPS'] as $arProp)://var_dump($arProp['FILE']);?>
																				<a href="<?=$arProp['VALUE'];?>" target="_blank" rel="nofollow" class="value <?=strtolower($arProp['CODE']);?>"><?//=$arProp['VALUE'];?>
																					<?//=(isset($arProp['FILE']) && $arProp['FILE'] ? CPriority::showIconSvg($arProp['FILE']) : '');?>
																					<?=(isset($arProp['FILE']) && $arProp['FILE'] ? CMax::showIconSvg(strtolower($arProp['CODE']), $arProp['FILE']) : '');?>
																					<?//=CMax::showIconSvg("subscribe", SITE_TEMPLATE_PATH."/images/svg/subscribe_insidepages.svg", "", "colored_theme_hover_bg-el-svg", true, false);?>

																				</a>
																			<?endforeach;?>
																		<!-- /noindex -->
																	</div>
																<?endif;?>
															</div>
															
														</div>
													<?endif;?>
													
												</div>
											    
												<?if($bShowMessButton):?>
													<div class="send_message_button">
														<span class="animate-load btn btn-default btn-xs" data-event="jqm" data-param-form_id="ASK_STAFF" data-autoload-staff="<?=CMax::formatJsName($arItem['NAME'])?>" data-name="ask_staff"><?=(strlen($arParams['SEND_MESSAGE_BUTTON_TEXT']) ? $arParams['SEND_MESSAGE_BUTTON_TEXT'] : Loc::getMessage('SEND_MESSAGE_BUTTON_TEXT'))?></span>
													</div>
												<?endif?>
											    
											</div>
										</div>
									</div>
								<?endforeach;?>
							</div>
						</div>
					<?endforeach;?>
				</div>
				<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
					<div class="pagination_nav">		
						<?=$arResult["NAV_STRING"]?>
					</div>
				<?endif;?>				
			</div>
		</div>
	</div>
<?endif;?>
<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die();?>
<?$this->setFrameMode(true);?>
<?use \Bitrix\Main\Localization\Loc;?>
<?if($arResult['SECTIONS']):?>
	<div class="item-views front staff-items staff2 within <?=($arParams['LINKED_MODE'] == 'Y' ? 'linked' : '')?> type_2 type_3">
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
				$slideshowSpeed = abs(intval($arTheme['PARTNERSBANNER_SLIDESSHOWSPEED']['VALUE']));
				$animationSpeed = abs(intval($arTheme['PARTNERSBANNER_ANIMATIONSPEED']['VALUE']));
				$bAnimation = (bool)$slideshowSpeed;
				$isNormalBlock = (isset($arParams['NORMAL_BLOCK']) && $arParams['NORMAL_BLOCK'] == 'Y');
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
							<div class="items">
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

									// show active date period
									$bActiveDate = strlen($arItem['DISPLAY_PROPERTIES']['PERIOD']['VALUE']) || ($arItem['DISPLAY_ACTIVE_FROM'] && in_array('DATE_ACTIVE_FROM', $arParams['FIELD_CODE']));
									?>
									<div class="item bordered box-shadow rounded3 colored_theme_hover_bg-block">
										<div class="wrap clearfix" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
											<?if($imageSrc):?>
												<div class="image<?=($bImage ? "" : " wti" );?> rounded">
													<?if($bDetailLink):?><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?endif;?>
														<?$img = ($bImage ? CFile::ResizeImageGet($arItem['PREVIEW_PICTURE']['ID'], array('width' => 330, 'height' => 10000), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true) : array());?>
														<?$img['src'] = (strlen($img['src']) ? $img['src'] : SITE_TEMPLATE_PATH.'/images/svg/noimage_content.svg');?>
														<img class="img-responsive lazy" data-src="<?=$img['src']?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($img['src']);?>" alt="<?=($bImage ? $arItem['PREVIEW_PICTURE']['ALT'] : $arItem['NAME'])?>" title="<?=($bImage ? $arItem['PREVIEW_PICTURE']['TITLE'] : $arItem['NAME'])?>" />
													<?if($bDetailLink):?></a><?endif;?>
												</div>
											<?endif;?>
											<div class="body-info">
												<div class="top-block-wrapper">
													<?// post?>
													<?if((isset($arItem['PROPERTIES']['POST']) && $arItem['PROPERTIES']['POST']) && (isset($arItem['PROPERTIES']['POST']['VALUE']) && $arItem['PROPERTIES']['POST']['VALUE'])):?>
														<div class="post muted font_upper"><?=$arItem['PROPERTIES']['POST']['VALUE'];?></div>
													<?endif;?>
													<?// element name?>
													<?if(strlen($arItem['FIELDS']['NAME'])):?>
														<div class="title">
															<?if($bDetailLink):?><a class="dark-color" href="<?=$arItem['DETAIL_PAGE_URL']?>"><?endif;?>
																<?=$arItem['NAME']?>
															<?if($bDetailLink):?></a><?endif;?>
														</div>
													<?endif;?>
												</div>

												<?// props?>
												<?if((isset($arItem['MIDDLE_PROPS']) && $arItem['MIDDLE_PROPS']) || (isset($arItem['DISPLAY_PROPERTIES']['SEND_MESSAGE_BUTTON']) && $arItem['DISPLAY_PROPERTIES']['SEND_MESSAGE_BUTTON']['VALUE_XML_ID'] == 'Y')):?>
													<div class="middle-props">
														<?if(isset($arItem['DISPLAY_PROPERTIES']['SEND_MESSAGE_BUTTON']) && $arItem['DISPLAY_PROPERTIES']['SEND_MESSAGE_BUTTON']['VALUE_XML_ID'] == 'Y'):?>
															<div class="send_message_button">
															    <span class="animate-load btn btn-transparent-border-color white btn-xs" data-event="jqm" data-param-form_id="ASK_STAFF" data-autoload-staff="<?=CMax::formatJsName($arItem['NAME'])?>" data-name="ask_staff"><?=(strlen($arParams['SEND_MESSAGE_BUTTON_TEXT']) ? $arParams['SEND_MESSAGE_BUTTON_TEXT'] : Loc::getMessage('SEND_MESSAGE_BUTTON_TEXT'))?></span>
															</div>
														<?endif?>
														<?if(isset($arItem['MIDDLE_PROPS']) && $arItem['MIDDLE_PROPS']):?>
															<div class="props">
																<?foreach($arItem['MIDDLE_PROPS'] as $key => $arProp):?>
																	<div class="prop">
																		<div class="title-prop font_upper"><?=$arProp['NAME']?></div>
																		<div class="value font_sm"><?if($key == 'EMAIL'):?><!-- noindex --><a class="dark-color" href="mailto:<?=$arProp['VALUE'];?>" target="_blank" rel="nofollow"><?endif;?><?=$arProp['VALUE'];?><?if($key == 'EMAIL'):?></a><!-- /noindex --><?endif;?></div>
																	</div>
																<?endforeach;?>
															</div>
														<?endif?>
													</div>
												<?endif;?>
												<?if(isset($arItem['SOCIAL_PROPS']) && $arItem['SOCIAL_PROPS']):?>
													<div class="bottom-soc-props social_props">
														<!-- noindex -->
															<?foreach($arItem['SOCIAL_PROPS'] as $arProp):?>
																<a href="<?=$arProp['VALUE'];?>" target="_blank" rel="nofollow" class="value <?=strtolower($arProp['CODE']);?>"><?//=$arProp['VALUE'];?>
																	<?=(isset($arProp['FILE']) && $arProp['FILE'] ? CMax::showIconSvg(strtolower($arProp['CODE']), $arProp['FILE']) : '');?>
																	<?//=(isset($arProp['FILE']) && $arProp['FILE'] ? CPriority::showIconSvg($arProp['FILE']) : '');?>
																</a>
															<?endforeach;?>
														<!-- /noindex -->
													</div>
												<?endif;?>
											    
												<?// element preview text?>
												<?if(strlen($arItem['FIELDS']['PREVIEW_TEXT'])):?>
												    <div class="previewtext">
													<?if($arItem['PREVIEW_TEXT_TYPE'] == 'text'):?>
													    <p><?=$arItem['FIELDS']['PREVIEW_TEXT']?></p>
													<?else:?>
													    <?=$arItem['FIELDS']['PREVIEW_TEXT']?>
													<?endif;?>
												    </div>
												<?endif;?>
											
												<?if($bDetailLink):?>								
													<a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="arrow_link colored_theme_hover_bg-el bordered-block rounded3 muted" title="<?=GetMessage('TO_ALL')?>"><?=CMax::showIconSvg("right-arrow", SITE_TEMPLATE_PATH.'/images/svg/arrow_right_list.svg', '', '');?></a>
												<?endif;?>
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
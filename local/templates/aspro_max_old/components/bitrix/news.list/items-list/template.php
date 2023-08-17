<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<?$onlyImgPart = isset($arParams['ONLY_IMG_MODE']) && $arParams['ONLY_IMG_MODE'] == 'Y';
$licensesMode = isset($arParams['LICENSES_MODE']) && $arParams['LICENSES_MODE'] == 'Y';
$documentsMode = isset($arParams['DOCUMENTS_MODE']) && $arParams['DOCUMENTS_MODE'] == 'Y';
$bIsAjax = isset($arParams['IS_AJAX']) && $arParams['IS_AJAX'] == true;
?>
<?if($arResult['SECTIONS']):?>
	<?if($arParams['SHOW_TITLE'] == 'Y'):?>
		<div class="title-tab-heading visible-xs"><?=$arParams["T_TITLE"];?></div>
	<?endif;?>
<?if (!$bIsAjax):?>
<div class="item-views items-list1 <?=($onlyImgPart ? 'only-img' : '')?> <?=($documentsMode ? 'documents-mode' : '');?> <?=($licensesMode ? 'licenses-mode' : '');?> <?=$arParams['VIEW_TYPE']?> <?=$arParams['VIEW_TYPE']?>-type-block <?=($arParams['SHOW_TABS'] == 'Y' ? 'with_tabs' : '')?> <?=($arParams['IMAGE_POSITION'] ? 'image_'.$arParams['IMAGE_POSITION'] : '')?> <?=($templateName = $component->{'__parent'}->{'__template'}->{'__name'})?>" data-hash="Y">
<?endif;?>
	<?// top pagination?>
	<?if($arParams['DISPLAY_TOP_PAGER']):?>
		<?=$arResult['NAV_STRING']?>
	<?endif;?>

		<?// tabs?>
		<?if($arParams['SHOW_TABS'] == 'Y'):?>
			<div class="tabs">
				<div class="arrow_scroll">
					<ul class="nav nav-tabs font_upper_md">
						<?$i = 0;?>
						<?foreach($arResult['SECTIONS'] as $SID => $arSection):?>
							<?if(!$SID) continue;?>
							<li class="<?=$i++ == 0 ? 'active' : ''?> rounded3 bordered"><a data-toggle="tab" href="#<?=$this->GetEditAreaId($arSection['ID'])?>"><?=$arSection['NAME']?></a></li>
						<?endforeach;?>
					</ul>
				</div>	
		<?endif;?>
			<?if (!$bIsAjax):?>
				<div class="<?=($arParams['SHOW_TABS'] == 'Y' ? 'tab-content' : 'group-content')?>">
			<?endif;?>
					<?// group elements by sections?>
					<?foreach($arResult['SECTIONS'] as $SID => $arSection):?>
						<?
						// edit/add/delete buttons for edit mode
						$arSectionButtons = CIBlock::GetPanelButtons($arSection['IBLOCK_ID'], 0, $arSection['ID'], array('SESSID' => false, 'CATALOG' => true));
						$this->AddEditAction($arSection['ID'], $arSectionButtons['edit']['edit_section']['ACTION_URL'], CIBlock::GetArrayByID($arSection['IBLOCK_ID'], 'SECTION_EDIT'));
						$this->AddDeleteAction($arSection['ID'], $arSectionButtons['edit']['delete_section']['ACTION_URL'], CIBlock::GetArrayByID($arSection['IBLOCK_ID'], 'SECTION_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
						?>
						<?if (!$bIsAjax):?>
						<div id="<?=$this->GetEditAreaId($arSection['ID'])?>" class="tab-pane <?=(!$si++ || !$arSection['ID'] ? 'active' : '')?>">

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
									<?if($arParams['SHOW_SECTION_DESC_DIVIDER'] == 'Y'):?>
										<hr class="sect-divider" />
									<?endif;?>
								<?endif;?>
							<?endif;?>

							<?// show section items?>
							<?if($arParams['VIEW_TYPE'] !== 'accordion'):?>
								<div class="row sid items <?=($arParams['VIEW_TYPE'] == 'table' ? 'flexbox' : '')?><?=($arParams['MOBILE_SCROLLED'] === 'Y' ? ' mobile-overflow mobile-margin-16 mobile-compact swipeignore' : '');?>">
							<?endif;?>
						<?endif;?>

								<?foreach($arSection['ITEMS'] as $i => $arItem):?>
									<?
									// edit/add/delete buttons for edit mode
									$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
									$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
									// use detail link?
									$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen($arItem['DETAIL_TEXT']) ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1) : true);
									// preview picture
									$bImage = isset($arItem['FIELDS']['PREVIEW_PICTURE']) && strlen($arItem['PREVIEW_PICTURE']['SRC']);
									$imageSrc = ($bImage ? $arItem['PREVIEW_PICTURE']['SRC'] : SITE_TEMPLATE_PATH.'/images/svg/noimage_brand.svg');
									$imageDetailSrc = ($bImage ? $arItem['DETAIL_PICTURE']['SRC'] : false);
									// show active date period
									$bActiveDate = strlen($arItem['DISPLAY_PROPERTIES']['PERIOD']['VALUE']) || ($arItem['DISPLAY_ACTIVE_FROM'] && in_array('DATE_ACTIVE_FROM', $arParams['FIELD_CODE']));

									
									$arFile = CMax::get_file_info($arItem['FIELDS']['DETAIL_PICTURE']['ID']);
                                	$fileSize = CMax::filesize_format($arFile['FILE_SIZE']);
									
									$arDocFile = array();
									$docFileSize = $docFileType = '';

                                    if(isset($arItem['DISPLAY_PROPERTIES']['DOCUMENT']) && $arItem['DISPLAY_PROPERTIES']['DOCUMENT']['VALUE']){
                                    	$arDocFile = CMax::GetFileInfo($arItem['DISPLAY_PROPERTIES']['DOCUMENT']['VALUE']);
                                    	//var_dump($arDocFile['TYPE']);
                                    	$docFileSize = $arDocFile['FILE_SIZE_FORMAT'];
                                    	$docFileType = $arDocFile['TYPE'];
                                    	$bDocImage = false;
                                    	if($docFileType == 'jpg' || $docFileType == 'jpeg' || $docFileType == 'bmp' || $docFileType == 'gif' || $docFileType == 'png'){
                                    		$bDocImage = true;
                                    	}

                                    }
                                    
									
									?>

									<?ob_start();?>
										<?// element name?>
										<?if(strlen($arItem['FIELDS']['NAME'])):?>
											
											<?if($documentsMode):?>
												<div class="title">
													<?if($arDocFile['SRC']):?><a href="<?=$arDocFile['SRC']?>" class="dark-color <?=($bDocImage ? 'fancy' : '')?>" data-caption="<?=$arItem['NAME'];?>" target="_blank"><?endif;?>
														<?=$arItem['NAME']?>
													<?if($arDocFile['SRC']):?></a><?endif;?>
													<?if($docFileSize):?>
														<div class="size muted font_xs"><?=$docFileSize;?></div>
													<?endif;?>
												</div>
											<?else:?>
												<div class="title <?=($licensesMode && $arParams['VIEW_TYPE'] == 'table' ? '' : 'font_mlg')?> ">
													<?if($bDetailLink):?><a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="dark-color"><?endif;?>
														<?=$arItem['NAME']?>
													<?if($bDetailLink):?></a><?endif;?>
													<?if($licensesMode && $fileSize):?>
														<?if($arParams['VIEW_TYPE'] == 'table'):?>
															<div class="size muted font_xs"><?=$fileSize;?></div>
														<?else:?>
															<span class="size muted font_xs"><?=$fileSize;?></span>
														<?endif;?>
	                                                <?endif;?>
												</div>
											<?endif;?>
										<?endif;?>

										<?if(!(($licensesMode || $documentsMode) && $arParams['VIEW_TYPE'] == 'table')):?>
											<?// date active period?>
											<?if($bActiveDate):?>
												<div class="period">
													<?if(strlen($arItem['DISPLAY_PROPERTIES']['PERIOD']['VALUE'])):?>
														<span class="date"><?=$arItem['DISPLAY_PROPERTIES']['PERIOD']['VALUE']?></span>
													<?else:?>
														<span class="date"><?=$arItem['DISPLAY_ACTIVE_FROM']?></span>
													<?endif;?>
												</div>
												<?unset($arItem['DISPLAY_PROPERTIES']['PERIOD']);?>
											<?endif;?>

											<?// element preview text?>
											<?if(strlen($arItem['FIELDS']['PREVIEW_TEXT']) || strlen($arItem['FIELDS']['DETAIL_TEXT'])):?>
												<div class="previewtext <?=($arParams['VIEW_TYPE'] == 'list' ? 'font_sm line-h-165' : '')?> <?=($arParams['VIEW_TYPE'] == 'table' ? 'font_xs' : '')?> muted777 ">
													<div>
														<?if(strlen($arItem['FIELDS']['PREVIEW_TEXT'])):?>
															<?if($arItem['PREVIEW_TEXT_TYPE'] == 'text'):?>
																<p><?=$arItem['FIELDS']['PREVIEW_TEXT']?></p>
															<?else:?>
																<?=$arItem['FIELDS']['PREVIEW_TEXT']?>
															<?endif;?>
														<?endif;?>
													</div>

													<?// element detail text?>
													<div>
														<?if(strlen($arItem['FIELDS']['DETAIL_TEXT'])):?>
															<?if($arItem['DETAIL_TEXT_TYPE'] == 'text'):?>
																<p><?=$arItem['FIELDS']['DETAIL_TEXT']?></p>
															<?else:?>
																<?=$arItem['FIELDS']['DETAIL_TEXT']?>
															<?endif;?>
														<?endif;?>
													</div>
												</div>
											<?endif;?>
											

											<?// button?>
											<?if(strlen($arItem['DISPLAY_PROPERTIES']['TITLE_BUTTON']['VALUE']) && strlen($arItem['DISPLAY_PROPERTIES']['LINK_BUTTON']['VALUE'])):?>
												<div class="button_wrap">
													<a class="btn btn-default btn-sm" href="<?=$arItem['DISPLAY_PROPERTIES']['LINK_BUTTON']['VALUE']?>" target="_blank">
														<?=$arItem['DISPLAY_PROPERTIES']['TITLE_BUTTON']['VALUE']?>
													</a>
												</div>
												<?unset($arItem['DISPLAY_PROPERTIES']['TITLE_BUTTON']);?>
												<?unset($arItem['DISPLAY_PROPERTIES']['LINK_BUTTON']);?>
											<?endif;?>

											<?// element display properties?>
											<?if($arItem['DISPLAY_PROPERTIES']):?>
												<div class="properties">
													<?foreach($arItem['DISPLAY_PROPERTIES'] as $PCODE => $arProperty):?>
														<?if(in_array($PCODE, array('PERIOD', 'TITLE_BUTTON', 'LINK_BUTTON'))) continue;?>
														<?if($documentsMode && $PCODE == 'DOCUMENT') continue;?>
														<?//$bIconBlock = ($PCODE == 'EMAIL' || $PCODE == 'PHONE' || $PCODE == 'SITE');?>
														<div class="inner-wrapper">
															<div class="property <?=($bIconBlock ? "icon-block" : "");?> <?=strtolower($PCODE);?>">
																<?if(!$bIconBlock):?>
																	<div class="title-prop font_upper muted777"><?=$arProperty['NAME']?></div>
																<?endif;?>
																<div class="value darken">
																	<?if(is_array($arProperty['DISPLAY_VALUE'])):?>
																		<?$val = implode('&nbsp;/&nbsp;', $arProperty['DISPLAY_VALUE']);?>
																	<?else:?>
																		<?$val = $arProperty['DISPLAY_VALUE'];?>
																	<?endif;?>
																	<?if($PCODE == 'SITE'):?>
																		<!--noindex-->
																		<a href="<?=(strpos($arProperty['VALUE'], 'http') === false ? 'http://' : '').$arProperty['VALUE'];?>" rel="nofollow" target="_blank" class="dark-color">
																			<?=strpos($arProperty['VALUE'], '?') === false ? $arProperty['VALUE'] : explode('?', $arProperty['VALUE'])[0]?>
																		</a>
																		<!--/noindex-->
																	<?elseif($PCODE == 'EMAIL'):?>
																		<a href="mailto:<?=$val?>"><?=$val?></a>
																	<?elseif($PCODE == 'PHONE'):?>
																		<a href="tel:<?=str_replace(array(' ', ',', '-', '(', ')'), '', $arProperty['VALUE']);?>" class="dark-color"><?=$arProperty['VALUE']?></a>
																	<?else:?>
																		<?=$val?>
																	<?endif;?>
																</div>
															</div>
														</div>
													<?endforeach;?>
												</div>
											<?endif;?>
											<?if($arParams['FORM'] == 'Y'):?>
												<button class="btn btn-default" data-event="jqm" data-name="resume" data-param-id="<?=$arParams["FORM_ID"]?>" data-autoload-POST="<?=CMax::formatJsName($arItem['NAME']);?>" data-autohide=""><?=$arParams["FORM_BUTTON_TITLE"];?></button>
											<?endif;?>
										<?endif;?>
										
									<?$textPart = ob_get_clean();?>

									<?ob_start();?>
										<?if($bImage || $onlyImgPart):?>
											<div class="image <?=($bImage ? ' w-picture' : ' wo-picture wpi')?>">
												<?if($bDetailLink):?>
													<a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="shine">
												<?elseif(isset($arItem['FIELDS']['DETAIL_PICTURE'])):?>
													<a href="<?=$imageDetailSrc?>" alt="<?=($bImage ? $arItem['PREVIEW_PICTURE']['ALT'] : $arItem['NAME'])?>" title="<?=($bImage ? $arItem['PREVIEW_PICTURE']['TITLE'] : $arItem['NAME'])?>" data-caption="<?=$arItem['NAME'];?>" class="img-inside fancy shine">
												<?endif;?>
													<img src="<?=\Aspro\Functions\CAsproMax::showBlankImg($imageSrc);?>" data-src="<?=$imageSrc?>" alt="<?=($bImage ? $arItem['PREVIEW_PICTURE']['ALT'] : $arItem['NAME'])?>" title="<?=($bImage ? $arItem['PREVIEW_PICTURE']['TITLE'] : $arItem['NAME'])?>" class="img-responsive lazy" />
												<?if($bDetailLink):?>
													</a>
												<?elseif(isset($arItem['FIELDS']['DETAIL_PICTURE'])):?>
													<?/*<span class="zoom"></span>*/?>
														<?if($licensesMode && $arParams['VIEW_TYPE'] == 'table'):?>
															<span class="zoom_wrap colored_theme_hover_bg-el bordered rounded3 muted">
																<?=CMax::showIconSvg("zoom-arrow", SITE_TEMPLATE_PATH.'/images/svg/enlarge.svg', '', '');?>
															</span>
														<?endif;?>
													</a>
												<?endif;?>
											</div>
										<?elseif($documentsMode && $arDocFile):?>
											<div class="file_type <?=$docFileType;?>">
												<i class="icon"></i>
											</div>
										<?endif;?>
									<?$imagePart = ob_get_clean();?>


									<?if($arParams['VIEW_TYPE'] == 'list'):?>
										<div class=" col-md-12">
											<div class="item colored_theme_hover_bg-block <?=($bImage ? '' : ' wti')?> box-shadow bordered clearfix " id="<?=$this->GetEditAreaId($arItem['ID'])?>">
												<?if($bImage || $arDocFile):?>
													<?=$imagePart?>
												<?endif;?>
												<div class="body-info">
													<?=$textPart?>
													<?if($bDetailLink):?>
														<div class="link-block-more">
															<a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="btn-inline sm rounded black"><?=GetMessage('TO_ALL')?><i class="fa fa-angle-right"></i></a>
														</div>
													<?endif;?>
													<?if(isset($arItem['FIELDS']['DETAIL_PICTURE'])):?>
														<a href="<?=$imageDetailSrc?>" data-caption="<?=$arItem['NAME'];?>" class="fancy zoom_wrap colored_theme_hover_bg-el bordered rounded3 muted ">
															<?/*<span class="zoom colored_theme_hover_bg-el bordered"></span>*/?>
															<?=CMax::showIconSvg("zoom-arrow", SITE_TEMPLATE_PATH.'/images/svg/enlarge.svg', '', '');?>
														</a>
													<?endif;?>
													<?if($documentsMode && $arDocFile):?>
														<a href="<?=$arDocFile['SRC']?>" class="colored_theme_hover_bg-el bordered dark-color rounded3 muted <?=($bDocImage ? 'fancy zoom_wrap' : 'download_wrap')?>" data-caption="<?=$arItem['NAME'];?>" target="_blank">								<?if($bDocImage):?>
																<?=CMax::showIconSvg("zoom-arrow", SITE_TEMPLATE_PATH.'/images/svg/enlarge.svg', '', '');?>
															<?else:?>
																<?=CMax::showIconSvg("download-arrow", SITE_TEMPLATE_PATH.'/images/svg/download.svg', '', '');?>
															<?endif;?>
															<?/*<span class="<?=($bDocImage ? 'zoom' : 'download')?> colored_theme_hover_bg-el bordered"></span>*/?>
														</a>
													<?endif;?>
												</div>
											</div>
										</div>
									<?elseif($arParams['VIEW_TYPE'] == 'table'):?>
										<div class="item-width-261 box-shadow bordered colored_theme_hover_bg-block item-wrap col-md-<?=floor(12 / $arParams['COUNT_IN_LINE'])?> col-sm-<?=floor(12 / round($arParams['COUNT_IN_LINE'] / 2))?> col-xs-12">
											<div class="item  <?=($bImage ? '' : ' wti')?>" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
												<div class="row">
													<div class="col-md-12">
														<?if(!($bImage || $arDocFile) && !$onlyImgPart):?>
															<div class="text"><?=$textPart?></div>
														<?elseif($onlyImgPart):?>
															<?=$imagePart?>
															<?// element name?>
															<?if(strlen($arItem['FIELDS']['NAME'])):?>
																<div class="title font_upper muted">
																	<?if($bDetailLink):?><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?endif;?>
																		<?=$arItem['NAME']?>
																	<?if($bDetailLink):?></a><?endif;?>
																</div>
															<?endif;?>
														<?else:?>
															<?=$imagePart?>
															<div class="text"><?=$textPart?></div>
														<?endif;?>
													</div>
												</div>
												<?if($documentsMode && $arDocFile):?>
													<a href="<?=$arDocFile['SRC']?>" class="link_absolute <?=($bDocImage ? 'fancy' : '')?>" data-caption="<?=$arItem['NAME'];?>" target="_blank"></a>
												<?endif;?>
											</div>
										</div>
									<?elseif($arParams['VIEW_TYPE'] == 'accordion'):?>
										<div class="accordion-type-1">
											<div class="item item-accordion-wrapper <?=($bImage ? '' : ' wti')?>  bordered box-shadow" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
												<div class="accordion-head accordion-close colored_theme_hover_bg-block font_md" data-toggle="collapse" data-parent="#accordion<?=$arSection['ID']?>" href="#accordion<?=$arItem['ID']?>_<?=$arSection['ID']?>">
													<span class="arrow_open pull-right colored_theme_hover_bg-el"></span>
													<span><?=$arItem['NAME']?></span>
													<?if(in_array('PAY', $arParams['PROPERTY_CODE'])):?>
														<div class="pay">
															<?if($arItem['DISPLAY_PROPERTIES']['PAY']['VALUE']):?>
																<?=GetMessage('PAY_ABOUT')?>&nbsp;<b><?=$arItem['DISPLAY_PROPERTIES']['PAY']['VALUE']?></b>
															<?else:?>
																<?=GetMessage('PAY_INTERVIEWS')?>
															<?endif;?>
														</div>
													<?endif;?>
												</div>
												<div id="accordion<?=$arItem['ID']?>_<?=$arSection['ID']?>" class="panel-collapse collapse">
													<div class="accordion-body">
														<div class="row">
															<?if(!$bImage):?>
																<div class="col-md-12"><div class="text"><?=$textPart?></div></div>
															<?elseif($arParams["IMAGE_POSITION"] == "right"):?>
																<div class="col-md-9"><div class="text"><?=$textPart?></div></div>
																<div class="col-md-3"><?=$imagePart?></div>
															<?else:?>
																<div class="col-md-3"><?=$imagePart?></div>
																<div class="col-md-9"><div class="text"><?=$textPart?></div></div>
															<?endif;?>
														</div>
													</div>
												</div>
											</div>
										</div>
									<?endif;?>
								<?endforeach;?>

						<?if (!$bIsAjax):?>
							<?if($arParams['VIEW_TYPE'] !== 'accordion'):?>
								</div>
							<?endif;?>
						</div>
						<?endif;?>
					<?endforeach;?>
				<?if (!$bIsAjax):?>
				</div> <?// .tab-content?>
				<?endif;?>

		<?if($arParams['SHOW_TABS'] == 'Y'):?>
			</div>
		<?endif;?>

	<?// bottom pagination?>
	<?if($arParams['DISPLAY_BOTTOM_PAGER']):?>
		<?// bottom pagination?>
		<?if ($arParams['ONLY_AJAX_LINK'] === 'Y'):?>
		<div class="bottom_nav_wrapper">
			<div class="bottom_nav animate-load-state" <?=($bIsAjax ? "style='display: none; '" : "");?> data-parent=".item-views" data-append=".tab-pane > .row">
		<?endif;?>
				<?=$arResult['NAV_STRING']?>
		<?if ($arParams['ONLY_AJAX_LINK'] === 'Y'):?>
		</div>
	</div>
	<?endif;?>
		<?//=$arResult['NAV_STRING']?>
	<?endif;?>
<?if (!$bIsAjax):?>
</div> <?// .item-views?>
<?endif;?>
<?endif;?>
<?if($arParams['SHOW_TABS'] == 'Y'):?>
	<script>tabsHistory();$(document).trigger('hashchange');</script>
<?endif;?>	
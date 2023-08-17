<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<?//if($arResult['ITEMS']):?>
<?if($arResult['SECTIONS']):?>
	<?$isAjax = $arParams['IS_AJAX'];//(isset($_GET["AJAX_REQUEST"]) && $_GET["AJAX_REQUEST"] == "Y");?>
	<?//$isWideImg = (isset($arParams['IMAGE_WIDE']) && $arParams['IMAGE_WIDE'] == 'Y');?>
	<?//if(!$isAjax):?>
		<div class="item-views list vacancy-list1 list-type-block wide_img <?=($arParams['IMAGE_POSITION'] ? 'image_'.$arParams['IMAGE_POSITION'] : '')?><?=($arParams['LINKED_MODE'] == 'Y' ? ' compact-view' : '')?> <?=($arParams['ACCORDION']=='Y' ? 'accordion-mode ' : '');?> <?=$templateName;?>-template">



			<div class="<?=($arParams['SHOW_TABS'] == 'Y' ? 'tab-content' : 'group-content')?>">
				<?// group elements by sections?>
				<?foreach($arResult['SECTIONS'] as $SID => $arSection):?>
					<?
					// edit/add/delete buttons for edit mode
					$arSectionButtons = CIBlock::GetPanelButtons($arSection['IBLOCK_ID'], 0, $arSection['ID'], array('SESSID' => false, 'CATALOG' => true));
					$this->AddEditAction($arSection['ID'], $arSectionButtons['edit']['edit_section']['ACTION_URL'], CIBlock::GetArrayByID($arSection['IBLOCK_ID'], 'SECTION_EDIT'));
					$this->AddDeleteAction($arSection['ID'], $arSectionButtons['edit']['delete_section']['ACTION_URL'], CIBlock::GetArrayByID($arSection['IBLOCK_ID'], 'SECTION_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
					?>
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
							<?endif;?>
						<?endif;?>



		<div class="items row <?=($arParams['ACCORDION']=='Y' ? 'accordion-type-1 ' : '');?>">
			<?// show section items?>
	<?//endif;?>
			<?
				//$count=count($arResult['ITEMS']);
				//$current=0;
			?>
			<?//foreach($arResult['ITEMS'] as $i => $arItem):?>	
			<?foreach($arSection['ITEMS'] as $i => $arItem):?>	    
				<?
				//$current++;
				// edit/add/delete buttons for edit mode
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				// use detail link?
				$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen($arItem['DETAIL_TEXT']) ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1) : true);
				$bImage = isset($arItem['FIELDS']['PREVIEW_PICTURE']) && strlen($arItem['PREVIEW_PICTURE']['SRC']);
				$imageSrc = ($bImage ? $arItem['PREVIEW_PICTURE']['SRC'] : false);
				$imageDetailSrc = ($bImage ? $arItem['DETAIL_PICTURE']['SRC'] : false);
				// show active date period
				$bActiveDate = strlen($arItem['DISPLAY_PROPERTIES']['PERIOD']['VALUE']) || ($arItem['DISPLAY_ACTIVE_FROM'] && in_array('DATE_ACTIVE_FROM', $arParams['FIELD_CODE']));
				?>
				
		    
				<div class="col-md-12">
					<div class="item_wrap box-shadow rounded3 <?=($arParams['BORDERED']=='Y' ? 'bordered-block ' : '')?> <?=($arParams['ACCORDION']=='Y' ? 'item-accordion-wrapper ' : ' colored_theme_hover_bg-block ');?>" >
						<?if($arParams['ACCORDION']=='Y'):?>
							<div class="item accordion-head colored_theme_hover_bg-block accordion-close noborder<?=($bImage ? '' : ' wti')?><?=($bActiveDate ? ' wdate' : '')?> clearfix" id="<?=$this->GetEditAreaId($arItem['ID'])?>" 
							data-toggle="collapse" data-parent="#accordion<?=$arSection['ID']?>" href="#accordion<?=$arItem['ID']?>_<?=$arSection['ID']?>" >
								<span class="arrow_open pull-right colored_theme_hover_bg-el"></span>
						<?else:?>
							<div class="item noborder<?=($bImage ? '' : ' wti')?><?=($bActiveDate ? ' wdate' : '')?> clearfix" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
						<?endif;?>

							<?if($bImage):?>
								<div class="image shine nopadding">
									<?if($bDetailLink):?>
										<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
									<?endif;?>
										<img src="<?=\Aspro\Functions\CAsproMax::showBlankImg($imageSrc);?>" alt="<?=($bImage ? $arItem['PREVIEW_PICTURE']['ALT'] : $arItem['NAME'])?>" title="<?=($bImage ? $arItem['PREVIEW_PICTURE']['TITLE'] : $arItem['NAME'])?>" class="img-responsive lazy" data-src="<?=$imageSrc?>" />
									<?if($bDetailLink):?>
										</a>
									<?endif;?>
								</div>
							<?endif;?>
							<div class="body-info <?=strlen($arResult['SECTIONS'][$arItem['IBLOCK_SECTION_ID']]['NAME'])? 'with-section': '';?> <?=($bDetailLink) ? 'has-link':'';?>">

								<div class="top-block flexbox flexbox--row  justify-content-between ">
									<div class="top-block__info">
										<?// element name?>
										<?if(strlen($arItem['FIELDS']['NAME'])):?>
											<div class="title font_mlg ">
												<?if($bDetailLink):?><a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="dark-color"><?endif;?>
													<?=$arItem['NAME']?>
												<?if($bDetailLink):?></a><?endif;?>
											</div>
										<?endif;?>

										<?// element display properties?>
										<?$arPayInfo = array();
										$hasProps = false;
										?>
										<?if($arItem['DISPLAY_PROPERTIES']):?>
											<div class="properties muted font_upper">
												<?foreach($arItem['DISPLAY_PROPERTIES'] as $PCODE => $arProperty):?>
													<?if(in_array($PCODE, array('PERIOD', 'TITLE_BUTTON', 'LINK_BUTTON', 'TYPE_BLOCK', 'SALE_NUMBER'))) continue;?>
													<?if($PCODE != 'PAY'):?>
														<?$hasProps = true;?>
														<div class="inner-wrapper">
															<div class="property <?=($bIconBlock ? "icon-block" : "");?> <?=strtolower($PCODE);?>">
																<?if($PCODE == 'QUALITY'):?>
																	<?=$arProperty['NAME']?>:&nbsp;
																<?endif;?>
																<?if(is_array($arProperty['DISPLAY_VALUE'])):?>
																	<?$val = implode('&nbsp;/&nbsp;', $arProperty['DISPLAY_VALUE']);?>
																<?else:?>
																	<?$val = $arProperty['DISPLAY_VALUE'];?>
																<?endif;?>
																<?if($PCODE == 'SITE'):?>
																	<!--noindex-->
																	<a href="<?=(strpos($arProperty['VALUE'], 'http') === false ? 'http://' : '').$arProperty['VALUE'];?>" rel="nofollow" target="_blank">
																		<?=$arProperty['VALUE'];?>
																	</a>
																	<!--/noindex-->
																<?elseif($PCODE == 'EMAIL'):?>
																	<a href="mailto:<?=$val?>"><?=$val?></a>
																<?else:?>
																	<?=$val?>
																<?endif;?>
															</div>
															<span class="separator">&mdash;</span>
														</div>
													<?else:?>
														<?$arPayInfo = $arProperty;?>
													<?endif;?>
												<?endforeach;?>
											</div>
										<?endif;?>
									</div>

									<?if($arPayInfo && $arPayInfo['VALUE']):?>
										<div class="top-block__pay <?=(!$hasProps ? 'no-props' : '')?>">
											<div class="font_md darken"><?=$arPayInfo['VALUE'];?></div>
										</div>
									<?endif;?>
								</div>

								<?if($bDetailLink && $arParams['ACCORDION'] !='Y'):?>								
									<a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="arrow_link colored_theme_hover_bg-el bordered-block rounded3 muted" title="<?=GetMessage('TO_ALL')?>"><?=CMax::showIconSvg("right-arrow", SITE_TEMPLATE_PATH.'/images/svg/arrow_right_list.svg', '', '');?></a>
								<?endif;?>

								<?// element preview text?>
								<?if(strlen($arItem['FIELDS']['PREVIEW_TEXT']) && $arParams['ACCORDION'] !='Y'):?>
									<div class="previewtext font_sm muted777 line-h-165 <?=(!$hasProps ? 'no-props' : '')?>">
										<?if($arItem['PREVIEW_TEXT_TYPE'] == 'text'):?>
											<p><?=$arItem['FIELDS']['PREVIEW_TEXT']?></p>
										<?else:?>
											<?=$arItem['FIELDS']['PREVIEW_TEXT']?>
										<?endif;?>
									</div>
								<?endif;?>
							</div>
						</div>
						<?if($arParams['ACCORDION']=='Y'):?>
						<div id="accordion<?=$arItem['ID']?>_<?=$arSection['ID']?>" class="panel-collapse collapse">
							<div class="accordion-body">
								<div class="row">
									<div class="col-md-12">
										<div class="text">
											<?if(strlen($arItem['FIELDS']['DETAIL_TEXT'])):?>
												<?// element detail text?>
													<?if(strlen($arItem['FIELDS']['DETAIL_TEXT'])):?>
														<?if($arItem['DETAIL_TEXT_TYPE'] == 'text'):?>
															<p><?=$arItem['FIELDS']['DETAIL_TEXT']?></p>
														<?else:?>
															<?=$arItem['FIELDS']['DETAIL_TEXT']?>
														<?endif;?>
													<?endif;?>
											<?endif;?>
												
										</div>
										<?if($arParams['FORM'] == 'Y'):?>
											<div class="add_resume">
												<div class="button_wrap">
													<span><span class="btn btn-default btn-lg animate-load" data-event="jqm" data-name="resume" data-param-form_id="<?=$arParams["FORM_ID"]?>" data-autoload-POST="<?=CMax::formatJsName($arItem['NAME']);?>" data-autohide=""><?=$arParams["FORM_BUTTON_TITLE"];?></span></span>
												</div>
											</div>
										<?endif;?>
									</div>
								</div>
							</div>
						</div>
						<?endif;?>

					</div>
				</div>
			<?endforeach;?>
		<?//if(!$isAjax):?>
		</div>
		<?//if($bHasSection):?>
			</div>
			<?endforeach;?>
		<?//endif;?>
		<?//endif;?>

		<?// bottom pagination?>
		<?if($arParams['DISPLAY_BOTTOM_PAGER']):?>
			<div class="bottom_nav_wrapper">
				<div class="bottom_nav animate-load-state<?=($arResult['NAV_STRING'] ? ' has-nav' : '');?>" <?=($isAjax ? "style='display: none; '" : "");?> data-parent=".item-views" data-append=".items.row">
					<?=$arResult['NAV_STRING']?>
				</div>
			</div>
		<?endif;?>
	<?//if(!$isAjax):?>
	</div>
	</div>

	<?//endif;?>
<?endif;?>

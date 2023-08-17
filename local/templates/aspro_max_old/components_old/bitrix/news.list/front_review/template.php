<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<?use \Bitrix\Main\Localization\Loc;?>
<?if($arResult['ITEMS']):?>
	<div class="content_wrapper_block <?=$templateName;?> <?=$arParams['SIZE_IN_ROW'] > 1 ? 'with-border' : ''?>">
	<div class="maxwidth-theme only-on-front">
		<?if($arParams['TITLE_BLOCK'] || $arParams['TITLE_BLOCK_ALL'] || ($arParams['SHOW_ADD_REVIEW'] == 'Y' && $arParams['TITLE_ADD_REVIEW'])):?>
			<?if($arParams['INCLUDE_FILE']):?>
				<div class="with-text-block-wrapper">
					<div class="row">
						<div class="col-md-3">
							<?if($arParams['TITLE_BLOCK'] || $arParams['TITLE_BLOCK_ALL']):?>
								<h3><?=$arParams['TITLE_BLOCK'];?></h3>
								<?// intro text?>
								<?if($arParams['INCLUDE_FILE']):?>
									<div class="text_before_items font_xs">
										<?$APPLICATION->IncludeComponent(
											"bitrix:main.include",
											"",
											Array(
												"AREA_FILE_SHOW" => "file",
												"PATH" => SITE_DIR."include/mainpage/inc_files/".$arParams['INCLUDE_FILE'],
												"EDIT_TEMPLATE" => ""
											)
										);?>
									</div>
								<?endif;?>
								<div class="block-links">									
										<span><a href="<?=SITE_DIR.$arParams['ALL_URL'];?>" class="btn btn-transparent-border-color btn-sm"><?=$arParams['TITLE_BLOCK_ALL'] ;?></a></span>
									<?if($arParams['SHOW_ADD_REVIEW'] == 'Y' && $arParams['TITLE_ADD_REVIEW']):?>
										<span><span class="btn btn-transparent-border-color btn-sm animate-load" data-event="jqm" data-param-form_id="REVIEW" data-name="send_review" title="<?=$arParams['TITLE_ADD_REVIEW'] ;?>"><?=CMax::showIconSvg("resume colored", SITE_TEMPLATE_PATH."/images/svg/leaveareview.svg", "", "", true, false);?></span></span>
									<?endif;?>
								</div>
							<?endif;?>
						</div>
						<div class="col-md-9">
			<?else:?>
				<div class="top_block">
					<h3><?=$arParams['TITLE_BLOCK'];?></h3>
					<?if($arParams['TITLE_BLOCK_ALL']):?>
						<a href="<?=SITE_DIR.$arParams['ALL_URL'];?>" class="pull-right font_upper muted"><?=$arParams['TITLE_BLOCK_ALL'] ;?></a>
					<?endif;?>
					<?if($arParams['SHOW_ADD_REVIEW'] == 'Y' && $arParams['TITLE_ADD_REVIEW']):?>
						<span class="pull-right reviews"><span class="pull-right font_upper muted dark_link animate-load" data-event="jqm" data-param-form_id="REVIEW" data-name="send_review"><?=CMax::showIconSvg("resume", SITE_TEMPLATE_PATH."/images/svg/leaveareview.svg", "", "", true, false);?><span><?=$arParams['TITLE_ADD_REVIEW'] ;?></span></span></span>
					<?endif;?>
				</div>
			<?endif;?>
		<?endif;?>
		<?global $arTheme;
		$slideshowSpeed = abs(intval($arTheme['PARTNERSBANNER_SLIDESSHOWSPEED']['VALUE']));
		$animationSpeed = abs(intval($arTheme['PARTNERSBANNER_ANIMATIONSPEED']['VALUE']));
		$bAnimation = (bool)$slideshowSpeed;
		$col = ($arParams['SIZE_IN_ROW'] ? $arParams['SIZE_IN_ROW'] : 1);
		$bCompact = ($arParams['COMPACT'] == 'Y');
		$bOneItem = ($col == 1);
		$bMoreItem = ($col > 2 || $arParams['INCLUDE_FILE']);
		$notSlider = ($arParams["NOT_SLIDER"] == "Y");
		?>
		<div class="item-views reviews<?=($bCompact ? ' compact' : '');?><?=($bMoreItem ? ' more-item' : '');?> <?=($arParams['LINKED_MODE'] == 'Y' ? ' linked ' : '');?> <?=($notSlider ? ' list-mode ' : '');?>">
			<?if($notSlider):?>
				<div class="appear-block loading_state<?=(!$bOneItem ? ' shadow' : '');?>" >
			<?else:?>
				<div class="owl-carousel owl-theme owl-bg-nav short-nav hidden-dots visible-nav swipeignore wsmooth1 appear-block loading_state<?=(!$bOneItem ? ' shadow' : '');?>" data-plugin-options='{"nav": true, "dots": false, "loop": false, "marginMove": true, "autoplay": false, <?=($animationSpeed >= 0 ? '"smartSpeed": '.$animationSpeed.',' : '')?> "useCSS": true, "responsive": {"0":{"items": 1, "autoWidth": true, "lightDrag": true, "margin":-1}, "601":{"items": 1, "autoWidth": false, "lightDrag": false, "margin":0}, "1200":{"items": <?=$col?>, "margin":-1}}}'>
			<?endif;?>
				<?foreach($arResult['ITEMS'] as $i => $arItem):?>
					<?
					// edit/add/delete buttons for edit mode
					$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
					$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => Loc::getMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
					// use detail link?
					$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen($arItem['DETAIL_TEXT']) ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1) : true);
					
					// preview image
					$bImage = strlen($arItem['FIELDS']['PREVIEW_PICTURE']['SRC']);
					$arImage = ($bImage ? CFile::ResizeImageGet($arItem['FIELDS']['PREVIEW_PICTURE']['ID'], array('width' => 70, 'height' => 10000), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true) : array());
					$imageSrc = ($bImage ? $arImage['src'] : '');

					$bLogo = false;						
					if(!$imageSrc && strlen($arItem['FIELDS']['DETAIL_PICTURE']['SRC']))
					{
						$bImage = strlen($arItem['FIELDS']['DETAIL_PICTURE']['SRC']);
						$arImage = ($bImage ? CFile::ResizeImageGet($arItem['FIELDS']['DETAIL_PICTURE']['ID'], array('width' => 80, 'height' => 10000), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true) : array());
						$imageSrc = ($bImage ? $arImage['src'] : '');
						$bLogo = ($imageSrc ? true : false);
					}
					?>
					<div class="item-wrapper col-xs-12<?=(!$bOneItem ?  ' bg-fill-white bordered1 box-shadow1' : '');?>">
						<div class="item clearfix <?=($bLogo ? ' wlogo' : '')?> <?=(!$bImage ? 'no_img' : '')?> <?=(!$bOneItem ? ' bordered box-shadow' : '');?> <?=($notSlider ? ' rounded2 bordered' : '');?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
							<div class="top_wrapper clearfix">
								<?if($imageSrc):?>
									<div class="image pull-left">
										<div class="wrap">
											<?if($bDetailLink):?><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?endif;?>
											<?if($imageSrc):?>
												<img class="img-responsive <?=(!$bLogo ? ' rounded' : '')?> lazy" data-src="<?=$imageSrc?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($imageSrc);?>" alt="<?=($bImage ? $arItem['PREVIEW_PICTURE']['ALT'] : $arItem['NAME'])?>" title="<?=($bImage ? $arItem['PREVIEW_PICTURE']['TITLE'] : $arItem['NAME'])?>" />
											<?endif;?>
											<?if($bDetailLink):?></a><?endif;?>
										</div>
									</div>
								<?endif;?>
								<div class="top-info">
									<div class="wrap muted">
										<?if(isset($arItem['DISPLAY_PROPERTIES']['POST']) && strlen($arItem['DISPLAY_PROPERTIES']['POST']['VALUE'])):?>
											<span class="font_upper"><?=$arItem['DISPLAY_PROPERTIES']['POST']['VALUE']?></span>
										<?endif?>
										<?if(isset($arItem['DISPLAY_ACTIVE_FROM']) && $arItem['DISPLAY_ACTIVE_FROM'] && isset($arItem['DISPLAY_PROPERTIES']['POST']) && strlen($arItem['DISPLAY_PROPERTIES']['POST']['VALUE'])):?>
											<span class="separator">&ndash;</span>
										<?endif;?>
										<?if(isset($arItem['DISPLAY_ACTIVE_FROM']) && $arItem['DISPLAY_ACTIVE_FROM']):?>
											<span class="date font_upper"><?=$arItem['DISPLAY_ACTIVE_FROM']?></span>
										<?endif;?>
									</div>
									<div class="title <?=($bCompact ? 'font_md' : 'font_lg');?> pull-left">
										<?if($bDetailLink):?><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?endif;?>
										<?=$arItem['NAME'];?>
										<?if($bDetailLink):?></a><?endif;?>
									</div>
									<?if(in_array('RATING', $arParams['PROPERTY_CODE'])):?>
										<?$ratingValue = ($arItem['DISPLAY_PROPERTIES']['RATING']['VALUE'] ? $arItem['DISPLAY_PROPERTIES']['RATING']['VALUE'] : 0);?>
										<div class="votes_block nstar big pull-right">
											<div class="ratings">
												<div class="inner_rating">
													<?for($i=1;$i<=5;$i++):?>
														<div class="item-rating <?=(round($ratingValue) >= $i ? "filed" : "");?>"><?=CMax::showIconSvg("star", SITE_TEMPLATE_PATH."/images/svg/star.svg");?></div>
													<?endfor;?>
												</div>
											</div>
										</div>
									<?endif;?>
								</div>
							</div>

							<?if(isset($arItem['FIELDS']['PREVIEW_TEXT']) && $arItem['FIELDS']['PREVIEW_TEXT']):?>
								<div class="body-info">
									<?if(!$notSlider):?>
										<?=CMax::showIconSvg("quote muted ncolor", SITE_TEMPLATE_PATH."/images/svg/quote.svg", "", "", true, false);?>
									<?endif;?>
									<?if(in_array('RATING', $arParams['PROPERTY_CODE'])):?>
										<?$ratingValue = ($arItem['DISPLAY_PROPERTIES']['RATING']['VALUE'] ? $arItem['DISPLAY_PROPERTIES']['RATING']['VALUE'] : 0);?>
										<div class="votes_block nstar big">
											<div class="ratings">
												<div class="inner_rating">
													<?for($i=1;$i<=5;$i++):?>
														<div class="item-rating <?=(round($ratingValue) >= $i ? "filed" : "");?>"><?=CMax::showIconSvg("star", SITE_TEMPLATE_PATH."/images/svg/star.svg");?></div>
													<?endfor;?>
												</div>
											</div>
										</div>
									<?endif;?>
									<div class="clearfix"></div>
									<div class="preview-text"><?=$arItem['FIELDS']['PREVIEW_TEXT'];?></div>
									<?if(($arParams['PREVIEW_TRUNCATE_LEN'] > 0) && (strlen($arItem['~PREVIEW_TEXT']) > $arParams['PREVIEW_TRUNCATE_LEN'])):?>
										<div class="link-block-more">
											<span><span class="btn btn-transparent-border-color btn-xs animate-load" data-event="jqm" data-param-id="<?=$arItem['ID'];?>" data-param-type="review" data-name="review"><?=Loc::getMessage('MORE_REVIEWS');?></span></span>
										</div>
									<?endif;?>
									<?$arVideo = (isset($arItem['DISPLAY_PROPERTIES']['VIDEO']) && is_array($arItem['DISPLAY_PROPERTIES']['VIDEO']['VALUE']) ? $arItem['DISPLAY_PROPERTIES']['VIDEO']['~VALUE'] : '');?>
									<?if($arVideo):?>
									<div class="video_block hidden_print">
										<?if(count($arVideo) > 1):?>
											<?foreach($arVideo as $v => $value):?>
												<div class="video">
													<?//=str_replace('src=', 'width="660" height="457" src=', str_replace(array('width', 'height'), array('data-width', 'data-height'), $value));?>
													<?=$value;?>
												</div>
											<?endforeach;?>
										<?else:?>
											<div class="col-md-12"><?=$arVideo[0]?></div>
										<?endif;?>
									</div>
									<?endif;?>
									<?if($arItem['DISPLAY_PROPERTIES']['FILE']['VALUE']):?>
										<div class="files_block">
											<div class="row flexbox">
												<?foreach((array)$arItem['DISPLAY_PROPERTIES']['FILE']['VALUE'] as $arFileItem):?>
													<div class="col-md-6 col-sm-6 col-xs-12">
														<?$arFile=CMax::GetFileInfo($arFileItem);?>
														<div class="file_type clearfix <?=$arFile["TYPE"];?>">
															<i class="icon"></i>
															<div class="description">
																<a target="_blank" href="<?=$arFile["SRC"];?>" class="dark_link font_sm"><?=$arFile["DESCRIPTION"];?></a>
																<span class="size font_xs muted">
																	<?=$arFile["FILE_SIZE_FORMAT"];?>
																</span>
															</div>
														</div>
													</div>
												<?endforeach;?>
											</div>
										</div>
									<?endif;?>
								</div>
							<?endif;?>
						</div>
						<?	$answer = (isset($arItem['FIELDS']['DETAIL_TEXT']) && strlen($arItem['FIELDS']['DETAIL_TEXT']) ? $arItem['FIELDS']['DETAIL_TEXT'] : '');
							if($notSlider && $answer):// isset($arItem['DISPLAY_PROPERTIES']['STAFF'])):?>
							<?
							// preview image response
							
							$arStaff = (isset($arItem['DISPLAY_PROPERTIES']['STAFF']['VALUE']) && isset($arResult['STAFF'][$arItem['DISPLAY_PROPERTIES']['STAFF']['VALUE']]) ? $arResult['STAFF'][$arItem['DISPLAY_PROPERTIES']['STAFF']['VALUE']] : array());
							$bImageResp = strlen($arStaff['PREVIEW_PICTURE']);

							$arImageResp = ($bImageResp ? CFile::ResizeImageGet($arStaff['PREVIEW_PICTURE'], array('width' => 40, 'height' => 40), BX_RESIZE_IMAGE_EXACT, true) : array());
							$imageSrcResp = ($bImageResp ? $arImageResp['src'] : '');

							?>
							<div class="respone_wrap rounded2 bordered  <?=($bImageResp ? 'with-img ' : '')?>">
								<?if($arStaff && $arStaff['PREVIEW_PICTURE']):?>
									<div class="response_img">
										<img class="img-responsive lazy rounded" data-src="<?=$imageSrcResp?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($imageSrcResp);?>" alt="<?=$arStaff['NAME']?>" title="<?=$arStaff['NAME']?>"/>
									</div>
								<?endif;?>
								<div class="response_body">
									<?if($arStaff):?>
										<div class="response_title"><span class="darken font_upper"><?=$arStaff['NAME'];?></span><?if($arStaff['PROPERTY_POST_VALUE']):?><span class="darken font_upper">, <?=$arStaff['PROPERTY_POST_VALUE'];?></span><?endif;?></div>
									<?endif;?>
									<div class="response_text muted777 font_xs"><?=$answer;?></div>
								</div>
							</div>
						<?endif;?>
					</div>
				<?endforeach;?>
			</div>
			<?// bottom pagination?>
			<?if($notSlider && $arParams['DISPLAY_BOTTOM_PAGER']):?>
				<div class="bottom_nav_wrapper">
					<div class="bottom_nav animate-load-state<?=($arResult['NAV_STRING'] ? ' has-nav' : '');?>" <?=($isAjax ? "style='display: none; '" : "");?> data-parent=".item-views" data-append=".items.row">
						<?=$arResult['NAV_STRING']?>
					</div>
				</div>
			<?endif;?>
		</div>
		<?if($arParams['INCLUDE_FILE']):?>
			</div></div></div>
		<?endif;?>
	</div></div>
<?endif;?>
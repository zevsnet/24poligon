<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>

<?$this->setFrameMode(true);?>	
<?use \Bitrix\Main\Localization\Loc;?>



<?// form question?>

<?
$bActiveDate = strlen($arResult['DISPLAY_PROPERTIES']['PERIOD']['VALUE']) || ($arResult['DISPLAY_ACTIVE_FROM'] && in_array('DATE_ACTIVE_FROM', $arParams['FIELD_CODE']));
$bDiscountCounter = ($arResult['ACTIVE_TO'] && in_array('ACTIVE_TO', $arParams['FIELD_CODE']));
$bShowDopBlock = ($arResult['DISPLAY_PROPERTIES']['SALE_NUMBER']['VALUE'] || $bDiscountCounter);
$bWideText = isset($arResult['PROPERTIES']['WIDE_TEXT']) && $arResult['PROPERTIES']['WIDE_TEXT']['VALUE_XML_ID'] == 'YES';

$bShowFormOrder = ($arResult['DISPLAY_PROPERTIES']['FORM_ORDER']['VALUE_XML_ID'] == 'YES');
$bShowFormQuestion = ($arResult['DISPLAY_PROPERTIES']['FORM_QUESTION']['VALUE_XML_ID'] == 'YES');


if($arResult['FIELDS']['DETAIL_PICTURE']){
	$atrTitle = (strlen($arResult['DETAIL_PICTURE']['DESCRIPTION']) ? $arResult['DETAIL_PICTURE']['DESCRIPTION'] : (strlen($arResult['DETAIL_PICTURE']['TITLE']) ? $arResult['DETAIL_PICTURE']['TITLE'] : $arResult['NAME']));
	$atrAlt = (strlen($arResult['DETAIL_PICTURE']['DESCRIPTION']) ? $arResult['DETAIL_PICTURE']['DESCRIPTION'] : (strlen($arResult['DETAIL_PICTURE']['ALT']) ? $arResult['DETAIL_PICTURE']['ALT'] : $arResult['NAME']));
}


$bTopImage = ($arResult['FIELDS']['DETAIL_PICTURE'] && $arResult['PROPERTIES']['PHOTOPOS']['VALUE_XML_ID'] == 'TOP');

$bPartnersMode = isset($arParams["PARTNERS_MODE"]) && $arParams["PARTNERS_MODE"] == "Y";

?>

<?
/*set array props for component_epilog*/
$templateData = array(
	'DOCUMENTS' => $arResult['DISPLAY_PROPERTIES']['DOCUMENTS']['VALUE'],
	'LINK_SALE' => $arResult['DISPLAY_PROPERTIES']['LINK_SALE']['VALUE'],
	'LINK_BRANDS' => $arResult['DISPLAY_PROPERTIES']['LINK_BRANDS']['VALUE'],
	'LINK_TIZERS' => $arResult['DISPLAY_PROPERTIES']['LINK_TIZERS']['VALUE'],
	'LINK_PROJECTS' => $arResult['DISPLAY_PROPERTIES']['LINK_PROJECTS']['VALUE'],
	'LINK_SERVICES' => $arResult['DISPLAY_PROPERTIES']['LINK_SERVICES']['VALUE'],
	'LINK_GOODS' => $arResult['DISPLAY_PROPERTIES']['LINK_GOODS']['VALUE'],
	'LINK_REVIEWS' => $arResult['DISPLAY_PROPERTIES']['LINK_REVIEWS']['VALUE'],
	'LINK_STAFF' => $arResult['DISPLAY_PROPERTIES']['LINK_STAFF']['VALUE'],
	'LINK_NEWS' => $arResult['DISPLAY_PROPERTIES']['LINK_NEWS']['VALUE'],
	'LINK_VACANCY' => $arResult['DISPLAY_PROPERTIES']['LINK_VACANCY']['VALUE'],
	'LINK_STAFF' => $arResult['DISPLAY_PROPERTIES']['LINK_STAFF']['VALUE'],
	'LINK_REVIEWS' => $arResult['DISPLAY_PROPERTIES']['LINK_REVIEWS']['VALUE'],
	'LINK_BLOG' => $arResult['DISPLAY_PROPERTIES']['LINK_BLOG']['VALUE'],
	'GALLERY_BIG' => $arResult['GALLERY_BIG'],
	'LINK_LANDINGS' => $arResult['DISPLAY_PROPERTIES']['LINK_LANDINGS']['VALUE'],
	'LINK_PARTNERS' => $arResult['DISPLAY_PROPERTIES']['LINK_PARTNERS']['VALUE'],
	'VIDEO' => $arResult["DISPLAY_PROPERTIES"]["VIDEO"]["~VALUE"],
	//'VIDEO_IFRAME' => $arResult['VIDEO_IFRAME'],
	'FORM_QUESTION' => $arResult['DISPLAY_PROPERTIES']['FORM_QUESTION']['VALUE'],
	'FORM_ORDER' => $arResult['DISPLAY_PROPERTIES']['FORM_ORDER']['VALUE'],
	'BNR_DARK_MENU_COLOR' => $arResult['PROPERTIES']['BNR_DARK_MENU_COLOR'],
	'SHOW_PERIOD_LINE' => $bShowDopBlock || $bActiveDate,
	//'CATALOG_LINKED_TEMPLATE' => $catalogLinkedTemplate,
	//'LIST_PAGE_URL' => $arResult['LIST_PAGE_URL'],
	'GALLERY_TYPE' => isset($arResult['PROPERTIES']['GALLERY_TYPE']) ? ($arResult['PROPERTIES']['GALLERY_TYPE']['VALUE'] === 'small' ? 'small' : 'big') : ($arParams['GALLERY_TYPE'] === 'small' ? 'small' : 'big'),
);
?>

<?//need for top banners
if(isset($arResult['PROPERTIES']['BNR_TOP']) && $arResult['PROPERTIES']['BNR_TOP']['VALUE_XML_ID'] == 'YES')
{
	$bBannerChar = isset($arResult['PROPERTIES']['BANNER_CHAR']) && $arResult['PROPERTIES']['BANNER_CHAR']['VALUE_XML_ID'] == 'Y';

	$templateData['SECTION_BNR_CONTENT'] = $bBannerChar ? false : true;
	if(isset($arResult['PROPERTIES']['BNR_ON_HEADER']) && $arResult['PROPERTIES']['BNR_ON_HEADER']['VALUE_XML_ID'] == 'YES' && !$bBannerChar)
	{
		$templateData['BNR_ON_HEAD'] = true;
	}
}
?>

<?// shot top banners start?>
<?$bShowTopBanner = (isset($templateData['SECTION_BNR_CONTENT'] ) && $templateData['SECTION_BNR_CONTENT'] == true);?>
<?if($bShowTopBanner):?>
	<?$this->SetViewTarget("section_bnr_content");?>
		<?CMax::ShowTopDetailBanner($arResult, $arParams);?>
	<?$this->EndViewTarget();?>
<?endif;?>
<?// shot top banners end?>


<div class="detail_wrapper detail-news1">
	<?if($bBannerChar): // banner char?>
		<?
		$arBannerChar = array(
			'TEXT' => isset($arResult['PROPERTIES']['BANNER_CHAR_TEXT']) ? $arResult['PROPERTIES']['BANNER_CHAR_TEXT']['VALUE'] : '',
			'GALLERY' => isset($arResult['PROPERTIES']['BANNER_CHAR_PHOTOS']) ? $arResult['PROPERTIES']['BANNER_CHAR_PHOTOS']['VALUE'] : false,
			'BTN' => false,
		);

		$sectionPath = '';
		$res = CIBlockSection::GetNavChain($arParams['IBLOCK_ID'], $arResult['IBLOCK_SECTION_ID'], array('ID', 'NAME'));
		while($section = $res->Fetch()) {
			$sectionPath .= $section['NAME'].($section['ID'] == $arResult['IBLOCK_SECTION_ID'] ? '' : '&nbsp;&nbsp;<span>&mdash;</span>&nbsp;&nbsp;');
		}
		$arBannerChar['SECTION_PATH'] = $sectionPath;

		$arAnchors = array(
			'GOODS' => '.ordered-block.goods, .ordered-block.goods_catalog',
			'PREVIEW' => '.introtext_wrapper',
			'SERVICES' => '.ordered-block.services',
			'NEWS' => '.ordered-block.news',
			'BRANDS' => '.ordered-block.brands',
			'PROJECTS' => '.ordered-block.projects-block',
			'TIZERS' => '.ordered-block.tizers-block',
			'REVIEWS' => '.ordered-block.reviews-block',
			'STAFF' => '.ordered-block.staff-block',
			'DOCS' => '.wraps.docs-block',
			'VACANCY' => '.ordered-block.vacancy',
			'BLOG' => '.ordered-block.blog',
			'PARTNERS' => '.ordered-block.partners',
			'SALE' => '.ordered-block.sale',
			'DETAIL' => '.ordered-block.detail_content_wrapper',
		);
		if( isset($arResult['PROPERTIES']['BANNER_CHAR_BTN_TEXT']) && $arResult['PROPERTIES']['BANNER_CHAR_BTN_TEXT']['VALUE'] ) {
			$arBannerChar['BTN'] = array(
				'TEXT' => $arResult['PROPERTIES']['BANNER_CHAR_BTN_TEXT']['VALUE'],
				'CLASS' => isset($arResult['PROPERTIES']['BANNER_CHAR_BTN_CLASS']) ? $arResult['PROPERTIES']['BANNER_CHAR_BTN_CLASS']['VALUE'] : '',
				'ANCHOR' => isset($arResult['PROPERTIES']['BANNER_CHAR_BTN_ANCHOR']) ? (isset($arAnchors[ $arResult['PROPERTIES']['BANNER_CHAR_BTN_ANCHOR']['VALUE_XML_ID'] ]) ? $arAnchors[ $arResult['PROPERTIES']['BANNER_CHAR_BTN_ANCHOR']['VALUE_XML_ID'] ] : false) : false,
			);
		}
		if($arBannerChar):?>
			<div class="banner-char bordered">
				<div class="row flexbox">
					<?if($arBannerChar['GALLERY']):?>
						<div class="banner-char__gallery col-md-6 col-sm-6 col-xs-12 flexbox align-items-center swipeignore">
							<div class="banner-char__gallery-inner">
								<div class="owl-theme owl-bg-nav short-nav owl-dots owl-carousel owl-drag" data-plugin-options='{"items": "1", "autoplay" : false, "autoplayTimeout" : "3000", "smartSpeed":1000, "dots": true, "nav": true, "loop": false, "rewind":true, "margin": 10}'>		
									<?foreach($arBannerChar['GALLERY'] as $photo):?>
										<div class="banner-char__gallery-item">
											<a href="<?=CFile::GetPath($photo)?>" class="fancy" data-fancybox="gallery">
												<img class="banner-char__gallery-item-img" src="<?=CFile::GetPath($photo)?>" />
											</a>
										</div>
									<?endforeach;?>
								</div>
							</div>
						</div>
					<?endif;?>

					<div class="banner-char__info <?=$arBannerChar['GALLERY'] ? '' : 'banner-char__info--alone'?> col-md-6 col-sm-6 col-xs-12">
						<?if($arBannerChar['SECTION_PATH'] || $arBannerChar['TEXT']):?>
							<div class="banner-char__info--top">
								<?if($arBannerChar['SECTION_PATH']):?>
									<div class="banner-char__info-sections font_upper">
										<?=$arBannerChar['SECTION_PATH']?>
									</div>
								<?endif;?>

								<?if($arBannerChar['TEXT']):?>
									<div class="banner-char__info-text darken">
										<?=$arBannerChar['TEXT']?>
									</div>
								<?endif;?>
							</div>
						<?endif;?>

						<?if($arResult['DISPLAY_PROPERTIES_FORMATTED'] || $arBannerChar['BTN'] || $bShowFormQuestion):?>
							<div class="banner-char__info--bottom">
								<?if($arResult['DISPLAY_PROPERTIES_FORMATTED']):?>
									<div class="banner-char__info-props font_xs">
										<?foreach($arResult['DISPLAY_PROPERTIES_FORMATTED'] as $code => $arProp):?>
											<?
											if($arProp['PROPERTY_TYPE'] == 'E' || $arProp['PROPERTY_TYPE'] == 'G')
												continue;
											?>
											<div class="banner-char__info-props-prop">
												<span class="title-prop"><?=$arProp['NAME']?>&nbsp;&nbsp;<span>&mdash;</span>&nbsp;&nbsp;</span>
												<span class="value darken">
													<?if(is_array($arProp['DISPLAY_VALUE'])):?>
														<?foreach($arProp['DISPLAY_VALUE'] as $key => $value):?>
															<?if($arProp['DISPLAY_VALUE'][$key + 1]):?>
																<?=$value.'&nbsp;/ '?>
															<?else:?>
																<?=$value?>
															<?endif;?>
														<?endforeach;?>
													<?else:?>
														<?=$arProp['DISPLAY_VALUE']?>
													<?endif;?>
												</span>
											</div>
										<?endforeach;?>
									</div>
								<?endif;?>

								<?if($arBannerChar['BTN'] || $bShowFormQuestion):?>
									<div class="banner-char__info-buttons flexbox flexbox--row flex-wrap">
										<?if($arBannerChar['BTN']):?>
											<div class="banner-char__info-buttons-btn">
												<span class="btn <?=($arBannerChar['BTN']['CLASS'] ? $arBannerChar['BTN']['CLASS'] : "btn-default");?>" data-scroll-block="<?=($arBannerChar['BTN']['ANCHOR'] ? $arBannerChar['BTN']['ANCHOR'] : $arAnchors['GOODS']);?>"><?=$arBannerChar['BTN']['TEXT'];?></span>
											</div>
										<?endif;?>

										<?if($bShowFormQuestion):?>
											<div class="banner-char__info-buttons-question">
												<span class="btn btn-transparent-border-color  animate-load" data-event="jqm" data-param-form_id="ASK" data-name="ASK" data-autoload-product_name="<?=CMax::formatJsName($arResult['NAME']);?>">
													<?=(strlen($arParams['S_ASK_QUESTION']) ? $arParams['S_ASK_QUESTION'] : Loc::getMessage('S_ASK_QUESTION'))?>
												</span>
											</div>
										<?endif;?>
									</div>
								<?endif;?>
							</div>
						<?endif;?>
					</div>
				</div>
			</div>
		<?endif;?>
	<?endif;?>

<?$templateData['USE_SLIDER'] = (($arBannerChar && $arBannerChar['GALLERY']) || $arResult['GALLERY']) ?>

<?//Preview text block?>
<?ob_start()?>
	<?if(strlen($arResult['FIELDS']['PREVIEW_TEXT']) && (!$bShowTopBanner || $arParams["IS_LANDING"] == "Y")):?>
		<div class="introtext_wrapper">
			<div class="introtext" itemprop="description">
				<?if($arResult['PREVIEW_TEXT_TYPE'] == 'text'):?>
					<p><?=$arResult['FIELDS']['PREVIEW_TEXT'];?></p>
				<?else:?>
					<?=$arResult['FIELDS']['PREVIEW_TEXT'];?>
				<?endif;?>
			</div>
		</div>
	<?endif;?>
<?$htmlPreview=ob_get_clean();?>
<?$this->SetViewTarget('PREVIEW_TEXT_BLOCK');?>	
	<?=$htmlPreview;?>
<?$this->EndViewTarget();?>
<?//end Preview text block?>


<?//Detail text block?>
<?ob_start()?>
	<?if(strlen($arResult['FIELDS']['DETAIL_TEXT'])):?>
		<?if($arResult['DETAIL_TEXT_TYPE'] == 'text'):?>
			<p><?=$arResult['FIELDS']['DETAIL_TEXT'];?></p>
		<?else:?>
			<?=$arResult['FIELDS']['DETAIL_TEXT'];?>
		<?endif;?>
	<?endif;?>
<?$htmlDetail=ob_get_clean();?>
<?$this->SetViewTarget('DETAIL_TEXT_BLOCK');?>	
	<?=$htmlDetail;?>
<?$this->EndViewTarget();?>
<?//end Detail text block?>


<?//top partners block?>
<?if($arParams["SHOW_TOP_PARTNERS_BLOCK"] == "Y"):?>
	<?//ob_start()?>
	<?if($arResult['FIELDS']['DETAIL_PICTURE'] || $arResult['DISPLAY_PROPERTIES_FORMATTED']):?>
		<div class="ordered-block top_partners_block bordered">
			<div class="top_content">
				<div class="image_partners">
					<img data-src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($arResult['DETAIL_PICTURE']['SRC']);?>" class="img-responsive lazy" title="<?=$atrTitle?>" alt="<?=$atrAlt?>" />
				</div>
				<?if(!$bBannerChar):?>
					<div class="properties">
						<?foreach($arResult['DISPLAY_PROPERTIES_FORMATTED'] as $code => $arProp):?>
							<?
							if($arProp['PROPERTY_TYPE'] == 'E' || $arProp['PROPERTY_TYPE'] == 'G')
								continue;
							?>
							<div class="property">
								<div class="title-prop muted font_upper"><?=$arProp['NAME']?></div>
								<div class="value darken">
									<?if(is_array($arProp['DISPLAY_VALUE'])):?>
										<?foreach($arProp['DISPLAY_VALUE'] as $key => $value):?>
											<?if($arProp['DISPLAY_VALUE'][$key + 1]):?>
												<?=$value.'&nbsp;/ '?>
											<?else:?>
												<?=$value?>
											<?endif;?>
										<?endforeach;?>
									<?elseif($code == 'SITE'):?>
										<!--noindex-->
										<a class="dark-color" href="<?=(strpos($arProp['VALUE'], 'http') === false ? 'http://' : '').$arProp['VALUE'];?>" rel="nofollow" target="_blank">
											<?=strpos($arProp['VALUE'], '?') === false ? $arProp['VALUE'] : explode('?', $arProp['VALUE'])[0]?>
										</a>
										<!--/noindex-->
									<?elseif($code == 'EMAIL'):?>
										<a href="mailto:<?=$arProp['VALUE']?>" class="dark-color"><?=$arProp['VALUE']?></a>
									<?elseif($code == 'PHONE'):?>
										<a href="tel:<?=str_replace(array(' ', ',', '-', '(', ')'), '', $arProp['VALUE']);?>" class="dark-color"><?=$arProp['VALUE']?></a>
									<?else:?>
										<?=$arProp['DISPLAY_VALUE']?>
									<?endif;?>

								</div>
							</div>
						<?endforeach;?>
					</div>
				<?endif;?>
			</div>
			<hr class="partners_line" />
			<div class="text_block">
				<?=$htmlPreview;?>
				<div class="detail_staff_text muted777">
					<?=$htmlDetail;?>
				</div>
			</div>
		</div>
	<?endif;?>
	<?//$htmlTopPartners=ob_get_clean();?>
<?endif;?>
<?//$this->SetViewTarget('TOP_PARTNERS_BLOCK');?>	
	<?//=$htmlTopPartners;?>
<?//$this->EndViewTarget();?>
<?//end?>


<?/*big top block*/?>
<?
//$bSowGaleryInTopBlock = $bTopImage || !$arResult['GALLERY'];?>

<?if($arParams["SHOW_TOP_PROJECT_BLOCK"] == "Y"):?><?//$APPLICATION->AddViewContent('top_section_filter_content', '1000');?>
	<?//$this->SetViewTarget("top_section_filter_content", 1000);?>
	<?ob_start();?>
	<div class="item project_block swipeignore  <?=(!$arResult['GALLERY'] ? ' wti' : '')?><?=($bTopImage ? ' wtop_image' : '')?>">
		<?if($bTopImage || !$arResult['GALLERY']):?>
			<div class="maxwidth-theme">
				<div class="info wti">
					<?if($arResult['DISPLAY_PROPERTIES_FORMATTED'] || ($bShowFormQuestion || $bShowFormOrder)):?>
						<div class="row flexbox">
							<div class="left_project_block col-md-<?=($bShowFormQuestion || $bShowFormOrder ? '9' : '12')?> col-sm-<?=($bShowFormQuestion || $bShowFormOrder ? '8' : '12')?> col-xs-<?=($bShowFormQuestion || $bShowFormOrder ? '7' : '12')?>">
								<?if(isset($arResult['DISPLAY_PROPERTIES']['DATA'])):?>
									<div class="date font_upper"><?=$arResult['DISPLAY_PROPERTIES_FORMATTED']['DATA']['VALUE']?></div>
								<?endif;?>
								<?if(
									isset($arResult['PROPERTIES']['TASK_PROJECT']['~VALUE']['TEXT']) &&
									$arResult['PROPERTIES']['TASK_PROJECT']['~VALUE']['TEXT']
								):?>
									<div class="task"><?=$arResult['PROPERTIES']['TASK_PROJECT']['~VALUE']['TEXT'];?></div>
								<?endif;?>
								<?if($arResult['DISPLAY_PROPERTIES_FORMATTED'] && !$bBannerChar):?>
									<div class="properties">
										<?foreach($arResult['DISPLAY_PROPERTIES_FORMATTED'] as $code => $arProp):?>
											<?
											if($code == 'DATA' || $arProp['PROPERTY_TYPE'] == 'E' || $arProp['PROPERTY_TYPE'] == 'G')
												continue;
											?>
											<div class="property">
												<div class="title-prop muted font_upper"><?=$arProp['NAME']?></div>
												<div class="value darken">
													<?if(is_array($arProp['DISPLAY_VALUE'])):?>
														<?foreach($arProp['DISPLAY_VALUE'] as $key => $value):?>
															<?if($arProp['DISPLAY_VALUE'][$key + 1]):?>
																<?=$value.'&nbsp;/ '?>
															<?else:?>
																<?=$value?>
															<?endif;?>
														<?endforeach;?>
													<?elseif($code == 'SITE'):?>
														<!--noindex-->
														<a class="dark-color" href="<?=(strpos($arProp['VALUE'], 'http') === false ? 'http://' : '').$arProp['VALUE'];?>" rel="nofollow" target="_blank">
															<?=strpos($arProp['VALUE'], '?') === false ? $arProp['VALUE'] : explode('?', $arProp['VALUE'])[0]?>
														</a>
														<!--/noindex-->
													<?elseif($code == 'EMAIL'):?>
														<a href="mailto:<?=$arProp['VALUE']?>" class="dark-color"><?=$arProp['VALUE']?></a>
													<?elseif($code == 'PHONE'):?>
														<a href="tel:<?=str_replace(array(' ', ',', '-', '(', ')'), '', $arProp['VALUE']);?>" class="dark-color"><?=$arProp['VALUE']?></a>
													<?else:?>
														<?=$arProp['DISPLAY_VALUE']?>
													<?endif;?>
												</div>
											</div>
										<?endforeach;?>
									</div>
								<?endif;?>
							</div>
							<?if($bShowFormQuestion || $bShowFormOrder):?>
								<div class="right_project_block col-md-3 col-sm-4 col-xs-5">
									<div class="buttons-block">
										<div class="wrap">
											<?if($bShowFormOrder):?>
												<div class="form_btn">
													<span class="btn btn-default btn-lg animate-load" data-event="jqm" data-param-form_id="<?=($arParams["FORM_ID_ORDER_SERVISE"] ? $arParams["FORM_ID_ORDER_SERVISE"] : 'SERVICES');?>" data-name="order_services" data-autoload-service="<?=CMax::formatJsName($arResult['NAME']);?>" data-autoload-project="<?=CMax::formatJsName($arResult['NAME']);?>" data-autoload-product="<?=CMax::formatJsName($arResult['NAME']);?>"><span><?=(strlen($arParams['S_ORDER_SERVISE']) ? $arParams['S_ORDER_SERVISE'] : Loc::getMessage('S_ORDER_SERVISE'))?></span></span>
												</div>
											<?endif;?>
											<?if($bShowFormQuestion):?>
												<div class="form_btn">
													<span class="btn btn-lg <?=($bShowFormOrder ? 'btn-transparent-border-color' : 'btn-default')?> <?=($bShowFormOrder ? 'white' : '')?>  animate-load" data-event="jqm" data-param-form_id="ASK" data-autoload-need_product="<?=CMax::formatJsName($arResult['NAME']);?>" data-name="question"><span><?=(strlen($arParams['S_ASK_QUESTION']) ? $arParams['S_ASK_QUESTION'] : Loc::getMessage('S_ASK_QUESTION'))?></span></span>
												</div>
											<?endif;?>
										</div>
									</div>
								</div>
							<?endif;?>
						</div>
					<?endif;?>
				</div>
			</div>
		<?elseif($arResult['GALLERY']):?>
			<div class="head-block">
				<div class="row flexbox">
					<div class="col-md-6 item info_wrap">
						<div class="info wti" >
							<?if($arResult['DISPLAY_PROPERTIES_FORMATTED'] || ($bShowFormQuestion || $bShowFormOrder)):?>
								<div class="row">
									<div class="col-md-12">
										<?if(isset($arResult['DISPLAY_PROPERTIES']['DATA'])):?>
											<div class="date font_upper muted"><?=$arResult['DISPLAY_PROPERTIES_FORMATTED']['DATA']['VALUE']?></div>
										<?endif;?>
										<?if(
											isset($arResult['PROPERTIES']['TASK_PROJECT']['~VALUE']['TEXT']) && 
											$arResult['PROPERTIES']['TASK_PROJECT']['~VALUE']['TEXT']
										):?>
											<div class="task darken "><?=$arResult['PROPERTIES']['TASK_PROJECT']['~VALUE']['TEXT'];?></div>
										<?endif;?>
										<?if($arResult['DISPLAY_PROPERTIES_FORMATTED'] && !$bBannerChar):?>
											<div class="properties">
												<?foreach($arResult['DISPLAY_PROPERTIES_FORMATTED'] as $code => $arProp):?>
													<?
													if($code == 'DATA' || $arProp['PROPERTY_TYPE'] == 'E' || $arProp['PROPERTY_TYPE'] == 'G')
														continue;
													?>
													<div class="property">
														<div class="title-prop font_upper muted"><?=$arProp['NAME']?></div>
														<div class="value darken">
															<?if(is_array($arProp['DISPLAY_VALUE'])):?>
																<?foreach($arProp['DISPLAY_VALUE'] as $key => $value):?>
																	<?if($arProp['DISPLAY_VALUE'][$key + 1]):?>
																		<?=$value.'&nbsp;/ '?>
																	<?else:?>
																		<?=$value?>
																	<?endif;?>
																<?endforeach;?>
															<?elseif($code == 'SITE'):?>
																<!--noindex-->
																<a class="dark-color" href="<?=(strpos($arProp['VALUE'], 'http') === false ? 'http://' : '').$arProp['VALUE'];?>" rel="nofollow" target="_blank">
																	<?=strpos($arProp['VALUE'], '?') === false ? $arProp['VALUE'] : explode('?', $arProp['VALUE'])[0]?>
																</a>
																<!--/noindex-->
															<?elseif($code == 'EMAIL'):?>
																<a href="mailto:<?=$arProp['VALUE']?>" class="dark-color"><?=$arProp['VALUE']?></a>
															<?elseif($code == 'PHONE'):?>
																<a href="tel:<?=str_replace(array(' ', ',', '-', '(', ')'), '', $arProp['VALUE']);?>" class="dark-color"><?=$arProp['VALUE']?></a>
															<?else:?>
																<?=$arProp['VALUE']?>
															<?endif;?>
														</div>
													</div>
												<?endforeach;?>
											</div>
										<?endif;?>
									</div>

									<?if($bShowFormQuestion || $bShowFormOrder):?>
										<div class="col-md-12">
											<div class="buttons-block">
												<div class="wrap">
													<?if($bShowFormOrder):?>
														<div class="form_btn">
															<span class="btn btn-default btn-lg animate-load" data-event="jqm" data-param-form_id="<?=($arParams["FORM_ID_ORDER_SERVISE"] ? $arParams["FORM_ID_ORDER_SERVISE"] : 'SERVICES');?>" data-name="order_services" data-autoload-service="<?=CMax::formatJsName($arResult['NAME']);?>" data-autoload-project="<?=CMax::formatJsName($arResult['NAME']);?>" data-autoload-product="<?=CMax::formatJsName($arResult['NAME']);?>"><span><?=(strlen($arParams['S_ORDER_SERVISE']) ? $arParams['S_ORDER_SERVISE'] : Loc::getMessage('S_ORDER_SERVISE'))?></span></span>
														</div>
													<?endif;?>
													<?if($bShowFormQuestion):?>
														<div class="form_btn">
															<span class="btn btn-lg <?=($bShowFormOrder ? 'btn-transparent-border-color' : 'btn-default')?> <?=($bShowFormOrder ? 'white' : '')?>  animate-load" data-event="jqm" data-param-form_id="ASK" data-autoload-need_product="<?=CMax::formatJsName($arResult['NAME']);?>" data-name="question"><span><?=(strlen($arParams['S_ASK_QUESTION']) ? $arParams['S_ASK_QUESTION'] : Loc::getMessage('S_ASK_QUESTION'))?></span></span>
														</div>
													<?endif;?>
												</div>
											</div>
										</div>
									<?endif;?>
								</div>
							<?endif;?>
						</div>
					</div>
					<div class="gallery_wrap col-md-6 pull-right item">
						<?//gallery?>
						<div class="big-gallery-block ">
						    <div class="owl-carousel owl-theme owl-bg-nav short-nav" data-slider="content-detail-gallery__slider" data-plugin-options='{"items": "1", "autoplay" : false, "autoplayTimeout" : "3000", "smartSpeed":1000, "dots": true, "nav": true, "loop": false, "rewind":true, "margin": 10}'>
							<?foreach($arResult['GALLERY'] as $i => $arPhoto):?>
							    <div class="item">
								<a href="<?=$arPhoto['DETAIL']['SRC']?>" class="fancy" data-fancybox="big-gallery-top" target="_blank" title="<?=$arPhoto['TITLE']?>">
								    <div class="lazy" data-src="<?=$arPhoto['PREVIEW']['src']?>" style="background-image:url('<?=\Aspro\Functions\CAsproMax::showBlankImg($arPhoto['PREVIEW']['src']);?>')"></div>
								</a>
							    </div>
							<?endforeach;?>
						    </div>
						</div>
					</div>

				</div>
			</div>
		<?endif;?>
	</div>
	<?$htmlProjectBlock=ob_get_clean();?>
	<?//$APPLICATION->AddViewContent('top_section_filter_content', $html, '1000');?>
	<?//$this->EndViewTarget();?>
<?endif;?>
<?/*end bigtop block*/?>

<?/*top banner-image*/?>
<?$this->SetViewTarget('top_section_filter_content');?>
	<?if($arResult['FIELDS']['DETAIL_PICTURE'] && $arResult['PROPERTIES']['PHOTOPOS']['VALUE_XML_ID'] == 'TOP'):?>
		
		<?//ob_start();?>
		<div class="detailimage image-head">
			<img data-src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($arResult['DETAIL_PICTURE']['SRC']);?>" class="img-responsive lazy" title="<?=$atrTitle?>" alt="<?=$atrAlt?>"/>
		</div>
		<?//$html=ob_get_clean();?>
		<?//$APPLICATION->AddViewContent('top_section_filter_content', $html, '500');?>
	<?endif;?>

	<?if($arParams["SHOW_TOP_PROJECT_BLOCK"] == "Y"):?>
		<?=$htmlProjectBlock?>
	<?endif;?>

<?$this->EndViewTarget();?>
<?/*__*/?>


    

<?// date active from or dates period active?>

	<?$this->SetViewTarget('PERIOD_LINE');?>
	<div class="period_wrapper_inner">
		<?if($bShowDopBlock):?>
			<div class="info-sticker-block inline">
				<?if($arResult['DISPLAY_PROPERTIES']['SALE_NUMBER']['VALUE']):?>
					<div class="sale-text font_sxs rounded2"><?=$arResult['DISPLAY_PROPERTIES']['SALE_NUMBER']['VALUE'];?></div>
				<?endif;?>
				<?if($bDiscountCounter):?>
					<?\Aspro\Functions\CAsproMax::showDiscountCounter(0, $arResult, array(), array(), '', 'compact');?>
				<?endif;?>
			</div>
		<?endif;?>
	    
		<?if($bActiveDate):?>
			<div class="period-block darken ncolor font_xs <?=($arResult['ACTIVE_TO'] ? 'red' : '');?>">
				<?if($arParams['SALE_MODE'] == 'Y'):?>
					<?=CMax::showIconSvg("sale", SITE_TEMPLATE_PATH.'/images/svg/icon_discount.svg', '', '', true, false);?>
				<?endif;?>
				<?if(strlen($arResult['DISPLAY_PROPERTIES']['PERIOD']['VALUE'])):?>
					<span class="date"><?=$arResult['DISPLAY_PROPERTIES']['PERIOD']['VALUE']?></span>
				<?else:?>
					<span class="date"><?=$arResult['DISPLAY_ACTIVE_FROM']?></span>
				<?endif;?>
			</div>
		<?endif;?>
	</div>
	<?$this->EndViewTarget();?>
	

<?//$APPLICATION->ShowViewContent('TIZERS_BLOCK');?>
	

<?/*staff mode*/?>
<?if($arParams["STAFF_MODE"] == "Y"):?>
	<div class="ordered-block staff_info_block bordered">
		<?$staffImage = $arResult['FIELDS']['DETAIL_PICTURE'] ? $arResult['FIELDS']['DETAIL_PICTURE'] : $arResult['FIELDS']['PREVIEW_PICTURE'] ;
		$staffImageTitle = $atrTitle ? $atrTitle : $arResult['NAME'];
		$staffImageAlt = $atrAlt ? $atrAlt : $arResult['NAME'];
		?>
		<div class="staff_top_wrapper clearfix">
			<div class="detailimage">
				<?if($staffImage):?>
					<a href="<?=$staffImage['SRC']?>" class="fancy" title="<?=$staffImageTitle?>">
						<img data-src="<?=$staffImage['SRC']?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($staffImage['SRC']);?>" class="img-responsive lazy" title="<?=$staffImageTitle?>" alt="<?=$staffImageAlt?>" />
					</a>
				<?else:?>
					<a href="<?=SITE_TEMPLATE_PATH.'/images/svg/noimage_content.svg'?>" class="fancy" >
					<img data-src="<?=SITE_TEMPLATE_PATH.'/images/svg/noimage_content.svg'?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg(SITE_TEMPLATE_PATH.'/images/svg/noimage_content.svg');?>" class="img-responsive lazy" />
					</a>
				<?endif;?>
			</div>
		
			<div class="properties_block">
				<?if($arResult['DISPLAY_PROPERTIES_FORMATTED'] && !$bBannerChar):?>
		
		
						<?if(isset($arResult["DISPLAY_PROPERTIES"]['POST']) && strlen($arResult["DISPLAY_PROPERTIES"]['POST']['DISPLAY_VALUE'])):?>
							<div class="post">
								<div class="prop-title font_upper"><?=$arResult["DISPLAY_PROPERTIES"]['POST']['NAME'];?></div>
								<div class="value darken font_mxs"><?=$arResult["DISPLAY_PROPERTIES"]['POST']['DISPLAY_VALUE'];?></div>
							</div>
						<?endif;?>
						<?
						$bFormButton = (isset($arResult['DISPLAY_PROPERTIES']['SEND_MESSAGE_BUTTON']) && $arResult['DISPLAY_PROPERTIES']['SEND_MESSAGE_BUTTON']['VALUE_XML_ID'] == 'Y' ? true : false);
						?>
						<?if($bFormButton):?>
							<div class="send_message_button">
								<div class="button_wrap">
									<span class="animate-load btn btn-transparent-border-color white btn-xs" data-event="jqm" data-param-form_id="ASK_STAFF" data-autoload-staff="<?=CMax::formatJsName($arItem['NAME'])?>" data-name="ask_staff"><?=(strlen($arParams['SEND_MESSAGE_BUTTON_TEXT']) ? $arParams['SEND_MESSAGE_BUTTON_TEXT'] : Loc::getMessage('SEND_MESSAGE_BUTTON_TEXT'))?></span>
								</div>
							</div>
						<?else:?>
							<hr/>
						<?endif?>
						<div class="properties">
						<?foreach($arResult['DISPLAY_PROPERTIES_FORMATTED'] as $code => $arProp):?>
							<?
							if($code == 'POST' || $code == 'SEND_MESSAGE_BUTTON' || $arProp['PROPERTY_TYPE'] == 'E' || $arProp['PROPERTY_TYPE'] == 'G')
								continue;
							?>
							<div class="property">
								<div class="title-prop muted font_upper"><?=$arProp['NAME']?></div>
								<div class="value darken">
									<?if(is_array($arProp['DISPLAY_VALUE'])):?>
										<?foreach($arProp['DISPLAY_VALUE'] as $key => $value):?>
											<?if($arProp['DISPLAY_VALUE'][$key + 1]):?>
												<?=$value.'&nbsp;/ '?>
											<?else:?>
												<?=$value?>
											<?endif;?>
										<?endforeach;?>
									<?elseif($code == 'SITE'):?>
										<!--noindex-->
										<a class="dark-color" href="<?=(strpos($arProp['VALUE'], 'http') === false ? 'http://' : '').$arProp['VALUE'];?>" rel="nofollow" target="_blank">
											<?=strpos($arProp['VALUE'], '?') === false ? $arProp['VALUE'] : explode('?', $arProp['VALUE'])[0]?>
										</a>
										<!--/noindex-->
									<?elseif($code == 'EMAIL'):?>
										<a href="mailto:<?=$arProp['VALUE']?>" class="dark-color"><?=$arProp['VALUE']?></a>
									<?elseif($code == 'PHONE'):?>
										<a href="tel:<?=str_replace(array(' ', ',', '-', '(', ')'), '', $arProp['VALUE']);?>" class="dark-color"><?=$arProp['VALUE']?></a>
									<?else:?>
										<?=$arProp['DISPLAY_VALUE']?>
									<?endif;?>
								</div>
							</div>
						<?endforeach;?>
					</div>
					<?if(isset($arResult['SOCIAL_PROPS']) && $arResult['SOCIAL_PROPS']):?>
						<div class="bottom-soc-props social_props">
							<!-- noindex -->
								<?foreach($arResult['SOCIAL_PROPS'] as $arProp):?>
									<a href="<?=$arProp['VALUE'];?>" target="_blank" rel="nofollow" class="value <?=strtolower($arProp['CODE']);?>"><?//=$arProp['VALUE'];?>
										<?=(isset($arProp['FILE']) && $arProp['FILE'] ? CMax::showIconSvg(strtolower($arProp['CODE']), $arProp['FILE']) : '');?>
										<?//=(isset($arProp['FILE']) && $arProp['FILE'] ? CPriority::showIconSvg($arProp['FILE']) : '');?>
									</a>
								<?endforeach;?>
							<!-- /noindex -->
						</div>
					<?endif;?>
				<?endif;?>
			</div>
		</div>
		<div class="text_block">
			<?=$htmlPreview;?>
			<div class="detail_staff_text muted777">
				<?=$htmlDetail;?>
			</div>
		</div>
	</div>
	
<?endif;?>
<?/*end staff mode*/?>
	

<?//ob_start();//detail picture block?>

<?//$html=ob_get_clean();?>
<?if($arResult['FIELDS']['DETAIL_PICTURE'] && $arResult['PROPERTIES']['PHOTOPOS']['VALUE_XML_ID'] == "WIDE_TOP"):?>
	<?$this->SetViewTarget('DETAIL_IMG');?>
		<div class="detailimage image-wide "><a href="<?=$arResult['DETAIL_PICTURE']['SRC']?>" class="fancy" title="<?=$atrTitle?>">
			<img data-src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($arResult['DETAIL_PICTURE']['SRC']);?>" class="img-responsive lazy" title="<?=$atrTitle?>" alt="<?=$atrAlt?>" /></a>
		</div>
	<?$this->EndViewTarget();?>
<?endif;?>





<?$this->SetViewTarget('DETAIL_CONTENT');?>	
<?
$bShowDetailContent = false;
if($arParams["IS_LANDING"] != "Y" && strlen($arResult['FIELDS']['PREVIEW_TEXT'])) {
	$bShowDetailContent = true;
}

if(strlen($arResult['FIELDS']['DETAIL_TEXT']) ||  $arResult['FIELDS']['DETAIL_PICTURE']) {
	$bShowDetailContent = true;
}

if($arParams["STAFF_MODE"] == "Y" || $arParams["SHOW_TOP_PARTNERS_BLOCK"] == "Y") {
	$bShowDetailContent = false;
}
?>
<?if($bShowDetailContent):?>
	<?
	$showSideImg =$arResult['DISPLAY_PROPERTIES']['SIDE_IMAGE'] && strlen($arResult['DISPLAY_PROPERTIES']['SIDE_IMAGE']["FILE_VALUE"]["SRC"]);
	$sideImageType = $showSideImg ? $arResult['PROPERTIES']['SIDE_IMAGE_TYPE']["VALUE_XML_ID"] : 'N';
	$imageInPartnersMode = $bPartnersMode && ($arResult['PROPERTIES']['PHOTOPOS']['VALUE_XML_ID'] == 'LEFT' || $arResult['PROPERTIES']['PHOTOPOS']['VALUE_XML_ID'] == 'RIGHT');
	?>
	<div class="ordered-block <?=($bPartnersMode ? 'partners_mode' : '')?> detail_content_wrapper side_image_<?=$sideImageType ? $sideImageType :'right';?> clearfix">
	    
	    <?if($arResult['FIELDS']['DETAIL_PICTURE'] 
	    	&& (!in_array($arResult['PROPERTIES']['PHOTOPOS']['VALUE_XML_ID'],array('TOP', 'LEFT', 'RIGHT', 'WIDE_TOP')) || $imageInPartnersMode) 
	    	&& $arParams["SHOW_TOP_PROJECT_BLOCK"] != "Y" && $arParams["STAFF_MODE"] != "Y" && $arParams["SHOW_TOP_PARTNERS_BLOCK"] != "Y" ):?>

			<div class="detailimage image-wide <?=($bPartnersMode ? 'img_side_'.$arResult['PROPERTIES']['PHOTOPOS']['VALUE_XML_ID'] : '');?>"><a href="<?=$arResult['DETAIL_PICTURE']['SRC']?>" class="fancy" title="<?=$atrTitle?>">
				<img data-src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($arResult['DETAIL_PICTURE']['SRC']);?>" class="img-responsive lazy" title="<?=$atrTitle?>" alt="<?=$atrAlt?>" /></a>
			</div>

		<?endif;?>

		<?if($arParams["SHOW_TOP_PARTNERS_BLOCK"] == "Y"):?>
			<?=$htmlTopPartners;?>
		<?endif;?>

		<div class="inner_wrapper_text">
			<div class="content-text <?=($bWideText ? 'wide-text' : '');?> muted777">


				<?// single detail image?>
				<?if($arResult['FIELDS']['DETAIL_PICTURE'] && $arParams["SHOW_TOP_PROJECT_BLOCK"] != "Y" && !$bPartnersMode):?>
					<?
					//$atrTitle = (strlen($arResult['DETAIL_PICTURE']['DESCRIPTION']) ? $arResult['DETAIL_PICTURE']['DESCRIPTION'] : (strlen($arResult['DETAIL_PICTURE']['TITLE']) ? $arResult['DETAIL_PICTURE']['TITLE'] : $arResult['NAME']));
					//$atrAlt = (strlen($arResult['DETAIL_PICTURE']['DESCRIPTION']) ? $arResult['DETAIL_PICTURE']['DESCRIPTION'] : (strlen($arResult['DETAIL_PICTURE']['ALT']) ? $arResult['DETAIL_PICTURE']['ALT'] : $arResult['NAME']));
					?>
					<?if($arResult['PROPERTIES']['PHOTOPOS']['VALUE_XML_ID'] == 'LEFT'):?>
						<div class="detailimage image-left col-md-4 col-sm-4 col-xs-12"><a href="<?=$arResult['DETAIL_PICTURE']['SRC']?>" class="fancy" title="<?=$atrTitle?>">
							<img data-src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($arResult['DETAIL_PICTURE']['SRC']);?>" class="img-responsive lazy" title="<?=$atrTitle?>" alt="<?=$atrAlt?>" /></a>
						</div>
					<?elseif($arResult['PROPERTIES']['PHOTOPOS']['VALUE_XML_ID'] == 'RIGHT'):?>
						<div class="detailimage image-right col-md-4 col-sm-4 col-xs-12"><a href="<?=$arResult['DETAIL_PICTURE']['SRC']?>" class="fancy" title="<?=$atrTitle?>">
							<img data-src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($arResult['DETAIL_PICTURE']['SRC']);?>" class="img-responsive lazy" title="<?=$atrTitle?>" alt="<?=$atrAlt?>" /></a>
						</div>
					<?endif;?>
				<?endif;?>

				<?// element name?>
				<?if($arParams['DISPLAY_NAME'] != 'N' && strlen($arResult['NAME'])):?>
					<h2><?=$arResult['NAME']?></h2>
				<?endif;?>

				<?// element preview text?>
				<?if(!$bShowTopBanner && $arParams["IS_LANDING"] != "Y"):?>
					<?=$htmlPreview;?>
				<?endif;?>

				<?// element detail text?>
				<?=$htmlDetail;?>
				
			</div>
			<?if($showSideImg && $sideImageType != 'N'):?>
			<div class="content-image "><?//echo '<pre>',var_dump($arResult['PROPERTIES']['SIDE_IMAGE_TYPE']),'</pre>';?>
				<img data-src="<?=$arResult['DISPLAY_PROPERTIES']['SIDE_IMAGE']["FILE_VALUE"]["SRC"]?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($arResult['DISPLAY_PROPERTIES']['SIDE_IMAGE']["FILE_VALUE"]["SRC"]);?>" class="img-responsive lazy"/>
			</div>
			<?endif;?>
		</div>
	</div>
	<div class="line-after"></div>
<?endif;?>
<?$this->EndViewTarget();?>
	
	
<?// order block?>
<?if($bShowFormOrder || $bShowFormQuestion || $arResult["SHOW_BUY_BUTTON"]):?>
<?$this->SetViewTarget('CONTENT_ORDER_FORM');?>
<div class="ordered-block wraps form-order-block ">
	<table class="order-block bordered <?=($arResult["SHOW_BUY_BUTTON"] ? 'buy_services js-notice-block' : '')?>">
		<tr>
			<td class="col-md-9 col-sm-8 col-xs-7 valign text-col">
				<div class="block-item"> 
					<div class="flexbox flexbox--row">
						<div class="block-item__image icon_sendmessage"><?=CMax::showIconSvg("sendmessage", SITE_TEMPLATE_PATH."/images/svg/sendmessage.svg", "", "colored_theme_svg", true, false);?></div>
						<div class="text darken">
							<?$APPLICATION->IncludeComponent(
								'bitrix:main.include',
								'',
								Array(
									'AREA_FILE_SHOW' => 'page',
									'AREA_FILE_SUFFIX' => 'services',
									'EDIT_TEMPLATE' => ''
								)
							);?>
						</div>
					</div>
				</div>
			</td>
			<td class="col-md-3 col-sm-4 col-xs-5 valign btns-col">
				<?if($arResult["SHOW_BUY_BUTTON"]):?>
					<?if(isset($arResult["BUTTON_RESULT_PRICE"]['PRICE']) && $arResult["BUTTON_RESULT_PRICE"]['PRICE'] > 0):?>
						<div class="prices prices-services-detail">
							<div class="price font_mlg font-bold darken"><?=CurrencyFormat($arResult["BUTTON_RESULT_PRICE"]['PRICE'], $arResult["BUTTON_RESULT_PRICE"]['CURRENCY']);?></div>
							<?if(isset($arResult["BUTTON_RESULT_PRICE"]['BASE_PRICE']) && $arResult["BUTTON_RESULT_PRICE"]['BASE_PRICE'] !== $arResult["BUTTON_RESULT_PRICE"]['PRICE'] ):?>
								<div class="price_old muted font_sm"><?=CurrencyFormat($arResult["BUTTON_RESULT_PRICE"]['BASE_PRICE'], $arResult["BUTTON_RESULT_PRICE"]['CURRENCY']);?></div>
							<?endif;?>
							<?\Aspro\Functions\CAsproMax::showBonusBlockDetail($arResult);?>
							<?\Aspro\Functions\CAsproMax::showBonusComponentDetail($arResult);?>
						</div>
					<?endif;?>
					<div class="btns">
						<div class="counter_wrapp list big clearfix <?=($bShowFormQuestion ? 'wquest' : '')?>">
							<?
							$arAddToBasketData = array();
							$totalCount = 999;
							$arResult["CAN_BUY"] = true;
							$arResult["MIN_PRICE"]["VALUE"] = $arResult["BUTTON_RESULT_PRICE"];
							$basketUrl = CMax::GetFrontParametrValue('BASKET_PAGE_URL');
							$arResult["strMainID"] = $this->GetEditAreaId($arResult['ID']);
							$arItemIDs=CMax::GetItemsIDs($arResult, "Y");
							$arAddToBasketData = CMax::GetAddToBasketArray($arResult, $totalCount, 1/*$arParams["DEFAULT_COUNT"]*/, $basketUrl, true, $arItemIDs["ALL_ITEM_IDS"], '', $arParams);
							
							?>
							<?//if(($arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_DETAIL"] && $arAddToBasketData["ACTION"] == "ADD") && $arAddToBasketData["CAN_BUY"]):?>
								<?=\Aspro\Functions\CAsproMax::showItemCounter($arAddToBasketData, $arResult["ID"], $arItemIDs, $arParams, '', '', true, true);?>
							<?//endif;?>

							<div id="<? echo $arItemIDs["ALL_ITEM_IDS"]['BASKET_ACTIONS']; ?>" class="button_block <?=($arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_DETAIL"] ? '' : 'wide')?>">
								<!--noindex-->
									<?=$arAddToBasketData["HTML"]?>
								<!--/noindex-->
							</div>
							<?if($bShowFormQuestion):?><span class="quest_btn"><span class="btn  btn-transparent-border-color animate-load question" data-event="jqm" data-param-form_id="ASK" data-name="ASK" data-autoload-product_name="<?=CMax::formatJsName($arResult['NAME']);?>"><?=CMax::showIconSvg("question", SITE_TEMPLATE_PATH.'/images/svg/qmark.svg', "", "colored_theme_svg", true, false);?></span></span><?endif;?>
						</div>
					</div>
				<?else:?>
					<?$bOneButton = (!$bShowFormQuestion && $bShowFormOrder) || ($bShowFormQuestion && !$bShowFormOrder);?>
					<?if(isset($arResult['PROPERTIES']['PRICE']) && $arResult['PROPERTIES']['PRICE']['VALUE']):?>
						<div class="prices  <?=$bOneButton ? 'text-right' : ''?>">
							<div class="price font_mlg font-bold darken"><?=$arResult['PROPERTIES']['PRICE']['VALUE'];?></div>
							<?if(isset($arResult['PROPERTIES']['PRICE_OLD']) && $arResult['PROPERTIES']['PRICE_OLD']['VALUE']):?>
									<div class="price_old muted font_sm"><?=$arResult['PROPERTIES']['PRICE_OLD']['VALUE'];?></div>
							<?endif;?>
						</div>
					<?endif;?>
					<div class="btns">
						<?if($bShowFormOrder):?><span><span class="btn btn-default  animate-load" data-event="jqm" data-param-form_id="<?=($arParams["FORM_ID_ORDER_SERVISE"] ? $arParams["FORM_ID_ORDER_SERVISE"] : 'SERVICES');?>" data-name="order_services" data-autoload-service="<?=CMax::formatJsName($arResult['NAME']);?>" data-autoload-project="<?=CMax::formatJsName($arResult['NAME']);?>"><span><?=(strlen($arParams['S_ORDER_SERVISE']) ? $arParams['S_ORDER_SERVISE'] : Loc::getMessage('S_ORDER_SERVISE'))?></span></span></span><?endif;?>
						<?if($bShowFormQuestion && $bShowFormOrder):?><span><span class="btn  btn-transparent-border-color animate-load question" data-event="jqm" data-param-form_id="ASK" data-name="ASK" data-autoload-product_name="<?=CMax::formatJsName($arResult['NAME']);?>"><?=CMax::showIconSvg("question", SITE_TEMPLATE_PATH.'/images/svg/qmark.svg', "", "colored_theme_svg", true, false);?></span></span><?endif;?>
						<?if($bShowFormQuestion && !$bShowFormOrder):?><span><span class="btn btn-default  animate-load" data-event="jqm" data-param-form_id="ASK" data-name="ASK" data-autoload-product_name="<?=CMax::formatJsName($arResult['NAME']);?>"><span><?=(strlen($arParams['S_ASK_QUESTION']) ? $arParams['S_ASK_QUESTION'] : Loc::getMessage('S_ASK_QUESTION'))?></span></span></span><?endif;?>
					</div>
				<?endif;?>

			</td>
		</tr>
	</table>
</div>
<div class="line-after"></div>
<?$this->EndViewTarget();?>
<?endif;?>


<?if($arResult['DISPLAY_PROPERTIES_FORMATTED'] && !$bBannerChar && $arParams["SHOW_TOP_PROJECT_BLOCK"] != "Y" && $arParams["STAFF_MODE"] != "Y" && $arParams["SHOW_TOP_PARTNERS_BLOCK"] != "Y"):?>	
	<?$this->SetViewTarget('CONTENT_PROPS_INFO');?>
	<div class="ordered-block wraps properties-block catalog_detail with-title">			
		<div class="ordered-block__title option-font-bold font_lg"> 
		    <?=($arParams["T_CHARACTERISTICS"] ? $arParams["T_CHARACTERISTICS"] : Loc::getMessage("T_CHARACTERISTICS"));?> 
		</div>
		<div class="char_block bordered rounded3 js-scrolled">
		    <table class="props_list nbg">
			<?foreach($arResult["DISPLAY_PROPERTIES_FORMATTED"] as $arProp):?>
			    <tr>
				<td class="char_name">
				    <?if($arProp["HINT"] && $arParams["SHOW_HINTS"]=="Y"):?><div class="hint"><span class="icon"><i>?</i></span><div class="tooltip"><?=$arProp["HINT"]?></div></div><?endif;?>
				    <div class="props_item <?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"){?>whint<?}?>">
					<span><?=$arProp["NAME"]?></span>
				    </div>
				</td>
				<td class="char_value">
				    <span>
					<?if(is_array($arProp["DISPLAY_VALUE"]) && count($arProp["DISPLAY_VALUE"]) > 1):?>
					    <?=implode(', ', $arProp["DISPLAY_VALUE"]);?>
					<?else:?>
					    <?=$arProp["DISPLAY_VALUE"];?>
					<?endif;?>
				    </span>
				</td>
			    </tr>
			<?endforeach;?>
		    </table>
		</div>
	</div>
	<div class="line-after"></div>
	<?$this->EndViewTarget();?>
<?endif;?>

<?if($arResult['TAGS']):?>
    <?$this->SetViewTarget('tags_content');?>
        <div class="search-tags-cloud">
            <div class="tags_title darken font_md option-font-bold"><?=Loc::getMessage('TAGS');?></div>
            <div class="tags">
                <?$arTags = explode(",", $arResult['TAGS']);?>
                <?foreach($arTags as $text):?>
                    <a href="<?=SITE_DIR;?>search/index.php?tags=<?=htmlspecialcharsex($text);?>" rel="nofollow"><?=$text;?></a>
                <?endforeach;?>
            </div>
        </div>
    <?$this->EndViewTarget();?>
<?endif;?>
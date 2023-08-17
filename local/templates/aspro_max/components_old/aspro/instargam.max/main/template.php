<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */

$this->setFrameMode(true);?>
<?
if($arResult['ITEMS']):?>
	<?
	$bNoMargin = ($arParams['NO_MARGIN'] == 'Y');
	$bWide = ($arParams['WIDE_BLOCK'] == 'Y');
	$bWideFirstBlock = ($arParams['WIDE_FIRST_BLOCK'] == 'Y');

	if($bWideFirstBlock)
		$bNoMargin = true;

	$col = floor(12/$arParams['LINE_ELEMENT_COUNT']);
	if($arParams['LINE_ELEMENT_COUNT'] == 5)
		$col = '20';
	elseif($arParams['LINE_ELEMENT_COUNT'] == 8)
		$col = '12-5';
	if(!$col)
		$col = 3;

	$bSmallBlocks = $arParams['LINE_ELEMENT_COUNT'] >= 6;

	$sTemplateMobile = (isset($arResult['MOBILE_TEMPLATE']) ? $arResult['MOBILE_TEMPLATE'] : '');
	$bSlider = ($sTemplateMobile === 'slider');
	//var_dump($bSlider);
	?>
	<div class="content_wrapper_block <?=$templateName;?> <?=$bSmallBlocks ? 'small' : ''?>">
		<div class="maxwidth-theme<?=($bWide ? ' wide' : '');?>">
			<div class="instagram_wrapper">
				<?$obParser = new CTextParser;?>
				<div class="item-views front blocks ">
					<?if(!$bWide):?>
						<?if($arResult['DOP_TEXT'] && !$bWideFirstBlock):?>
							<div class="with-text-block-wrapper">
								<div class="row">
									<div class="col-md-3">
										<h3><?=($arResult['TITLE'] ? $arResult['TITLE'] : \Bitrix\Main\Localization\Loc::getMessage('TITLE'));?></h3>
										<?// intro text?>
										<?if($arParams['INCLUDE_FILE']):?>
											<div class="text_before_items font_xs">
												<?$APPLICATION->IncludeComponent(
													"bitrix:main.include",
													"",
													Array(
														"AREA_FILE_SHOW" => "file",
														"PATH" => $arResult['DOP_TEXT'],
														"EDIT_TEMPLATE" => ""
													)
												);?>
											</div>
										<?endif;?>
										<a href="https://www.instagram.com/<?=$arResult['USER']['username']?>/" class="btn btn-transparent-border-color btn-sm"><?=($arResult['ALL_TITLE'] ? $arResult['ALL_TITLE'] : \Bitrix\Main\Localization\Loc::getMessage('INSTAGRAM_ALL_ITEMS'));?></a>
									</div>
									<div class="col-md-9 instagram_body">
						<?else:?>
							<div class="top_block">
								<h3><?=($arResult['TITLE'] ? $arResult['TITLE'] : \Bitrix\Main\Localization\Loc::getMessage('TITLE'));?></h3>
								<a class="pull-right font_upper muted" href="https://www.instagram.com/<?=$arResult['USER']['username']?>/" target="_blank"><?=CMax::showIconSvg("resume", SITE_TEMPLATE_PATH."/images/svg/social/Instagram.svg", "", "inline", true, false);?><span><?=($arResult['ALL_TITLE'] ? $arResult['ALL_TITLE'] : \Bitrix\Main\Localization\Loc::getMessage('INSTAGRAM_ALL_ITEMS'));?></span></a>
							</div>
						<?endif;?>
					<?endif;?>
					<div class="instagram clearfix">
						<?$index = 0;?>
						<div class="items row flexbox<?=($bNoMargin ? ' margin0 rounded3' : '');?> <?=$sTemplateMobile;?><?=($bSlider ? ' mobile-slider' : '');?> <?=($bSlider && !$bWideFirstBlock ? ' swipeignore mobile-overflow mobile-margin-16 ' : '');?>">
							<?if($bWideFirstBlock):?>
								<?$arItem = array_shift($arResult['ITEMS']);
								$arItem['LINK'] = $arItem['thumbnail_url'] ? $arItem['thumbnail_url'] : $arItem['media_url'];
								?>
								<div class="item custom">
									<div class="item-wrapper">
										<div class="image shine<?=(!$bNoMargin ? ' rounded3' : '');?>" style="background:url(<?=$arItem['LINK'];?>) center center/cover no-repeat;"><a href="<?=$arItem['permalink']?>" target="_blank"></a></div>
										<a class="wrap scrollbar" href="<?=$arItem['permalink']?>" target="_blank" rel="nofollow">
											<span class="wrapper">
												<span class="date font_upper_md muted"><span><?=FormatDate('d F', strtotime($arItem['timestamp']), 'SHORT');?></span></span>
												<?if($arItem['caption']):?>
													<span class="text font_xs"><?=($obParser->html_cut($arItem['caption'], $arResult['TEXT_LENGTH']));?></span>
												<?endif;?>
											</span>
										</a>
									</div>
								</div>
								<div class="custom <?=($bSlider && $bWideFirstBlock ? ' swipeignore mobile-overflow mobile-margin-16 ' : '');?>">
									<div class="item col-lg-<?=$col;?> col-sm-4 col-xs-6 col-xxs-6 _adaptive <?=($bSlider ? ' item-width-261' : '');?>">
										<div class="item-wrapper">
											<div class="image shine<?=(!$bNoMargin ? ' rounded3' : '');?>" style="background:url(<?=$arItem['LINK'];?>) center center/cover no-repeat;"><a href="<?=$arItem['permalink']?>" target="_blank"></a></div>
											<a class="wrap scrollbar" href="<?=$arItem['permalink']?>" target="_blank" rel="nofollow">
												<span class="wrapper">
													<span class="date font_upper_md muted"><span><?=FormatDate('d F', strtotime($arItem['timestamp']), 'SHORT');?></span></span>
													<?if($arItem['caption']):?>
														<span class="text font_xs"><?=($obParser->html_cut($arItem['caption'], $arResult['TEXT_LENGTH']));?></span>
													<?endif;?>
												</span>
											</a>
										</div>
									</div>
							<?endif;?>
							<?foreach($arResult['ITEMS'] as $arItem):?>
								<?$arItem['LINK'] = $arItem['thumbnail_url'] ? $arItem['thumbnail_url'] : $arItem['media_url'];?>
								<div class="item col-lg-<?=$col;?> col-sm-4 col-xs-6 col-xxs-6 <?=($bSlider ? ' item-width-261' : '');?>">
									<div class="item-wrapper">
										<div class="image shine<?=(!$bNoMargin ? ' rounded3' : '');?>" style="background:url(<?=$arItem['LINK'];?>) center center/cover no-repeat;"><a href="<?=$arItem['permalink']?>" target="_blank"></a></div>
										<a class="wrap scrollbar" href="<?=$arItem['permalink']?>" target="_blank" rel="nofollow">
											<span class="wrapper">
												<span class="date font_upper_md muted"><span><?=FormatDate('d F', strtotime($arItem['timestamp']), 'SHORT');?></span></span>
												<?if($arItem['caption']):?>
													<span class="text font_xs"><?=($obParser->html_cut($arItem['caption'], $arResult['TEXT_LENGTH']));?></span>
												<?endif;?>
											</span>
										</a>
									</div>
								</div>
							<?endforeach;?>
							<?if($bWideFirstBlock):?>
								</div>
							<?endif;?>
						</div>
					</div>
					<?if(!$bWide):?>
						<?if($arResult['DOP_TEXT'] && !$bWideFirstBlock):?>
							</div></div></div>
						<?endif;?>
					<?endif;?>
				</div>
			</div>
		</div>
	</div>
<?endif;?>
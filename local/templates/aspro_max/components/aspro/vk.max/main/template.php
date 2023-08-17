<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */

$this->setFrameMode(true);?>
<?if($arResult['ITEMS']):?>
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
	$bSlider = true;//($sTemplateMobile === 'slider');
	$sGroupLink = 'https://vk.com/'.$arResult['GROUP']['screen_name'];
	$bSquareBlock = $arParams['VIEW_TYPE'] === 'SQUARE';
	$bShowScrollbar = $bSquareBlock;
	?>
	<div class="content_wrapper_block <?=$templateName;?> <?=$bSmallBlocks ? 'small' : ''?>">
		<div class="maxwidth-theme<?=($bWide ? ' wide' : '');?>">
			<div class="vk_wrapper<?= $arParams['VIEW_TYPE'] ? ' vk_wrapper--type-'.strtolower($arParams['VIEW_TYPE']) : '';?>">
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
											<div class="text_before_items font_sm">
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
										<a href="<?= $sGroupLink; ?>" class="btn btn-transparent-border-color btn-sm"><?=($arResult['ALL_TITLE'] ?: \Bitrix\Main\Localization\Loc::getMessage('VK_ALL_ITEMS'));?></a>
									</div>
									<div class="col-md-9 vk_body">
						<?else:?>
							<div class="top_block">
								<h3><?=($arResult['TITLE'] ? $arResult['TITLE'] : \Bitrix\Main\Localization\Loc::getMessage('TITLE'));?></h3>
								<a class="pull-right font_upper muted" href="<?= $sGroupLink; ?>" target="_blank">
									<?=CMax::showIconSvg("resume", SITE_TEMPLATE_PATH."/images/svg/social/vk_main.svg", "", "inline", true, false);?>
									<span><?=($arResult['ALL_TITLE'] ?: \Bitrix\Main\Localization\Loc::getMessage('VK_ALL_ITEMS'));?></span>
								</a>
							</div>
						<?endif;?>
					<?endif;?>
					<div class="vk clearfix">
						<?$index = 0;?>
						<div class="items row flexbox<?=($bNoMargin ? ' margin0 rounded3' : '');?> <?=$sTemplateMobile;?><?=($bSlider ? ' mobile-slider' : '');?> <?=($bSlider && !$bWideFirstBlock ? ' swipeignore mobile-overflow mobile-margin-16 ' : '');?>">
							<?if($bWideFirstBlock):?>
								<?
									$arItem = array_shift($arResult['ITEMS']);
									$arItem['permalink'] = $sGroupLink.'?w=wall'.$arItem['owner_id']."_".$arItem['id'];
									$arItem['LINK'] = '';
									if (isset($arItem['attachments']) && count($arItem['attachments'])) {
										switch ($arItem['attachments'][0]['type']) {
											case "photo":
												$arItem['LINK'] = $arItem['attachments'][0]['photo']['sizes'][6]['url'];
												break;
											case "video":
												$arItem['LINK'] = $arItem['attachments'][0]['video']['image'][3]['url'];
										}
									}
								?>
								<div class="item custom">
									<div class="item-wrapper bg-white<?= !$bSquareBlock ? ' bordered' : ''; ?>">
										<div class="image shine" style="background:url(<?=$arItem['LINK'];?>) center center/cover no-repeat;"><a href="<?=$arItem['permalink']?>" target="_blank"></a></div>
										<a class="wrap<?= $bShowScrollbar ? ' scrollbar' : ''; ?>" href="<?=$arItem['permalink']?>" target="_blank" rel="nofollow">
											<span class="wrapper">
												<span class="date font_xs">
													<span><?=FormatDate('d F Y', $arItem['date'], 'SHORT');?></span>
													<?= isset($arItem['copy_history']) ? CMax::showSpriteIconSvg(SITE_TEMPLATE_PATH."/images/svg/content_icons_sprite.svg#icon-repost", 'svg-repost', [
														'WIDTH' => 14,
														'HEIGHT' => 16,
													]) : ''; ?>
												</span>
												<?if($arItem['caption']):?>
													<span class="text font_sm"><?=($obParser->html_cut($arItem['caption'], $arResult['TEXT_LENGTH']));?></span>
												<?endif;?>
											</span>
										</a>
									</div>
								</div>
								<div class="custom <?=($bSlider && $bWideFirstBlock ? ' swipeignore mobile-overflow mobile-margin-16 ' : '');?>">
									<div class="item col-lg-<?=$col;?> col-sm-4 col-xs-6 col-xxs-6 _adaptive <?=($bSlider ? ' item-width-261' : '');?>">
										<div class="item-wrapper bg-white<?= !$bSquareBlock ? ' bordered' : ''; ?>">
											<div class="image shine" style="background:url(<?=$arItem['LINK'];?>) center center/cover no-repeat;"><a href="<?=$arItem['permalink']?>" target="_blank"></a></div>
											<a class="wrap<?= $bShowScrollbar ? ' scrollbar' : ''; ?>" href="<?=$arItem['permalink']?>" target="_blank" rel="nofollow">
												<span class="wrapper">
													<span class="date font_xs">
														<span><?=FormatDate('d F Y', $arItem['date'], 'SHORT');?></span>
														<?= isset($arItem['copy_history']) ? CMax::showSpriteIconSvg(SITE_TEMPLATE_PATH."/images/svg/content_icons_sprite.svg#icon-repost", 'svg-repost', [
															'WIDTH' => 14,
															'HEIGHT' => 16,
														]) : ''; ?>
													</span>
													<?if($arItem['caption']):?>
														<span class="text font_sm"><?=($obParser->html_cut($arItem['caption'], $arResult['TEXT_LENGTH']));?></span>
													<?endif;?>
												</span>
											</a>
										</div>
									</div>
							<?endif;?>
							<?foreach($arResult['ITEMS'] as $arItem):?>
								<?
									$arItem['permalink'] = $sGroupLink.'?w=wall'.$arItem['owner_id']."_".$arItem['id'];
									$arItem['LINK'] = '';
									if (isset($arItem['attachments']) && count($arItem['attachments'])) {
										$arItem['attachments'] = array_filter($arItem['attachments'], function($attachment){
											return in_array($attachment['type'], ['video', 'photo']);
										});
										switch ($arItem['attachments'][0]['type']) {
											case "photo":
												$arItem['LINK'] = $arItem['attachments'][0]['photo']['sizes'][6]['url'];
												break;
											case "video":
												$arItem['LINK'] = $arItem['attachments'][0]['video']['image'][3]['url'];
										}
									}
								?>
								<div class="item col-lg-<?=$col;?> col-sm-4 col-xs-6 col-xxs-6<?=($bSlider ? ' item-width-261' : '');?><?= $arItem['LINK'] ? ' item--icon-'.$arItem['attachments'][0]['type'] : ' item--no-image'; ?>">
									<div class="item-wrapper bg-white box-shadow rounded3<?= !$bSquareBlock ? ' bordered' : ''; ?>">
										<div class="image shine" style="background:url(<?=$arItem['LINK'];?>) center center/cover no-repeat;"><a href="<?=$arItem['permalink']?>" target="_blank"></a></div>
										<a class="wrap<?= $bShowScrollbar ? ' scrollbar' : ''; ?>" href="<?=$arItem['permalink']?>" target="_blank" rel="nofollow">
											<span class="wrapper">
												<span class="date font_xs">
													<span><?=FormatDate('d F Y', $arItem['date'], 'SHORT');?></span>
													<?= isset($arItem['copy_history']) ? CMax::showSpriteIconSvg(SITE_TEMPLATE_PATH."/images/svg/content_icons_sprite.svg#icon-repost", 'svg-repost', [
														'WIDTH' => 14,
														'HEIGHT' => 16,
													]) : ''; ?>
												</span>
												<?if($arItem['text']):?>
													<span class="text font_sm"><?=($obParser->html_cut($arItem['text'], $arResult['TEXT_LENGTH']));?></span>
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
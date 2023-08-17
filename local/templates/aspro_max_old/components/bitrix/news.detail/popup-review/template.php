<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die();?>
<?$this->setFrameMode(true);?>
<?use \Bitrix\Main\Localization\Loc;?>
<?$bImage = strlen($arResult['FIELDS']['PREVIEW_PICTURE']['SRC']);
$arImage = ($bImage ? CFile::ResizeImageGet($arResult['FIELDS']['PREVIEW_PICTURE']['ID'], array('width' => 70, 'height' => 10000), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true) : array());
$imageSrc = ($bImage ? $arImage['src'] : '');
if(!$imageSrc && strlen($arResult['FIELDS']['DETAIL_PICTURE']['SRC'])){
	$bImage = strlen($arResult['FIELDS']['DETAIL_PICTURE']['SRC']);
	$arImage = ($bImage ? CFile::ResizeImageGet($arResult['FIELDS']['DETAIL_PICTURE']['ID'], array('width' => 90, 'height' => 10000), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true) : array());
	$imageSrc = ($bImage ? $arImage['src'] : '');
	$bLogo = ($imageSrc ? true : false);
}

?>
<div class="review-detail">
	<div class="item-views reviews front">
		<?if(!$GLOBALS['bMobileForm']):?>
			<span class="jqmClose close"><?=CMax::showIconSvg('', SITE_TEMPLATE_PATH.'/images/svg/Close.svg')?></span>
		<?endif;?>
		<div class="item <?=($bImage ? '' : 'wti')?><?=($bLogo ? ' wlogo' : '')?>">
			<div class="header-block">
				<div class="top_wrapper clearfix">
					<?if($imageSrc):?>
						<div class="image pull-left">
							<div class="wrap">
							<?if($imageSrc):?>
								<img class="img-responsive<?=(!$bLogo ? ' rounded' : '')?>" src="<?=$imageSrc?>" alt="<?=($bImage ? $arResult['PREVIEW_PICTURE']['ALT'] : $arResult['NAME'])?>" title="<?=($bImage ? $arResult['PREVIEW_PICTURE']['TITLE'] : $arResult['NAME'])?>" />
							<?endif;?>
							</div>
						</div>
					<?endif;?>
					<div class="top-info">
						<div class="wrap muted">
							<?if(isset($arResult['DISPLAY_PROPERTIES']['POST']) && strlen($arResult['DISPLAY_PROPERTIES']['POST']['VALUE'])):?>
								<span class="font_upper"><?=$arResult['DISPLAY_PROPERTIES']['POST']['VALUE']?></span>
							<?endif?>
							<?if(isset($arResult['DISPLAY_ACTIVE_FROM']) && $arResult['DISPLAY_ACTIVE_FROM'] && isset($arResult['DISPLAY_PROPERTIES']['POST']) && strlen($arResult['DISPLAY_PROPERTIES']['POST']['VALUE'])):?>
								<span class="separator">&ndash;</span>
							<?endif;?>
							<?if(isset($arResult['DISPLAY_ACTIVE_FROM']) && $arResult['DISPLAY_ACTIVE_FROM']):?>
								<span class="date font_upper"><?=$arResult['DISPLAY_ACTIVE_FROM']?></span>
							<?endif;?>
						</div>
						<div class="title font_lg"><?=$arResult['NAME'];?></div>
					</div>
				</div>
			</div>
			<div class="bottom-block">
				<?if(in_array('RATING', $arParams['PROPERTY_CODE'])):?>
					<?$ratingValue = ($arResult['DISPLAY_PROPERTIES']['RATING']['VALUE'] ? $arResult['DISPLAY_PROPERTIES']['RATING']['VALUE'] : 0);?>
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
				<?if($arResult['PREVIEW_TEXT'] && strlen($arResult['PREVIEW_TEXT'])):?>
					<div class="preview-text"><?=$arResult['PREVIEW_TEXT'];?></div>
				<?endif;?>
				<div class="close-block">
					<?if($GLOBALS['bMobileForm']):?>
						<a href="<?=$GLOBALS['backUrl']?>" title="Вернуться назад" class="btn btn-lg btn-default jqmClose"><?=Loc::getMessage('CLOSE_POPUP');?></a>
					<?else:?>
						<span class="btn btn-lg btn-default jqmClose"><?=Loc::getMessage('CLOSE_POPUP');?></span>
					<?endif;?>
				</div>
			</div>
		</div>
	</div>
</div>
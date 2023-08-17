<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$count=count($arResult);?>
<?=CMax::showIconSvg("down", SITE_TEMPLATE_PATH.'/images/svg/compare.svg', '', '', true, false);?>
<div class="count <?=($count ? '' : 'empty_items');?>">
	<span class="colored_theme_bg">
		<div class="items">
			<div><?=$count;?></div>
		</div>
	</span>
</div>
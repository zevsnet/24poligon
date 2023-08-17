<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<?
$templateData = array(
	'MAP_ITEMS' => $arResult['MAP_ITEMS']
);
?>
<?if($arResult['STORES']):?>
	<div class="content_wrapper_block map_type_1 <?=$templateName;?>">
		<div class="maxwidth-theme">
	<?if($arParams['TITLE_BLOCK'] || $arParams['TITLE_BLOCK_ALL']):?>
		<div class="top_block">
			<h3><?=$arParams['TITLE_BLOCK'];?></h3>
			<a href="<?=SITE_DIR.$arParams['ALL_URL'];?>" class="pull-right font_upper"><?=$arParams['TITLE_BLOCK_ALL'] ;?></a>
		</div>
	<?endif;?>
<?endif;?>
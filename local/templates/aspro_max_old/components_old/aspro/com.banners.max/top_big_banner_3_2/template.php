<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?if($arResult['ITEMS']):?>
	<?if($arParams['WIDE_BANNER'] != 'Y'):?>
		<div class="maxwidth-theme">
	<?endif;?>
	<div class="top_big_one_banner short_block top_big_banners <?=($arResult['HAS_CHILD_BANNERS'] ? 'with_childs' : '');?>" style="overflow: hidden;">
		<?include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/components/aspro/com.banners.max/common_files/slider.php');?>
		<?/*if($arResult['HAS_SLIDE_BANNERS'] && $arResult['HAS_CHILD_BANNERS']):?>
			<div class="items clearfix">
				<?foreach($arResult['ITEMS'][$arParams['BANNER_TYPE_THEME_CHILD']]['ITEMS'] as $key => $arItem):?>
					<?include('float.php');?>
				<?endforeach;?>
			</div>
		<?endif;*/?>
		<?if($arResult['HAS_CHILD_BANNERS2']):?>
			<div class="items <?=$arParams['SLIDER_VIEW_MOBILE']?><?=($arParams['SLIDER_VIEW_MOBILE'] === 'slider' ? ' swipeignore mobile-overflow' : '')?> c_<?=count($arResult['ITEMS'][$arParams['BANNER_TYPE_THEME_CHILD2']]['ITEMS']);?>">
				<?foreach($arResult['ITEMS'][$arParams['BANNER_TYPE_THEME_CHILD2']]['ITEMS'] as $key => $arItem):?>
					<?include('float.php');?>
				<?endforeach;?>
			</div>
		<?endif;?>
	</div>
	<?if($arParams['WIDE_BANNER'] != 'Y'):?>
		</div>
	<?endif;?>
<?endif;?>
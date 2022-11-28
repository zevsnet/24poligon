<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?if($arResult['ITEMS']):?>
	<div class="top_big_one_banner top_big_banners nop <?=($arResult['HAS_CHILD_BANNERS'] ? 'with_childs' : '');?> <?=($arParams['MORE_HEIGHT'] ? 'more_height' : '');?>" style="overflow: hidden;">
		<?include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/components/aspro/com.banners.max/common_files/slider.php');?>
		<?if($arResult['HAS_SLIDE_BANNERS'] && $arResult['HAS_CHILD_BANNERS']):?>
			<div class="items clearfix">
				<?foreach($arResult['ITEMS'][$arParams['BANNER_TYPE_THEME_CHILD']]['ITEMS'] as $key => $arItem):?>
					<?include('float.php');?>
				<?endforeach;?>
			</div>
		<?endif;?>
		<?if($arResult['HAS_CHILD_BANNERS2']):?>
			<div class="items <?=$arParams['SLIDER_VIEW_MOBILE']?><?=($arParams['SLIDER_VIEW_MOBILE'] === 'slider' ? ' swipeignore mobile-overflow' : '')?> c_<?=count($arResult['ITEMS'][$arParams['BANNER_TYPE_THEME_CHILD2']]['ITEMS']);?>">
				<?foreach($arResult['ITEMS'][$arParams['BANNER_TYPE_THEME_CHILD2']]['ITEMS'] as $key => $arItem):?>
					<?include('float.php');?>
				<?endforeach;?>
			</div>
		<?endif;?>
	</div>
<?endif;?>
<?$templateData = array(
	'EMPTY_ITEMS' => !count($arResult['ITEMS']),
);?>
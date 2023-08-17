<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?if($arResult['ITEMS']):?>
	<?if($arParams['WIDE_BANNER'] != 'Y'):?>
		<div class="maxwidth-theme">
	<?endif;?>
	<div class="top_big_one_banner short_block top_margin_banner top_big_banners <?=($arResult['HAS_CHILD_BANNERS'] ? 'with_childs' : '');?>" style="overflow: hidden;">
		<div class="row dd">
			<?if($arResult['HAS_SLIDE_BANNERS'] && $arResult['HAS_CHILD_BANNERS']):?>
				<?$iSmallBannersCount = count($arResult["ITEMS"][$arParams["BANNER_TYPE_THEME_CHILD"]]["ITEMS"]);?>
				<div class="col-md-<?=($iSmallBannersCount <= 2 ? "9" : "6 col-m-push-21 col-m-58");?> slide">
					<?include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/components/aspro/com.banners.max/common_files/slider.php');?>
				</div>
				<div class="col-md-3 child hidden_side_mobile <?=($iSmallBannersCount > 2 ? "col-m-pull-58 col-m-21" : "");?>"><div class="row">
					<?foreach($arResult['ITEMS'][$arParams['BANNER_TYPE_THEME_CHILD']]['ITEMS'] as $key => $arItem):?>
						<?if($key > 3)
							continue;?>
						<?if($key == 2):?>
							</div></div><div class="col-md-3 child hidden_side_mobile col-m-21"><div class="row">
						<?elseif($key == 4):?>
							</div></div><div class="col-md-12 items clearfix"><div class="row">
						<?endif;?>
						<?include('float.php');?>
					<?endforeach;?>
				<?if($key <= 4):?>
					</div>
				<?else:?>
					</div>
				<?endif;?>
				</div>
				<div class="col-md-12 items visible_side_mobile clearfix side-childs <?=$arParams['SIDE_SLIDER_VIEW_MOBILE']?><?=($arParams['SIDE_SLIDER_VIEW_MOBILE'] === 'slider' ? ' swipeignore mobile-overflow' : '')?> c_<?=count($arResult['ITEMS'][$arParams['BANNER_TYPE_THEME_CHILD']]['ITEMS']);?>">
					<?foreach($arResult['ITEMS'][$arParams['BANNER_TYPE_THEME_CHILD']]['ITEMS'] as $key => $arItem):?>
						<?if($key > 3)	continue;?>
						<?include('float.php');?>
					<?endforeach;?>
				</div>
			<?elseif($arResult['HAS_SLIDE_BANNERS']):?>
				<div class="col-md-12">
					<?include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/components/aspro/com.banners.max/common_files/slider.php');?>
				</div>
			<?elseif($arResult['HAS_CHILD_BANNERS']):?>
				<div class="col-md-12 items clearfix side-childs <?=$arParams['SIDE_SLIDER_VIEW_MOBILE']?><?=($arParams['SIDE_SLIDER_VIEW_MOBILE'] === 'slider' ? ' swipeignore mobile-overflow' : '')?> c_<?=count($arResult['ITEMS'][$arParams['BANNER_TYPE_THEME_CHILD']]['ITEMS']);?>">
					<?foreach($arResult['ITEMS'][$arParams['BANNER_TYPE_THEME_CHILD']]['ITEMS'] as $key => $arItem):?>
						<?include('float.php');?>
					<?endforeach;?>
				</div>
			<?endif;?>
			<?if($arResult['HAS_CHILD_BANNERS2']):?>
				<div class="col-md-12 items <?=$arParams['SLIDER_VIEW_MOBILE']?><?=($arParams['SLIDER_VIEW_MOBILE'] === 'slider' ? ' swipeignore mobile-overflow' : '')?> c_<?=count($arResult['ITEMS'][$arParams['BANNER_TYPE_THEME_CHILD2']]['ITEMS']);?>">
					<?foreach($arResult['ITEMS'][$arParams['BANNER_TYPE_THEME_CHILD2']]['ITEMS'] as $key => $arItem):?>
						<?include('float.php');?>
					<?endforeach;?>
				</div>
			<?endif;?>
		</div>
	</div>
	<?if($arParams['WIDE_BANNER'] != 'Y'):?>
		</div>
	<?endif;?>
<?endif;?>
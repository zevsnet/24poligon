<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?if($arResult['ITEMS']):?>
	<div class="top_big_banners nop <?=($arResult['HAS_CHILD_BANNERS'] ? 'with_childs' : '');?>">
		<div class="row dd">
			<?if($arResult['HAS_SLIDE_BANNERS'] && $arResult['HAS_CHILD_BANNERS']):?>
				<?$iSmallBannersCount = count($arResult["ITEMS"][$arParams["BANNER_TYPE_THEME_CHILD"]]["ITEMS"]);?>
				<div class="col-md-6 slide">
					<?include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/components/aspro/com.banners.max/common_files/slider.php');?>
				</div>
				<div class="col-md-6 child "><div class="row side-childs <?=$arParams['SIDE_SLIDER_VIEW_MOBILE']?><?=($arParams['SIDE_SLIDER_VIEW_MOBILE'] === 'slider' ? ' swipeignore mobile-overflow' : '')?> c_<?=count($arResult['ITEMS'][$arParams['BANNER_TYPE_THEME_CHILD']]['ITEMS']);?> combine">
					<?foreach($arResult['ITEMS'][$arParams['BANNER_TYPE_THEME_CHILD']]['ITEMS'] as $key => $arItem):?>
						<?if($key > 2) continue;?>
						<?$bRight2Block = (($key == 1 || $key == 2) && $iSmallBannersCount >= 3);?>
						<?if($bRight2Block):?>
							<div class="blocks2">
						<?endif;?>

						<?include('float.php');?>

						<?if($bRight2Block):?>
							</div>
						<?endif;?>

						<?if($key && $key < 4 && $key%3 == 2):?>
							</div></div><div class="col-md-12 items"><div class="row">
						<?endif;?>
					<?endforeach;?>
				<?if($key <= 4):?>
					</div>
				<?else:?>
					</div>
				<?endif;?>
				</div>
			<?elseif($arResult['HAS_SLIDE_BANNERS']):?>
				<div class="col-md-12">
					<?include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/components/aspro/com.banners.max/common_files/slider.php');?>
				</div>
			<?elseif($arResult['HAS_CHILD_BANNERS']):?>
				<div class="col-md-12 items side-childs <?=$arParams['SIDE_SLIDER_VIEW_MOBILE']?><?=($arParams['SIDE_SLIDER_VIEW_MOBILE'] === 'slider' ? ' swipeignore mobile-overflow' : '')?> c_<?=count($arResult['ITEMS'][$arParams['BANNER_TYPE_THEME_CHILD']]['ITEMS']);?>">
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
<?endif;?>
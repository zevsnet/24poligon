<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<?
global $arTheme;
$countSldes = (is_array($arResult['ITEMS'])? count($arResult['ITEMS']) : '0');
$slideshowSpeed = abs(intval($arTheme['PARTNERSBANNER_SLIDESSHOWSPEED']['VALUE']));
$animationSpeed = abs(intval($arTheme['PARTNERSBANNER_ANIMATIONSPEED']['VALUE']));
$bAnimation = (bool)$slideshowSpeed && $countSldes>6;

?>
<?if($arResult['ITEMS']):?>
	<?$bShowTopBlock = ($arParams['TITLE_BLOCK'] || $arParams['TITLE_BLOCK_ALL']);
	$bBordered = ($arParams['BORDERED'] == 'Y');
	?>
	<div class="content_wrapper_block <?=$templateName;?>">
	<div class="maxwidth-theme only-on-front <?=($bShowTopBlock ? '' : 'no-title')?>">
		<?if($bShowTopBlock):?>
			<div class="top_block">
				<h3><?=$arParams['TITLE_BLOCK'];?></h3>
				<?if($arParams['TITLE_BLOCK_ALL']):?>
					<a href="<?=SITE_DIR.$arParams['ALL_URL'];?>" class="pull-right font_upper muted"><?=$arParams['TITLE_BLOCK_ALL'] ;?></a>
				<?endif;?>
			</div>
		<?endif;?>
		<div class="item-views brands owl-carousel owl-theme owl-bg-nav appear-block loading_state short-nav hidden-dots visible-nav swipeignore <?=($bBordered ? 'with_border':'')?> brands_slider slides"  data-plugin-options='{"nav": true, <?=($bBordered ? '"margin":0,' : '"margin":32,')?> "autoplay": true, "useCSS": true, "dots": false, <?=($bAnimation ? '"loop": true,' : '"loop": false,')?> <?=($slideshowSpeed >= 0 ? '"autoplayTimeout": '.$slideshowSpeed.',' : '')?> <?=($animationSpeed >= 0 ? '"smartSpeed": '.$animationSpeed.',' : '')?> "responsive": {"0":{"items": 1, "autoWidth": true, "lightDrag": true},"601":{"items": 3, "autoWidth": false, "lightDrag": false},"768":{"items": 4},"992":{"items": 5}, "1200":{"items": <?=($bBordered ? '5' : '6')?>}}}'>
			<?foreach($arResult["ITEMS"] as $arItem){?>
				<?
					$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
					$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				?>
				<?if( is_array($arItem["PREVIEW_PICTURE"]) ){?>
					<div class="visible item pull-left text-center <?=($bBordered ? 'bordered' : '')?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
						<a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
							<img class="noborder lazy" data-src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($arItem["PREVIEW_PICTURE"]["SRC"]);?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" />
						</a>
					</div>
				<?}?>
			<?}?>
		</div>
	</div></div>
<?endif;?>
<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<?if($arResult["ITEMS"]):?>
	<?foreach($arResult["ITEMS"] as $arItem){
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
		?>
		<div id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="side-block rounded2 bordered box-shadow colored_theme_hover_bg-block side-block--margined">
			<div class="side-block__top side-block__top--small-padding text-center">
				<?if($arItem['FIELDS']['PREVIEW_PICTURE']):?>
					<?$arImage = CFile::ResizeImageGet($arItem['FIELDS']['PREVIEW_PICTURE'], array('width'=>120, 'height'=>120), BX_RESIZE_IMAGE_PROPORTIONAL, true)?>
					<?if($arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 'Y'):?>
						<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="side-block__img lazy rounded bg-img" data-src="<?=$arImage['src'];?>" style="background-image: url(<?=\Aspro\Functions\CAsproMax::showBlankImg($arImage['src']);?>)"></a>
					<?else:?>
						<div class="side-block__img lazy rounded bg-img" data-src="<?=$arImage['src'];?>" style="background-image: url(<?=\Aspro\Functions\CAsproMax::showBlankImg($arImage['src']);?>)"></div>
					<?endif;?>
				<?endif;?>
				<div class="side-block__text">
					<?if($arParams['TITLE_BLOCK']):?>
						<div class="side-block__text-title font_upper muted"><?=$arParams['TITLE_BLOCK'];?></div>
					<?endif;?>
					<?if($arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 'Y'):?>
						<a class="side-block__text-link dark_link" href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a>
					<?else:?>
						<span class="side-block__text-link darken"><?=$arItem["NAME"]?></span>
					<?endif;?>
					<?if($arItem['DISPLAY_PROPERTIES']['PHONE']['VALUE']):?>
						<div class="side-block__text-phone font_xs muted777"><?=$arItem['DISPLAY_PROPERTIES']['PHONE']['VALUE']?></div>
					<?endif;?>
				</div>
			</div>
			<div class="side-block__bottom side-block__bottom--last">
				<span class="btn btn-lg btn-transparent btn-wide font_upper animate-load colored_theme_hover_bg-el round-ignore" data-event="jqm" data-param-form_id="ASK" data-name="ask"><?=GetMessage('ASK')?></span>
			</div>
		</div>
	<?}?>
<?endif;?>
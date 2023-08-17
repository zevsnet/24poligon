<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<?if($arResult["ITEMS"]){?>
	<div class="side-news">
		<?if($arParams["TITLE_BLOCK"]):?>
			<div class="side-news__title side-news__title--margined">
				<?if($arParams["ALL_URL"]):?>
					<a href="<?=SITE_DIR.$arParams["ALL_URL"];?>" class="dark_link font_md"><?=$arParams["TITLE_BLOCK"];?></a>
				<?else:?>
					<span class="font_md"><?=$arParams["TITLE_BLOCK"];?></span>
				<?endif;?>
			</div>
		<?endif;?>
		<div class="side-news__list box-shadow">
			<?foreach($arResult["ITEMS"] as $arItem){
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				?>
				<div id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="side-news__item<?=($arItem['FIELDS']['PREVIEW_PICTURE'] ? ' side-news__item--has-picture clearfix' : '');?> rounded2 bordered">
					<?if($arItem['FIELDS']['PREVIEW_PICTURE']):?>
						<?$arImage = CFile::ResizeImageGet($arItem['FIELDS']['PREVIEW_PICTURE'], array('width'=>80, 'height'=>80), BX_RESIZE_IMAGE_PROPORTIONAL, true)?>
						<div class="side-news__img lazy rounded bg-img" data-src="<?=$arImage['src'];?>" style="background-image: url(<?=\Aspro\Functions\CAsproMax::showBlankImg($arImage['src']);?>)"></div>
					<?endif;?>
					<div class="side-news__item-info">
						<?if($arItem["DISPLAY_ACTIVE_FROM"] && $arParams["SHOW_DATE"]=="Y"):?>
							<div class="side-news__item-date muted font_sxs"><?=$arItem["DISPLAY_ACTIVE_FROM"]?></div>
						<?endif;?>
						<?if($arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 'Y'):?>
							<a class="side-news__item-link dark_link font_xs" href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a>
						<?else:?>
							<span class="side-news__item-link darken font_xs"><?=$arItem["NAME"]?></span>
						<?endif;?>
					</div>
				</div>
			<?}?>
		</div>
	</div>
<?}?>
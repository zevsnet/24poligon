<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<?use \Bitrix\Main\Localization\Loc;?>
<?if($arResult["SECTIONS"]){?>
	<?global $arTheme;

	$useHideTags = ((int)$arParams["SHOW_COUNT"] != 0);
	$countToShow = $useHideTags ? (int)$arParams["SHOW_COUNT"] : 0;
	?>

	<?$i = 0;?>	

	<div class="section-detail-list">
		<div class="">
			<?if($arResult["SECTIONS"]):?>
				<div class="section-detail-list__item item font_xs " >
					<div class="section-detail-list__info bordered  box-shadow-sm rounded3 all-sections section-detail-list__item--active colored_theme_bg" data-section_reset="true">
						<span><?=Loc::getMessage('ALL_SECTIONS_TITLE')?></span>
						<?if($arResult["ALL_ELEMENT_CNT"]):?>
							<span class="element-count-section"><?=$arResult["ALL_ELEMENT_CNT"];?></span>
						<?endif;?>
					</div>
				</div>
			<?endif;?>

			<?foreach( $arResult["SECTIONS"] as $arItems ){
				$this->AddEditAction($arItems['ID'], $arItems['EDIT_LINK'], CIBlock::GetArrayByID($arItems["IBLOCK_ID"], "SECTION_EDIT"));
				$this->AddDeleteAction($arItems['ID'], $arItems['DELETE_LINK'], CIBlock::GetArrayByID($arItems["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_SECTION_DELETE_CONFIRM')));

				++$i;
				$bHidden = ($useHideTags && ($i > $arParams["SHOW_COUNT"]));
			?>

				<?if($bHidden && !$bHiddenOK):?>
					<?$bHiddenOK = true;?>
					<div class="section-detail-list__item-more hidden">
				<?endif?>

				<div class="section-detail-list__item item font_xs " id="<?=$this->GetEditAreaId($arItems['ID']);?>">
					<div>
						<div class="section-detail-list__info bordered  box-shadow-sm rounded3" data-section_id="<?=$arItems["ID"]?>" data-section_reset="false">
							<span><?=$arItems["NAME"]?></span>
							<?if($arItems["ELEMENT_CNT"]):?>
								<span class="element-count-section"><?=$arItems["ELEMENT_CNT"];?></span>
							<?endif;?>
						</div>
					</div>
				</div>
				
			<?}?>
			<?if($bHidden):?>
				</div>
				<div class="section-detail-list__item font_xs">
					<span class=" section-detail-list__item--js-more colored_theme_text_with_hover">
						<span data-opened="N" data-text="<?=Loc::getMessage("HIDE");?>"><?=Loc::getMessage("SHOW_ALL");?></span><?=CMax::showIconSvg("wish ncolor", SITE_TEMPLATE_PATH."/images/svg/arrow_showmoretags.svg");?>
					</span>
				</div>
			<?endif?>
		</div>
	</div>
<?}?>
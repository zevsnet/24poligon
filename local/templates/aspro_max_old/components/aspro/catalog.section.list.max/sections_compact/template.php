<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<?use \Bitrix\Main\Localization\Loc;?>
<?if($arResult["SECTIONS"]){?>
	<?global $arTheme;
	$bSlick = ($arParams['NO_MARGIN'] == 'Y');
	$bIcons = ($arParams['SHOW_ICONS'] == 'Y');?>

	<?if($arResult['SECTION']['ELEMENT_CNT']):?>
		<?$this->SetViewTarget("more_text_title");?>
			<span class="element-count-wrapper"><span class="element-count muted font_xs rounded3"><?=$arResult['SECTION']['ELEMENT_CNT'];?></span></span>
		<?$this->EndViewTarget();?>
	<?endif;?>

	<div class="section-compact-list">
		<div class="row<?=($bSlick ? ' margin0' : '');?> flexbox">
			<?foreach( $arResult["SECTIONS"] as $arItems ){
				$this->AddEditAction($arItems['ID'], $arItems['EDIT_LINK'], CIBlock::GetArrayByID($arItems["IBLOCK_ID"], "SECTION_EDIT"));
				$this->AddDeleteAction($arItems['ID'], $arItems['DELETE_LINK'], CIBlock::GetArrayByID($arItems["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_SECTION_DELETE_CONFIRM')));
			?>
			<?if($arParams['USE_FILTER_SECTION'] == 'Y' && $arParams['BRAND_NAME'])
			{
				$arItems["SECTION_PAGE_URL"] .= "filter/brand-is-".$arParams['BRAND_CODE']."/apply/";
			}?>
				<div class="col-lg-3 col-md-4 col-xs-6 col-xxs-12">
					<div class="section-compact-list__item item bordered box-shadow flexbox flexbox--row" id="<?=$this->GetEditAreaId($arItems['ID']);?>">
						<?if($arParams["SHOW_SECTION_LIST_PICTURES"]=="Y"):?>
							<div class="section-compact-list__image<?=($bIcons && $arItems["UF_CATALOG_ICON"] ? ' with-icons colored_theme_svg' : '');?> flexbox flexbox--row">
								<?\Aspro\Functions\CAsproMaxItem::showSectionImg($arParams, $arItems, $bIcons);?>
							</div>
						<?endif;?>
						<div class="section-compact-list__info">
							<a href="<?=$arItems["SECTION_PAGE_URL"]?>" class="section-compact-list__link dark_link"><span><?=$arItems["NAME"]?></span></a>
							<?if($arItems["ELEMENT_CNT"]):?>
								<span class="element-count2 muted font_upper"><?=\Aspro\Functions\CAsproMax::declOfNum($arItems["ELEMENT_CNT"], array(Loc::getMessage('COUNT_ELEMENTS_TITLE'), Loc::getMessage('COUNT_ELEMENTS_TITLE_2'), Loc::getMessage('COUNT_ELEMENTS_TITLE_3')))?></span>
							<?endif;?>
						</div>
					</div>
				</div>
			<?}?>
		</div>
		</div>
<?}?>
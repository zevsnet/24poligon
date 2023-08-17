<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<?use \Bitrix\Main\Localization\Loc;?>
<?if($arResult["SECTIONS"]){?>
	<?global $arTheme;
	$iVisibleItemsMenu = CMax::GetFrontParametrValue('MAX_VISIBLE_ITEMS_MENU');
	$bSlide = false;
	$bSlick = ($arParams['NO_MARGIN'] == 'Y');
	$bSmallBlock = ($arParams['VIEW_TYPE'] == 'sm');
	$bSlideBlock = ($arParams['VIEW_TYPE'] == 'slide');
	$bBigBlock = ($arParams['VIEW_TYPE'] == 'lg');
	$bIcons = ($arParams['SHOW_ICONS'] == 'Y');?>
	<div class="catalog_section_list row items<?=($bSlick ? ' margin0' : '');?> flexbox type_<?=$arParams['TEMPLATE_TYPE']?>">
		<?foreach( $arResult["SECTIONS"] as $arItems ){
			$this->AddEditAction($arItems['ID'], $arItems['EDIT_LINK'], CIBlock::GetArrayByID($arItems["IBLOCK_ID"], "SECTION_EDIT"));
			$this->AddDeleteAction($arItems['ID'], $arItems['DELETE_LINK'], CIBlock::GetArrayByID($arItems["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_SECTION_DELETE_CONFIRM')));
		?>
			<div class="item_block <?=$arParams['VIEW_TYPE'];?><?=($bBigBlock ? ' col-lg-20' : '')?> <?if($bSlideBlock):?>col-xs-12<?else:?>col-md-<?=($arParams['VIEW_TYPE'] ? 4 : 6)?> col-xs-6<?endif;?>">
				<div class="section_item item bordered box-shadow" id="<?=$this->GetEditAreaId($arItems['ID']);?>">
					<table class="section_item_inner">
						<tr>
							<?if($arParams["SHOW_SECTION_LIST_PICTURES"]=="Y"):?>
								<td class="image<?=($bIcons && $arItems["UF_CATALOG_ICON"] ? ' with-icons colored_theme_svg' : '');?>">
									<?\Aspro\Functions\CAsproMaxItem::showSectionImg($arParams, $arItems, $bIcons);?>
								</td>
							<?endif;?>
							<td class="section_info toggle">
								<ul>
									<li class="name<?=($bBigBlock ? ' text-center' : '');?>">
										<a href="<?=$arItems["SECTION_PAGE_URL"]?>" class="dark_link"><span class="<?=($bSlideBlock ? 'font_mlg' : 'font_md');?>"><?=$arItems["NAME"]?></span></a>
										<?if($arItems["ELEMENT_CNT"]):?>
											<?if($bBigBlock || $bSlideBlock):?>
												<span class="element-count2 muted font_xs"><?=\Aspro\Functions\CAsproMax::declOfNum($arItems["ELEMENT_CNT"], array(Loc::getMessage('COUNT_ELEMENTS_TITLE'), Loc::getMessage('COUNT_ELEMENTS_TITLE_2'), Loc::getMessage('COUNT_ELEMENTS_TITLE_3')))?></span>
											<?else:?>
												<span class="element-count muted font_xxs rounded3"><?=$arItems["ELEMENT_CNT"];?></span>
											<?endif;?>
										<?endif;?>
									</li>
									<?if($arItems["SECTIONS"]):?>
										<?if($bSlideBlock):?>
											<?$bSlide = true;?>
											</ul><div class="slide-wrapper row"><ul class="subsections">
										<?endif;?>

										<?$iCountChilds = count($arItems["SECTIONS"]);
										$i = 0;?>
										<?foreach($arItems["SECTIONS"] as $key => $arItem):?>
											<li class="sect<?=(++$i > $iVisibleItemsMenu ? ' collapsed' : '');?><?=($bSlideBlock ? ' col-sm-6 font_sm' : ' font_xs');?>"><a href="<?=$arItem["SECTION_PAGE_URL"]?>" class="<?=($bSmallBlock ? 'muted777' : '')?>"><?=$arItem["NAME"]?><?=($arItem["ELEMENT_CNT"]?'&nbsp;<span class="'.($bSmallBlock ? 'dark-color' : '').'">'.$arItem["ELEMENT_CNT"].'</span>':'');?></a></li>
										<?endforeach;?>
										<?if($iCountChilds > $iVisibleItemsMenu):?>
											<li class="sect font_upper more_items<?=($bSlideBlock ? ' col-sm-6' : '');?>"><span class="<?=($bSmallBlock ? 'dark-color' : 'colored');?>">+&nbsp;&nbsp;<?=\Bitrix\Main\Localization\Loc::getMessage('S_MORE_ITEMS');?></span></li>
										<?endif;?>
									<?endif;?>
								</ul>
								<?if($arParams["SECTIONS_LIST_PREVIEW_DESCRIPTION"]!="N"):?>
									<?if($arParams["SECTIONS_LIST_PREVIEW_PROPERTY"]):
										$arSection = $section = CMaxCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "ID" => $arItems["ID"], "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, array("ID", $arParams["SECTIONS_LIST_PREVIEW_PROPERTY"]));?>
										<?if($arSection[$arParams["SECTIONS_LIST_PREVIEW_PROPERTY"]]):?>
											<?if($bSlideBlock && !$arItems["SECTIONS"]):?>
												<?$bSlide = true;?>
												<div class="slide-wrapper">
											<?endif;?>
											<div class="desc"><span class="desc_wrapp font_xs"><?=$arSection[$arParams["SECTIONS_LIST_PREVIEW_PROPERTY"]]?></span></div>
										<?elseif($arItems["DESCRIPTION"]):?>
											<?if($bSlideBlock && !$arItems["SECTIONS"]):?>
												<?$bSlide = true;?>
												<div class="slide-wrapper">
											<?endif;?>
											<div class="desc"><span class="desc_wrapp font_xs"><?=$arItems["DESCRIPTION"]?></span></div>
										<?endif;?>
									<?elseif($arItems["DESCRIPTION"]):?>
										<?if($bSlideBlock && !$arItems["SECTIONS"]):?>
											<?$bSlide = true;?>
											<div class="slide-wrapper">
										<?endif;?>
										<div class="desc"><span class="desc_wrapp font_xs"><?=$arItems["DESCRIPTION"]?></span></div>
									<?endif;?>
								<?endif;?>
								<?if($bSlideBlock && $bSlide):?>
									</div>
									<div class="arrow-block bordered rounded3 text-center arrow-block--absolute">
										<?=CMax::showIconSvg("arrow", SITE_TEMPLATE_PATH.'/images/svg/arrow_down_accordion.svg', '', '', true, false);?>
									</div>
								<?endif;?>
							</td>
						</tr>
					</table>
				</div>
			</div>
		<?}?>
	</div>
<?}?>
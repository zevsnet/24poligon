<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true );?>
<?if($arResult['SECTIONS']):?>
	<?
	global $arTheme;
	$iVisibleItemsMenu = CMax::GetFrontParametrValue('MAX_VISIBLE_ITEMS_MENU');

	$bSlick = ($arParams['NO_MARGIN'] == 'Y');
	$bFilled = ($arParams['FILLED'] == 'Y');
	$bIcons = ($arParams['SHOW_ICONS'] == 'Y');
	$bShowSubsections = ($arParams['SHOW_SUBSECTIONS'] == 'Y' && $arParams['SCROLL_SUBSECTIONS'] != 'Y');
	$nResizeWH = ($bShowSubsections ? 80 : 120);
	?>
	<div class="content_wrapper_block <?=$templateName;?>">
		<div class="maxwidth-theme">
			<div class="sections_wrapper <?=$arParams['VIEW_TYPE'];?><?=($bIcons ? ' icons' : '');?> <?=$arParams['MOBILE_TEMPLATE']?>">
				<?if($arParams["TITLE_BLOCK"] || $arParams["TITLE_BLOCK_ALL"]):?>
					<?if($arParams['INCLUDE_FILE']):?>
						<div class="with-text-block-wrapper">
							<div class="row">
								<div class="col-md-3">
									<?if($arParams['TITLE_BLOCK'] || $arParams['TITLE_BLOCK_ALL']):?>
										<h3><?=$arParams['TITLE_BLOCK'];?></h3>
										<?// intro text?>
										<?if($arParams['INCLUDE_FILE']):?>
											<div class="text_before_items font_xs">
												<?$APPLICATION->IncludeComponent(
													"bitrix:main.include",
													"",
													Array(
														"AREA_FILE_SHOW" => "file",
														"PATH" => SITE_DIR."include/mainpage/inc_files/".$arParams['INCLUDE_FILE'],
														"EDIT_TEMPLATE" => ""
													)
												);?>
											</div>
										<?endif;?>
										<a href="<?=SITE_DIR.$arParams['ALL_URL'];?>" class="btn btn-transparent-border-color btn-sm"><?=$arParams['TITLE_BLOCK_ALL'] ;?></a>
									<?endif;?>
								</div>
								<div class="col-md-9">
					<?else:?>
						<div class="top_block">
							<h3><?=$arParams["TITLE_BLOCK"];?></h3>
							<a href="<?=SITE_DIR.$arParams["ALL_URL"];?>" class="pull-right font_upper muted"><?=$arParams["TITLE_BLOCK_ALL"] ;?></a>
						</div>
					<?endif;?>
				<?endif;?>
				<div class="list items <?=($bShowSubsections ? 'catalog_section_list' : '');?>">
					<div class="row <?=($bSlick ? 'margin0' : '');?> flexbox">
						<?foreach($arResult['SECTIONS'] as $arSection):
							$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "ELEMENT_EDIT"));
							$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
							<?if($arParams['USE_FILTER_SECTION'] == 'Y' && $arParams['BRAND_NAME'])
							{
								$arSection["SECTION_PAGE_URL"] .= "filter/brand-is-".$arParams['BRAND_CODE']."/apply/";
							}?>
							<?if(!$bShowSubsections):?>
							<div class="<?=(!$arParams['INCLUDE_FILE'] && $arParams['SCROLL_SUBSECTIONS'] != 'Y' ? 'col-m-20 ' : '');?>col-md-3 col-sm-4 col-xs-6">
							<?else:?>
							<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
							<?endif;?>
								<div class="item bg-white box-shadow <?=($bFilled ? 'bg-fill-grey2' : 'bordered');?><?=($bShowSubsections ? ' section_item' : '');?>" id="<?=$this->GetEditAreaId($arSection['ID']);?>">
									<?if($bShowSubsections):?>
										<div class="section_item_inner">
									<?endif;?>
										<div class="img shine">
											<?if($bIcons && $arSection["UF_CATALOG_ICON"]):?>
												<?$img = CFile::ResizeImageGet($arSection["UF_CATALOG_ICON"], array( "width" => 40, "height" => 40 ), BX_RESIZE_IMAGE_EXACT, true );?>
												<a href="<?=$arSection["SECTION_PAGE_URL"]?>" class="thumb 22">
													<?if(strpos($img["src"], ".svg") !== false && \CMax::GetFrontParametrValue('COLORED_CATALOG_ICON') === 'Y'):?>
														<?=\Aspro\Functions\CAsproMax::showSVG([
															'PATH' => $img["src"]
														]);?>
													<?else:?>
														<img class="lazy" data-src="<?=$img["src"]?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($img["src"]);?>" alt="<?=($arSection["PICTURE"]["ALT"] ? $arSection["PICTURE"]["ALT"] : $arSection["NAME"])?>" title="<?=($arSection["PICTURE"]["TITLE"] ? $arSection["PICTURE"]["TITLE"] : $arSection["NAME"])?>" />
													<?endif;?>
												</a>
											<?else:?>
												<?if($arSection["PICTURE"]["SRC"]):?>
													<?$img = CFile::ResizeImageGet($arSection["PICTURE"]["ID"], array( "width" => $nResizeWH, "height" => $nResizeWH ), BX_RESIZE_IMAGE_EXACT, true );?>
													<a href="<?=$arSection["SECTION_PAGE_URL"]?>" class="thumb"><img class="lazy" data-src="<?=$img["src"]?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($img["src"]);?>" alt="<?=($arSection["PICTURE"]["ALT"] ? $arSection["PICTURE"]["ALT"] : $arSection["NAME"])?>" title="<?=($arSection["PICTURE"]["TITLE"] ? $arSection["PICTURE"]["TITLE"] : $arSection["NAME"])?>" /></a>
												<?elseif($arSection["~PICTURE"]):?>
													<?$img = CFile::ResizeImageGet($arSection["~PICTURE"], array( "width" => $nResizeWH, "height" => $nResizeWH ), BX_RESIZE_IMAGE_EXACT, true );?>
													<a href="<?=$arSection["SECTION_PAGE_URL"]?>" class="thumb"><img class="lazy" data-src="<?=$img["src"]?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($img["src"]);?>" alt="<?=($arSection["PICTURE"]["ALT"] ? $arSection["PICTURE"]["ALT"] : $arSection["NAME"])?>" title="<?=($arSection["PICTURE"]["TITLE"] ? $arSection["PICTURE"]["TITLE"] : $arSection["NAME"])?>" /></a>
												<?else:?>
													<a href="<?=$arSection["SECTION_PAGE_URL"]?>" class="thumb"><img class="lazy" data-src="<?=SITE_TEMPLATE_PATH?>/images/svg/noimage_product.svg" src="<?=\Aspro\Functions\CAsproMax::showBlankImg(SITE_TEMPLATE_PATH.'/images/svg/noimage_product.svg');?>" alt="<?=$arSection["NAME"]?>" title="<?=$arSection["NAME"]?>" height="90" /></a>
												<?endif;?>
											<?endif;?>
										</div>
									<?if(!$bShowSubsections):?>
										<div class="name font_sm">
											<a href="<?=$arSection['SECTION_PAGE_URL'];?>" class="dark_link"><?=$arSection['NAME'];?></a>
										</div>
									<?endif;?>
									<?if($arParams['SHOW_SUBSECTIONS'] == 'Y'):?>
										<?if($arParams['SCROLL_SUBSECTIONS'] == 'Y'):?>
											<div class="section_info onhover">
												<div class="section_info_inner scrollblock">
													<ul>
														<li class="name">
															<a href="<?=$arSection['SECTION_PAGE_URL']?>" class="dark_link"><span><?=$arSection['NAME']?></span></a>
														</li>
														<?if($arSection['ITEMS']):?>
															<?foreach($arSection['ITEMS'] as $key => $arItem):?>
																<?$sectionName = ($arParams['SECTION_TYPE_TEXT'] == 'SEO' && $arItem['IPROPERTY_VALUES']['SECTION_PAGE_TITLE'] ? $arItem['IPROPERTY_VALUES']['SECTION_PAGE_TITLE'] : $arItem['NAME'])?>
																<li class="sect font_xs"><a href="<?=$arItem['SECTION_PAGE_URL']?>" class="dark_link"><?=$sectionName?></a></li>
															<?endforeach;?>
														<?endif;?>
													</ul>
												</div>
											</div>
										<?else:?>
											<div class="section_info toggle">
												<ul>
													<li class="name">
														<a href="<?=$arSection['SECTION_PAGE_URL']?>" class="dark_link"><span class="font_md"><?=$arSection['NAME']?></span></a>
													</li>
													<?if($arSection['ITEMS']):
														$iCountChilds = count($arSection['ITEMS']);
														foreach($arSection['ITEMS'] as $key => $arItem):?>
															<?$sectionName = ($arParams['SECTION_TYPE_TEXT'] == 'SEO' && $arItem['IPROPERTY_VALUES']['SECTION_PAGE_TITLE'] ? $arItem['IPROPERTY_VALUES']['SECTION_PAGE_TITLE'] : $arItem['NAME'])?>
															<li class="sect <?=(++$key > $iVisibleItemsMenu ? 'collapsed' : '');?> font_xs"><a href="<?=$arItem['SECTION_PAGE_URL']?>" class=""><?=$sectionName?><? echo $arItem['ELEMENT_CNT']?'&nbsp;<span>'.$arItem['ELEMENT_CNT'].'</span>':'';?><?if($key < $iCountChilds):?><span class="separator">&mdash;</span><?endif;?></a></li>
														<?endforeach;?>
														<?if($iCountChilds > $iVisibleItemsMenu):?>
															<li class="sect font_xs more_items"><span class="colored with_dropdown"><?=\Bitrix\Main\Localization\Loc::getMessage('S_MORE_ITEMS');?></span></li>
														<?endif;?>
													<?endif;?>
												</ul>
											</div>
										<?endif;?>
									<?endif;?>
									<?if($bShowSubsections):?>
										</div>
									<?endif;?>
								</div>
							</div>
						<?endforeach;?>
					</div>
				</div>
				<?if($arParams['INCLUDE_FILE']):?>
					</div></div></div>
				<?endif;?>
			</div>
		</div>
	</div>
<?endif;?>
<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
global $arTheme, $arRegion, $dopClass;

$arRegions = CMaxRegionality::getRegions();
$bIncludeRegionsList = $arRegions || ($arTheme['USE_REGIONALITY']['VALUE'] !== 'Y' && $arTheme['USE_REGIONALITY']['DEPENDENT_PARAMS']['REGIONALITY_IPCITY_IN_HEADER']['VALUE'] !== 'N');

if($arRegion)
	$bPhone = ($arRegion['PHONES'] ? true : false);
else
	$bPhone = ((int)$arTheme['HEADER_PHONES'] ? true : false);

$logoClass = ($arTheme['COLORED_LOGO']['VALUE'] !== 'Y' ? '' : ' colored');
$dopClass = 'wides_menu';
?>
<div class="header-wrapper header-v20">
	<div class="logo_and_menu-row basket_top_line smalls">
		
			<div class="menu-row sliced">
				<div class="maxwidth-theme wides logo-row">
					
					<div class="header__top-inner pos-static">
					<div class="right-icons right_wrap wb header__top-item no-shrinked ">
							<div class="line-block line-block--40 line-block--40-1200">
								<?if($bIncludeRegionsList):?>
									<div class="line-block__item no-shrinked right_blocks">
										<div class="top-description no-title">
											<?\Aspro\Functions\CAsproMax::showRegionList();?>
										</div>
									</div>
								<?endif;?>
								<div class="line-block__item no-shrinked">
									<div class="wrap_icon inner-table-block">
										<div class="phone-block blocks">
											<?if($bPhone):?>
												<?CMax::ShowHeaderPhones('no-icons');?>
											<?endif?>
											<?$callbackExploded = explode(',', $arTheme['SHOW_CALLBACK']['VALUE']);
											if( in_array('HEADER', $callbackExploded) ):?>
												<div class="inline-block">
													<span class="callback-block animate-load font_upper_xs colored" data-event="jqm" data-param-form_id="CALLBACK" data-name="callback"><?=GetMessage("CALLBACK")?></span>
												</div>
											<?endif;?>
										</div>
									</div>
								</div>
								<?$arShowSites = \Aspro\Functions\CAsproMax::getShowSites();?>
								<?$countSites = count($arShowSites);?>
								<?if ($countSites > 1) :?>
									<div class="line-block__item no-shrinked">
										<div class="wrap_icon inner-table-block">
											<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
												array(
													"COMPONENT_TEMPLATE" => ".default",
													"PATH" => SITE_DIR."/include/header_include/site.selector.php",
													"SITE_LIST" => $arShowSites,
													"AREA_FILE_SHOW" => "file",
													"AREA_FILE_SUFFIX" => "",
													"AREA_FILE_RECURSIVE" => "Y",
													"EDIT_TEMPLATE" => "include_area.php",
												),
												false, array("HIDE_ICONS" => "Y")
											);?>
										</div>
									</div>
								<?endif;?>
								<div class="line-block__item no-shrinked">
									<div class="wrap_icon">
										<button class="top-btn inline-search-show">
											<?=CMax::showSpriteIconSvg(SITE_TEMPLATE_PATH."/images/svg/header_icons_srite.svg#search", "svg-inline-search", ['WIDTH' => 17,'HEIGHT' => 17]);?>
											<span class="title"><?=GetMessage("CT_BST_SEARCH_BUTTON")?></span>
										</button>
									</div>
								</div>
								<div class="line-block__item no-shrinked">
									<div class="wrap_icon inner-table-block1 person">
										<?=CMax::showCabinetLink(true, true, 'big');?>
									</div>
								</div>
								<?if (CMax::GetFrontParametrValue("ORDER_BASKET_VIEW") === "NORMAL"):?>
								
									<?=CMax::ShowBasketWithCompareLink('', 'big', '', 'wrap_icon wrap_basket baskets line-block__item');?>
								
								<?endif;?>
							</div>
						</div>
						<div class="header__top-item  minwidth0 flex1 order-1">
							<div class="line-block line-block--40">
								<div class="logo-block  floated line-block__item no-shrinked">
									<div class="logo<?=$logoClass?>">
										<?=CMax::ShowLogo();?>
									</div>
								</div>
								<div class="line-block__item flex1 	minwidth0">
									<div class="menu-only">
										<div class="menu-wrapper">
											<nav class="mega-menu sliced">
												<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
													array(
														"COMPONENT_TEMPLATE" => ".default",
														"PATH" => SITE_DIR."include/menu/menu.".($arTheme["HEADER_TYPE"]["LIST"][$arTheme["HEADER_TYPE"]["VALUE"]]["ADDITIONAL_OPTIONS"]["MENU_HEADER_TYPE"]["VALUE"] == "Y" ? "top_catalog_wide" : "top").".php",
														"AREA_FILE_SHOW" => "file",
														"AREA_FILE_SUFFIX" => "",
														"AREA_FILE_RECURSIVE" => "Y",
														"EDIT_TEMPLATE" => "include_area.php"
													),
													false, array("HIDE_ICONS" => "Y")
												);?>
											</nav>
										</div>
									</div>
								</div>
							</div>		
						</div>						
					</div>
					<div class="lines-row"></div>
				</div>
			</div>

	</div>
</div>
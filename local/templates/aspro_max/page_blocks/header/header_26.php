<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
global $arTheme, $arRegion, $bLongHeader2, $dopClass;

$arRegions = CMaxRegionality::getRegions();
$bIncludeRegionsList = $arRegions || ($arTheme['USE_REGIONALITY']['VALUE'] !== 'Y' && $arTheme['USE_REGIONALITY']['DEPENDENT_PARAMS']['REGIONALITY_IPCITY_IN_HEADER']['VALUE'] !== 'N');

if($arRegion)
	$bPhone = ($arRegion['PHONES'] ? true : false);
else
	$bPhone = ((int)$arTheme['HEADER_PHONES'] ? true : false);

$logoClass = ($arTheme['COLORED_LOGO']['VALUE'] !== 'Y' ? '' : ' colored');
$bLongHeader2 = true;
$dopClass = 'wides_menu smalls big_header';
?>
<div class="header-wrapper fix-logo header-v26">
	<div class="logo_and_menu-row icons_top showed">
		<div class="maxwidth-theme wides logo-row icons_bottom">
			<div class="header__sub-inner">
				<div class = "header__left-part ">
					<div class="header__main-item no-shrinked">
						<div class="logo<?=$logoClass?>">
							<?=CMax::ShowLogo();?>
						</div>
					</div>
				</div>	
				<div class="content-block no-area header__right-part minwidth0">
					
					<div class="subtop lines-block header__top-part">
						
						<div class="header__top-item">
							<?if($bIncludeRegionsList):?>
								<div class="inline-block">
									<div class="top-description no-title wicons">
										<?\Aspro\Functions\CAsproMax::showRegionList();?>
									</div>
								</div>
							<?endif;?>
						</div>	
						<div class="header__top-item flex1">
							<div class="search_wrap only_bg">
								<div class="search-block inner-table-block">
									<?$APPLICATION->IncludeComponent(
										"bitrix:main.include",
										"",
										Array(
											"AREA_FILE_SHOW" => "file",
											"PATH" => SITE_DIR."include/top_page/search.title.catalog.php",
											"EDIT_TEMPLATE" => "include_area.php",
											'SEARCH_ICON' => 'Y',
										),
										false, array("HIDE_ICONS" => "Y")
									);?>
								</div>
							</div>
						</div>
						<div class="header__top-item">
							<div class="wrap_icon inner-table-block">
								<div class="phone-block icons flexbox flexbox--row">
									<?if($bPhone):?>
										<?CMax::ShowHeaderPhones('');?>
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
					
					</div>
					<div class="subcontent">
						<div class="subbottom menu-row header__main-part">
							<div class="right-icons  wb top-block-item logo_and_menu-row header__main-item">
								<div class="line-block line-block--40 line-block--40-1200">
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
										<div class="wrap_icon inner-table-block1 person">
											<?=CMax::showCabinetLink(true, true, 'big');?>
										</div>
									</div>
									<?if (CMax::GetFrontParametrValue("ORDER_BASKET_VIEW") === "NORMAL"):?>
										<?=CMax::ShowBasketWithCompareLink('', 'big', '', 'wrap_icon wrap_basket baskets line-block__item');?>
									<?endif;?>
								</div>	
							</div>
							<div class="header__main-item minwidth0 flex1 order-1">
								<div class="menu">
									<div class="menu-only">
										<nav class="mega-menu sliced heightauto">
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
			</div>
			<div class="lines-row"></div>
		</div>
	</div>
</div>
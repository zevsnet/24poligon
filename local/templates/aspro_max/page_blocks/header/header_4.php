<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
global $arTheme, $arRegion, $bLongHeader, $bColoredHeader;

$arRegions = CMaxRegionality::getRegions();
$bIncludeRegionsList = $arRegions || ($arTheme['USE_REGIONALITY']['VALUE'] !== 'Y' && $arTheme['USE_REGIONALITY']['DEPENDENT_PARAMS']['REGIONALITY_IPCITY_IN_HEADER']['VALUE'] !== 'N');

if($arRegion)
	$bPhone = ($arRegion['PHONES'] ? true : false);
else
	$bPhone = ((int)$arTheme['HEADER_PHONES'] ? true : false);

$logoClass = ($arTheme['COLORED_LOGO']['VALUE'] !== 'Y' ? '' : ' colored');
$bLongHeader = true;
$bColoredHeader = true;
$basketViewNormal = CMax::GetFrontParametrValue("ORDER_BASKET_VIEW") === "NORMAL";
?>
<div class="header-wrapper header-v4">
	<div class="logo_and_menu-row icons_top with-search wide_search header__top-part">
			<div class="maxwidth-theme logo-row ">
				<div class="header__top-inner">
						<div class="logo-block  floated header__top-item">
								<div class="logo<?=$logoClass?>">
									<?=CMax::ShowLogo();?>
							</div>
						</div>
						<?if($bIncludeRegionsList):?>
							<div class="header__top-item">
								<div class="top-description no-title">
									<?\Aspro\Functions\CAsproMax::showRegionList();?>
								</div>
							</div>
						<?endif;?>
						<div class="header__top-item flex1">
							<div class="search_wrap ">
								<div class="search-block inner-table-block">
									<?$APPLICATION->IncludeComponent(
										"bitrix:main.include",
										"",
										Array(
											"AREA_FILE_SHOW" => "file",
											"PATH" => SITE_DIR."include/top_page/search.title.catalog.php",
											"EDIT_TEMPLATE" => "include_area.php",
											'SEARCH_ICON' => 'Y'
										),
										false, array("HIDE_ICONS" => "Y")
									);?>
								</div>
							</div>
						</div>
						<div class="header__top-item">
								<div class="wrap_icon inner-table-block">
									<div class="phone-block blocks fontUp">
										<?if($bPhone):?>
											<?CMax::ShowHeaderPhones('no-icons');?>
										<?endif?>
										<?$callbackExploded = explode(',', $arTheme['SHOW_CALLBACK']['VALUE']);
										if( in_array('HEADER', $callbackExploded) ):?>
											<div class="inline-block">
												<span class="callback-block animate-load colored" data-event="jqm" data-param-form_id="CALLBACK" data-name="callback"><?=GetMessage("CALLBACK")?></span>
											</div>
										<?endif;?>
									</div>
								</div>
							</div>	

						<div class="right-icons wb header__top-item ">
							<div class="line-block line-block--40">
								<?$arShowSites = \Aspro\Functions\CAsproMax::getShowSites();?>
										<?$countSites = count($arShowSites);?>
										<?if ($countSites > 1) :?>
											<div class="line-block__item ">
												<div class="wrap_icon inner-table-block with-title sites_bottom_menu">
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
								<div class="line-block__item">
									<div class="wrap_icon inner-table-block person with-title">
										<?=CMax::showCabinetLink(true, true, 'big');?>
									</div>
								</div>		
							</div>
						</div>
				</div>
			</div>
	</div>
	<div class="menu-row sliced middle-block bg<?=strtolower($arTheme["MENU_COLOR"]["VALUE"]);?>">
		<div class="maxwidth-theme">
			<div class="header__main-part menu-only">
				<?if ($basketViewNormal):?>
					<div class="header__main-item no-shrinked">
						<?=CMax::ShowBasketWithCompareLink('', '', false, 'wrap_icon inner-table-block');?>
					</div>
				<?endif;?>
				<div class="header__main-item minwidth0 flex1 order-1">
					<?/*<div class="menu-only">*/?>
						<div class="menu-inner">
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
					<?/*</div>*/?>
				</div>
			</div>
		</div>
	</div>
	<div class="line-row visible-xs"></div>
</div>
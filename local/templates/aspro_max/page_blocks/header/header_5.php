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
<div class="header-wrapper header-v5">
	<div class="logo_and_menu-row icons_top with-search  header__top-part">
			<div class="maxwidth-theme logo-row">
				<div class= "header__top-inner">
					<div class="header__top-item flex1">
						<div class="line-block line-block--2">
							<?if($bIncludeRegionsList):?>
								<div class="line-block__item ">
									<div class="top-description no-title">
										<?\Aspro\Functions\CAsproMax::showRegionList();?>
									</div>
								</div>
							<?endif;?>
							<div class="line-block__item header_search_wrapper">
								<div class="custom-search <?=($arRegions ? '' : 'nopadding');?>">
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
						</div>		
					</div>

					<div class="logo-block col-md-2 text-center nopadding header__top-item">
						<div class="line-block line-block--16">
							<div class="logo<?=$logoClass?> line-block__item no-shrinked">
								<?=CMax::ShowLogo();?>
							</div>
						</div>	
					</div>
					<div class="right_wrap   only-login header__top-item flex1">
						<div class="line-block line-block--40 line-block--40-1200 flexbox--justify-end  ">
							<div class="line-block__item ">
								<div class="wrap_icon inner-table-block">
									<div class="phone-block blocks icons fontUp">
										<?if($bPhone):?>
											<?CMax::ShowHeaderPhones('');?>
										<?endif?>
										<?$callbackExploded = explode(',', $arTheme['SHOW_CALLBACK']['VALUE']);
										if( in_array('HEADER', $callbackExploded) ):?>
											<div class="inline-block">
												<span class="callback-block animate-load twosmallfont colored" data-event="jqm" data-param-form_id="CALLBACK" data-name="callback"><?=GetMessage("CALLBACK")?></span>
											</div>
										<?endif;?>
									</div>
								</div>
							</div>
							<?$arShowSites = \Aspro\Functions\CAsproMax::getShowSites();?>
									<?$countSites = count($arShowSites);?>
									<?if ($countSites > 1) :?>
										<div class="line-block__item no-shrinked">
											<div class="wrap_icon inner-table-block sites_bottom_menu">
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
										<div class="wrap_icon inner-table-block person">
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
				<div class="minwidth0 flex1 order-1">
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
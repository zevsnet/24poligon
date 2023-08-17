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
?>
<div class="header-wrapper">
	<div class="logo_and_menu-row with-search header__top-part">
			<div class="maxwidth-theme logo-row short">
				<div class="header__top-inner">
							<div class="logo-block  floated  header__top-item">
								<div class="line-block line-block--16">
									<div class="logo<?=$logoClass?> line-block__item no-shrinked">
										<?=CMax::ShowLogo();?>
									</div>
								</div>	
							</div>
							<?if($bIncludeRegionsList):?>
								<div class="header__top-item">
									<div class="line-block line-block--8">
										<div class="line-block__item">
											<div class="top-description no-title">
												<?\Aspro\Functions\CAsproMax::showRegionList();?>
											</div>
										</div>	
									</div>	
								</div>
							<?endif;?>
							<div class="header__top-item flex1">
									<div class="search_wrap">
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
							<div class="header__top-item flex">
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
							<div class="right-icons wb header__top-item">
								<div class="line-block line-block--40 line-block--40-1200">
									<?$arShowSites = \Aspro\Functions\CAsproMax::getShowSites();?>
									<?$countSites = count($arShowSites);?>
									<?if ($countSites > 1) :?>
										<div class="line-block__item ">
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
									<div class="line-block__item">
										<div class="wrap_icon inner-table-block person">
											<?=CMax::showCabinetLink(true, true, 'big');?>
										</div>
									</div>
									<?if (CMax::GetFrontParametrValue("ORDER_BASKET_VIEW") === "NORMAL"):?>
										<?=CMax::ShowBasketWithCompareLink('', 'big', '', 'wrap_icon wrap_basket baskets line-block__item');?>
									<?endif;?>
								</div>	
							</div>
			</div>				
		</div>
</div>
	<div class="menu-row middle-block bg<?=strtolower($arTheme["MENU_COLOR"]["VALUE"]);?>">
		<div class="maxwidth-theme">
			<div class="row">
				<div class="col-md-12">
					<div class="menu-only">
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
	<div class="line-row visible-xs"></div>
</div>
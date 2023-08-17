<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
global $arTheme, $arRegion, $bLongHeader2, $dopClass, $dopBodyClass, $bHeaderStickyMenu;

$arRegions = CMaxRegionality::getRegions();
$bIncludeRegionsList = $arRegions || ($arTheme['USE_REGIONALITY']['VALUE'] !== 'Y' && $arTheme['USE_REGIONALITY']['DEPENDENT_PARAMS']['REGIONALITY_IPCITY_IN_HEADER']['VALUE'] !== 'N');

if($arRegion)
	$bPhone = ($arRegion['PHONES'] ? true : false);
else
	$bPhone = ((int)$arTheme['HEADER_PHONES'] ? true : false);

$logoClass = ($arTheme['COLORED_LOGO']['VALUE'] !== 'Y' ? '' : ' colored');
$bLongHeader2 = true;
$dopClass = 'wides_menu smalls big_header sticky_menu';
$dopBodyClass = ' sticky_menu';
$bHeaderStickyMenu = true;
?>
<div class="header-wrapper fix-logo1 header-v28">
	<div class="logo_and_menu-row showed icons_top">
		<div class="maxwidth-theme wides logo-row ">
			<div class="content-block no-area">
				<div class = "subcontent">
					<div class="top-block lines-block items-wrapper header__top-inner">
						<?if($bIncludeRegionsList):?>
							<div class="header__top-item">
								<div class="top-description no-title">
									<?\Aspro\Functions\CAsproMax::showRegionList();?>
								</div>
							</div>
						<?endif;?>
						<div class="header__top-item dotted-flex-1 hide-dotted">
							<div class="menus">
								<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
									array(
										"COMPONENT_TEMPLATE" => ".default",
										"PATH" => SITE_DIR."include/menu/menu.topest2.php",
										"AREA_FILE_SHOW" => "file",
										"AREA_FILE_SUFFIX" => "",
										"AREA_FILE_RECURSIVE" => "Y",
										"EDIT_TEMPLATE" => "include_area.php"
									),
									false
								);?>
							</div>
						</div>
						<div class="right-icons header__top-item  showed icons_top">
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
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="subbottom header__top-part">
				<div class="header__top-inner">
					<div class="header__top-item flex1">
						<div class="search_wraps content-block">
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
					<div class="header__top-item phone-wrapper">
						<div class="wrap_icon inner-table-block">
							<div class="phone-block icons blocks">
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
					<?if (CMax::GetFrontParametrValue("ORDER_BASKET_VIEW") === "NORMAL"):?>
						<div class="right-icons header__top-item logo_and_menu-row icons_bottom">
							<div class="line-block__item line-block line-block--40 line-block--40-1200">
								<?=CMax::ShowBasketWithCompareLink('', 'big', '', 'wrap_icon wrap_basket baskets');?>
							</div>
						</div> 
					<?endif;?>	
				</div>	
			</div>
			<div class="lines-row"></div>
		</div>
	</div>
</div>
<div class="sidebar_menu">
	<div class="sidebar_menu_inner">
		<div class="logo-row">
			<div class="logo-block">
				<div class="logo<?=$logoClass?>">
					<?=CMax::ShowLogo();?>
				</div>
			</div>
		</div>
		<div class="menu-wrapper">
			<?$APPLICATION->IncludeComponent("bitrix:menu", "left_catalog", array(
				"ROOT_MENU_TYPE" => "left",
				"MENU_CACHE_TYPE" => "A",
				"MENU_CACHE_TIME" => "3600000",
				"MENU_CACHE_USE_GROUPS" => "N",
				"CACHE_SELECTED_ITEMS" => "N",
				"MENU_CACHE_GET_VARS" => "",
				"MAX_LEVEL" => $arTheme["MAX_DEPTH_MENU"]["VALUE"],
				"CHILD_MENU_TYPE" => "left",
				"USE_EXT" => "Y",
				"DELAY" => "N",
				"ALLOW_MULTI_SELECT" => "N" ),
				false, array( "ACTIVE_COMPONENT" => "Y" )
			);?>
		</div>
	</div>
</div>
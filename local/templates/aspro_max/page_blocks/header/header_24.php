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
$dopClass = 'wides_menu smalls1 additionally_top';
?>
<div class="top-block top-block-v1 header-v16 header-wrapper">
	<div class="maxwidth-theme wides logo_and_menu-row logo_top_white icons_top">		
		<div class="wrapp_block logo-row">
			<div class="items-wrapper header__top-inner">
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

				<div class="header__top-item phone-wrapper">
					<div class="phone-block icons">
						<?if($bPhone):?>
							<div class="inline-block">
								<?CMax::ShowHeaderPhones('');?>
							</div>
						<?endif?>
						<?$callbackExploded = explode(',', $arTheme['SHOW_CALLBACK']['VALUE']);
						if( in_array('HEADER', $callbackExploded) ):?>
							<div class="inline-block">
								<span class="callback-block animate-load font_upper_xs colored" data-event="jqm" data-param-form_id="CALLBACK" data-name="callback"><?=GetMessage("CALLBACK")?></span>
							</div>
						<?endif;?>
					</div>
				</div>
				<div class="right-icons header__top-item logo_and_menu-row showed logo_top_white icons_top">
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
											"AREA_FILE_SHOW" => "file",
											"AREA_FILE_SUFFIX" => "",
											"AREA_FILE_RECURSIVE" => "Y",
											"SITE_LIST" => $arShowSites,
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
</div>
<div class="header-wrapper header-v17">
	<div class="logo_and_menu-row icons_bottom longs header__top-part">
		<div class="maxwidth-theme wides logo-row  icons_bottom">
			<div class="header__top-inner no-shrinked">
				<div class="header__top-item">
					<div class ="line-block line-block--48">
						<div class="logo-block floated line-block__item no-shrinked">
								<div class="logo<?=$logoClass?>">
									<?=CMax::ShowLogo();?>
								</div>
						</div>
						<div class="line-block__item">
							<div class="menu-row">
								<div class="menu-only">
									<nav class="mega-menu short with_icons">
										<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
											array(
												"COMPONENT_TEMPLATE" => ".default",
												"PATH" => SITE_DIR."include/menu/menu.only_catalog.php",
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
									'SEARCH_ICON' => 'Y',
								),
								false, array("HIDE_ICONS" => "Y")
							);?>
						</div>
					</div>
				</div>
				<?if (CMax::GetFrontParametrValue("ORDER_BASKET_VIEW") === "NORMAL"):?>
					<div class="right-icons1 long_search wb header__top-item">
						<div class="longest line-block line-block--40 line-block--40-1200">
								<?=CMax::ShowBasketWithCompareLink('', 'big', '', 'wrap_icon wrap_basket baskets line-block__item');?>
						</div>
					</div>
				<?endif;?>	
		</div>
		<div class="lines-row"></div>
	</div>
</div>
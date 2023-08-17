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
$dopClass = 'wides_menu smalls additionally_top';
?>
<div class="top-block top-block-v1 top-block-small header-v22 header-wrapper ">
	<div class="maxwidth-theme wides logo_and_menu-row icons_top logo_top_white">		
		<div class="wrapp_block logo-row">
			
			<div class="items-wrapper header__top-inner">
				<?if($bIncludeRegionsList):?>
					<div class="header__top-item">
						<div class="top-description1 no-title">
							<?\Aspro\Functions\CAsproMax::showRegionList();?>
						</div>
					</div>
				<?endif;?>
				<div class="header__top-item">
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
				
				<div class="soc header__top-item">
					<?$APPLICATION->IncludeComponent(
						"aspro:social.info.max",
						"top",
						array(
							"CACHE_TYPE" => "A",
							"CACHE_TIME" => "3600000",
							"CACHE_GROUPS" => "N",
							"COMPONENT_TEMPLATE" => "top"
						),
						false
					);?>
				</div>
				<div class="right-icons header__top-item logo_and_menu-row to-mr showed icons_top logo_top_white">
					<div class="line-block line-block--40 line-block--40-1200 flexbox--justify-end  ">
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
							<div class="wrap_icon">
								<button class="top-btn inline-search-show">
									<?=CMax::showSpriteIconSvg(SITE_TEMPLATE_PATH."/images/svg/header_icons_srite.svg#search", "svg-inline-search", ['WIDTH' => 17,'HEIGHT' => 17]);?>
									<span class="title"><?=GetMessage("CT_BST_SEARCH_BUTTON")?></span>
								</button>
							</div>
						</div>
						<div class="line-block__item">
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
<div class="header-wrapper header-v22">
	<div class="logo_and_menu-row smalls">
		<div class="logo-row paddings">
			<div class="maxwidth-theme wides">
				<div class="header__main-part pos-static">
					<div class="header__main-item logo-block order-1">
						<div class="logo<?=$logoClass?>">
							<?=CMax::ShowLogo();?>
						</div>
					</div>
					<?if (CMax::GetFrontParametrValue("ORDER_BASKET_VIEW") === "NORMAL"):?>
						<div class="header__main-item no-shrinked">
							<?=CMax::ShowBasketWithCompareLink('', 'big', '', 'wrap_icon wrap_basket baskets');?>
						</div>
					<?endif;?>
					<div class="header__main-item float_wrapper fix-block order-1">
						<div class="hidden-sm hidden-xs">
							<div class="top-description addr">
								<?$APPLICATION->IncludeFile(SITE_DIR."include/top_page/slogan.php", array(), array(
										"MODE" => "html",
										"NAME" => "Text in title",
										"TEMPLATE" => "include_area.php",
									)
								);?>
							</div>
						</div>
					</div>
					<div class="header__main-item minwidth0 flex1 order-1">
						<div class="menu-row">
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
				<div class="lines-row"></div>
			</div>
		</div><?// class=logo-row?>
	</div>
</div>
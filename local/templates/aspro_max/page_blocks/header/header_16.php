<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
global $arTheme, $arRegion, $bLongHeader, $dopClass;

$arRegions = CMaxRegionality::getRegions();
$bIncludeRegionsList = $arRegions || ($arTheme['USE_REGIONALITY']['VALUE'] !== 'Y' && $arTheme['USE_REGIONALITY']['DEPENDENT_PARAMS']['REGIONALITY_IPCITY_IN_HEADER']['VALUE'] !== 'N');

if($arRegion)
	$bPhone = ($arRegion['PHONES'] ? true : false);
else
	$bPhone = ((int)$arTheme['HEADER_PHONES'] ? true : false);

$logoClass = ($arTheme['COLORED_LOGO']['VALUE'] !== 'Y' ? '' : ' colored');
$bLongHeader = true;
$dopClass .= ' high_one_row_header';
?>
<div class="top-block top-block-v1 header-v16 header-wrapper">
	<div class="maxwidth-theme  logo_and_menu-row logo_top_white icons_top">		
		<div class="wrapp_block logo-row">
			<div class="items-wrapper header__top-inner">
				<?if($bIncludeRegionsList):?>
					<div class="header__top-item">
						<div class="top-description no-title">
							<?\Aspro\Functions\CAsproMax::showRegionList();?>
						</div>
					</div>
				<?endif;?>
				<div class="header__top-item">
					<div class="phone-block">
						<?if($bPhone):?>
							<div class="inline-block">
								<?CMax::ShowHeaderPhones('no-icons');?>
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
				
				<div class="header__top-item addr-block">
					<div><?CMax::showAddress('address tables inline-block no-icons');?></div>
				</div>

				<div class="right-icons logo_and_menu-row logo_top_white icons_top showed header__top-item">
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
							<div class="wrap_icon top-search">
								<button class="top-btn inline-search-show">
									<?=CMax::showIconSvg("search", SITE_TEMPLATE_PATH."/images/svg/Search.svg");?>
									<span class="title"><?=GetMessage("CT_BST_SEARCH_BUTTON")?></span>
								</button>
							</div>
						</div>
						<div class="line-block__item">
							<div class="wrap_icon inner-table-block1 person">
								<?=CMax::showCabinetLink(true, true, 'big1');?>
							</div>
						</div>
					</div>	
				</div>
			</div>
		</div>
	</div>
</div>
<div class="header-wrapper header-v16">
	<div class="logo_and_menu-row longs">
		<div class="logo-row paddings">
			<div class="maxwidth-theme">
				<div class="header__main-part">
					<div class="logo-block pull-left floated header__main-item order-1">
						<div class="logo<?=$logoClass?>">
							<?=CMax::ShowLogo();?>
						</div>
					</div>
					<?if (CMax::GetFrontParametrValue("ORDER_BASKET_VIEW") === "NORMAL"):?>
						<div class="header__main-item no-shrinked">
							<?=CMax::ShowBasketWithCompareLink('', 'big', '', 'wrap_icon wrap_basket baskets');?>
						</div>
					<?endif;?>
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
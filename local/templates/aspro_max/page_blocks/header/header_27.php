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
<div class="header-wrapper fix-logo header-v27">
	<div class="logo_and_menu-row showed icons_top">
		<div class="maxwidth-theme wides logo-row icons_bottom">
				<div class="header__sub-inner">
					<div class = "header__left-part ">
						<div class="logo<?=$logoClass?>">
							<?=CMax::ShowLogo();?>
						</div>
					</div>	
					<div class="content-block no-area header__right-part minwidth0">
					
						<div class="subtop lines-block header__top-part items-wrapper top-block top-block-v1">
							<?if($bIncludeRegionsList):?>
								<div class="header__top-item">
									<div class="top-description no-title wicons">
										<?\Aspro\Functions\CAsproMax::showRegionList();?>
									</div>
								</div>
							<?endif;?>
							<div class="header__top-item phone-wrapper">
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

							<div class="right-icons  header__top-item logo_and_menu-row showed icons_top">
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
										<div class="wrap_icon">
											<button class="top-btn inline-search-show">
												<?=CMax::showSpriteIconSvg(SITE_TEMPLATE_PATH."/images/svg/header_icons_srite.svg#search", "svg-inline-search search", ['WIDTH' => 17,'HEIGHT' => 17]);?>
												<span class="title"><?=GetMessage("CT_BST_SEARCH_BUTTON")?></span>
											</button>
										</div>
									</div>
									<div class="line-block__item no-shrinked">
										<div class="wrap_icon inner-table-block1 person">
											<?=CMax::showCabinetLink(true, true, 'big');?>
										</div>
									</div>
								</div>	
							</div>
						</div>
						<div class="subcontent">
							<div class="subbottom menu-row header__main-part">
								<?if (CMax::GetFrontParametrValue("ORDER_BASKET_VIEW") === "NORMAL"):?>
								<div class="right-icons  wb top-block-item logo_and_menu-row header__main-item">
									<div class="line-block__item line-block line-block--40 line-block--40-1200">
										<?=CMax::ShowBasketWithCompareLink('', 'big', '', 'wrap_icon wrap_basket baskets');?>
									</div>
								</div>
								<?endif;?>
								<div class="header__main-item minwidth0 flex1 order-1">
									<div class="menu">
										<div class="menu-only">
											<nav class="mega-menu sliced heightauto">
												<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
													array(
														"COMPONENT_TEMPLATE" => ".default",
														"PATH" => SITE_DIR."include/menu/menu.top_catalog_sections.php",
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
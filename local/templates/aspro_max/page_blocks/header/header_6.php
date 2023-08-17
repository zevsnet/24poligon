<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
global $arTheme, $arRegion, $bLongHeader2, $bColoredHeader;

$arRegions = CMaxRegionality::getRegions();
$bIncludeRegionsList = $arRegions || ($arTheme['USE_REGIONALITY']['VALUE'] !== 'Y' && $arTheme['USE_REGIONALITY']['DEPENDENT_PARAMS']['REGIONALITY_IPCITY_IN_HEADER']['VALUE'] !== 'N');

if($arRegion)
	$bPhone = ($arRegion['PHONES'] ? true : false);
else
	$bPhone = ((int)$arTheme['HEADER_PHONES'] ? true : false);

$logoClass = ($arTheme['COLORED_LOGO']['VALUE'] !== 'Y' ? '' : ' colored');
$bLongHeader2 = true;
$bColoredHeader = true;
$basketViewNormal = CMax::GetFrontParametrValue("ORDER_BASKET_VIEW") === "NORMAL";
?>
<div class="header-wrapper fix-logo header-v6">
	<div class="logo_and_menu-row logo_and_menu-row--nested-menu icons_top">
			<div class="maxwidth-theme logo-row">
				<div class ="header__sub-inner">
						<div class = "header__left-part ">
							<div class="logo-block1 header__main-item">
								<div class="line-block line-block--16">
									<div class="logo<?=$logoClass?> line-block__item no-shrinked">
										<?=CMax::ShowLogo();?>
									</div>
								</div>	
							</div>
						</div>	
						<div class="content-block header__right-part">
							<div class="subtop lines-block header__top-part  ">
									<div class="header__top-item">
										<div class="line-block line-block--8">
											<?if($bIncludeRegionsList):?>
												<div class="line-block__item">
													<div class="top-description no-title wicons">
														<?\Aspro\Functions\CAsproMax::showRegionList();?>
													</div>
												</div>
											<?endif;?>
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
														<span class="callback-block animate-load twosmallfont colored" data-event="jqm" data-param-form_id="CALLBACK" data-name="callback"><?=GetMessage("CALLBACK")?></span>
													</div>
												<?endif;?>
											</div>
										</div>
									</div>
									<div class="header__top-item">
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
													<div class="inner-table-block">
														<?CMax::showAddress('address inline-block tables');?>
													</div>
											</div>
										</div>	
									</div>
								
							</div>
							<div class="subbottom header__main-part">
								<div class="header__main-item flex1">	
											<div class="menu">
												<div class="menu-only">
													<nav class="mega-menu sliced">
														<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
															array(
																"COMPONENT_TEMPLATE" => ".default",
																"PATH" => SITE_DIR."include/menu/menu.subtop_content.php",
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
								
									<div class="header__main-item">
										<div class="auth">
											<div class="wrap_icon inner-table-block person  with-title">
												<?=CMax::showCabinetLink(true, true, 'big');?>
											</div>
										</div>
									</div>	
								
							</div>	
						</div>
				</div>
			</div>	
			
	</div>
	<div class="menu-row middle-block bg<?=strtolower($arTheme["MENU_COLOR"]["VALUE"]);?>">
		<div class="maxwidth-theme">
			<div class="header__main-part menu-only">
				<div class="<?=$basketViewNormal ? "header__top-item" : "";?> menu-only-wr margin0">
					<nav class="mega-menu">
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
				<div class="header__top-item search-block">
					<div class="inner-table-block">
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
				<?if ($basketViewNormal):?>
					<div class="header__main-item no-shrinked">
						<?=CMax::ShowBasketWithCompareLink('', '', false, 'wrap_icon inner-table-block');?>
					</div>
				<?endif;?>
			</div>
		</div>
	</div>
	<div class="line-row visible-xs"></div>
</div>
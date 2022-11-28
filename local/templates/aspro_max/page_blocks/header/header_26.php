<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
global $arTheme, $arRegion, $bLongHeader2, $dopClass;
$arRegions = CMaxRegionality::getRegions();
if($arRegion)
	$bPhone = ($arRegion['PHONES'] ? true : false);
else
	$bPhone = ((int)$arTheme['HEADER_PHONES'] ? true : false);
$logoClass = ($arTheme['COLORED_LOGO']['VALUE'] !== 'Y' ? '' : ' colored');
$bLongHeader2 = true;
$dopClass = 'wides_menu smalls big_header';
?>
<div class="header-wrapper fix-logo header-v26">
	<div class="logo_and_menu-row showed">
		<div class="logo-row">
			<div class="maxwidth-theme wides">
				<div class="row pos-static">
					<div class="col-md-12 pos-static">
						<div class="logo-block">
							<div class="logo<?=$logoClass?>">
								<?=CMax::ShowLogo();?>
							</div>
						</div>
						<div class="content-block no-area">
							<div class="subcontent">
								<div class="subtop lines-block">
									<div class="row">
										<div class="col-md-12">
											<?if($arRegions):?>
												<div class="inline-block pull-left">
													<div class="top-description no-title wicons">
														<?\Aspro\Functions\CAsproMax::showRegionList();?>
													</div>
												</div>
											<?endif;?>
											<div class="pull-right">
												<div class="wrap_icon inner-table-block">
													<div class="phone-block icons">
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
											<div class="search_wrap pull-left only_bg">
												<div class="search-block inner-table-block">
													<?$APPLICATION->IncludeComponent(
														"bitrix:main.include",
														"",
														Array(
															"AREA_FILE_SHOW" => "file",
															"PATH" => SITE_DIR."include/top_page/search.title.catalog.php",
															"EDIT_TEMPLATE" => "include_area.php",
															'SEARCH_ICON' => 'Y',
														)
													);?>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="subbottom menu-row">
									<div class="right-icons pull-right wb top-block-item logo_and_menu-row">
										<div class="pull-right">
											<?=CMax::ShowBasketWithCompareLink('', 'big', '', 'wrap_icon wrap_basket baskets');?>
										</div>
										<div class="pull-right">
											<div class="wrap_icon inner-table-block1 person">
												<?=CMax::showCabinetLink(true, true, 'big');?>
											</div>
										</div>
									</div>
									<div class="menu">
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
					</div>
				</div>
				<div class="lines-row"></div>
			</div>
		</div><?// class=logo-row?>
	</div>
</div>
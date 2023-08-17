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
<div class="header-wrapper fix-logo header-v25">
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
						<div class="content-block <?=($APPLICATION->GetShowIncludeAreas() || CMax::checkContentFile(SITE_DIR.'include/top_page/slogan.php') ? "" : "no-area")?>">
							<div class="float_wrapper fix-block abs-block">
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
							<div class="subcontent">
								<div class="subtop lines-block">
									<div class="row">
										<div class="col-md-5">
											<?if($arRegions):?>
												<div class="inline-block pull-left">
													<div class="top-description no-title">
														<?\Aspro\Functions\CAsproMax::showRegionList();?>
													</div>
												</div>
											<?endif;?>
											<div class="pull-left">
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
										</div>
										<div class="col-md-7 pull-right">
											<div class="pull-left">
												<div class="inner-table-block">
													<?CMax::showAddress('address inline-block tables no-icons');?>
												</div>
											</div>
											<div class="right-icons pull-right top-block-item logo_and_menu-row showed">
												<div class="pull-right">
													<div class="wrap_icon inner-table-block1 person">
														<?=CMax::showCabinetLink(true, true, 'big');?>
													</div>
												</div>

												<div class="pull-right">
													<div class="wrap_icon">
														<button class="top-btn inline-search-show">
															<?=CMax::showIconSvg("search", SITE_TEMPLATE_PATH."/images/svg/Search.svg");?>
															<span class="title"><?=GetMessage("CT_BST_SEARCH_BUTTON")?></span>
														</button>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="subbottom menu-row">
									<div class="right-icons pull-right wb top-block-item logo_and_menu-row pos-static">
										<div class="pull-right">
											<?=CMax::ShowBasketWithCompareLink('', 'big', '', 'wrap_icon wrap_basket baskets');?>
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
<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
global $arTheme, $arRegion, $bLongHeader2, $bColoredHeader;
$arRegions = CMaxRegionality::getRegions();
if($arRegion)
	$bPhone = ($arRegion['PHONES'] ? true : false);
else
	$bPhone = ((int)$arTheme['HEADER_PHONES'] ? true : false);
$logoClass = ($arTheme['COLORED_LOGO']['VALUE'] !== 'Y' ? '' : ' colored');
$bLongHeader2 = true;
$bColoredHeader = true;
?>
<div class="header-wrapper fix-logo header-v6">
	<div class="logo_and_menu-row">
		<div class="logo-row">
			<div class="maxwidth-theme">
				<div class="row">
					<div class="col-md-12">
						<div class="logo-block">
							<div class="logo<?=$logoClass?>">
								<?=CMax::ShowLogo();?>
							</div>
						</div>
						<div class="content-block">
							<div class="subtop lines-block">
								<div class="row">
									<div class="col-md-3 cols3">
										<?if($arRegions):?>
											<div class="inline-block pull-left">
												<div class="top-description no-title wicons">
													<?\Aspro\Functions\CAsproMax::showRegionList();?>
												</div>
											</div>
										<?endif;?>
									</div>
									<div class="pull-left">
										<div class="wrap_icon inner-table-block">
											<div class="phone-block icons">
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
									<div class="col-md-4 pull-right">
										<div class="pull-right">
											<div class="inner-table-block">
												<?CMax::showAddress('address inline-block tables');?>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="subbottom">
								<div class="auth">
									<div class="wrap_icon inner-table-block person  with-title">
										<?=CMax::showCabinetLink(true, true, 'big');?>
									</div>
								</div>
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
						</div>
					</div>
				</div>
			</div>
		</div><?// class=logo-row?>
	</div>
	<div class="menu-row middle-block bg<?=strtolower($arTheme["MENU_COLOR"]["VALUE"]);?>">
		<div class="maxwidth-theme">
			<div class="row">
				<div class="col-md-12 menu-only">
					<div class="right-icons pull-right">
						<div class="pull-right">
							<?=CMax::ShowBasketWithCompareLink('', '', false, 'wrap_icon inner-table-block');?>
						</div>
					</div>
					<div class="menu-only-wr pull-left">
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
					<div class="search-block">
						<div class="inner-table-block">
							<?$APPLICATION->IncludeComponent(
								"bitrix:main.include",
								"",
								Array(
									"AREA_FILE_SHOW" => "file",
									"PATH" => SITE_DIR."include/top_page/search.title.catalog.php",
									"EDIT_TEMPLATE" => "include_area.php",
									'SEARCH_ICON' => 'Y'
								)
							);?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="line-row visible-xs"></div>
</div>
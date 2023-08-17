<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
global $arTheme, $arRegion, $bLongHeader, $bColoredHeader;
$arRegions = CMaxRegionality::getRegions();
if($arRegion)
	$bPhone = ($arRegion['PHONES'] ? true : false);
else
	$bPhone = ((int)$arTheme['HEADER_PHONES'] ? true : false);
$logoClass = ($arTheme['COLORED_LOGO']['VALUE'] !== 'Y' ? '' : ' colored');
$bLongHeader = true;
$bColoredHeader = true;
?>
<div class="header-wrapper header-v4">
	<div class="logo_and_menu-row with-search wide_search">
		<div class="logo-row paddings">
			<div class="maxwidth-theme">
				<div class="row">
					<div class="col-md-12">
						<div class="logo-block pull-left floated">
							<div class="logo <?=$logoClass?>">
								<?=\SB\Site\SB_CMax::ShowLogoMain();?>
							</div>
						</div>

						<?if($arRegions):?>
							<div class="inline-block pull-left">
								<div class="top-description no-title">
									<?\Aspro\Functions\CAsproMax::showRegionList();?>
								</div>
							</div>
						<?endif;?>

						<div class="search_wrap pull-left">
							<div class="search-block inner-table-block">
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

						<div class="right-icons pull-right">
							<div class="pull-right">
								<div class="wrap_icon inner-table-block person with-title">
									<?=CMax::showCabinetLink(true, true, 'big');?>
								</div>
							</div>
						</div>

						<div class="pull-right">
							<div class="wrap_icon inner-table-block">
								<div class="phone-block blocks icons">
									<?if($bPhone):?>
										<?CMax::ShowHeaderPhones('');?>
									<?endif?>
									<?$callbackExploded = explode(',', $arTheme['SHOW_CALLBACK']['VALUE']);
									if( in_array('HEADER', $callbackExploded) ):?>
										<div class="inline-block sb_btn sb_header_4">
											<span class="callback-block animate-load colored" data-event="jqm" data-param-form_id="CALLBACK" data-name="callback"><?=GetMessage("CALLBACK")?></span>
										</div>
									<?endif;?>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div><?// class=logo-row?>
	</div>
	<div class="menu-row sliced middle-block bg<?=strtolower($arTheme["MENU_COLOR"]["VALUE"]);?>">
		<div class="maxwidth-theme">
			<div class="row">
				<div class="col-md-12">
					<div class="right-icons pull-right">
						<div class="pull-right">
							<?=CMax::ShowBasketWithCompareLink('', '', false, 'wrap_icon inner-table-block');?>
						</div>
					</div>
					<div class="menu-only">
						<div class="menu-inner">
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
	<div class="line-row visible-xs"></div>
</div>
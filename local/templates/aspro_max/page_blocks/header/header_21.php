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
$dopClass = 'wides_menu smalls1';
?>
<div class="header-wrapper header-v21">
	<div class="logo_and_menu-row  basket_top_line smalls1 header__top-part">
		<div class="maxwidth-theme wides logo-row">
			<div class="header__top-inner">
				<div class="header__top-item flex1">
					<div class="line-block line-block--8">
						<div class="line-block__item no-shrinked">
							<div class="burger smalls"><?=CMax::showIconSvg("burger dark", SITE_TEMPLATE_PATH."/images/svg/burger.svg");?></div>
						</div>	
						<?if($bIncludeRegionsList):?>
							<div class="line-block__item ">
								<div class="top-description no-title">
									<?\Aspro\Functions\CAsproMax::showRegionList();?>
								</div>
							</div>
						<?endif;?>
						<div class="line-block__item ">
							<div class="wrap_icon inner-table-block">
								<div class="phone-block fontUp">
									<?if($bPhone):?>
										<?CMax::ShowHeaderPhones('no-icons');?>
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
				</div>
				<div class="logo-block  text-center nopadding header__top-item">
					<div class="logo<?=$logoClass?>">
						<?=CMax::ShowLogo();?>
					</div>
				</div>
				<div class="right_wrap header__top-item flex1">
					<div class="right-icons line-block line-block--40 line-block--40-1200 flexbox--justify-end">
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
							<div class="wrap_icon inner-table-block person">
								<?=CMax::showCabinetLink(true, true, 'big');?>
							</div>
						</div>
						<?if (CMax::GetFrontParametrValue("ORDER_BASKET_VIEW") === "NORMAL"):?>
							<?=CMax::ShowBasketWithCompareLink('', 'big', '', 'wrap_icon wrap_basket baskets line-block__item');?>
						<?endif;?>
					</div>
				</div>
			</div>
			<div class="lines-row"></div>
		</div>
	</div>
</div>
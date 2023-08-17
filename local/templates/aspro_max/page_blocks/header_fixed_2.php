<?
global $arTheme, $arRegion;
$logoClass = ($arTheme['COLORED_LOGO']['VALUE'] !== 'Y' ? '' : ' colored');
?>
<div class="maxwidth-theme">
	<div class="logo-row v2 margin0 menu-row">
		<div class="header__top-inner">
			<?if($arTheme['HEADER_TYPE']['VALUE'] != 29):?>
				<div class="header__top-item">
					<div class="burger inner-table-block"><?=CMax::showIconSvg("burger dark", SITE_TEMPLATE_PATH."/images/svg/burger.svg");?></div>
				</div>	
			<?endif;?>

			<?if($arTheme['HEADER_TYPE']['VALUE'] != 28):?>
				<div class="header__top-item no-shrinked">
					<div class="inner-table-block nopadding logo-block">
						<div class="logo<?=$logoClass?>">
							<?=CMax::ShowLogoFixed();?>
						</div>
					</div>
				</div>	
			<?endif;?>
			<div class="header__top-item minwidth0 flex1">
				<div class="menu-block">
					<div class="navs table-menu js-nav">
						<?if(CMax::nlo('menu-fixed')):?>
						<!-- noindex -->
						<nav class="mega-menu sliced">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
								array(
									"COMPONENT_TEMPLATE" => ".default",
									"PATH" => SITE_DIR."include/menu/menu.top.php",
									"SITE_LIST" => $arShowSites,
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "",
									"AREA_FILE_RECURSIVE" => "Y",
									"EDIT_TEMPLATE" => "include_area.php"
								),
								false, array("HIDE_ICONS" => "Y")
							);?>
						</nav>
						<!-- /noindex -->
						<?endif;?>
						<?CMax::nlo('menu-fixed');?>
					</div>
				</div>
			</div>	
			<div class="header__top-item">
				<div class="line-block line-block--40 line-block--40-1200 flexbox--justify-end">
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
										"EDIT_TEMPLATE" => "include_area.php",
									),
									false, array("HIDE_ICONS" => "Y")
								);?>
							</div>
						</div>
					<?endif;?>
					<div class="line-block__item  no-shrinked">
						<div class=" inner-table-block">
							<div class="wrap_icon">
								<button class="top-btn inline-search-show dark-color">
									<?=CMax::showSpriteIconSvg(SITE_TEMPLATE_PATH."/images/svg/header_icons_srite.svg#search", "svg-inline-search", ['WIDTH' => 17,'HEIGHT' => 17]);?>
								</button>
							</div>
						</div>
					</div>	
					<div class="line-block__item  no-shrinked">
						<div class=" inner-table-block nopadding small-block">
							<div class="wrap_icon wrap_cabinet">
								<?=CMax::showCabinetLink(true, false, 'big');?>
							</div>
						</div>
					</div>
					<?if (CMax::GetFrontParametrValue("ORDER_BASKET_VIEW") === "NORMAL"):?>
							<div class="line-block__item line-block line-block--40 line-block--40-1200">
							<?=CMax::ShowBasketWithCompareLink('inner-table-block', 'big');?>
						</div>
					<?endif;?>
				</div>	
			</div>	
		</div>
	</div>
</div>
<?=\Aspro\Functions\CAsproMax::showProgressBarBlock();?>
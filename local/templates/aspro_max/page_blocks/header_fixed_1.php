<?
global $arTheme, $arRegion;

$arRegions = CMaxRegionality::getRegions();
if($arRegion)
	$bPhone = ($arRegion['PHONES'] ? true : false);
else
	$bPhone = ((int)$arTheme['HEADER_PHONES'] ? true : false);

$logoClass = ($arTheme['COLORED_LOGO']['VALUE'] !== 'Y' ? '' : ' colored');

?>
<div class="maxwidth-theme">
	<div class="logo-row v1 row margin0 menu-row ">
		<div class="header__top-inner">
			<div class="left_wrap header__top-item  flex1">
				<div class="line-block line-block--8">
					<?if($arTheme['HEADER_TYPE']['VALUE'] != 29):?>
						<div class="line-block__item">
							<div class="burger inner-table-block"><?=CMax::showIconSvg("burger dark", SITE_TEMPLATE_PATH."/images/svg/burger.svg");?></div>
						</div>
					<?endif;?>

					<?if($arRegions):?>
						<div class="line-block__item">
							<div class="top-description no-title inner-table-block">
								<?\Aspro\Functions\CAsproMax::showRegionList();?>
							</div>
						</div>
					<?endif;?>
					<div class="line-block__item">
						<div class="wrap_icon inner-table-block phones_block ">
							<div class="phone-block">
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

			<?if($arTheme['HEADER_TYPE']['VALUE'] != 28):?>
				<div class="header__top-item no-shrinked">
					<div class="logo-block text-center nopadding">
						<div class="inner-table-block">
							<div class="logo<?=$logoClass?>">
								<?=CMax::ShowLogoFixed();?>
							</div>
						</div>
					</div>
				</div>
			<?endif;?>

			<div class="right_wrap header__top-item wb  flex1">
				<div class="line-block line-block--40 line-block--40-1200 flex1 flexbox--justify-end">
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
					<div class="line-block__item  no-shrinked">
						<div class=" inner-table-block">
							<div class="wrap_icon">
								<button class="top-btn inline-search-show dark-color">
									<?=CMax::showSpriteIconSvg(SITE_TEMPLATE_PATH."/images/svg/header_icons_srite.svg#search", "svg-inline-search", ['WIDTH' => 17,'HEIGHT' => 17]);?>
									<span class="title"><?=GetMessage("CT_BST_SEARCH_BUTTON")?></span>
								</button>
							</div>
						</div>
					</div>
					<div class="line-block__item  no-shrinked">
						<div class=" inner-table-block nopadding small-block">
							<div class="wrap_icon wrap_cabinet">
								<?=CMax::showCabinetLink(true, true, 'big');?>
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

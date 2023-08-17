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

	<div class="logo-row v3  margin0 menu-row">
		<div class="header__top-inner">
			<div class=" left_wrap header__top-item">
				<div class="line-block line-block--8">
					<?if($arTheme['HEADER_TYPE']['VALUE'] != 29):?>
						<div class="line-block__item">
							<div class="burger inner-table-block"><?=CMax::showIconSvg("burger dark", SITE_TEMPLATE_PATH."/images/svg/burger.svg");?></div>
						</div>
					<?endif;?>

					<?if($arTheme['HEADER_TYPE']['VALUE'] != 28):?>
						<div class="logo-block text-center nopadding line-block__item no-shrinked">
							<div class="inner-table-block">
								<div class="logo<?=$logoClass?>">
									<?=CMax::ShowLogoFixed();?>
								</div>
							</div>
							
						</div>
					<?endif;?>
				</div>	
			</div>

			<div class="header__top-item flex1">
				<div class="search_wrap only_bg">
					<div class="search-block inner-table-block">
						<?global $isFixedTopSearch; 
						$isFixedTopSearch = true;?>
						<?$APPLICATION->IncludeComponent(
							"bitrix:main.include",
							"",
							Array(
								"AREA_FILE_SHOW" => "file",
								"PATH" => SITE_DIR."include/top_page/search.title.catalog.php",
								"EDIT_TEMPLATE" => "include_area.php",
								'SEARCH_ICON' => 'Y',
							),
							false, array("HIDE_ICONS" => "Y")
						);?>
					</div>
				</div>
			</div>	

			


			<div class="right_wrap   wb header__top-item ">
				<div class="line-block line-block--40 line-block--40-1200 flex flexbox--justify-end ">
					<?if($arRegions):?>
						<div class="line-block__item">
							<div class="top-description no-title inner-table-block">
								<?\Aspro\Functions\CAsproMax::showRegionList();?>
							</div>
						</div>
					<?endif;?>


					<div class="line-block__item ">
						<div class="wrap_icon inner-table-block phones_block">
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

					
					<div class=" hidden-lg compact_search_block line-block__item  no-shrinked">
						<div class=" inner-table-block">
							<div class="wrap_icon">
								<button class="top-btn inline-search-show ">
									<?=CMax::showSpriteIconSvg(SITE_TEMPLATE_PATH."/images/svg/header_icons_srite.svg#search", "svg-inline-search", ['WIDTH' => 17,'HEIGHT' => 17]);?>
								</button>
							</div>
						</div>
					</div>
						
				</div>					
			</div>
		</div>
	</div>
</div>
<?=\Aspro\Functions\CAsproMax::showProgressBarBlock();?>
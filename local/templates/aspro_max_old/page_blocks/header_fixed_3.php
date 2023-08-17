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

	<div class="logo-row v3 row margin0 menu-row">
		<div class="pull-left left_wrap">
			<?if($arTheme['HEADER_TYPE']['VALUE'] != 29):?>
				<div class="pull-left">
					<div class="burger inner-table-block"><?=CMax::showIconSvg("burger dark", SITE_TEMPLATE_PATH."/images/svg/burger.svg");?></div>
				</div>
			<?endif;?>

			<?if($arTheme['HEADER_TYPE']['VALUE'] != 28 && $arTheme['HEADER_TYPE']['VALUE'] != 29):?>
				<div class="pull-left logo-block text-center nopadding">
					<div class="inner-table-block">
						<div class="logo<?=$logoClass?>">
							<?=CMax::ShowLogoFixed();?>
						</div>
					</div>
					
				</div>
			<?endif;?>

			
		</div>

		


		<div class="right_wrap pull-right  wb">

			<?if($arRegions):?>
				<div class="pull-left">
					<div class="top-description no-title inner-table-block">
						<?\Aspro\Functions\CAsproMax::showRegionList();?>
					</div>
				</div>
			<?endif;?>


			<div class="pull-left">
				<div class="wrap_icon inner-table-block phones_block">
					<div class="phone-block">
						<?if($bPhone):?>
							<?CMax::ShowHeaderPhones('no-icons');?>
						<?endif?>
						<?$callbackExploded = explode(',', $arTheme['SHOW_CALLBACK']['VALUE']);?>
					</div>
                    <?if( in_array('HEADER', $callbackExploded) ):?>
                    <div class="inline-block btn sb_btn">
                        <span class="callback-block animate-load font_upper_xs colored" data-event="jqm" data-param-form_id="CALLBACK" data-name="callback"><?=GetMessage("CALLBACK")?></span>
                    </div>
                    <?endif;?>
				</div>
			</div>

			<div class="pull-right">	
				<?=CMax::ShowBasketWithCompareLink('inner-table-block', 'big');?>
			</div>

			<div class="pull-right">
				<div class=" inner-table-block nopadding small-block">
					<div class="wrap_icon wrap_cabinet">
						<?=CMax::showCabinetLink(true, false, 'big');?>
					</div>
				</div>
			</div>

			<div class="pull-right hidden-lg compact_search_block">
				<div class=" inner-table-block">
					<div class="wrap_icon">
						<button class="top-btn inline-search-show ">
							<?=CMax::showIconSvg("search", SITE_TEMPLATE_PATH."/images/svg/Search.svg");?>
							<?/*<span class="title"><?=GetMessage("CT_BST_SEARCH_BUTTON")?></span>*/?>
						</button>
					</div>
				</div>
			</div>

		</div>

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
					)
				);?>
			</div>
		</div>

	</div>
</div>

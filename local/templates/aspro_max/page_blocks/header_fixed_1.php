<?
global $arTheme, $arRegion;

$arRegions = CMaxRegionality::getRegions();
if($arRegion)
	$bPhone = ($arRegion['PHONES'] ? true : false);
else
	$bPhone = ((int)$arTheme['HEADER_PHONES'] ? true : false);

$logoClass = ($arTheme['COLORED_LOGO']['VALUE'] !== 'Y' ? '' : ' colored');

$hideLogo = ($arTheme['HEADER_TYPE']['VALUE'] == 28 || $arTheme['HEADER_TYPE']['VALUE'] == 29);

?>
<div class="maxwidth-theme">
	<div class="logo-row v1 row margin0 menu-row ">
		<div class=" <?=($hideLogo ? 'col-md-6' : 'col-md-5' )?>  left_wrap">
			<?if($arTheme['HEADER_TYPE']['VALUE'] != 29):?>
				<div class="pull-left">
					<div class="burger inner-table-block"><?=CMax::showIconSvg("burger dark", SITE_TEMPLATE_PATH."/images/svg/burger.svg");?></div>
				</div>
			<?endif;?>

			<?if($arRegions):?>
				<div class="pull-left">
					<div class="top-description no-title inner-table-block">
						<?\Aspro\Functions\CAsproMax::showRegionList();?>
					</div>
				</div>
			<?endif;?>
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

		<?if($arTheme['HEADER_TYPE']['VALUE'] != 28 && $arTheme['HEADER_TYPE']['VALUE'] != 29):?>
			<div class="col-md-2">
				<div class="logo-block text-center nopadding">
					<div class="inner-table-block">
						<div class="logo<?=$logoClass?>">
							<?=CMax::ShowLogoFixed();?>
						</div>
					</div>
					
				</div>
			</div>
		<?endif;?>


		<div class="right_wrap <?=($hideLogo ? 'col-md-6' : 'col-md-5' )?> pull-right wb">
			


			<div class="pull-right">	
				<?=CMax::ShowBasketWithCompareLink('inner-table-block', 'big');?>
			</div>
				

			<div class="pull-right">
				<div class=" inner-table-block nopadding small-block">
					<div class="wrap_icon wrap_cabinet">
						<?=CMax::showCabinetLink(true, true, 'big');?>
					</div>
				</div>
			</div>

			<div class="pull-right">
				<div class=" inner-table-block">
					<div class="wrap_icon">
						<button class="top-btn inline-search-show dark-color">
							<?=CMax::showIconSvg("search", SITE_TEMPLATE_PATH."/images/svg/Search.svg");?>
							<span class="title"><?=GetMessage("CT_BST_SEARCH_BUTTON")?></span>
						</button>
					</div>
				</div>
			</div>

				
			
		</div>
	</div>
</div>

<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
global $arTheme, $arRegion, $dopClass;
$arRegions = CMaxRegionality::getRegions();
if($arRegion)
	$bPhone = ($arRegion['PHONES'] ? true : false);
else
	$bPhone = ((int)$arTheme['HEADER_PHONES'] ? true : false);
$logoClass = ($arTheme['COLORED_LOGO']['VALUE'] !== 'Y' ? '' : ' colored');
$dopClass = 'subsmall';
?>
<div class="header-wrapper header-v13">
	<div class="logo_and_menu-row smlong">
		<div class="logo-row">
			<div class="maxwidth-theme">
				<div class="row">
					<div class="col-md-5 col-sm-3">
						<div class="burger pull-left"><?=CMax::showIconSvg("burger dark", SITE_TEMPLATE_PATH."/images/svg/burger.svg");?></div>
						<?if($arRegions):?>
							<div class="inline-block pull-left">
								<div class="top-description no-title">
									<?\Aspro\Functions\CAsproMax::showRegionList();?>
								</div>
							</div>
						<?endif;?>
						<div class="wrap_icon inner-table-block">
							<div class="phone-block blocks">
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

					<div class="logo-block col-md-2 text-center nopadding">
						<div class="logo<?=$logoClass?>">
							<?=CMax::ShowLogo();?>
						</div>
					</div>
					<div class="right_wrap col-md-5 pull-right wb">
						<div class="right-icons">
							<div class="pull-right">
								<?=CMax::ShowBasketWithCompareLink('', 'big', '', 'wrap_icon wrap_basket baskets');?>
							</div>

							<div class="pull-right">
								<div class="wrap_icon inner-table-block person">
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
				<div class="lines-row"></div>
			</div>
		</div><?// class=logo-row?>
	</div>
</div>
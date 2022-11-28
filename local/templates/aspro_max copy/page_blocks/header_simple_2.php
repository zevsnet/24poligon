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
<div class="header-wrapper logo-centered">
	<div class="logo_and_menu-row">
		<div class="logo-row bordered-bottom paddings">
			<div class="maxwidth-theme">
				<div class="row">
					<div class="col-md-12 flexbox flexbox--row justify-content-between">
						<div class="back-mobile-arrow visible-xs pull-left">
							<div class="arrow-back">
								<?=CMax::showIconSvg("arrow-back", SITE_TEMPLATE_PATH."/images/svg/m_cart_arrow.svg");?>
							</div>
						</div>

						<?if($arRegions):?>
							<div class="inline-block pull-left hidden-xs">
								<div class="top-description no-title">
									<?\Aspro\Functions\CAsproMax::showRegionList();?>
								</div>
							</div>
						<?endif;?>

						<div class="logo-block">
							<div class="logo<?=$logoClass?>">
								<?=CMax::ShowLogo();?>
							</div>
						</div>

						<div class="pull-right hidden-xs">
							<div class="wrap_icon inner-table-block">
								<div class="phone-block">
									<?if($bPhone):?>
										<?CMax::ShowHeaderPhones('no-icons');?>
									<?endif?>
									<?$callbackExploded = explode(',', $arTheme['SHOW_CALLBACK']['VALUE']);
									if( in_array('HEADER', $callbackExploded) ):?>
										<div class="inline-block">
											<span class="callback-block animate-load colored" data-event="jqm" data-param-form_id="CALLBACK" data-name="callback"><?=GetMessage("CALLBACK")?></span>
										</div>
									<?endif;?>
								</div>
							</div>
						</div>

						<div class="pull-right visible-xs">
							<div class="wrap_icon wrap_phones">
								<?CMax::ShowHeaderMobilePhones("big");?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div><?// class=logo-row?>
	</div>
	<div class="line-row visible-xs"></div>
</div>
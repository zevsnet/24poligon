<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
global $arTheme, $arRegion;
$arRegions = CMaxRegionality::getRegions();
$bOrderView = isset($arTheme['ORDER_VIEW']) ? ($arTheme['ORDER_VIEW']['VALUE'] == 'Y' ? true : false) : false;
$bCabinet = isset($arTheme['CABINET']) ? ($arTheme["CABINET"]["VALUE"]=='Y' ? true : false) : false;
$logoClass = ($arTheme['COLORED_LOGO']['VALUE'] !== 'Y' ? '' : ' colored');
?>

<div class="mega_fixed_menu scrollblock">
	<div class="maxwidth-theme">
		<svg class="svg svg-close" width="14" height="14" viewBox="0 0 14 14">
		  <path data-name="Rounded Rectangle 568 copy 16" d="M1009.4,953l5.32,5.315a0.987,0.987,0,0,1,0,1.4,1,1,0,0,1-1.41,0L1008,954.4l-5.32,5.315a0.991,0.991,0,0,1-1.4-1.4L1006.6,953l-5.32-5.315a0.991,0.991,0,0,1,1.4-1.4l5.32,5.315,5.31-5.315a1,1,0,0,1,1.41,0,0.987,0.987,0,0,1,0,1.4Z" transform="translate(-1001 -946)"></path>
		</svg>
		<i class="svg svg-close mask arrow"></i>

		<div class="row">
			<div class="col-md-9">
				<div class="left_menu_block">
					<div class="logo_block flexbox flexbox--row align-items-normal">
						<div class="logo<?=$logoClass?>">
							<?=CMax::ShowLogo();?>
						</div>
						<div class="top-description addr">
							<?$APPLICATION->IncludeFile(SITE_DIR."include/top_page/slogan.php", array(), array(
									"MODE" => "html",
									"NAME" => "Text in title",
									"TEMPLATE" => "include_area.php",
								)
							);?>
						</div>
					</div>

					<div class="search_block">
						<div class="search_wrap">
							<div class="search-block">
								<?$APPLICATION->IncludeComponent(
									"bitrix:main.include",
									"",
									Array(
										"AREA_FILE_SHOW" => "file",
										"PATH" => SITE_DIR."include/top_page/search.title.megamenu.php",
										"EDIT_TEMPLATE" => "include_area.php",					
									),
									false, array("HIDE_ICONS" => "Y")
								);?>
							</div>
						</div>
					</div>
					<?if(CMax::nlo('menu-megafixed', 'class="loadings" style="height:125px;width:50px;"')):?>
					<!-- noindex -->
					<?$APPLICATION->IncludeComponent(
						"bitrix:menu",
						"menu_in_burger",
						Array(
							"ALLOW_MULTI_SELECT" => "N",
							"CHILD_MENU_TYPE" => "left",
							"COMPONENT_TEMPLATE" => "top",
							"COUNT_ITEM" => "6",
							"DELAY" => "N",
							"MAX_LEVEL" => $arTheme["MAX_DEPTH_MENU"]["VALUE"],
							"MENU_CACHE_GET_VARS" => array(),
							"MENU_CACHE_TIME" => "3600000",
							"MENU_CACHE_TYPE" => "A",
							"MENU_CACHE_USE_GROUPS" => "N",
							"CACHE_SELECTED_ITEMS" => "N",
							"ALLOW_MULTI_SELECT" => "Y",
							"ROOT_MENU_TYPE" => "top_content_multilevel",
							"USE_EXT" => "Y"
						)
					);?>
					<!-- /noindex -->
					<?endif;?>
					<?CMax::nlo('menu-megafixed');?>
				</div>
			</div>
			<div class="col-md-3">
				<div class="right_menu_block">
					<div class="contact_wrap">
						<div class="info">
							<div class="phone blocks">
								<div class="">
									<?CMax::ShowHeaderPhones('white sm', true);?>
								</div>
								<div class="callback_wrap">
									<span class="callback-block animate-load font_upper colored" data-event="jqm" data-param-form_id="CALLBACK" data-name="callback"><?=GetMessage("S_CALLBACK")?></span>
								</div>
							</div>
							<div class="question_button_wrapper">
								<span class="btn btn-lg btn-transparent-border-color btn-wide animate-load colored_theme_hover_bg-el" data-event="jqm" data-param-form_id="ASK" data-name="ask">
									<?=GetMessage('ASK')?>
								</span>
							</div>

							<div class="person_wrap">
								<?
								// show cabinet item
								CMax::showCabinetLink(true, true, 'big');

								// show basket item
								CMax::ShowMobileMenuBasket();
								?>
							</div>
						</div>
					</div>

					<div class="footer_wrap">

						<?if($arRegions):?>
							<div class="inline-block">
								<div class="top-description no-title">
									<?\Aspro\Functions\CAsproMax::showRegionList();?>
								</div>
							</div>
						<?endif;?>

						<?=CMax::showEmail('email blocks color-theme-hover')?>
						<?=CMax::showAddress('address blocks')?>
						<div class="social-block">
							<?$APPLICATION->IncludeComponent(
								"aspro:social.info.max",
								"",
								array(
									"CACHE_TYPE" => "A",
									"CACHE_TIME" => "3600000",
									"CACHE_GROUPS" => "N",
									"COMPONENT_TEMPLATE" => ""
								),
								false
							);?>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
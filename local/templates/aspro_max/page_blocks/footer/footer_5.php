<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
global $arTheme;
?>

<div class="footer-v5">
	<div class="footer-inner shorten">
		<div class="footer_top">
			<div class="maxwidth-theme">
				<div class="row">
					<div class="wrapper col-md-5">
						<div class="first_bottom_menu">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
								array(
									"COMPONENT_TEMPLATE" => ".default",
									"PATH" => SITE_DIR."include/footer/menu/menu_bottom5.php",
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "",
									"AREA_FILE_RECURSIVE" => "Y",
									"EDIT_TEMPLATE" => "include_area.php"
								),
								false, array("HIDE_ICONS" => "Y")
							);?>
						</div>
						<div class="social-block">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
								array(
									"COMPONENT_TEMPLATE" => ".default",
									"PATH" => SITE_DIR."include/footer/social.info.php",
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "",
									"AREA_FILE_RECURSIVE" => "Y",
									"EDIT_TEMPLATE" => "include_area.php"
								),
								false, array("HIDE_ICONS" => "Y")
							);?>
						</div>
					</div>
					<div class="col-md-3 contact-block">
						<div class="info">
							<div class="row">
								<div class="col-md-12 col-sm-12">
									<div class="phone blocks">
										<div class="inline-block">
											<?CMax::ShowHeaderPhones('white sm', true);?>
										</div>
										<?$callbackExploded = explode(',', $arTheme['SHOW_CALLBACK']['VALUE']);
										if( in_array('FOOTER', $callbackExploded) ):?>
											<div class="inline-block callback_wrap">
												<span class="callback-block animate-load colored" data-event="jqm" data-param-form_id="CALLBACK" data-name="callback"><?=GetMessage("CALLBACK")?></span>
											</div>
										<?endif;?>
									</div>
								</div>
								<div class="col-md-12 col-sm-12">
									<?=CMax::showEmail('email blocks')?>
								</div>
								<div class="col-md-12 col-sm-12">
									<?=CMax::showAddress('address blocks')?>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-3 col-md-offset-1">
						<div class="info">
							<?if(\Bitrix\Main\Loader::includeModule('subscribe') && $arTheme['HIDE_SUBSCRIBE']['VALUE'] != 'Y'):?>
								<div class="subscribe_button">
									<span class="btn" data-event="jqm" data-param-id="subscribe" data-param-type="subscribe" data-name="subscribe"><?=GetMessage('SUBSCRIBE_TITLE')?>
									<?=CMax::showIconSvg('subscribe', SITE_TEMPLATE_PATH.'/images/svg/subscribe_small_footer.svg')?></span>
								</div>
							<?endif;?>
							<div>
								<div class="confidentiality">
									<?=CMax::showIconSvg('privacy_policy', SITE_TEMPLATE_PATH.'/images/svg/privacy_policy.svg')?>
									<?$APPLICATION->IncludeFile(SITE_DIR."include/footer/confidentiality.php", Array(), Array(
											"MODE" => "php",
											"NAME" => "onfidentiality",
											"TEMPLATE" => "include_area.php",
										)
									);?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="footer_bottom">
			<div class="maxwidth-theme">
				<div class="footer-bottom__items-wrapper">
					<div class="footer-bottom__item copy font_xs">
						<?$APPLICATION->IncludeFile(SITE_DIR."include/footer/copy/copyright.php", Array(), Array(
								"MODE" => "php",
								"NAME" => "Copyright",
								"TEMPLATE" => "include_area.php",
							)
						);?>
					</div>
					<div id="bx-composite-banner"></div>
					<div class="footer-bottom__item pays">
						<?$APPLICATION->IncludeFile(SITE_DIR."include/footer/copy/pay_system_icons.php", Array(), Array(
								"MODE" => "php",
								"NAME" => "onfidentiality",
								"TEMPLATE" => "include_area.php",
							)
						);?>
					</div>
					<?=\Aspro\Functions\CAsproMax::showDeveloperBlock();?>
				</div>
			</div>
		</div>
	</div>
</div>
<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
global $arTheme;
$bPrintButton = isset($arTheme['PRINT_BUTTON']) ? ($arTheme['PRINT_BUTTON']['VALUE'] == 'Y' ? true : false) : false;
?>
<div class="footer-v1">
	<div class="footer-inner">
		<div class="footer_bottom">
			<div class="maxwidth-theme">
				<div class="row">
					<div class="link_block col-md-6 col-sm-6 pull-right">
						<div class="pull-right">
							<div class="pays">
								<?$APPLICATION->IncludeFile(SITE_DIR."include/footer/copy/pay_system_icons.php", Array(), Array(
										"MODE" => "php",
										"NAME" => "onfidentiality",
										"TEMPLATE" => "include_area.php",
									)
								);?>
							</div>
						</div>
						<div class="pull-right">
							<?=CMax::ShowPrintLink();?>
						</div>
					</div>
					<div class="copy-block col-md-6 col-sm-6 pull-left">
						<div class="copy font_xs pull-left">
							<?$APPLICATION->IncludeFile(SITE_DIR."include/footer/copy/copyright.php", Array(), Array(
									"MODE" => "php",
									"NAME" => "Copyright",
									"TEMPLATE" => "include_area.php",
								)
							);?>
						</div>
						<div id="bx-composite-banner" class="pull-left"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
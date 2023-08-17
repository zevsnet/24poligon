<?if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true ) die();?>
<?if( $arParams["POPUP_AUTH"] == "Y"){ ?>
	<?if( $arResult["AUTH_SERVICES"] ){?>
		<div class="soc-avt inline">
			<div class="title">
				<?=GetMessage("SOCSERV_AS_USER_FORM");?>
			</div>
			<?$APPLICATION->IncludeComponent(
				"bitrix:socserv.auth.form",
				"icons_inline",
				array(
					"AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
					"AUTH_URL" => $arResult["AUTH_URL"],
					"POST" => $arResult["POST"],
					"POPUP" => "N",
					"SUFFIX" => "form_inline",
				),
				$component,
				array( "HIDE_ICONS" => "N" )
			);?>
		</div>
	<?}?>
<?}?>

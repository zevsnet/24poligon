<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?if( $_POST["TYPE"] == "AUTH" || $_GET["auth_service_id"] ){?>
	<?if( !$GLOBALS["USER"]->IsAuthorized() ){?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:system.auth.form",
			"mshop",
			array(
				"TITLE" => "Авторизация",
				"AUTH_URL" => SITE_DIR."ajax/show_auth_popup.php",
				"REGISTER_URL" => SITE_DIR."auth/registration/",
				"PROFILE_URL" => SITE_DIR."auth/",
				"FORGOT_PASSWORD_URL" => SITE_DIR."auth/forgot-password/",
				"SHOW_ERRORS" => "Y",
				"PERSONAL" => SITE_DIR."personal/",
				"POST" => $_POST
			)
		);?>
	<?}elseif( $_REQUEST["backurl"] ){?>
		<div class="progress_auth">Осуществляется вход в аккаунт...</div>
		<script>
			jsAjaxUtil.ShowLocalWaitWindow( 'id', 'wrap_ajax_auth' );
			BX.reload( true );
		</script>
	<?}else{
		if( $_REQUEST["backurl"] ){
			LocalRedirect( $_REQUEST["backurl"] );
		}else{
			LocalRedirect( $arParams["PERSONAL"] );
		}
	}?>
<?}else{?>
	<?LocalRedirect( $arParams["PERSONAL"] );?>
<?}?>
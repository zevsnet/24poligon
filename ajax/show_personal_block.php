<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?$APPLICATION->IncludeComponent(
	"bitrix:system.auth.form",
	"top",
	Array(
		"REGISTER_URL" => trim($_REQUEST["REGISTER_URL"]),
		"FORGOT_PASSWORD_URL" =>  trim($_REQUEST["FORGOT_PASSWORD_URL"]),
		"PROFILE_URL" =>  trim($_REQUEST["PROFILE_URL"]),
		"SHOW_ERRORS" =>  trim($_REQUEST["SHOW_ERRORS"])
	)
);?>
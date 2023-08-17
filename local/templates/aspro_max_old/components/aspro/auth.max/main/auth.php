<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

global $USER, $arTheme;
\Bitrix\Main\Localization\Loc::loadMessages(__FILE__);
$APPLICATION->SetTitle(GetMessage("TITLE"));
$APPLICATION->SetPageProperty("TITLE_CLASS", "center");
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/phoneorlogin.min.js');

$bPopupAuth = (isset($_POST['POPUP_AUTH']) ? $_POST['POPUP_AUTH'] === 'Y' : false);
?>
<?if(!$bPopupAuth):?>
	<style>
		.personal-link[data-event]{display:none !important;}
		.left-menu-md, body .container.cabinte-page .maxwidth-theme .left-menu-md, .right-menu-md, body .container.cabinte-page .maxwidth-theme .right-menu-md{display:none !important;}
		.content-md{width:100%;}
		.border_block{border:none;}
	</style>
<?endif;?>
<?if(!$USER->IsAuthorized()):?>
	<?if(!isset($_SERVER["HTTP_X_REQUESTED_WITH"])):?>
		<div class="top-text top-text--auth">
			<?$APPLICATION->IncludeFile(SITE_DIR."include/auth_description.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("AUTH_INCLUDE_AREA")));?>
		</div>
	<?endif;?>
	<?$APPLICATION->IncludeComponent(
		"bitrix:system.auth.form",
		"main",
		Array(
			"AUTH_URL" => $arResult["SEF_FOLDER"].$arResult["URL_TEMPLATES"]["auth"],
			"REGISTER_URL" => $arResult["SEF_FOLDER"].$arResult["URL_TEMPLATES"]["registration"],
			"FORGOT_PASSWORD_URL" => $arResult["SEF_FOLDER"].$arResult["URL_TEMPLATES"]["forgot_password"],
			"CHANGE_PASSWORD_URL" => $arResult["SEF_FOLDER"].$arResult["URL_TEMPLATES"]["change_password"],
			"PROFILE_URL" => $arResult["SEF_FOLDER"],
			"SHOW_ERRORS" => "Y",
			"POPUP_AUTH" => $bPopupAuth ? 'Y' : 'N',
		)
	);?>
<?else:?>
	<?$url = ($arTheme["PERSONAL_PAGE_URL"]["VALUE"] ? $arTheme["PERSONAL_PAGE_URL"]["VALUE"] : $arParams["SEF_FOLDER"]);?>
	<?LocalRedirect($url);?>
<?endif;?>
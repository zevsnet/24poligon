<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Поделиться корзиной");
?>
<?$APPLICATION->IncludeComponent(
	"aspro:basket.share.max",
	"",
	array(
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"DETAIL_ACTUAL" => "Y",
		"DETAIL_PRODUCT_PROPERTIES" => array(
			"CML2_ARTICLE",
			"COLOR_REF",
			"COLOR_REF2",
			"SIZES",
			"SIZES2",
			"SIZES3",
			"SIZES4",
			"SIZES5"
		),
		"DETAIL_SET_PAGE_TITLE" => "Y",
		"DETAIL_SHOW_AMOUNT" => "Y",
		"DETAIL_SHOW_DISCOUNT_PERCENT" => "Y",
		"DETAIL_SHOW_DISCOUNT_PERCENT_NUMBER" => "Y",
		"DETAIL_SHOW_OLD_PRICE" => "Y",
		"DETAIL_SHOW_ONE_CLICK_BUY" => "N",
		"DETAIL_SHOW_STICKERS" => "N",
		"DETAIL_SHOW_VERSION_SWITCHER" => "Y",
		"DETAIL_USE_COMPARE" => "Y",
		"DETAIL_USE_CUSTOM_MESSAGES" => "N",
		"DETAIL_USE_DELAY" => "N",
		"DETAIL_USE_FAST_VIEW" => "N",
		"FILE_404" => "",
		"MESSAGE_404" => "",
		"NEW_SET_PAGE_TITLE" => "Y",
		"NEW_SHARE_SOCIALS" => array(
			"VKONTAKTE",
			"FACEBOOK",
			"ODNOKLASSNIKI",
			"TWITTER"
		),
		"NEW_SHOW_SHARE_SOCIALS" => "Y",
		"NEW_SITE_ID" => "",
		"NEW_USER_ID" => "",
		"NEW_USE_CUSTOM_MESSAGES" => "N",
		"SEF_FOLDER" => "/sharebasket/",
		"SEF_MODE" => "Y",
		"SEF_URL_TEMPLATES" => array(
			"detail" => "#CODE#/",
			"new" => "new/"
		),
		"SET_STATUS_404" => "Y",
		"SHOW_404" => "N"
	)
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
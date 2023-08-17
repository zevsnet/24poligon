<?
use Bitrix\Main\Loader,
	Bitrix\Main\Config\Option;

define("NOT_CHECK_PERMISSIONS",true);
define("STATISTIC_SKIP_ACTIVITY_CHECK", "true");
define('STOP_STATISTICS', true);
define('NO_KEEP_STATISTIC', true);
define('NO_AGENT_STATISTIC', true);
define('NO_AGENT_CHECK', true);
define('PUBLIC_AJAX_MODE', true);

if(isset($_REQUEST['site_id'])) {
	$SITE_ID = htmlspecialchars($_REQUEST['site_id']);
	define('SITE_ID', $SITE_ID);
}
if(isset($_REQUEST['site_dir'])) {
	$SITE_DIR = htmlspecialchars($_REQUEST['site_dir']);
	define('SITE_DIR', $SITE_DIR);
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$arSite = CSite::GetByID($SITE_ID)->Fetch();

Loader::includeModule('aspro.max');
$moduleClass = 'CMax';
$arFrontParametrs = $moduleClass::GetFrontParametrsValues($SITE_ID, $SITE_DIR);
$tmp = $arFrontParametrs['DATE_FORMAT'];
$DATE_MASK = ($tmp == 'DOT' ? 'd.m.y' : ($tmp == 'HYPHEN' ? 'd-m-y' : ($tmp == 'SPACE' ? 'd m y' : ($tmp == 'SLASH' ? 'd/m/y' : 'd:m:y'))));
$VALIDATE_DATE_MASK = ($tmp == 'DOT' ? '^[0-9]{1,2}\.[0-9]{1,2}\.[0-9]{4}$' : ($tmp == 'HYPHEN' ? '^[0-9]{1,2}\-[0-9]{1,2}\-[0-9]{4}$' : ($tmp == 'SPACE' ? '^[0-9]{1,2} [0-9]{1,2} [0-9]{4}$' : ($tmp == 'SLASH' ? '^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$' : '^[0-9]{1,2}\:[0-9]{1,2}\:[0-9]{4}$'))));
$DATE_PLACEHOLDER = ($tmp == 'DOT' ? GetMessage('DATE_FORMAT_DOT') : ($tmp == 'HYPHEN' ? GetMessage('DATE_FORMAT_HYPHEN') : ($tmp == 'SPACE' ? GetMessage('DATE_FORMAT_SPACE') : ($tmp == 'SLASH' ? GetMessage('DATE_FORMAT_SLASH') : GetMessage('DATE_FORMAT_COLON')))));
$DATETIME_MASK = ($tmp == 'DOT' ? 'd.m.y' : ($tmp == 'HYPHEN' ? 'd-m-y' : ($tmp == 'SPACE' ? 'd m y' : ($tmp == 'SLASH' ? 'd/m/y' : 'd:m:y')))).' h:s';
$DATETIME_PLACEHOLDER = ($tmp == 'DOT' ? GetMessage('DATE_FORMAT_DOT') : ($tmp == 'HYPHEN' ? GetMessage('DATE_FORMAT_HYPHEN') : ($tmp == 'SPACE' ? GetMessage('DATE_FORMAT_SPACE') : ($tmp == 'SLASH' ? GetMessage('DATE_FORMAT_SLASH') : GetMessage('DATE_FORMAT_COLON'))))).' '.GetMessage('TIME_FORMAT_COLON');
$VALIDATE_DATETIME_MASK = ($tmp == 'DOT' ? '^[0-9]{1,2}\.[0-9]{1,2}\.[0-9]{4} [0-9]{1,2}\:[0-9]{1,2}$' : ($tmp == 'HYPHEN' ? '^[0-9]{1,2}\-[0-9]{1,2}\-[0-9]{4} [0-9]{1,2}\:[0-9]{1,2}$' : ($tmp == 'SPACE' ? '^[0-9]{1,2} [0-9]{1,2} [0-9]{4} [0-9]{1,2}\:[0-9]{1,2}$' : ($tmp == 'SLASH' ? '^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4} [0-9]{1,2}\:[0-9]{1,2}$' : '^[0-9]{1,2}\:[0-9]{1,2}\:[0-9]{4} [0-9]{1,2}\:[0-9]{1,2}$'))));

list($bPhoneAuthSupported, $bPhoneAuthShow, $bPhoneAuthRequired, $bPhoneAuthUse) = Aspro\Max\PhoneAuth::getOptions();?>
<?header('Content-Type: application/javascript;charset='.LANG_CHARSET);?>
var arAsproOptions = window[solutionName] = ({
	"SITE_DIR" : "<?=$SITE_DIR?>",
	"SITE_ID" : "<?=$SITE_ID?>",
	"SITE_TEMPLATE_PATH" : "<?=SITE_TEMPLATE_PATH?>",
	"SITE_ADDRESS" : "<?=$arSite['SERVER_NAME'];?>",
	"FORM" : ({
		"ASK_FORM_ID" : "ASK",
		"SERVICES_FORM_ID" : "SERVICES",
		"FEEDBACK_FORM_ID" : "FEEDBACK",
		"CALLBACK_FORM_ID" : "CALLBACK",
		"RESUME_FORM_ID" : "RESUME",
		"TOORDER_FORM_ID" : "TOORDER"
	}),
	"PAGES" : ({
		"CATALOG_PAGE_URL" : "<?=$arFrontParametrs['CATALOG_PAGE_URL']?>",
		"COMPARE_PAGE_URL" : "<?=$arFrontParametrs['COMPARE_PAGE_URL']?>",
		"SEARCH_PAGE_URL" : "<?=$arFrontParametrs['SEARCH_PAGE_URL']?>",
		"BASKET_PAGE_URL" : "<?=$arFrontParametrs['BASKET_PAGE_URL']?>",
		"SHARE_BASKET_PAGE_URL" : "<?=$arFrontParametrs['SHARE_BASKET_PAGE_URL']?>",
		"ORDER_PAGE_URL" : "<?=$arFrontParametrs['ORDER_PAGE_URL']?>",
		"PERSONAL_PAGE_URL" : "<?=$arFrontParametrs['PERSONAL_PAGE_URL']?>",
		"SUBSCRIBE_PAGE_URL" : "<?=$arFrontParametrs['SUBSCRIBE_PAGE_URL']?>",
	}),
	"PRICES" : ({
		"MIN_PRICE" : "<?=trim(Option::get($moduleClass::moduleID, "MIN_ORDER_PRICE", "1000", $SITE_ID));?>",
	}),
	"THEME" : ({
		'THEME_SWITCHER' : '<?=$arFrontParametrs['THEME_SWITCHER']?>',
		'BIGBANNER_MOBILE': '<?=$arFrontParametrs['BIGBANNER_MOBILE']?>',
		'BASE_COLOR' : '<?=$arFrontParametrs['BASE_COLOR']?>',
		'BASE_COLOR_CUSTOM' : '<?=$arFrontParametrs['BASE_COLOR_CUSTOM']?>',
		'LOGO_IMAGE' : '<?=$arFrontParametrs['LOGO_IMAGE']?>',
		'LOGO_IMAGE_LIGHT' : '<?=$arFrontParametrs['LOGO_IMAGE_WHITE']?>',
		'TOP_MENU_FIXED' : '<?=$arFrontParametrs['TOP_MENU_FIXED']?>',
		'COLORED_LOGO' : '<?=$arFrontParametrs['COLORED_LOGO']?>',
		'COMPACT_FOOTER_MOBILE' : '<?=$arFrontParametrs['COMPACT_FOOTER_MOBILE']?>',
		'SIDE_MENU' : '<?=$arFrontParametrs['SIDE_MENU']?>',
		'SCROLLTOTOP_TYPE' : '<?=$arFrontParametrs['SCROLLTOTOP_TYPE']?>',
		'SCROLLTOTOP_POSITION' : '<?=$arFrontParametrs['SCROLLTOTOP_POSITION']?>',
		'SCROLLTOTOP_POSITION_RIGHT' : '<?=$arFrontParametrs['SCROLLTOTOP_POSITION_RIGHT']?>',
		'SCROLLTOTOP_POSITION_BOTTOM' : '<?=$arFrontParametrs['SCROLLTOTOP_POSITION_BOTTOM']?>',
		'ONE_CLICK_BUY_CAPTCHA' : '<?=$arFrontParametrs['ONE_CLICK_BUY_CAPTCHA']?>',
		'USE_INTL_PHONE' : '<?=$arFrontParametrs['USE_INTL_PHONE']?>',
		'PHONE_MASK' : '<?=$arFrontParametrs['PHONE_MASK']?>',
		'VALIDATE_PHONE_MASK' : '<?=$arFrontParametrs['VALIDATE_PHONE_MASK']?>',
		'DATE_MASK' : '<?=$DATE_MASK?>',
		'DATE_PLACEHOLDER' : '<?=$DATE_PLACEHOLDER?>',
		'VALIDATE_DATE_MASK' : '<?=($VALIDATE_DATE_MASK)?>',
		'DATETIME_MASK' : '<?=$DATETIME_MASK?>',
		'DATETIME_PLACEHOLDER' : '<?=$DATETIME_PLACEHOLDER?>',
		'VALIDATE_DATETIME_MASK' : '<?=($VALIDATE_DATETIME_MASK)?>',
		'VALIDATE_FILE_EXT' : '<?=$arFrontParametrs['VALIDATE_FILE_EXT']?>',
		'BIGBANNER_ANIMATIONTYPE' : '<?=$arFrontParametrs['BIGBANNER_ANIMATIONTYPE']?>',
		'BIGBANNER_SLIDESSHOWSPEED' : '<?=$arFrontParametrs['BIGBANNER_SLIDESSHOWSPEED']?>',
		'BIGBANNER_ANIMATIONSPEED' : '<?=$arFrontParametrs['BIGBANNER_ANIMATIONSPEED']?>',
		'PARTNERSBANNER_SLIDESSHOWSPEED' : '<?=$arFrontParametrs['PARTNERSBANNER_SLIDESSHOWSPEED']?>',
		'PARTNERSBANNER_ANIMATIONSPEED' : '<?=$arFrontParametrs['PARTNERSBANNER_ANIMATIONSPEED']?>',
		'ORDER_BASKET_VIEW' : '<?=$arFrontParametrs['ORDER_BASKET_VIEW']?>',
		'SHOW_BASKET_ONADDTOCART' : '<?=$arFrontParametrs['SHOW_BASKET_ONADDTOCART']?>',
		'SHOW_BASKET_PRINT' : '<?=$arFrontParametrs['SHOW_BASKET_PRINT']?>',
		'SHOW_SHARE_BASKET' : '<?=$arFrontParametrs['SHOW_SHARE_BASKET']?>',
		"SHOW_DOWNLOAD_BASKET" : '<?=$arFrontParametrs['SHOW_DOWNLOAD_BASKET'];?>',
		"BASKET_FILE_DOWNLOAD_TEMPLATE" : '<?=$arFrontParametrs['BASKET_FILE_DOWNLOAD_TEMPLATE'];?>',
		"SHOW_ONECLICKBUY_ON_BASKET_PAGE" : '<?=$arFrontParametrs['SHOW_ONECLICKBUY_ON_BASKET_PAGE'];?>',
		'SHOW_LICENCE' : '<?=$arFrontParametrs['SHOW_LICENCE'];?>',
		'LICENCE_CHECKED' : '<?=$arFrontParametrs['LICENCE_CHECKED'];?>',
		'SHOW_OFFER' : '<?=$arFrontParametrs['SHOW_OFFER'];?>',
		'OFFER_CHECKED' : '<?=$arFrontParametrs['OFFER_CHECKED'];?>',
		'LOGIN_EQUAL_EMAIL' : '<?=$arFrontParametrs['LOGIN_EQUAL_EMAIL'];?>',
		'PERSONAL_ONEFIO' : '<?=$arFrontParametrs['PERSONAL_ONEFIO'];?>',
		'SHOW_TOTAL_SUMM' : '<?=$arFrontParametrs['SHOW_TOTAL_SUMM'];?>',
		'SHOW_TOTAL_SUMM_TYPE' : '<?=$arFrontParametrs['SHOW_TOTAL_SUMM_TYPE'];?>',
		'CHANGE_TITLE_ITEM_LIST' : '<?=$arFrontParametrs['CHANGE_TITLE_ITEM_LIST'];?>',
		'CHANGE_TITLE_ITEM_DETAIL' : '<?=$arFrontParametrs['CHANGE_TITLE_ITEM_DETAIL'];?>',
		'DISCOUNT_PRICE' : '<?=$arFrontParametrs['DISCOUNT_PRICE'];?>',
		'STORES_SOURCE' : '<?=$arFrontParametrs['STORES_SOURCE'];?>',
		'TYPE_SKU' : '<?=$arFrontParametrs['TYPE_SKU']?>',
		'MENU_POSITION' : '<?=$arFrontParametrs['MENU_POSITION']?>',
		'MENU_TYPE_VIEW' : '<?=$arFrontParametrs['MENU_TYPE_VIEW']?>',
		'DETAIL_PICTURE_MODE' : '<?=$arFrontParametrs['DETAIL_PICTURE_MODE']?>',
		'PAGE_WIDTH' : '<?=$arFrontParametrs['PAGE_WIDTH']?>',
		'PAGE_CONTACTS' : '<?=$arFrontParametrs['PAGE_CONTACTS']?>',
		'HEADER_TYPE' : '<?=$arFrontParametrs['HEADER_TYPE']?>',
		'REGIONALITY_SEARCH_ROW' : '<?=$arFrontParametrs['REGIONALITY_SEARCH_ROW']?>',
		'HEADER_FIXED' : '<?=$arFrontParametrs['HEADER_FIXED']?>',
		'HEADER_MOBILE' : '<?=$arFrontParametrs['HEADER_MOBILE']?>',
		'HEADER_MOBILE_MENU' : '<?=$arFrontParametrs['HEADER_MOBILE_MENU']?>',
		'TYPE_SEARCH' : '<?=$arFrontParametrs['TYPE_SEARCH']?>',
		'PAGE_TITLE' : '<?=$arFrontParametrs['PAGE_TITLE']?>',
		'INDEX_TYPE' : '<?=$arFrontParametrs['INDEX_TYPE']?>',
		'FOOTER_TYPE' : '<?=$arFrontParametrs['FOOTER_TYPE']?>',
		'PRINT_BUTTON' : '<?=(isset($arFrontParametrs['PRINT_BUTTON']) && $arFrontParametrs['PRINT_BUTTON'] ? $arFrontParametrs['PRINT_BUTTON'] : 'N')?>',
		'USE_LAZY_LOAD' : '<?=$arFrontParametrs['USE_LAZY_LOAD']?>',
		'EXPRESSION_FOR_PRINT_PAGE' : '<?=$arFrontParametrs['EXPRESSION_FOR_PRINT_PAGE']?>',
		'EXPRESSION_FOR_FAST_VIEW' : '<?=$arFrontParametrs['EXPRESSION_FOR_FAST_VIEW']?>',
		'EXPRESSION_FOR_SHARE_BASKET' : '<?=$arFrontParametrs['EXPRESSION_FOR_SHARE_BASKET']?>',
		'EXPRESSION_FOR_DOWNLOAD_BASKET' : '<?=$arFrontParametrs['EXPRESSION_FOR_DOWNLOAD_BASKET']?>',
		'FILTER_VIEW' : '<?=$arFrontParametrs['FILTER_VIEW']?>',
		'YA_GOALS' : '<?=$arFrontParametrs['YA_GOALS']?>',
		'YA_COUNTER_ID' : '<?=$arFrontParametrs['YA_COUNTER_ID']?>',
		'USE_FORMS_GOALS' : '<?=$arFrontParametrs['USE_FORMS_GOALS']?>',
		'USE_BASKET_GOALS' : '<?=$arFrontParametrs['USE_BASKET_GOALS']?>',
		'USE_DEBUG_GOALS' : '<?=$arFrontParametrs['USE_DEBUG_GOALS']?>',
		'SHOW_HEADER_GOODS' : '<?=$arFrontParametrs['SHOW_HEADER_GOODS']?>',
		'INSTAGRAMM_INDEX' : '<?=(isset($arFrontParametrs[$arFrontParametrs['INDEX_TYPE'].'_INSTAGRAMM']) ? $arFrontParametrs[$arFrontParametrs['INDEX_TYPE'].'_INSTAGRAMM'] : 'Y')?>',
		'USE_PHONE_AUTH': '<?=($bPhoneAuthUse ? 'Y' : 'N')?>',
		'MOBILE_CATALOG_LIST_ELEMENTS_COMPACT': '<?=$arFrontParametrs['MOBILE_CATALOG_LIST_ELEMENTS_COMPACT']?>',
		'STICKY_SIDEBAR': '<?=$arFrontParametrs['STICKY_SIDEBAR']?>',
		'REVIEWS_VIEW': '<?=$arFrontParametrs['REVIEWS_VIEW']?>',
		'MANY_BUY_CATALOG_SECTIONS': '<?=$arFrontParametrs['MANY_BUY_CATALOG_SECTIONS']?>',
		'FIXED_BUY_MOBILE': '<?=$arFrontParametrs['FIXED_BUY_MOBILE']?>',
	}),
	"REGIONALITY":({
		'USE_REGIONALITY' : '<?=$arFrontParametrs['USE_REGIONALITY']?>',
		'REGIONALITY_VIEW' : '<?=$arFrontParametrs['REGIONALITY_VIEW']?>',
	}),
	"COUNTERS":({
		"YANDEX_COUNTER" : 1,
		"GOOGLE_COUNTER" : 1,
		"YANDEX_ECOMERCE" : "<?=Option::get($moduleClass::moduleID, "YANDEX_ECOMERCE", false, $SITE_ID)?>",
		"GOOGLE_ECOMERCE" : "<?=Option::get($moduleClass::moduleID, "GOOGLE_ECOMERCE", false, $SITE_ID)?>",
		"GA_VERSION" : "<?=Option::get($moduleClass::moduleID, "GA_VERSION", 'v3', $SITE_ID)?>",
		"TYPE":{
			"ONE_CLICK":"<?=GetMessage("ONE_CLICK_BUY");?>",
			"QUICK_ORDER":"<?=GetMessage("QUICK_ORDER");?>",
		},
		"GOOGLE_EVENTS":{
			"ADD2BASKET": "<?=trim(Option::get($moduleClass::moduleID, "BASKET_ADD_EVENT", "addToCart", $SITE_ID))?>",
			"REMOVE_BASKET": "<?=trim(Option::get($moduleClass::moduleID, "BASKET_REMOVE_EVENT", "removeFromCart", $SITE_ID))?>",
			"CHECKOUT_ORDER": "<?=trim(Option::get($moduleClass::moduleID, "CHECKOUT_ORDER_EVENT", "checkout", $SITE_ID))?>",
			"PURCHASE": "<?=trim(Option::get($moduleClass::moduleID, "PURCHASE_ORDER_EVENT", "gtm.dom", $SITE_ID))?>",
		}
	}),
	"JS_ITEM_CLICK":({
		"precision" : 6,
		"precisionFactor" : Math.pow(10,6)
	})
});
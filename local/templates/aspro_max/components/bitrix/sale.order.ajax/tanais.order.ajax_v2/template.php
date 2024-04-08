<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main,
	Bitrix\Main\Loader,
	Tanais\Order\System,
	Bitrix\Main\Config\Option,
	Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CMain $APPLICATION
 * @var CUser $USER
 * @var SaleOrderAjax $component
 * @var string $templateFolder
 */

if (!Loader::includeModule("tanais.order")) {
	return;
}

$context = Main\Application::getInstance()->getContext();
$request = $context->getRequest();

CJSCore::Init(['masked_input']);

$arParams['ORDER_THEME'] = Option::get("tanais.order", "ORDER_THEME", "first");
$arParams['ORDER_DELIVERY_SORT'] = Option::get("tanais.order", "ORDER_DELIVERY_SORT", "SORT");
$arParams['PHONE_INPUT_MASK'] = Option::get("tanais.order", "PHONE_INPUT_MASK", "+7 (999) 999-99-99");

$arParams['USE_SALE_BASKET_TO_ORDER_PAGE'] = Option::get("tanais.order", "USE_SALE_BASKET_TO_ORDER_PAGE", "Y");
$arParams['LINK_TO_CATALOG'] = str_replace(
	"#SITE_DIR#",
	SITE_DIR,
	Option::get("tanais.order", "LINK_TO_CATALOG", "#SITE_DIR#catalog/")
);
$arParams['ALLOW_BASKET_SPEC_TO_PRINT'] = Option::get("tanais.order", "ALLOW_BASKET_SPEC_TO_PRINT", "Y");
$arParams['ALLOW_BASKET_SPEC_TO_DOWNLOAD_PDF'] = Option::get("tanais.order", "ALLOW_BASKET_SPEC_TO_DOWNLOAD_PDF", "Y");

$arParams['USE_BUY_ONE_CLICK'] = Option::get("tanais.order", "USE_BUY_ONE_CLICK", "Y");
$arParams['BUY_ONE_CLICK_SHOW_USER_DESCRIPTION'] = Option::get("tanais.order", "BUY_ONE_CLICK_SHOW_USER_DESCRIPTION", "N");
$arParams['BUY_ONE_CLICK_USE_CAPTCHA'] = Option::get("tanais.order", "BUY_ONE_CLICK_USE_CAPTCHA", "N");

$arParams['USE_LEGAL_ENTITY_AUTOLOAD_DATA'] = Option::get("tanais.order", "USE_LEGAL_ENTITY_AUTOLOAD_DATA", "Y");
$arParams['AUTOLOAD_LEGAL_INN'] = Option::get("tanais.order", "AUTOLOAD_LEGAL_INN", "INN");
$arParams['AUTOLOAD_LEGAL_KPP'] = Option::get("tanais.order", "AUTOLOAD_LEGAL_KPP", "KPP");
$arParams['AUTOLOAD_LEGAL_COMPANY_ADDR'] = Option::get("tanais.order", "AUTOLOAD_LEGAL_COMPANY_ADDR", "COMPANY_ADR");
$arParams['AUTOLOAD_LEGAL_COMPANY'] = Option::get("tanais.order", "AUTOLOAD_LEGAL_COMPANY", "COMPANY");

$arParams['USE_ADDR_AUTOCOMPLATE'] = Option::get("tanais.order", "USE_ADDR_AUTOCOMPLATE", "Y");
$arParams['AUTOCOMPLATE_ADDR'] = Option::get("tanais.order", "AUTOCOMPLATE_ADDR", "ADDRESS");

$arParams['ALLOW_USER_PROFILES'] = $arParams['ALLOW_USER_PROFILES'] === 'Y' ? 'Y' : 'N';
$arParams['SKIP_USELESS_BLOCK'] = $arParams['SKIP_USELESS_BLOCK'] === 'N' ? 'N' : 'Y';

$arParams['HIDE_ORDER_DESCRIPTION'] = isset($arParams['HIDE_ORDER_DESCRIPTION']) && $arParams['HIDE_ORDER_DESCRIPTION'] === 'Y' ? 'Y' : 'N';
$arParams['SHOW_TOTAL_ORDER_BUTTON'] = $arParams['SHOW_TOTAL_ORDER_BUTTON'] === 'Y' ? 'Y' : 'N';
$arParams['SHOW_PAY_SYSTEM_LIST_NAMES'] = $arParams['SHOW_PAY_SYSTEM_LIST_NAMES'] === 'N' ? 'N' : 'Y';
$arParams['SHOW_PAY_SYSTEM_INFO_NAME'] = $arParams['SHOW_PAY_SYSTEM_INFO_NAME'] === 'N' ? 'N' : 'Y';
$arParams['SHOW_DELIVERY_LIST_NAMES'] = $arParams['SHOW_DELIVERY_LIST_NAMES'] === 'N' ? 'N' : 'Y';
$arParams['SHOW_DELIVERY_INFO_NAME'] = $arParams['SHOW_DELIVERY_INFO_NAME'] === 'N' ? 'N' : 'Y';
$arParams['SHOW_DELIVERY_PARENT_NAMES'] = $arParams['SHOW_DELIVERY_PARENT_NAMES'] === 'N' ? 'N' : 'Y';
$arParams['SHOW_STORES_IMAGES'] = $arParams['SHOW_STORES_IMAGES'] === 'N' ? 'N' : 'Y';

if (!isset($arParams['BASKET_POSITION']) || !in_array($arParams['BASKET_POSITION'], array('before', 'after')))
{
	$arParams['BASKET_POSITION'] = 'after';
}

$arParams['EMPTY_BASKET_HINT_PATH'] = isset($arParams['EMPTY_BASKET_HINT_PATH']) ? (string)$arParams['EMPTY_BASKET_HINT_PATH'] : '/';
$arParams['SHOW_BASKET_HEADERS'] = $arParams['SHOW_BASKET_HEADERS'] === 'Y' ? 'Y' : 'N';
$arParams['HIDE_DETAIL_PAGE_URL'] = isset($arParams['HIDE_DETAIL_PAGE_URL']) && $arParams['HIDE_DETAIL_PAGE_URL'] === 'Y' ? 'Y' : 'N';
$arParams['DELIVERY_FADE_EXTRA_SERVICES'] = $arParams['DELIVERY_FADE_EXTRA_SERVICES'] === 'Y' ? 'Y' : 'N';

$arParams['SHOW_COUPONS'] = isset($arParams['SHOW_COUPONS']) && $arParams['SHOW_COUPONS'] === 'N' ? 'N' : 'Y';

if ($arParams['SHOW_COUPONS'] === 'N')
{
	$arParams['SHOW_COUPONS_BASKET'] = 'N';
	$arParams['SHOW_COUPONS_DELIVERY'] = 'N';
	$arParams['SHOW_COUPONS_PAY_SYSTEM'] = 'N';
}
else
{
	$arParams['SHOW_COUPONS_BASKET'] = isset($arParams['SHOW_COUPONS_BASKET']) && $arParams['SHOW_COUPONS_BASKET'] === 'N' ? 'N' : 'Y';
	$arParams['SHOW_COUPONS_DELIVERY'] = isset($arParams['SHOW_COUPONS_DELIVERY']) && $arParams['SHOW_COUPONS_DELIVERY'] === 'N' ? 'N' : 'Y';
	$arParams['SHOW_COUPONS_PAY_SYSTEM'] = isset($arParams['SHOW_COUPONS_PAY_SYSTEM']) && $arParams['SHOW_COUPONS_PAY_SYSTEM'] === 'N' ? 'N' : 'Y';
}

$arParams['SHOW_NEAREST_PICKUP'] = $arParams['SHOW_NEAREST_PICKUP'] === 'Y' ? 'Y' : 'N';
$arParams['DELIVERIES_PER_PAGE'] = isset($arParams['DELIVERIES_PER_PAGE']) ? intval($arParams['DELIVERIES_PER_PAGE']) : 9;
$arParams['PAY_SYSTEMS_PER_PAGE'] = isset($arParams['PAY_SYSTEMS_PER_PAGE']) ? intval($arParams['PAY_SYSTEMS_PER_PAGE']) : 9;
$arParams['PICKUPS_PER_PAGE'] = isset($arParams['PICKUPS_PER_PAGE']) ? intval($arParams['PICKUPS_PER_PAGE']) : 5;
$arParams['SHOW_PICKUP_MAP'] = $arParams['SHOW_PICKUP_MAP'] === 'N' ? 'N' : 'Y';
$arParams['SHOW_MAP_IN_PROPS'] = $arParams['SHOW_MAP_IN_PROPS'] === 'Y' ? 'Y' : 'N';
$arParams['USE_YM_GOALS'] = $arParams['USE_YM_GOALS'] === 'Y' ? 'Y' : 'N';
$arParams['USE_ENHANCED_ECOMMERCE'] = isset($arParams['USE_ENHANCED_ECOMMERCE']) && $arParams['USE_ENHANCED_ECOMMERCE'] === 'Y' ? 'Y' : 'N';
$arParams['DATA_LAYER_NAME'] = isset($arParams['DATA_LAYER_NAME']) ? trim($arParams['DATA_LAYER_NAME']) : 'dataLayer';
$arParams['BRAND_PROPERTY'] = isset($arParams['BRAND_PROPERTY']) ? trim($arParams['BRAND_PROPERTY']) : '';

$useDefaultMessages = !isset($arParams['USE_CUSTOM_MAIN_MESSAGES']) || $arParams['USE_CUSTOM_MAIN_MESSAGES'] != 'Y';

$arParams["MESS_TOTAL_BLOCK_BUY_ONE_CLICK_BTN"] = Loc::getMessage("TOTAL_BLOCK_BUY_ONE_CLICK_BTN");
$arParams["MESS_TOTAL_BLOCK_BUY_ONE_CLICK_FORM_UNKNOWN_ERROR"] = Loc::getMessage("TOTAL_BLOCK_BUY_ONE_CLICK_FORM_UNKNOWN_ERROR");
$arParams["MESS_AUTOLOAD_LEGAL_INN_BTN"] = Loc::getMessage("AUTOLOAD_LEGAL_INN_BTN");

if (isset($arResult["JS_DATA"]["GRID"]["ROWS"])) {
	$basketItemsCount = count($arResult["JS_DATA"]["GRID"]["ROWS"]);
	$basketItemsDeclension = new Bitrix\Main\Grid\Declension(
		Loc::getMessage("SOA_SUM_SUMMARY_TEXT_1"),
		Loc::getMessage("SOA_SUM_SUMMARY_TEXT_2"),
		Loc::getMessage("SOA_SUM_SUMMARY_TEXT_3")
	);
	$arParams["MESS_SOA_SUM_SUMMARY"] = Loc::getMessage(
		"SOA_SUM_SUMMARY",
		[
			"#ITEMS_COUNT#" => $basketItemsCount,
			"#PRODUCT_TITLE#" => $basketItemsDeclension->get($basketItemsCount)
		]
	);
}

if ($useDefaultMessages || !isset($arParams['MESS_AUTH_BLOCK_NAME']))
{
	$arParams['MESS_AUTH_BLOCK_NAME'] = Loc::getMessage('AUTH_BLOCK_NAME_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_REG_BLOCK_NAME']))
{
	$arParams['MESS_REG_BLOCK_NAME'] = Loc::getMessage('REG_BLOCK_NAME_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_BASKET_BLOCK_NAME']))
{
	$arParams['MESS_BASKET_BLOCK_NAME'] = Loc::getMessage('BASKET_BLOCK_NAME_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_REGION_BLOCK_NAME']))
{
	$arParams['MESS_REGION_BLOCK_NAME'] = Loc::getMessage('REGION_BLOCK_NAME_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_PAYMENT_BLOCK_NAME']))
{
	$arParams['MESS_PAYMENT_BLOCK_NAME'] = Loc::getMessage('PAYMENT_BLOCK_NAME_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_DELIVERY_BLOCK_NAME']))
{
	$arParams['MESS_DELIVERY_BLOCK_NAME'] = Loc::getMessage('DELIVERY_BLOCK_NAME_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_BUYER_BLOCK_NAME']))
{
	$arParams['MESS_BUYER_BLOCK_NAME'] = Loc::getMessage('BUYER_BLOCK_NAME_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_BACK']))
{
	$arParams['MESS_BACK'] = Loc::getMessage('BACK_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_FURTHER']))
{
	$arParams['MESS_FURTHER'] = Loc::getMessage('FURTHER_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_EDIT']))
{
	$arParams['MESS_EDIT'] = Loc::getMessage('EDIT_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_ORDER']))
{
	$arParams['MESS_ORDER'] = $arParams['~MESS_ORDER'] = Loc::getMessage('ORDER_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_PRICE']))
{
	$arParams['MESS_PRICE'] = Loc::getMessage('PRICE_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_PERIOD']))
{
	$arParams['MESS_PERIOD'] = Loc::getMessage('PERIOD_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_NAV_BACK']))
{
	$arParams['MESS_NAV_BACK'] = Loc::getMessage('NAV_BACK_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_NAV_FORWARD']))
{
	$arParams['MESS_NAV_FORWARD'] = Loc::getMessage('NAV_FORWARD_DEFAULT');
}

$useDefaultMessages = !isset($arParams['USE_CUSTOM_ADDITIONAL_MESSAGES']) || $arParams['USE_CUSTOM_ADDITIONAL_MESSAGES'] != 'Y';

if ($useDefaultMessages || !isset($arParams['MESS_PRICE_FREE']))
{
	$arParams['MESS_PRICE_FREE'] = Loc::getMessage('PRICE_FREE_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_ECONOMY']))
{
	$arParams['MESS_ECONOMY'] = Loc::getMessage('ECONOMY_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_REGISTRATION_REFERENCE']))
{
	$arParams['MESS_REGISTRATION_REFERENCE'] = Loc::getMessage('REGISTRATION_REFERENCE_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_AUTH_REFERENCE_1']))
{
	$arParams['MESS_AUTH_REFERENCE_1'] = Loc::getMessage('AUTH_REFERENCE_1_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_AUTH_REFERENCE_2']))
{
	$arParams['MESS_AUTH_REFERENCE_2'] = Loc::getMessage('AUTH_REFERENCE_2_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_AUTH_REFERENCE_3']))
{
	$arParams['MESS_AUTH_REFERENCE_3'] = Loc::getMessage('AUTH_REFERENCE_3_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_ADDITIONAL_PROPS']))
{
	$arParams['MESS_ADDITIONAL_PROPS'] = Loc::getMessage('ADDITIONAL_PROPS_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_USE_COUPON']))
{
	$arParams['MESS_USE_COUPON'] = Loc::getMessage('USE_COUPON_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_COUPON']))
{
	$arParams['MESS_COUPON'] = Loc::getMessage('COUPON_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_PERSON_TYPE']))
{
	$arParams['MESS_PERSON_TYPE'] = Loc::getMessage('PERSON_TYPE_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_SELECT_PROFILE']))
{
	$arParams['MESS_SELECT_PROFILE'] = Loc::getMessage('SELECT_PROFILE_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_REGION_REFERENCE']))
{
	$arParams['MESS_REGION_REFERENCE'] = Loc::getMessage('REGION_REFERENCE_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_PICKUP_LIST']))
{
	$arParams['MESS_PICKUP_LIST'] = Loc::getMessage('PICKUP_LIST_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_NEAREST_PICKUP_LIST']))
{
	$arParams['MESS_NEAREST_PICKUP_LIST'] = Loc::getMessage('NEAREST_PICKUP_LIST_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_SELECT_PICKUP']))
{
	$arParams['MESS_SELECT_PICKUP'] = Loc::getMessage('SELECT_PICKUP_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_INNER_PS_BALANCE']))
{
	$arParams['MESS_INNER_PS_BALANCE'] = Loc::getMessage('INNER_PS_BALANCE_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_ORDER_DESC']))
{
	$arParams['MESS_ORDER_DESC'] = Loc::getMessage('ORDER_DESC_DEFAULT');
}

$useDefaultMessages = !isset($arParams['USE_CUSTOM_ERROR_MESSAGES']) || $arParams['USE_CUSTOM_ERROR_MESSAGES'] != 'Y';

if ($useDefaultMessages || !isset($arParams['MESS_PRELOAD_ORDER_TITLE']))
{
	$arParams['MESS_PRELOAD_ORDER_TITLE'] = Loc::getMessage('PRELOAD_ORDER_TITLE_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_SUCCESS_PRELOAD_TEXT']))
{
	$arParams['MESS_SUCCESS_PRELOAD_TEXT'] = Loc::getMessage('SUCCESS_PRELOAD_TEXT_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_FAIL_PRELOAD_TEXT']))
{
	$arParams['MESS_FAIL_PRELOAD_TEXT'] = Loc::getMessage('FAIL_PRELOAD_TEXT_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_DELIVERY_CALC_ERROR_TITLE']))
{
	$arParams['MESS_DELIVERY_CALC_ERROR_TITLE'] = Loc::getMessage('DELIVERY_CALC_ERROR_TITLE_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_DELIVERY_CALC_ERROR_TEXT']))
{
	$arParams['MESS_DELIVERY_CALC_ERROR_TEXT'] = Loc::getMessage('DELIVERY_CALC_ERROR_TEXT_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_PAY_SYSTEM_PAYABLE_ERROR']))
{
	$arParams['MESS_PAY_SYSTEM_PAYABLE_ERROR'] = Loc::getMessage('PAY_SYSTEM_PAYABLE_ERROR_DEFAULT');
}

$scheme = $request->isHttps() ? 'https' : 'http';

switch (LANGUAGE_ID)
{
	case 'ru':
		$locale = 'ru-RU'; break;
	case 'ua':
		$locale = 'ru-UA'; break;
	case 'tk':
		$locale = 'tr-TR'; break;
	default:
		$locale = 'en-US'; break;
}

$APPLICATION->SetAdditionalCSS($templateFolder . '/themes/colors/' . $arParams['ORDER_THEME'] . '.css');
$APPLICATION->SetAdditionalCSS($templateFolder.'/style.css', true);
$this->addExternalJs($templateFolder.'/order_ajax.js');
\Bitrix\Sale\PropertyValueCollection::initJs();
$this->addExternalJs($templateFolder . '/scripts/jquery.maskedinput.min.js');
$this->addExternalJs($templateFolder.'/script.js');

if ($arParams['ORDER_THEME'] == "other") { ?>
	<style>
		.tanais-order {
		  --color-primary-one-h: <?=$arResult['THEME']["COLOR_FIRST"]['h']?>;
		  --color-primary-one-s: <?=$arResult['THEME']["COLOR_FIRST"]['s']?>%;
		  --color-primary-one-l: <?=$arResult['THEME']["COLOR_FIRST"]['l']?>%;

		  --color-primary-two-h: <?=$arResult['THEME']["COLOR_SECOND"]['h']?>;
		  --color-primary-two-s: <?=$arResult['THEME']["COLOR_SECOND"]['s']?>%;
		  --color-primary-two-l: <?=$arResult['THEME']["COLOR_SECOND"]['l']?>%;

		  --color-block-header: <?=$arResult['THEME']["COLOR_HEADER"]?>;
		}
	</style>
<?php } ?>
	<NOSCRIPT>
		<div style="color:red"><?=Loc::getMessage('SOA_NO_JS')?></div>
	</NOSCRIPT>
<?

if ($request->get('ORDER_ID') <> '')
{
	include(Main\Application::getDocumentRoot().$templateFolder.'/confirm.php');
}
elseif ($arParams['DISABLE_BASKET_REDIRECT'] === 'Y' && $arResult['SHOW_EMPTY_BASKET'])
{
	include(Main\Application::getDocumentRoot().$templateFolder.'/empty.php');
}
else
{
	Main\UI\Extension::load('phone_auth');

	$hideDelivery = empty($arResult['DELIVERY']);
	?>
	<form action="<?=POST_FORM_ACTION_URI?>" method="POST" name="ORDER_FORM" class="tanais-order" id="bx-soa-tanais-order-form" enctype="multipart/form-data">
		<?
		echo bitrix_sessid_post();

		if ($arResult['PREPAY_ADIT_FIELDS'] <> '')
		{
			echo $arResult['PREPAY_ADIT_FIELDS'];
		}
		?>
		<input type="hidden" name="<?=$arParams['ACTION_VARIABLE']?>" value="saveOrderAjax">
		<input type="hidden" name="location_type" value="code">
		<input type="hidden" name="BUYER_STORE" id="BUYER_STORE" value="<?=$arResult['BUYER_STORE']?>">
		<div id="bx-soa-tanais-order" class="tanais-flex-block" style="opacity: 0">
			<!--	MAIN BLOCK	-->
			<div class="tanais-main-block bx-soa">

				<?if($arParams["USE_SALE_BASKET_TO_ORDER_PAGE"] == "Y"):?>
					<div id="bx-soa-real-basket">
						<div class="bx-soa-section bx-active bx-step-completed soa-border-bottom-radius-none soa-box-shadow-none">
							<div class="bx-soa-section-title-container">
								<h2 class="bx-soa-section-title">
									<span class="bx-soa-section-title-icon">
										<? System::showImgFile("/blocks/basket.svg") ?>
									</span>
									<div class="bx-soa-section-title-text">
										<?=Loc::getMessage("HEADER_LINE_TITLE")?>
									</div>
								</h2>
							</div>
						</div>

						<div class="bx-soa-header-panel">
							<div class="bx-soa-header-panel-buttons">
								<a href="<?=$arParams['LINK_TO_CATALOG']?>" class="btn btn-outline-primary soa-back-to-catalog-link"><i>←</i> <?=Loc::getMessage("HEADER_BUTTONS_BACK_TO_CATALOG")?></a>
								<?if($arParams["ALLOW_BASKET_SPEC_TO_PRINT"] == "Y"):?>
									<a class="soa-icon-link soa-icon-print soa-action-print" title="<?=Loc::getMessage("HEADER_BUTTONS_TO_PRINT")?>">
										<? System::showImgFile("/print.svg") ?>
									</a>
								<?endif;?>
								<?if($arParams["ALLOW_BASKET_SPEC_TO_DOWNLOAD_PDF"] == "Y"):?>
									<a class="soa-icon-link soa-icon-save-to-pdf soa-action-save-to-pdf" title="<?=Loc::getMessage("HEADER_BUTTONS_SAVE_TO_PDF")?>">
										<? System::showImgFile("/save_to_pdf.svg") ?>
									</a>
								<?endif;?>
							</div>
						</div>

						<? require_once "basket.php"; ?>
					</div>
				<?endif;?>

				<div id="bx-soa-main-notifications">
					<div class="alert alert-danger" style="display:none"></div>
					<div data-type="informer" style="display:none"></div>
				</div>
				<!--	AUTH BLOCK	-->
				<div id="bx-soa-auth" class="bx-soa-section bx-soa-auth" style="display:none">
					<div class="bx-soa-section-title-container">
						<h2 class="bx-soa-section-title">
							<span class="bx-soa-section-title-icon"></span><?=$arParams['MESS_AUTH_BLOCK_NAME']?>
						</h2>
					</div>
					<div class="bx-soa-section-content container-fluid"></div>
				</div>

				<!--	DUPLICATE MOBILE ORDER SAVE BLOCK	-->
				<div id="bx-soa-total-mobile" style="margin-bottom: 6px;"></div>

				<? if ($arParams["USE_SALE_BASKET_TO_ORDER_PAGE"] != "Y" && $arParams['BASKET_POSITION'] === 'before'): ?>
					<!--	BASKET ITEMS BLOCK	-->
					<div id="bx-soa-basket" data-visited="false" class="bx-soa-section bx-active">
						<div class="bx-soa-section-title-container">
							<h2 class="bx-soa-section-title">
								<span class="bx-soa-section-title-icon">
									<? System::showImgFile("/blocks/basket.svg") ?>
								</span>
								<div class="bx-soa-section-title-text">
									<?=$arParams['MESS_BASKET_BLOCK_NAME']?>
								</div>
							</h2>
						</div>
						<div class="bx-soa-section-content container-fluid"></div>
					</div>
				<? endif ?>

				<!--	REGION BLOCK	-->
				<div id="bx-soa-region" data-visited="false" class="bx-soa-section bx-active">
					<div class="bx-soa-section-title-container">
						<h2 class="bx-soa-section-title">
							<span class="bx-soa-section-title-icon">
								<? System::showImgFile("/blocks/location.svg") ?>
							</span>
							<div class="bx-soa-section-title-text">
								<?=$arParams['MESS_REGION_BLOCK_NAME']?>
							</div>
						</h2>
					</div>
					<div class="bx-soa-section-content container-fluid"></div>
				</div>

		
				<!--	BUYER PROPS BLOCK	-->
				<div id="bx-soa-properties" data-visited="false" class="bx-soa-section bx-active">
					<div class="bx-soa-section-title-container">
						<h2 class="bx-soa-section-title">
							<span class="bx-soa-section-title-icon">
								<? System::showImgFile("/blocks/personal.svg") ?>
							</span>
							<div class="bx-soa-section-title-text">
								<?=$arParams['MESS_BUYER_BLOCK_NAME']?>
							</div>
						</h2>
					</div>
					<div class="bx-soa-section-content container-fluid"></div>
				</div>


<div class="single-container <?=($hideDelivery ? ' one-child' : '')?>">
					<? if ($arParams['DELIVERY_TO_PAYSYSTEM'] === 'p2d'): ?>
						<!--	PAY SYSTEMS BLOCK	-->
						<div id="bx-soa-paysystem" data-visited="false" class="bx-soa-section bx-active">
							<div class="bx-soa-section-title-container">
								<h2 class="bx-soa-section-title">
									<span class="bx-soa-section-title-icon">
										<? System::showImgFile("/blocks/payment.svg") ?>
									</span>
									<div class="bx-soa-section-title-text">
										<?=$arParams['MESS_PAYMENT_BLOCK_NAME']?>
									</div>
								</h2>
							</div>
							<div class="bx-soa-section-content container-fluid"></div>
						</div>
						<!--	DELIVERY BLOCK	-->
						<div id="bx-soa-delivery" data-visited="false" class="bx-soa-section bx-active" <?=($hideDelivery ? 'style="display:none"' : '')?>>
							<div class="bx-soa-section-title-container">
								<h2 class="bx-soa-section-title">
									<span class="bx-soa-section-title-icon">
										<? System::showImgFile("/blocks/delivery.svg") ?>
									</span>
									<div class="bx-soa-section-title-text">
										<?=$arParams['MESS_DELIVERY_BLOCK_NAME']?>
									</div>
								</h2>
							</div>
							<div class="bx-soa-section-content container-fluid"></div>
						</div>
						<!--	PICKUP BLOCK	-->
						<div id="bx-soa-pickup" data-visited="false" class="bx-soa-section" style="display:none">
							<div class="bx-soa-section-title-container">
								<h2 class="bx-soa-section-title">
									<span class="bx-soa-section-title-icon">
										<? System::showImgFile("/blocks/pickup.svg") ?>
									</span>
									<div class="bx-soa-section-title-text">
										<?=$arParams['MESS_PAYMENT_BLOCK_NAME']?>
									</div>
								</h2>
							</div>
							<div class="bx-soa-section-content container-fluid"></div>
						</div>
					<? else: ?>
						<!--	DELIVERY BLOCK	-->
						<div id="bx-soa-delivery" data-visited="false" class="bx-soa-section bx-active" <?=($hideDelivery ? 'style="display:none"' : '')?>>
							<div class="bx-soa-section-title-container">
								<h2 class="bx-soa-section-title">
									<span class="bx-soa-section-title-icon">
										<? System::showImgFile("/blocks/delivery.svg") ?>
									</span>
									<div class="bx-soa-section-title-text">
										<?=$arParams['MESS_DELIVERY_BLOCK_NAME']?>
									</div>
								</h2>
							</div>
							<div class="bx-soa-section-content container-fluid"></div>
						</div>
						<!--	PAY SYSTEMS BLOCK	-->
						<div id="bx-soa-paysystem" data-visited="false" class="bx-soa-section bx-active">
							<div class="bx-soa-section-title-container">
								<h2 class="bx-soa-section-title">
									<span class="bx-soa-section-title-icon">
										<? System::showImgFile("/blocks/payment.svg") ?>
									</span>
									<div class="bx-soa-section-title-text">
										<?=$arParams['MESS_PAYMENT_BLOCK_NAME']?>
									</div>
								</h2>
							</div>
							<div class="bx-soa-section-content container-fluid"></div>
						</div>
						<!--	PICKUP BLOCK	-->
						<div id="bx-soa-pickup" data-visited="false" class="bx-soa-section" style="display:none">
							<div class="bx-soa-section-title-container">
								<h2 class="bx-soa-section-title">
									<span class="bx-soa-section-title-icon">
										<? System::showImgFile("/blocks/pickup.svg") ?>
									</span>
									<div class="bx-soa-section-title-text">
										<?=$arParams['MESS_PAYMENT_BLOCK_NAME']?>
									</div>
								</h2>
							</div>
							<div class="bx-soa-section-content container-fluid"></div>
						</div>
					<? endif ?>
				</div>
                
				<? if ($arParams["USE_SALE_BASKET_TO_ORDER_PAGE"] != "Y" && $arParams['BASKET_POSITION'] === 'after'): ?>
					<!--	BASKET ITEMS BLOCK	-->
					<div id="bx-soa-basket" data-visited="false" class="bx-soa-section bx-active">
						<div class="bx-soa-section-title-container">
							<h2 class="bx-soa-section-title">
								<span class="bx-soa-section-title-icon">
									<? System::showImgFile("/blocks/basket.svg") ?>
								</span>
								<div class="bx-soa-section-title-text">
									<?=$arParams['MESS_BASKET_BLOCK_NAME']?>
								</div>
							</h2>
						</div>
						<div class="bx-soa-section-content container-fluid"></div>
					</div>
				<? endif ?>

				<!--	ORDER SAVE BLOCK	-->
                <center>
				<div id="bx-soa-orderSave">
					<div class="checkbox">
						<?
						if ($arParams['USER_CONSENT'] === 'Y')
						{
							$APPLICATION->IncludeComponent(
								'bitrix:main.userconsent.request',
								'',
								array(
									'ID' => $arParams['USER_CONSENT_ID'],
									'IS_CHECKED' => $arParams['USER_CONSENT_IS_CHECKED'],
									'IS_LOADED' => $arParams['USER_CONSENT_IS_LOADED'],
									'AUTO_SAVE' => 'N',
									'SUBMIT_EVENT_NAME' => 'bx-soa-order-save',
									'REPLACE' => array(
										'button_caption' => isset($arParams['~MESS_ORDER']) ? $arParams['~MESS_ORDER'] : $arParams['MESS_ORDER'],
										'fields' => $arResult['USER_CONSENT_PROPERTY_DATA']
									)
								)
							);
						}
						?>
					</div>
					<a href="javascript:void(0)" style="margin: 10px 0" class="btn btn-default" data-save-button="true">
						<?=$arParams['MESS_ORDER']?>
					</a>
				</div>
                     </center>

				<div style="display: none;">
					<div id='bx-soa-basket-hidden' class="bx-soa-section"></div>
					<div id='bx-soa-region-hidden' class="bx-soa-section"></div>
					<div id='bx-soa-paysystem-hidden' class="bx-soa-section"></div>
					<div id='bx-soa-delivery-hidden' class="bx-soa-section"></div>
					<div id='bx-soa-pickup-hidden' class="bx-soa-section"></div>
					<div id="bx-soa-properties-hidden" class="bx-soa-section"></div>
					<div id="bx-soa-auth-hidden" class="bx-soa-section">
						<div class="bx-soa-section-content container-fluid reg"></div>
					</div>
				</div>
			</div>

			<!--	SIDEBAR BLOCK	-->
			<div id="bx-soa-total" class="tanais-sidebar-block bx-soa-sidebar">
				<div class="bx-soa-cart-total-ghost"></div>
				<div class="bx-soa-cart-total">
					<div class="bx-soa-cart-total-data"></div>
					<?if($arParams['USE_BUY_ONE_CLICK'] == "Y"):?>
						<div class="bx-soa-cart-total-form">
							<a onclick="BX.Sale.OrderAjaxComponent.buyOneClickFormHide();" class="bx-soa-cart-total-form-close-btn"></a>
							<div class="bx-soa-cart-total-form-title">
								<p><?=Loc::getMessage("TOTAL_BLOCK_BUY_ONE_CLICK_FORM_TITLE")?></p>
							</div>
							<div class="bx-soa-cart-total-form-message-box"></div>
							<div class="bx-soa-cart-total-form-field">
								<span><?=Loc::getMessage("TOTAL_BLOCK_BUY_ONE_CLICK_FORM_FIELD_NAME")?>: <i>*</i></span>
								<input type="text" required class="tanais-form-control" name="full_name" />
							</div>
							<div class="bx-soa-cart-total-form-field">
								<span><?=Loc::getMessage("TOTAL_BLOCK_BUY_ONE_CLICK_FORM_FIELD_PHONE")?>: <i>*</i></span>
								<input type="text" required class="tanais-form-control" name="phone" autocomplete="tel" />
							</div>
							<div class="bx-soa-cart-total-form-field">
								<span><?=Loc::getMessage("TOTAL_BLOCK_BUY_ONE_CLICK_FORM_FIELD_EMAIL")?>: <i>*</i></span>
								<input type="email" required class="tanais-form-control" name="email" />
							</div>
							<?if($arParams["BUY_ONE_CLICK_SHOW_USER_DESCRIPTION"] == "Y"):?>
								<div class="bx-soa-cart-total-form-field">
									<span><?=Loc::getMessage("TOTAL_BLOCK_BUY_ONE_CLICK_FORM_FIELD_COMMENT")?>:</span>
									<textarea name="comment" class="tanais-form-control"></textarea>
								</div>
							<?endif;?>
							<?if($arParams["BUY_ONE_CLICK_USE_CAPTCHA"] == "Y"):
									require_once $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/classes/general/captcha.php";
									$captcha = new CCaptcha();
									$captchaPass = Option::get("main", "captcha_password", "");

									if(strlen($captchaPass) <= 0) {
										$captchaPass = randString(13);
										Option::set("main", "captcha_password", $captchaPass);
									}

									$captcha->SetCodeCrypt($captchaPass);
								?>
								<div class="bx-soa-cart-total-form-field soa-buy-one-click-captcha-block">
									<span><?=Loc::getMessage("TOTAL_BLOCK_BUY_ONE_CLICK_FORM_FIELD_CAPTCHA")?>: <i>*</i></span>
									<input name="captcha_code" value="<?=htmlspecialchars($captcha->GetCodeCrypt());?>" type="hidden">
									<input required name="captcha_word" class="tanais-form-control" type="text">
									<img src="/bitrix/tools/captcha.php?captcha_code=<?=htmlspecialchars($captcha->GetCodeCrypt());?>">
								</div>
							<?endif;?>
							<a onclick="BX.Sale.OrderAjaxComponent.buyOneClickFormSend(this);" class="btn btn-default">
								<?=Loc::getMessage("TOTAL_BLOCK_BUY_ONE_CLICK_FORM_BTN")?>
							</a>
						</div>
					<?endif;?>
				</div>
			</div>
		</div>
	</form>

	<div id="bx-soa-saved-files" style="display:none"></div>
	<div id="bx-soa-soc-auth-services" style="display:none">
		<?
		$arServices = false;
		$arResult['ALLOW_SOCSERV_AUTHORIZATION'] = Main\Config\Option::get('main', 'allow_socserv_authorization', 'Y') != 'N' ? 'Y' : 'N';
		$arResult['FOR_INTRANET'] = false;

		if (Main\ModuleManager::isModuleInstalled('intranet') || Main\ModuleManager::isModuleInstalled('rest'))
			$arResult['FOR_INTRANET'] = true;

		if (Main\Loader::includeModule('socialservices') && $arResult['ALLOW_SOCSERV_AUTHORIZATION'] === 'Y')
		{
			$oAuthManager = new CSocServAuthManager();
			$arServices = $oAuthManager->GetActiveAuthServices(array(
				'BACKURL' => $this->arParams['~CURRENT_PAGE'],
				'FOR_INTRANET' => $arResult['FOR_INTRANET'],
			));

			if (!empty($arServices))
			{
				$APPLICATION->IncludeComponent(
					'bitrix:socserv.auth.form',
					'flat',
					array(
						'AUTH_SERVICES' => $arServices,
						'AUTH_URL' => $arParams['~CURRENT_PAGE'],
						'POST' => $arResult['POST'],
					),
					$component,
					array('HIDE_ICONS' => 'Y')
				);
			}
		}
		?>
	</div>

	<div style="display: none">
		<?
		// we need to have all styles for sale.location.selector.steps, but RestartBuffer() cuts off document head with styles in it
		$APPLICATION->IncludeComponent(
			'bitrix:sale.location.selector.steps',
			'.default',
			array(),
			false
		);
		$APPLICATION->IncludeComponent(
			'bitrix:sale.location.selector.search',
			'.default',
			array(),
			false
		);
		?>
	</div>
	<?
	$signer = new Main\Security\Sign\Signer;
	$signedParams = $signer->sign(base64_encode(serialize($arParams)), 'sale.order.ajax');
	$messages = Loc::loadLanguageFile(__FILE__);
	?>
	<script>
		BX.message(<?=CUtil::PhpToJSObject($messages)?>);
		BX.Sale.OrderAjaxComponent.init({
			result: <?=CUtil::PhpToJSObject($arResult['JS_DATA'])?>,
			locations: <?=CUtil::PhpToJSObject($arResult['LOCATIONS'])?>,
			params: <?=CUtil::PhpToJSObject($arParams)?>,
			signedParamsString: '<?=CUtil::JSEscape($signedParams)?>',
			siteID: '<?=CUtil::JSEscape($component->getSiteId())?>',
			ajaxUrl: '<?=CUtil::JSEscape($component->getPath().'/ajax.php')?>',
			templateFolder: '<?=CUtil::JSEscape($templateFolder)?>',
			imgFolder: '<?=CUtil::JSEscape(System::getModuleImg())?>',
			propertyValidation: true,
			showWarnings: true,
			pickUpMap: {
				defaultMapPosition: {
					lat: 55.76,
					lon: 37.64,
					zoom: 7
				},
				secureGeoLocation: false,
				geoLocationMaxTime: 5000,
				minToShowNearestBlock: 3,
				nearestPickUpsToShow: 3
			},
			propertyMap: {
				defaultMapPosition: {
					lat: 55.76,
					lon: 37.64,
					zoom: 7
				}
			},
			orderBlockId: 'bx-soa-tanais-order',
			authBlockId: 'bx-soa-auth',
			basketBlockId: 'bx-soa-basket',
			regionBlockId: 'bx-soa-region',
			paySystemBlockId: 'bx-soa-paysystem',
			deliveryBlockId: 'bx-soa-delivery',
			pickUpBlockId: 'bx-soa-pickup',
			propsBlockId: 'bx-soa-properties',
			totalBlockId: 'bx-soa-total'
		});
	</script>
	<script>
		<?
		// spike: for children of cities we place this prompt
		$city = \Bitrix\Sale\Location\TypeTable::getList(array('filter' => array('=CODE' => 'CITY'), 'select' => array('ID')))->fetch();
		?>
		BX.saleOrderAjax.init(<?=CUtil::PhpToJSObject(array(
			'source' => $component->getPath().'/get.php',
			'cityTypeId' => intval($city['ID']),
			'messages' => array(
				'otherLocation' => '--- '.Loc::getMessage('SOA_OTHER_LOCATION'),
				'moreInfoLocation' => '--- '.Loc::getMessage('SOA_NOT_SELECTED_ALT'), // spike: for children of cities we place this prompt
				'notFoundPrompt' => '<div class="-bx-popup-special-prompt">'.Loc::getMessage('SOA_LOCATION_NOT_FOUND').'.<br />'.Loc::getMessage('SOA_LOCATION_NOT_FOUND_PROMPT', array(
						'#ANCHOR#' => '<a href="javascript:void(0)" class="-bx-popup-set-mode-add-loc">',
						'#ANCHOR_END#' => '</a>'
					)).'</div>'
			)
		))?>);
	</script>
	<?
	if ($arParams['SHOW_PICKUP_MAP'] === 'Y' || $arParams['SHOW_MAP_IN_PROPS'] === 'Y')
	{
		if ($arParams['PICKUP_MAP_TYPE'] === 'yandex')
		{
			$this->addExternalJs($templateFolder.'/scripts/yandex_maps.js');
			$apiKey = htmlspecialcharsbx(Main\Config\Option::get('fileman', 'yandex_map_api_key', ''));
			?>
			<script src="<?=$scheme?>://api-maps.yandex.ru/2.1.50/?apikey=<?=$apiKey?>&load=package.full&lang=<?=$locale?>"></script>
			<script>
				(function bx_ymaps_waiter(){
					if (typeof ymaps !== 'undefined' && BX.Sale && BX.Sale.OrderAjaxComponent)
						ymaps.ready(BX.proxy(BX.Sale.OrderAjaxComponent.initMaps, BX.Sale.OrderAjaxComponent));
					else
						setTimeout(bx_ymaps_waiter, 100);
				})();
			</script>
			<?
		}

		if ($arParams['PICKUP_MAP_TYPE'] === 'google')
		{
			$this->addExternalJs($templateFolder.'/scripts/google_maps.js');
			$apiKey = htmlspecialcharsbx(Main\Config\Option::get('fileman', 'google_map_api_key', ''));
			?>
			<script async defer
				src="<?=$scheme?>://maps.googleapis.com/maps/api/js?key=<?=$apiKey?>&callback=bx_gmaps_waiter">
			</script>
			<script>
				function bx_gmaps_waiter()
				{
					if (BX.Sale && BX.Sale.OrderAjaxComponent)
						BX.Sale.OrderAjaxComponent.initMaps();
					else
						setTimeout(bx_gmaps_waiter, 100);
				}
			</script>
			<?
		}
	}

	if ($arParams['USE_YM_GOALS'] === 'Y')
	{
		?>
		<script>
			(function bx_counter_waiter(i){
				i = i || 0;
				if (i > 50)
					return;

				if (typeof window['yaCounter<?=$arParams['YM_GOALS_COUNTER']?>'] !== 'undefined')
					BX.Sale.OrderAjaxComponent.reachGoal('initialization');
				else
					setTimeout(function(){bx_counter_waiter(++i)}, 100);
			})();
		</script>
		<?
	}
}
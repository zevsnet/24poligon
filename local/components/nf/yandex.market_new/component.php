<?
use Bitrix\Main\Loader;
use Yenisite\Core\Ajax;

@set_time_limit(0);
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$isConsole = PHP_SAPI == 'cli' || (!isset($_SERVER['DOCUMENT_ROOT']) && !isset($_SERVER['REQUEST_URI']));
$isAgent = $isConsole || $_GET['agent'] == 'y';

define('DESCRIPTION_SIZE', 511);

if (!Loader::includeModule("iblock")) {
	echo GetMessage('IBLOCK_MODULE_NOT_INSTALLED');
	return;
}
if (!Loader::includeModule("yenisite.core")) {
	echo GetMessage('CORE_MODULE_NOT_INSTALLED');
	return;
}

/**
 * @var array $arParams
 * @var CMain $APPLICATION
 * @var CBitrixComponent $this
 */

global $bCatalog;
global $bCurrency;
$bCatalog = Loader::includeModule('catalog');
$bCurrency = Loader::includeModule('currency');

$arParams['PRICE_FROM_IBLOCK'] = $arParams['IBLOCK_CATALOG'] == 'Y' ? 'N' : $arParams['PRICE_FROM_IBLOCK'];
$bPriceFromProp = ($arParams['PRICE_FROM_IBLOCK'] == 'Y' && $arParams['PRICE_CODE'] != '');
$arParams["OLD_PRICE_LIST"] = $bPriceFromProp ? "PROP_PRICE" : $arParams["OLD_PRICE_LIST"];
$arParams['ONE_STEP_COUNT'] = (int)$arParams['ONE_STEP_COUNT'];
$arParams['USE_AGENT'] = $arParams['USE_AGENT'] == 'Y';
$arParams['AGENT_TIME'] = (int)$arParams['AGENT_TIME'] ?: 86400;
$arParams['UTM_PROP'] = trim($arParams['UTM_PROP']);
if ($arParams['UTM_PROP'][0] == '?') {
	$arParams['UTM_PROP'] = substr($arParams['UTM_PROP'], 1);
}

/*************************************************************************
 * Processing of received parameters
 *************************************************************************/

if ($componentTemplate == 'Realty_YRL') {
	$arParams['IBLOCK_ORDER'] = 'Y';
}

if (!isset($arParams["DO_NOT_INCLUDE_SUBSECTIONS"])) {
	$arParams["DO_NOT_INCLUDE_SUBSECTIONS"] = "N";
}

if (!is_array($arParams["PROPERTY_CODE"])) {
	$arParams["PROPERTY_CODE"] = array();
}

if (!$arParams['SKU_PROPERTY']) {
	$arParams['SKU_PROPERTY'] = 'PROPERTY_CML2_LINK';
}


$arParams['SKU_PROPERTY'] = strtoupper($arParams['SKU_PROPERTY']);

$arProperty = null;

foreach ($arParams["PROPERTY_CODE"] as $key => $value) {
	if ($value === "") {
		unset($arParams["PROPERTY_CODE"][$key]);
	} else {
		$arProperty[] = "PROPERTY_" . trim($value);
	}
}

if ($arParams['IBLOCK_AS_CATEGORY'] != 'N') {
	$arParams['IBLOCK_AS_CATEGORY'] = 'Y';
}


$arParams["IBLOCK_TYPE"] = trim($arParams["IBLOCK_TYPE"]);

$arParams["COMPANY"] = trim($arParams["COMPANY"]);

if (!is_array($arParams["IBLOCK_ID_IN"])) {
	$arParams["IBLOCK_ID_IN"] = array();
}
foreach ($arParams["IBLOCK_ID_IN"] as $k => $v) {
	if ($v === "") {
		unset($arParams["IBLOCK_ID_IN"][$k]);
	}
}

if ((count($arParams["IBLOCK_ID_IN"]) > 0 && $arParams["IBLOCK_ID_IN"][0] === '0')) {
	$arParams["IBLOCK_ID_IN"] = '';
}


if (!is_array($arParams["IBLOCK_ID_EX"])) {
	$arParams["IBLOCK_ID_EX"] = array();
}
foreach ($arParams["IBLOCK_ID_EX"] as $k => $v) {
	if ($v === "") {
		unset($arParams["IBLOCK_ID_EX"][$k]);
	}
}

if (strlen($arParams["FILTER_NAME"]) <= 0 || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"])) {
	$arrFilter = array();
} else {
	$arrFilter = $GLOBALS[$arParams['FILTER_NAME']];
	if (!is_array($arrFilter)) {
		$arrFilter = array();
	}
}

if (strlen($arParams["SKU_FILTER_NAME"]) <= 0 || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["SKU_FILTER_NAME"])) {
	$arrSKUFilter = array();
} else {
	$arrSKUFilter = $GLOBALS[$arParams['SKU_FILTER_NAME']];
	if (!is_array($arrSKUFilter)) {
		$arrSKUFilter = array();
	}
}

if ($arParams["SHOW_PRICE_COUNT"] <= 0) {
	$arParams["SHOW_PRICE_COUNT"] = 1;
}

$arParams["PRICE_VAT_INCLUDE"] = $arParams["PRICE_VAT_INCLUDE"] !== "N";

if (empty($arParams["DISCOUNTS"])) $arParams["DISCOUNTS"] = "DISCOUNT_CUSTOM";

if (!function_exists("getBaseCurrencyTempl")) {
	function getBaseCurrencyTempl()
	{
		if (Loader::includeModule('currency')) {
			$res = CCurrency::GetList($by = "name", $order = "asc", "RU");
			while ($arRes = $res->Fetch()) {
				if ($arRes["AMOUNT"] == 1) {
					return $arRes["CURRENCY"];
				}
			}
		}
		return null;
	}
}


if (!function_exists("yandex_replace_special")) {
	function yandex_replace_special($arg)
	{
		$ent = html_entity_decode($arg[0], ENT_QUOTES, LANG_CHARSET);

		if ($ent == $arg[0]) return '';
		return $ent;
	}
}

if (!function_exists("yandex_text2xml")) {
	function yandex_text2xml($text, $bHSC = true, $bDblQuote = false)
	{
		$bDblQuote = (true == $bDblQuote ? true : false);
		$text = strip_tags($text);
		$text = strlen($text) > 175 ? substr($text, 0, 175): $text;
		if ($bHSC) {
			$text = htmlspecialcharsBx($text);
			if ($bDblQuote) {
				$text = str_replace('&quot;', '"', $text);
			}
		}
		$text = preg_replace("/[\x1-\x8\xB-\xC\xE-\x1F]/", "", $text);
		$text = str_replace("'", "&apos;", $text);
		return $text;
	}
}

if ($arParams["DISCOUNTS"] == "PRICE_ONLY") {
	function yenisite_yandex_GetPrice($product_id, &$arPrices, &$arOffers, $bConvert = false)
	{
		$arOffers[$product_id]["PRICE"] = 0;
		foreach ($arPrices as $arProductPrice) {
			if ($arProductPrice['PRICE'] && ($arProductPrice['PRICE'] < $arOffers[$product_id]["PRICE"] || !$arOffers[$product_id]["PRICE"])) {
				$arOffers[$product_id]["PRICE"] = $arProductPrice['PRICE'];
				$arOffers[$product_id]["CURRENCY"] = $arProductPrice["CURRENCY"];
			}
		}
	}
} elseif ($arParams["DISCOUNTS"] == "DISCOUNT_CUSTOM") {
	$arUserGroups = $GLOBALS["USER"]->GetUserGroupArray();

	function yenisite_yandex_GetPrice($product_id, &$arPrices, &$arOffers, $bConvert = false)
	{
		global $arUserGroups;
		$price = 0;
		$price_not_discount = 0;
		foreach ($arPrices as &$arProductPrice) {
			if ($arProductPrice['PRICE'] && ($arProductPrice['PRICE'] < $price || !$price)) {
				$price = $arProductPrice['PRICE'];
				$arOffers[$product_id]["OLD_CURENCY"] = $arOffers[$product_id]["CURRENCY"];
				$arOffers[$product_id]["CURRENCY"] = $arProductPrice["CURRENCY"];
				$price_not_discount = $arProductPrice['PRICE'];
			}

			$arDiscounts = CCatalogDiscount::GetDiscountByProduct($product_id, $arUserGroups, "N", $arProductPrice['CATALOG_GROUP_ID'],
				SITE_ID, array());
			foreach ($arDiscounts as &$arDiscount) {
				switch ($arDiscount["VALUE_TYPE"]) {
					case 'P':
						$price_buf = $arProductPrice["PRICE"] - $arDiscount["VALUE"] * $arProductPrice["PRICE"] / 100;
						break;
					case 'F':
						$price_buf = $arProductPrice["PRICE"] - $arDiscount["VALUE"];
						break;
					default:
						$price_buf = $arDiscount["VALUE"];
						break;
				}

				if ($price_buf && ($price_buf < $price || !$price)) {
					$price = $price_buf;
					$arOffers[$product_id]["OLD_CURENCY"] = $arOffers[$product_id]["CURRENCY"];
					$arOffers[$product_id]["CURRENCY"] = $arProductPrice["CURRENCY"];
				}
			}
			$arDiscounts = null;
		}
		$arOffers[$product_id]["PRICE_NOT_DISCONT"] = $price_not_discount;
		$arOffers[$product_id]["PRICE"] = $price;
		CCatalogDiscount::ClearDiscountCache(array('PRODUCT' => 'Y'));
	}
} else // if($arParams["DISCOUNTS"] == "DISCOUNT_API")
{
	global $baseCurrency;
	if ($bCurrency) {
		$baseCurrency = CCurrency::GetBaseCurrency();
	}

	function yenisite_yandex_GetPrice($product_id, &$arPrices, &$arOffers, $bConvert = false)
	{
		global $bCurrency;
		global $baseCurrency;
		$price_not_discount = 0;
		$arPrice = CCatalogProduct::GetOptimalPrice($product_id, 1, $GLOBALS["USER"]->GetUserGroupArray(), "N", $arPrices, false, array());
		if (!$bConvert) {
			if ($arPrice["PRICE"]["CURRENCY"] != $baseCurrency && $bCurrency) {
				$arPrice["DISCOUNT_PRICE"] = CCurrencyRates::ConvertCurrency($arPrice["DISCOUNT_PRICE"], $baseCurrency,
					$arPrice["PRICE"]["CURRENCY"]);
			}
			$arOffers[$product_id]["OLD_CURENCY"] = $arOffers[$product_id]["CURRENCY"];
			$arOffers[$product_id]["CURRENCY"] = $arPrice["PRICE"]["CURRENCY"];
		} else {
			$arOffers[$product_id]["OLD_CURENCY"] = $arOffers[$product_id]["CURRENCY"];
			$arOffers[$product_id]["CURRENCY"] = $baseCurrency;
		}
		foreach ($arPrices as &$arProductPrice) {
			if ($arProductPrice['PRICE'] && ($arProductPrice['PRICE'] < $price_not_discount || !$price_not_discount)) {
				$price_not_discount = $arProductPrice['PRICE'];
			}
		}
		unset($arProductPrice);

		$arOffers[$product_id]["PRICE_NOT_DISCONT"] = $price_not_discount;
		$arOffers[$product_id]["PRICE"] = $arPrice["DISCOUNT_PRICE"];
		CCatalogDiscount::ClearDiscountCache(array('PRODUCT' => 'Y'));
	}
}

$GENERATE = $_GET['gen'] == 'y';
$STEP = (int)$_GET['step'] ?: 1;
$uniq = substr(md5($_SERVER['SCRIPT_NAME'] ?: $_SERVER['PHP_SELF']), 0, 7);
$fileName = 'ys_ym_' . $uniq . '.xml';
$curDir = $_SERVER['DOCUMENT_ROOT'] . '/' . $APPLICATION->GetCurDir();
$filePath = $curDir . $fileName;

if ($arParams['DEBUG_LOG'] && $GENERATE) {
	$debugStartTime = microtime(true);
	$debugFH = fopen($filePath . '.log', 'a');
	fwrite($debugFH, "\n\n" . '[STEP ' . $STEP . ']' . "\n");
	fwrite($debugFH, '-- START COMPONENT--' . "\n");
	fwrite($debugFH, 'IsAgent: ' . ($isAgent ? '1' : '0') . "\n");
	fwrite($debugFH, 'StartDate: ' . date('d.m.Y H:i:s') . "\n");
	fwrite($debugFH, 'Memory: ' . CFile::FormatSize(memory_get_usage()) . ' (' . CFile::FormatSize(memory_get_usage(true)) . ')' . "\n");
	fwrite($debugFH,
		'MemoryPeak: ' . CFile::FormatSize(memory_get_peak_usage()) . ' (' . CFile::FormatSize(memory_get_peak_usage(true)) . ')' . "\n");
}

if ($arParams['USE_AGENT']) {
	if (Loader::includeModule('yenisite.yandex')) {
		$URL = '';
		if (!empty($_SERVER['REQUEST_SCHEME'])) {
			$URL .= $_SERVER['REQUEST_SCHEME'] . '://';
		} else {
			if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) {
				$URL .= 'https://';
			} else {
				$URL .= 'http://';
			}
		}
		$SERVER_NAME = $_SERVER['SERVER_NAME'] ?: 'SITE_SERVER_NAME';
		$URL .= $SERVER_NAME . $APPLICATION->GetCurPage(true);
		$arNextExec = \Yenisite\Yandex\Agent::addAgent($URL, SITE_ID, $arParams['AGENT_TIME']);
	}
}

$bDesignMode = is_object($GLOBALS["USER"]) && $GLOBALS["USER"]->IsAdmin();

if (!$bDesignMode) {
	$APPLICATION->RestartBuffer();
	$CHARSET = SITE_CHARSET;
	if ($arParams['FORCE_CHARSET']) {
		$CHARSET = $arParams['FORCE_CHARSET'];
	}
	if (!$GENERATE) {
		header("Content-Type: text/xml; charset=" . $CHARSET);
		header("Pragma: no-cache");
	}
} else {
	if ($GENERATE) {
		$APPLICATION->RestartBuffer();
	} else {
		echo "<div>", GetMessage("RZ_HELLO");
		if (file_exists($filePath)) {
			echo GetMessage('RZ_FILE_EXIST',
				array('#URL#' => $APPLICATION->GetCurDir() . $fileName, '#FILE_DATE#' => date("d.m.y H:i:s.", filemtime($filePath))));
		} else {
			echo GetMessage('RZ_FILE_NOT_EXIST');
		}
		if ($arParams['USE_AGENT']) {
			echo GetMessage('RZ_AGENT_INFO', array('#TIME#' => $arParams['AGENT_TIME']));
			if (!empty($URL)) {
				echo GetMessage('RZ_AGENT_URL', array('#URL#' => $URL));
			}
			if (!empty($arNextExec)) {
				echo GetMessage('RZ_AGENT_NEXT_UPDATE', array('#TIME#' => $arNextExec['TIME'], '#DATE#' => $arNextExec['DATE']));
			}
			$arSaveParams = Ajax::getParams($URL, '', SITE_ID);
			if (!empty($arSaveParams['STEP'])) {
				if ($arSaveParams['STEP'] < 0) {
					echo GetMessage('RZ_AGENT_COMPLETE');
				} else {
					echo GetMessage('RZ_AGENT_LAST_STEP', array('#STEP#' => $arSaveParams['STEP']));
				}
			} else {
				echo GetMessage('RZ_AGENT_COMPLETE');
			}
		}

		echo GetMessage('RZ_GENERATE_FILE', array('#URL#' => $APPLICATION->GetCurPage(true) . '?gen=y')), '</div>';
		return;
	}
}


/*************************************************************************
 * START GENERATION
 *************************************************************************/
if ($GENERATE) {
	$protocol = (CMain::IsHTTPS()) ? "https" : "http";

	$arResult["DATE"] = date("Y-m-d H:i");
	$arResult["COMPANY"] = strip_tags(html_entity_decode($arParams["COMPANY"]));
	$arResult["SITE"] = $arParams["SITE"];
	$arResult["URL"] = $protocol . '://' . htmlspecialcharsEx(COption::GetOptionString("main", "server_name", ""));

	// list of the element fields that will be used in selection
	$arSelect = array(
		"ID",
		"NAME",
		"IBLOCK_ID",
		"IBLOCK_SECTION_ID",
		"DATE_CREATE",
		"DETAIL_PAGE_URL",
		"DETAIL_TEXT",
		"PREVIEW_TEXT",
	);
	if (!$bCatalog && !empty($arParams["PRICE_CODE"])) {
		$arSelect[] = "PROPERTY_" . $arParams["PRICE_CODE"];
	}
	if ($arParams["OLD_PRICE_LIST"] == "PROP_PRICE") {
		$arSelect[] = "PROPERTY_" . $arParams['OLD_PRICE_CODE'];
	}
	if ($bPriceFromProp) {
		$arSelect[] = "PROPERTY_" . $arParams['PRICE_CODE'];
	}
	if ($arParams['MORE_PHOTO']) {
		$arSelect[] = "DETAIL_PICTURE";
		$arSelect[] = "PREVIEW_PICTURE";
	}

	//
	if (is_array($arProperty)) {
		$arSelect = array_merge($arProperty, $arSelect);
	}

	$arFilter = array(
		"IBLOCK_LID" => SITE_ID,
		"IBLOCK_ID" => $arParams["IBLOCK_ID_IN"],
		"SECTION_ID" => $arParams["IBLOCK_SECTION"],
		"INCLUDE_SUBSECTIONS" => "Y",
		"IBLOCK_ACTIVE" => "Y",
		"ACTIVE_DATE" => "Y",
		"ACTIVE" => "Y",
		"CHECK_PERMISSIONS" => "Y",
		"SECTION_ACTIVE" => "Y", //New bitrix API can't fetch from IBLOCK root with this filter
		"SECTION_GLOBAL_ACTIVE" => "Y",
	);

	if ($bCatalog && $arParams['IBLOCK_CATALOG'] != 'N' && $arParams['IBLOCK_ORDER'] != 'Y') {
		$arFilter["CATALOG_AVAILABLE"] = 'Y';
	}

	if ($arParams['IBLOCK_AS_CATEGORY'] == 'Y') {
		unset($arFilter["SECTION_ACTIVE"]);
		unset($arFilter["SECTION_GLOBAL_ACTIVE"]);
	}

	if ($arParams["DO_NOT_INCLUDE_SUBSECTIONS"] == "Y") {
		$arFilter["INCLUDE_SUBSECTIONS"] = "N";
	}

	if ((count($arParams["IBLOCK_SECTION"]) == 1
			&& $arParams["IBLOCK_SECTION"][0] == 0)
		|| !$arParams["IBLOCK_SECTION"]
	) {
		unset($arFilter["SECTION_ID"]);
	}

	$arSort = array(
		"ID" => "DESC",
	);

	$i = 0;
	//EXECUTE
	if ($arParams["IBLOCK_TYPE"]) {
		/** @noinspection PhpDynamicAsStaticMethodCallInspection */
		$rsIBlock = CIBlock::GetList(Array("sort" => "asc"),
			Array("ID" => $arParams["IBLOCK_ID_IN"], "TYPE" => $arParams["IBLOCK_TYPE"], "ACTIVE" => "Y"));
		$arFilter["IBLOCK_TYPE"] = $arParams["IBLOCK_TYPE"];
	} else {
		/** @noinspection PhpDynamicAsStaticMethodCallInspection */
		$rsIBlock = CIBlock::GetList(Array("sort" => "asc"),
			Array("ID" => $arParams["IBLOCK_ID_IN"], "TYPE" => $arParams["IBLOCK_TYPE_LIST"], "ACTIVE" => "Y"));
		$arFilter["IBLOCK_TYPE"] = $arParams["IBLOCK_TYPE_LIST"];
	}

	$arSKUiblockID = array();

	while ($res = $rsIBlock->GetNext(false, false)) {
		if ($arParams['IBLOCK_AS_CATEGORY'] == 'Y') {
			$arResult["CATEGORIES"][$res["ID"]] = Array("ID" => $res["ID"], "NAME" => yandex_text2xml($res["NAME"], true));
		}

		if ($bCatalog) {

			if (class_exists('CCatalogSku') && method_exists('CCatalogSku', 'GetInfoByProductIBlock')) {
				$arSKUiBlock = CCatalogSku::GetInfoByProductIBlock($res["ID"]);
				if (is_array($arSKUiBlock)) {
					$arSKUiblockID[$res["ID"]] = $arSKUiBlock["IBLOCK_ID"];
				}
			} else {
				/** @noinspection PhpDynamicAsStaticMethodCallInspection */
				$rsSKU = CCatalog::GetList(array(), array("PRODUCT_IBLOCK_ID" => $res["ID"]), false, false, array("IBLOCK_ID"));
				if ($arSKUiBlock = $rsSKU->Fetch()) {
					$arSKUiblockID[$res["ID"]] = $arSKUiBlock["IBLOCK_ID"];
				}
				unset($rsSKU);
			}

			unset($arSKUiBlock);
		}
	}
	unset($rsIBlock, $res);

	//fetch sections into categories list
	if ((count($arParams["IBLOCK_SECTION"]) == 1 && $arParams["IBLOCK_SECTION"][0] == 0)) {
		$filter = Array(
			"IBLOCK_TYPE" => $arFilter["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID_IN"],
			"ACTIVE" => "Y",
			"IBLOCK_ACTIVE" => "Y",
			"GLOBAL_ACTIVE" => "Y",
		);
		$bSections = false;
	} else {
		$filter = Array(
			"IBLOCK_TYPE" => $arFilter["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID_IN"],
			"ID" => $arParams["IBLOCK_SECTION"],
			"ACTIVE" => "Y",
			"IBLOCK_ACTIVE" => "Y",
			"GLOBAL_ACTIVE" => "Y",
		);
		$bSections = true;
	}

	if ($arParams['IBLOCK_AS_CATEGORY'] == 'Y') {
		unset($filter['ACTIVE']);
		unset($filter['GLOBAL_ACTIVE']);
	}

	/** @noinspection PhpDynamicAsStaticMethodCallInspection */
	$db_acc = CIBlockSection::GetList(array("left_margin" => "asc"), $filter, false,
		array("ID", "NAME", "IBLOCK_ID", "IBLOCK_SECTION_ID", "LEFT_MARGIN", "RIGHT_MARGIN", "DEPTH_LEVEL"));

	unset($filter["ID"], $filter["IBLOCK_ID"]);

	while ($arAcc = $db_acc->Fetch()) {
		$id = $arAcc["IBLOCK_ID"] . $arAcc["ID"];
		if (array_key_exists($id, $arResult["CATEGORIES"])) continue;

		$arResult["CATEGORIES"][$id] = Array(
			"ID" => $id,
			"NAME" => yandex_text2xml($arAcc["NAME"], true),
			"PARENT" => ($arParams['IBLOCK_AS_CATEGORY'] == 'Y') ? $arAcc["IBLOCK_ID"] : null,
		);

		if ($arParams["DO_NOT_INCLUDE_SUBSECTIONS"] != "Y" && $bSections) {
			$subFilter = array(
				'IBLOCK_ID' => $arAcc['IBLOCK_ID'],
				'>LEFT_MARGIN' => $arAcc['LEFT_MARGIN'],
				'<RIGHT_MARGIN' => $arAcc['RIGHT_MARGIN'],
				'>DEPTH_LEVEL' => $arAcc['DEPTH_LEVEL'],
			);

			/** @noinspection PhpDynamicAsStaticMethodCallInspection */
			$db_sub = CIBlockSection::GetList(array("left_margin" => "asc"), array_merge($filter, $subFilter), false,
				array("ID", "NAME", "IBLOCK_ID", "IBLOCK_SECTION_ID"));

			while ($arAcc2 = $db_sub->Fetch()) {
				$id2 = $arAcc2["IBLOCK_ID"] . $arAcc2["ID"];
				$arResult["CATEGORIES"][$id2] = Array(
					"ID" => $id2,
					"NAME" => yandex_text2xml($arAcc2["NAME"], true),
					"PARENT" => ($arParams['IBLOCK_AS_CATEGORY'] == 'Y') ? $arAcc2["IBLOCK_ID"] : null,
				);
				if ((int)$arAcc2["IBLOCK_SECTION_ID"] < 1) continue;

				$key2 = $arAcc2["IBLOCK_ID"] . $arAcc2["IBLOCK_SECTION_ID"];
				if (!array_key_exists($key2, $arResult["CATEGORIES"])) continue;

				$arResult["CATEGORIES"][$id2]["PARENT"] = $key2;
			}
			unset($db_sub);
		}
		if ((int)$arAcc["IBLOCK_SECTION_ID"] < 1) continue;

		$key = $arAcc["IBLOCK_ID"] . $arAcc["IBLOCK_SECTION_ID"];
		if (!array_key_exists($key, $arResult["CATEGORIES"])) continue;

		$arResult["CATEGORIES"][$id]["PARENT"] = $key;
	}
	unset($arAcc, $db_acc);

	$navStatParam = false;
	if ($arParams['ONE_STEP_COUNT'] > 0) {
		$arResult['STEP'] = $STEP;
		$navStatParam = array(
			'nPageSize' => $arParams['ONE_STEP_COUNT'],
			'iNumPage' => $STEP,
		);
	}
	/** @noinspection PhpDynamicAsStaticMethodCallInspection */
	$rsElements = CIBlockElement::GetList($arSort, array_merge($arrFilter, $arFilter), false, $navStatParam, $arSelect);

	if ($arParams['ONE_STEP_COUNT'] > 0) {
		$arResult['STEP_TOTAL'] = $rsElements->NavPageCount;
		$arResult['LAST_STEP'] = $STEP == $arResult['STEP_TOTAL'];
	} else {
		$arResult['LAST_STEP'] = true;
		$arResult['STEP'] = 1;
	}
	//fetch elements
	$arOffers = array();
	$arOfferID = null;
	while ($arOffer = $rsElements->GetNext(false, false)) {
		$arOfferID[] = $arOffer["ID"];
		// default fileds
		$arOffer["SKU"] = array();
		$arOffer["AVAIBLE"] = 'false';
		$arOffers[$arOffer["ID"]] = $arOffer;
	}
	unset($rsElements, $arOffer);

	$arCurrencies = null;
	//work with module 'catalog'
	if ($bCatalog && $arParams['PRICE_FROM_IBLOCK'] != 'Y') {
		if (empty($arSKUiblockID)) {
			$arAllID = &$arOfferID; //ID of SKU and offers without any SKU
		} else {
			//fetch SKU
			/** @noinspection PhpDynamicAsStaticMethodCallInspection */
			$arOfferInOb = CIBlockElement::GetList(array($arParams['SKU_PROPERTY'] => 'DESC'),
				array_merge($arrSKUFilter, array("IBLOCK_ID" => $arSKUiblockID, $arParams['SKU_PROPERTY'] => $arOfferID, 'ACTIVE' => 'Y')),
				false, false, $arSelect);

			$arAllID = array(); //ID of SKU and offers without any SKU
			$productKey = $arParams['SKU_PROPERTY'] . '_VALUE';

			while ($arOfferIn = $arOfferInOb->GetNext(false, false)) {
				$arAllID[] = $arOfferIn["ID"];
				$productID = $arOfferIn[$productKey];
				$arOffers[$productID]["SKU"][] = $arOfferIn["ID"];
				$arOffers[$arOfferIn["ID"]] = $arOfferIn;
			}
			unset($arOfferInOb);

			foreach ($arOfferID as &$offerID) {
				if (empty($arOffers[$offerID]["SKU"])) $arAllID[] = $offerID;
			}
			unset($offerID);
		}

		//process catalog products
		$arProductSelect = array(
			"ID",
			"QUANTITY",
			"QUANTITY_TRACE",
			"CAN_BUY_ZERO",
		);

		$blCatalog = false;
		/** @noinspection PhpDynamicAsStaticMethodCallInspection */
		$dbProducts = CCatalogProduct::GetList(array("ID" => "DESC"), array("@ID" => $arAllID), false, false, $arProductSelect);

		while ($tr = $dbProducts->Fetch()) {
			$blCatalog = true;
			$arOffers[$tr["ID"]]["AVAIBLE"] = "true";
			$arOffers[$tr["ID"]]["QUANTITY"] = $tr["QUANTITY"];

			if ($tr["QUANTITY_TRACE"] == "N") continue;
			if ($tr["QUANTITY"] > 0) continue;
			if ($tr["CAN_BUY_ZERO"] == 'Y') continue;

			$arOffers[$tr["ID"]]["AVAIBLE"] = "false";
		}
		unset($dbProducts, $tr);

		if (!$blCatalog && $arParams['IBLOCK_CATALOG'] == 'N') {
			if (!empty($arOffers)) {
				foreach ($arOffers as &$arOffer) {
					$arOffer["AVAIBLE"] = 'true';
				}
				unset ($arOffer);
			}
		}


		//fetch price types
		/** @noinspection PhpDynamicAsStaticMethodCallInspection */
		$dbPriceTypes = CCatalogGroup::GetList(array("SORT" => "ASC"), array("NAME" => $arParams["PRICE_CODE"], "CAN_BUY" => "Y"));
		$arPriceTypesID = null;
		while ($arPriceType = $dbPriceTypes->Fetch()) {
			$arPriceTypesID[] = $arPriceType['ID'];
		}
		unset($dbPriceTypes);

		//fetch and process product prices
		$arPriceSelect = array('PRODUCT_ID', 'PRICE', 'CURRENCY', 'CATALOG_GROUP_ID');
		/** @noinspection PhpDynamicAsStaticMethodCallInspection */
		$dbProductPrices = CPrice::GetList(array("PRODUCT_ID" => "DESC"),
			array("@PRODUCT_ID" => $arAllID, "@CATALOG_GROUP_ID" => $arPriceTypesID), false, false, $arPriceSelect);

		$bConvert = ($arParams['CURRENCIES_CONVERT'] != 'NOT_CONVERT');
		$arPrices = array();
		if (count($arPriceTypesID) > 1) {
			$arProductPrice = $dbProductPrices->GetNext(false, false);
			$product_id = $arProductPrice["PRODUCT_ID"];
			$arPrices[] = $arProductPrice;
			while ($arProductPrice = $dbProductPrices->GetNext(false, false)) {
				if ($arProductPrice["PRODUCT_ID"] != $product_id) {
					yenisite_yandex_GetPrice($product_id, $arPrices, $arOffers, $bConvert);

					$product_id = $arProductPrice["PRODUCT_ID"];
					$arPrices = array();
				}
				$arPrices[] = $arProductPrice;
			}
			yenisite_yandex_GetPrice($product_id, $arPrices, $arOffers, $bConvert);
		} else {
			if ($arParams["DISCOUNTS"] == 'PRICE_ONLY') {
				while ($arPrice = $dbProductPrices->GetNext(false, false)) {
					$arOffers[$arPrice["PRODUCT_ID"]]["PRICE"] = $arPrice["PRICE"];
					$arOffers[$arPrice["PRODUCT_ID"]]["CURRENCY"] = $arPrice["CURRENCY"];
				}
			} else {
				$arAllPricesHolder = array();
				while ($tmpPrice = $dbProductPrices->GetNext(false, false)) {
					$arPrices[0]["PRODUCT_ID"] = $tmpPrice["PRODUCT_ID"];
					$arPrices[0]["PRICE"] = $tmpPrice["PRICE"];
					$arPrices[0]["CURRENCY"] = $tmpPrice["CURRENCY"];
					$arPrices[0]["CATALOG_GROUP_ID"] = $tmpPrice["CATALOG_GROUP_ID"];
					$arAllPricesHolder[] = $arPrices;
					unset($tmpPrice);
				}
				unset($arPrices);

				$arr_length = count($arAllPricesHolder);
				for ($i = 0; $i < $arr_length; $i++) {
					yenisite_yandex_GetPrice($arAllPricesHolder[$i][0]["PRODUCT_ID"], $arAllPricesHolder[$i], $arOffers, $bConvert);
				}
				unset($arAllPricesHolder);
			}
		}
		unset($dbProductPrices);

		if ($bPriceFromProp) {
			$baseCur = CCurrency::GetBaseCurrency();
			if ($baseCur != '') {
				foreach ($arOffers as &$arOffer) {
					if ($arOffer['PROPERTY_' . $arParams['PRICE_CODE'] . '_VALUE'] > 0) {
						$arOffer['PRICE'] = (float)$arOffer['PROPERTY_' . $arParams['PRICE_CODE'] . '_VALUE'];
						$arOffer['CURRENCY'] = $baseCur;
						unset($arOffer['PROPERTY_' . $arParams['PRICE_CODE'] . '_VALUE'], $arOffer['PROPERTY_' . $arParams['PRICE_CODE'] . '_VALUE_ID']);
					}
				}
				unset($arOffer, $baseCur);
			}
		}

		CCatalogDiscount::ClearDiscountCache(array('SECTIONS' => 'Y', 'SECTION_CHAINS' => 'Y'));

		//Format price decimal part for currencies
		if ($bCurrency) {
			$arCurrencies = array();
			$obCurrencies = CCurrency::GetList($by = 'sort', $order = 'asc');
			while ($arCurrency = $obCurrencies->Fetch()) {
				$arCurrency['DECIMALS'] = intval($arCurrency['DECIMALS']);
				if ($arCurrency['DECIMALS'] < 0) $arCurrency['DECIMALS'] = 0;
				$arCurrencies[$arCurrency['CURRENCY']] = $arCurrency;
			}
		}

		//fetch old_price types
		if ($arParams["OLD_PRICE_LIST"] == "TYPE_PRICE") {
			/** @noinspection PhpDynamicAsStaticMethodCallInspection */
			$arOldPrice = CCatalogGroup::GetList(array("SORT" => "ASC"), array("NAME" => $arParams["OLD_PRICE_CODE"]))->Fetch();
			$oldPriceID = $arOldPrice['ID'];
			unset($rsPrice, $arOldPrice);

			$arPriceSelect = array('PRODUCT_ID', 'PRICE', 'CURRENCY', 'CATALOG_GROUP_ID');
			/** @noinspection PhpDynamicAsStaticMethodCallInspection */
			$rsOldPrices = CPrice::GetList(array("PRODUCT_ID" => "DESC"),
				array("@PRODUCT_ID" => $arAllID, "CATALOG_GROUP_ID" => $oldPriceID), false, false, $arPriceSelect);

			while ($arOldPrice = $rsOldPrices->GetNext(false, false)) {
				if ((float)$arOldPrice['PRICE'] > (float)$arOffers[$arOldPrice['PRODUCT_ID']]) {
					$arOffers[$arOldPrice['PRODUCT_ID']]['OLD_PRICE'] = $arOldPrice['PRICE'];
					$arOffers[$arOldPrice['PRODUCT_ID']]['OLD_CURENCY'] = $arOldPrice['CURRENCY'];
				}
			}
		}
		if ($arParams["OLD_PRICE_LIST"] == "FROM_DISCOUNT") {
			foreach ($arOffers as &$arOffer) {
				if ((float)$arOffer['PRICE_NOT_DISCONT'] > (float)$arOffer['PRICE']) {
					$arOffer['OLD_PRICE'] = &$arOffer['PRICE_NOT_DISCONT'];
					$arOffer['OLD_CURENCY'] = &$arOffer['CURRENCY'];
				}
			}
			unset($arOffer);
		}
	}

	$arResult['OFFER'] = array();
	$arResult['CURRENCIES'] = array();

	if ($arParams["OLD_PRICE_LIST"] == "PROP_PRICE") {
		foreach ($arOffers as &$arOffer) {
			$floatVal = (float)$arOffer['PROPERTY_' . $arParams['OLD_PRICE_CODE'] . '_VALUE'];
			if ($floatVal > 0) {
				$arOffer['OLD_PRICE'] = (float)$arOffer['PROPERTY_' . $arParams['OLD_PRICE_CODE'] . '_VALUE'];
				$arOffer['OLD_CURENCY'] = &$arOffer['CURRENCY'];
			}
		}
		unset($arOffer);
	}

	//*************************************************************
	//******************** OFFER ITERATION ************************
	//*************************************************************

	foreach ($arOfferID as &$offerID) {
		$arOffer = &$arOffers[$offerID];

		if ($bCatalog && empty($arOffer["SKU"]) && $arParams['PRICE_FROM_IBLOCK'] != 'Y') {
			if (floatval($arOffer["PRICE"]) <= 0 && $arParams['PRICE_REQUIRED'] != 'N') {
				continue;
			}
			if ($arParams["IBLOCK_ORDER"] != "Y" && $arOffer["AVAIBLE"] == "false") {
				continue;
			}
		}

		if ($arParams["CURRENCIES_CONVERT"] != "NOT_CONVERT") {
			if ($arParams["OLD_PRICE_LIST"] == "TYPE_PRICE") {
				if ($arOffer["OLD_CURENCY"] != $arParams["CURRENCIES_CONVERT"]) {
					$arOffer["OLD_PRICE"] = CCurrencyRates::ConvertCurrency($arOffer["OLD_PRICE"], $arOffer["OLD_CURENCY"],
						$arParams["CURRENCIES_CONVERT"]);
					$arOffer["OLD_PRICE"] = round($arOffer["OLD_PRICE"], $arCurrencies[$arOffer["OLD_CURENCY"]]['DECIMALS']);

					$arOffer["OLD_CURENCY"] = $arParams["CURRENCIES_CONVERT"];
				}
			} else {
				if ($arOffer["CURRENCY"] != $arParams["CURRENCIES_CONVERT"]) {

					$arOffer["OLD_PRICE"] = CCurrencyRates::ConvertCurrency($arOffer["OLD_PRICE"], $arOffer["CURRENCY"],
						$arParams["CURRENCIES_CONVERT"]);
					$arOffer["OLD_PRICE"] = round($arOffer["OLD_PRICE"], $arCurrencies[$arOffer["CURRENCY"]]['DECIMALS']);
				}
			}

			if ($arOffer["CURRENCY"] != $arParams["CURRENCIES_CONVERT"]) {
				$arOffer["PRICE"] = CCurrencyRates::ConvertCurrency($arOffer["PRICE"], $arOffer["CURRENCY"],
					$arParams["CURRENCIES_CONVERT"]);
				$arOffer["CURRENCY"] = $arParams["CURRENCIES_CONVERT"];
			}
		}
		$arOffer["PRICE"] = round($arOffer["PRICE"], $arCurrencies[$arOffer["CURRENCY"]]['DECIMALS']);

		//setting offer pictures
		if ($arOffer["DETAIL_PICTURE"]) {
			$db_file = CFile::GetByID($arOffer["DETAIL_PICTURE"]);
			if ($ar_file = $db_file->Fetch()) {
				$arOffer["PICTURE"] = $ar_file["~src"] ? $ar_file["~src"] : $protocol . "://" . $_SERVER["SERVER_NAME"] . "/" . (COption::GetOptionString("main",
						"upload_dir", "upload")) . "/" . $ar_file["SUBDIR"] . "/" . implode("/",
						array_map("rawurlencode", explode("/", $ar_file["FILE_NAME"])));
			}
			unset($ar_file);
			unset($db_file);
		}


		if ($arOffer["PREVIEW_PICTURE"] && !$arOffer["PICTURE"]) {
			$db_file = CFile::GetByID($arOffer["PREVIEW_PICTURE"]);
			if ($ar_file = $db_file->Fetch()) {
				$arOffer["PICTURE"] = $ar_file["~src"] ? $ar_file["~src"] : $protocol . "://" . $_SERVER["SERVER_NAME"] . "/" . (COption::GetOptionString("main",
						"upload_dir", "upload")) . "/" . $ar_file["SUBDIR"] . "/" . implode("/",
						array_map("rawurlencode", explode("/", $ar_file["FILE_NAME"])));
			}
			unset($ar_file);
			unset($db_file);
		}

		if (isset($arParams["MORE_PHOTO"]) && $arParams["MORE_PHOTO"] != "YS_EMPTY") {
			$ph = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("value_id" => "asc"),
				Array("CODE" => $arParams["MORE_PHOTO"]));
			$arOffer["MORE_PHOTO"] = array();

			while (($ob = $ph->GetNext(false, false)) && count($arOffer["MORE_PHOTO"]) < 10) {
				$arFile = CFile::GetFileArray($ob["VALUE"]);
				if (!empty($arFile)) {
					if (strpos($arFile["SRC"], $protocol) === false) {
						$pic = $protocol . "://" . $_SERVER["SERVER_NAME"] . implode("/",
								array_map("rawurlencode", explode("/", $arFile["SRC"])));
					} else {
						$ar = explode($protocol . "://", $arFile["SRC"]);
						$pic = $protocol . "://" . implode("/", array_map("rawurlencode", explode("/", $ar[1])));
					}
					$arOffer["MORE_PHOTO"][] = $pic;
				}
				unset($ob);
			}
			unset($ph);

			if (!$arOffer["PICTURE"] && is_array($arOffer["MORE_PHOTO"])) {
				$arOffer['PICTURE'] = array_shift($arOffer["MORE_PHOTO"]);
			}
			$arOffer["MORE_PHOTO"] = array_slice($arOffer["MORE_PHOTO"], 0, 9);
		}

		//offer URL
		$arOffer["URL"] = $protocol . "://" . $_SERVER["SERVER_NAME"] . $arOffer["DETAIL_PAGE_URL"];
		if (!empty($arParams['UTM_PROP'])) {
			// original url has params
			if (parse_url($arOffer['URL'], PHP_URL_QUERY)) {
				$arOffer['URL'] .= '&amp;';
			} else {
				$arOffer['URL'] .= '?';
			}
			$arOffer['URL'] .= htmlspecialcharsbx($arParams['UTM_PROP']);
		}
		//setting offer description
		if ($arOffer["PREVIEW_TEXT"]) {
			$arOffer["PREVIEW_TEXT"] = yandex_text2xml(($arOffer["PREVIEW_TEXT_TYPE"] == "html" ? preg_replace_callback("'&[^;]*;'",
				"yandex_replace_special", strip_tags($arOffer["PREVIEW_TEXT"])) : $arOffer["PREVIEW_TEXT"]), true);
		}

		if ($arOffer["DETAIL_TEXT"]) {
			$arOffer["DETAIL_TEXT"] = yandex_text2xml(($arOffer["DETAIL_TEXT_TYPE"] == "html" ? preg_replace_callback("'&[^;]*;'",
				"yandex_replace_special", strip_tags($arOffer["DETAIL_TEXT"])) : $arOffer["DETAIL_TEXT"]), true);
		}

		$arOffer["DESCRIPTION"] = $arOffer["PREVIEW_TEXT"] ? $arOffer["PREVIEW_TEXT"] : $arOffer["DETAIL_TEXT"];

		if ($arParams["DETAIL_TEXT_PRIORITET"] == "Y") {
			$arOffer["DESCRIPTION"] = $arOffer["DETAIL_TEXT"] ? $arOffer["DETAIL_TEXT"] : $arOffer["PREVIEW_TEXT"];
		}

		$arOffer["CATEGORY"] = $arOffer["IBLOCK_ID"] . $arOffer["IBLOCK_SECTION_ID"];

		if (!array_key_exists($arOffer["CATEGORY"], $arResult["CATEGORIES"]) && $arOffer["IBLOCK_SECTION_ID"]) {
			$arGr = CIBlockElement::GetElementGroups($arOffer["ID"]);
			while ($ar_group = $arGr->Fetch()) {
				if (!array_key_exists($arOffer["IBLOCK_ID"] . $ar_group["ID"], $arResult["CATEGORIES"])) continue;
				$arOffer["CATEGORY"] = $arOffer["IBLOCK_ID"] . $ar_group["ID"];
				break;
			}
		}
		if ($arParams['SECTION_AS_VENDOR'] == 'Y') {
			if (!empty($arOffer['IBLOCK_SECTION_ID'])) {
				$arOffer["DEVELOPER"] = $arResult["CATEGORIES"][$arOffer["IBLOCK_ID"] . $arOffer['IBLOCK_SECTION_ID']]["NAME"];
			}
		}

		if ($arParams["MARKET_CATEGORY_CHECK"] == "Y") {
			if (!empty($arParams['MARKET_CATEGORY_PROP'])) {
				$arProps = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"),
					Array("CODE" => $arParams["MARKET_CATEGORY_PROP"]))->Fetch();

				$arOffer["MARKET_CATEGORY"] = $arProps["VALUE_ENUM"] ? $arProps["VALUE_ENUM"] : $arProps["VALUE"];
				unset($arProps);
			}

			if (!$arOffer["MARKET_CATEGORY"]) {
				$arGr = CIBlockElement::GetElementGroups($arOffer["ID"]);
				$ar_group = $arGr->Fetch();
				$groupid = $ar_group["ID"];

				$res = CIBlockSection::GetNavChain(false, $groupid);
				while ($el = $res->GetNext(false, false)) {
					$arOffer["MARKET_CATEGORY"] .= $el['NAME'];
					$arOffer["MARKET_CATEGORY"] .= "/";
				}
				unset($res);
				unset($arGr);
				unset($ar_group);
				if ($arParams["IBLOCK_AS_CATEGORY"] == 'Y') {
					$arOffer["MARKET_CATEGORY"] = $arResult["CATEGORIES"][$arOffer["IBLOCK_ID"]]["NAME"]
						. '/'
						. $arOffer["MARKET_CATEGORY"];
				}
				$arOffer["MARKET_CATEGORY"] = substr($arOffer["MARKET_CATEGORY"], 0, -1);
			}
		}


		//setting offer name
		if (!empty($arParams['NAME_PROP'])) {
			$arProps = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"),
				Array("CODE" => $arParams['NAME_PROP']))->Fetch();
			$arOffer["MODEL"] = $arProps["VALUE_ENUM"] ? $arProps["VALUE_ENUM"] : $arProps["VALUE"];
			unset($arProps);
		}

		if (empty($arOffer["MODEL"])) {
			$arOffer["MODEL"] = &$arOffer["NAME"];
		}

		//setting offer salse_notes
		$arSalse_notes = array();

		if ($arParams['SELF_SALES_NOTES'] == 'N') {
			if (!empty($arParams['SALES_NOTES_NAMES'])) {
				$rs = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"),
					Array("CODE" => $arParams['SALES_NOTES_NAMES']));
				$arProps = $rs->Fetch();
				$arSalse_notes = $arProps["VALUE_ENUM"] ? $arProps["VALUE_ENUM"] : $arProps["VALUE"];
				unset($arProps, $rs);
			}
		} else {
			if (!empty($arParams['SELF_SALES_NOTES_INPUT'])) {
				$arSalse_notes = $arParams['SELF_SALES_NOTES_INPUT'];
			}
		};

		//work with offer SKU
		$flag = 0;
		foreach ($arOffer["SKU"] as &$arOfferInID) {
			$arOfferIn = &$arOffers[$arOfferInID];
			$flag = 1;

			//check available status
			if ($arParams["IBLOCK_ORDER"] != "Y" && $arOfferIn["AVAIBLE"] == "false") {
				continue;
			}

			if (floatval($arOfferIn["PRICE"]) <= 0) {
				if (floatval($arOffer['PRICE']) <= 0) {
					continue;
				}
				$arOfferIn['PRICE'] = $arOffer['PRICE'];
			}

			//setting offer salse_notes for offerIn
			if ($arParams['SELF_SALES_NOTES'] == 'N') {
				if (!empty($arParams['SALES_NOTES_NAMES'])) {
					$rs = CIBlockElement::GetProperty($arOfferIn["IBLOCK_ID"], $arOfferIn["ID"], array("sort" => "asc"),
						Array("CODE" => $arParams['SALES_NOTES_NAMES']));
					$arProps = $rs->Fetch();
					$arSalse_notes = $arProps["VALUE_ENUM"] ? $arProps["VALUE_ENUM"] : $arProps["VALUE"];
					unset($arProps, $rs);

					if (empty($arSalse_notes)) {
						$rs = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"),
							Array("CODE" => $arParams['SALES_NOTES_NAMES']));
						$arProps = $rs->Fetch();
						$arSalse_notes = $arProps["VALUE_ENUM"] ? $arProps["VALUE_ENUM"] : $arProps["VALUE"];
						unset($arProps, $rs);
					}
				}
			} else {
				if (!empty($arParams['SELF_SALES_NOTES_INPUT'])) {
					$arSalse_notes = $arParams['SELF_SALES_NOTES_INPUT'];
				}
			};


			//setting offer old_price
			if ($arParams["OLD_PRICE_LIST"] == "PROP_PRICE") {
				$floatVal = (float)$arOfferIn['PROPERTY_' . $arParams['OLD_PRICE_CODE'] . '_VALUE'];
				if ($floatVal > 0) {
					$arOfferIn['OLD_PRICE'] = $floatVal;
					$arOfferIn['OLD_CURENCY'] = &$arOfferIn['CURRENCY'];
				}
			}

			if ($arParams["CURRENCIES_CONVERT"] != "NOT_CONVERT" && $arOfferIn["CURRENCY"] != $arParams["CURRENCIES_CONVERT"]) {
				$arOfferIn["PRICE"] = CCurrencyRates::ConvertCurrency($arOfferIn["PRICE"], $arOfferIn["CURRENCY"],
					$arParams["CURRENCIES_CONVERT"]);
				if ($arParams["OLD_PRICE_LIST"] != "TYPE_PRICE") {
					$arOfferIn["OLD_PRICE"] = CCurrencyRates::ConvertCurrency($arOfferIn["OLD_PRICE"], $arOfferIn["CURRENCY"],
						$arParams["CURRENCIES_CONVERT"]);
					$arOfferIn["OLD_CURENCY"] = $arOfferIn["CURRENCY"];
					$arOfferIn["OLD_PRICE"] = round($arOfferIn["OLD_PRICE"], $arCurrencies[$arOfferIn["CURRENCY"]]['DECIMALS']);
				}
				$arOfferIn["CURRENCY"] = $arParams["CURRENCIES_CONVERT"];
			}
			$arOfferIn["PRICE"] = round($arOfferIn["PRICE"], $arCurrencies[$arOfferIn["CURRENCY"]]['DECIMALS']);


			if (!in_array($arOfferIn["CURRENCY"], $arResult["CURRENCIES"])) {
				$arResult["CURRENCIES"][] = $arOfferIn["CURRENCY"];
			}

			$arOfferIn["CATEGORY"] = $arOffer["CATEGORY"];

			$tmpName = $arOffer["MODEL"];

			switch ($arParams["SKU_NAME"]) {
				case "PRODUCT_NAME":
					$arOfferIn["MODEL"] = yandex_text2xml($tmpName, true);
					break;

				case "SKU_NAME":
					$arOfferIn["MODEL"] = yandex_text2xml(empty($arOfferIn["NAME"]) ? $tmpName : $arOfferIn["NAME"], true);
					break;

				default:
					if (!empty($arOfferIn["NAME"])) $tmpName .= " / " . $arOfferIn["NAME"];
					$arOfferIn["MODEL"] = yandex_text2xml($tmpName, true);
					break;
			}


			if (!$arOfferIn["DETAIL_PAGE_URL"]) {
				$arOfferIn["URL"] = $arOffer["URL"] . "#" . $arOfferIn["ID"];
			} else {
				$arOfferIn["URL"] = $protocol . "://" . $_SERVER["SERVER_NAME"] . $arOfferIn["DETAIL_PAGE_URL"];
				if (!empty($arParams['UTM_PROP'])) {
					// original url has params
					if (parse_url($arOfferIn['URL'], PHP_URL_QUERY)) {
						$arOfferIn['URL'] .= '&amp;';
					} else {
						$arOfferIn['URL'] .= '?';
					}
					$arOfferIn['URL'] .= htmlspecialcharsbx($arParams['UTM_PROP']);
				}
			}

			if ($arOfferIn["DETAIL_PICTURE"]) {
				$db_file = CFile::GetByID($arOfferIn["DETAIL_PICTURE"]);
				if ($ar_file = $db_file->Fetch()) {
					$arOfferIn["PICTURE"] = $ar_file["~src"] ? $ar_file["~src"] : $protocol . "://" . $_SERVER["SERVER_NAME"] . "/" . (COption::GetOptionString("main",
							"upload_dir", "upload")) . "/" . $ar_file["SUBDIR"] . "/" . implode("/",
							array_map("rawurlencode", explode("/", $ar_file["FILE_NAME"])));
				}
				unset($ar_file);
				unset($db_file);
			}

			if ($arOfferIn["PREVIEW_PICTURE"] && !$arOfferIn["PICTURE"]) {
				$db_file = CFile::GetByID($arOfferIn["PREVIEW_PICTURE"]);
				if ($ar_file = $db_file->Fetch()) {
					$arOfferIn["PICTURE"] = $ar_file["~src"] ? $ar_file["~src"] : $protocol . "://" . $_SERVER["SERVER_NAME"] . "/" . (COption::GetOptionString("main",
							"upload_dir", "upload")) . "/" . $ar_file["SUBDIR"] . "/" . implode("/",
							array_map("rawurlencode", explode("/", $ar_file["FILE_NAME"])));
				}
				unset($ar_file);
				unset($db_file);
			}

			if (isset($arParams["MORE_PHOTO"]) && $arParams["MORE_PHOTO"] != "YS_EMPTY") {

				$ph = CIBlockElement::GetProperty($arOfferIn["IBLOCK_ID"], $arOfferIn["ID"], array("sort" => "asc"),
					Array("CODE" => $arParams["MORE_PHOTO"]));
				$arOfferIn["MORE_PHOTO"] = array();

				while (($ob = $ph->GetNext(false, false)) && count($arOfferIn["MORE_PHOTO"]) < 10) {
					$arFile = CFile::GetFileArray($ob["VALUE"]);
					if (!empty($arFile)) {
						if (strpos($arFile["SRC"], $protocol) === false) {
							$pic = $protocol . "://" . $_SERVER["SERVER_NAME"] . implode("/",
									array_map("rawurlencode", explode("/", $arFile["SRC"])));
						} else {
							$ar = explode($protocol . "://", $arFile["SRC"]);
							$pic = $protocol . "://" . implode("/", array_map("rawurlencode", explode("/", $ar[1])));

						}
						$arOfferIn["MORE_PHOTO"][] = $pic;
					}
					unset($ob);
					unset($arFile);
				}
				unset($ph);
			}

			if (is_array($arOffer["MORE_PHOTO"])) {
				foreach ($arOffer["MORE_PHOTO"] as $pic) {
					if (!in_array($pic, $arOfferIn["MORE_PHOTO"]) && count($arOfferIn["MORE_PHOTO"]) < 10) {
						$arOfferIn["MORE_PHOTO"][] = $pic;
					}
				}
			}

			if (!$arOfferIn["PICTURE"]) {
				if ($arOffer["PICTURE"]) {
					$arOfferIn["PICTURE"] = $arOffer["PICTURE"];
				} else {
					if (is_array($arOfferIn["MORE_PHOTO"])) {
						$arOfferIn["PICTURE"] = array_shift($arOfferIn["MORE_PHOTO"]);
					}
				}
			}
			$arOfferIn["MORE_PHOTO"] = array_slice($arOfferIn["MORE_PHOTO"], 0, 9);

			if ($arOfferIn["PREVIEW_TEXT"]) {
				$arOfferIn["PREVIEW_TEXT"] = yandex_text2xml(($arOfferIn["PREVIEW_TEXT_TYPE"] == "html" ? preg_replace_callback("'&[^;]*;'",
					"yandex_replace_special", strip_tags($arOfferIn["PREVIEW_TEXT"])) : $arOfferIn["PREVIEW_TEXT"]), true);
			}

			if ($arOfferIn["DETAIL_TEXT"]) {
				$arOfferIn["DETAIL_TEXT"] = yandex_text2xml(($arOfferIn["DETAIL_TEXT_TYPE"] == "html" ? preg_replace_callback("'&[^;]*;'",
					"yandex_replace_special", strip_tags($arOfferIn["DETAIL_TEXT"])) : $arOfferIn["DETAIL_TEXT"]), true);
			}

			$arOfferIn["DESCRIPTION"] = $arOfferIn["PREVIEW_TEXT"] ? $arOfferIn["PREVIEW_TEXT"] : $arOfferIn["DETAIL_TEXT"];

			if ($arParams["DETAIL_TEXT_PRIORITET"] == "Y") {
				$arOfferIn["DESCRIPTION"] = $arOfferIn["DETAIL_TEXT"] ? $arOfferIn["DETAIL_TEXT"] : $arOfferIn["PREVIEW_TEXT"];
			}

			if (!$arOfferIn["DESCRIPTION"]) {
				$arOfferIn["DESCRIPTION"] = $arOffer["DESCRIPTION"];
			}

			// MARKET_CATEGORY
			//$nameIb = CIBlock::GetByID( $arOffer['IBLOCK_ID'] )->GetNext(); // name IB

			if ($arParams["MARKET_CATEGORY_CHECK"] == "Y") {
				$arOfferIn["MARKET_CATEGORY"] = $arOffer["MARKET_CATEGORY"];
			}

			// GROUP_ID
			$arOfferIn["GROUP_ID"] = $arOffer["ID"];
			// ID Ibloka cataloga
			$arOfferIn["IBLOCK_ID_CATALOG"] = $arOffer["IBLOCK_ID"];

			if ($arParams['SECTION_AS_VENDOR'] == 'Y') {
				if (!empty($arOffer['IBLOCK_SECTION_ID'])) {
					$arOfferIn["DEVELOPER"] = $arOffer["DEVELOPER"];
				}
			}

			//include PARAMETRS [CATALOG_PRICE_ID, CATALOG_CURRENCY_ID] for oldprices from discounts adn Create Old Price
			if ($arParams["OLD_PRICE_LIST"] == "FROM_DISCOUNT") {
				if ((float)$arOfferIn['PRICE_NOT_DISCONT'] > (float)$arOfferIn['PRICE']) {
					$arOfferIn['OLD_PRICE'] = &$arOfferIn["PRICE_NOT_DISCONT"];
					$arOfferIn['OLD_CURENCY'] = &$arOfferIn["CURRENCY"];
				}
			}
            //\_::dd($arOfferIn);
			$arOfferIn['SALES_NOTES_OFFER'] = $arSalse_notes;
			$arResult["OFFER"][] = $arOfferIn;
			unset($arOldPrice);


		} // foreach ($arOffer["SKU"] as &$arOfferInID)

		if ($flag == 1) continue; //dalshe ne idem, a perehod k novomu tovaru

		if (!$bCatalog || $arParams['PRICE_FROM_IBLOCK'] == 'Y') {
			$arOffer["AVAIBLE"] = "true";
			if (isset($arParams["IBLOCK_QUANTITY"]) && $arParams["IBLOCK_QUANTITY"] != "YS_EMPTY") {
				$av = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"),
					Array("CODE" => $arParams["IBLOCK_QUANTITY"]))->Fetch();
				if ((int)$av["VALUE"] > 0) {
					$arOffer["AVAIBLE"] = "true";
				} else {
					if ($arParams["IBLOCK_ORDER"] == "Y") {
						$arOffer["AVAIBLE"] = "false";
					} else {
						continue;
					}
				}
			}
		}

		if ($bCatalog && $arParams['PRICE_FROM_IBLOCK'] != 'Y') {
			if ($arParams["CURRENCIES_CONVERT"] != "NOT_CONVERT" && $arOffer["CURRENCY"] != $arParams["CURRENCIES_CONVERT"]) {
				$arOffer["PRICE"] = CCurrencyRates::ConvertCurrency($arOffer["PRICE"], $arOffer["CURRENCY"],
					$arParams["CURRENCIES_CONVERT"]);
				if ($arParams["OLD_PRICE_LIST"] != "TYPE_PRICE") {
					$arOffer["OLD_PRICE"] = CCurrencyRates::ConvertCurrency($arOffer["OLD_PRICE"], $arOffer["CURRENCY"],
						$arParams["CURRENCIES_CONVERT"]);
					$arOffer["OLD_PRICE"] = round($arOffer["OLD_PRICE"], $arCurrencies[$arOffer["CURRENCY"]]['DECIMALS']);
				}
				$arOffer["CURRENCY"] = $arParams["CURRENCIES_CONVERT"];
			}
			$arOffer['PRICE'] = round($arOffer['PRICE'], $arCurrencies[$arOffer["CURRENCY"]]['DECIMALS']);

			if ($arOffer['CURRENCY'] == "RUR") $arOffer['CURRENCY'] = "RUB";
			if (!in_array($arOffer["CURRENCY"], $arResult["CURRENCIES"])) {
				$arResult["CURRENCIES"][] = $arOffer["CURRENCY"];
			}
		} else {
			$arProps = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer['ID'], array("sort" => "asc"),
				Array("CODE" => $arParams["PRICE_CODE"]))->Fetch();

			$arOffer["PRICE"] = $arProps["VALUE_ENUM"] ? $arProps["VALUE_ENUM"] : $arProps["VALUE"];
			$arOffer["PRICE"] = floatval(str_replace(" ", "", $arOffer["PRICE"]));
			unset($arProps);

			if (intval($arOffer["PRICE"]) <= 0 && $arParams['PRICE_REQUIRED'] != 'N') {
				continue;
			}

			if (!empty($arParams["CURRENCIES_PROP"])) {
				$arProps = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer['ID'], array("sort" => "asc"),
					Array("CODE" => $arParams["CURRENCIES_PROP"]))->Fetch();
			}

			$arOffer["CURRENCY"] = empty($arProps["VALUE_XML_ID"]) ? $arParams["CURRENCY"] : $arProps["VALUE_XML_ID"];
			$arProps = null;

			if (!in_array($arOffer["CURRENCY"], $arResult["CURRENCIES"])) {
				$arResult["CURRENCIES"][] = $arOffer["CURRENCY"];
			}
		}

		// Need to work in result_modifier.php with $arParams['COND_PARAMS']
		// If no offers these parameters must be exist
		// --- Total bull shit. These parameters causes double calls of GetProperty. Fixed by Ilya F.
		// $arOffer["IBLOCK_ID_CATALOG"] = $arOffer["IBLOCK_ID"];
		// $arOffer["GROUP_ID"] = $arOffer["ID"];

		$arOffer["MODEL"] = yandex_text2xml($arOffer["MODEL"], true);

		$arOffer["SALES_NOTES_OFFER"] = yandex_text2xml($arSalse_notes, true);

		$arResult["OFFER"][] = $arOffer;


		$i++;
	}
	unset($arOffers);

	//fetc #arProps for PARAMS & COND_PARAMS
	$baseCur = getBaseCurrencyTempl();
	if (!CModule::IncludeModule('currency')) $baseCur = $arParams["CURRENCY"];
	$arCur = array();
	$arCur[0] = $baseCur;
	foreach ($arResult["CURRENCIES"] as $cur) {
		if ($cur == 'RUR') {
			$cur = 'RUB';
		}

		if (!in_array($cur, $arCur)) {
			$arCur[] = $cur;
		}
	}

	$arResult["CURRENCIES"] = $arCur;

	if ($arParams['DEBUG_LOG'] && $GENERATE && $debugFH) {
		$debugParamsStart = microtime(true);
		fwrite($debugFH, '-- EXECUTE COMPONENT--' . "\n");
		fwrite($debugFH, 'AgentID: ' . ($arNextExec? $arNextExec['ID'] : '0') . "\n");
		fwrite($debugFH, 'FinishDate: ' . date('d.m.Y H:i:s') . "\n");
		fwrite($debugFH, 'StepTime: ' . ($debugParamsStart - $debugStartTime) . "\n");
		fwrite($debugFH,
			'Memory: ' . CFile::FormatSize(memory_get_usage()) . ' (' . CFile::FormatSize(memory_get_usage(true)) . ')' . "\n");
		fwrite($debugFH,
			'MemoryPeak: ' . CFile::FormatSize(memory_get_peak_usage()) . ' (' . CFile::FormatSize(memory_get_peak_usage(true)) . ')' . "\n");
	}

	$arNeedParams = array_flip($arParams['PARAMS']);
	$arCondParams = array();
	if (!empty($arParams['COND_PARAMS'])) {
		$arCondParams = array_flip($arParams['COND_PARAMS']);
		foreach ($arParams['COND_PARAMS'] as $value) {
			$arNeedParams[$value] = 1;
		}
	}

	foreach ($arParams as $code => $value) {
		if (substr($code, 0, 10) == 'MANDATORY_') {
			$arNeedParams[$value] = 1;
		}
	}
	if (!empty($arParams['PARAMS'])) {
		foreach ($arResult["OFFER"] as &$arOffer) {
			$arEmptyProps = array();
			$rs = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"));
			while ($ar = $rs->GetNext(0, 0)) {
				if (!isset($arNeedParams[$ar['CODE']])) continue;
				$code = &$ar['CODE'];
				$ar['~VALUE'] = $ar['~VALUE']?:$ar['VALUE'];
				$arTmp = CIBlockFormatProperties::GetDisplayValue($arOffer, $ar, "ym_out");
				$arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"] = htmlspecialcharsbx($arTmp["VALUE_ENUM"] ?: strip_tags($arTmp["DISPLAY_VALUE"]));
				$arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_NAME"] = htmlspecialcharsbx($ar["NAME"]);
				$arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_DESCRIPTION"] = $ar["DESCRIPTION"] ? htmlspecialcharsbx($ar["DESCRIPTION"]) : '';
				if (isset($arCondParams[$code])) {
					$arOffer["CONDITION_PROPERTIES"][$code]["DISPLAY_VALUE"] = $arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"];
					$arOffer["CONDITION_PROPERTIES"][$code]["DISPLAY_NAME"] = $arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_NAME"];
					$arOffer["CONDITION_PROPERTIES"][$code]["DISPLAY_DESCRIPTION"] = $arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_DESCRIPTION"];
				}
				if (empty($arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])) {
					$arEmptyProps[$code] = 1;
				}
				unset($code);
			}

			if (!empty($arEmptyProps) && $arOffer["GROUP_ID"]) {
				$rs = CIBlockElement::GetProperty($arOffer["IBLOCK_ID_CATALOG"], $arOffer["GROUP_ID"], array("sort" => "asc"));
				while ($ar = $rs->GetNext(0, 0)) {
					if (!isset($arEmptyProps[$ar['CODE']])) continue;
					$code = &$ar['CODE'];
					$ar['~VALUE'] = $ar['~VALUE']?:$ar['VALUE'];
					$arTmp = CIBlockFormatProperties::GetDisplayValue($arOffer, $ar, "ym_out");
					$arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"] = htmlspecialcharsbx($arTmp["VALUE_ENUM"] ?: strip_tags($arTmp["DISPLAY_VALUE"]));
					$arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_NAME"] = htmlspecialcharsbx($ar["NAME"]);
					$arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_DESCRIPTION"] = htmlspecialcharsbx($ar["DESCRIPTION"]);
					if (isset($arCondParams[$code])) {
						$arOffer["CONDITION_PROPERTIES"][$code]["DISPLAY_VALUE"] = $arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"];
						$arOffer["CONDITION_PROPERTIES"][$code]["DISPLAY_NAME"] = $arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_NAME"];
						$arOffer["CONDITION_PROPERTIES"][$code]["DISPLAY_DESCRIPTION"] = $arOffer["DISPLAY_PROPERTIES"][$code]["DISPLAY_DESCRIPTION"];
					}
					unset($code);
				}
			}
		}
		unset($ar, $rs, $arNeedParams, $arEmptyProps, $arCondParams);
	}

	if ($arParams['DEBUG_LOG'] && $GENERATE && $debugFH) {
		$debugTemplateTimeStart = microtime(true);
		fwrite($debugFH, '-- EXECUTE PARAMS --' . "\n");
		fwrite($debugFH, 'FinishDate: ' . date('d.m.Y H:i:s') . "\n");
		fwrite($debugFH, 'StepTime: ' . ($debugTemplateTimeStart - $debugParamsStart) . "\n");
		fwrite($debugFH, 'TotalTime: ' . ($debugTemplateTimeStart - $debugStartTime) . "\n");
		fwrite($debugFH,
			'Memory: ' . CFile::FormatSize(memory_get_usage()) . ' (' . CFile::FormatSize(memory_get_usage(true)) . ')' . "\n");
		fwrite($debugFH,
			'MemoryPeak: ' . CFile::FormatSize(memory_get_peak_usage()) . ' (' . CFile::FormatSize(memory_get_peak_usage(true)) . ')' . "\n");
	}
	$this->includeComponentTemplate();
	if ($arParams['DEBUG_LOG'] && $GENERATE && $debugFH) {
		$debugTemplateTimeFinish = microtime(true);
		fwrite($debugFH, '-- EXECUTE TEMPLATE--' . "\n");
		fwrite($debugFH, 'FinishDate: ' . date('d.m.Y H:i:s') . "\n");
		fwrite($debugFH, 'StepTime: ' . ($debugTemplateTimeFinish - $debugTemplateTimeStart) . "\n");
		fwrite($debugFH, 'TotalTime: ' . ($debugTemplateTimeFinish - $debugStartTime) . "\n");
		fwrite($debugFH,
			'Memory: ' . CFile::FormatSize(memory_get_usage()) . ' (' . CFile::FormatSize(memory_get_usage(true)) . ')' . "\n");
		fwrite($debugFH,
			'MemoryPeak: ' . CFile::FormatSize(memory_get_peak_usage()) . ' (' . CFile::FormatSize(memory_get_peak_usage(true)) . ')' . "\n");
	}
}

if (!$bDesignMode || $GENERATE) {
	if (!$GENERATE && file_exists($filePath)) {
		// kill the Buffer! free memory!
		while (ob_get_level()) {
			ob_end_clean();
		}
		if ($fh = fopen($filePath, 'rb')) {
			while (!feof($fh)) {
				print fread($fh, 1024);
			}
			fclose($fh);
		}
	} else {
		if ($GENERATE) {
			$curPage = $APPLICATION->GetCurPage(true);
			$r = $APPLICATION->EndBufferContentMan();
			$tmpFile = $fileName . '.tmp';
			$tmpPath = $curDir . $tmpFile;

			if ($STEP == 1 && file_exists($tmpPath)) {
				@unlink($tmpPath);
			}
			if ($fh = fopen($tmpPath, 'ab')) {
				fwrite($fh, $r);
				fclose($fh);
			}
			if ($isAgent) {
				Ajax::saveParams($URL, array('STEP_TOTAL' => $arResult['STEP_TOTAL']));
			}
			if (!$arResult['LAST_STEP']) {
				if ($isAgent) {
					// Ajax::saveParams($URL, array('STEP' => $STEP + 1));
					die();
				} else {
					$redirectURL = $curPage . '?gen=y&step=' . ($STEP + 1);
					echo GetMessage('RZ_GENERATION_STEP', array('#STEP#' => $STEP, '#STEP_TOTAL#' => $arResult['STEP_TOTAL']));
					echo '<script>setTimeout(function(){window.location = "' . $redirectURL . '"},1000);</script>';
				}
			} else {
				rename($tmpPath, $filePath);
				if ($isAgent) {
					// Ajax::saveParams($URL, array('STEP' => -1));
					die();
				} else {
					if ($bDesignMode) {
						LocalRedirect($APPLICATION->GetCurDir() . $fileName);
					} else {
						LocalRedirect($curPage);
					}
				}
			}
		}
	}
	die();
}
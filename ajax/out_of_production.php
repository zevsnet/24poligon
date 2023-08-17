<?define("STATISTIC_SKIP_ACTIVITY_CHECK", "true");?>
<?define('STOP_STATISTICS', true);
define('PUBLIC_AJAX_MODE', true);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?
use \Aspro\Functions\CAsproMax;

$context = \Bitrix\Main\Application::getInstance()->getContext();
$request = $context->getRequest();
$arPost = $request->getPostList()->toArray();

global $APPLICATION, $USER;

$arPost = $APPLICATION->ConvertCharsetArray($arPost, 'UTF-8', LANG_CHARSET);
$arParams = $arPost['PARAMS'];
$USER_ID = $USER->GetID();

$arFilter = [
	'ID' => $arPost['ID'],
	'IBLOCK_ID' => $arParams['IBLOCK_ID'],
	'ACTIVE' => 'Y',
];
$arSelect = ['ID', 'IBLOCK_ID', 'LANG_ID', 'NAME', 'PREVIEW_PICTURE', 'DETAIL_PICTURE', 'DETAIL_PAGE_URL', 'CATALOG_TYPE', 'PROPERTY_OUT_OF_PRODUCTION'];

$obCache = new CPHPCache();

$cacheTag = "elements_out_of_production";
$cacheID = "getOutOfProductionjs".$cacheTag.md5(serialize(array_merge((array)($arPost["PARAMS"]["CACHE_GROUPS"]==="N"? false : $USER->GetGroups()), $arFilter, (array)$arSelect, $arPost["PARAMS"])));
$cachePath = "/CMaxCache/iblock/getOutOfProductionjs/".$cacheTag."/";
$cacheTime = $arPost["PARAMS"]["CACHE_TIME"];

if (isset($arPost["clear_cache"]) && $arPost["clear_cache"] == "y"){
	\CMaxCache::ClearCacheByTag($cacheTag);
}

if ($obCache->InitCache($cacheTime, $cacheID, $cachePath)) {
	$res = $obCache->GetVars();
	$arElement = $res["arElement"];
	$arParams = $res["arParams"];
} else {
	//select prices
	if ($arParams["PRICE_CODE"]) {
		$arPricesIDs = CAsproMax::getPricesID($arParams["PRICE_CODE"], true);
		if ($arPricesIDs) {
			foreach ($arPricesIDs as $priceID)
				$arSelect[] = "CATALOG_GROUP_".$priceID;
		} else {
			$arSelect[] = "CATALOG_QUANTITY";
		}
	}
	$rsElement = CIBlockElement::GetList(['SORT' => 'ASC'], $arFilter, false, false, $arSelect);
	$arElement = $rsElement->GetNext();

	if ((int)$arElement['CATALOG_TYPE'] === 1) {
		unset($arParams['MESSAGE_FROM']);
	} else {
		$arElement['SHOW_MORE_BUTTON'] = 'Y';
	}


	$arElement['IMAGE'] = $arElement['PREVIEW_PICTURE'] ?: $arElement['DETAIL_PICTURE'];
	if ($arElement['IMAGE']) {
		$arElement['IMAGE'] = CFile::ResizeImageGet($arElement['IMAGE'], ['width' => 93, 'height' => 93], BX_RESIZE_IMAGE_PROPORTIONAL, true, []);
	}

	if ($arParams['CONVERT_CURRENCY'] === 'Y') {
		if (!CModule::IncludeModule('currency')){
			$arParams['CONVERT_CURRENCY'] = 'N';
			$arParams['CURRENCY_ID'] = '';
		} else {
			$arResultModules['currency'] = true;
			$arCurrencyInfo = CCurrency::GetByID($arParams['CURRENCY_ID']);
			if (!(is_array($arCurrencyInfo) && !empty($arCurrencyInfo))) {
				$arParams['CONVERT_CURRENCY'] = 'N';
				$arParams['CURRENCY_ID'] = '';
			} else {
				$arParams['CURRENCY_ID'] = $arCurrencyInfo['CURRENCY'];
				$arConvertParams['CURRENCY_ID'] = $arCurrencyInfo['CURRENCY'];
			}
		}
	}

	$arElement['PRICES_TYPE'] = CIBlockPriceTools::GetCatalogPrices($arElement["IBLOCK_ID"], $arParams["PRICE_CODE"]);
	$arElement['PRICES'] = CIBlockPriceTools::GetItemPrices($arElement["IBLOCK_ID"], $arElement["PRICES_TYPE"], $arElement, $arParams["PRICE_VAT_INCLUDE"], $arParams, $USER_ID, $arPost["SITE_ID"]);

	foreach ($arElement['PRICES'] as $priceKey => $arPrice) {
		if ($arElement['CATALOG_GROUP_NAME_'.$arPrice['PRICE_ID']]) {
			$arPriceTypeID[] = $arPrice['PRICE_ID'];
			$arElement['PRICES'][$priceKey]['GROUP_NAME'] = $arElement['CATALOG_GROUP_NAME_'.$arPrice['PRICE_ID']];
		}
		unset($arElement['PRICES'][$priceKey]['MIN_PRICE']);

		if (empty($result)) {
			$minPrice = (!$arParams['CURRENCY_ID']
				? $arPrice['DISCOUNT_VALUE']
				: CCurrencyRates::ConvertCurrency($arPrice['DISCOUNT_VALUE'], $arPrice['CURRENCY'], $arParams['CURRENCY_ID'])
			);
			$result = $priceKey;
		} else {
			$comparePrice = (!$arParams['CURRENCY_ID']
				? $arPrice['DISCOUNT_VALUE']
				: CCurrencyRates::ConvertCurrency($arPrice['DISCOUNT_VALUE'], $arPrice['CURRENCY'], $arParams['CURRENCY_ID'])
			);
			if ($minPrice > $comparePrice && $arPrice['CAN_BUY'] == 'Y') {
				$minPrice = $comparePrice;
				$result = $priceKey;
			}
		}
	}
	if ($result) {
		$arElement['PRICES'][$result]['MIN_PRICE'] = 'Y';
		foreach ($arElement['PRICES'] as &$arPrice) {
			if ($arPrice['MIN_PRICE'] === 'Y') {
				$arElement['MIN_PRICE'] = $arPrice;
				break;
			}
		}
		unset($arPrice);
	}

	// cache
	if (\Bitrix\Main\Config\Option::get("main", "component_cache_on", "Y") !== "N") {
		$obCache->StartDataCache($cacheTime, $cacheID, $cachePath);

		if (strlen($cacheTag)) {
			global $CACHE_MANAGER;
			$CACHE_MANAGER->StartTagCache($cachePath);
			$CACHE_MANAGER->RegisterTag($cacheTag);
			$CACHE_MANAGER->EndTagCache();
		}

		$obCache->EndDataCache([
			"arElement" => $arElement, 
			"arParams" => $arParams, 
		]);
	}
}

if ($arElement['PROPERTY_OUT_OF_PRODUCTION_VALUE']) {
	die();
}

$arItemIDs = CMax::GetItemsIDs($arElement, "Y");
$arItem = [
	'ID' => $arElement['ID'],
	'NAME' => $arElement['NAME'],
	'URL' => $arElement['DETAIL_PAGE_URL'],
];

if ($arElement['IMAGE']) {
	$arItem['IMAGE'] = $arElement['IMAGE'];
}

$arElement['CAN_BUY'] = CIBlockPriceTools::CanBuy($arElement['IBLOCK_ID'], $arElement['PRICES_TYPE'], $arElement);
$arElement['EMPTY_PROPS_JS'] = 'Y';

$totalCount = CMax::GetTotalCount($arElement, $arParams);
$arAddToBasketData = CMax::GetAddToBasketArray($arElement, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], true, $arItemIDs["ALL_ITEM_IDS"], 'btn-sm', $arParams, true);

$arItem['PRICE'] = \Aspro\Functions\CAsproMaxItem::showItemPrices(array_merge($arParams, ['ONLY_POPUP_PRICE' => 'Y']), $arElement['PRICES'], '', $arElement['MIN_PRICE']['ID'], (isset($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"]) && $arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] === 'Y' ? 'N' : 'Y'), false, true, true);
$arItem['BUTTONS'] = ['BUY' => $arAddToBasketData['HTML']];

if ($arElement['SHOW_MORE_BUTTON'] !== 'Y') {
	$arItem['BUTTONS']['ACTIONS'] = \Aspro\Functions\CAsproMaxItem::showDelayCompareBtn($arParams, $arElement, $arAddToBasketData, $totalCount, false, 'list icons static ignore', false, false, '_small', '', '', true);
}
?>
<?ob_start();?>
<?
$arConfig = [
	'FILE' => 'catalog/catalog_detail_analog.php',
	'PARAMS' => [
		'BLOCK_TITLE' => $arParams['TEXT'],
		'BLOCK_NOTE' => $arParams['NOTE'],
	],
	'ITEM' => $arItem,
];
?>
<?CAsproMax::showBlockHtml($arConfig);?>
<?$html = ob_get_clean();?>
<?die($html);?>
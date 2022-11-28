<?define("STATISTIC_SKIP_ACTIVITY_CHECK", "true");?>
<?define('STOP_STATISTICS', true);
define('PUBLIC_AJAX_MODE', true);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?
$context = \Bitrix\Main\Application::getInstance()->getContext();
$request = $context->getRequest();
$arPost = $request->getPostList()->toArray();

global $APPLICATION;
$arPost = $APPLICATION->ConvertCharsetArray($arPost, 'UTF-8', LANG_CHARSET);

if(!strlen($arPost["SITE_ID"])){
	$arPost["SITE_ID"] = SITE_ID;
}

if($arPost["SITE_ID"]){
	$dbRes = CSite::GetByID($arPost["SITE_ID"]);
	$arSite = $dbRes->Fetch();
	$arSite['DIR'] = str_replace('//', '/', '/'.$arSite['DIR']);
}

if(!$arPost['CLASS'])
	$arPost['CLASS'] = "inner_content";
?>

<?if($arPost["PARAMS"]):?>
	<?
	$arPost["PARAMS"]["SHOW_ABSENT"] = true; // set true for opacity 0.4 unable item

	if(!strlen($arPost["PARAMS"]["BASKET_URL"])){
		$arPost["PARAMS"]["BASKET_URL"] = str_replace('/', $arSite['DIR'], \Bitrix\Main\Config\Option::get('aspro.max', 'BASKET_PAGE_URL', '/basket/', $arPost["SITE_ID"]));
	}

	$typeSku = \Bitrix\Main\Config\Option::get('aspro.max', 'TYPE_SKU', 'TYPE_1', $arPost["SITE_ID"]);
	$bChangeTitleItem = \Bitrix\Main\Config\Option::get('aspro.max', 'CHANGE_TITLE_ITEM', 'N', $arPost["SITE_ID"]) === 'Y';

	\Bitrix\Main\Loader::includeModule("sale");
	\Bitrix\Main\Loader::includeModule("catalog");

	$arPropsTmp = array();
	foreach($arPost as $key => $value)
	{
		if(strpos($key, 'PROP_') !== false)
		{
			$arPropsTmp[$key] = $value;
		}
	}
	$arSelectedProps = json_encode($arPropsTmp);

	$arFilter = array("IBLOCK_ID" => $arPost["IBLOCK_ID"], "PROPERTY_CML2_LINK" => $arPost["LINK_ID"], "ACTIVE" => "Y");

	if($arPost["PARAMS"]["HIDE_NOT_AVAILABLE_OFFERS"] == "Y")
		$arFilter["CATALOG_AVAILABLE"] = "Y";

	$arSelect = array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", "PREVIEW_PICTURE", "DETAIL_PICTURE", "PROPERTY_*");
	$arSelectPrice = array("ID", "IBLOCK_ID");

	/* select prices */
	if($arPost["PARAMS"]["PRICE_CODE"])
	{
		$arPricesIDs = \Aspro\Functions\CAsproMax::getPricesID($arPost["PARAMS"]["PRICE_CODE"], true);
		if($arPricesIDs)
		{
			foreach($arPricesIDs as $priceID)
				$arSelectPrice[] = "CATALOG_GROUP_".$priceID;
		}
	}
	/**/

	/* get sku props*/
	$arSKU = array("IBLOCK_ID" => $arPost["IBLOCK_ID"], "SKU_PROPERTY_ID" => $arPost["PROPERTY_ID"], "VERSION" => 1);
	if ( $arPost["IBLOCK_ID"] && $featureProps = \Bitrix\Iblock\Model\PropertyFeature::getListPageShowPropertyCodes( $arPost["IBLOCK_ID"], array('CODE' => 'Y') ) ) {
		$arPost["PARAMS"]['OFFER_TREE_PROPS'] = $featureProps;
	}
	$arSKUPropList = CIBlockPriceTools::getTreeProperties(
		$arSKU,
		$arPost["PARAMS"]["OFFER_TREE_PROPS"],
		array(
			//'PICT' => $arEmptyPreview,
			'NAME' => '-'
		)
	);

	$arNeedValues = array();
	CIBlockPriceTools::getTreePropertyValues($arSKUPropList, $arNeedValues);

	$arSKUPropIDs = array_keys($arSKUPropList);
	if($featureProps) {
		$arPost["PARAMS"]['OFFER_TREE_PROPS'] = $arSKUPropIDs;
	}

	if ($arSKUPropIDs)
		$arSKUPropKeys = array_fill_keys($arSKUPropIDs, false);
	/**/

	global $USER;
	$USER_ID = $USER->GetID();
	$arUserGroups = $USER->GetUserGroupArray();

	$obCache = new CPHPCache();

	$cacheTag = "element_".$arPost['LINK_ID'];
	$cacheTag = "elements_by_offer";
	$cacheID = "getSKUjs".$cacheTag.$typeSku.$bChangeTitleItem.md5(serialize(array_merge((array)($arPost["PARAMS"]["CACHE_GROUPS"]==="N"? false : $USER->GetGroups()), $arFilter, (array)$arSelect, $arPost["PARAMS"])));
	$cachePath = "/CMaxCache/iblock/getSKUjs/".$cacheTag."/";
	$cacheTime = $arPost["PARAMS"]["CACHE_TIME"];
	// $cacheTime = 0;

	if(isset($arPost["clear_cache"]) && $arPost["clear_cache"] == "y"){
		\CMaxCache::ClearCacheByTag($cacheTag);
	}

	/*get currency for convert*/
	$arCurrencyParams = array();
	if ("Y" == $arPost["PARAMS"]["CONVERT_CURRENCY"])
	{
		if(CModule::IncludeModule("currency"))
		{
			$arCurrencyInfo = CCurrency::GetByID($arPost["PARAMS"]["CURRENCY_ID"]);
			if (is_array($arCurrencyInfo) && !empty($arCurrencyInfo))
			{
				$arCurrencyParams["CURRENCY_ID"] = $arCurrencyInfo["CURRENCY"];
			}
		}
	}
	/**/

	if(!$arPost["PARAMS"]["LIST_OFFERS_LIMIT"])
		$arPost["PARAMS"]["LIST_OFFERS_LIMIT"] = 9999;

	if($obCache->InitCache($cacheTime, $cacheID, $cachePath))
	{
		$res = $obCache->GetVars();
		$arItems = $res["arItems"];
	}
	else
	{
		$arElements = array();

		//get main item
		$arTmpID = explode("_", $arPost["LINK_ID"]);
		$arMainItem = array();
		if(count($arTmpID) > 1)
		{
			$arFilterTmp = array("ID" => $arTmpID[0]);
			$arSelectTmp = array("ID", "NAME", 'PREVIEW_PICTURE', 'DETAIL_PICTURE', 'PROPERTY_'.$arPost["PARAMS"]["ADD_PICT_PROP"]);
			$rsItems = CIBLockElement::GetList(array(), $arFilterTmp, false, false, $arSelectTmp);
			while($arItemTmp = $rsItems->Fetch())
			{
				if($arItemTmp['PROPERTY_'.$arPost["PARAMS"]["ADD_PICT_PROP"].'_VALUE'])
					$arTmp[] = $arItemTmp['PROPERTY_'.$arPost["PARAMS"]["ADD_PICT_PROP"].'_VALUE'];

				$arMainItem["NAME"] = $arItemTmp["NAME"];

				$arMainItem["DETAIL_PICTURE"] = $arItemTmp["DETAIL_PICTURE"];
				if(($arMainItem['DETAIL_PICTURE'] && $arMainItem['PREVIEW_PICTURE']) || (!$arMainItem['DETAIL_PICTURE'] && $arMainItem['PREVIEW_PICTURE']))
					$arMainItem["DETAIL_PICTURE"] = $arItemTmp["PREVIEW_PICTURE"];
			}
			if($arTmp)
			{
				$arMainItem["PROPERTIES"][$arPost["PARAMS"]["ADD_PICT_PROP"]] = array(
					"PROPERTY_TYPE" => "F",
					"VALUE" => $arTmp
				);
				unset($arTmp);
			}
		}

		if ( $featureProps = \Bitrix\Iblock\Model\PropertyFeature::getListPageShowPropertyCodes( $arPost["IBLOCK_ID"], array('CODE' => 'Y') ) ) {
			$arPost["PARAMS"]["LIST_OFFERS_PROPERTY_CODE"] = $featureProps;
		}
		

		/* get sku by link item*/
		$rsElements = CIBLockElement::GetList(array($arPost["PARAMS"]["OFFERS_SORT_FIELD"] => $arPost["PARAMS"]["OFFERS_SORT_ORDER"], $arPost["PARAMS"]["OFFERS_SORT_FIELD2"] => $arPost["PARAMS"]["OFFERS_SORT_ORDER2"]), $arFilter, false, array("nTopCount" => $arPost["PARAMS"]["LIST_OFFERS_LIMIT"]), $arSelect);
		while($obElement = $rsElements->GetNextElement())
		{
			$arItem = $obElement->GetFields();
			$arItem["FIELDS"] = array();
			$arItem["PROPERTIES"] = $obElement->GetProperties();
			$arItem["DISPLAY_PROPERTIES"]=array();

			if($typeSku === 'TYPE_1' && $bChangeTitleItem){
				$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($arItem['IBLOCK_ID'], $arItem['ID']);
				$arItem['IPROPERTY_VALUES'] = $ipropValues->getValues();
			}

			foreach($arPost["PARAMS"]["LIST_OFFERS_PROPERTY_CODE"] as $pid)
			{
				$prop = &$arItem["PROPERTIES"][$pid];
				if(
					(is_array($prop["VALUE"]) && count($prop["VALUE"])>0)
					|| (!is_array($prop["VALUE"]) && strlen($prop["VALUE"])>0)
				)
				{
					$arItem["DISPLAY_PROPERTIES"][$pid] = CIBlockFormatProperties::GetDisplayValue($arItem, $prop, "news_out");
				}
			}
			$arElements[$arItem["ID"]] = $arItem;
		}

		// get sku prices
		if($arElements){
			$rsElements = CIBLockElement::GetList(array(), array('ID' => array_keys($arElements)), false, false, $arSelectPrice);
			while($obElement = $rsElements->GetNextElement())
			{
				$arItem = $obElement->GetFields();
				$arElements[$arItem["ID"]] = array_merge($arElements[$arItem["ID"]], $arItem);
			}
		}

		/**/

		/* get tree props */
		$arMatrixFields = $arSKUPropKeys;
		$arMatrix = $arMeasureMap = array();
		$arResult = $arDouble = array();

		$arDefaultMeasure = CCatalogMeasure::getDefaultMeasure(true, true);

		foreach ($arElements as $keyOffer => $arOffer)
		{
			$arOffer['ID'] = intval($arOffer['ID']);
			$arOffer['IBLOCK_ID'] = $arOffer['IBLOCK_ID'];
			$arOffer['IS_OFFER'] = 'Y';
			if (isset($arDouble[$arOffer['ID']]))
				continue;
			$arRow = array();
			
			foreach ($arSKUPropIDs as $propkey => $strOneCode)
			{
				$arCell = array(
					'VALUE' => 0,
					'SORT' => PHP_INT_MAX,
					'NA' => true
				);
				if (isset($arOffer['DISPLAY_PROPERTIES'][$strOneCode]))
				{
					$arMatrixFields[$strOneCode] = true;
					$arCell['NA'] = false;
					if ('directory' == $arSKUPropList[$strOneCode]['USER_TYPE'])
					{
						$intValue = $arSKUPropList[$strOneCode]['XML_MAP'][$arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE']];
						$arCell['VALUE'] = $intValue;
					}
					elseif ('L' == $arSKUPropList[$strOneCode]['PROPERTY_TYPE'])
					{
						$arCell['VALUE'] = intval($arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE_ENUM_ID']);
					}
					elseif ('E' == $arSKUPropList[$strOneCode]['PROPERTY_TYPE'])
					{
						$arCell['VALUE'] = intval($arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE']);
					}
					$arCell['SORT'] = $arSKUPropList[$strOneCode]['VALUES'][$arCell['VALUE']]['SORT'];
				}
				$arRow[$strOneCode] = $arCell;
			}
			$arMatrix[$keyOffer] = $arRow;

			CIBlockPriceTools::clearProperties($arOffer['DISPLAY_PROPERTIES'], $arPost["PARAMS"]['OFFER_TREE_PROPS']);

			$arOffer['PRICES_TYPE'] = \CIBlockPriceTools::GetCatalogPrices(false, $arPost["PARAMS"]['PRICE_CODE']);
			$arOffer['PRICES_ALLOW'] = \CIBlockPriceTools::GetAllowCatalogPrices($arOffer['PRICES_TYPE']);

			// CIBlockPriceTools::setRatioMinPrice($arOffer, false);

			$offerPictures = CIBlockPriceTools::getDoublePicturesForItem($arOffer, $arPost["PARAMS"]['OFFER_ADD_PICT_PROP']);
			$arOffer['OWNER_PICT'] = empty($offerPictures['PICT']);
			$arOffer['PREVIEW_PICTURE_FIELD'] = $arOffer['PREVIEW_PICTURE'];
			$arOffer['PREVIEW_PICTURE'] = false;
			$arOffer['PREVIEW_PICTURE_SECOND'] = false;
			$arOffer['SECOND_PICT'] = true;
			if (!$arOffer['OWNER_PICT'])
			{
				if (empty($offerPictures['SECOND_PICT']))
					$offerPictures['SECOND_PICT'] = $offerPictures['PICT'];
				$arOffer['PREVIEW_PICTURE'] = $offerPictures['PICT'];
				$arOffer['PREVIEW_PICTURE_SECOND'] = $offerPictures['SECOND_PICT'];
			}

			if($arOffer["DISPLAY_PROPERTIES"]["ARTICLE"]["VALUE"])
			{
				$arOffer["ARTICLE"] = GetMessage('T_ARTICLE_COMPACT').": ".(is_array($arOffer["DISPLAY_PROPERTIES"]["ARTICLE"]["VALUE"]) ? reset($arOffer["DISPLAY_PROPERTIES"]["ARTICLE"]["VALUE"]) : $arOffer["DISPLAY_PROPERTIES"]["ARTICLE"]["VALUE"]);
				unset($arOffer["DISPLAY_PROPERTIES"]["ARTICLE"]);
			}

			$arDouble[$arOffer['ID']] = true;

			$arOffer['CATALOG_MEASURE_NAME'] = $arDefaultMeasure['SYMBOL_RUS'];
			$arOffer['~CATALOG_MEASURE_NAME'] = $arDefaultMeasure['SYMBOL_RUS'];
			$arOffer["CATALOG_MEASURE_RATIO"] = 1;
			if (!isset($arOffer['CATALOG_MEASURE']))
				$arOffer['CATALOG_MEASURE'] = 0;
			$arOffer['CATALOG_MEASURE'] = (int)$arOffer['CATALOG_MEASURE'];
			if (0 > $arOffer['CATALOG_MEASURE'])
				$arOffer['CATALOG_MEASURE'] = 0;
			if (0 < $arOffer['CATALOG_MEASURE'])
			{
				if (!isset($arMeasureMap[$arOffer['CATALOG_MEASURE']]))
					$arMeasureMap[$arOffer['CATALOG_MEASURE']] = array();
				$arMeasureMap[$arOffer['CATALOG_MEASURE']][] = $keyOffer;
			}

			if($arPost["PARAMS"]["SHOW_DISCOUNT_TIME"] == "Y" && $arPost["PARAMS"]["SHOW_COUNTER_LIST"] != "N")
			{
				$active_to = '';
				$arDiscounts = CCatalogDiscount::GetDiscountByProduct($arOffer['ID'], $arUserGroups, "N", array(), $arPost["SITE_ID"]);
				if($arDiscounts)
				{
					foreach($arDiscounts as $arDiscountOffer)
					{
						if($arDiscountOffer['ACTIVE_TO'])
						{
							$active_to = $arDiscountOffer['ACTIVE_TO'];
							break;
						}
					}
				}
				$arOffer['DISCOUNT_ACTIVE'] = $active_to;
			}

			$arResult["ITEMS"][$keyOffer] = $arOffer;
		}
		unset($arElements);

		/*get measure ratio*/
		$rsRatios = CCatalogMeasureRatio::getList(
			array(),
			array('@PRODUCT_ID' => array_keys($arResult["ITEMS"])),
			false,
			false,
			array('PRODUCT_ID', 'RATIO')
		);
		while ($arRatio = $rsRatios->Fetch())
		{
			$arRatio['PRODUCT_ID'] = (int)$arRatio['PRODUCT_ID'];
			if (isset($arResult["ITEMS"][$arRatio['PRODUCT_ID']]))
			{
				$intRatio = (int)$arRatio['RATIO'];
				$dblRatio = (float)$arRatio['RATIO'];
				$mxRatio = ($dblRatio > $intRatio ? $dblRatio : $intRatio);
				if (CATALOG_VALUE_EPSILON > abs($mxRatio))
					$mxRatio = 1;
				elseif (0 > $mxRatio)
					$mxRatio = 1;
				$arResult["ITEMS"][$arRatio['PRODUCT_ID']]['CATALOG_MEASURE_RATIO'] = $mxRatio;
				$arResult["ITEMS"][$arRatio['PRODUCT_ID']]['STEP_QUANTITY'] = $mxRatio;
			}
		}
		/**/

		/*get item prices*/
		foreach($arResult["ITEMS"] as $key => $arOffer)
		{
			$arResult["ITEMS"][$key]['CATALOG_QUANTITY'] = (
				0 < $arOffer['CATALOG_QUANTITY'] && is_float($arOffer['CATALOG_MEASURE_RATIO'])
				? (float)$arOffer['CATALOG_QUANTITY']
				: (int)$arOffer['CATALOG_QUANTITY']
			);

			$result = false;
			$minPrice = 0;
			$arOffer["PRICES"] = CIBlockPriceTools::GetItemPrices($arOffer["IBLOCK_ID"], $arOffer["PRICES_TYPE"], $arOffer, $arPost["PARAMS"]["PRICE_VAT_INCLUDE"], $arCurrencyParams, $USER_ID, $arPost["SITE_ID"]);


			$arResult["ITEMS"][$key]["PRICES"] = $arOffer["PRICES"];

			if($arOffer['PRICES'])
			{
				$arPriceTypeID = array();
				foreach($arOffer['PRICES'] as $priceKey => $arOfferPrice)
				{
					if($arOffer['CATALOG_GROUP_NAME_'.$arOfferPrice['PRICE_ID']])
					{
						$arPriceTypeID[] = $arOfferPrice['PRICE_ID'];
						$arResult["ITEMS"][$key]['PRICES'][$priceKey]['GROUP_NAME'] = $arOffer['CATALOG_GROUP_NAME_'.$arOfferPrice['PRICE_ID']];
					}
					unset($arResult["ITEMS"][$key]['PRICES'][$priceKey]['MIN_PRICE']);

					if (empty($result))
					{
						$minPrice = (!$arCurrencyParams['CURRENCY_ID']
							? $arOfferPrice['DISCOUNT_VALUE']
							: CCurrencyRates::ConvertCurrency($arOfferPrice['DISCOUNT_VALUE'], $arOfferPrice['CURRENCY'], $arCurrencyParams['CURRENCY_ID'])
						);
						$result = $priceKey;
					}
					else
					{
						$comparePrice = (!$arCurrencyParams['CURRENCY_ID']
							? $arOfferPrice['DISCOUNT_VALUE']
							: CCurrencyRates::ConvertCurrency($arOfferPrice['DISCOUNT_VALUE'], $arOfferPrice['CURRENCY'], $arCurrencyParams['CURRENCY_ID'])
						);
						if ($minPrice > $comparePrice && $arOfferPrice['CAN_BUY'] == 'Y')
						{
							$minPrice = $comparePrice;
							$result = $priceKey;
						}
					}
				}
				if ($result) {
					$arResult["ITEMS"][$key]['PRICES'][$result]['MIN_PRICE'] = 'Y';
				}
				$arResult["ITEMS"][$key]['PRICE_MATRIX'] = '';
				if($arPost["PARAMS"]["USE_PRICE_COUNT"] == "Y")
				{
					if(function_exists('CatalogGetPriceTableEx'))
					{
						$arResult["ITEMS"][$key]["PRICE_MATRIX"] = CatalogGetPriceTableEx($arOffer["ID"], 0, $arPriceTypeID, 'Y', $arCurrencyParams);
						if(count($arResult["ITEMS"][$key]['PRICE_MATRIX']['ROWS']) <= 1)
						{
							$arResult["ITEMS"][$key]['PRICE_MATRIX'] = '';
						}
						else
						{
							$arOffer = array_merge($arResult["ITEMS"][$key], CMax::formatPriceMatrix($arResult["ITEMS"][$key]));
							$arResult["ITEMS"][$key] = $arOffer;
						}
					}
				}
			}

			$arResult["ITEMS"][$key]["CAN_BUY"] = CIBlockPriceTools::CanBuy($arOffer["IBLOCK_ID"], $arOffer["PRICES_TYPE"], $arOffer);
		}
		/**/

		if (isset($arOffer))
			unset($arOffer);

		/*get measure*/
		if(!empty($arMeasureMap))
		{
			$rsMeasures = CCatalogMeasure::getList(
				array(),
				array('@ID' => array_keys($arMeasureMap)),
				false,
				false,
				array('ID', 'SYMBOL_RUS')
			);
			while ($arMeasure = $rsMeasures->GetNext())
			{
				$arMeasure['ID'] = (int)$arMeasure['ID'];
				if (isset($arMeasureMap[$arMeasure['ID']]) && !empty($arMeasureMap[$arMeasure['ID']]))
				{
					foreach ($arMeasureMap[$arMeasure['ID']] as $intOneKey)
					{
						$arResult["ITEMS"][$intOneKey]['CATALOG_MEASURE_NAME'] = $arMeasure['SYMBOL_RUS'];
						$arResult["ITEMS"][$intOneKey]['~CATALOG_MEASURE_NAME'] = $arMeasure['~SYMBOL_RUS'];
					}
					unset($intOneKey);
				}
			}
		}
		/**/

		/*format tree props*/
		foreach ($arSKUPropIDs as $propkey => $strOneCode)
		{
			$boolExist = $arMatrixFields[$strOneCode];
			foreach ($arMatrix as $keyOffer => $arRow)
			{
				if ($boolExist)
				{
					if (!isset($arResult["ITEMS"][$keyOffer]['TREE']))
						$arResult["ITEMS"][$keyOffer]['TREE'] = array();
					$arResult["ITEMS"][$keyOffer]['TREE']['PROP_'.$arSKUPropList[$strOneCode]['ID']] = $arMatrix[$keyOffer][$strOneCode]['VALUE'];
					$arResult["ITEMS"][$keyOffer]['SKU_SORT_'.$strOneCode] = $arMatrix[$keyOffer][$strOneCode]['SORT'];
					$arUsedFields[$strOneCode] = true;
					$arSortFields['SKU_SORT_'.$strOneCode] = SORT_NUMERIC;
				}
				else
				{
					unset($arMatrix[$keyOffer][$strOneCode]);
				}
			}
		}

		\Bitrix\Main\Type\Collection::sortByColumn($arResult["ITEMS"], $arSortFields);
		/**/

		if($arPost['PARAMS']['IBINHERIT_TEMPLATES']){
			$arItemTmp = array(
				'OFFERS' => $arResult["ITEMS"],
			);
			\Aspro\Max\Property\IBInherited::modifyItemTemplates($arPost['PARAMS'], $arItemTmp);
			$arResult["ITEMS"] = $arItemTmp['OFFERS'];
		}

		/* save cache */
		$arItems = array();

		if($arMainItem)
			$arItems['MAIN_ITEM'] = $arMainItem;

		foreach($arResult["ITEMS"] as $key => $arItem)
		{
			$arItems["ITEMS"][$key] = array(
				"ID" => $arItem["ID"],
				"IBLOCK_ID" => $arItem["IBLOCK_ID"],
				"IS_OFFER" => $arItem["IS_OFFER"],
				"NAME" => $arItem["NAME"],
				"PICTURE" => ($arItem["PREVIEW_PICTURE"] ? $arItem["PREVIEW_PICTURE"]["SRC"] : ($arItem["DETAIL_PICTURE"] ? $arItem["DETAIL_PICTURE"]["SRC"] : ($arPost["PICTURE"] ? $arPost["PICTURE"] : ''))),
				"PREVIEW_PICTURE_FIELD" => $arItem["PREVIEW_PICTURE_FIELD"],
				"TREE" => $arItem["TREE"],
				"CAN_BUY" => $arItem["CAN_BUY"],
				"MEASURE" => $arItem["CATALOG_MEASURE_NAME"],
				"CATALOG_MEASURE_RATIO" => $arItem["CATALOG_MEASURE_RATIO"],
				"CATALOG_QUANTITY_TRACE" => $arItem["CATALOG_QUANTITY_TRACE"],
				"CATALOG_CAN_BUY_ZERO" => $arItem["CATALOG_CAN_BUY_ZERO"],
				"DISCOUNT_ACTIVE" => $arItem["DISCOUNT_ACTIVE"],
				"CATALOG_SUBSCRIBE" => $arItem["CATALOG_SUBSCRIBE"],
				"ARTICLE" => $arItem["ARTICLE"],
				"PRICES" => $arItem["PRICES"],
				"PRICE_MATRIX" => $arItem["PRICE_MATRIX"],
				"PROPERTIES" => $arItem["PROPERTIES"],
				"DISPLAY_PROPERTIES" => $arItem["DISPLAY_PROPERTIES"],
				"URL" => $arItem["DETAIL_PAGE_URL"],
				"TOTAL_COUNT" => CMax::GetTotalCount($arItem, $arPost["PARAMS"]),
				"IPROPERTY_VALUES" => $arItem["IPROPERTY_VALUES"],
			);
		}

		if(\Bitrix\Main\Config\Option::get("main", "component_cache_on", "Y") != "N")
		{
			$obCache->StartDataCache($cacheTime, $cacheID, $cachePath);

			if(strlen($cacheTag)){
				global $CACHE_MANAGER;
				$CACHE_MANAGER->StartTagCache($cachePath);
				$CACHE_MANAGER->RegisterTag($cacheTag);
				$CACHE_MANAGER->EndTagCache();
			}

			$obCache->EndDataCache(array("arItems" => $arItems));
		}
		/**/
	}
	
	/*format items*/
	if($arItems)
	{
		foreach($arItems["ITEMS"] as $key => $arItem)
		{
			$arItems["ITEMS"][$key]["MIN_PRICE"] = false;
			if(!empty($arItem["PRICES"]))
			{
				foreach ($arItem['PRICES'] as &$arOnePrice)
				{
					if ($arOnePrice['MIN_PRICE'] == 'Y')
					{
						$arItems["ITEMS"][$key]["MIN_PRICE"] = $arOnePrice;
						$arItem["MIN_PRICE"] = $arOnePrice;
						break;
					}
				}
				unset($arOnePrice);
			}

			$arAddToBasketData = CMax::GetAddToBasketArray($arItem, $arItem["TOTAL_COUNT"], $arPost["PARAMS"]["DEFAULT_COUNT"], $arPost["PARAMS"]["BASKET_URL"], false, array(), 'btn-exlg', $arPost["PARAMS"]);
			$arAddToBasketData["HTML"] = str_replace('data-item', 'data-props="'.implode(';', (array)$arPost["PARAMS"]['OFFERS_CART_PROPERTIES']).'" data-item', $arAddToBasketData["HTML"]);

			$arItems["ITEMS"][$key]["PRICES_HTML"] = \Aspro\Functions\CAsproMaxItem::showItemPrices($arPost["PARAMS"], $arItem["PRICES"], $arItem['MEASURE'], $arItem["MIN_PRICE"]["ID"], ($arPost["PARAMS"]['SHOW_DISCOUNT_PERCENT_NUMBER'] == "Y" ? "N" : "Y"), false, true);
			$arItems["ITEMS"][$key]["MAX_QUANTITY"] = $arItem["TOTAL_COUNT"];
			$arItems["ITEMS"][$key]["STEP_QUANTITY"] = $arItem["CATALOG_MEASURE_RATIO"];
			$arItems["ITEMS"][$key]["QUANTITY_FLOAT"] = is_double($arItem["CATALOG_MEASURE_RATIO"]);
			$arItems["ITEMS"][$key]["AVAILIABLE"] = CMax::GetQuantityArray($arItem["TOTAL_COUNT"]);
			$arItems["ITEMS"][$key]["CONFIG"] = $arAddToBasketData;
			$arItems["ITEMS"][$key]["HTML"] = $arAddToBasketData["HTML"];

			if($arPost["PARAMS"]["SHOW_GALLERY"] == "Y")
			{
				$arItem["DETAIL_PICTURE"] = $arItem["PREVIEW_PICTURE_FIELD"];
				$arItems["ITEMS"][$key]["GALLERY"] = CMax::getSliderForItemExt($arItem, $arPost["PARAMS"]["OFFER_ADD_PICT_PROP"], true);

				// if(!$arItems["ITEMS"][$key]["GALLERY"] && $arItems['MAIN_ITEM'])
				if($arItems['MAIN_ITEM'] && 'Y' == $arPost["PARAMS"]['ADD_DETAIL_TO_SLIDER'])
				{
					$arItems["ITEMS"][$key]["GALLERY"] = array_merge($arItems["ITEMS"][$key]["GALLERY"], CMax::getSliderForItemExt($arItems['MAIN_ITEM'], $arPost["PARAMS"]["ADD_PICT_PROP"], 'Y' == $arPost["PARAMS"]['ADD_DETAIL_TO_SLIDER']));
				}

				array_splice($arItems["ITEMS"][$key]["GALLERY"], $arPost["PARAMS"]["MAX_GALLERY_ITEMS"]);
				$arItems["ITEMS"][$key]["GALLERY_HTML"] = \Aspro\Functions\CAsproMaxItem::showSectionGallery( array('ITEM' => $arItems["ITEMS"][$key], 'RETURN' => true) );
			}

			$arItems["ITEMS"][$key]["SHOW_ONE_CLICK_BUY"] = ($arPost["PARAMS"]["SHOW_ONE_CLICK_BUY"] ? $arPost["PARAMS"]["SHOW_ONE_CLICK_BUY"] : "N");

			// for list view
			//$arPost["PARAMS"]["SHOW_ONE_CLICK_BUY"] = "Y";//comment cause its broke html when ocb off  http://joxi.ru/LmGvoGxiJ7R8Zr
			$arPost["PARAMS"]["IBLOCK_ID"] = $arItem["IBLOCK_ID"];
			$arItems["ITEMS"][$key]["ONE_CLICK_BUY_HTML"] = \Aspro\Functions\CAsproMax::showItemOCB($arAddToBasketData, $arItem, $arPost["PARAMS"], true);

			$arItems["ITEMS"][$key]["DISPLAY_COMPARE"] = ($arPost["PARAMS"]["DISPLAY_COMPARE"] ? $arPost["PARAMS"]["DISPLAY_COMPARE"] : "N");
			$arItems["ITEMS"][$key]["DISPLAY_WISH_BUTTONS"] = ($arPost["PARAMS"]["DISPLAY_WISH_BUTTONS"] ? $arPost["PARAMS"]["DISPLAY_WISH_BUTTONS"] : "N");

			$arItems["ITEMS"][$key]["CAN_BUY"] = ($arPost["PARAMS"]['USE_REGION'] == "Y" ? $arAddToBasketData["CAN_BUY"] : $arItem["CAN_BUY"]);

			$arItem['ITEM_PRICES'] = array();
			if($arItem["PRICE_MATRIX"])
			{
				$arItems["ITEMS"][$key]["PRICE_MATRIX_HTML"] = CMax::showPriceMatrix($arItem, $arPost["PARAMS"], $arItem['MEASURE']);
				foreach($arItem['PRICE_MATRIX']['ROWS'] as $range => $arInterval)
				{
					$minimalPrice = null;
					foreach($arItem['PRICE_MATRIX']['MATRIX'] as $arPrice)
					{
						if($arPrice[$range])
						{
							if($minimalPrice === null || $minimalPrice['DISCOUNT_PRICE'] > $arPrice[$range]['DISCOUNT_PRICE'])
							{
								if($arPrice[$range]['PRICE'] > $arPrice[$range]['DISCOUNT_PRICE'])
								{
									$arPrice[$range]['PERCENT'] = round((($arPrice[$range]['PRICE']-$arPrice[$range]['DISCOUNT_PRICE'])/$arPrice[$range]['PRICE'])*100);
									$arPrice[$range]['DIFF'] = ($arPrice[$range]['PRICE']-$arPrice[$range]['DISCOUNT_PRICE']);
									$arPrice[$range]['PRINT_DIFF'] = CCurrencyLang::CurrencyFormat($arPrice[$range]['PRICE']-$arPrice[$range]['DISCOUNT_PRICE'], $arPrice[$range]['CURRENCY'], true);
								}
								$minimalPrice = $arPrice[$range];
							}
						}
					}
					$arItem['ITEM_PRICES'][$range] = $minimalPrice;
				}
				// $arItems["ITEMS"][$key]["PRICE_MATRIX_TOP_HTML"] = CMax::showPriceRangeTop($arItem, $arPost["PARAMS"], GetMessage("CATALOG_ECONOMY"));
			}
			$arItems["ITEMS"][$key]["ITEM_PRICES"] = $arItem['ITEM_PRICES'];
			$arItems["ITEMS"][$key]["CATALOG_SUBSCRIBE"] = $arItem['CATALOG_SUBSCRIBE'];
			$arItems["ITEMS"][$key]["IPROPERTY_VALUES"] = $arItem['IPROPERTY_VALUES'];

			$arItems["ITEMS"][$key]["SHOW_OLD_PRICE"] = ($arPost["PARAMS"]['SHOW_OLD_PRICE'] == 'Y');
			$arItems["ITEMS"][$key]["PRODUCT_QUANTITY_VARIABLE"] = $arPost["PARAMS"]['PRODUCT_QUANTITY_VARIABLE'];
			$arItems["ITEMS"][$key]["SHOW_DISCOUNT_PERCENT"] = ($arPost["PARAMS"]['SHOW_DISCOUNT_PERCENT'] == 'Y');
			$arItems["ITEMS"][$key]["SHOW_POPUP_PRICE"] = ($arPost["PARAMS"]['SHOW_POPUP_PRICE'] == 'Y');
			$arItems["ITEMS"][$key]["SHOW_SKU_PROPS"] = $arPost["PARAMS"]['SHOW_SKU_PROPS'];
			$arItems["ITEMS"][$key]["SHOW_DISCOUNT_TIME_EACH_SKU"] = $arPost["PARAMS"]['SHOW_DISCOUNT_TIME_EACH_SKU'];
			$arItems["ITEMS"][$key]["SHOW_MEASURE"] = ($arPost["PARAMS"]['SHOW_MEASURE'] == "Y" ? "Y" : "N");
			$arItems["ITEMS"][$key]["USE_PRICE_COUNT"] = $arPost["PARAMS"]['USE_PRICE_COUNT'];
			$arItems["ITEMS"][$key]["SHOW_DISCOUNT_PERCENT_NUMBER"] = ($arPost["PARAMS"]['SHOW_DISCOUNT_PERCENT_NUMBER'] == 'Y');
			$arItems["ITEMS"][$key]["SHOW_ARTICLE_SKU"] = $arPost["PARAMS"]['SHOW_ARTICLE_SKU'];
			$arItems["ITEMS"][$key]["ARTICLE_SKU"] = ($arPost["PARAMS"]['SHOW_ARTICLE_SKU'] == 'Y' ? (isset($arPost['ARTICLE_VALUE']) && $arPost['ARTICLE_VALUE'] ? $arPost['ARTICLE_NAME'].': '.$arPost['ARTICLE_VALUE'] : '') : '');
		}
		unset($arItem);
	}
	/**/

	$offerShowPreviewPictureProps = array();
	if($arPost["PARAMS"]['OFFER_SHOW_PREVIEW_PICTURE_PROPS'] && is_array($arPost["PARAMS"]['OFFER_SHOW_PREVIEW_PICTURE_PROPS'])){
		foreach($arPost["PARAMS"]['OFFER_SHOW_PREVIEW_PICTURE_PROPS'] as $strOneCode){
			if(isset($arSKUPropList[$strOneCode])){
				$offerShowPreviewPictureProps[] = $arSKUPropList[$strOneCode]['ID'];
			}
		}
	}
	?>
	<script>
		/* functions */
		GetRowValues = function(arFilter, index)
		{
			var i = 0,
				j,
				arValues = [],
				boolSearch = false,
				boolOneSearch = true;

			if (0 === arFilter.length)
			{
				for (i = 0; i < obOffers.length; i++)
				{
					if (!BX.util.in_array(obOffers[i].TREE[index], arValues))
						arValues[arValues.length] = obOffers[i].TREE[index];
				}
				boolSearch = true;
			}
			else
			{
				for (i = 0; i < obOffers.length; i++)
				{
					boolOneSearch = true;
					for (j in arFilter)
					{
						if (arFilter[j])
						{
							if (arFilter[j].toString() !== obOffers[i].TREE[j])
							{
								boolOneSearch = false;
								break;
							}
						}
					}

					if (boolOneSearch)
					{
						if (!BX.util.in_array(obOffers[i].TREE[index], arValues))
							arValues[arValues.length] = obOffers[i].TREE[index];
						boolSearch = true;
					}
				}
			}
			return (boolSearch ? arValues : false);
		};

		GetCanBuy = function(arFilter)
		{
			var i = 0,
				j,
				boolSearch = false,
				boolOneSearch = true;

			for (i = 0; i < obOffers.length; i++)
			{
				boolOneSearch = true;
				for (j in arFilter)
				{
					if (arFilter[j] !== obOffers[i].TREE[j])
					{
						boolOneSearch = false;
						break;
					}
				}
				if (boolOneSearch)
				{
					if (obOffers[i].CAN_BUY)
					{
						boolSearch = true;
						break;
					}
				}
			}
			return boolSearch;
		};

		checkPriceRange = function(quantity, obj)
		{
			if (typeof quantity === 'undefined'|| !obj.PRICE_MATRIX)
				return;

			var range, found = false, rangeSelected = '';
			for(var i in obj.PRICE_MATRIX.ROWS)
			{
				if(obj.PRICE_MATRIX.ROWS.hasOwnProperty(i))
				{
					range = obj.PRICE_MATRIX.ROWS[i];
					if(
						parseInt(quantity) >= parseInt(range.QUANTITY_FROM)
						&& (
							range.QUANTITY_TO == '0'
							|| parseInt(quantity) <= parseInt(range.QUANTITY_TO)
						)
					)
					{
						found = true;
						return i;
						break;
					}
				}
			}

			if(!found && (range = getMinPriceRange(obj)))
			{
				rangeSelected = range;

				return rangeSelected;
			}

			for(var k in obj.ITEM_PRICES)
			{
				if(obj.ITEM_PRICES.hasOwnProperty(k))
				{
					if(k == rangeSelected)
					{
						return k;
						break;
					}
				}
			}
		};

		getMinPriceRange = function(obj)
		{
			var range, found = '';

			for(var i in obj.PRICE_MATRIX.ROWS)
			{
				if(obj.PRICE_MATRIX.ROWS.hasOwnProperty(i))
				{
					if(
						!range
						|| parseInt(obj.PRICE_MATRIX.ROWS[i].QUANTITY_FROM) < parseInt(range.QUANTITY_FROM)
					)
					{
						range = obj.PRICE_MATRIX.ROWS[i];
						found = i;
					}
				}
			}

			return i;
		}

		/*set blocks*/
		setActualDataBlock = function(th, obj)
		{
			var size = 0;

			if(obj.DISPLAY_WISH_BUTTONS == "Y")
			{
				if(obj.CAN_BUY)
					size++;
			}

			if(obj.DISPLAY_COMPARE == "Y")
			{
				size++;
			}

			if(obj.SHOW_ONE_CLICK_BUY == "Y")
			{
				if(obj.CAN_BUY)
				{
					size++;
				}
			}

			th.find('.like_icons').attr('data-size', size);

			/*wish|like*/
			setLikeBlock(th, '.like_icons .wish_item_button', obj, 'DELAY');
			setLikeBlock(th, '.like_icons .compare_item_button',obj, 'COMPARE');
			/**/

			/*buy*/
			setBuyBlock(th, obj);
			/**/
		}
		/**/

		/*set compare/wish*/
		setLikeBlock = function(th, className, obj, type)
		{
			var block=th;
			if(type=="DELAY")
			{
				if(obj.CAN_BUY)
					block.find(className).show();
				else
					block.find(className).hide();
			}

			block.find(className).attr('data-item', obj.ID);
			block.find(className).find('span').attr('data-item', obj.ID);

			if(arBasketAspro[type])
			{
				block.find(className).find('.to').removeClass('added').css('display','block');
				block.find(className).find('.in').hide();

				if(arBasketAspro[type][obj.ID]!==undefined)
				{
					block.find(className).find('.to').hide();
					block.find(className).find('.in').addClass('added').css('display','block');
				}
			}
		}
		/**/

		/*set buy*/
		setBuyBlock = function(th, obj, index)
		{
			var buyBlock=th.find('.offer_buy_block').first(),
				input_value = obj.CONFIG.MIN_QUANTITY_BUY,
				bList = (buyBlock.find('.counter_wrapp.list').length);

			if(buyBlock.find('.counter_wrapp .counter_block').length)
				buyBlock.find('.counter_wrapp .counter_block').attr('data-item', obj.ID);

			if(typeof window["obSkuQuantys"][obj.ID] != "undefined")
				input_value = window["obSkuQuantys"][obj.ID];

			if((obj.CONFIG.OPTIONS.USE_PRODUCT_QUANTITY_LIST && obj.CONFIG.ACTION == "ADD") && obj.CAN_BUY)
			{
				var max=(obj.CONFIG.MAX_QUANTITY_BUY>0 ? "data-max='"+obj.CONFIG.MAX_QUANTITY_BUY+"'" : ""),
					counterHtml='<span class="minus dark-color"><?=\CMax::showIconSvg("wish ncolor colored1", SITE_TEMPLATE_PATH."/images/svg/minus.svg");?></span>'+
						'<input type="text" class="text" name="'+obj.PRODUCT_QUANTITY_VARIABLE+'" value="'+input_value+'" />'+
						'<span class="plus dark-color" '+max+'><?=\CMax::showIconSvg("wish ncolor colored1", SITE_TEMPLATE_PATH."/images/svg/plus.svg");?></span>';

				if(arBasketAspro["BASKET"] && arBasketAspro["BASKET"][obj.ID]!==undefined)
				{
					if(buyBlock.find('.counter_wrapp .counter_block').length)
					{
						buyBlock.find('.counter_wrapp .counter_block').hide();
					}
					else
					{
						if(bList)
							buyBlock.find('.counter_wrapp').prepend('<div class="counter_block_inner"><div class="counter_block big" data-item="'+obj.ID+'"></div></div>');
						else
							buyBlock.find('.counter_wrapp').prepend('<div class="counter_block big" data-item="'+obj.ID+'"></div>');
						buyBlock.find('.counter_wrapp .counter_block').html(counterHtml).hide();
					}
				}
				else
				{
					if(buyBlock.find('.counter_wrapp .counter_block').length)
					{
						buyBlock.find('.counter_wrapp .counter_block_inner').show();
						buyBlock.find('.counter_wrapp .counter_block').html(counterHtml).show();
					}
					else
					{
						if(bList)
							buyBlock.find('.counter_wrapp').prepend('<div class="counter_block_inner"><div class="counter_block big" data-item="'+obj.ID+'"></div></div>');
						else
							buyBlock.find('.counter_wrapp').prepend('<div class="counter_block big" data-item="'+obj.ID+'"></div>');
						buyBlock.find('.counter_wrapp .counter_block').html(counterHtml);
					}
				}
			}
			else
			{
				if(buyBlock.find('.counter_wrapp .counter_block').length)
					buyBlock.find('.counter_wrapp .counter_block').hide();
			}

			var className=((obj.CONFIG.ACTION == "ORDER") || !obj.CAN_BUY || !obj.CONFIG.OPTIONS.USE_PRODUCT_QUANTITY_LIST || (obj.CONFIG.ACTION == "SUBSCRIBE" && obj.CATALOG_SUBSCRIBE == "Y") ? "wide" : "" ),
				buyBlockBtn=$('<div class="button_block"></div>');

			th.find('.like_icons.block .wrapp_one_click').empty();
			if(obj.CAN_BUY)
			{
				th.find('.like_icons.block .wrapp_one_click').html('<span class="rounded2 colored_theme_hover_bg" data-item="'+obj.ID+'" data-iblockID="'+obj.IBLOCK_ID+'" data-quantity="'+obj.CONFIG.MIN_QUANTITY_BUY+'" onclick="oneClickBuy(\''+obj.ID+'\', \''+obj.IBLOCK_ID+'\', this)" title="<?=GetMessage('ONE_CLICK_BUY')?>">'+
					'<?=\CMax::showIconSvg("fw ncolor colored", SITE_TEMPLATE_PATH."/images/svg/quickbuy.svg");?>'+
					'</span>');
			}

			if(buyBlock.find('.button_block').length)
			{
				if(arBasketAspro["BASKET"] && arBasketAspro["BASKET"][obj.ID]!==undefined)
				{
					buyBlock.find('.button_block').addClass('wide').html(obj.HTML);
					markProductAddBasket(obj.ID);
				}
				else
				{
					if(className)
					{
						buyBlock.find('.button_block').addClass('wide').html(obj.HTML);
						if(arBasketAspro["SUBSCRIBE"] && arBasketAspro["SUBSCRIBE"][obj.ID]!==undefined)
							markProductSubscribe(obj.ID);
					}
					else
					{
						buyBlock.find('.button_block').removeClass('wide').html(obj.HTML);
					}
				}
			}
			else
			{
				buyBlock.find('.counter_wrapp').append('<div class="button_block '+className+'">'+obj.HTML+'</div>');
				if(arBasketAspro["BASKET"] && arBasketAspro["BASKET"][obj.ID]!==undefined)
					markProductAddBasket(obj.ID);
				if(arBasketAspro["SUBSCRIBE"] && arBasketAspro["SUBSCRIBE"][obj.ID]!==undefined)
					markProductSubscribe(obj.ID);
			}

			if(th.closest('.list').length) // is list view
			{
				var inner = buyBlock.parent();

				if(inner.find('.wrapp-one-click').length)
					inner.find('.wrapp-one-click').remove();

				if(obj.CONFIG.ACTION !== "NOTHING")
				{
					inner.append(obj.ONE_CLICK_BUY_HTML);
					/*if(obj.CONFIG.ACTION == "ADD" && obj.CAN_BUY && obj.SHOW_ONE_CLICK_BUY!="N")
					{
						var ocb='<span class="transparent big_btn type_block button one_click" data-offers="Y" data-item="'+obj.ID+'" data-iblockID="'+obj.IBLOCK_ID+'" data-quantity="'+obj.CONFIG.MIN_QUANTITY_BUY+'" data-props="'+obj.OFFER_PROPS+'" onclick="oneClickBuy('+obj.ID+', '+obj.IBLOCK_ID+', this)">'+
							'<span>'+obj.ONE_CLICK_BUY+'</span>'+
							'</span>';
						if(buyBlock.find('.wrapp_one_click').length)
							buyBlock.find('.wrapp_one_click').html(ocb);
						else
							buyBlock.append('<div class="wrapp_one_click">'+ocb+'</div>');
					}
					else
					{
						if(buyBlock.find('.wrapp-one-click').length)
							buyBlock.find('.wrapp-one-click').remove();
					}*/
				}
				else
				{
					if(inner.find('.wrapp-one-click').length)
						inner.find('.wrapp-one-click').remove();
				}
			}

			buyBlock.fadeIn();
			buyBlock.find('.counter_wrapp .counter_block input').data('product', 'obOffers');
			setPriceAction(obj, 'Y', '');

			setTimeout(function(){
				$('.<?=$arPost["CLASS"]?>.js_offers__<?=$arPost["LINK_ID"]?>').closest('.catalog_block').find('.catalog_item_wrapp.catalog_item:visible').sliceHeight({
					classNull: '.footer_button', 
					item: '.cur .catalog_block .catalog_item_wrapp.catalog_item:visible', 
					classes: [
						'.item_info .item-title',
						'.item_info .sa_block',
						'.item_info .cost.prices',
					],
					mobile: true,
					blockNull: {
						class: 'big',
						width: 2,
					},
					breakpoint: {
						'(max-width: 600px)': {
							blockNull: {
								width: 1,
							},
						}
					},
					resize: false,
					currentRow: true,
					currentItem: th.find('.catalog_item_wrapp.catalog_item')[0],
				});
			}, 100);
		}
		/**/

		setPriceAction = function(obj, sku, change)
		{
			if(obj == "" || typeof obj === "undefined")
				obj = obOffers[wrapper.find('.counter_wrapp').data('index')];

			var measure = obj.MEASURE && obj.SHOW_MEASURE=="Y" ? obj.MEASURE : '';
			var check_quantity = '',
				currentPriceSelected = '',
				is_sku = (typeof sku !== 'undefined' && sku == 'Y');

			window["obSkuQuantys"][obj.ID] = obj.CONFIG.MIN_QUANTITY_BUY;
			if(wrapper.find('input[name=quantity]').length)
				window["obSkuQuantys"][obj.ID] = wrapper.find('input[name=quantity]').val();


			if(obj.USE_PRICE_COUNT && obj.PRICE_MATRIX)
			{
				currentPriceSelected = checkPriceRange(window["obSkuQuantys"][obj.ID], obj);

				setPriceMatrix(obj.PRICE_MATRIX_HTML, obj, currentPriceSelected);
			}
			else
			{
				if('PRICES' in obj && obj.PRICES)
				{
					setPrice(obj.PRICES, measure, obj);
				}
			}

			if(arMaxOptions['THEME']['SHOW_TOTAL_SUMM'] == 'Y')
			{
				if(obj.check_quantity)
					check_quantity = 'Y';
				else
				{
					var check_quantity = ((typeof change !== 'undefined' && change == 'Y') ? change : '');
					if(check_quantity)
						obj.check_quantity = true;
				}
				// if(arMaxOptions["THEME"]["SHOW_TOTAL_SUMM_TYPE"] == "ALWAYS")
					check_quantity = is_sku = '';

				if(typeof obj.ITEM_PRICES[currentPriceSelected] !== 'undefined')
				{
					setPriceItem(wrapper, window["obSkuQuantys"][obj.ID], obj.ITEM_PRICES[currentPriceSelected].DISCOUNT_PRICE, check_quantity, is_sku);
				}
				else
				{
					setPriceItem(wrapper, window["obSkuQuantys"][obj.ID], obj.MIN_PRICE.DISCOUNT_VALUE, check_quantity, is_sku);
				}
			}
		}

		setPriceMatrix = function(sPriceMatrix, obj, currentPriceSelected)
		{
			var prices = '';

			if (wrapper.find('.cost').length)
			{

				var measure = obj.MEASURE && obj.SHOW_MEASURE=="Y" ? obj.MEASURE : '',
					strPrice = '';
				strPrice = getCurrentPrice(obj.ITEM_PRICES[currentPriceSelected].DISCOUNT_PRICE, obj.ITEM_PRICES[currentPriceSelected].CURRENCY, obj.ITEM_PRICES[currentPriceSelected].PRINT_DISCOUNT_PRICE);
				if(measure)
					strPrice += '<span class="price_measure">/'+measure+'</span>';
				wrapper.find('.not_matrix').hide();
				wrapper.find('.with_matrix .price_value_block').html(strPrice);

				if(obj.SHOW_OLD_PRICE)
				{
					if(parseFloat(obj.ITEM_PRICES[currentPriceSelected].PRICE) > parseFloat(obj.ITEM_PRICES[currentPriceSelected].DISCOUNT_PRICE))
					{
						wrapper.find('.with_matrix .discount .values_wrapper').html(getCurrentPrice(obj.ITEM_PRICES[currentPriceSelected].PRICE, obj.ITEM_PRICES[currentPriceSelected].CURRENCY, obj.ITEM_PRICES[currentPriceSelected].PRINT_PRICE));
						wrapper.find('.with_matrix .discount').css('display', 'inline-block');
					}
					else
					{
						wrapper.find('.with_matrix .discount').html('');
						wrapper.find('.with_matrix .discount').css('display', 'none');
					}
				}
				else
				{
					wrapper.find('.with_matrix .discount').html('');
					wrapper.find('.with_matrix .discount').css('display', 'none');
				}

				if(obj.ITEM_PRICES[currentPriceSelected].PERCENT > 0)
				{
					if(obj.SHOW_DISCOUNT_PERCENT_NUMBER)
					{
						if(obj.ITEM_PRICES[currentPriceSelected].PERCENT > 0 && obj.ITEM_PRICES[currentPriceSelected].PERCENT < 100)
						{
							if(!wrapper.find('.with_matrix .sale_block .sale_wrapper .value').length)
								$('<div class="value"></div>').insertBefore(wrapper.find('.with_matrix .sale_block .sale_wrapper .text'));

							wrapper.find('.with_matrix .sale_block .sale_wrapper .value').html('-<span>'+obj.ITEM_PRICES[currentPriceSelected].PERCENT+'</span>%');
						}
						else
						{
							if(wrapper.find('.with_matrix .sale_block .sale_wrapper .value').length)
								wrapper.find('.with_matrix .sale_block .sale_wrapper .value').remove();
						}
					}

					wrapper.find('.with_matrix .sale_block .text .values_wrapper').html(getCurrentPrice(obj.ITEM_PRICES[currentPriceSelected].DIFF, obj.ITEM_PRICES[currentPriceSelected].CURRENCY, obj.ITEM_PRICES[currentPriceSelected].PRINT_DIFF));
					wrapper.find('.with_matrix .sale_block').show();
				}
				else
				{
					wrapper.find('.with_matrix .sale_block').hide();
				}

				wrapper.find('.sale_block.normal').hide();
				wrapper.find('.with_matrix').show();

				if(obj.SHOW_DISCOUNT_PERCENT)
				{
					wrapper.find('.cost > .price:not(.discount)').closest('.cost').find('.sale_block:not(.matrix)').hide();
					wrapper.find('.cost > .price:not(.discount)').closest('.cost').find('.sale_block:not(.matrix) .text span').html('');
				}

				// BX.adjust(wrapper.find('.cost .js_price_wrapper')[0], {html: sPriceMatrix});
				wrapper.find('.cost .js_price_wrapper').html(obj.PRICE_MATRIX_HTML);
				if(obj.SHOW_POPUP_PRICE)
					wrapper.find('.cost .js_price_wrapper').append('<div class="js-show-info-block more-item-info rounded3 bordered-block text-center"><?=\CMax::showIconSvg("fw", SITE_TEMPLATE_PATH."/images/svg/dots.svg");?></div>');

				var eventdata = {product: wrapper, measure: measure, config: this.config, offer: obj, obPrice: obj.ITEM_PRICES[currentPriceSelected]};
				BX.onCustomEvent('onAsproSkuSetPriceMatrix', [eventdata])
			}
		}

		setPrice = function(obPrices, measure, obj)
		{
			var strPrice,
				obData;

			if (wrapper.find('.cost.prices').length){
				var measure = obj.MEASURE && obj.SHOW_MEASURE=="Y" ? obj.MEASURE : '',
					product = wrapper,
					obPrices = obj.PRICES;
				if(typeof(obPrices) == 'object')
				{
					wrapper.find('.with_matrix').hide();
					/*var strPrice = '',
						count = Object.keys(obPrices).length,
						arStikePrices = [];

					if(arMaxOptions['THEME']['DISCOUNT_PRICE'])
					{
						arStikePrices = arMaxOptions['THEME']['DISCOUNT_PRICE'].split(',');
					}

					strPrice = '<div class="offers_price_wrapper price_matrix_wrapper">';
					wrapper.find('.not_matrix').show();
					for(var j in obPrices)
					{
						if(obPrices[j] && obPrices[j].VALUE > 0)
						{
							if('GROUP_NAME' in obPrices[j])
							{
								if(count > 1)
								{
									strPrice += '<div class="offers_price_title">';
									strPrice += obPrices[j].GROUP_NAME;
									strPrice += '</div>';
								}
							}
							strPrice += '<div class="prices-wrapper"><div class="price font-bold font_mxs '+(arStikePrices ? (BX.util.in_array(obPrices[j].PRICE_ID, arStikePrices) ? ' strike_block' : '') : '')+'">';
								strPrice += '<span class="values_wrapper">'+getCurrentPrice(obPrices[j].DISCOUNT_VALUE, obPrices[j].CURRENCY, obPrices[j].PRINT_DISCOUNT_VALUE)+'</span>';
								if(measure)
									strPrice += '<span class="price_measure">/'+measure+'</span>';

							strPrice += '</div>';
							if (obPrices[j].DISCOUNT_VALUE !== obPrices[j].VALUE)
							{
								if(obj.SHOW_OLD_PRICE)
								{
									strPrice += '<div class="price discount">';
										strPrice += '<span class="values_wrapper font_xs muted">'+getCurrentPrice(obPrices[j].VALUE, obPrices[j].CURRENCY, obPrices[j].PRINT_VALUE)+'</span>';
									strPrice += '</div>';
								}
							}
							strPrice += '</div>';
							if (obPrices[j].DISCOUNT_VALUE !== obPrices[j].VALUE)
							{
								if(obj.SHOW_DISCOUNT_PERCENT)
								{
									if(!obj.SHOW_DISCOUNT_PERCENT_NUMBER || (obj.SHOW_DISCOUNT_PERCENT_NUMBER && (obPrices[j].DISCOUNT_DIFF_PERCENT <= 0 && obPrices[j].DISCOUNT_DIFF_PERCENT >= 100)))
									{
										strPrice += '<div class="sale_block matrix"><div class="sale_wrapper font_xxs"><div class="inner-sale rounded1">';
											strPrice += '<span class="title">'+BX.message('ITEM_ECONOMY')+'</span>';
											strPrice += '<div class="text">';
												strPrice += '<span class="values_wrapper">'+getCurrentPrice(obPrices[j].DISCOUNT_DIFF, obPrices[j].CURRENCY, obPrices[j].PRINT_DISCOUNT_DIFF)+'</span>';
											strPrice += '</div>';
										strPrice += '<div class="clearfix"></div></div></div></div>';
									}
									else
									{
										strPrice += '<div class="sale_block matrix"><div class="sale_wrapper font_xxs"><div class="sale-number rounded2">';
											strPrice += '<div class="value">-<span>'+obPrices[j].DISCOUNT_DIFF_PERCENT+'</span>%</div>';
											strPrice += '<div class="inner-sale rounded1"><div class="text">';
												strPrice += '<span class="title">'+BX.message('ITEM_ECONOMY')+'</span> ';
												strPrice += '<span class="values_wrapper">'+getCurrentPrice(obPrices[j].DISCOUNT_DIFF, obPrices[j].CURRENCY, obPrices[j].PRINT_DISCOUNT_DIFF)+'</span>';
											strPrice += '</div></div>';
										strPrice += '<div class="clearfix"></div></div></div>';
									}
								}
							}
						}
						else
						{
							$('.prices_block .cost.prices').hide();
						}
					}
					if(obj.SHOW_DISCOUNT_PERCENT)
					{
						wrapper.find('.cost').find('.sale_block:not(.matrix)').hide();
						wrapper.find('.cost').find('.sale_block:not(.matrix) .text span').html('');
					}
					if(obj.SHOW_OLD_PRICE)
					{
						wrapper.find('.cost').find('.price.discount').hide();
					}

					strPrice += '</div>';
					wrapper.find('.cost .js_price_wrapper').html(strPrice);*/
					wrapper.find('.cost .js_price_wrapper').html(obj.PRICES_HTML);

					var eventdata = {product: product, measure: measure, config: this.config, offer: obj, obPrices: obPrices};
					BX.onCustomEvent('onAsproSkuSetPrice', [eventdata])
				}
			}
		};

		/*set store quantity*/
		setQuantityStore = function(ob, text)
		{
			if(parseFloat(ob.MAX_QUANTITY)>0)
				wrapper.find('.item-stock .icon').removeClass('order').addClass('stock');
			else
				wrapper.find('.item-stock .icon').removeClass('stock').addClass('order');
			wrapper.find('.item-stock .icon + span').html(text);
			// wrapper.find('.item-stock').addClass('js-show-stores').data('id', ob.ID);
			wrapper.find('.item-stock').data('id', ob.ID);
			wrapper.find('.sa_block .js-info-block').remove();
		}

		ChangeInfo = function()
		{
			var i = 0,
				j,
				index = -1,
				compareParams,
				selectedValues = {},
				boolOneSearch = true;

			if($('.<?=$arPost["CLASS"]?>.js_offers__<?=$arPost["LINK_ID"]?> .bx_catalog_item_scu').data('selected'))
				selectedValues = $('.<?=$arPost["CLASS"]?>.js_offers__<?=$arPost["LINK_ID"]?> .bx_catalog_item_scu').data('selected');

			for (i = 0; i < obOffers.length; i++)
			{
				boolOneSearch = true;
				for (j in selectedValues)
				{
					if (selectedValues[j])
					{
						if (selectedValues[j].toString() !== obOffers[i].TREE[j])
						{
							boolOneSearch = false;
							break;
						}
					}
				}
				if (boolOneSearch)
				{
					index = i;
					break;
				}
			}

			if(-1 < index)
			{
				wrapper.find('.counter_wrapp').data('index', index); // set current sku
				if(obOffers[index].GALLERY)
				{
					obOffers[index].GALLERY_HTML = $(obOffers[index].GALLERY_HTML).children();
					wrapper.find('.image_wrapper_block .thumb').html(obOffers[index].GALLERY_HTML);
					InitLazyLoad();
				}
				else
				{
					if(!!obOffers[index].PICTURE) {
						wrapper.find('.thumb img').attr('src', obOffers[index].PICTURE);
					}
				}

				if(arMaxOptions["THEME"]["TYPE_SKU"] === "TYPE_1" && arMaxOptions["THEME"]["CHANGE_TITLE_ITEM"] === "Y"){
					var skuName = typeof obOffers[index].IPROPERTY_VALUES === 'object' && obOffers[index].IPROPERTY_VALUES.ELEMENT_PAGE_TITLE ? obOffers[index].IPROPERTY_VALUES.ELEMENT_PAGE_TITLE : obOffers[index].NAME;

					var skuAlt = typeof obOffers[index].PREVIEW_PICTURE_FIELD === 'object' && obOffers[index].PREVIEW_PICTURE_FIELD.DESCRIPTION ? obOffers[index].PREVIEW_PICTURE_FIELD.DESCRIPTION : (typeof obOffers[index].IPROPERTY_VALUES === 'object' && obOffers[index].IPROPERTY_VALUES.ELEMENT_PREVIEW_PICTURE_FILE_ALT ? obOffers[index].IPROPERTY_VALUES.ELEMENT_PREVIEW_PICTURE_FILE_ALT : obOffers[index].NAME);

					var skuTitle = typeof obOffers[index].PREVIEW_PICTURE_FIELD === 'object' && obOffers[index].PREVIEW_PICTURE_FIELD.DESCRIPTION ? obOffers[index].PREVIEW_PICTURE_FIELD.DESCRIPTION : (typeof obOffers[index].IPROPERTY_VALUES === 'object' && obOffers[index].IPROPERTY_VALUES.ELEMENT_PREVIEW_PICTURE_FILE_TITLE ? obOffers[index].IPROPERTY_VALUES.ELEMENT_PREVIEW_PICTURE_FILE_TITLE : obOffers[index].NAME);

					wrapper.find('.item-title span').html(skuName)
					wrapper.find('.image_wrapper_block img').attr('alt', decodeHtmlEntity(skuAlt))
					wrapper.find('.image_wrapper_block img').attr('title', decodeHtmlEntity(skuTitle))
				}

				if(!!obOffers[index].URL)
				{
					var arUrl = obOffers[index].URL.split("?");
					if(arUrl.length > 1)
					{
						var arUrl2 = wrapper.find('.item-title > a').attr('href').split("?");
						if(arUrl2.length > 1)
						{
							wrapper.find('.item-title > a').attr('href', wrapper.find('.item-title > a').attr('href').replace(arUrl2[1], arUrl[1]));
							wrapper.find('.thumb').attr('href', wrapper.find('.thumb').attr('href').replace(arUrl2[1], arUrl[1]));
						}
					}
				}

				if(wrapper.find('.total_summ').length)
					wrapper.find('.total_summ').slideUp();

				setActualDataBlock(wrapper, obOffers[index]);

				wrapper.find('.to-cart').data("item", obOffers[index].ID);

				setQuantityStore(obOffers[index], obOffers[index].AVAILIABLE.TEXT);

				if(wrapper.find('.article_block'))
				{
					var article_text = (obOffers[index].ARTICLE ? obOffers[index].ARTICLE : '');
					if(!article_text && obOffers[index].SHOW_ARTICLE_SKU == 'Y' && obOffers[index].ARTICLE_SKU)
						article_text = obOffers[index].ARTICLE_SKU;
					wrapper.find('.article_block > div').text(article_text);
				}

				if(wrapper.find('.quantity_block .values').length)
					wrapper.find('.quantity_block .values .item span.value').text(obOffers[index].MAX_QUANTITY).css({'opacity':'1'});

				<?//if($arPost['PARAMS']['SHOW_PROPS'] == 'Y'):?>
					if(wrapper.find('.properties').length)
					{
						var props = '';
						if(obOffers[index].DISPLAY_PROPERTIES)
						{
							if(wrapper.find('.properties.list').length)
							{
								for(var j in obOffers[index].DISPLAY_PROPERTIES)
								{
									props += '<div class="properties__item properties__item--compact font_xs">'+
									'<div class="properties__title properties__item--inline muted">'+obOffers[index].DISPLAY_PROPERTIES[j]['NAME']+'</div>'+
									'<div class="properties__hr properties__item--inline muted">&mdash;</div>'+
									'<div class="properties__value properties__item--inline darken">'+obOffers[index].DISPLAY_PROPERTIES[j]['VALUE']+'</div>'+
									'</div>';
								}
							}
							else
							{
								for(var j in obOffers[index].DISPLAY_PROPERTIES)
								{
									props += '<div class="properties__item">'+
									'<div class="properties__title font_sxs muted">'+obOffers[index].DISPLAY_PROPERTIES[j]['NAME']+'</div>'+
									'<div class="properties__value font_sm darken">'+obOffers[index].DISPLAY_PROPERTIES[j]['VALUE']+'</div>'+
									'</div>';
								}
							}
						}
						wrapper.find('.properties .properties__container_js').html(props);
					}
					else if(wrapper.find('.props_list.js-container').length)
					{
						var props = '';
						if(obOffers[index].DISPLAY_PROPERTIES)
						{
							for(var j in obOffers[index].DISPLAY_PROPERTIES)
							{
								props += '<tr>'+
								'<td><span>'+obOffers[index].DISPLAY_PROPERTIES[j]['NAME']+'</span></td>'+
								'<td><span>'+obOffers[index].DISPLAY_PROPERTIES[j]['VALUE']+'</span></td>'+
								'</tr>';
							}
						}
						wrapper.find('.props_list.js-container').html(props).show();
					}
				<?//endif;?>

				/*set discount*/
				if(obOffers[index].SHOW_DISCOUNT_TIME_EACH_SKU == 'Y')
					initCountdownTime(wrapper, obOffers[index].DISCOUNT_ACTIVE);
				/**/
			}
		};

		UpdateRowsImages = function()
		{
			if(typeof offerShowPreviewPictureProps === 'object' && offerShowPreviewPictureProps.length){
				var currentTree = $('.<?=$arPost["CLASS"]?>.js_offers__<?=$arPost["LINK_ID"]?> .bx_catalog_item_scu').data('selected');
				var $obTreeRows = $('.<?=$arPost["CLASS"]?>.js_offers__<?=$arPost["LINK_ID"]?> .bx_catalog_item_scu .item_wrapper');

				for(var i = 0, cnt = $obTreeRows.length; i < cnt; ++i){
					if(BX.util.in_array($obTreeRows.eq(i).find('>div').data('id'), offerShowPreviewPictureProps)){
						var RowItems = BX.findChildren($obTreeRows.eq(i).find('.list_values_wrapper')[0], {tagName: 'LI'}, false);
						if(!!RowItems && 0 < RowItems.length){
							for(var j in RowItems){
								var ImgItem = BX.findChild(RowItems[j], {className: 'cnt_item'}, true, false);
								if(ImgItem){
									var value = RowItems[j].getAttribute('data-onevalue');
									if(value != 0){
										var bgi = ImgItem.style.backgroundImage;
										var obgi = ImgItem.getAttribute('data-obgi');
										if(!obgi){
											obgi = bgi;
											ImgItem.setAttribute('data-obgi', obgi);
										}

										var boolOneSearch = false;
										var rowTree = BX.clone(currentTree, true);
										rowTree['PROP_' + $obTreeRows.eq(i).find('>div').data('id')] = value;

										for(var m in obOffers){
											boolOneSearch = true;
											for(var n in rowTree){
												if(rowTree[n] !== obOffers[m].TREE[n]){
													boolOneSearch = false;
													break;
												}
											}
											if(boolOneSearch){
												if(typeof obOffers[m].PREVIEW_PICTURE_FIELD === 'object' && obOffers[m].PREVIEW_PICTURE_FIELD.SRC){
													var newBgi = 'url("' + obOffers[m].PREVIEW_PICTURE_FIELD.SRC + '")';
													if(bgi !== newBgi){
														ImgItem.style.backgroundImage = newBgi;
														BX.addClass(ImgItem, 'pp');
													}
												}
												else{
													boolOneSearch = false;
												}
												break;
											}
										}

										for(var m in obOffers)
										{
											if(rowTree['PROP_' + $obTreeRows.eq(i).find('>div').data('id')] == obOffers[m].TREE['PROP_' + $obTreeRows.eq(i).find('>div').data('id')] && !boolOneSearch)
											{
												// if(typeof obOffers[m].PREVIEW_PICTURE === 'object' && obOffers[m].PREVIEW_PICTURE.SRC)
												if(typeof obOffers[m].PREVIEW_PICTURE_FIELD === 'object' && obOffers[m].PREVIEW_PICTURE_FIELD.SRC)
												{
													var newBgi = 'url("' + obOffers[m].PREVIEW_PICTURE_FIELD.SRC + '")';
													ImgItem.style.backgroundImage = newBgi;
													BX.addClass(ImgItem, 'pp');
													boolOneSearch = true;
												}
												break
											}
										}

										if(!boolOneSearch && obgi && bgi !== obgi){
											ImgItem.style.backgroundImage = obgi;
											BX.removeClass(ImgItem, 'pp');
										}
									}
								}
							}
						}
					}
				}
			}
		}

		UpdateRow = function(intNumber, activeID, showID, canBuyID)
		{
			var i = 0,
				showI = 0,
				value = '',
				countShow = 0,
				strNewLen = '',
				obData = {},
				obDataCont = {},
				pictMode = false,
				extShowMode = false,
				isCurrent = false,
				selectIndex = 0,
				obLeft = this.treeEnableArrow,
				obRight = this.treeEnableArrow,
				currentShowStart = 0,
				RowItems = null;

			if (-1 < intNumber && intNumber < $('.<?=$arPost["CLASS"]?>.js_offers__<?=$arPost["LINK_ID"]?> .bx_catalog_item_scu .item_wrapper').length){
				propMode = $('.<?=$arPost["CLASS"]?>.js_offers__<?=$arPost["LINK_ID"]?> .bx_catalog_item_scu .item_wrapper:eq('+intNumber+') > div').data('display_type');
				selectMode = ('SELECT' === propMode);

				var tag = (selectMode ? 'option' : 'li'),
					hideClass = (selectMode ? 'hidden' : 'missing');

				RowItems = BX.findChildren($('.<?=$arPost["CLASS"]?>.js_offers__<?=$arPost["LINK_ID"]?> .bx_catalog_item_scu .item_wrapper:eq('+intNumber+') .list_values_wrapper')[0], {tagName: tag}, false);
				if (!!RowItems && 0 < RowItems.length){
					countShow = showID.length;
					obData = {
						style: {},
						props: {
							disabled: '',
							selected: '',
						},
					};
					obDataCont = {
						style: {},
					};

					var listWrapper = $('.<?=$arPost["CLASS"]?>.js_offers__<?=$arPost["LINK_ID"]?> .bx_catalog_item_scu .item_wrapper:eq('+intNumber+') .list_values_wrapper');
					var max_scu_count = listWrapper.length ? listWrapper.data('maxCount') : 0;
					var current_count = 0;
					var more_hidden_count = 0;

					for (i = 0; i < RowItems.length; i++){
						value = RowItems[i].getAttribute('data-onevalue');
						isCurrent = (value === activeID && value !=0);
						if (BX.util.in_array(value, canBuyID)){
							var dop_class = '';
							if(max_scu_count && current_count >= max_scu_count) {
								dop_class = 'scu_prop_more';
								more_hidden_count++;
							}
							obData.props.className = (isCurrent ? 'active' : '') + dop_class;
							current_count++;
						}else{
							obData.props.className = (isCurrent ? 'active'+' '+hideClass : hideClass);
						}

						if(selectMode){
							obData.props.disabled = 'disabled';
							obData.props.selected = (isCurrent ? 'selected' : '');
						}else{
							obData.style.display = 'none';
							obData.props.className += ' item';
						}
						if (BX.util.in_array(value, showID)){
							if(selectMode){
								obData.props.disabled = '';
							}else{
								obData.style.display = '';
							}
							if (isCurrent){
								selectIndex = showI;
							}
							showI++;
						}
						BX.adjust(RowItems[i], obData);
					}

					if(max_scu_count) {
						var scu_item_wrapper = $('.<?=$arPost["CLASS"]?>.js_offers__<?=$arPost["LINK_ID"]?> .bx_catalog_item_scu .item_wrapper:eq('+intNumber+')');
						var more_link = scu_item_wrapper.find('.show_more_link');

						if(!more_hidden_count) {
							more_link.remove();
						} else {
							if(!more_link.length) {
								var link = wrapper.find('.item-title a').attr('href');
								more_link = $('<div class="show_more_link"><a class="font_sxs colored_theme_n_hover_bg-svg-stroke" href="' + link + '"></a></div>');
								scu_item_wrapper.append(more_link);
							}
							var titles = [
								BX.message('SHOW_MORE_SCU_1'),
								BX.message('SHOW_MORE_SCU_2'),
								BX.message('SHOW_MORE_SCU_3'),
							];
							var more_scu_mess = BX.message('SHOW_MORE_SCU_MAIN').replace('#COUNT#', declOfNum(more_hidden_count, titles));
							var svgHTML = 
							'<svg xmlns="http://www.w3.org/2000/svg" width="4" height="7" viewBox="0 0 4 7" fill="none">'
								+'<path d="M0.5 0.5L3.5 3.5L0.5 6.5" stroke="#333" stroke-linecap="round" stroke-linejoin="round"/>'
							+'</svg>';
							more_link.find('a').text(more_scu_mess).append(svgHTML);
							more_link.show();
						}
					}

					if(!showI || activeID == 0) // activeID is string, and can be '0' or ''
						obDataCont.style.display = 'none';
					else
						obDataCont.style.display = '';
					BX.adjust($('.<?=$arPost["CLASS"]?>.js_offers__<?=$arPost["LINK_ID"]?> .bx_catalog_item_scu .item_wrapper:eq('+intNumber+') > div')[0], obDataCont);

					if(selectMode){
						if($('.<?=$arPost["CLASS"]?>.js_offers__<?=$arPost["LINK_ID"]?> .bx_catalog_item_scu .item_wrapper:eq('+intNumber+') .list_values_wrapper').parent().hasClass('ik_select'))
							$('.<?=$arPost["CLASS"]?>.js_offers__<?=$arPost["LINK_ID"]?> .bx_catalog_item_scu .item_wrapper:eq('+intNumber+') .list_values_wrapper').ikSelect('reset');
					}
				}
			}
		};

		var decodeHtmlEntity = function(str) {
			return str.replace(/&#(\d+);/g, function(match, dec) {
				return String.fromCharCode(dec);
			});
		};

		/**/

		var strName = '',
			arShowValues = false,
			i, j,
			arCanBuyValues = [],
			selectedValues = JSON.parse('<?=$arSelectedProps?>'),
			obOffers = <?=CUtil::PhpToJSObject($arItems["ITEMS"], false, true)?>,
			offerShowPreviewPictureProps = <?=CUtil::PhpToJSObject($offerShowPreviewPictureProps, false, true)?>,
			allValues = [],
			strPropValue = '<?=$arPost['VALUE'];?>',
			depth = '<?=$arPost['DEPTH'];?>',
			wrapper = $('.<?=$arPost["CLASS"]?>.js_offers__<?=$arPost["LINK_ID"]?>').closest('.item'),
			arFilter = {},
			tmpFilter = [];
			
		if(typeof window["obSkuQuantys"] == "undefined")
			window["obSkuQuantys"] = {};

		for (i = 0; i < depth; i++)
		{
			strName = 'PROP_'+$('.<?=$arPost["CLASS"]?>.js_offers__<?=$arPost["LINK_ID"]?> .bx_catalog_item_scu .item_wrapper:eq('+i+') > div').data('id');
			arFilter[strName] = selectedValues[strName].toString();
		}

		strName = 'PROP_'+$('.<?=$arPost["CLASS"]?>.js_offers__<?=$arPost["LINK_ID"]?> .bx_catalog_item_scu .item_wrapper:eq('+depth+') > div').data('id');
		arShowValues = GetRowValues(arFilter, strName);

		if(arShowValues && BX.util.in_array(strPropValue, arShowValues))
		{
			if($('.<?=$arPost["CLASS"]?>.js_offers__<?=$arPost["LINK_ID"]?> .bx_catalog_item_scu').data('selected'))
				selectedValues = $('.<?=$arPost["CLASS"]?>.js_offers__<?=$arPost["LINK_ID"]?> .bx_catalog_item_scu').data('selected');

			arFilter[strName] = strPropValue;
			for (i = ++depth; i < $('.<?=$arPost["CLASS"]?>.js_offers__<?=$arPost["LINK_ID"]?> .bx_catalog_item_scu .item_wrapper').length; i++)
			{
				strName = 'PROP_'+$('.<?=$arPost["CLASS"]?>.js_offers__<?=$arPost["LINK_ID"]?> .bx_catalog_item_scu .item_wrapper:eq('+i+') > div').data('id');
				arShowValues = GetRowValues(arFilter, strName);

				if (!arShowValues)
					break;

				allValues = [];
				<?if($arPost["PARAMS"]["SHOW_ABSENT"]):?>
					arCanBuyValues = [];
					tmpFilter = [];
					// tmpFilter = BX.clone(arFilter, true);
					tmpFilter = arFilter;
					for (j = 0; j < arShowValues.length; j++)
					{
						tmpFilter[strName] = arShowValues[j];
						allValues[allValues.length] = arShowValues[j];
						if (GetCanBuy(tmpFilter))
						{
							arCanBuyValues[arCanBuyValues.length] = arShowValues[j];
						}
					}
				<?else:?>
					arCanBuyValues = arShowValues;
				<?endif;?>

				if (selectedValues[strName] && BX.util.in_array(selectedValues[strName], arCanBuyValues))
				{
					arFilter[strName] = selectedValues[strName].toString();
				}
				else
				{
					<?if($arPost["PARAMS"]["SHOW_ABSENT"]):?>
						arFilter[strName] = (arCanBuyValues.length ? arCanBuyValues[0] : allValues[0]);
					<?else:?>
						arFilter[strName] = arCanBuyValues[0];
					<?endif;?>
				}

				UpdateRow(i, arFilter[strName], arShowValues, arCanBuyValues);
			}

			$('.<?=$arPost["CLASS"]?>.js_offers__<?=$arPost["LINK_ID"]?> .bx_catalog_item_scu').data('selected', arFilter);

			ChangeInfo();
			UpdateRowsImages();
		}
	</script>
<?endif;?>
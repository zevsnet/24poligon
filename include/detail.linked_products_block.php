<?global $arRegion;
if($arRegion)
{
	if($arRegion['LIST_PRICES'])
	{
		if(reset($arRegion['LIST_PRICES']) != 'component')
			$arParams['PRICE_CODE'] = array_keys($arRegion['LIST_PRICES']);
	}
	if($arRegion['LIST_STORES'])
	{
		if(reset($arRegion['LIST_STORES']) != 'component')
			$arParams['STORES'] = $arRegion['LIST_STORES'];
	}

	if($catalogID = \Bitrix\Main\Config\Option::get("aspro.max", "CATALOG_IBLOCK_ID", \Bitrix\Main\Config\Option::get("aspro.max", "CATALOG_IBLOCK_ID", "#IBLOCK_CATALOG_ID#")))
	{
		$GLOBALS['arrProductsFilter']['IBLOCK_ID'] = $catalogID;
		if(($arParams["FILTER_NAME"] == 'arRegionLink' || CMax::GetFrontParametrValue('REGIONALITY_FILTER_CATALOG') == 'Y') && CMax::GetFrontParametrValue('REGIONALITY_FILTER_ITEM') == 'Y'){
				$GLOBALS['arrProductsFilter']['PROPERTY_LINK_REGION'] = $arRegion['ID'];
				CMax::makeElementFilterInRegion($GLOBALS['arrProductsFilter']);
			}
	}
}
?>
<?\Aspro\Functions\CAsproMax::replacePropsParams($arParams);?>
<?if (!$arParams['OFFERS_PROPERTY_CODE']) $arParams['OFFERS_PROPERTY_CODE'] = $arParams['LIST_OFFERS_PROPERTY_CODE'] ;?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section",
	"catalog_block",
	array(
		"COMPATIBLE_MODE" => "Y",
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => \Bitrix\Main\Config\Option::get("aspro.max", "CATALOG_IBLOCK_ID", "#IBLOCK_CATALOG_ID#"),
		"PAGE_ELEMENT_COUNT" =>(isset($arParams["DISPLAY_ELEMENT_SLIDER"]) ? $arParams["DISPLAY_ELEMENT_SLIDER"] : 10),
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "ID",
		"SORT_ORDER2" => "DESC",
		"FILTER_NAME" => "arrProductsFilter",
		"INCLUDE_SUBSECTIONS" => "Y",
		"SHOW_ALL_WO_SECTION" => "Y",
		"PAGER_TEMPLATE" => "ajax",
		"DISPLAY_TYPE" => "block",
		"TYPE_SKU" => $arParams['DETAIL_LINKED_GOODS_SLIDER'] !== 'N' ? "TYPE_2" : CMax::GetFrontParametrValue("TYPE_SKU"),
		"SLIDE_ITEMS" => ($arParams['DETAIL_LINKED_GOODS_SLIDER'] != 'N'),
		"AJAX_REQUEST" => $arParams["FROM_AJAX"],
		"LINE_ELEMENT_COUNT" => "4",
		"PROPERTY_CODE" => isset($arParams['LINKED_PROPERTY_CODE']) ? $arParams['LINKED_PROPERTY_CODE'] : $arParams['PROPERTY_CODE'],
		"OFFERS_LIMIT" => $arParams["OFFERS_LIMIT"],
		"OFFER_ADD_PICT_PROP" => $arParams["OFFER_ADD_PICT_PROP"],
		"SECTION_URL" => "",
		"DETAIL_URL" => "",
		"BASKET_URL" => SITE_DIR."basket/",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SECTION_ID" =>  $arParams['BIG_DATA_MODE'] === "Y" ?  $arParams['SECTION_ID_FOR_BIG_DATA'] : "",
		"SECTION_CODE" => "",
		"SHOW_BIG_BLOCK" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => ($arParams["DISPLAY_LINKED_PAGER"] ? $arParams["DISPLAY_LINKED_PAGER"] : "N"),
		"CACHE_TYPE" => $arParams['CACHE_TYPE'],
		"CACHE_TIME" => "3600000",
		"CACHE_GROUPS" => ($arParams["CACHE_GROUPS"] ? $arParams["CACHE_GROUPS"] : "N"),
		"CACHE_FILTER" => "Y",
		"DISPLAY_COMPARE" => CMax::GetFrontParametrValue('CATALOG_COMPARE'),
		"USE_FAST_VIEW" => CMax::GetFrontParametrValue('USE_FAST_VIEW_PAGE_DETAIL'),
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"CUSTOM_FILTER" => ((isset($arParams['CONTENT_LINKED_FILTER_BY_FILTER']) && $arParams['CONTENT_LINKED_FILTER_BY_FILTER']) ? $arParams['~CONTENT_LINKED_FILTER_BY_FILTER'] : ''),
		"STORES" => $arParams["STORES"],
		"USE_REGION" => ($GLOBALS['arRegion'] ? "Y" : "N"),
		"USE_PRICE_COUNT" => "Y",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => (isset($arParams["PRICE_VAT_INCLUDE"]) ? $arParams["PRICE_VAT_INCLUDE"] : "Y"),
		"MAX_GALLERY_ITEMS" => $arParams["MAX_GALLERY_ITEMS"],
		"SHOW_GALLERY" => $arParams["SHOW_GALLERY"],
		"SHOW_PROPS" => $arParams['SHOW_PROPS'],
		'SHOW_POPUP_PRICE' => $arParams['SHOW_POPUP_PRICE'],
		"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"] ? $arParams["CONVERT_CURRENCY"] : 'N',
		"CURRENCY_ID" => $arParams["CURRENCY_ID"] ? $arParams["CURRENCY_ID"] : 'RUB',
		"TYPE_VIEW_BASKET_BTN" => "TYPE_2",
		"USE_PRODUCT_QUANTITY" => "N",
		"COMPONENT_TEMPLATE" => "catalog_block",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER2" => "desc",
		"HIDE_NOT_AVAILABLE" => $arParams['HIDE_NOT_AVAILABLE_LINKED'],
		"FILTER_IDS" => ($arParams['BIG_DATA_MODE'] === 'Y' ? $arParams['BIG_DATA_FILTER_IDS'] : ''),
		'HIDE_NOT_AVAILABLE_OFFERS' => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],
		"ADD_DETAIL_TO_SLIDER" => ($arParams["ADD_DETAIL_TO_SLIDER"] ? $arParams["ADD_DETAIL_TO_SLIDER"] : "Y"),
		"OFFERS_FIELD_CODE" => array(
			0 => "ID",
			1 => "NAME",
			2 => "DETAIL_PAGE_URL",
		),
		"OFFERS_PROPERTY_CODE" => $arParams['OFFERS_PROPERTY_CODE'],
		"OFFER_TREE_PROPS" => $arParams['OFFER_TREE_PROPS'],
		"OFFER_SHOW_PREVIEW_PICTURE_PROPS" => $arParams["OFFER_SHOW_PREVIEW_PICTURE_PROPS"],
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_ORDER" => "asc",
		"OFFERS_SORT_FIELD2" => "id",
		"OFFERS_SORT_ORDER2" => "desc",
		"ELEMENT_SORT_FIELD" => ($arParams["LINKED_ELEMENT_TAB_SORT_FIELD"] ? $arParams["LINKED_ELEMENT_TAB_SORT_FIELD"] : "SORT"),
		"ELEMENT_SORT_ORDER" => ($arParams["LINKED_ELEMENT_TAB_SORT_ORDER"] ? $arParams["LINKED_ELEMENT_TAB_SORT_ORDER"] : "ASC"),
		"ELEMENT_SORT_FIELD2" => ($arParams["LINKED_ELEMENT_TAB_SORT_FIELD2"] ? $arParams["LINKED_ELEMENT_TAB_SORT_FIELD2"] : "ID"),
		"ELEMENT_SORT_ORDER2" => ($arParams["LINKED_ELEMENT_TAB_SORT_ORDER2"] ? $arParams["LINKED_ELEMENT_TAB_SORT_ORDER2"] : "DESC"),
		"SEF_MODE" => "N",
		"BIG_DATA_MODE" => $arParams['BIG_DATA_MODE'],
		"RCM_PROD_ID" => $arParams['RCM_PROD_ID'],
		"PRODUCT_ROW_VARIANTS" => $arParams['PRODUCT_ROW_VARIANTS'],
		"SHOW_FROM_SECTION" => $arParams['SHOW_FROM_SECTION'],
		"RCM_TYPE" => $arParams['RCM_TYPE'],
		"FILTER_HIT_PROP" => $arParams['FILTER_HIT_PROP'] ?? 'block',
		"BIGDATA_COUNT_BOTTOM" => $arParams["BIGDATA_COUNT_BOTTOM"],
		"SHOW_SLIDER" => "N",
		"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"OFFERS_CART_PROPERTIES" => array(
		),
		"COMPARE_PATH" => "",
		"SHOW_DISCOUNT_PERCENT_NUMBER" => $arParams["SHOW_DISCOUNT_PERCENT_NUMBER"],
		"SHOW_DISCOUNT_PERCENT" => $arParams["SHOW_DISCOUNT_PERCENT"],
		"SHOW_DISCOUNT_TIME" => $arParams["SHOW_DISCOUNT_TIME"],
		"SHOW_ONE_CLICK_BUY" => $arParams["SHOW_ONE_CLICK_BUY"],
		"SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"],
		"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
		"SALE_STIKER" => $arParams["SALE_STIKER"],
		"STIKERS_PROP" => $arParams["STIKERS_PROP"],
		"SHOW_RATING" => $arParams["SHOW_RATING"],
		"ADD_PICT_PROP" => $arParams["ADD_PICT_PROP"],
		"DISPLAY_WISH_BUTTONS" => $arParams["DISPLAY_WISH_BUTTONS"],
		"DISPLAY_WISH_BUTTONS" => CMax::GetFrontParametrValue('CATALOG_DELAY'),
		"COMPOSITE_FRAME_MODE" => "A",
		"REVIEWS_VIEW" => CMax::GetFrontParametrValue('REVIEWS_VIEW') == 'EXTENDED',
		"SET_SKU_TITLE" => ((CMax::GetFrontParametrValue("TYPE_SKU") == "TYPE_1" && CMax::GetFrontParametrValue("CHANGE_TITLE_ITEM_LIST") == "Y") ? "Y" : ""),
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false, array("HIDE_ICONS" => "Y")
);?>
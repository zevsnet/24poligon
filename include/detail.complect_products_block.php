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
		$GLOBALS['arrProductsSetFilter']['IBLOCK_ID'] = $catalogID;
		if(($arParams["FILTER_NAME"] == 'arRegionLink' || CMax::GetFrontParametrValue('REGIONALITY_FILTER_CATALOG') == 'Y') && CMax::GetFrontParametrValue('REGIONALITY_FILTER_ITEM') == 'Y'){
				$GLOBALS['arrProductsSetFilter']['PROPERTY_LINK_REGION'] = $arRegion['ID'];
				CMax::makeElementFilterInRegion($GLOBALS['arrProductsSetFilter']);
			}
	}
}
?>
<?\Aspro\Functions\CAsproMax::replacePropsParams($arParams);?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section",
	"catalog_block",
	array(
		"COMPATIBLE_MODE" => "Y",
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => \Bitrix\Main\Config\Option::get("aspro.max", "CATALOG_IBLOCK_ID", "#IBLOCK_CATALOG_ID#"),
		"PAGE_ELEMENT_COUNT" => ($arParams["MODULES_ELEMENT_COUNT"] ? $arParams["MODULES_ELEMENT_COUNT"] : 10),
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "ID",
		"SORT_ORDER2" => "DESC",
		"FILTER_NAME" => "arrProductsSetFilter",
		"INCLUDE_SUBSECTIONS" => "Y",
		"SHOW_ALL_WO_SECTION" => "Y",
		"PAGER_TEMPLATE" => "ajax",
		"DISPLAY_TYPE" => "block",
		"TYPE_SKU" => "TYPE_1",
		"SLIDE_ITEMS" => false,
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
		"SECTION_ID" => "",
		"SECTION_CODE" => "",
		"SHOW_BIG_BLOCK" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"CACHE_TYPE" => $arParams['CACHE_TYPE'],
		"CACHE_TIME" => "3600000",
		"CACHE_GROUPS" => ($arParams["CACHE_GROUPS"] ? $arParams["CACHE_GROUPS"] : "N"),
		"CACHE_FILTER" => "Y",
		"DISPLAY_COMPARE" => CMax::GetFrontParametrValue('CATALOG_COMPARE'),
		"USE_FAST_VIEW" => CMax::GetFrontParametrValue('USE_FAST_VIEW_PAGE_DETAIL'),
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		//"CUSTOM_FILTER" => ((isset($arParams['CONTENT_LINKED_FILTER_BY_FILTER']) && $arParams['CONTENT_LINKED_FILTER_BY_FILTER']) ? $arParams['~CONTENT_LINKED_FILTER_BY_FILTER'] : ''),
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
		"TYPE_VIEW_BASKET_BTN" => "TYPE_1",
		"USE_PRODUCT_QUANTITY" => "N",
		"COMPONENT_TEMPLATE" => "catalog_block",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER2" => "desc",
		"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
		'HIDE_NOT_AVAILABLE_OFFERS' => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],
		"ADD_DETAIL_TO_SLIDER" => ($arParams["ADD_DETAIL_TO_SLIDER"] ? $arParams["ADD_DETAIL_TO_SLIDER"] : "Y"),
		"OFFERS_FIELD_CODE" => $arParams['LIST_OFFERS_FIELD_CODE'],
		"OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_ORDER" => "asc",
		"OFFERS_SORT_FIELD2" => "id",
		"OFFERS_SORT_ORDER2" => "desc",
		"ELEMENT_SORT_FIELD" => ($arParams["LINKED_ELEMENT_TAB_SORT_FIELD"] ? $arParams["LINKED_ELEMENT_TAB_SORT_FIELD"] : "SORT"),
		"ELEMENT_SORT_ORDER" => ($arParams["LINKED_ELEMENT_TAB_SORT_ORDER"] ? $arParams["LINKED_ELEMENT_TAB_SORT_ORDER"] : "ASC"),
		"ELEMENT_SORT_FIELD2" => ($arParams["LINKED_ELEMENT_TAB_SORT_FIELD2"] ? $arParams["LINKED_ELEMENT_TAB_SORT_FIELD2"] : "ID"),
		"ELEMENT_SORT_ORDER2" => ($arParams["LINKED_ELEMENT_TAB_SORT_ORDER2"] ? $arParams["LINKED_ELEMENT_TAB_SORT_ORDER2"] : "DESC"),
		"SEF_MODE" => "N",
		"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
		"PARTIAL_PRODUCT_PROPERTIES" => $arParams["PARTIAL_PRODUCT_PROPERTIES"],
		"ADD_PROPERTIES_TO_BASKET" => $arParams["ADD_PROPERTIES_TO_BASKET"],
		"COMPARE_PATH" => "",
		"SHOW_DISCOUNT_PERCENT_NUMBER" => $arParams["SHOW_DISCOUNT_PERCENT_NUMBER"],
		"SHOW_DISCOUNT_PERCENT" => $arParams["SHOW_DISCOUNT_PERCENT"],
		"SHOW_DISCOUNT_TIME" => $arParams["SHOW_DISCOUNT_TIME"],
		"SHOW_ONE_CLICK_BUY" => $arParams["SHOW_ONE_CLICK_BUY"],
		"SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"],
		"SALE_STIKER" => $arParams["SALE_STIKER"],
		"OFFER_SHOW_PREVIEW_PICTURE_PROPS" => $arParams['OFFER_SHOW_PREVIEW_PICTURE_PROPS'],
		"STIKERS_PROP" => $arParams["STIKERS_PROP"],
		"SHOW_RATING" => $arParams["SHOW_RATING"],
		"ADD_PICT_PROP" => $arParams["ADD_PICT_PROP"],
		"DISPLAY_WISH_BUTTONS" => $arParams["DISPLAY_WISH_BUTTONS"],
		"DISPLAY_WISH_BUTTONS" => CMax::GetFrontParametrValue('CATALOG_DELAY'),
		"COMPOSITE_FRAME_MODE" => "A",
		"REVIEWS_VIEW" => CMax::GetFrontParametrValue('REVIEWS_VIEW') == 'EXTENDED',
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"COMPLECT_MODE" => "Y",
		"OFFER_TREE_PROPS" => $arParams["OFFER_TREE_PROPS"],
	),
	false, array("HIDE_ICONS" => "Y")
);?>
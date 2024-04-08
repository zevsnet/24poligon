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
		// "IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"PAGE_ELEMENT_COUNT" => "20",
		"ELEMENT_COUNT" => ($arParams["LINKED_ELEMENST_PAGE_COUNT"] ? $arParams["LINKED_ELEMENST_PAGE_COUNT"] : 20),
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "ID",
		"SORT_ORDER2" => "DESC",
		"FILTER_NAME" => "arrProductsFilter",
		"INCLUDE_SUBSECTIONS" => "Y",
		"SHOW_ALL_WO_SECTION" => "Y",
		"PAGER_TEMPLATE" => "main",
		"DISPLAY_TYPE" => "block",
		"TYPE_SKU" => "TYPE_1",
		"SLIDE_ITEMS" => true,
		"AJAX_REQUEST" => $arParams["FROM_AJAX"],
		"LINE_ELEMENT_COUNT" => "4",
		"PROPERTY_CODE" => $arParams['PROPERTY_CODE'],
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
		"CACHE_TYPE" => $arParams['CACHE_TYPE'],
		"CACHE_TIME" => "3600000",
		"CACHE_GROUPS" => "N",
		"CACHE_FILTER" => "Y",
		"DISPLAY_COMPARE" => ($arParams['DISPLAY_COMPARE'] || $arParams['DISPLAY_COMPARE'] == 'Y' ? 'Y' : $arParams['DISPLAY_COMPARE']),
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"CUSTOM_FILTER" => $arParams["~CUSTOM_FILTER"],
		"STORES" => $arParams["STORES"],
		"USE_REGION" => ($GLOBALS['arRegion'] ? "Y" : "N"),
		"USE_PRICE_COUNT" => "Y",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"MAX_GALLERY_ITEMS" => $arParams["MAX_GALLERY_ITEMS"],
		"SHOW_GALLERY" => $arParams["SHOW_GALLERY"],
		"SHOW_PROPS" => $arParams['SHOW_PROPS'],
		'SHOW_POPUP_PRICE' => $arParams['SHOW_POPUP_PRICE'],
		"CONVERT_CURRENCY" => "N",
		"TYPE_VIEW_BASKET_BTN" => "TYPE_2",
		"USE_PRODUCT_QUANTITY" => "N",
		"COMPONENT_TEMPLATE" => "catalog_block",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER2" => "desc",
		"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
		'HIDE_NOT_AVAILABLE_OFFERS' => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],
		"OFFERS_FIELD_CODE" => array(
			0 => "ID",
			1 => "NAME",
			2 => "",
		),
		"OFFERS_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_ORDER" => "asc",
		"OFFERS_SORT_FIELD2" => "id",
		"OFFERS_SORT_ORDER2" => "desc",
		"SEF_MODE" => "N",
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
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false, array("HIDE_ICONS" => "Y")
);?>
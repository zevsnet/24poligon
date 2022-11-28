<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?if ($_REQUEST["ELEMENT_ID"]):?>
	<?$APPLICATION->IncludeComponent(
		"bitrix:catalog.element",
		"add_item_popup",
		Array(
			"IBLOCK_TYPE" => "aspro_mshop_catalog",
			"IBLOCK_ID" => "95",
			"ELEMENT_ID" => intval($_REQUEST["ELEMENT_ID"]),
			"OFFER_ID" => intval($_REQUEST["SKU_ID"]),
			"ELEMENT_CODE" => "",
			"SECTION_ID" => $_REQUEST["SECTION_ID"],
			"SECTION_CODE" => "",
			"SECTION_URL" => "",
			"DETAIL_URL" => "",
			"BASKET_URL" => SITE_DIR."personal/basket.php",
			"ACTION_VARIABLE" => "action",
			"PRODUCT_ID_VARIABLE" => "id",
			"PRODUCT_QUANTITY_VARIABLE" => "quantity",
			"PRODUCT_PROPS_VARIABLE" => "prop",
			"SECTION_ID_VARIABLE" => "SECTION_ID",
			"META_KEYWORDS" => "-",
			"META_DESCRIPTION" => "-",
			"BROWSER_TITLE" => "-",
			"SET_TITLE" => "Y",
			"SET_STATUS_404" => "N",
			"ADD_SECTIONS_CHAIN" => "Y",
			"PROPERTY_CODE" => array(),
			"OFFERS_LIMIT" => "0",
			"PRICE_CODE" => array("BASE"),
			"USE_PRICE_COUNT" => "N",
			"SHOW_PRICE_COUNT" => "1",
			"PRICE_VAT_INCLUDE" => "Y",
			"PRICE_VAT_SHOW_VALUE" => "N",
			"PRODUCT_PROPERTIES" => array(),
			"USE_PRODUCT_QUANTITY" => "N",
			"LINK_IBLOCK_TYPE" => "",
			"LINK_IBLOCK_ID" => "",
			"LINK_PROPERTY_SID" => "",
			"LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "36000000",
			"CACHE_GROUPS" => "Y",
			"USE_ELEMENT_COUNTER" => "Y",
			"CONVERT_CURRENCY" => "N",
			"OFFERS_FIELD_CODE" => array(0 => "ID",1 => "NAME",),
			"OFFERS_PROPERTY_CODE" => array(	0 => "CML2_LINK",),
			"OFFERS_SORT_FIELD" => "sort",
			"OFFERS_SORT_ORDER" => "asc",
		),
	false
	);?> 
<?endif;?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>
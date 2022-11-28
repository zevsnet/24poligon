<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("header-compare-block");?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.compare.list", 
	"compare_top", 
	array(
		"IBLOCK_TYPE" => "aspro_mshop_catalog",
		"IBLOCK_ID" => "105",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"DETAIL_URL" => SITE_DIR."catalog/#SECTION_CODE_PATH#/#ELEMENT_ID#/",
		"COMPARE_URL" => SITE_DIR."catalog/compare.php",
		"NAME" => "CATALOG_COMPARE_LIST",
		"AJAX_OPTION_ADDITIONAL" => "",
		"COMPONENT_TEMPLATE" => "compare_top",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id"
	),
	false
);?>
<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("header-compare-block", "");?>
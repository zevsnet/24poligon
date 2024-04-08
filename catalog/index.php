<? use Poligon\Core\Iblock\Helper;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Каталог товаров в интернет-магазине. Купите в Военторге армейскую одежду, обувь и военное снаряжение. Большой выбор товаров по доступным ценам. Быстрая доставка в любой регион.");
$APPLICATION->SetPageProperty("title", "Военторг - каталог товаров. Цены в магазине Полигон");?>
<?$APPLICATION->SetTitle("Каталог армейских товаров в магазине Полигон  ");?>
<?
global $MAX_SMART_FILTER, $USER;
if( !$USER->IsAdmin()){
	$MAX_SMART_FILTER = [
	    [
	         "LOGIC"=>'OR',
	         ">CATALOG_PRICE_4"=>0,
	         //">CATALOG_PRICE_8"=>0
	    ],
	    [
	        "LOGIC"=>'OR',
	        "!DETAIL_PICTURE"=>false,
	        "!PREVIEW_PICTURE"=>false,
	    ]

	];
}	
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog", 
	"main", 
	array(
		"COMPONENT_TEMPLATE" => "main",
		"IBLOCK_TYPE" => "aspro_max_catalog",
		"IBLOCK_ID" => "180",
		"SECTIONS_TYPE_VIEW" => "FROM_MODULE",
		"SECTION_TYPE_VIEW" => "FROM_MODULE",
		"SECTION_ELEMENTS_TYPE_VIEW" => "list_elements_1",
		"ELEMENT_TYPE_VIEW" => "FROM_MODULE",
		"LANDING_TYPE_VIEW" => "FROM_MODULE",
		"BIGDATA_EXT" => "bigdata_1",
		"HIDE_NOT_AVAILABLE" => "Y",
		"HIDE_NOT_AVAILABLE_OFFERS" => "Y",
		"SHOW_MEASURE_WITH_RATIO" => "N",
		"SHOW_DISCOUNT_PERCENT_NUMBER" => "Y",
		"SHOW_DISCOUNT_PERCENT" => "Y",
		"ALT_TITLE_GET" => "SEO",
		"SHOW_DISCOUNT_TIME" => "Y",
		"SHOW_COUNTER_LIST" => "Y",
		"SHOW_DISCOUNT_TIME_EACH_SKU" => "N",
		"SHOW_RATING" => "N",
		"SHOW_OLD_PRICE" => "Y",
		"ADD_PICT_PROP" => "-",
		"DETAIL_DOCS_PROP" => "-",
		"USER_CONSENT" => "N",
		"USER_CONSENT_ID" => "0",
		"USER_CONSENT_IS_CHECKED" => "Y",
		"USER_CONSENT_IS_LOADED" => "N",
		"SEF_MODE" => "Y",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"DETAIL_STRICT_SECTION_CHECK" => "N",
		"SET_LAST_MODIFIED" => "Y",
		"SET_TITLE" => "Y",
		"ADD_SECTIONS_CHAIN" => "Y",
		"ADD_ELEMENT_CHAIN" => "N",
		"SHOW_HOW_BUY" => "Y",
		"TITLE_HOW_BUY" => "Как купить",
		"SHOW_DELIVERY" => "Y",
		"TITLE_DELIVERY" => "Доставка",
		"SHOW_PAYMENT" => "Y",
		"TITLE_PAYMENT" => "Оплата",
		"SHOW_GARANTY" => "Y",
		"TITLE_GARANTY" => "Условия гарантии",
		"SHOW_BUY_DELIVERY" => "Y",
		"TITLE_BUY_DELIVERY" => "Оплата и доставка",
		"IBLOCK_STOCK_ID" => "",
		"IBLOCK_LINK_NEWS_ID" => "",
		"IBLOCK_SERVICES_ID" => "",
		"IBLOCK_TIZERS_ID" => "",
		"IBLOCK_LINK_REVIEWS_ID" => "",
		"SHOW_MEASURE" => "N",
		"USE_RATING" => "Y",
		"SHOW_GALLERY" => "Y",
		"MAX_GALLERY_ITEMS" => "2",
		"SHOW_UNABLE_SKU_PROPS" => "N",
		"SHOW_ARTICLE_SKU" => "N",
		"DEFAULT_COUNT" => "1",
		"STIKERS_PROP" => "-",
		"SALE_STIKER" => "-",
		"SHOW_HINTS" => "Y",
		"USE_FILTER" => "Y",
		"AJAX_FILTER_CATALOG" => "Y",
		"USE_FILTER_PRICE" => "Y",
		"DISPLAY_ELEMENT_COUNT" => "Y",
		"USE_REVIEW" => "N",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"USE_COMPARE" => "N",
		"PRICE_CODE" => array(
			0 => "Розничная цена",
		),
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"PRICE_VAT_SHOW_VALUE" => "N",
		"CONVERT_CURRENCY" => "N",
		"BASKET_URL" => "/personal/basket.php",
		"USE_PRODUCT_QUANTITY" => "N",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"RESTART" => "Y",
		"USE_LANGUAGE_GUESS" => "N",
		"NO_WORD_LOGIC" => "Y",
		"SHOW_LANDINGS_SEARCH" => "Y",
		"LANDING_SEARCH_TITLE" => "",
		"LANDING_SEARCH_COUNT" => "15",
		"LANDING_SEARCH_COUNT_MOBILE" => "3",
		"SHOW_TOP_ELEMENTS" => "Y",
		"TOP_ELEMENT_COUNT" => "9",
		"TOP_LINE_ELEMENT_COUNT" => "3",
		"TOP_ELEMENT_SORT_FIELD" => "PROPERTY_DETAIL_PICTURE",
		"TOP_ELEMENT_SORT_ORDER" => "asc",
		"TOP_ELEMENT_SORT_FIELD2" => "shows",
		"TOP_ELEMENT_SORT_ORDER2" => "asc",
		"SECTION_TOP_BLOCK_TITLE" => "Лучшие предложения",
		"SECTION_COUNT_ELEMENTS" => "Y",
		"SECTION_TOP_DEPTH" => "2",
		"SECTIONS_LIST_PREVIEW_PROPERTY" => "DESCRIPTION",
		"SECTIONS_LIST_PREVIEW_DESCRIPTION" => "N",
		"SHOW_SECTION_LIST_PICTURES" => "Y",
		"PAGE_ELEMENT_COUNT" => "30",
		"LINE_ELEMENT_COUNT" => helper::countLine(),
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_FIELD2" => "SCALED_PRICE_4",
		"ELEMENT_SORT_ORDER2" => "asc",
		"INCLUDE_SUBSECTIONS" => "Y",
		"LIST_META_KEYWORDS" => "-",
		"LIST_META_DESCRIPTION" => "-",
		"LIST_BROWSER_TITLE" => "-",
		"SECTION_BACKGROUND_IMAGE" => "-",
		"SHOW_MORE_SUBSECTIONS" => "Y",
		"SHOW_SIDE_BLOCK_LAST_LEVEL" => "Y",
		"SHOW_SORT_IN_FILTER" => "Y",
		"SORT_BUTTONS" => array(
		),
		"DEFAULT_LIST_TEMPLATE" => "block",
		"SECTION_DISPLAY_PROPERTY" => "UF_SECTION_TEMPLATE",
		"LIST_DISPLAY_POPUP_IMAGE" => "Y",
		"SECTION_PREVIEW_PROPERTY" => "DESCRIPTION",
		"SUBSECTION_PREVIEW_PROPERTY" => "DESCRIPTION",
		"SHOW_SECTION_PICTURES" => "Y",
		"SHOW_SUBSECTION_DESC" => "Y",
		"SHOW_SECTION_DESC" => "Y",
		"SHOW_LANDINGS" => "Y",
		"LANDING_TITLE" => "",
		"LANDING_POSITION" => "BEFORE_PRODUCTS",
		"LANDING_IBLOCK_ID" => "",
		"LANDING_SECTION_COUNT" => "7",
		"LANDING_SECTION_COUNT_MOBILE" => "3",
		"SHOW_SMARTSEO_TAGS" => "Y",
		"AJAX_CONTROLS" => "Y",
		"SECTION_BG" => "-",
		"DETAIL_META_KEYWORDS" => "-",
		"DETAIL_META_DESCRIPTION" => "-",
		"DETAIL_BROWSER_TITLE" => "-",
		"DETAIL_SET_CANONICAL_URL" => "Y",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"DETAIL_CHECK_SECTION_ID_VARIABLE" => "N",
		"DETAIL_BACKGROUND_IMAGE" => "-",
		"SHOW_DEACTIVATED" => "N",
		"SHOW_SKU_DESCRIPTION" => "N",
		"DISPLAY_ELEMENT_SLIDER" => "10",
		"TITLE_SLIDER" => "Рекомендуем",
		"PROPERTIES_DISPLAY_LOCATION" => "DESCRIPTION",
		"USE_CUSTOM_RESIZE" => "N",
		"DETAIL_ADD_DETAIL_TO_SLIDER" => "Y",
		"SHOW_BRAND_PICTURE" => "Y",
		"SHOW_CHEAPER_FORM" => "Y",
		"SHOW_SEND_GIFT" => "Y",
		"SEND_GIFT_FORM_NAME" => "",
		"CHEAPER_FORM_NAME" => "",
		"SHOW_ASK_BLOCK" => "Y",
		"ASK_FORM_ID" => "",
		"DETAIL_OFFERS_LIMIT" => "0",
		"DETAIL_EXPANDABLES_TITLE" => "Аксессуары",
		"DETAIL_ASSOCIATED_TITLE" => "Похожие товары",
		"DETAIL_LINKED_GOODS_SLIDER" => "Y",
		"DETAIL_LINKED_GOODS_TABS" => "Y",
		"SHOW_ADDITIONAL_TAB" => "N",
		"PROPERTIES_DISPLAY_TYPE" => "BLOCK",
		"LINKED_ELEMENT_TAB_SORT_FIELD" => "shows",
		"LINKED_ELEMENT_TAB_SORT_ORDER" => "asc",
		"LINKED_ELEMENT_TAB_SORT_FIELD2" => "shows",
		"LINKED_ELEMENT_TAB_SORT_ORDER2" => "asc",
		"SHOW_KIT_PARTS" => "N",
		"SHOW_KIT_PARTS_PRICES" => "N",
		"SHOW_ONE_CLICK_BUY" => "Y",
		"USE_SHARE" => "N",
		"SKU_DETAIL_ID" => "oid",
		"USE_ADDITIONAL_GALLERY" => "N",
		"ASK_TAB" => "",
		"TAB_STAFF_NAME" => "",
		"TAB_VACANCY_NAME" => "",
		"TAB_KOMPLECT_NAME" => "",
		"TAB_NABOR_NAME" => "",
		"TAB_OFFERS_NAME" => "",
		"TAB_DESCR_NAME" => "",
		"TAB_CHAR_NAME" => "",
		"TAB_VIDEO_NAME" => "",
		"TAB_REVIEW_NAME" => "",
		"TAB_STOCK_NAME" => "",
		"TAB_NEWS_NAME" => "",
		"TAB_DOPS_NAME" => "",
		"BLOCK_SERVICES_NAME" => "",
		"BLOCK_DOCS_NAME" => "",
		"BLOG_IBLOCK_ID" => "",
		"STAFF_IBLOCK_ID" => "",
		"VACANCY_IBLOCK_ID" => "",
		"BLOCK_BLOG_NAME" => "",
		"STAFF_VIEW_TYPE" => "staff_block",
		"RECOMEND_COUNT" => "5",
		"VISIBLE_PROP_COUNT" => "4",
		"BUNDLE_ITEMS_COUNT" => "3",
		"USE_DETAIL_PREDICTION" => "N",
		"DETAIL_BLOCKS_ORDER" => "complect,nabor,offers,tabs,services,news,blog,staff,vacancy,gifts,goods",
		"DETAIL_BLOCKS_TAB_ORDER" => "desc,char,buy,payment,delivery,video,stores,reviews,custom_tab",
		"DETAIL_BLOCKS_ALL_ORDER" => "complect,nabor,offers,desc,char,buy,payment,delivery,video,stores,custom_tab,services,news,reviews,blog,staff,vacancy,gifts,goods",
		"LINK_IBLOCK_TYPE" => "",
		"LINK_IBLOCK_ID" => "",
		"LINK_PROPERTY_SID" => "",
		"LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
		"USE_ALSO_BUY" => "Y",
		"USE_GIFTS_DETAIL" => "Y",
		"USE_GIFTS_SECTION" => "Y",
		"USE_GIFTS_MAIN_PR_SECTION_LIST" => "Y",
		"GIFTS_DETAIL_PAGE_ELEMENT_COUNT" => "4",
		"GIFTS_DETAIL_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_DETAIL_BLOCK_TITLE" => "Выберите один из подарков",
		"GIFTS_DETAIL_TEXT_LABEL_GIFT" => "Подарок",
		"GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT" => "4",
		"GIFTS_SECTION_LIST_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_SECTION_LIST_BLOCK_TITLE" => "Подарки к товарам этого раздела",
		"GIFTS_SECTION_LIST_TEXT_LABEL_GIFT" => "Подарок",
		"GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",
		"GIFTS_SHOW_OLD_PRICE" => "Y",
		"GIFTS_SHOW_NAME" => "Y",
		"GIFTS_SHOW_IMAGE" => "Y",
		"GIFTS_MESS_BTN_BUY" => "Выбрать",
		"GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT" => "4",
		"GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE" => "Выберите один из товаров, чтобы получить подарок",
		"USE_STORE" => "Y",
		"STORES_FILTER" => "TITLE",
		"STORES_FILTER_ORDER" => "SORT_ASC",
		"USE_BIG_DATA" => "Y",
		"BIG_DATA_RCM_TYPE" => "bestsell",
		"BIGDATA_SHOW_FROM_SECTION" => "N",
		"PAGER_TEMPLATE" => "modern",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Товары",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "Y",
		"SET_STATUS_404" => "Y",
		"SHOW_404" => "Y",
		"MESSAGE_404" => "",
		"COMPATIBLE_MODE" => "Y",
		"USE_ELEMENT_COUNTER" => "Y",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"DETAIL_SET_VIEWED_IN_COMPONENT" => "Y",
		"OFFER_ADD_PICT_PROP" => "-",
		"SEF_FOLDER" => "/catalog/",
		"TOP_OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"TOP_OFFERS_LIMIT" => "5",
		"LIST_OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "DETAIL_PAGE_URL",
			2 => "",
		),
		"LIST_OFFERS_LIMIT" => "0",
		"SORT_PRICES" => "MINIMUM_PRICE",
		"SORT_REGION_PRICE" => "Премиальная цена",
		"SMARTSEO_TAGS_COUNT" => "10",
		"SMARTSEO_TAGS_COUNT_MOBILE" => "3",
		"DETAIL_OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "DETAIL_PAGE_URL",
			2 => "",
		),
		"ALSO_BUY_ELEMENT_COUNT" => "5",
		"ALSO_BUY_MIN_BUYES" => "1",
		"OFFERS_SORT_FIELD" => "shows",
		"OFFERS_SORT_ORDER" => "asc",
		"OFFERS_SORT_FIELD2" => "shows",
		"OFFERS_SORT_ORDER2" => "asc",
		"OFFER_TREE_PROPS" => array(
		),
		"OFFER_HIDE_NAME_PROPS" => "N",
		"OFFER_SHOW_PREVIEW_PICTURE_PROPS" => array(
		),
		"FILE_404" => "",
		"USE_COMPARE_GROUP" => "N",
		"SHOW_SORT_RANK_BUTTON" => "Y",
		"USE_BIG_DATA_IN_SEARCH" => "N",
		"HIDE_SUBSECTIONS_LIST" => "N",
		"USE_LANDINGS_GROUP" => "N",
		"LANDINGS_GROUP_FROM_SEO" => "N",
		"SMARTSEO_TAGS_BY_GROUPS" => "N",
		"SMARTSEO_TAGS_SHOW_DEACTIVATED" => "N",
		"SMARTSEO_TAGS_SORT" => "NAME",
		"SMARTSEO_TAGS_LIMIT" => "",
		"MODULES_ELEMENT_COUNT" => "10",
		"DETAIL_SET_PRODUCT_TITLE" => "Собрать комплект",
		"DISPLAY_LINKED_PAGER" => "Y",
		"DISPLAY_LINKED_ELEMENT_SLIDER_CROSSLINK" => "",
		"SHOW_KIT_ALL" => "N",
		"TAB_BUY_SERVICES_NAME" => "",
		"VISIBLE_PROP_WITH_OFFER" => "N",
		"COUNT_SERVICES_IN_ANNOUNCE" => "2",
		"SHOW_ALL_SERVICES_IN_SLIDE" => "N",
		"BIGDATA_EXT_BOTTOM" => "bigdata_bottom_1",
		"BIGDATA_COUNT" => "5",
		"BIGDATA_TYPE_VIEW" => "RIGHT",
		"FILTER_NAME" => "MAX_SMART_FILTER",
		"FILTER_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_PRICE_CODE" => array(
		),
		"FILTER_OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_OFFERS_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"PRODUCT_PROPERTIES" => array(
		),
		"OFFERS_CART_PROPERTIES" => array(
		),
		"TOP_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"TOP_OFFERS_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"LIST_PROPERTY_CODE" => array(
			0 => "HIT",
			1 => "CML2_ARTICLE",
			2 => "NAME",
			3 => "",
		),
		"LIST_OFFERS_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"DETAIL_PROPERTY_CODE" => array(
			0 => "CML2_ARTICLE",
			1 => "",
		),
		"DETAIL_OFFERS_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"DISPLAY_NAME" => "Y",
		"STORES" => array(
			0 => "3",
			1 => "6",
			2 => "10",
			3 => "11",
			4 => "21",
			5 => "26",
			6 => "45",
			7 => "46",
			8 => "47",
			9 => "49",
			10 => "50",
			11 => "51",
			12 => "52",
			13 => "53",
			14 => "54",
			15 => "55",
			16 => "56",
			17 => "",
		),
		"USE_MIN_AMOUNT" => "Y",
		"USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"FIELDS" => array(
			0 => "",
			1 => "",
		),
		"MIN_AMOUNT" => "10",
		"SHOW_EMPTY_STORE" => "N",
		"SHOW_GENERAL_STORE_INFORMATION" => "N",
		"STORE_PATH" => "/store/#store_id#",
		"MAIN_TITLE" => "Наличие на складах",
		"PAGER_BASE_LINK" => "",
		"PAGER_PARAMS_NAME" => "arrPager",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"SEF_URL_TEMPLATES" => array(
			"sections" => "",
			"section" => "#SECTION_CODE_PATH#/",
			"element" => "#SECTION_CODE_PATH#/#ELEMENT_CODE#/",
			"compare" => "compare.php?action=#ACTION_CODE#",
			"smart_filter" => "#SECTION_CODE_PATH#/filter/#SMART_FILTER_PATH#/apply/",
		),
		"VARIABLE_ALIASES" => array(
			"compare" => array(
				"ACTION_CODE" => "action",
			),
		)
	),
	false
);?>

<?php
$current_url = $_SERVER['REQUEST_URI'];
if (preg_match('~^/catalog/(?!.*\/).*$~', $current_url)) {
	echo '<div class="sometext" style="padding:40px 0 0;clear:both;">';
	$APPLICATION->IncludeFile(SITE_DIR."catalog/seotext.php",array(),array("MODE"=>"html"));
	echo '</div>';
}
?>




<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>

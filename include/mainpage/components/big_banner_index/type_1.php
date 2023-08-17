<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$APPLICATION->IncludeComponent(
	"aspro:com.banners.max", 
	"top_big_banners_1_2", 
	array(
		"IBLOCK_TYPE" => "aspro_max_adv",
		"IBLOCK_ID" => "181",
		"TYPE_BANNERS_IBLOCK_ID" => "155",
		"SET_BANNER_TYPE_FROM_THEME" => "N",
		"NEWS_COUNT" => "10",
		"NEWS_COUNT2" => "3",
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "ID",
		"SORT_ORDER2" => "DESC",
		"PROPERTY_CODE" => array(
			0 => "TEXT_POSITION",
			1 => "TARGETS",
			2 => "TEXTCOLOR",
			3 => "URL_STRING",
			4 => "BUTTON1TEXT",
			5 => "BUTTON1LINK",
			6 => "BUTTON2TEXT",
			7 => "BUTTON2LINK",
			8 => "",
		),
		"CHECK_DATES" => "Y",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_GROUPS" => "N",
		"WIDE_BANNER" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"BANNER_TYPE_THEME" => "TOP",
		"COMPONENT_TEMPLATE" => "top_big_banners_1_2",
		"FILTER_NAME" => "arRegionLink",
		"SHOW_MEASURE" => "Y",
		"BANNER_TYPE_THEME_CHILD" => "",
		"SECTION_ID" => "",
		"NEWS_COUNT3" => "20",
		"PRICE_CODE" => array(
			0 => "Розничная цена",
			1 => "Оптовая цена",
			2 => "Онлайн цена",
			3 => "Премиальная цена",
			4 => "От 10шт.",
			5 => "Дисконт цена",
			6 => "Учетная цена",
			7 => "От 50шт.",
			8 => "OLD_PRICE",
		),
		"STORES" => array(
			0 => "",
			1 => "3",
			2 => "6",
			3 => "10",
			4 => "11",
			5 => "21",
			6 => "26",
			7 => "45",
			8 => "46",
			9 => "47",
			10 => "49",
			11 => "50",
			12 => "51",
			13 => "52",
			14 => "53",
			15 => "54",
			16 => "55",
			17 => "56",
			18 => "57",
			19 => "58",
			20 => "",
		),
		"CONVERT_CURRENCY" => "N"
	),
	false
);?>
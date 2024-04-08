<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>


<?global $SITE_THEME, $TEMPLATE_OPTIONS;?>
<div class="wrapper_inner1 wides float_banners">
	<?$APPLICATION->IncludeComponent("aspro:com.banners.optimus", "optimus", array(
	"IBLOCK_TYPE" => "aspro_optimus_adv",
		"IBLOCK_ID" => "141",
		"TYPE_BANNERS_IBLOCK_ID" => "140",
		"SET_BANNER_TYPE_FROM_THEME" => "N",
		"NEWS_COUNT" => "6",
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
		"CACHE_TYPE" => "A",
		"CACHE_GROUPS" => "N",
		"CACHE_TIME" => "36000000",
		"SITE_THEME" => $SITE_THEME,
		"BANNER_TYPE_THEME" => "FLOAT"
	),
	false,
	array(
	"ACTIVE_COMPONENT" => "N"
	)
);?>
</div>
<div class="clearfix"></div>

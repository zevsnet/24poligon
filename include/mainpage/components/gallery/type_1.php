<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$indexPageOptions = $GLOBALS['arTheme']['INDEX_TYPE']['SUB_PARAMS'][$GLOBALS['arTheme']['INDEX_TYPE']['VALUE']];
$blockOptions = $indexPageOptions['GALLERY'];
$blockTemplateOptions = $blockOptions['TEMPLATE']['LIST'][$blockOptions['TEMPLATE']['VALUE']];

$bShowMore = $blockTemplateOptions["ADDITIONAL_OPTIONS"]["LINES_COUNT"]["VALUE"] === 'SHOW_MORE';
$linesCount = $bShowMore ? 1 : (intval($blockTemplateOptions["ADDITIONAL_OPTIONS"]["LINES_COUNT"]["VALUE"]) ?: 1);

$itemsType = $blockTemplateOptions["ADDITIONAL_OPTIONS"]["ITEMS_TYPE"]["VALUE"];
$elementInRow = $blockTemplateOptions["ADDITIONAL_OPTIONS"]["ELEMENTS_COUNT"]["VALUE"];

?>
<?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"gallery-list", 
	array(
		"IBLOCK_TYPE" => "#IBLOCK_MAX_CONTENT_TYPE#",
		"IBLOCK_ID" => "#IBLOCK_GALLERY_ID#",
		"NEWS_COUNT" => $linesCount*$elementInRow,
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_ORDER1" => "DESC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"FILTER_NAME" => "arRegionLinkFront",
		"FIELD_CODE" => array(
			0 => "NAME",
			1 => "PREVIEW_PICTURE",
			2 => "",
		),
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "PHOTOS",
			2 => "",
		),
		"CHECK_DATES" => "Y",
		"SHOW_SECTION" => "N",
		"DETAIL_URL" => "",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600000",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "N",
		"PREVIEW_TRUNCATE_LEN" => "",
		"ACTIVE_DATE_FORMAT" => "j F Y",
		"SET_TITLE" => "N",
		"SET_STATUS_404" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" => "N",
		"PAGER_TEMPLATE" => "ajax",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "3600000",
		"PAGER_SHOW_ALL" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"SET_BROWSER_TITLE" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_META_DESCRIPTION" => "N",
		"COMPONENT_TEMPLATE" => "gallery-list",
		"SET_LAST_MODIFIED" => "N",
		"STRICT_SECTION_CHECK" => "N",
		"SHOW_DETAIL_LINK" => "Y",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => "",
		"SHOW_DATE" => "Y",
		"COUNT_IN_LINE" => "4",
		"TEMPLATE_VIEW" => $blockOptions["TEMPLATE"]["VALUE"],
		"IMAGE_POSITION" => "BG",
		"SLIDER" => false,
		"HIDE_PAGINATION" => $bShowMore?"N":"Y",
		"SHOW_PREVIEW" => "N",
		"TITLE_BLOCK" => "Галерея",
		"TITLE_BLOCK_ALL" => "Все фотографии",
		"RIGHT_LINK" => "gallery/",
		"CHECK_REQUEST_BLOCK" => CMax::checkRequestBlock("gallery"),
		"IS_AJAX" => CMax::checkAjaxRequest(),
		"SUBTITLE" => "Галерея",
		"SHOW_PREVIEW_TEXT" => "Y",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"INCLUDE_FILE" => "gallery_desc.php",
		"ALL_URL" => "gallery/",
		"MOBILE_SCROLLED" => "Y",
		"ELEMENT_IN_ROW" => "FROM_MODULE",
		"ITEMS_TYPE" => "FROM_MODULE",
		"THEME" => array(
			"ITEMS_TYPE" => $itemsType??null,
			"ELEMENT_IN_ROW" => $elementInRow??null,
		)
	),
	false
);?>
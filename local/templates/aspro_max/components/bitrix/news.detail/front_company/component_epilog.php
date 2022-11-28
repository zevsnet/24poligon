<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?if(isset($templateData['BENEFITS']) && $templateData['BENEFITS']):?>
	<?$GLOBALS['LINK_BENEFIT_COMPANY']['ID'] = $templateData['BENEFITS']?>
	<div class="js-tizers-tmp">
	    <?$APPLICATION->IncludeComponent(
			"bitrix:news.list", 
			"front_tizers", 
			array(
				"IBLOCK_TYPE" => "aspro_max_content",
				"IBLOCK_ID" => $arParams['TIZERS_IBLOCK_ID'],
				"NEWS_COUNT" => $arParams['COUNT_BENEFIT'],
				"SORT_BY1" => "SORT",
				"SORT_ORDER1" => "ASC",
				"SORT_BY2" => "ID",
				"SORT_ORDER2" => "DESC",
				"FILTER_NAME" => "LINK_BENEFIT_COMPANY",
				"FIELD_CODE" => array(
					0 => "PREVIEW_PICTURE",
					1 => "PREVIEW_TEXT",
					2 => "DETAIL_PICTURE",
					3 => "",
				),
				"PROPERTY_CODE" => array(
					0 => "ICON",
					1 => "URL",
				),
				"CHECK_DATES" => "Y",
				"DETAIL_URL" => "",
				"AJAX_MODE" => "N",
				"AJAX_OPTION_JUMP" => "N",
				"AJAX_OPTION_STYLE" => "Y",
				"AJAX_OPTION_HISTORY" => "N",
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "36000000",
				"CACHE_FILTER" => "Y",
				"CACHE_GROUPS" => "N",
				"PREVIEW_TRUNCATE_LEN" => "250",
				"ACTIVE_DATE_FORMAT" => "d F Y",
				"SET_TITLE" => "N",
				"SHOW_DETAIL_LINK" => "N",
				"SET_STATUS_404" => "N",
				"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
				"ADD_SECTIONS_CHAIN" => "N",
				"HIDE_LINK_WHEN_NO_DETAIL" => "N",
				"PARENT_SECTION" => "",
				"PARENT_SECTION_CODE" => "",
				"DISPLAY_TOP_PAGER" => "N",
				"DISPLAY_BOTTOM_PAGER" => "Y",
				"PAGER_TITLE" => "",
				"PAGER_SHOW_ALWAYS" => "N",
				"PAGER_TEMPLATE" => "ajax",
				"PAGER_DESC_NUMBERING" => "N",
				"PAGER_DESC_NUMBERING_CACHE_TIME" => "3600",
				"PAGER_SHOW_ALL" => "N",
				"DISPLAY_DATE" => "Y",
				"DISPLAY_NAME" => "Y",
				"DISPLAY_PICTURE" => "N",
				"DISPLAY_PREVIEW_TEXT" => "N",
				"AJAX_OPTION_ADDITIONAL" => "",
				"COMPONENT_TEMPLATE" => "front_tizers",
				"SET_BROWSER_TITLE" => "N",
				"SET_META_KEYWORDS" => "N",
				"SET_META_DESCRIPTION" => "N",
				"SET_LAST_MODIFIED" => "N",
				"INCLUDE_SUBSECTIONS" => "Y",
				"STRICT_SECTION_CHECK" => "N",
				"TYPE_IMG" => "left",
				"CENTERED" => "Y",
				"SIZE_IN_ROW" => $arParams['BENEFIT_COL'],
				"PAGER_BASE_LINK_ENABLE" => "N",
				"SHOW_404" => "N",
				"MOBILE_TEMPLATE" => $GLOBALS['arTheme']['MOBILE_TIZERS']['VALUE'],
				"MESSAGE_404" => ""
			),
			false, array("HIDE_ICONS" => "Y")
		);?>
	</div>

	<script>if($('.item-views.company').length && $('.js-tizers-tmp .item-views').length){$('.item-views.company .text').addClass('with-benefit');$('.item-views.company .text .btn').addClass('btn-transparent-border-color btn-sm').removeClass('btn-default');$('.js-tizers-tmp').appendTo($('.item-views.company .js-tizers'));}</script>
<?endif;?>
<script>if($('.item-views.company.no-img').length && $('.item-views.company.no-img h3').length)$('.item-views.company.no-img h3').appendTo($('.item-views.company .js-h3'));</script>
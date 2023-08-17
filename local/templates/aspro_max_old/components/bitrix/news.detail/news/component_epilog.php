<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
__IncludeLang($_SERVER["DOCUMENT_ROOT"].$templateFolder."/lang/".LANGUAGE_ID."/template.php");

$libs = ['fancybox', 'countdown'];

if (isset($templateData['SECTION_BNR_CONTENT']) && $templateData['SECTION_BNR_CONTENT'] == true)
{
	global $SECTION_BNR_CONTENT, $bLongBannerContents;
	$SECTION_BNR_CONTENT = true;
	if(isset($templateData['BNR_ON_HEAD']) && $templateData['BNR_ON_HEAD'] == true){
		global $arTheme;
		if( isset($arTheme['HEADER_TYPE']['LIST'][ $arTheme['HEADER_TYPE']['VALUE'] ]['ADDITIONAL_OPTIONS'])  && isset($arTheme['HEADER_TYPE']['LIST'][ $arTheme['HEADER_TYPE']['VALUE'] ]['ADDITIONAL_OPTIONS']['TOP_HEADER_OPACITY']) ) {
			$bTopHeaderOpacity = $arTheme['HEADER_TYPE']['LIST'][ $arTheme['HEADER_TYPE']['VALUE'] ]['ADDITIONAL_OPTIONS']['TOP_HEADER_OPACITY']['VALUE'] == 'Y';
		}

		if($bTopHeaderOpacity) {
			global $dopBodyClass;
			$dopBodyClass .= ' top_header_opacity';
		}
		$bLongBannerContents = true;
		if ($templateData['BNR_DARK_MENU_COLOR']['VALUE'] != 'Y') {
			global $dopClass;
			$dopClass .= ' light-menu-color';
		}

		$libs[] = 'banners';
	}
}?>

<?global $isHideLeftBlock; ?>

<?$bHideLeftBlock = ($APPLICATION->GetProperty("HIDE_LEFT_BLOCK") == "Y");?>

<?$arBlockOrder = explode(",", $arParams["DETAIL_BLOCKS_ALL_ORDER"]);?>

<?if($templateData['SHOW_PERIOD_LINE']):?>
<div class="period_wrapper in-detail-news1">
<?$APPLICATION->ShowViewContent('share_in_contents');?>
<?/*period line*/
$APPLICATION->ShowViewContent('PERIOD_LINE');?>
</div>
<div class="line-after in-detail-news1"></div>
<?endif;?>

<?if($arParams["PARTNERS_MODE"] == "Y" && ($isHideLeftBlock || $APPLICATION->GetProperty("HIDE_LEFT_BLOCK_DETAIL") == "Y")):?>
	<div class="line-after"></div>
<?endif;?>

<?$APPLICATION->ShowViewContent('DETAIL_IMG');?>

<?foreach($arBlockOrder as $code):?>
	<?//news?>
        <?if($code == 'news' && $templateData['LINK_NEWS']):?>
		<?ob_start();?>
			<?$GLOBALS['arrNewsFilter'] = array('ID' => $templateData['LINK_NEWS']);
			  $GLOBALS['arrNewsFilter'] = array_merge($GLOBALS['arrNewsFilter'], (array)$GLOBALS['arRegionLink']);?>
			<?$APPLICATION->IncludeComponent(
				"bitrix:news.list",
				"news-list",
				array(
					"IBLOCK_TYPE" => "aspro_max_content",
					"IBLOCK_ID" => $arParams['IBLOCK_LINK_NEWS_ID'],
					"NEWS_COUNT" => "20",
					"SORT_BY1" => "SORT",
					"SORT_ORDER1" => "ASC",
					"SORT_BY2" => "ID",
					"SORT_ORDER2" => "DESC",
					"FILTER_NAME" => "arrNewsFilter",
					"FIELD_CODE" => array(
					    0 => "NAME",
					    1 => "DETAIL_PAGE_URL",
					    2 => "PREVIEW_TEXT",
					    3 => "PREVIEW_PICTURE",
					    4 => "DATE_ACTIVE_FROM",
					),
					"PROPERTY_CODE" => array(
					    0 => "PERIOD",
					),
					"CHECK_DATES" => "Y",
					"DETAIL_URL" => "",
					"AJAX_MODE" => "N",
					"AJAX_OPTION_JUMP" => "N",
					"AJAX_OPTION_STYLE" => "Y",
					"AJAX_OPTION_HISTORY" => "N",
					"CACHE_TYPE" => $arParams['CACHE_TYPE'],
					"CACHE_TIME" => "36000000",
					"CACHE_FILTER" => "Y",
					"HIDE_LINK_WHEN_NO_DETAIL" => "Y",
					"CACHE_GROUPS" => "N",
					"PREVIEW_TRUNCATE_LEN" => "",
					"ACTIVE_DATE_FORMAT" => "d.m.Y",
					"SET_TITLE" => "N",
					"SET_STATUS_404" => "N",
					"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
					"ADD_SECTIONS_CHAIN" => "N",
					"PARENT_SECTION" => "",
					"PARENT_SECTION_CODE" => "",
					"INCLUDE_SUBSECTIONS" => "Y",
					"PAGER_TEMPLATE" => ".default",
					"DISPLAY_TOP_PAGER" => "N",
					"DISPLAY_BOTTOM_PAGER" => "N",
					"PAGER_TITLE" => "",
					"PAGER_SHOW_ALWAYS" => "N",
					"PAGER_DESC_NUMBERING" => "N",
					"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
					"PAGER_SHOW_ALL" => "N",
					"VIEW_TYPE" => "list",
					"IMAGE_POSITION" => "left",
					"COUNT_IN_LINE" => "3",
					"SHOW_TITLE" => "Y",
					"AJAX_OPTION_ADDITIONAL" => "",
					"BORDERED" => "Y",
					"LINKED_MODE" => "Y",
				),
				false, array("HIDE_ICONS" => "Y")
			);?>
		<?$html=ob_get_clean();?>
		<?if($html && trim($html) && strpos($html, 'error') === false):?>
			<div class="ordered-block <?=$code?> with-title">
				<div class="ordered-block__title option-font-bold font_lg">
					<?=$arParams["BLOCK_NEWS_NAME"];?>
				</div>
				<?=$html;?>
			</div>
			<div class="line-after"></div>
		<?endif;?>
	<?//services?>
	<?elseif($code == 'services' && $templateData['LINK_SERVICES']):?>
		<?ob_start();?>
			<?$GLOBALS['arrServicesFilter'] = array('ID' => $templateData['LINK_SERVICES']);
			  $GLOBALS['arrServicesFilter'] = array_merge($GLOBALS['arrServicesFilter'], (array)$GLOBALS['arRegionLink']);
			 ?>
			<?$APPLICATION->IncludeComponent(
				"bitrix:news.list",
				"news-list",
				array(
					"IBLOCK_TYPE" => "aspro_max_content",
					"IBLOCK_ID" => $arParams['IBLOCK_LINK_SERVICES_ID'],
					"NEWS_COUNT" => "20",
					"SORT_BY1" => "SORT",
					"SORT_ORDER1" => "ASC",
					"SORT_BY2" => "ID",
					"SORT_ORDER2" => "DESC",
					"FILTER_NAME" => "arrServicesFilter",
					"FIELD_CODE" => array(
					    0 => "NAME",
					    1 => "DETAIL_PAGE_URL",
					    2 => "PREVIEW_TEXT",
					    3 => "PREVIEW_PICTURE",
					),
					"PROPERTY_CODE" => array(
					    0 => "PRICE",
					    1 => "PRICE_OLD",
					),
					"CHECK_DATES" => "Y",
					"DETAIL_URL" => "",
					"AJAX_MODE" => "N",
					"AJAX_OPTION_JUMP" => "N",
					"AJAX_OPTION_STYLE" => "Y",
					"AJAX_OPTION_HISTORY" => "N",
					"CACHE_TYPE" => $arParams['CACHE_TYPE'],
					"CACHE_TIME" => "36000000",
					"CACHE_FILTER" => "Y",
					"HIDE_LINK_WHEN_NO_DETAIL" => "Y",
					"CACHE_GROUPS" => "N",
					"PREVIEW_TRUNCATE_LEN" => "",
					"ACTIVE_DATE_FORMAT" => "d.m.Y",
					"SET_TITLE" => "N",
					"SET_STATUS_404" => "N",
					"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
					"ADD_SECTIONS_CHAIN" => "N",
					"PARENT_SECTION" => "",
					"PARENT_SECTION_CODE" => "",
					"INCLUDE_SUBSECTIONS" => "Y",
					"PAGER_TEMPLATE" => ".default",
					"DISPLAY_TOP_PAGER" => "N",
					"DISPLAY_BOTTOM_PAGER" => "N",
					"PAGER_TITLE" => "",
					"PAGER_SHOW_ALWAYS" => "N",
					"PAGER_DESC_NUMBERING" => "N",
					"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
					"PAGER_SHOW_ALL" => "N",
					"VIEW_TYPE" => "list",
					"IMAGE_POSITION" => "left",
					"COUNT_IN_LINE" => "3",
					"SHOW_TITLE" => "Y",
					"AJAX_OPTION_ADDITIONAL" => "",
					"BORDERED" => "Y",
					"LINKED_MODE" => "Y",
				),
				false, array("HIDE_ICONS" => "Y")
			);?>
		<?$html=ob_get_clean();?>
		<?if($html && trim($html) && strpos($html, 'error') === false):?>
			<div class="ordered-block <?=$code?> with-title">
				<div class="ordered-block__title option-font-bold font_lg">
					<?=$arParams["BLOCK_SERVICES_NAME"];?>
				</div>
				<?=$html;?>
			</div>
			<div class="line-after"></div>
		<?endif;?>
	<?//tizers?>
	<?elseif($code == 'tizers' && $templateData['LINK_TIZERS']):?>
		<?ob_start()?>
			<?
			$GLOBALS['arrTizersFilter'] = array('ID' => $templateData['LINK_TIZERS']);
			$GLOBALS['arrTizersFilter'] = array_merge($GLOBALS['arrTizersFilter'], (array)$GLOBALS['arRegionLink']);
			$APPLICATION->IncludeComponent(
				"bitrix:news.list",
				"front_tizers",
				array(
					"IBLOCK_TYPE" => "aspro_max_content",
					"IBLOCK_ID" => $arParams['IBLOCK_LINK_TIZERS_ID'],
					"NEWS_COUNT" => ($bHideLeftBlock ? '4' : '3'),
					"SORT_BY1" => "SORT",
					"SORT_ORDER1" => "ASC",
					"SORT_BY2" => "ID",
					"SORT_ORDER2" => "DESC",
					"FILTER_NAME" => "arrTizersFilter",
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
					"DISPLAY_BOTTOM_PAGER" => "N",
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
					"SIZE_IN_ROW" => ($bHideLeftBlock ? '4' : '3'),
					"PAGER_BASE_LINK_ENABLE" => "N",
					"SHOW_404" => "N",
					"MESSAGE_404" => ""
				),
				false, array("HIDE_ICONS" => "Y")
			);?>
		<?$html=ob_get_clean();?>
		<?if($html && trim($html) && strpos($html, 'error') === false):?>
			<div class="ordered-block tizers-block in-detail-news1 ">
				<?if($arParams["BLOCK_TIZERS_NAME"]):?>
					<div class="ordered-block__title option-font-bold font_lg">
						<?=$arParams["BLOCK_TIZERS_NAME"];?>
					</div>
				<?endif;?>
				<?=$html;?>
			</div>
			<div class="line-after"></div>
		<?endif;?>
	<?//preview_text block ?>
	<?elseif($code == 'preview_text'):?>
                <div class="ordered-block">
					<?$APPLICATION->ShowViewContent('PREVIEW_TEXT_BLOCK');?>
				</div>
				<div class="line-after"></div>
	<?//detail description block?>
	<?elseif($code == 'desc'):?>
                <?$APPLICATION->ShowViewContent('DETAIL_CONTENT')?>
	<?//form_order block?>
	<?elseif($code == 'form_order'):?>
                <?$APPLICATION->ShowViewContent('CONTENT_ORDER_FORM')?>
        <?//props block?>
        <?elseif($code == 'char'):?>
                <?$APPLICATION->ShowViewContent('CONTENT_PROPS_INFO')?>
	<?//brands?>
	<?elseif($code == 'brands' && $templateData['LINK_BRANDS']):?>
		<?ob_start()?>
			<?
			$GLOBALS['arrBrandsFilter'] = array('ID' => $templateData['LINK_BRANDS']);
			$GLOBALS['arrBrandsFilter'] = array_merge($GLOBALS['arrBrandsFilter'], (array)$GLOBALS['arRegionLink']);
			$APPLICATION->IncludeComponent(
				"bitrix:news.list",
				"front_brands_slider",
				array(
					"IBLOCK_TYPE" => "aspro_max_content",
					"IBLOCK_ID" => $arParams['IBLOCK_LINK_BRANDS_ID'],
					"NEWS_COUNT" => "",
					"SORT_BY1" => "SORT",
					"SORT_ORDER1" => "DESC",
					"SORT_BY2" => "SORT",
					"SORT_ORDER2" => "ASC",
					"FILTER_NAME" => "arrBrandsFilter",
					"FIELD_CODE" => array(
						0 => "PREVIEW_PICTURE",
						1 => "",
					),
					"PROPERTY_CODE" => array(
						0 => "",
						1 => "",
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
					"PREVIEW_TRUNCATE_LEN" => "",
					"ACTIVE_DATE_FORMAT" => "d.m.Y",
					"SET_TITLE" => "N",
					"SET_STATUS_404" => "N",
					"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
					"ADD_SECTIONS_CHAIN" => "N",
					"HIDE_LINK_WHEN_NO_DETAIL" => "N",
					"PARENT_SECTION" => "",
					"PARENT_SECTION_CODE" => "",
					"DISPLAY_TOP_PAGER" => "N",
					"DISPLAY_BOTTOM_PAGER" => "N",
					"PAGER_TITLE" => "",
					"PAGER_SHOW_ALWAYS" => "N",
					"PAGER_TEMPLATE" => "",
					"PAGER_DESC_NUMBERING" => "N",
					"PAGER_DESC_NUMBERING_CACHE_TIME" => "3600",
					"PAGER_SHOW_ALL" => "N",
					"DISPLAY_DATE" => "Y",
					"DISPLAY_NAME" => "Y",
					"DISPLAY_PICTURE" => "N",
					"DISPLAY_PREVIEW_TEXT" => "N",
					"AJAX_OPTION_ADDITIONAL" => "",
					"COMPONENT_TEMPLATE" => "front_brands_slider",
					"SET_BROWSER_TITLE" => "N",
					"SET_META_KEYWORDS" => "N",
					"SET_META_DESCRIPTION" => "N",
					"SET_LAST_MODIFIED" => "N",
					"INCLUDE_SUBSECTIONS" => "Y",
					"STRICT_SECTION_CHECK" => "N",
					"TITLE_BLOCK" => '',
					//"TITLE_BLOCK_ALL" => "",
					"ALL_URL" => "",
					"PAGER_BASE_LINK_ENABLE" => "N",
					"BORDERED" => ($bHideLeftBlock ? "N" : "Y"),
					"SHOW_404" => "N",
					"MESSAGE_404" => ""
				),
				false, array("HIDE_ICONS" => "Y")
			);?>
		<?$html=ob_get_clean();?>
		<?if($html && trim($html) && strpos($html, 'error') === false):?>
			<div class="ordered-block <?=$code?> brands-block with-title">
				<div class="ordered-block__title option-font-bold font_lg">
					<?=$arParams["BLOCK_BRANDS_NAME"];?>
				</div>
				<?=$html;?>
			</div>
			<div class="line-after"></div>
		<?endif;?>
	<?//projects?>
	<?elseif($code == 'projects' && $templateData['LINK_PROJECTS']):?>
		<?ob_start()?>
			<?
			$GLOBALS['arrProjectsFilter'] = array('ID' => $templateData['LINK_PROJECTS']);
			$GLOBALS['arrProjectsFilter'] = array_merge($GLOBALS['arrProjectsFilter'], (array)$GLOBALS['arRegionLink']);
			$APPLICATION->IncludeComponent(
				"bitrix:news.list",
				"front_news",
				array(
					"IBLOCK_TYPE" => "aspro_max_content",
					"IBLOCK_ID" => $arParams['IBLOCK_LINK_PROJECTS_ID'],
					"NEWS_COUNT" => '',
					"SORT_BY1" => "ACTIVE_FROM",
					"SORT_ORDER1" => "DESC",
					"SORT_BY2" => "SORT",
					"SORT_ORDER2" => "ASC",
					"FILTER_NAME" => "arrProjectsFilter",
					"FIELD_CODE" => array(
					    0 => "PREVIEW_PICTURE",
					    1 => "DATE_ACTIVE_FROM",
					),
					"PROPERTY_CODE" => array(
					    0 => "PERIOD",
					    1 => "REDIRECT",
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
					"PREVIEW_TRUNCATE_LEN" => "",
					"ACTIVE_DATE_FORMAT" => "d F Y",
					"SET_TITLE" => "N",
					"SET_STATUS_404" => "N",
					"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
					"ADD_SECTIONS_CHAIN" => "N",
					"HIDE_LINK_WHEN_NO_DETAIL" => "N",
					"PARENT_SECTION" => "",
					"PARENT_SECTION_CODE" => "",
					"DISPLAY_TOP_PAGER" => "N",
					"DISPLAY_BOTTOM_PAGER" => "N",
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
					"COMPONENT_TEMPLATE" => "front_news",
					"SET_BROWSER_TITLE" => "N",
					"SET_META_KEYWORDS" => "N",
					"SET_META_DESCRIPTION" => "N",
					"SET_LAST_MODIFIED" => "N",
					"INCLUDE_SUBSECTIONS" => "Y",
					"STRICT_SECTION_CHECK" => "N",
					"TITLE_BLOCK" => '',
					//"TITLE_BLOCK_ALL" => "",
					"ALL_URL" => "",
					"SIZE_IN_ROW" => ($bHideLeftBlock ? '3' : '2'),
					"TYPE_IMG" => "lg",
					"BORDERED" => "Y",
					"SHOW_SUBSCRIBE" => "Y",
					"TITLE_SUBSCRIBE" => "",
					"PAGER_BASE_LINK_ENABLE" => "N",
					"SHOW_404" => "N",
					//"IS_AJAX" => CMax::checkAjaxRequest(),
					"MESSAGE_404" => "",
					"FON_BLOCK_2_COLS" => 'N',
					"ALL_BLOCK_BG" => 'Y',
				),
				false, array("HIDE_ICONS" => "Y")
			);?>
		<?$html=ob_get_clean();?>
		<?if($html && trim($html) && strpos($html, 'error') === false):?>
			<div class="ordered-block projects-block with-title">
				<div class="ordered-block__title option-font-bold font_lg">
					<?=$arParams["BLOCK_PROJECTS_NAME"];?>
				</div>
				<?=$html;?>
			</div>
			<div class="line-after"></div>
		<?endif;?>
	<?//comments block?>
	<?elseif($code == 'comments' && $arParams["DETAIL_USE_COMMENTS"] == "Y"):?>
		<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/rating_likes.js"); ?>
		<?ob_start()?>
			<?
			$APPLICATION->IncludeComponent(
				"bitrix:catalog.comments",
				"main",
				array(
					'CACHE_TYPE' => $arParams['CACHE_TYPE'],
					'CACHE_TIME' => $arParams['CACHE_TIME'],
					'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
					"COMMENTS_COUNT" => $arParams['COMMENTS_COUNT'],
					"ELEMENT_CODE" => "",
					"ELEMENT_ID" => $arResult["ID"],
					"FB_USE" => $arParams["DETAIL_FB_USE"],
					"IBLOCK_ID" => $arParams["IBLOCK_ID"],
					"IBLOCK_TYPE" => "aspro_max_catalog",
					"SHOW_DEACTIVATED" => "N",
					"TEMPLATE_THEME" => "blue",
					"URL_TO_COMMENT" => "",
					"VK_USE" => $arParams["DETAIL_VK_USE"],
					"AJAX_POST" => "Y",
					"WIDTH" => "",
					"COMPONENT_TEMPLATE" => ".default",
					"BLOG_USE" => $arParams["DETAIL_BLOG_USE"],
					"BLOG_TITLE" => $arParams["BLOG_TITLE"],
					"BLOG_URL" => $arParams["DETAIL_BLOG_URL"],
					"PATH_TO_SMILE" => '/bitrix/images/blog/smile/',
					"EMAIL_NOTIFY" => $arParams["DETAIL_BLOG_EMAIL_NOTIFY"],
					"SHOW_SPAM" => "Y",
					"SHOW_RATING" => "Y",
					"RATING_TYPE" => "like_graphic",
					"FB_TITLE" => $arParams["FB_TITLE"],
					"FB_USER_ADMIN_ID" => "",
					"FB_APP_ID" => $arParams["DETAIL_FB_APP_ID"],
					"FB_COLORSCHEME" => "light",
					"FB_ORDER_BY" => "reverse_time",
					"VK_TITLE" => $arParams["VK_TITLE"],
					"VK_API_ID" => $arParams["DETAIL_VK_API_ID"]
				),
				false, array("HIDE_ICONS" => "Y")
			);?>
		<?$html=ob_get_clean();?>
		<?if($html && trim($html) && strpos($html, 'error') === false):?>
			<div class="ordered-block comments-block">
				<?=$html;?>
			</div>
			<div class="line-after"></div>
		<?endif;?>

	<?//reviews block?>
	<?elseif($code == 'reviews' && $templateData['LINK_REVIEWS']):?>
		<?ob_start()?>
			<?
			$GLOBALS['arrReviewsFilter'] = array('ID' => $templateData['LINK_REVIEWS']);
			$GLOBALS['arrReviewsFilter'] = array_merge($GLOBALS['arrReviewsFilter'], (array)$GLOBALS['arRegionLink']);
			$APPLICATION->IncludeComponent(
				"bitrix:news.list",
				"front_review",
				array(
				    "IBLOCK_TYPE" => "aspro_max_content",
				    "IBLOCK_ID" => $arParams['IBLOCK_LINK_REVIEWS_ID'],
				    "NEWS_COUNT" => "",
				    "SORT_BY1" => "SORT",
				    "SORT_ORDER1" => "ASC",
				    "SORT_BY2" => "ID",
				    "SORT_ORDER2" => "DESC",
				    "FILTER_NAME" => "arrReviewsFilter",
				    "FIELD_CODE" => array(
					0 => "PREVIEW_PICTURE",
					1 => "PREVIEW_TEXT",
					2 => "DETAIL_PICTURE",
					3 => "",
				    ),
				    "PROPERTY_CODE" => array(
					0 => "POST",
					1 => "RATING",
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
				    "COMPONENT_TEMPLATE" => "front_review",
				    "SET_BROWSER_TITLE" => "N",
				    "SET_META_KEYWORDS" => "N",
				    "SET_META_DESCRIPTION" => "N",
				    "SET_LAST_MODIFIED" => "N",
				    "INCLUDE_SUBSECTIONS" => "Y",
				    "STRICT_SECTION_CHECK" => "N",
				    "TITLE_BLOCK" => '',
				    //"TITLE_BLOCK_ALL" => "",
				    "SHOW_ADD_REVIEW" => "N",
				    "TITLE_ADD_REVIEW" => "",
				    "ALL_URL" => "",
				    "PAGER_BASE_LINK_ENABLE" => "N",
				    "SHOW_404" => "N",
				    "COMPACT" => "Y",
				    "SIZE_IN_ROW" => "1",
				    "MESSAGE_404" => "",
				    "LINKED_MODE" => 'Y',
				),
				false, array("HIDE_ICONS" => "Y")
			);?>
		<?$html=ob_get_clean();?>
		<?if($html && trim($html) && strpos($html, 'error') === false):?>
			<div class="ordered-block reviews-block with-title">
				<div class="ordered-block__title option-font-bold font_lg">
					<?=$arParams["BLOCK_REVIEWS_NAME"];?>
				</div>
				<?=$html;?>
			</div>
			<div class="line-after"></div>
		<?endif;?>

	<?//staff?>
	<?elseif($code == 'staff' && $templateData['LINK_STAFF']):?>
		<?ob_start()?>
			<?
			$GLOBALS['arrStaffFilter'] = array('ID' => $templateData['LINK_STAFF']);
			$GLOBALS['arrStaffFilter'] = array_merge($GLOBALS['arrStaffFilter'], (array)$GLOBALS['arRegionLink']);
			if($arParams['STAFF_TYPE'] == 'block'):?>
				<?$APPLICATION->IncludeComponent(
					"bitrix:news.list",
					"staff_block",
					array(
						"IBLOCK_TYPE" => "",
						"IBLOCK_ID" => $arParams['IBLOCK_LINK_STAFF_ID'],
						"NEWS_COUNT" => "",
						"SORT_BY1" => "SORT",
						"SORT_ORDER1" => "ASC",
						"SORT_BY2" => "ID",
						"SORT_ORDER2" => "DESC",
						"FILTER_NAME" => "arrStaffFilter",
						"FIELD_CODE" => array(
						    0 => "PREVIEW_PICTURE",
						    1 => "NAME",
						    2 => "",
						),
						"PROPERTY_CODE" => array(
						    0 => "POST",
						    1 => "PHONE",
						    2 => "EMAIL",
						    3 => "SEND_MESSAGE_BUTTON",
						),
						"CHECK_DATES" => "Y",
						"DETAIL_URL" => "",
						"AJAX_MODE" => "N",
						"AJAX_OPTION_JUMP" => "N",
						"AJAX_OPTION_STYLE" => "Y",
						"AJAX_OPTION_HISTORY" => "N",
						'CACHE_TYPE' => $arParams['CACHE_TYPE'],
						'CACHE_TIME' => $arParams['CACHE_TIME'],
						'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
						"CACHE_FILTER" => "Y",
						"PREVIEW_TRUNCATE_LEN" => "",
						"ACTIVE_DATE_FORMAT" => "d.m.Y",
						"SET_TITLE" => "N",
						"SET_STATUS_404" => "N",
						"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
						"ADD_SECTIONS_CHAIN" => "N",
						"HIDE_LINK_WHEN_NO_DETAIL" => "N",
						"PARENT_SECTION" => "",
						"PARENT_SECTION_CODE" => "",
						"INCLUDE_SUBSECTIONS" => "Y",
						"PAGER_TEMPLATE" => ".default",
						"DISPLAY_TOP_PAGER" => "N",
						"DISPLAY_BOTTOM_PAGER" => "N",
						"PAGER_TITLE" => "",
						"PAGER_SHOW_ALWAYS" => "N",
						"PAGER_DESC_NUMBERING" => "N",
						"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
						"PAGER_SHOW_ALL" => "N",
						"LINKED_MODE" => "Y",
						"TITLE_BLOCK" => '',
						"COUNT_IN_LINE" => ($bHideLeftBlock ? '4' : '3'),
					),
					false, array("HIDE_ICONS" => "Y")
				);?>
			<?else:?>
				<?$APPLICATION->IncludeComponent(
					"bitrix:news.list",
					"staff_list",
					array(
						"IBLOCK_TYPE" => "",
						"IBLOCK_ID" => $arParams['IBLOCK_LINK_STAFF_ID'],
						"NEWS_COUNT" => "",
						"SORT_BY1" => "SORT",
						"SORT_ORDER1" => "ASC",
						"SORT_BY2" => "ID",
						"SORT_ORDER2" => "DESC",
						"FILTER_NAME" => "arrStaffFilter",
						"FIELD_CODE" => array(
							0 => "PREVIEW_PICTURE",
							1 => "NAME",
							2 => "",
						),
						"PROPERTY_CODE" => array(
							0 => "POST",
							1 => "PHONE",
							2 => "EMAIL",
							3 => "SEND_MESSAGE_BUTTON",
						),
						"CHECK_DATES" => "Y",
						"DETAIL_URL" => "",
						"AJAX_MODE" => "N",
						"AJAX_OPTION_JUMP" => "N",
						"AJAX_OPTION_STYLE" => "Y",
						"AJAX_OPTION_HISTORY" => "N",
						'CACHE_TYPE' => $arParams['CACHE_TYPE'],
						'CACHE_TIME' => $arParams['CACHE_TIME'],
						'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
						"CACHE_FILTER" => "Y",
						"PREVIEW_TRUNCATE_LEN" => "",
						"ACTIVE_DATE_FORMAT" => "d.m.Y",
						"SET_TITLE" => "N",
						"SET_STATUS_404" => "N",
						"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
						"ADD_SECTIONS_CHAIN" => "N",
						"HIDE_LINK_WHEN_NO_DETAIL" => "N",
						"PARENT_SECTION" => "",
						"PARENT_SECTION_CODE" => "",
						"INCLUDE_SUBSECTIONS" => "Y",
						"PAGER_TEMPLATE" => ".default",
						"DISPLAY_TOP_PAGER" => "N",
						"DISPLAY_BOTTOM_PAGER" => "N",
						"PAGER_TITLE" => "",
						"PAGER_SHOW_ALWAYS" => "N",
						"PAGER_DESC_NUMBERING" => "N",
						"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
						"PAGER_SHOW_ALL" => "N",
						"LINKED_MODE" => "Y",
						"TITLE_BLOCK" => '',
						"COUNT_IN_LINE" => ($bHideLeftBlock ? '4' : '3'),
					),
					false, array("HIDE_ICONS" => "Y")
				);?>
			<?endif;?>
		<?$html=ob_get_clean();?>
		<?if($html && trim($html) && strpos($html, 'error') === false):?>
			<div class="ordered-block staff-block type_<?=$arParams['STAFF_TYPE'];?>  with-title">
				<div class="ordered-block__title option-font-bold font_lg">
					<?=$arParams["BLOCK_STAFF_NAME"];?>
				</div>
				<?=$html;?>
			</div>
			<div class="line-after"></div>
		<?endif;?>
	<?//video block?>
	<?elseif($code == 'video' && $templateData['VIDEO']):?>
		<div class="wraps video-block ordered-block with-title">
			<div class="ordered-block__title option-font-bold font_lg ">
				<?=($arParams["T_VIDEO"] ? $arParams["T_VIDEO"] : GetMessage("T_VIDEO"));?>
			</div>
			<div class="hidden_print">
				<div class="video_block row">
					<?if(count($templateData['VIDEO']) > 1):?>
						<?foreach($templateData['VIDEO'] as $v => $value):?>
							<div class="col-sm-6 col-xs-12">
								<?=str_replace('src=', 'width="660" height="457" src=', str_replace(array('width', 'height'), array('data-width', 'data-height'), $value));?>
							</div>
						<?endforeach;?>
					<?else:?>
						<div class="col-md-12"><?=$templateData['VIDEO'][0]?></div>
					<?endif;?>
				</div>
			</div>
		</div>
		<div class="line-after"></div>
	<?//docs block?>
	<?elseif($code == 'docs' && $templateData['DOCUMENTS']):?>
		<div class="wraps docs-block ordered-block with-title">
			<div class="ordered-block__title option-font-bold font_lg ">
				<?=($arParams["T_DOCS"] ? $arParams["T_DOCS"] : GetMessage("T_DOCS"));?>
			</div>
				<div class="docs_wrap files_block">
					<div class="row flexbox">
					<?foreach($templateData['DOCUMENTS'] as $docID):?>
						<?$arItem = CMax::GetFileInfo($docID);?>
						<div class="col-md-3 col-sm-6 col-xs-12">
							<?
							$fileName = substr($arItem['ORIGINAL_NAME'], 0, strrpos($arItem['ORIGINAL_NAME'], '.'));
							$fileTitle = (strlen($arItem['DESCRIPTION']) ? $arItem['DESCRIPTION'] : $fileName);

							?>
							<div class="file_type clearfix <?=$arItem["TYPE"];?>">
								<i class="icon"></i>
								<div class="description">
									<a href="<?=$arItem['SRC']?>" class="dark-color text" target="_blank"><?=$fileTitle?></a>
									<span class="size font_xs muted">
										<?=$arItem["FILE_SIZE_FORMAT"];?>
									</span>
								</div>
							</div>
						</div>
					<?endforeach;?>
				</div>
			</div>
		</div>
		<div class="line-after"></div>
    <?//gallery block?>
    <?elseif($code == 'gallery' && (is_array($templateData['GALLERY_BIG']) && count($templateData['GALLERY_BIG']))):?>
		<?
		$bShowSmallGallery = $templateData['GALLERY_TYPE'] === 'small';
		?>
		<div class="wraps galerys-block swipeignore  muted777 ordered-block with-title">
			<div class="ordered-block__title option-font-bold font_lg">
				<?=GetMessage("T_GALLERY");?>
			</div>
			<?//switch gallery?>
			<div class="switch-item-block">
			    <div class="flexbox flexbox--row">
				<div class="switch-item-block__count muted777 font_xs">
				    <div class="switch-item-block__count-wrapper switch-item-block__count-wrapper--small" <?=($bShowSmallGallery ? "" : "style='display:none;'");?>>
					<span class="switch-item-block__count-value"><?=count($templateData['GALLERY_BIG']);?></span>
					<?=GetMessage('T_GALLERY_TITLE');?>
					<span class="switch-item-block__count-separate">&mdash;</span>
				    </div>
				    <div class="switch-item-block__count-wrapper switch-item-block__count-wrapper--big" <?=($bShowSmallGallery ? "style='display:none;'" : "");?>>
					<span class="switch-item-block__count-value">1/<?=count($templateData['GALLERY_BIG']);?></span>
					<?=GetMessage('T_GALLERY_TITLE');?>
					<span class="switch-item-block__count-separate">&mdash;</span>
				    </div>
				</div>
				<div class="switch-item-block__icons-wrapper">
				    <span class="switch-item-block__icons<?=(!$bShowSmallGallery ? ' active' : '');?> switch-item-block__icons--big" title="<?=GetMessage("BIG_GALLERY");?>"><?=CMax::showIconSvg("gallery", SITE_TEMPLATE_PATH."/images/svg/gallery_alone.svg", "", "colored_theme_hover_bg-el-svg", true, false);?></span>
				    <span class="switch-item-block__icons<?=($bShowSmallGallery ? ' active' : '');?> switch-item-block__icons--small" title="<?=GetMessage("SMALL_GALLERY");?>"><?=CMax::showIconSvg("gallery", SITE_TEMPLATE_PATH."/images/svg/gallery_list.svg", "", "colored_theme_hover_bg-el-svg", true, false);?></span>
				</div>
			    </div>
			</div>

			<?//big gallery?>
			<div class="big-gallery-block "<?=($bShowSmallGallery ? ' style="display:none;"' : '');?> >
			    <div class="owl-carousel owl-theme owl-bg-nav short-nav" data-slider="content-detail-gallery__slider" data-plugin-options='{"items": "1", "autoplay" : false, "autoplayTimeout" : "3000", "smartSpeed":1000, "dots": true, "nav": true, "loop": false, "rewind":true, "index": true, "margin": 10}'>
				<?foreach($templateData['GALLERY_BIG'] as $i => $arPhoto):?>
				    <div class="item">
					<a href="<?=$arPhoto['DETAIL']['SRC']?>" class="fancy" data-fancybox="big-gallery" target="_blank" title="<?=$arPhoto['TITLE']?>">
					    <img data-src="<?=$arPhoto['PREVIEW']['src']?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($arPhoto['PREVIEW']['src']);?>" class="img-responsive inline lazy" title="<?=$arPhoto['TITLE']?>" alt="<?=$arPhoto['ALT']?>" />
					</a>
				    </div>
				<?endforeach;?>
			    </div>
			</div>

			<?//small gallery?>
			<?\Aspro\Functions\CAsproMax::showSmallGallery(['IS_ACTIVE' => $bShowSmallGallery], $templateData['GALLERY_BIG']);?>
		</div>
		<div class="line-after"></div>
	<?//vacancy?>
	<?elseif($code == 'vacancy' && $templateData['LINK_VACANCY']):?>
		<?ob_start();?>
			<?$GLOBALS['arrVacancyFilter'] = array('ID' => $templateData['LINK_VACANCY']);
			  $GLOBALS['arrVacancyFilter'] = array_merge($GLOBALS['arrVacancyFilter'], (array)$GLOBALS['arRegionLink']);?>
			<?$APPLICATION->IncludeComponent(
				"bitrix:news.list",
				"vacancy",
				array(
					"IBLOCK_TYPE" => "aspro_max_content",
					"IBLOCK_ID" => $arParams['IBLOCK_LINK_VACANCY_ID'],
					"NEWS_COUNT" => "20",
					"SORT_BY1" => "SORT",
					"SORT_ORDER1" => "ASC",
					"SORT_BY2" => "ID",
					"SORT_ORDER2" => "DESC",
					"FILTER_NAME" => "arrVacancyFilter",
					"FIELD_CODE" => array(
						0 => "NAME",
						1 => "DETAIL_PAGE_URL",
						2 => "PREVIEW_TEXT",
						3 => "PREVIEW_PICTURE",
					),
					"PROPERTY_CODE" => array(
						0 => "PAY",
						1 => "CITY",
						2 => "WORK_TYPE",
						3 => "QUALITY",
					),
					"CHECK_DATES" => "Y",
					"DETAIL_URL" => "",
					"AJAX_MODE" => "N",
					"AJAX_OPTION_JUMP" => "N",
					"AJAX_OPTION_STYLE" => "Y",
					"AJAX_OPTION_HISTORY" => "N",
					"CACHE_TYPE" => $arParams['CACHE_TYPE'],
					"CACHE_TIME" => "36000000",
					"CACHE_FILTER" => "Y",
					"HIDE_LINK_WHEN_NO_DETAIL" => "Y",
					"CACHE_GROUPS" => "N",
					"PREVIEW_TRUNCATE_LEN" => "",
					"ACTIVE_DATE_FORMAT" => "d.m.Y",
					"SET_TITLE" => "N",
					"SET_STATUS_404" => "N",
					"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
					"ADD_SECTIONS_CHAIN" => "N",
					"PARENT_SECTION" => "",
					"PARENT_SECTION_CODE" => "",
					"INCLUDE_SUBSECTIONS" => "Y",
					"PAGER_TEMPLATE" => ".default",
					"DISPLAY_TOP_PAGER" => "N",
					"DISPLAY_BOTTOM_PAGER" => "Y",
					"PAGER_TITLE" => "",
					"PAGER_SHOW_ALWAYS" => "N",
					"PAGER_DESC_NUMBERING" => "N",
					"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
					"PAGER_SHOW_ALL" => "N",
					"VIEW_TYPE" => "list",
					"IMAGE_POSITION" => "left",
					"COUNT_IN_LINE" => "3",
					"SHOW_TITLE" => "Y",
					"AJAX_OPTION_ADDITIONAL" => "",
					"BORDERED" => "Y",
					"LINKED_MODE" => "Y",
				),
				false, array("HIDE_ICONS" => "Y")
			);?>
		<?$html=ob_get_clean();?>
		<?if($html && trim($html) && strpos($html, 'error') === false):?>
			<div class="ordered-block <?=$code?> with-title">
				<div class="ordered-block__title option-font-bold font_lg ">
					<?=$arParams["BLOCK_VACANCY_NAME"];?>
				</div>
				<?=$html;?>
			</div>
			<div class="line-after"></div>
		<?endif;?>
	<?//blog?>
	<?elseif($code == 'blog' && $templateData['LINK_BLOG']):?>
		<?ob_start();?>
			<?$GLOBALS['arrBlogFilter'] = array('ID' => $templateData['LINK_BLOG']);
			  $GLOBALS['arrBlogFilter'] = array_merge($GLOBALS['arrBlogFilter'], (array)$GLOBALS['arRegionLink']);
			 ?>
			<?$APPLICATION->IncludeComponent(
				"bitrix:news.list",
				"news-list",
				array(
					"IBLOCK_TYPE" => "aspro_max_content",
					"IBLOCK_ID" => $arParams['IBLOCK_LINK_BLOG_ID'],
					"NEWS_COUNT" => "20",
					"SORT_BY1" => "SORT",
					"SORT_ORDER1" => "ASC",
					"SORT_BY2" => "ID",
					"SORT_ORDER2" => "DESC",
					"FILTER_NAME" => "arrBlogFilter",
					"FIELD_CODE" => array(
						0 => "NAME",
						1 => "DETAIL_PAGE_URL",
						2 => "PREVIEW_TEXT",
						3 => "PREVIEW_PICTURE",
						4 => "DATE_ACTIVE_FROM",
					),
					"PROPERTY_CODE" => array(
						0 => "PERIOD",
					),
					"CHECK_DATES" => "Y",
					"DETAIL_URL" => "",
					"AJAX_MODE" => "N",
					"AJAX_OPTION_JUMP" => "N",
					"AJAX_OPTION_STYLE" => "Y",
					"AJAX_OPTION_HISTORY" => "N",
					"CACHE_TYPE" => $arParams['CACHE_TYPE'],
					"CACHE_TIME" => "36000000",
					"CACHE_FILTER" => "Y",
					"HIDE_LINK_WHEN_NO_DETAIL" => "Y",
					"CACHE_GROUPS" => "N",
					"PREVIEW_TRUNCATE_LEN" => "",
					"ACTIVE_DATE_FORMAT" => "d.m.Y",
					"SET_TITLE" => "N",
					"SET_STATUS_404" => "N",
					"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
					"ADD_SECTIONS_CHAIN" => "N",
					"PARENT_SECTION" => "",
					"PARENT_SECTION_CODE" => "",
					"INCLUDE_SUBSECTIONS" => "Y",
					"PAGER_TEMPLATE" => ".default",
					"DISPLAY_TOP_PAGER" => "N",
					"DISPLAY_BOTTOM_PAGER" => "Y",
					"PAGER_TITLE" => "",
					"PAGER_SHOW_ALWAYS" => "N",
					"PAGER_DESC_NUMBERING" => "N",
					"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
					"PAGER_SHOW_ALL" => "N",
					"VIEW_TYPE" => "list",
					"IMAGE_POSITION" => "left",
					"COUNT_IN_LINE" => "3",
					"SHOW_TITLE" => "Y",
					"AJAX_OPTION_ADDITIONAL" => "",
					"BORDERED" => "Y",
					"LINKED_MODE" => "Y",
				),
				false, array("HIDE_ICONS" => "Y")
			);?>
		<?$html=ob_get_clean();?>
		<?if($html && trim($html) && strpos($html, 'error') === false):?>
			<div class="ordered-block <?=$code?> with-title">
				<div class="ordered-block__title option-font-bold font_lg ">
					<?=$arParams["BLOCK_BLOG_NAME"];?>
				</div>
				<?=$html;?>
			</div>
			<div class="line-after"></div>
		<?endif;?>

	<?//landings?>
	<?elseif($code == 'landings' && $templateData['LINK_LANDINGS']):?>
		<?ob_start();?>
			<?$GLOBALS['arrLandingsFilter'] = array('ID' => $templateData['LINK_LANDINGS']);
			  $GLOBALS['arrLandingsFilter'] = array_merge($GLOBALS['arrLandingsFilter'], (array)$GLOBALS['arRegionLink']);?>
			<?$APPLICATION->IncludeComponent(
				"bitrix:news.list",
				"news-list",
				array(
					"IBLOCK_TYPE" => "aspro_max_content",
					"IBLOCK_ID" => $arParams['IBLOCK_LINK_LANDINGS_ID'],
					"NEWS_COUNT" => "20",
					"SORT_BY1" => "SORT",
					"SORT_ORDER1" => "ASC",
					"SORT_BY2" => "ID",
					"SORT_ORDER2" => "DESC",
					"FILTER_NAME" => "arrLandingsFilter",
					"FIELD_CODE" => array(
						0 => "NAME",
						1 => "DETAIL_PAGE_URL",
						2 => "PREVIEW_TEXT",
						3 => "PREVIEW_PICTURE",
					),
					"PROPERTY_CODE" => array(
						0 => "",
					),
					"CHECK_DATES" => "Y",
					"DETAIL_URL" => "",
					"AJAX_MODE" => "N",
					"AJAX_OPTION_JUMP" => "N",
					"AJAX_OPTION_STYLE" => "Y",
					"AJAX_OPTION_HISTORY" => "N",
					"CACHE_TYPE" => $arParams['CACHE_TYPE'],
					"CACHE_TIME" => "36000000",
					"CACHE_FILTER" => "Y",
					"HIDE_LINK_WHEN_NO_DETAIL" => "Y",
					"CACHE_GROUPS" => "N",
					"PREVIEW_TRUNCATE_LEN" => "",
					"ACTIVE_DATE_FORMAT" => "d.m.Y",
					"SET_TITLE" => "N",
					"SET_STATUS_404" => "N",
					"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
					"ADD_SECTIONS_CHAIN" => "N",
					"PARENT_SECTION" => "",
					"PARENT_SECTION_CODE" => "",
					"INCLUDE_SUBSECTIONS" => "Y",
					"PAGER_TEMPLATE" => ".default",
					"DISPLAY_TOP_PAGER" => "N",
					"DISPLAY_BOTTOM_PAGER" => "Y",
					"PAGER_TITLE" => "",
					"PAGER_SHOW_ALWAYS" => "N",
					"PAGER_DESC_NUMBERING" => "N",
					"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
					"PAGER_SHOW_ALL" => "N",
					"VIEW_TYPE" => "list",
					"IMAGE_POSITION" => "left",
					"COUNT_IN_LINE" => "3",
					"SHOW_TITLE" => "Y",
					"AJAX_OPTION_ADDITIONAL" => "",
					"BORDERED" => "Y",
					"LINKED_MODE" => "Y",
				),
				false, array("HIDE_ICONS" => "Y")
			);?>
		<?$html=ob_get_clean();?>
		<?if($html && trim($html) && strpos($html, 'error') === false):?>
			<div class="ordered-block with-title">
				<div class="ordered-block__title option-font-bold font_lg ">
					<?=$arParams["BLOCK_LANDINGS_NAME"];?>
				</div>
				<?=$html;?>
			</div>
			<div class="line-after"></div>
		<?endif;?>

	<?//partners?>
	<?elseif($code == 'partners' && $templateData['LINK_PARTNERS']):?>
		<?ob_start();?>
			<?$GLOBALS['arrPartnersFilter'] = array('ID' => $templateData['LINK_PARTNERS']);
			  $GLOBALS['arrPartnersFilter'] = array_merge($GLOBALS['arrPartnersFilter'], (array)$GLOBALS['arRegionLink']);?>
			<?$APPLICATION->IncludeComponent(
				"bitrix:news.list",
				"news-list",
				array(
					"IBLOCK_TYPE" => "aspro_max_content",
					"IBLOCK_ID" => $arParams['IBLOCK_LINK_PARTNERS_ID'],
					"NEWS_COUNT" => "20",
					"SORT_BY1" => "SORT",
					"SORT_ORDER1" => "ASC",
					"SORT_BY2" => "ID",
					"SORT_ORDER2" => "DESC",
					"FILTER_NAME" => "arrPartnersFilter",
					"FIELD_CODE" => array(
						0 => "NAME",
						1 => "DETAIL_PAGE_URL",
						2 => "PREVIEW_TEXT",
						3 => "PREVIEW_PICTURE",
					),
					"PROPERTY_CODE" => array(
						0 => "SITE",
						1 => "PHONE",
					),
					"CHECK_DATES" => "Y",
					"DETAIL_URL" => "",
					"AJAX_MODE" => "N",
					"AJAX_OPTION_JUMP" => "N",
					"AJAX_OPTION_STYLE" => "Y",
					"AJAX_OPTION_HISTORY" => "N",
					"CACHE_TYPE" => $arParams['CACHE_TYPE'],
					"CACHE_TIME" => "36000000",
					"CACHE_FILTER" => "Y",
					"HIDE_LINK_WHEN_NO_DETAIL" => "Y",
					"CACHE_GROUPS" => "N",
					"PREVIEW_TRUNCATE_LEN" => "",
					"ACTIVE_DATE_FORMAT" => "d.m.Y",
					"SET_TITLE" => "N",
					"SET_STATUS_404" => "N",
					"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
					"ADD_SECTIONS_CHAIN" => "N",
					"PARENT_SECTION" => "",
					"PARENT_SECTION_CODE" => "",
					"INCLUDE_SUBSECTIONS" => "Y",
					"PAGER_TEMPLATE" => ".default",
					"DISPLAY_TOP_PAGER" => "N",
					"DISPLAY_BOTTOM_PAGER" => "Y",
					"PAGER_TITLE" => "",
					"PAGER_SHOW_ALWAYS" => "N",
					"PAGER_DESC_NUMBERING" => "N",
					"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
					"PAGER_SHOW_ALL" => "N",
					"VIEW_TYPE" => "list",
					"IMAGE_POSITION" => "left",
					"COUNT_IN_LINE" => "3",
					"SHOW_TITLE" => "Y",
					"AJAX_OPTION_ADDITIONAL" => "",
					"BORDERED" => "Y",
					"LINKED_MODE" => "Y",
					"HIDE_SECTION_NAME" => "Y",
				),
				false, array("HIDE_ICONS" => "Y")
			);?>
		<?$html=ob_get_clean();?>
		<?if($html && trim($html) && strpos($html, 'error') === false):?>
			<div class="ordered-block <?=$code?> with-title">
				<div class="ordered-block__title option-font-bold font_lg ">
					<?=$arParams["BLOCK_PARTNERS_NAME"];?>
				</div>
				<?=$html;?>
			</div>
			<div class="line-after"></div>
		<?endif;?>
    <?//goods?>
	<?elseif($code == 'goods'):?>
		<?if((in_array('LINK_GOODS', $arParams['PROPERTY_CODE']) || ($arParams['SHOW_LINKED_PRODUCTS'] == 'Y' && strlen($arParams['LINKED_PRODUCTS_PROPERTY'])))
			&& (isset($GLOBALS['arrProductsFilter']) || (isset($arParams['CONTENT_LINKED_FILTER_BY_FILTER']) && $arParams['CONTENT_LINKED_FILTER_BY_FILTER']))):?>
			<?
			$filter_by_filter = (isset($arParams['CONTENT_LINKED_FILTER_BY_FILTER']) && $arParams['CONTENT_LINKED_FILTER_BY_FILTER']);
			if($filter_by_filter){
				$cond = new CMaxCondition();
				try{
					$arTmpGoods = \Bitrix\Main\Web\Json::decode($arParams["~CONTENT_LINKED_FILTER_BY_FILTER"]);
					$arExGoodsFilter = $cond->parseCondition($arTmpGoods, $arParams);
				}
				catch(\Exception $e){
					$arExGoodsFilter = array();
				}
				unset($arTmpGoods);

				$GLOBALS['arrProductsFilter'] = array($arExGoodsFilter);
				unset($arParams['CONTENT_LINKED_FILTER_BY_FILTER']);
			}

			global $arTheme;
			$catalogIBlockID = ($arParams["IBLOCK_CATALOG_ID"] ? $arParams["IBLOCK_CATALOG_ID"] : $arTheme["CATALOG_IBLOCK_ID"]["VALUE"]);
			$arItemsFilter = array("IBLOCK_ID" => $catalogIBlockID, "ACTIVE" => "Y", 'SECTION_GLOBAL_ACTIVE' => 'Y');
			$arItemsFilter = array_merge($arItemsFilter, $GLOBALS['arrProductsFilter']);

			// if(is_array($GLOBALS['arRegionLink'])){
			// 	$arItemsFilter = array_merge($arItemsFilter, $GLOBALS['arRegionLink']);
			// }
			if($GLOBALS['arRegion']){
				if(CMax::GetFrontParametrValue('REGIONALITY_FILTER_ITEM') == 'Y' && CMax::GetFrontParametrValue('REGIONALITY_FILTER_CATALOG') == 'Y'){
					$arItemsFilter['PROPERTY_LINK_REGION'] = $GLOBALS['arRegion']['ID'];
					CMax::makeElementFilterInRegion($arItemsFilter);
				}
			}			
			if($arParams['SHOW_SECTIONS_FILTER']!="N"){
				$nTopCount = array("nTopCount" => 20000);
			} else {
				$nTopCount = array("nTopCount" => 10);
			}
			$arItems = CMaxCache::CIBLockElement_GetList(array('CACHE' => array("MULTI" =>"Y", "TAG" => CMaxCache::GetIBlockCacheTag($arTheme["CATALOG_IBLOCK_ID"]["VALUE"]))), $arItemsFilter, false, $nTopCount, array("ID", "IBLOCK_ID", "IBLOCK_SECTION_ID"));
			?>
			<?if($arItems):?>
				<div class="ordered-block <?=$code?> cur with-title">
				<?if($arParams["T_GOODS"]):?>
					<div class="ordered-block__title option-font-bold font_lg">
						<?=$arParams["T_GOODS"];?>
					</div>
				<?endif;?>
			<?endif;?>

				<?if($arParams['SHOW_SECTIONS_FILTER']!="N"):?>
					<div class="sections_wrap_detail">
						<?
						if($arItems)
						{
							$arSectionsID = array();
							foreach($arItems as $arItem)
							{
								if($arItem["IBLOCK_SECTION_ID"])
								{
									if(is_array($arItem["IBLOCK_SECTION_ID"]))
										$arSectionsID = array_merge($arSectionsID, $arItem["IBLOCK_SECTION_ID"]);
									else
										$arSectionsID[] = $arItem["IBLOCK_SECTION_ID"];
								}
							}

							if($arSectionsID){
								$arSectionsID = array_unique($arSectionsID);
							}

							if($arSectionsID):?>
									<?$GLOBALS["arDetailSections"] = array("ID" => $arSectionsID);?>
									<?$APPLICATION->IncludeComponent(
										"aspro:catalog.section.list.max",
										"sections_tags",
										Array(
											"IBLOCK_TYPE" => "aspro_max_catalog",
											"IBLOCK_ID" => $arTheme["CATALOG_IBLOCK_ID"]["VALUE"],
											"SECTION_ID" => '',
											"SECTION_CODE" => '',
											"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
											"CACHE_TYPE" => $arParams["CACHE_TYPE"],
											"CACHE_TIME" => $arParams["CACHE_TIME"],
											"CACHE_GROUPS" => 'N',
											"SECTION_URL" => '',
											"COUNT_ELEMENTS" => "N",
											"ADD_SECTIONS_CHAIN" => "N",
											"SHOW_SECTION_LIST_PICTURES" => 'N',
											"TOP_DEPTH" => $arParams["SECTIONS_TAGS_DEPTH_LEVEL"],
											"FILTER_NAME" => "arDetailSections",
											"CACHE_FILTER" => "Y",
											"COUNT_ELEMENTS" => $arParams["SHOW_COUNT_ELEMENTS"],
											"SECTION_USER_FIELDS" => array("UF_CATALOG_ICON"),
											"SHOW_COUNT" => $arParams["TAGS_SECTION_COUNT"],
											"FILTER_ELEMENTS_CNT" => $arItemsFilter,
										),
										false, array("HIDE_ICONS" => "Y")
									);?>
							<?endif;
						}?>
					</div>
				<?endif;?>

				<div class="assoc-block js-load-block tabs_slider loader_circle content_linked_goods" data-sections-ids="" data-block="assoc" data-file="<?=$APPLICATION->GetCurPage()?>">
					<div class="stub"></div>
					<?if($arParams['IS_AJAX_SECTIONS']=="Y" || $arParams['FROM_AJAX'] == 'Y'){
						$APPLICATION->RestartBuffer();

						$arFilterSectionsIDs = json_decode($_REQUEST['ajax_section_id']);
						if($arParams['SHOW_SECTIONS_FILTER']!="N" && isset($arFilterSectionsIDs) && is_array($arFilterSectionsIDs) && count($arFilterSectionsIDs)>0){
							$GLOBALS["arrProductsFilter"]['SECTION_ID'] = $arFilterSectionsIDs;
							$GLOBALS["arrProductsFilter"]['INCLUDE_SUBSECTIONS'] = 'Y';
							$GLOBALS["arrProductsFilter"]['SECTION_GLOBAL_ACTIVE'] = 'Y';
						}
					}else{
						CMax::checkRestartBuffer(true, 'assoc');
					}?>
						<?if(CMax::checkAjaxRequest() || CMax::checkAjaxRequest2()):?>
							<?$APPLICATION->ShowAjaxHead();?>
							<?include($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/detail.linked_products_block.php');?>
						<?endif;?>
					<?if($arParams['IS_AJAX_SECTIONS']=="Y" || $arParams['FROM_AJAX'] == 'Y'){
						die();
					}else{
						CMax::checkRestartBuffer(true, 'assoc');
					}?>
				</div>
			<?if($arItems):?>
				</div>
				<div class="line-after"></div>
			<?endif;?>
		<?endif;?>
	<?//goods sectios?>
	<?elseif($code == 'goods_sections'):?>
		<?if((in_array('LINK_GOODS', $arParams['PROPERTY_CODE']) || ($arParams['SHOW_LINKED_PRODUCTS'] == 'Y' && strlen($arParams['LINKED_PRODUCTS_PROPERTY'])))):?>
				<?$APPLICATION->ShowViewContent('only_sections_block')?>
		<?endif;?>
	<?//sale?>
	<?elseif($code == 'sale' && $templateData['LINK_SALE']):?>
		<?ob_start();?>
		<?$GLOBALS['arrSaleFilter'] = array('ID' => $templateData['LINK_SALE']);
		  $GLOBALS['arrSaleFilter'] = array_merge($GLOBALS['arrSaleFilter'], (array)$GLOBALS['arRegionLink']);?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:news.list",
			"news-list",
			array(
				"IBLOCK_TYPE" => "aspro_max_content",
				"IBLOCK_ID" => $arParams['IBLOCK_LINK_SALE_ID'],
				"NEWS_COUNT" => "20",
				"SORT_BY1" => "SORT",
				"SORT_ORDER1" => "ASC",
				"SORT_BY2" => "ID",
				"SORT_ORDER2" => "DESC",
				"FILTER_NAME" => "arrSaleFilter",
				"FIELD_CODE" => array(
					0 => "NAME",
					1 => "DETAIL_PAGE_URL",
					2 => "PREVIEW_TEXT",
					3 => "PREVIEW_PICTURE",
					4 => "ACTIVE_TO",
					5 => "DATE_ACTIVE_FROM",
				),
				"PROPERTY_CODE" => array(
					0 => "PERIOD",
					1 => "SALE_NUMBER",
				),
				"CHECK_DATES" => "Y",
				"DETAIL_URL" => "",
				"AJAX_MODE" => "N",
				"AJAX_OPTION_JUMP" => "N",
				"AJAX_OPTION_STYLE" => "Y",
				"AJAX_OPTION_HISTORY" => "N",
				"CACHE_TYPE" => $arParams['CACHE_TYPE'],
				"CACHE_TIME" => "36000000",
				"CACHE_FILTER" => "Y",
				"HIDE_LINK_WHEN_NO_DETAIL" => "Y",
				"CACHE_GROUPS" => "N",
				"PREVIEW_TRUNCATE_LEN" => "",
				"ACTIVE_DATE_FORMAT" => "d.m.Y",
				"SET_TITLE" => "N",
				"SET_STATUS_404" => "N",
				"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
				"ADD_SECTIONS_CHAIN" => "N",
				"PARENT_SECTION" => "",
				"PARENT_SECTION_CODE" => "",
				"INCLUDE_SUBSECTIONS" => "Y",
				"PAGER_TEMPLATE" => ".default",
				"DISPLAY_TOP_PAGER" => "N",
				"DISPLAY_BOTTOM_PAGER" => "Y",
				"PAGER_TITLE" => "",
				"PAGER_SHOW_ALWAYS" => "N",
				"PAGER_DESC_NUMBERING" => "N",
				"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
				"PAGER_SHOW_ALL" => "N",
				"VIEW_TYPE" => "list",
				"IMAGE_POSITION" => "left",
				"COUNT_IN_LINE" => "3",
				"SHOW_TITLE" => "Y",
				"AJAX_OPTION_ADDITIONAL" => "",
				"BORDERED" => "Y",
				"LINKED_MODE" => "Y",
				"SALE_MODE" => "Y",
			),
			false, array("HIDE_ICONS" => "Y")
		);?>
		<?$html=ob_get_clean();?>
		<?if($html && trim($html) && strpos($html, 'error') === false):?>
			<div class="ordered-block <?=$code?> with-title">
				<div class="ordered-block__title option-font-bold font_lg ">
					<?=$arParams["BLOCK_SALE_NAME"];?>
				</div>
				<?=$html;?>
			</div>
			<div class="line-after"></div>
		<?endif;?>

	<?//goods catalog?>
	<?elseif($code == 'goods_catalog'):?>
		<?if((in_array('LINK_GOODS', $arParams['PROPERTY_CODE']) || ($arParams['SHOW_LINKED_PRODUCTS'] == 'Y' && strlen($arParams['LINKED_PRODUCTS_PROPERTY'])))):?>
				<?$APPLICATION->ShowViewContent('goods_catalog_block_prolog')?>
				<?$APPLICATION->ShowViewContent('goods_catalog_block')?>
				<?$APPLICATION->ShowViewContent('goods_catalog_block_epilog')?>
		<?endif;?>
	<?endif;?>
<?endforeach;?>
<div style="clear:both"></div>
</div><?//detail close?>
<script>typeof useCountdown === 'function' && useCountdown()</script>
<?

if ($templateData['USE_SLIDER']) {
	$libs[] = 'owl_carousel';
	$libs[] = 'swiper';
	$libs[] = 'swiper_main_styles';
}
if ($bShowSmallGallery) {
	$libs[] = 'gallery_small';
}
?>
<?\Aspro\Max\Functions\Extensions::init($libs);?>
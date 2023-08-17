<?$APPLICATION->IncludeComponent(
	"bitrix:news.detail",
	"news",
	Array(
		"S_ASK_QUESTION" => $arParams["S_ASK_QUESTION"],
		"S_ORDER_SERVISE" => $arParams["S_ORDER_SERVISE"],
		"T_GALLERY" => $arParams["T_GALLERY"],
		"T_DOCS" => $arParams["T_DOCS"],
		"T_GOODS" => str_replace("#BRAND_NAME#",$arElement["NAME"],(strlen($arParams["T_GOODS"])?$arParams["T_GOODS"]:GetMessage("T_GOODS"))),//$arParams["T_GOODS"],
		"T_SERVICES" => $arParams["T_SERVICES"],
		"T_PROJECTS" => $arParams["T_PROJECTS"],
		"T_REVIEWS" => $arParams["T_REVIEWS"],
		"T_STAFF" => $arParams["T_STAFF"],
		"T_VIDEO" => $arParams["T_VIDEO"],
		"FORM_ID_ORDER_SERVISE" => ($arParams["FORM_ID_ORDER_SERVISE"] ? $arParams["FORM_ID_ORDER_SERVISE"] : 'SERVICES'),
		"DISPLAY_DATE" => $arParams["DISPLAY_DATE"],
		"DISPLAY_NAME" => $arParams["DISPLAY_NAME"],
		"DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],
		"DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],
		"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"FIELD_CODE" => $arParams["DETAIL_FIELD_CODE"],
		"PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
		"DETAIL_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
		"SECTION_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"META_KEYWORDS" => $arParams["META_KEYWORDS"],
		"META_DESCRIPTION" => $arParams["META_DESCRIPTION"],
		"BROWSER_TITLE" => $arParams["BROWSER_TITLE"],
		"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
		"SET_CANONICAL_URL" => $arParams["DETAIL_SET_CANONICAL_URL"],
		"SET_TITLE" => $arParams["SET_TITLE"],
		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
		"INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
		"ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
		"ADD_ELEMENT_CHAIN" => $arParams["ADD_ELEMENT_CHAIN"],
		"ACTIVE_DATE_FORMAT" => $arParams["DETAIL_ACTIVE_DATE_FORMAT"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
		"GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
		"DISPLAY_TOP_PAGER" => $arParams["DETAIL_DISPLAY_TOP_PAGER"],
		"DISPLAY_BOTTOM_PAGER" => $arParams["DETAIL_DISPLAY_BOTTOM_PAGER"],
		"PAGER_TITLE" => $arParams["DETAIL_PAGER_TITLE"],
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => $arParams["DETAIL_PAGER_TEMPLATE"],
		"PAGER_SHOW_ALL" => $arParams["DETAIL_PAGER_SHOW_ALL"],
		"CHECK_DATES" => $arParams["CHECK_DATES"],
		"ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
		"ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
		"IBLOCK_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
		"USE_SHARE" 			=> $arParams["USE_SHARE"],
		"SHARE_HIDE" 			=> $arParams["SHARE_HIDE"],
		"SHARE_TEMPLATE" 		=> $arParams["SHARE_TEMPLATE"],
		"SHARE_HANDLERS" 		=> $arParams["SHARE_HANDLERS"],
		"SHARE_SHORTEN_URL_LOGIN"	=> $arParams["SHARE_SHORTEN_URL_LOGIN"],
		"SHARE_SHORTEN_URL_KEY" => $arParams["SHARE_SHORTEN_URL_KEY"],
		"GALLERY_TYPE" => $arParams["GALLERY_TYPE"],
		"DETAIL_USE_COMMENTS" => $arParams["DETAIL_USE_COMMENTS"],
		"DETAIL_BLOG_USE" => $arParams["DETAIL_BLOG_USE"],
		"DETAIL_BLOG_URL" => $arParams["DETAIL_BLOG_URL"],
		"DETAIL_BLOG_EMAIL_NOTIFY" => $arParams["DETAIL_BLOG_EMAIL_NOTIFY"],
		"DETAIL_VK_USE" => $arParams["DETAIL_VK_USE"],
		"DETAIL_VK_API_ID" => $arParams["DETAIL_VK_API_ID"],
		"DETAIL_FB_USE" => $arParams["DETAIL_FB_USE"],
		"DETAIL_FB_APP_ID" => $arParams["DETAIL_FB_APP_ID"],
		"COMMENTS_COUNT" => $arParams["COMMENTS_COUNT"],
		"BLOG_TITLE" => $arParams["BLOG_TITLE"],
		"VK_TITLE" => $arParams["VK_TITLE"],
		"FB_TITLE" => $arParams["FB_TITLE"],
		"STAFF_TYPE" => $arParams["STAFF_TYPE_DETAIL"],
		"IBLOCK_LINK_NEWS_ID" => $arParams["IBLOCK_LINK_NEWS_ID"],
		"IBLOCK_LINK_BLOG_ID" => $arParams["IBLOCK_LINK_BLOG_ID"],
		"IBLOCK_LINK_SERVICES_ID" => $arParams["IBLOCK_LINK_SERVICES_ID"],
	    	"IBLOCK_LINK_TIZERS_ID" => $arParams["IBLOCK_LINK_TIZERS_ID"],
		"IBLOCK_LINK_REVIEWS_ID" => $arParams["IBLOCK_LINK_REVIEWS_ID"],
		"IBLOCK_LINK_STAFF_ID" => $arParams["IBLOCK_LINK_STAFF_ID"],
		"IBLOCK_LINK_VACANCY_ID" => $arParams["IBLOCK_LINK_VACANCY_ID"],
		"IBLOCK_LINK_PROJECTS_ID" => $arParams["IBLOCK_LINK_PROJECTS_ID"],
		"IBLOCK_LINK_BRANDS_ID" => $arParams["IBLOCK_LINK_BRANDS_ID"],
		"IBLOCK_LINK_LANDINGS_ID" => $arParams["IBLOCK_LINK_LANDINGS_ID"],
		"IBLOCK_LINK_PARTNERS_ID" => $arParams["IBLOCK_LINK_PARTNERS_ID"],
		"BLOCK_SERVICES_NAME" => $arParams["BLOCK_SERVICES_NAME"],
		"BLOCK_NEWS_NAME" => $arParams["BLOCK_NEWS_NAME"],
		"BLOCK_BLOG_NAME" => $arParams["BLOCK_BLOG_NAME"],
	    	"BLOCK_TIZERS_NAME" => $arParams["BLOCK_TIZERS_NAME"],
		"BLOCK_REVIEWS_NAME" => $arParams["BLOCK_REVIEWS_NAME"],
		"BLOCK_STAFF_NAME" => $arParams["BLOCK_STAFF_NAME"],
		"BLOCK_VACANCY_NAME" => $arParams["BLOCK_VACANCY_NAME"],
		"BLOCK_PROJECTS_NAME" => $arParams["BLOCK_PROJECTS_NAME"],
		"BLOCK_BRANDS_NAME" => $arParams["BLOCK_BRANDS_NAME"],
		"BLOCK_LANDINGS_NAME" => $arParams["BLOCK_LANDINGS_NAME"],
		"BLOCK_PARTNERS_NAME" => $arParams["BLOCK_PARTNERS_NAME"],
		"DETAIL_BLOCKS_ALL_ORDER" => ($arParams["DETAIL_BLOCKS_ALL_ORDER"] ? $arParams["DETAIL_BLOCKS_ALL_ORDER"] : 'tizers,desc,char,docs,services,news,blog,vacancy,reviews,projects,staff,landings,comments'),
		//"CONTENT_LINKED_FILTER_BY_FILTER" => ($arTmpGoods['CHILDREN'] ? $arElement['~PROPERTY_LINK_GOODS_FILTER_VALUE']:''),
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"STORES" => $arParams["STORES"],
		"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
		"DISPLAY_ELEMENT_SLIDER" => $arParams["LINKED_ELEMENST_PAGE_COUNT"],
		"LINKED_ELEMENST_PAGINATION" => $arParams["LINKED_ELEMENST_PAGINATION"],
		"SHOW_DISCOUNT_PERCENT_NUMBER" => $arParams["SHOW_DISCOUNT_PERCENT_NUMBER"],
		"DETAIL_LINKED_GOODS_SLIDER" => $arParams["DETAIL_LINKED_GOODS_SLIDER"],
		"SHOW_LINKED_PRODUCTS" => $arParams["SHOW_LINKED_PRODUCTS"],
		"LINKED_PRODUCTS_PROPERTY" => $arParams["LINKED_PRODUCTS_PROPERTY"],
		"PARTNERS_MODE" => "Y",
		"LINKED_ELEMENT_TAB_SORT_FIELD" => $arParams["LINKED_ELEMENT_TAB_SORT_FIELD"],
		"LINKED_ELEMENT_TAB_SORT_ORDER" => $arParams["LINKED_ELEMENT_TAB_SORT_ORDER"],
		"LINKED_ELEMENT_TAB_SORT_FIELD2" => $arParams["LINKED_ELEMENT_TAB_SORT_FIELD2"],
		"LINKED_ELEMENT_TAB_SORT_ORDER2" => $arParams["LINKED_ELEMENT_TAB_SORT_ORDER2"],
	),
	$component
);?>


<? // link goods?>
<?if($arParams["SHOW_LINKED_PRODUCTS"] == "Y" && strlen($arParams["LINKED_PRODUCTS_PROPERTY"])):?>
	<?$this->SetViewTarget('only_sections_block');?>
		<?global $arTheme?>
		
		<?
		$catalogIBlockID = ($arParams["IBLOCK_CATALOG_ID"] ? $arParams["IBLOCK_CATALOG_ID"] : $arTheme["CATALOG_IBLOCK_ID"]["VALUE"]);

		$arItemsFilter = array("IBLOCK_ID" => $catalogIBlockID, "ACTIVE"=>"Y", "PROPERTY_".$arParams["LINKED_PRODUCTS_PROPERTY"] => $arElement["ID"], 'SECTION_GLOBAL_ACTIVE' => 'Y');
		CMax::makeElementFilterInRegion($arItemsFilter);
		if(is_array($GLOBALS['arRegionLink'])){
			$arItemsFilter = array_merge($GLOBALS['arRegionLink'], $arItemsFilter);
		}
		$arItems = CMaxCache::CIBLockElement_GetList(array('CACHE' => array("MULTI" =>"Y", "TAG" => CMaxCache::GetIBlockCacheTag($arTheme["CATALOG_IBLOCK_ID"]["VALUE"]))), $arItemsFilter, false, false, array("ID", "IBLOCK_ID", "IBLOCK_SECTION_ID"));

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
				<div class="ordered-block goods_sections cur with-title">
					<?//if($arParams["T_GOODS"]):?>
						<div class="ordered-block__title option-font-bold font_lg">
							<?=str_replace("#BRAND_NAME#", $arElement["NAME"], (strlen($arParams['T_GOODS_SECTION']) ? $arParams['T_GOODS_SECTION'] : GetMessage('T_GOODS_SECTION')));//$arParams["T_GOODS"];?>
						</div>
					<?//endif;?>
					
					<?$GLOBALS["arBrandSections"] = array("ID" => $arSectionsID);?>
					
					<?$APPLICATION->IncludeComponent(
						"aspro:catalog.section.list.max",
						"sections_compact",
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
							"ADD_SECTIONS_CHAIN" => "N",
							"SHOW_SECTION_LIST_PICTURES" => 'Y',//$arParams["SHOW_SECTION_PICTURES"],
							"TOP_DEPTH" => $arParams["DEPTH_LEVEL_BRAND"],
							"FILTER_NAME" => "arBrandSections",
							"CACHE_FILTER" => "Y",
							"USE_FILTER_SECTION" => "Y",
							"BRAND_NAME" => $arElement["NAME"],
							"BRAND_CODE" => $arElement["CODE"],
							"SHOW_ICONS" => "Y",//$arParams["SHOW_ICONS_SECTION"],
							"COUNT_ELEMENTS" => $arParams["SHOW_COUNT_ELEMENTS"],//$arParams["SECTION_COUNT_ELEMENTS"],
							"SECTION_USER_FIELDS" => array("UF_CATALOG_ICON"),
							"NO_MARGIN" => "Y",
							"FILTER_ELEMENTS_CNT" => $arItemsFilter,
						),
						false, array("HIDE_ICONS" => "Y")
					);?>
				</div>
				<div class="line-after"></div>
			<?endif;
		}?>
	<?$this->EndViewTarget();?>
<?endif;?>

<?$APPLICATION->ShowViewContent('bottom_links_block');?>
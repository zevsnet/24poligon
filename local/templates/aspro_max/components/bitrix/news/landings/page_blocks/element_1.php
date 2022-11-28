<?global $isHideLeftBlock, $needLEftBlock;?>
<?$bHideLeftBlock = ($APPLICATION->GetProperty("HIDE_LEFT_BLOCK") == "Y");//$isHideLeftBlock;?>


<?$isAjax="N";?>
<?if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest"  && isset($_GET["ajax_get"]) && $_GET["ajax_get"] == "Y" || (isset($_GET["ajax_basket"]) && $_GET["ajax_basket"]=="Y")){
	$isAjax="Y";
}?>
<?if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest" && isset($_GET["ajax_get_filter"]) && $_GET["ajax_get_filter"] == "Y" ){
	$isAjaxFilter="Y";
}?>
<?global $arTheme, $arRegion;?>

		<?
		/*if($arParams["FILTER_NAME"] == '' || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"])){
			$arParams["FILTER_NAME"] = "arrFilter";
		}*/

		$arParams["FILTER_NAME"] = "arRegionLink";

		if(!in_array($arParams["LIST_OFFERS_FIELD_CODE"], "DETAIL_PAGE_URL")){
			$arParams["LIST_OFFERS_FIELD_CODE"][] = "DETAIL_PAGE_URL";
		}

		$catalogIBlockID = ($arParams["IBLOCK_CATALOG_ID"] ? $arParams["IBLOCK_CATALOG_ID"] : $arTheme["CATALOG_IBLOCK_ID"]["VALUE"]);


		// decode CUSTOM_FILTER
        if(\Bitrix\Main\Loader::includeModule('catalog') && class_exists('CMaxCondition')){
            $arCustomFilter = array();
			$cond = new CMaxCondition();
			$elementFilter = isset($arElement['~PROPERTY_FILTER_NEW_VALUE']) ? $arElement['~PROPERTY_FILTER_NEW_VALUE'] : $arElement['PROPERTY_FILTER_NEW_VALUE'];
            $elementFilter = (array)$elementFilter;

            foreach($elementFilter as $customFilter){
                if(isset($customFilter) && is_string($customFilter)){
                    try{
						$customFilter = $cond->parseCondition(\Bitrix\Main\Web\Json::decode($customFilter), $arParams);
                    }
                    catch(\Exception $e){
                        $customFilter = array();
                    }
                }

                if($customFilter){
                    $arCustomFilter = array_merge($arCustomFilter, $customFilter);
                }
			}
		}


		// sort
		ob_start();
		include_once(__DIR__."/../sort.php");
		$htmlSort = ob_get_clean();
		$listElementsTemplate = $template;
		?>

		<?if($arCustomFilter) {
			$GLOBALS[$arParams['FILTER_NAME']] = $GLOBALS[$arParams['FILTER_NAME']] ? $GLOBALS[$arParams['FILTER_NAME']] : array();
			$GLOBALS[$arParams['FILTER_NAME']]['LOGIC'] = 'AND';
			$GLOBALS[$arParams['FILTER_NAME']][] = array_merge($GLOBALS[$arParams['FILTER_NAME']], $arCustomFilter);
		} else {
			ob_start();
			include_once(__DIR__."/../filter.php");
			$htmlFilter=ob_get_clean();
			$APPLICATION->AddViewContent('filter_content', $htmlFilter);
		}?>

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
				"IS_LANDING" => "Y",
			),
			$component
		);?>

<?if($arElement['PROPERTY_FILTER_URL_VALUE'] || $arElement['PROPERTY_SECTION_VALUE'] || $arCustomFilter):?>
<?// start catalog section block?>

		<?ob_start()//goods_catalog_block prolog?>
		<div class="ordered-block goods_catalog with-title <?=($bHideLeftBlock ? 'right_block wide_Y' : '')?>">
				<?if($arElement["PROPERTY_H3_GOODS_VALUE"]):?>
					<div class="ordered-block__title font_lg">
							<?=$arElement["PROPERTY_H3_GOODS_VALUE"];?>
					</div>
				<?endif;?>



			<div class="main-catalog-wrapper catalog_in_content">
				<div class="section-content-wrapper <?=(!$bHideLeftBlock ? 'with-leftblock' : '');?> js-load-wrapper">

		<?$html=ob_get_clean();?>
		<?$APPLICATION->AddViewContent('goods_catalog_block_prolog', $html);//?>

				<?if($isAjax=="Y" || $isAjaxFilter):?>
					<?$APPLICATION->RestartBuffer();?>
				<?endif;?>

				<?ob_start()//goods_catalog_block ?>

					<?if(!empty($arElement['PROPERTY_SECTION_VALUE']) || !empty($arElement['PROPERTY_FILTER_URL_VALUE']) || $arCustomFilter):?>

						<div class="catalog vertical filter_exists">
							<?
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

							if($arParams['LIST_PRICES'])
							{
								foreach($arParams['LIST_PRICES'] as $key => $price)
								{
									if(!$price)
										unset($arParams['LIST_PRICES'][$key]);
								}
							}

							if($arParams['STORES'])
							{
								foreach($arParams['STORES'] as $key => $store)
								{
									if(!$store)
										unset($arParams['STORES'][$key]);
								}
							}

							if($arRegion)
							{
								if($arRegion["LIST_STORES"] && $arParams["HIDE_NOT_AVAILABLE"] == "Y")
								{
									if($arParams['STORES']){
										if(CMax::checkVersionModule('18.6.200', 'iblock')){
											$arStoresFilter = array(
												'STORE_NUMBER' => $arParams['STORES'],
												'>STORE_AMOUNT' => 0,
											);
										}
										else{
											if(count($arParams['STORES']) > 1){
												$arStoresFilter = array('LOGIC' => 'OR');
												foreach($arParams['STORES'] as $storeID)
												{
													$arStoresFilter[] = array(">CATALOG_STORE_AMOUNT_".$storeID => 0);
												}
											}
											else{
												foreach($arParams['STORES'] as $storeID)
												{
													$arStoresFilter = array(">CATALOG_STORE_AMOUNT_".$storeID => 0);
												}
											}
										}

										$arTmpFilter = array('!TYPE' => array('2', '3'));
										if($arStoresFilter){
											if(count($arStoresFilter) > 1){
												$arTmpFilter[] = $arStoresFilter;
											}
											else{
												$arTmpFilter = array_merge($arTmpFilter, $arStoresFilter);
											}

											$GLOBALS[$arParams["FILTER_NAME"]][] = array(
												'LOGIC' => 'OR',
												array('TYPE' => array('2', '3')),
												$arTmpFilter,
											);
										}
									}
								}

								$GLOBALS[$arParams['FILTER_NAME']]['IBLOCK_ID'] = $catalogIBlockID;
								CMax::makeElementFilterInRegion($GLOBALS[$arParams['FILTER_NAME']]);

							}


							if( $arElement['PROPERTY_SECTION_VALUE'] && !$arCustomFilter ) {
								$GLOBALS[$arParams["FILTER_NAME"]]['SECTION_ID'] = $arElement['PROPERTY_SECTION_VALUE'];
							}

        					$GLOBALS[$arParams["FILTER_NAME"]]['INCLUDE_SUBSECTIONS'] = 'Y';
							$GLOBALS[$arParams["FILTER_NAME"]]['SECTION_GLOBAL_ACTIVE'] = 'Y';
							?>
							<?//=$htmlSections;?>

									<?if($isAjax=="N"){
										//$frame = new \Bitrix\Main\Page\FrameHelper("viewtype-brand-block");
										//$frame->begin();
										\Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("viewtype-brand-block");
										?>
									<?}?>

									<?// sort?>
									<?=$htmlSort?>

									<?$show = ($arParams["LINKED_ELEMENST_PAGE_COUNT"] ? $arParams["LINKED_ELEMENST_PAGE_COUNT"] : 20);?>
									<?if(isset($GLOBALS[$arParams["FILTER_NAME"]]["FACET_OPTIONS"]))
										unset($GLOBALS[$arParams["FILTER_NAME"]]["FACET_OPTIONS"]);?>

							<div class="inner_wrapper">
								<div class="ajax_load cur <?=$display?>" data-code="<?=$display?>">
									<?$arTransferParams = array(
										"SHOW_ABSENT" => $arParams["SHOW_ABSENT"],
										"HIDE_NOT_AVAILABLE_OFFERS" => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],
										"PRICE_CODE" => $arParams["PRICE_CODE"],
										"OFFER_TREE_PROPS" => $arParams["OFFER_TREE_PROPS"],
										"CACHE_TIME" => $arParams["CACHE_TIME"],
										"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
										"CURRENCY_ID" => $arParams["CURRENCY_ID"],
										"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
										"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
										"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
										"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
										"LIST_OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],
										"LIST_OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
										"SHOW_DISCOUNT_TIME" => $arParams["SHOW_DISCOUNT_TIME"],
										"SHOW_COUNTER_LIST" => $arParams["SHOW_COUNTER_LIST"],
										"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
										"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
										"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
										"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
										"SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"],
										"SHOW_DISCOUNT_PERCENT" => $arParams["SHOW_DISCOUNT_PERCENT"],
										"SHOW_DISCOUNT_PERCENT_NUMBER" => $arParams["SHOW_DISCOUNT_PERCENT_NUMBER"],
										"USE_REGION" => ($arRegion ? "Y" : "N"),
										"STORES" => $arParams["STORES"],
										"DEFAULT_COUNT" => $arParams["DEFAULT_COUNT"],
										//"BASKET_URL" => $arTheme["BASKET_PAGE_URL"]["VALUE"],
										"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
										"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
										"PARTIAL_PRODUCT_PROPERTIES" => $arParams["PARTIAL_PRODUCT_PROPERTIES"],
										"ADD_PROPERTIES_TO_BASKET" => $arParams["ADD_PROPERTIES_TO_BASKET"],
										"SHOW_DISCOUNT_TIME_EACH_SKU" => $arParams["SHOW_DISCOUNT_TIME_EACH_SKU"],
										"SHOW_ARTICLE_SKU" => $arParams["SHOW_ARTICLE_SKU"],
										"OFFER_ADD_PICT_PROP" => $arParams["OFFER_ADD_PICT_PROP"],
										"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
										"OFFER_SHOW_PREVIEW_PICTURE_PROPS" => $arParams["OFFER_SHOW_PREVIEW_PICTURE_PROPS"],
										"SHOW_GALLERY" => $arParams["SHOW_GALLERY_GOODS"],
										"MAX_GALLERY_ITEMS" => $arParams["MAX_GALLERY_GOODS_ITEMS"],
										"ADD_PICT_PROP" => $arParams["ADD_PICT_PROP"],
										"ADD_DETAIL_TO_SLIDER" => $arParams["ADD_DETAIL_TO_SLIDER"],
										"SET_SKU_TITLE" => "Y",
										"IBINHERIT_TEMPLATES" => $arElement ? $arIBInheritTemplates : array(),
									);?>
									<?//var_dump($GLOBALS[$arParams["FILTER_NAME"]])?>
									<div class=" <?=$display;?> js_wrapper_items" data-params='<?=str_replace('\'', '"', CUtil::PhpToJSObject($arTransferParams, false))?>'>
										<?$APPLICATION->IncludeComponent(
											"bitrix:catalog.section",
											$listElementsTemplate,
											Array(
												"USE_REGION" => ($arRegion ? "Y" : "N"),
												"STORES" => $arParams['STORES'],
												"SHOW_UNABLE_SKU_PROPS"=>$arParams["SHOW_UNABLE_SKU_PROPS"],
												"ALT_TITLE_GET" => $arParams["ALT_TITLE_GET"],
												"SEF_URL_TEMPLATES" => $arParams["SEF_URL_TEMPLATES"],
												"IBLOCK_TYPE" => $arParams["IBLOCK_CATALOG_TYPE"],
												"IBLOCK_ID" => $catalogIBlockID,
												"SHOW_COUNTER_LIST" => "Y",
												"SECTION_ID" => '',
												"SECTION_CODE" => '',
												"AJAX_REQUEST" => (($isAjax == "Y" && $isAjaxFilter != "Y") ? "Y" : "N"),
												"ELEMENT_SORT_FIELD" => $sort,
												"ELEMENT_SORT_ORDER" => $sort_order,
												"SHOW_DISCOUNT_TIME_EACH_SKU" => $arParams["SHOW_DISCOUNT_TIME_EACH_SKU"],
												"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
												"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
												"FILTER_NAME" => $arParams["FILTER_NAME"],
												"INCLUDE_SUBSECTIONS" => "Y",
												"SHOW_ALL_WO_SECTION" => "Y",
												"PAGE_ELEMENT_COUNT" => $show,
												"LINE_ELEMENT_COUNT" => $linerow,//$arParams["LINE_ELEMENT_COUNT"],
												"SHOW_PROPS" => (CMax::GetFrontParametrValue("SHOW_PROPS_BLOCK") == "Y" ? "Y" : "N"),
												'SHOW_POPUP_PRICE' => (CMax::GetFrontParametrValue('SHOW_POPUP_PRICE') == 'Y' ? "Y" : "N"),
												'TYPE_VIEW_BASKET_BTN' => CMax::GetFrontParametrValue('TYPE_VIEW_BASKET_BTN'),
												'TYPE_VIEW_CATALOG_LIST' => CMax::GetFrontParametrValue('TYPE_VIEW_CATALOG_LIST'),
												"MANY_BUY_CATALOG_SECTIONS" => CMax::GetFrontParametrValue('MANY_BUY_CATALOG_SECTIONS'),
												"DISPLAY_TYPE" => $display,
												"TYPE_SKU" => $arTheme["TYPE_SKU"]["VALUE"],
												"PROPERTY_CODE" => $arParams["LIST_PROPERTY_CATALOG_CODE"],
												"SHOW_ARTICLE_SKU" => $arParams["SHOW_ARTICLE_SKU"],
												"SHOW_MEASURE_WITH_RATIO" => $arParams["SHOW_MEASURE_WITH_RATIO"],

												"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
												"OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
												"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
												"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
												"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
												"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
												'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
												"USE_CUSTOM_RESIZE_LIST" => $arTheme['USE_CUSTOM_RESIZE_LIST']['VALUE'],

												"OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],

												"SECTION_URL" => "",
												"DETAIL_URL" => "",
												"BASKET_URL" => $arTheme["BASKET_PAGE_URL"]["VALUE"],
												"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
												"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
												"PRODUCT_QUANTITY_VARIABLE" => "quantity",
												"PRODUCT_PROPS_VARIABLE" => "prop",
												"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
												"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
												"AJAX_MODE" => $arParams["AJAX_MODE"],
												"AJAX_OPTION_JUMP" => $arParams["AJAX_OPTION_JUMP"],
												"AJAX_OPTION_STYLE" => $arParams["AJAX_OPTION_STYLE"],
												"AJAX_OPTION_HISTORY" => $arParams["AJAX_OPTION_HISTORY"],
												"CACHE_TYPE" => $arParams["CACHE_TYPE"],
												"CACHE_TIME" => $arParams["CACHE_TIME"],
												"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
												"CACHE_FILTER" => "Y",
												"META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
												"META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
												"BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
												"ADD_SECTIONS_CHAIN" => "N",
												"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
												'HIDE_NOT_AVAILABLE_OFFERS' => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],
												"SET_TITLE" => "N",
												"SET_STATUS_404" => "N",
												"SHOW_404" => "N",
												"MESSAGE_404" => "",
												"FILE_404" => "",
												"PRICE_CODE" => $arParams['PRICE_CODE'],
												"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
												"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
												"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
												"USE_PRODUCT_QUANTITY" => $arParams["USE_PRODUCT_QUANTITY"],
												"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
												"DISPLAY_TOP_PAGER" => "N",
												"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],

												"PAGER_TITLE" => $arParams["PAGER_TITLE"],
												"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
												"PAGER_TEMPLATE" => "main",
												"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
												"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
												"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],

												"AJAX_OPTION_ADDITIONAL" => "",
												"ADD_CHAIN_ITEM" => "N",
												"SHOW_QUANTITY" => $arParams["SHOW_QUANTITY"],
												"SHOW_QUANTITY_COUNT" => $arParams["SHOW_QUANTITY_COUNT"],
												"SHOW_DISCOUNT_PERCENT" => $arParams["SHOW_DISCOUNT_PERCENT"],
												"SHOW_DISCOUNT_PERCENT_NUMBER" => $arParams["SHOW_DISCOUNT_PERCENT_NUMBER"],
												"SHOW_DISCOUNT_TIME" => $arParams["SHOW_DISCOUNT_TIME"],
												"SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"],
												"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
												"CURRENCY_ID" => $arParams["CURRENCY_ID"],
												"USE_STORE" => $arParams["USE_STORE"],
												"MAX_AMOUNT" => $arParams["MAX_AMOUNT"],
												"MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
												"USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
												"USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
												"DISPLAY_WISH_BUTTONS" => $arParams["DISPLAY_WISH_BUTTONS"],
												"SHOW_ONE_CLICK_BUY" => $arParams["SHOW_ONE_CLICK_BUY"],
												"LIST_DISPLAY_POPUP_IMAGE" => $arParams["LIST_DISPLAY_POPUP_IMAGE"],
												"DEFAULT_COUNT" => 1,
												"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
												"SHOW_HINTS" => $arParams["SHOW_HINTS"],
												"OFFER_HIDE_NAME_PROPS" => $arParams["OFFER_HIDE_NAME_PROPS"],
												"SHOW_SECTIONS_LIST_PREVIEW" => $arParams["SHOW_SECTIONS_LIST_PREVIEW"],
												"SECTIONS_LIST_PREVIEW_PROPERTY" => $arParams["SECTIONS_LIST_PREVIEW_PROPERTY"],
												"SHOW_SECTION_LIST_PICTURES" => $arParams["SHOW_SECTION_LIST_PICTURES"],
												"USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
												"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
												"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
												"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
												"SALE_STIKER" => $arParams["SALE_STIKER"],
												"STIKERS_PROP" => $arParams["STIKERS_PROP"],
												"SHOW_RATING" => ($arParams["SHOW_RATING"] ? $arParams["SHOW_RATING"] : "Y"),
												"DISPLAY_COMPARE" => ($arParams["DISPLAY_COMPARE"] ? $arParams["DISPLAY_COMPARE"] : "Y"),
												"ADD_PICT_PROP" => $arParams["ADD_PICT_PROP"],
												"OFFER_SHOW_PREVIEW_PICTURE_PROPS" => $arParams["OFFER_SHOW_PREVIEW_PICTURE_PROPS"],
												"SHOW_GALLERY" => $arParams["SHOW_GALLERY_GOODS"],
												"MAX_GALLERY_ITEMS" => $arParams["MAX_GALLERY_GOODS_ITEMS"],
												"OFFER_ADD_PICT_PROP" => $arParams["OFFER_ADD_PICT_PROP"],
												"ADD_DETAIL_TO_SLIDER" => $arParams["ADD_DETAIL_TO_SLIDER"],
												"SHOW_BIG_BLOCK" => 'N',
												'MAX_SCU_COUNT_VIEW' => $arTheme['MAX_SCU_COUNT_VIEW']['VALUE'],
												"SET_SKU_TITLE" => "Y",
												"IBINHERIT_TEMPLATES" => $arElement ? $arIBInheritTemplates : array(),
											), $component, array("HIDE_ICONS" => $isAjax)
										);?>
									</div>
								</div>
							</div>
							<?if($isAjax != "Y"):?>
								<?
								\Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("viewtype-brand-block", "...");
								//$frame->end();
								?>
							<?endif;?>
						</div>
					<?endif;?>

				<?$htmlCatalog=ob_get_clean();?>
				<?$APPLICATION->AddViewContent('goods_catalog_block', $htmlCatalog);//?>

				<?if($isAjax=="Y" || $isAjaxFilter == "Y"):?>
					<?=$htmlCatalog;?>
				<?endif;?>

				<?if($isAjax=="Y" || $isAjaxFilter == "Y"):?>
					<?die();?>
				<?endif;?>

		<?ob_start()//goods_catalog_block epilog ?>
				</div><?//close section-content-wrapper?>
			</div><?//close main-catalog-wrapper?>
		</div><div class="line-after"></div><?//close ordered-block goods_catalog?>
		<?$html=ob_get_clean();?>
		<?$APPLICATION->AddViewContent('goods_catalog_block_epilog', $html);//?>
<?endif;?>
<?// end catalog section block?>

<?$APPLICATION->ShowViewContent('bottom_links_block');?>


<?
$langing_seo_h1 = ($arElement["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] != "" ? $arElement["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] : $arElement["NAME"]);
$langing_seo_title = ($arElement["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"] != "" ? $arElement["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"] : $arElement["NAME"]);

$APPLICATION->SetTitle($langing_seo_h1);
$APPLICATION->SetPageProperty("title", $langing_seo_title);
?>

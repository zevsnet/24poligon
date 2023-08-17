<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>

<?
$arItemsFilter = CMax::GetIBlockAllElementsFilter($arParams);

if(!($bMap = in_array('MAP', $arParams['LIST_PROPERTY_CODE']))){
	$itemsCnt = CMaxCache::CIBlockElement_GetList(array('CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']))), $arItemsFilter, array());
}
else{
	// get items & coordinates
	$arItems = CMaxCache::CIBlockElement_GetList(array('CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'URL_TEMPLATE' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['detail'])), $arItemsFilter, false, false, array('ID', 'NAME', 'DETAIL_PAGE_URL', 'PREVIEW_TEXT', 'PROPERTY_ADDRESS', 'PROPERTY_PHONE', 'PROPERTY_EMAIL', 'PROPERTY_SCHEDULE', 'PROPERTY_METRO', 'PROPERTY_MAP'));
	$itemsCnt = count($arItems);
}

if($bMap && $itemsCnt){

	$dbRes = CIBlock::GetProperties($arParams['IBLOCK_ID']);
	while($arRes = $dbRes->Fetch()){
		$arProperties[$arRes['CODE']] = $arRes;
	}

		foreach($arItems as $arItem){
			// element coordinates
			$arItem['GPS_S'] = $arItem['GPS_N'] = 0;

			if(
				isset($arItem['PROPERTY_MAP_VALUE']) &&
				strlen($arItem['PROPERTY_MAP_VALUE']) &&
				$arStoreMap = explode(',', $arItem['PROPERTY_MAP_VALUE'])
			){
				$arItem['GPS_S'] = $arStoreMap[0];
				$arItem['GPS_N'] = $arStoreMap[1];
			}
			$html = '';
	
			$html .= '<div class="title"><a href="'.$arItem["DETAIL_PAGE_URL"].'" class="dark_link">'.$arItem['NAME'].($arItem['PROPERTY_ADDRESS_VALUE'] ? ', '.$arItem['PROPERTY_ADDRESS_VALUE'] : '').'</a></div>';
			
			if(strlen($arItem['PROPERTY_SCHEDULE_VALUE']['TEXT'] ?? '') || $arItem['PROPERTY_PHONE_VALUE'] || $arItem['PROPERTY_METRO_VALUE'] || $arItem['PROPERTY_EMAIL_VALUE']){
				$html .= '<div class="properties">';
					
					$html .= ($arItem['PROPERTY_METRO_VALUE'] ? '<div class="property schedule"><div class="title-prop font_upper">'.$arProperties['METRO']['NAME'].'</div><div class="value font_sm">'.(is_array($arItem['PROPERTY_METRO_VALUE']) ? implode('<br /> ', $arItem['PROPERTY_METRO_VALUE']) : $arItem['PROPERTY_METRO_VALUE']).'</div></div>' : '');
					$html .= (strlen($arItem['PROPERTY_SCHEDULE_VALUE']['TEXT'] ?? '') ? '<div class="property schedule"><div class="title-prop font_upper">'.$arProperties['SCHEDULE']['NAME'].'</div><div class="value font_sm">'.$arItem['~PROPERTY_SCHEDULE_VALUE']['TEXT'].'</div></div>' : '');
					
					if($arItem['PROPERTY_PHONE_VALUE']){
						$phone = '';
						if(is_array($arItem['PROPERTY_PHONE_VALUE'])){
							foreach($arItem['PROPERTY_PHONE_VALUE'] as $value){
								$phone .= '<div class="value"><a class="dark_link" rel= "nofollow" href="tel:'.str_replace(array(' ', ',', '-', '(', ')'), '', $value).'">'.$value.'</a></div>';
							}
						}
						else{
							$phone = '<div class="value font_sm"><a class="dark_link" rel= "nofollow" href="tel:'.str_replace(array(' ', ',', '-', '(', ')'), '', $arItem['PROPERTY_PHONE_VALUE']).'">'.$arItem['PROPERTY_PHONE_VALUE'].'</a></div>';
						
							
						}
						$html .= '<div class="property phone"><div class="title-prop font_upper">'.$arProperties['PHONE']['NAME'].'</div>'.$phone.'</div>';
					}
				
					$html .= (strlen($arItem['PROPERTY_EMAIL_VALUE']) ? '<div class="property email"><div class="title-prop font_upper">'.$arProperties['EMAIL']['NAME'].'</div><div class="value font_sm"><a class="dark_link" href="mailto:'.$arItem['PROPERTY_EMAIL_VALUE'].'">'.$arItem['PROPERTY_EMAIL_VALUE'].'</a></div></div>' : '');
				$html .= '</div>';
			}

			// add placemark to map
			if($arItem['GPS_S'] && $arItem['GPS_N']){
				$mapLAT += $arItem['GPS_S'];
				$mapLON += $arItem['GPS_N'];
				$arPlacemarks[] = array(
					"ID" => $arItem["ID"],
					"LAT" => $arItem['GPS_S'],
					"LON" => $arItem['GPS_N'],
					"TEXT" => $html,
					"HTML" => $html,
				);
			}
		}

		// map?>
		<div class="contacts_map">
			<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID('shops-map-block');?>
				<?if($arParams["MAP_TYPE"] != "0"):?>
					<?$APPLICATION->IncludeComponent(
						"bitrix:map.google.view",
						"map",
						array(
							"INIT_MAP_TYPE" => "ROADMAP",
							"MAP_DATA" => serialize(array("google_lat" => $mapLAT, "google_lon" => $mapLON, "google_scale" => 15, "PLACEMARKS" => $arPlacemarks)),
							"MAP_WIDTH" => "100%",
							"MAP_HEIGHT" => "550",
							"CONTROLS" => array(
							),
							"OPTIONS" => array(
								0 => "ENABLE_DBLCLICK_ZOOM",
								1 => "ENABLE_DRAGGING",
							),
							"MAP_ID" => "",
							"ZOOM_BLOCK" => array(
								"POSITION" => "right center",
							),
							"API_KEY" => $arParams["GOOGLE_API_KEY"],
							"COMPONENT_TEMPLATE" => "map",
							"COMPOSITE_FRAME_MODE" => "A",
							"COMPOSITE_FRAME_TYPE" => "AUTO"
						),
						false,
						array(
							"HIDE_ICONS" => "Y"
						)
					);?>
				<?else:?>
					<?
					$mapLAT = floatval($mapLAT / count($arItems));
					$mapLON = floatval($mapLON / count($arItems));
					?>
					<?$APPLICATION->IncludeComponent(
						"bitrix:map.yandex.view",
						"map",
						array(
							"INIT_MAP_TYPE" => "ROADMAP",
							"MAP_DATA" => serialize(array("yandex_lat" => $mapLAT, "yandex_lon" => $mapLON, "yandex_scale" => 4, "PLACEMARKS" => $arPlacemarks)),
							"MAP_WIDTH" => "100%",
							"MAP_HEIGHT" => "550",
							"CONTROLS" => array(
								0 => "ZOOM",
								1 => "SMALLZOOM",
								3 => "TYPECONTROL",
								4 => "SCALELINE",
							),
							"OPTIONS" => array(
								0 => "ENABLE_DBLCLICK_ZOOM",
								1 => "ENABLE_DRAGGING",
							),
							"MAP_ID" => "",
							"ZOOM_BLOCK" => array(
								"POSITION" => "right center",
							),
							"COMPONENT_TEMPLATE" => "map",
							"API_KEY" => $arParams["GOOGLE_API_KEY"],
							"COMPOSITE_FRAME_MODE" => "A",
							"COMPOSITE_FRAME_TYPE" => "AUTO"
						),
						false, array("HIDE_ICONS" =>"Y")
					);?>
				<?endif;?>
			<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID('shops-map-block', '');?>
		</div>
	<?}
?>

<?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"shops",
	Array(
		"IBLOCK_TYPE"	=>	$arParams["IBLOCK_TYPE"],
		"IBLOCK_ID"	=>	$arParams["IBLOCK_ID"],
		"NEWS_COUNT"	=>	$arParams["NEWS_COUNT"],
		"SORT_BY1"	=>	$arParams["SORT_BY1"],
		"SORT_ORDER1"	=>	$arParams["SORT_ORDER1"],
		"SORT_BY2"	=>	$arParams["SORT_BY2"],
		"SORT_ORDER2"	=>	$arParams["SORT_ORDER2"],
		"FIELD_CODE"	=>	$arParams["LIST_FIELD_CODE"],
		"PROPERTY_CODE"	=>	$arParams["LIST_PROPERTY_CODE"],
		"DETAIL_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
		"SECTION_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"IBLOCK_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
		"DISPLAY_PANEL"	=>	$arParams["DISPLAY_PANEL"],
		"SET_TITLE"	=>	"N",
		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
		"INCLUDE_IBLOCK_INTO_CHAIN"	=>	$arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
		"ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
		"ADD_ELEMENT_CHAIN" => $arParams["ADD_ELEMENT_CHAIN"],
		"CACHE_TYPE"	=>	'A', // for map!
		"CACHE_TIME"	=>	$arParams["CACHE_TIME"],
		"CACHE_FILTER"	=>	$arParams["CACHE_FILTER"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"DISPLAY_TOP_PAGER"	=>	$arParams["DISPLAY_TOP_PAGER"],
		"DISPLAY_BOTTOM_PAGER"	=>	$arParams["DISPLAY_BOTTOM_PAGER"],
		"PAGER_TITLE" => $arParams["PAGER_TITLE"],
		"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
		"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
		"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
		"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
		"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
		"PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],
		"PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
		"PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
		"DISPLAY_DATE"	=>	$arParams["DISPLAY_DATE"],
		"DISPLAY_NAME"	=>	"Y",
		"DISPLAY_PICTURE"	=>	$arParams["DISPLAY_PICTURE"],
		"DISPLAY_PREVIEW_TEXT"	=>	$arParams["DISPLAY_PREVIEW_TEXT"],
		"PREVIEW_TRUNCATE_LEN"	=>	$arParams["PREVIEW_TRUNCATE_LEN"],
		"ACTIVE_DATE_FORMAT"	=>	$arParams["LIST_ACTIVE_DATE_FORMAT"],
		"USE_PERMISSIONS"	=>	$arParams["USE_PERMISSIONS"],
		"GROUP_PERMISSIONS"	=>	$arParams["GROUP_PERMISSIONS"],
		"FILTER_NAME"	=>	$arParams["FILTER_NAME"],
		"HIDE_LINK_WHEN_NO_DETAIL"	=>	$arParams["HIDE_LINK_WHEN_NO_DETAIL"],
		"CHECK_DATES"	=>	$arParams["CHECK_DATES"],
	),
	$component
);?>
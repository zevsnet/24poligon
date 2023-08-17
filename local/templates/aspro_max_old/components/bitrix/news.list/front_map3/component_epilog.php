
<?
$arParams["KEY_MAP"] = $arParams["KEY_MAP"] ?? '';
$arShop=CMax::prepareShopListArray($templateData['MAP_ITEMS'], $arParams);
?>
<?if(abs($arShop["POINTS"]["LAT"]) > 0 && abs($arShop["POINTS"]["LON"]) > 0):?>
	<div class="contacts_map_list">
		<div class="contacts_map bordered">
			<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID('shops-map-block');?>
				<?if($arParams["MAP_TYPE"] != "0"):?>
					<?$APPLICATION->IncludeComponent(
						"bitrix:map.google.view",
						"",
						array(
							"INIT_MAP_TYPE" => "ROADMAP",
							"MAP_DATA" => serialize(array("google_lat" => $arShop["POINTS"]["LAT"], "google_lon" => $arShop["POINTS"]["LON"], "google_scale" => 16, "PLACEMARKS" => $arShop["PLACEMARKS"])),
							"MAP_WIDTH" => "100%",
							"MAP_HEIGHT" => "568",
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
							"COMPONENT_TEMPLATE" => "map",
							"API_KEY" => $arParams["KEY_MAP"],
							"COMPOSITE_FRAME_MODE" => "A",
							"COMPOSITE_FRAME_TYPE" => "AUTO"
						),
						false, array("HIDE_ICONS" =>"Y")
					);?>
				<?else:?>
					<?$APPLICATION->IncludeComponent(
						"bitrix:map.yandex.view",
						"map",
						array(
							"INIT_MAP_TYPE" => "ROADMAP",
							"MAP_DATA" => serialize(array("yandex_lat" => $arShop["POINTS"]["LAT"], "yandex_lon" => $arShop["POINTS"]["LON"], "yandex_scale" => 17, "PLACEMARKS" => $arShop["PLACEMARKS"])),
							"MAP_WIDTH" => "100%",
							"MAP_HEIGHT" => "568",
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
							"API_KEY" => $arParams["KEY_MAP"],
							"COMPOSITE_FRAME_MODE" => "A",
							"COMPOSITE_FRAME_TYPE" => "AUTO"
						),
						false, array("HIDE_ICONS" =>"Y")
					);?>
				<?endif;?>
			<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID('shops-map-block', '');?>
		</div>
	</div>
<?endif;?>
<?if($templateData['MAP_ITEMS']):?>
	</div></div>
<?endif;?>
<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$frame = $this->createFrame()->begin();?>
<?
if(strlen($arResult["ERROR_MESSAGE"]) > 0){
	ShowError($arResult["ERROR_MESSAGE"]);
}
?>
<?if(count($arResult["STORES"]) > 0):?>
	<?
	// get shops
	$arShops = array();
	CModule::IncludeModule('iblock');
	$dbRes = CIBlock::GetList(array(), array('CODE' => 'aspro_max_shops', 'ACTIVE' => 'Y', 'SITE_ID' => SITE_ID));
	if ($arShospIblock = $dbRes->Fetch()){
		$dbRes = CIBlockElement::GetList(array(), array('ACTIVE' => 'Y', 'IBLOCK_ID' => $arShospIblock['ID']), false, false, array('ID', 'DETAIL_PAGE_URL', 'PROPERTY_STORE_ID'));
		while($arShop = $dbRes->GetNext()){
			$arShops[$arShop['PROPERTY_STORE_ID_VALUE']] = $arShop;
		}
	}
	$bCombineMode = ($arParams["STORE_AMOUNT_VIEW"] == "COMBINE_AMOUNT");
	$bMapMode = ($arParams["STORE_AMOUNT_VIEW"] == "MAP_AMOUNT");
	?>
	<div class="stores_block_wrap <?if($bCombineMode):?>combine<?endif;?>">

		<?if($bCombineMode):?>
			<div class="stores-title flexbox flexbox--row">
				<div class="stores-title__list stores-title--list stores-title--active font_xs">
					<?=\CMax::showIconSvg("stores ncolor", SITE_TEMPLATE_PATH."/images/svg/catalog/presence_list.svg");?>
					<?=GetMessage("STORES_LIST");?>
				</div>
				<div class="stores-title__list stores-title--map font_xs">
					<?=\CMax::showIconSvg("stores ncolor", SITE_TEMPLATE_PATH."/images/svg/catalog/presence_map.svg");?>
					<?=GetMessage("STORES_MAP");?>
				</div>
			</div>

			<div class="stores-amount-list stores-amount-list--active">
		<?endif;?>

		<?$empty_count=0;
		$arTmpItems = [];
		$count_stores=count($arResult["STORES"]);?>
		
		<?foreach($arResult["STORES"] as $pid => $arProperty):
			$amount = (isset($arProperty['REAL_AMOUNT']) ? $arProperty['REAL_AMOUNT'] : $arProperty['AMOUNT']);
			if($arParams['SHOW_EMPTY_STORE'] == 'N' && $amount <= 0) {
				$empty_count++;
				continue;
			}?>

			<?if (isset($arProperty["TITLE"])):?>
					<?
					if($arParams['FIELDS'] && (in_array('TITLE', $arParams['FIELDS']) || in_array('ADDRESS', $arParams['FIELDS'])) ) {
						$setTitle = in_array('TITLE', $arParams['FIELDS']) && strlen($arProperty["TITLE"]);
						$setAddress = in_array('ADDRESS', $arParams['FIELDS']) && strlen($arProperty["ADDRESS"]);
						$storeName = ($setTitle ? $arProperty["TITLE"] : '');
						$storeName .= $setTitle && $setAddress ? ', ' : '';
						$storeName .= ($setAddress ? $arProperty["ADDRESS"] : '');
					} else {
						$storeName = $arProperty["TITLE"].(strlen($arProperty["ADDRESS"]) && strlen($arProperty["TITLE"]) ? ', ' : '').$arProperty["ADDRESS"];
					}
					?>
			<?endif;?>

			<?
			$totalCount = CMax::CheckTypeCount($arProperty["NUM_AMOUNT"]);
			$arQuantityData = CMax::GetQuantityArray($totalCount);
			?>

			<?if(!$bMapMode):?>
				<div class="stores_block bordered rounded3 <?=(isset($arProperty["IMAGE_ID"]) && !empty($arProperty["IMAGE_ID"]) ? 'w_image' : 'wo_image')?>" <? echo ($arParams['SHOW_EMPTY_STORE'] == 'N' && $amount <= 0 ? 'style="display: none"' : ''); ?>>
					<div class="stores_text_wrapp <?=(isset($arProperty["IMAGE_ID"]) && !empty($arProperty["IMAGE_ID"]) ? 'image_block' : '')?>">
						<?					
						if (isset($arProperty["IMAGE_ID"]) && !empty($arProperty["IMAGE_ID"])):?>
							<div class="imgs"><?=GetMessage('S_IMAGE')?> <?=CFile::ShowImage($arProperty["IMAGE_ID"], 100, 100, "border=0", "", false);?></div>
						<?endif;?>
						<div class="main_info ">
							<?if (isset($arProperty["TITLE"])):?>
								<span>
									<a class="title_stores font_sm dark_link option-font-bold" href="<?=$arProperty["URL"]?>" data-storehref="<?=$arProperty["URL"]?>" data-iblockhref="<?=$arShops[$arProperty['ID']]['DETAIL_PAGE_URL']?>"> <?=$storeName?></a>
								</span>
							<?endif;?>
							<?if($arParams['FIELDS'] && in_array('PHONE', $arParams['FIELDS']) && isset($arProperty["PHONE"]) && $arProperty["PHONE"]):?><div class="store_phone p10 muted777 font_xs"><?=GetMessage('S_PHONE')?> <?=$arProperty["PHONE"]?></div><?endif;?>
							<?if(isset($arProperty["SCHEDULE"]) && $arProperty["SCHEDULE"]):?><div class="schedule p10 muted777 font_xs"><?=GetMessage('S_SCHEDULE')?>&nbsp;<?=str_replace("&lt;br/&gt;", "<br/>", $arProperty["SCHEDULE"]);?></div><?endif;?>
							<?if(isset($arProperty["EMAIL"]) && $arProperty["EMAIL"]):?><div class="email p10 muted777 font_xs"><?=GetMessage('S_EMAIL')?>&nbsp;<a href="<?='mailto:'.$arProperty["EMAIL"]?>"><?=$arProperty["EMAIL"];?></a></div><?endif;?>
							<?if (!empty($arProperty['USER_FIELDS']) && is_array($arProperty['USER_FIELDS'])){
								foreach ($arProperty['USER_FIELDS'] as $userField){
									if (isset($userField['CONTENT'])){
										?><span class="muted777 font_xs"><?=$userField['TITLE']?>: <?=$userField['CONTENT']?></span><br /><?
									}
								}
							}?>
							<?if ($arParams['SHOW_GENERAL_STORE_INFORMATION'] == "Y"){?>
								<?=GetMessage('BALANCE')?>
							<?}?>
						</div>
					</div>					
					<?if(strlen($arQuantityData["TEXT"])):?>
						<?=$arQuantityData["HTML"]?>
					<?endif;?>
				</div>
			<?endif;?>

			<?if(strlen($arQuantityData["TEXT"])):?>
				<?$arProperty["QUANTITY"] = $arQuantityData["HTML"]?>
			<?endif;?>
			<?
			if($arProperty["GPS_N"] && $arProperty["GPS_N"])
			{
				if($arProperty["METRO"])
					$arProperty["METRO_PLACEMARK_HTML"] = implode('/', $arProperty["METRO"]);
				$arProperty["ADDRESS"] = $storeName;//$arProperty["TITLE"].(strlen($arProperty["ADDRESS"]) && strlen($arProperty["TITLE"]) ? ', ' : '').$arProperty["ADDRESS"];

				if(CMax::GetFrontParametrValue("STORES_SOURCE", $_POST["SITE_ID"]) == 'IBLOCK')
					$arProperty["URL"] = $arShops[$arProperty['ID']]['DETAIL_PAGE_URL'];
				$arTmpItems[$pid] = $arProperty;
			}?>
		<?endforeach;?>
		
		<?if(!$bMapMode):?>
			<?if($empty_count==$count_stores){?>
				<div class="stores_block">
					<div class="stores_text_wrapp"><?=GetMessage('NO_STORES')?></div>
				</div>
			<?}?>
		<?endif;?>

		<?if($bCombineMode || $bMapMode):?>
			<?if($bCombineMode):?>
				</div>
			<?endif;?>
			<div class="stores-amount-list<?=($bMapMode ? '  stores-amount-list--active' : '');?>">
				<?if($arTmpItems):?>
					<?$nCountItems = count($arTmpItems);?>
					<?$arShop=CMax::prepareShopListArray($arTmpItems, $arParams);?>
					<div class="wrapper_block with_title title_left">
						<div class="block_container bordered <?=($nCountItems == 1 ? 'one' : '');?>">
							<div class="items" <?=($nCountItems == 1 ? "style='display:none;'" : "")?>>
								<div class="items-inner">
									<?foreach($arTmpItems as $arItem):?>
										<?$pos = ($arItem['GPS_N'] && $arItem['GPS_S'] ? $arItem['GPS_N'].','.$arItem['GPS_S'] : '')?>
										<div class="item" data-coordinates="<?=$pos;?>" data-id="<?=$arItem['ID']?>">
											<?//print_r($arItem);?>
											<div class="title option-font-bold font_sm"><?=$arItem['ADDRESS']?></div>
											<?if($arItem['QUANTITY']):?>
												<?=$arItem['QUANTITY']?>
											<?endif;?>
										</div>
									<?endforeach;?>
								</div>
							</div>
							<div class="detail_items" <?=($nCountItems == 1 ? "style='display:block;'" : "")?>>
								<?foreach($arTmpItems as $arItem):?>
									<?$pos = ($arItem['GPS_N'] && $arItem['GPS_S'] ? $arItem['GPS_N'].','.$arItem['GPS_S'] : '')?>
									<div class="item" <?=($nCountItems == 1 ? "style='display:block;'" : "")?> data-coordinates="<?=$pos;?>" data-id="<?=$arItem['ID']?>">
										<?=CMax::prepareItemMapHtml($arItem, "Y");?>
										<div class="top-close muted svg">
											<svg class="svg-close" width="14" height="14" viewBox="0 0 14 14"><path data-name="Rounded Rectangle 568 copy 16" class="cls-1" d="M1009.4,953l5.32,5.315a0.987,0.987,0,0,1,0,1.4,1,1,0,0,1-1.41,0L1008,954.4l-5.32,5.315a0.991,0.991,0,0,1-1.4-1.4L1006.6,953l-5.32-5.315a0.991,0.991,0,0,1,1.4-1.4l5.32,5.315,5.31-5.315a1,1,0,0,1,1.41,0,0.987,0.987,0,0,1,0,1.4Z" transform="translate(-1001 -946)"></path></svg>
										</div>
										<?/*<div class="buttons_block">
											<span class="btn btn-transparent-border-color btn-xs animate-load" data-event="jqm" data-param-form_id="ASK" data-name="question"><?=GetMessage('SEND_MESSAGE_BUTTON');?>
											</span>
										</div>*/?>
									</div>
								<?endforeach;?>
							</div>
						</div>
					</div>
					<div class="contacts_map_list">
						<?$APPLICATION->IncludeComponent(
							"bitrix:map.yandex.view",
							"map",
							array(
								"INIT_MAP_TYPE" => "ROADMAP",
								"MAP_DATA" => serialize(array("yandex_lat" => $arShop["POINTS"]["LAT"], "yandex_lon" => $arShop["POINTS"]["LON"], "yandex_scale" => 17, "PLACEMARKS" => $arShop["PLACEMARKS"])),
								"MAP_WIDTH" => "100%",
								"MAP_HEIGHT" => "500",
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
					</div>
				<?else:?>
					<?=GetMessage("NO_STORES_COORDINATES");?>
				<?endif;?>
			</div>
		<?endif;?>
	</div>
<?endif;?>
<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?
use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

global $arRegion;

$arItemFilter = CMax::GetIBlockAllElementsFilter($arParams);
$arItemSelect = array('ID', 'NAME', 'IBLOCK_ID', 'DETAIL_PAGE_URL', 'IBLOCK_SECTION_ID', 'PROPERTY_MAP', 'PROPERTY_PHONE', 'PROPERTY_SCHEDULE', 'PROPERTY_METRO', 'PROPERTY_EMAIL', 'PROPERTY_ADDRESS');
$arItems = CMaxCache::CIblockElement_GetList(array("CACHE" => array("TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), $arItemFilter, false, false, $arItemSelect);

$arAllSections = array();
if($arItems)
	$arAllSections = CMax::GetSections($arItems, $arParams);
?>
<?if($arParams['SHOW_TOP_MAP'] != 'Y'):?>
	<div class="contacts-page-top">
		<div class="contacts maxwidth-theme" itemscope itemtype="http://schema.org/Organization">
			<div class="row">
				<?$bHasSections = (isset($arAllSections['ALL_SECTIONS']) && $arAllSections['ALL_SECTIONS']);?>
				<?$bHasChildSections = (isset($arAllSections['CHILD_SECTIONS']) && $arAllSections['CHILD_SECTIONS']);?>
				<?if($bHasSections):?>
					<div class="col-lg-6 col-md-12 region-row">
						<div class="row">
							<div class="col-lg-6 col-md-4 col-sm-6">
								<select class="<?=($bHasChildSections ? 'region' : 'city');?>">
									<option value="0" selected><?=Loc::getMessage('CHOISE_ITEM', array('#ITEM#' => ($bHasChildSections ? Loc::getMessage('REGION') : Loc::getMessage('CITY'))))?></option>
									<?foreach($arAllSections['ALL_SECTIONS'] as $arSection):?>
										<option value="<?=$arSection['SECTION']['ID'];?>"><?=$arSection['SECTION']['NAME'];?></option>
									<?endforeach;?>
								</select>
							</div>
							<?if($bHasChildSections):?>
								<div class="col-lg-6 col-md-4 col-sm-6">
									<select class="city">
										<option value="0" selected><?=Loc::getMessage('CHOISE_ITEM', array('#ITEM#' => Loc::getMessage('CITY')))?></option>
										<?foreach($arAllSections['CHILD_SECTIONS'] as $arSection):?>
											<option style="display:none;" disabled="disabled" value="<?=$arSection['ID'];?>" data-parent_section="<?=$arSection['IBLOCK_SECTION_ID'];?>"><?=$arSection['NAME'];?></option>
										<?endforeach;?>
									</select>
								</div>
							<?endif;?>
						</div>
					</div>
				<?endif;?>
				<div class="col-lg-<?=($bHasSections ? 6 : 12);?> col-md-12 info-row">
					<div class="row">
						<div class="col-md-4 col-sm-6 print-6">
							<?CMax::showContactPhones('', true, '', 'Phone_black2.svg', 'grey');?>
						</div>
						<div class="col-md-4 col-sm-6 print-6">
							<?CMax::showContactEmail('E-mail', true, '', 'Email.svg', 'grey');?>
						</div>
						<div class="col-md-4 col-sm-6 ask_button text-right">
							<span>
								<span class="btn  btn-transparent-border-color white  animate-load" data-event="jqm" data-param-form_id="ASK" data-name="contacts"><?=Loc::getMessage('S_ASK_QUESTION');?></span>
							</span>
						</div>
					</div>

					<?//hidden text for validate microdata?>
					<div class="hidden">
						<?if($arRegion):?>
							<?if($arRegion["PROPERTY_ADDRESS_VALUE"]["TEXT"]):?>
								<span itemprop="address"><?=$arRegion["PROPERTY_ADDRESS_VALUE"]["TEXT"];?></span>
							<?endif;?>
						<?else:?>
							<span itemprop="address"><?$APPLICATION->IncludeFile(SITE_DIR."include/contacts-site-address.php", Array(), Array("MODE" => "html", "NAME" => "Address"));?></span>
						<?endif;?>
						<?global $arSite;?>
						<span itemprop="name"><?=$arSite["NAME"];?></span>
					</div>
				</div>
			</div>
		</div>
	</div>
<?endif;?>
<div class="ajax_items">
	<?if((isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") || (strtolower($_REQUEST['ajax']) == 'y')){
		$APPLICATION->RestartBuffer();?>
	<?}?>
	<?if($arItems):?>

		<?$dbRes = CIBlock::GetProperties($arParams['IBLOCK_ID']);
		while($arRes = $dbRes->Fetch()){
			$arProperties[$arRes['CODE']] = $arRes;
		}?>

		<?$bPostSection = (isset($_POST['ID']) && $_POST['ID']);?>
		<?
		$bUseMap = CMax::GetFrontParametrValue('CONTACTS_USE_MAP', SITE_ID) != 'N';
		$mapLAT = $mapLON = $iCountShops =0;
		$arPlacemarks = array();
		if($bPostSection)
		{
			$arItems = CMaxCache::CIblockElement_GetList(array("CACHE" => array("TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array_merge($arItemFilter, array('SECTION_ID' => $_POST['ID'])), false, false, $arItemSelect);
			$GLOBALS[$arParams['FILTER_NAME']]['SECTION_ID'] = $_POST['ID'];
		}

		foreach($arItems as $arItem)
		{
			if($arItem['PROPERTY_MAP_VALUE']){
				$arCoords = explode(',', $arItem['PROPERTY_MAP_VALUE']);
				$mapLAT += $arCoords[0];
				$mapLON += $arCoords[1];
				$str_phones = '';
				if($arItem['PHONE'])
				{
					foreach($arShop['PHONE'] as $phone)
					{
						$str_phones .= '<div class="phone"><a rel="nofollow" href="tel:'.str_replace(array(' ', ',', '-', '(', ')'), '', $phone).'">'.$phone.'</a></div>';
					}
				}
				
				$html = '<div class="map_info_store">';
	
				$html .= '<div class="title font_mlg"><a href="'.$arItem["DETAIL_PAGE_URL"].'" class="dark_link">'.$arItem['NAME'].($arItem['PROPERTY_ADDRESS_VALUE'] ? ', '.$arItem['PROPERTY_ADDRESS_VALUE'] : '').'</a></div>';
				
				if(strlen($arItem['PROPERTY_SCHEDULE_VALUE']['TEXT']) || $arItem['PROPERTY_PHONE_VALUE'] || $arItem['PROPERTY_METRO_VALUE'] || $arItem['PROPERTY_EMAIL_VALUE']){
					$html .= '<div class="properties">';
						
						$html .= (strlen($arItem['PROPERTY_METRO_VALUE']) ? '<div class="property schedule"><div class="title-prop font_upper">'.$arProperties['METRO']['NAME'].'</div><div class="value font_sm">'.$arItem['PROPERTY_METRO_VALUE'].'</div></div>' : '');
						$html .= (strlen($arItem['PROPERTY_SCHEDULE_VALUE']['TEXT']) ? '<div class="property schedule"><div class="title-prop font_upper">'.$arProperties['SCHEDULE']['NAME'].'</div><div class="value font_sm">'.$arItem['~PROPERTY_SCHEDULE_VALUE']['TEXT'].'</div></div>' : '');
						
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
					$html .= '</div></div>';
				}

				$arPlacemarks[] = array(
					"ID" => $arItem["ID"],
					"LAT" => $arCoords[0],
					"LON" => $arCoords[1],
					// "TEXT" => $arItem["NAME"],
					"TEXT" => $html
				);
				++$iCountShops;
			}
		}
		if($iCountShops && $bUseMap)
		{
			$mapLAT = floatval($mapLAT / $iCountShops);
			$mapLON = floatval($mapLON / $iCountShops);?>
			<?if($arParams['SHOW_TOP_MAP'] == 'Y'):?>
				<?$this->SetViewTarget('yandex_map');?>
			<?endif;?>
			<div class="contacts-page-map">
				<?$APPLICATION->IncludeComponent(
					"bitrix:map.yandex.view",
					"map",
					array(
						"INIT_MAP_TYPE" => "MAP",
						"MAP_DATA" => serialize(array("yandex_lat" => $mapLAT, "yandex_lon" => $mapLON, "yandex_scale" => 19, "PLACEMARKS" => $arPlacemarks)),
						"MAP_WIDTH" => "100%",
						"MAP_HEIGHT" => "550",
						"CONTROLS" => array(
							0 => "ZOOM",
							1 => "TYPECONTROL",
							2 => "SCALELINE",
						),
						"OPTIONS" => array(
							0 => "ENABLE_DBLCLICK_ZOOM",
							1 => "ENABLE_DRAGGING",
						),
						"MAP_ID" => "MAP_v33",
						"COMPONENT_TEMPLATE" => "map"
					),
					false
				);?>
			</div>
			<?if($arParams['SHOW_TOP_MAP'] == 'Y'):?>
				<?$this->EndViewTarget();?>
			<?endif;?>
		<?}?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:news.list",
			"contacts",
			Array(
				"COUNT_IN_LINE" => $arParams["COUNT_IN_LINE"],
				"SHOW_SECTION_PREVIEW_DESCRIPTION" => $arParams["SHOW_SECTION_PREVIEW_DESCRIPTION"],
				"VIEW_TYPE" => $arParams["VIEW_TYPE"],
				"SHOW_TABS" => $arParams["SHOW_TABS"],
				"IMAGE_POSITION" => $arParams["IMAGE_POSITION"],
				"IBLOCK_TYPE"	=>	$arParams["IBLOCK_TYPE"],
				"IBLOCK_ID"	=>	$arParams["IBLOCK_ID"],
				"NEWS_COUNT"	=>	$arParams["NEWS_COUNT"],
				"SORT_BY1"	=>	$arParams["SORT_BY1"],
				"SORT_ORDER1"	=>	$arParams["SORT_ORDER1"],
				"SORT_BY2"	=>	$arParams["SORT_BY2"],
				"SORT_ORDER2"	=>	$arParams["SORT_ORDER2"],
				"FIELD_CODE"	=>	$arParams["LIST_FIELD_CODE"],
				"PROPERTY_CODE"	=>	$arParams["LIST_PROPERTY_CODE"],
				"DISPLAY_PANEL"	=>	$arParams["DISPLAY_PANEL"],
				"SET_TITLE"	=>	$arParams["SET_TITLE"],
				"SET_STATUS_404" => $arParams["SET_STATUS_404"],
				"INCLUDE_IBLOCK_INTO_CHAIN"	=>	$arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
				"ADD_SECTIONS_CHAIN"	=>	$arParams["ADD_SECTIONS_CHAIN"],
				"CACHE_TYPE"	=>	$arParams["CACHE_TYPE"],
				"CACHE_TIME"	=>	$arParams["CACHE_TIME"],
				"CACHE_FILTER"	=>	"Y",
				"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
				"DISPLAY_TOP_PAGER"	=>	$arParams["DISPLAY_TOP_PAGER"],
				"DISPLAY_BOTTOM_PAGER"	=>	$arParams["DISPLAY_BOTTOM_PAGER"],
				"PAGER_TITLE"	=>	$arParams["PAGER_TITLE"],
				"PAGER_TEMPLATE"	=>	$arParams["PAGER_TEMPLATE"],
				"PAGER_SHOW_ALWAYS"	=>	$arParams["PAGER_SHOW_ALWAYS"],
				"PAGER_DESC_NUMBERING"	=>	$arParams["PAGER_DESC_NUMBERING"],
				"PAGER_DESC_NUMBERING_CACHE_TIME"	=>	$arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
				"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
				"DISPLAY_DATE"	=>	$arParams["DISPLAY_DATE"],
				"DISPLAY_NAME"	=>	$arParams["DISPLAY_NAME"],
				"DISPLAY_PICTURE"	=>	$arParams["DISPLAY_PICTURE"],
				"DISPLAY_PREVIEW_TEXT"	=>	$arParams["DISPLAY_PREVIEW_TEXT"],
				"PREVIEW_TRUNCATE_LEN"	=>	$arParams["PREVIEW_TRUNCATE_LEN"],
				"ACTIVE_DATE_FORMAT"	=>	$arParams["LIST_ACTIVE_DATE_FORMAT"],
				"USE_PERMISSIONS"	=>	$arParams["USE_PERMISSIONS"],
				"GROUP_PERMISSIONS"	=>	$arParams["GROUP_PERMISSIONS"],
				"FILTER_NAME"	=>	$arParams["FILTER_NAME"],
				"HIDE_LINK_WHEN_NO_DETAIL"	=>	$arParams["HIDE_LINK_WHEN_NO_DETAIL"],
				"CHECK_DATES"	=>	$arParams["CHECK_DATES"],
				"PARENT_SECTION"	=>	$arResult["VARIABLES"]["SECTION_ID"],
				"PARENT_SECTION_CODE"	=>	$arResult["VARIABLES"]["SECTION_CODE"],
				"DETAIL_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
				"SECTION_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
				"IBLOCK_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
				"INCLUDE_SUBSECTIONS" => "Y",
				"SHOW_DETAIL_LINK" => $arParams["SHOW_DETAIL_LINK"],
			),
			$component
		);?>
		<?CMax::checkRestartBuffer();?>
	<?endif;?>
</div>
<?
use \Bitrix\Currency,
	\Bitrix\Catalog;

CMax::getFieldImageData($arResult, array('DETAIL_PICTURE'));

/*landings*/
if(isset($arParams["IS_LANDING"]) && $arParams["IS_LANDING"]== 'Y'){
	$arResult['IS_LANDING'] = 'Y';
}

/*set prop for galery*/
$smallGaleryCode = (isset($arParams["TOP_GALLERY_PROPERTY_CODE"]) && $arParams["TOP_GALLERY_PROPERTY_CODE"]!= '-' ? $arParams["TOP_GALLERY_PROPERTY_CODE"] : 'PHOTOS');
$bigGaleryCode = (isset($arParams["MAIN_GALLERY_PROPERTY_CODE"]) && $arParams["MAIN_GALLERY_PROPERTY_CODE"]!= '-' ? $arParams["MAIN_GALLERY_PROPERTY_CODE"] : 'PHOTOS');
$bTopImage = ($arResult['FIELDS']['DETAIL_PICTURE'] && $arResult['PROPERTIES']['PHOTOPOS']['VALUE_XML_ID'] == 'TOP');

//echo var_dump($arParams["TOP_GALLERY_PROPERTY_CODE"]);
$arResult['GALLERY'] = array();

if(is_array($arResult['FIELDS']['DETAIL_PICTURE']) && $arParams["SHOW_TOP_PROJECT_BLOCK"] == "Y" && !$bTopImage){
	$arResult['GALLERY'][] = array(
		'DETAIL' => $arResult['DETAIL_PICTURE'],
		'PREVIEW' => CFile::ResizeImageGet($arResult['DETAIL_PICTURE'] , array('width' => 1000, 'height' => 1000), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true),
		'TITLE' => (strlen($arResult['DETAIL_PICTURE']['DESCRIPTION']) ? $arResult['DETAIL_PICTURE']['DESCRIPTION'] : (strlen($arResult['DETAIL_PICTURE']['TITLE']) ? $arResult['DETAIL_PICTURE']['TITLE'] : $arResult['NAME'])),
		'ALT' => (strlen($arResult['DETAIL_PICTURE']['DESCRIPTION']) ? $arResult['DETAIL_PICTURE']['DESCRIPTION'] : (strlen($arResult['DETAIL_PICTURE']['ALT']) ? $arResult['DETAIL_PICTURE']['ALT'] : $arResult['NAME'])),
	);
}

if($arResult['DISPLAY_PROPERTIES']){
	//$arResult['GALLERY'] = array();
	$arResult['VIDEO'] = array();

	if($arResult['DISPLAY_PROPERTIES'][$smallGaleryCode]['VALUE'] && is_array($arResult['DISPLAY_PROPERTIES'][$smallGaleryCode]['VALUE'])){
		foreach($arResult['DISPLAY_PROPERTIES'][$smallGaleryCode]['VALUE'] as $img){
			$arResult['GALLERY'][] = array(
				'DETAIL' => ($arPhoto = CFile::GetFileArray($img)),
				'PREVIEW' => CFile::ResizeImageGet($img, array('width' => 1500, 'height' => 1500), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true),
				'THUMB' => CFile::ResizeImageGet($img, array('width' => 60, 'height' => 60), BX_RESIZE_IMAGE_EXACT, true),
				'TITLE' => (strlen($arPhoto['DESCRIPTION']) ? $arPhoto['DESCRIPTION'] : (strlen($arResult['DETAIL_PICTURE']['TITLE']) ? $arResult['DETAIL_PICTURE']['TITLE']  :(strlen($arPhoto['TITLE']) ? $arPhoto['TITLE'] : $arResult['NAME']))),
				'ALT' => (strlen($arPhoto['DESCRIPTION']) ? $arPhoto['DESCRIPTION'] : (strlen($arResult['DETAIL_PICTURE']['ALT']) ? $arResult['DETAIL_PICTURE']['ALT']  : (strlen($arPhoto['ALT']) ? $arPhoto['ALT'] : $arResult['NAME']))),
			);
		}
	}
	
	if($arResult['DISPLAY_PROPERTIES'][$bigGaleryCode]['VALUE'] && is_array($arResult['DISPLAY_PROPERTIES'][$bigGaleryCode]['VALUE'])){
		foreach($arResult['DISPLAY_PROPERTIES'][$bigGaleryCode]['VALUE'] as $img){
			$arResult['GALLERY_BIG'][] = array(
				'DETAIL' => ($arPhoto = CFile::GetFileArray($img)),
				'PREVIEW' => CFile::ResizeImageGet($img, array('width' => 1500, 'height' => 1500), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true),
				'THUMB' => CFile::ResizeImageGet($img , array('width' => 60, 'height' => 60), BX_RESIZE_IMAGE_EXACT, true),
				'TITLE' => (strlen($arPhoto['DESCRIPTION']) ? $arPhoto['DESCRIPTION'] : (strlen($arResult['DETAIL_PICTURE']['TITLE']) ? $arResult['DETAIL_PICTURE']['TITLE']  :(strlen($arPhoto['TITLE']) ? $arPhoto['TITLE'] : $arResult['NAME']))),
				'ALT' => (strlen($arPhoto['DESCRIPTION']) ? $arPhoto['DESCRIPTION'] : (strlen($arResult['DETAIL_PICTURE']['ALT']) ? $arResult['DETAIL_PICTURE']['ALT']  : (strlen($arPhoto['ALT']) ? $arPhoto['ALT'] : $arResult['NAME']))),
			);
		}
	}

	foreach($arResult['DISPLAY_PROPERTIES'] as $i => $arProp){
		if($arProp['VALUE'] || strlen($arProp['VALUE'])){
			if($arProp['USER_TYPE'] == 'video'){
				if (count($arProp['PROPERTY_VALUE_ID']) > 1) {
					foreach($arProp['VALUE'] as $val){
						if($val['path']){
							$arResult['VIDEO'][] = $val;
						}
					}
				}
				elseif($arProp['VALUE']['path']){
					$arResult['VIDEO'][] = $arProp['VALUE'];
				}
				unset($arResult['DISPLAY_PROPERTIES'][$i]);
			}
		}
	}
	
	if($arParams["STAFF_MODE"]){
		foreach($arResult['DISPLAY_PROPERTIES'] as $key2 => $arProp)
		{
			/*if(($key2 == 'EMAIL' || $key2 == 'PHONE') && $arProp['VALUE']){
				$arResult['MIDDLE_PROPS'][$key2] = $arProp;
				unset($arResult['DISPLAY_PROPERTIES'][$key2]);
			}*/
			if(strpos($key2, 'SOCIAL') !== false && $arProp['VALUE']){
				switch($key2){
					case('SOCIAL_VK'):
						$arProp['FILE'] = SITE_TEMPLATE_PATH.'/images/svg/social/social_vk.svg';
						break;
					case('SOCIAL_ODN'):
						$arProp['FILE'] = SITE_TEMPLATE_PATH.'/images/svg/social/social_odnoklassniki.svg';
						break;
					case('SOCIAL_FB'):
						$arProp['FILE'] = SITE_TEMPLATE_PATH.'/images/svg/social/social_facebook.svg';
						break;
					case('SOCIAL_MAIL'):
						$arProp['FILE'] = SITE_TEMPLATE_PATH.'/images/svg/social/social_mail.svg';
						break;
					case('SOCIAL_TW'):
						$arProp['FILE'] = SITE_TEMPLATE_PATH.'/images/svg/social/social_twitter.svg';
						break;
					case('SOCIAL_INST'):
						$arProp['FILE'] = SITE_TEMPLATE_PATH.'/images/svg/social/social_instagram.svg';
						break;
					case('SOCIAL_GOOGLE'):
						$arProp['FILE'] = SITE_TEMPLATE_PATH.'/images/svg/social/social_google.svg';
						break;
					case('SOCIAL_SKYPE'):
						$arProp['FILE'] = SITE_TEMPLATE_PATH.'/images/svg/social/social_skype.svg';
						break;
					case('SOCIAL_BITRIX'):
						$arProp['FILE'] = SITE_TEMPLATE_PATH.'/images/svg/social/social_bitrix24.svg';
						break;
				}
				$arResult['SOCIAL_PROPS'][] = $arProp;
				unset($arResult['DISPLAY_PROPERTIES'][$key2]);
			}
		}
	}
	
}

/*get price and avaible */
$arResult["SHOW_BUY_BUTTON"] = false;
if($arResult['PROPERTIES']['ALLOW_BUY']['VALUE'] === 'Y'){
	$product_data = CCatalogProduct::GetByID($arResult['ID']);
	if($product_data['AVAILABLE'] === 'Y'){
		
		$arResult["PRICES"] = CIBlockPriceTools::GetCatalogPrices($arParams["IBLOCK_ID"], $arParams["PRICE_CODE"]);
		$arResult['PRICES_ALLOW'] = \CIBlockPriceTools::GetAllowCatalogPrices($arResult["PRICES"]);
		
		$select = array(
			'ID', 'PRODUCT_ID', 'CATALOG_GROUP_ID', 'PRICE', 'CURRENCY',
			'QUANTITY_FROM', 'QUANTITY_TO'
		);

		if($arResult['PRICES_ALLOW']){
			$iterator = \Bitrix\Catalog\PriceTable::getList(array(
				'select' => $select,
				'filter' => array('@PRODUCT_ID' => $arResult['ID'], '@CATALOG_GROUP_ID' => $arResult['PRICES_ALLOW']),
				'order' => array('PRODUCT_ID' => 'ASC', 'CATALOG_GROUP_ID' => 'ASC')
			));
	
			$arPrices = array();
	
			while ($row = $iterator->fetch())
			{
				if($row['QUANTITY_FROM'] && $row['QUANTITY_FROM'] !== '1')
					continue;
				
				$arPrices[$row['CATALOG_GROUP_ID']] = $row;
				
			}
	
			$vatData = array();
			$vatIterator = Catalog\VatTable::getList([
				'select' => ['ID', 'RATE'],
				'order' => ['ID' => 'ASC']
			]);
			while ($rowVat = $vatIterator->fetch())
				$vatData[(int)$rowVat['ID']] = (float)$rowVat['RATE'];
	
			$vatRate = (float)$vatData[$product_data['VAT_ID']];
	
	
			$arMinimalPrice = \Aspro\Functions\CAsproMaxItem::getServicePrices($arParams, $arPrices, $product_data, $vatRate);
			if(is_array($arMinimalPrice) && isset($arMinimalPrice['PRICE']) && $arMinimalPrice['PRICE']>0 ){
				$arResult["BUTTON_RESULT_PRICE"] = $arMinimalPrice;
				$arResult["SHOW_BUY_BUTTON"] = true;
			}
		}		
	}	
}

/* */

$arResult['DISPLAY_PROPERTIES_FORMATTED'] = CMax::PrepareItemProps($arResult['DISPLAY_PROPERTIES']);

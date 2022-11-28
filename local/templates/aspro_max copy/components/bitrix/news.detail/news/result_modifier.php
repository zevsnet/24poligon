<?
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
				'PREVIEW' => CFile::ResizeImageGet($img, array('width' => 1500, 'height' => 1500), BX_RESIZE_PROPORTIONAL_ALT, true),
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





/*if(isset($arResult['PROPERTIES']['BNR_TOP']) && $arResult['PROPERTIES']['BNR_TOP']['VALUE_XML_ID'] == 'YES')
{
	$cp = $this->__component;
	if(is_object($cp))
	{
	    $onHead = isset($arResult['PROPERTIES']['BNR_ON_HEADER']) && $arResult['PROPERTIES']['BNR_ON_HEADER']['VALUE_XML_ID'] == 'YES' ? true : false;
	    $cp->arResult['SECTION_BNR_CONTENT'] = $onHead ? 'onHead' : true;
	    $cp->SetResultCacheKeys( array('SECTION_BNR_CONTENT') );
	}
}*/




//echo '<pre>', var_dump($arResult['DISPLAY_PROPERTIES']), '</pre>';

$arResult['DISPLAY_PROPERTIES_FORMATTED'] = CMax::PrepareItemProps($arResult['DISPLAY_PROPERTIES']);

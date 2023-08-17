<?

/*need get prices for services */
$serviceNeedPrice = false;
if(isset($arParams["SERVICES_MODE"]) && $arParams["SERVICES_MODE"] === 'Y'){
	$serviceNeedPrice = true;
}
/**/

if($arResult['ITEMS'])
{
	$arSectionsIDs = array();
	
	foreach($arResult['ITEMS'] as $key => &$arItem){
		if($SID = $arItem['IBLOCK_SECTION_ID']){
			$arSectionsIDs[] = $SID;
		}
		$arResult['ITEMS'][$key]['DETAIL_PAGE_URL'] = CMax::FormatNewsUrl($arItem);

		if(isset($arItem['DISPLAY_PROPERTIES']['REDIRECT']) && strlen($arItem['DISPLAY_PROPERTIES']['REDIRECT']['VALUE']))
			unset($arResult['ITEMS'][$key]['DISPLAY_PROPERTIES']['REDIRECT']);
		
		if($arItem['DISPLAY_PROPERTIES'])
		{
			// filtered props to display on list of elements page
			$arItem['FILTERED_PROPERTIES'] = array_filter($arItem['DISPLAY_PROPERTIES'], function($prop){
				return !in_array($prop, ['PERIOD', 'TITLE_BUTTON', 'LINK_BUTTON', 'TYPE_BLOCK', 'SALE_NUMBER', 'PRICE_OLD', 'PRICE', 'EMAIL', 'PHONE', 'SITE', 'FORM_ORDER']);
			}, ARRAY_FILTER_USE_KEY);
	
			if( isset($arParams['SERVICE_MAX_PROPERTIES_COUNT']) && intval($arParams['SERVICE_MAX_PROPERTIES_COUNT']) && count($arItem['FILTERED_PROPERTIES']) >= intval($arParams['SERVICE_MAX_PROPERTIES_COUNT']) )
				$arItem['FILTERED_PROPERTIES'] = array_slice($arItem['FILTERED_PROPERTIES'], 0, intval($arParams['SERVICE_MAX_PROPERTIES_COUNT']), true);
	
			foreach($arItem['DISPLAY_PROPERTIES'] as $key2 => $arProp)
			{
				if(($key2 == 'EMAIL' || $key2 == 'PHONE'|| $key2 == 'SITE') && $arProp['VALUE'])
					$arResult['ITEMS'][$key]['MIDDLE_PROPS'][$key2] = $arProp;
				
			}
		}
		//echo '<pre>',var_dump($arItem['MIDDLE_PROPS']),'</pre>';

		CMax::getFieldImageData($arResult['ITEMS'][$key], array('PREVIEW_PICTURE'));

		if($arParams['USE_SECTIONS_TABS']=='Y'){
			if($arItem['IBLOCK_SECTION_ID']){
				$resGroups = CIBlockElement::GetElementGroups($arItem['ID'], true, array('ID'));
				while($arGroup = $resGroups->Fetch())
				{
					$arResult['ITEMS'][$key]['SECTIONS'][$arGroup['ID']] = $arGroup['ID'];
					$arGoodsSectionsIDs[$arGroup['ID']] = $arGroup['ID'];
				}
			}
		}


		if($serviceNeedPrice && $arItem['PROPERTIES']['ALLOW_BUY']['VALUE'] === 'Y'){
			$arItemsNeedPrice[] = $arItem['ID'];
			$arItemsKeys[$arItem['ID']] = $key;
		}

	}

	/*start price services */
	if($serviceNeedPrice && is_array($arItemsNeedPrice) && count($arItemsNeedPrice) > 0 ){
		
		$db_res = CCatalogProduct::GetList(
			array("ID" => "DESC"),
			array("@ID" => $arItemsNeedPrice),
			false,
			array("nTopCount" => count($arItemsNeedPrice) )
		);

		$pricesTypes = CIBlockPriceTools::GetCatalogPrices($arParams["IBLOCK_ID"], $arParams["PRICE_CODE"]);
		$pricesTypesAllow = \CIBlockPriceTools::GetAllowCatalogPrices($pricesTypes);

		$select = array(
			'ID', 'PRODUCT_ID', 'CATALOG_GROUP_ID', 'PRICE', 'CURRENCY',
			'QUANTITY_FROM', 'QUANTITY_TO'
		);

		if($pricesTypesAllow && $arItemsNeedPrice){

			$iterator = \Bitrix\Catalog\PriceTable::getList(array(
				'select' => $select,
				'filter' => array('@PRODUCT_ID' => $arItemsNeedPrice, '@CATALOG_GROUP_ID' => $pricesTypesAllow),
				'order' => array('PRODUCT_ID' => 'ASC', 'CATALOG_GROUP_ID' => 'ASC')
			));

			$arPrices = array();

			while ($row = $iterator->fetch())
			{
				if($row['QUANTITY_FROM'] && $row['QUANTITY_FROM'] !== '1')
					continue;
				$arPrices[$row['PRODUCT_ID']][$row['CATALOG_GROUP_ID']] = $row;
			}

			$vatData = array();
			$vatIterator = \Bitrix\Catalog\VatTable::getList([
				'select' => ['ID', 'RATE'],
				'order' => ['ID' => 'ASC']
			]);
			while ($rowVat = $vatIterator->fetch())
				$vatData[(int)$rowVat['ID']] = (float)$rowVat['RATE'];

			
			while ( $product_data = $db_res->Fetch() )
			{
				if($product_data['AVAILABLE'] === 'Y'){
					$vatRate = (float)$vatData[$product_data['VAT_ID']];
					$arMinimalPrice = \Aspro\Functions\CAsproMaxItem::getServicePrices($arParams, $arPrices[$product_data['ID']], $product_data, $vatRate);
					if(is_array($arMinimalPrice) && isset($arMinimalPrice['PRICE']) && $arMinimalPrice['PRICE']>0 ){
						$arResult['ITEMS'][$arItemsKeys[$product_data['ID']]] ["BUTTON_RESULT_PRICE"] = $arMinimalPrice;
						$arResult['ITEMS'][$arItemsKeys[$product_data['ID']]] ["SHOW_BUY_BUTTON"] = true;
						//$arResult['ITEMS'][$arItemsKeys[$product_data['ID']]] ["CATALOG_QUANTITY"] = $product_data["QUANTITY"];	
					}
				}
			}
		}
	}
	/*end price services */
	
	
	if($arSectionsIDs && $arParams['USE_SECTIONS_TABS']!='Y')
	{
		$arResult['SECTIONS'] = CMaxCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => 'ID', 'MULTI' => 'N')), array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ID' => $arSectionsIDs, 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'), false, array('ID', 'NAME'));		
	} elseif(isset($arGoodsSectionsIDs) && $arGoodsSectionsIDs && $arParams['USE_SECTIONS_TABS']=='Y'){
		$arResult['SECTIONS'] = CMaxCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => 'ID', 'MULTI' => 'N')), array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ID' => $arGoodsSectionsIDs, 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'), false, array('ID', 'NAME'));
	}
}
?>
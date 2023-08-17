<?
use \Bitrix\Currency,
\Bitrix\Catalog;

//CMax::getFieldImageData($arResult, array('PREVIEW_PICTURE'));

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
				$arPrices[$row['CATALOG_GROUP_ID']] = $row;
				
			}

			//$arResult["CATALOG_QUANTITY"] = $product_data["QUANTITY"];	

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

?>
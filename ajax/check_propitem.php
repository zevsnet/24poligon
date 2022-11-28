<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if(!CModule::IncludeModule("sale") || !CModule::IncludeModule("catalog") || !CModule::IncludeModule("iblock")) { echo "failure"; return;}

if( isset( $_REQUEST["ocb_item"] ) && $_REQUEST["ocb_item"]=="Y" ){		
	$product_properties=$arSkuProp=array();
	$successfulAdd = true;
	$intProductIBlockID = (int)CIBlockElement::GetIBlockByID($_REQUEST["item"]);
	$arPropsItem=($_REQUEST["prop"] ? $_REQUEST["prop"] : array());
	if (0 < $intProductIBlockID){				
		if($_REQUEST["add_props"]=="Y"){
			$arSkuProp=json_decode($_REQUEST["props"]);
			if ($intProductIBlockID == $_REQUEST["iblockID"]){
				if($_REQUEST["props"]){
					$product_properties = CIBlockPriceTools::CheckProductProperties(
						$_REQUEST["iblockID"],
						$_REQUEST["item"],
						$arSkuProp,
						$arPropsItem,
						$_REQUEST['part_props'] == 'Y'
					);
					if (!is_array($product_properties)){
						$strError = "CATALOG_PARTIAL_BASKET_PROPERTIES_ERROR";
						$successfulAdd = false;
					}
				}else{
					$strError = "CATALOG_EMPTY_BASKET_PROPERTIES_ERROR";
					$successfulAdd  = false;
				}
			}else{
				$skuAddProps = (isset($_REQUEST['basket_props']) && !empty($_REQUEST['basket_props']) ? $_REQUEST['basket_props'] : '');
				if ($arSkuProp || !empty($skuAddProps))
				{
					$product_properties = CIBlockPriceTools::GetOfferProperties(
						$_REQUEST["item"],
						$_REQUEST["iblockID"],
						$arSkuProp,
						$skuAddProps
					);
				}
			}
		}				
	}else{
		$strError = 'CATALOG_ELEMENT_NOT_FOUND';
		$successfulAdd = false;
	}
	if ($successfulAdd){
		$addResult = array('STATUS' => 'OK', 'MESSAGE' => 'CATALOG_SUCCESSFUL_ADD_TO_BASKET', 'PROPS' => $arPropsItem);
	}else{
		$addResult = array('STATUS' => 'ERROR', 'MESSAGE' => $strError);
	}
	echo json_encode($addResult);
	die();
}
?>
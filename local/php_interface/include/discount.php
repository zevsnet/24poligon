<?
//для реализации расширенных цен
//инфолблок Discounts для хранения настроек

define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/local/php_interface/include/log.txt");


//выдает все настройки-скидки по расширенным ценам (у какиз товаров, у каких разделов)
function GetAllDiscountItems(){
	$cache = \Bitrix\Main\Data\Cache::createInstance(); // Служба кеширования
	$taggedCache = \Bitrix\Main\Application::getInstance()->getTaggedCache(); // Служба пометки кеша тегами
	 

	$cachePath = '/get_all_discount_items2/';
	$cacheTtl = 100000; //время кеша в секундах
	$cacheKey = 'get_all_discount_items2';
	
	$result = []; 
	if ($cache->initCache($cacheTtl, $cacheKey,$cachePath)) {
	    $result = $cache->getVars();
	} 
	elseif (\Bitrix\Main\Loader::includeModule('iblock') && $cache->startDataCache()) {	    
	    $taggedCache->startTagCache($cachePath); //одинаковый с $cache->initCache
	    $dbItems = \Bitrix\Iblock\Elements\ElementDiscountsTable::getList([
        	'filter'=>["ACTIVE"=>"Y"],  
        	'order' =>["SORT"=>"DESC"],
   			'select' => ['ID', "SORT", 'MY_SECTIONS', 'MY_GOODS'],         	
		]);
		$arIds = [];
		$result = [];
		while ($item = $dbItems->fetchObject()){ 
   			$arIds[$item->getId()] = $item->getId();
   			
   			$arGoods = [];
   			foreach($item->getMyGoods()->getAll() as $good){ //если множественное
				$arGoods[] = $good->getValue();
			}
			$arSections = [];
			foreach($item->getMySections()->getAll() as $section){ //если множественное
				$arSections[] = $section->getValue();
			}

   			$result[$item->getId()] = [
   				'SECTIONS' => $arSections,
   				'GOODS' => $arGoods,

   			];
		}

		if(!empty($arIds)	&& \Bitrix\Main\Loader::includeModule("catalog")){
			$allProductPrices = \Bitrix\Catalog\PriceTable::getList([
				"select" => ["PRICE","PRODUCT_ID","QUANTITY_FROM","QUANTITY_TO"],
				"order"  => ["PRICE"=>"DESC"],
				"filter" => [
					"PRODUCT_ID" => $arIds,
					"CATALOG_GROUP_ID" => 4 //Розничная цена
				],				
			]);
			
			while ($price = $allProductPrices->fetch()){ 	
				if(!empty($price["QUANTITY_FROM"]) || !empty($price["QUANTITY_TO"])){			
					$result[$price["PRODUCT_ID"]]["PRICE"][] = $price;
				}
			}
		}	

	   

	    // Добавляем теги    
	    $taggedCache->registerTag('iblock_id_194'); //кеш будет автоматически сбрасываться при изменении данных в 194 инфоблоке    	    
	    	 
	    //записываем кеш
	    $taggedCache->endTagCache();
	    $cache->endDataCache($result);
	}   
	return $result;
}

function GetConditionPriceById($id){
	$arConditions = GetAllDiscountItems();
	
	$arCurrentCondition = false;

	//ищем по айдишнику товара
	foreach($arConditions as $arCondition){
		if(in_array($id,$arCondition["GOODS"])){
			$arCurrentCondition = $arCondition;
			break;
		}
	}
	//ищем по разделу
	if(empty($arCurrentCondition) && \Bitrix\Main\Loader::includeModule('iblock')){
		//сначала в офферсах
		$dbItems = \Bitrix\Iblock\Elements\ElementMaxcatalogskuTable::getList([
        	'filter'=>["ID"=>$id],          	
   			'select' => ["ID",'CML2_LINK'],
   			"cache" => ["ttl" => 36000000],         	
		]);
		if($item = $dbItems->fetchObject()){ 			
			$idNew = $item->getCml2Link()->getValue();	
			//ищем по айдишнику товара еще раз
			foreach($arConditions as $arCondition){
				if(in_array($idNew,$arCondition["GOODS"])){
					$arCurrentCondition = $arCondition;
					break;
				}
			}
			
		}
		else{
			$idNew = $id;
		}
		
		//ищем по разделу
		if(empty($arCurrentCondition)){			
			$dbItems = \Bitrix\Iblock\Elements\ElementMaxcatalogTable::getList([
	        	'filter'=>["ID"=>$idNew],          	
	   			'select' => ['IBLOCK_SECTION'],  
	   			"cache" => ["ttl" => 36000000],            	
			]);
			
			if($item = $dbItems->fetchObject()){ 
				$sectionId = $item->getIblockSection()->getId();
				
				foreach($arConditions as $arCondition){
					if(in_array($sectionId,$arCondition["SECTIONS"])){
						$arCurrentCondition = $arCondition;
						break;
					}
				}
			}
		}		
	}

	return $arCurrentCondition["PRICE"] ?? false;
}


AddEventHandler("catalog", "OnPriceAdd", "MyOnPriceAdd");
AddEventHandler("catalog", "OnPriceUpdate", "MyOnPriceUpdate");


function MyOnPriceAdd($id,$arFields){ 
	//AddMessage2Log($id);   
	SetExtendedPrices($arFields);
}	
function MyOnPriceUpdate($id,$arFields){    
	//AddMessage2Log($id);
	SetExtendedPrices($arFields);
}

function SetExtendedPrices($arFields){ 	
    if (($arFields["CATALOG_GROUP_ID"] == 4) && empty($arFields["QUANTITY_FROM"]) && empty($arFields["QUANTITY_TO"])) {    	
    	$arPrices = GetConditionPriceById($arFields["PRODUCT_ID"]);
    	if(!empty($arPrices)){    		 
    		$date = new DateTime();		    
		    $date->modify("-3 hour -55 minutes");
		    $res = CAgent::AddAgent(
		        "AddNewExtendPrice4(".$arFields["PRODUCT_ID"].");",
		        "main",
		        "Y",
		        5,
		        $date->format("d.m.Y H:i:s"),
		        "Y",
		        $date->format("d.m.Y H:i:s")
		    );   			    
    	}        
    }
}

function AddNewExtendPrice4($productId){
	$arPrices = GetConditionPriceById($productId);
    if(!empty($arPrices) && \Bitrix\Main\Loader::includeModule("catalog")){
		global $APPLICATION;
		//
		$allProductPrices = \Bitrix\Catalog\PriceTable::getList([
			"select" => ["ID","PRICE","QUANTITY_FROM"],				
			"filter" => [
				
				"PRODUCT_ID" => $productId,
				"CATALOG_GROUP_ID" => 4 //Розничная цена
			],				
		]);
		while ($arPriceOld = $allProductPrices->fetch()){ 		
			if(empty($arPriceOld["QUANTITY_FROM"])){
				$currentPrice = $arPriceOld["PRICE"];
			}
			
			//AddMessage2Log($arPriceOld);		
			CPrice::Delete($arPriceOld["ID"]);
			
		}
		foreach($arPrices as $arPrice){
			$res = CPrice::Add([
		        "PRODUCT_ID" => $productId,
		        "CATALOG_GROUP_ID" => 4,
		        "PRICE" =>  ceil($currentPrice * intval($arPrice["PRICE"]))/100,
		        "CURRENCY" => "RUB",
		        "QUANTITY_FROM" => $arPrice["QUANTITY_FROM"],
		        "QUANTITY_TO" => $arPrice["QUANTITY_TO"]
		    ], false);

		    if($res){
		    	AddMessage2Log([$res,[$productId,$price,$from,$to]]);
		    }
		    else{
		    	AddMessage2Log([$APPLICATION->GetException(),[$productId,$price,$from,$to]]);
		    }
		}    
	}    
    return "";
}


AddEventHandler('aspro.max', "OnAsproShowPriceRangeTop", "MyOnAsproShowPriceRangeTop");
function MyOnAsproShowPriceRangeTop($arItem, $arParams, $mess, &$html){
	$matrix = $arItem["PRICE_MATRIX"] ?? false; 
	if(!empty($matrix["MATRIX"][4]) && is_array($matrix["MATRIX"][4]) && count(($matrix["MATRIX"][4]))>1){
		$add = "<div class='priceMatrixExt'>";
		foreach($matrix["MATRIX"][4] as $key => $arPrice){
			$from = $matrix["ROWS"][$key]["QUANTITY_FROM"] ?? false;
			$to = $matrix["ROWS"][$key]["QUANTITY_TO"] ?? false;
			if(!empty ($from) && !empty($to)){
				$add .= $matrix["ROWS"][$key]["QUANTITY_FROM"]."-". $matrix["ROWS"][$key]["QUANTITY_TO"]."шт. ";
		    	$add .= ": <b>". $arPrice["DISCOUNT_PRICE"]." руб.</b><br>";	
		    }
		    elseif(!empty($to)){
		    	$add .= "до ". $matrix["ROWS"][$key]["QUANTITY_TO"]."шт. ";
		    	$add .= ": <b>". $arPrice["DISCOUNT_PRICE"]." руб.</b><br>";
		    }	
		    elseif(!empty($from)){
		    	$add .= $matrix["ROWS"][$key]["QUANTITY_FROM"]."шт. и более";
		    	$add .= ": <b>". $arPrice["DISCOUNT_PRICE"]." руб.</b><br>";
		    }
		    else{
		    	AddMessage2Log(["алгоритмическая ошибка, надо разобраться",[$arPrice]]);
		    }		    
		}
		$add .="</div>";
		$html .= $add;
	}
	//AddMessage2Log([$arItem["PRICE_MATRIX"], $mess, &$html]);
}

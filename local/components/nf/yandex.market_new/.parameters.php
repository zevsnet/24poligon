<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
	return;
	
	//$arIBlockType = CIBlockParameters::GetIBlockTypes();
	
$arFilterIBlock = array("ACTIVE" => "Y");

if (!empty($_REQUEST['src_site'])) {
	$arFilterIBlock["SITE_ID"] = $_REQUEST['src_site'];
}

if($arCurrentValues["IBLOCK_TYPE"]	!= "")
{
	$db_iblock_type = CIBlockType::GetList(array(), array("ID" => $arCurrentValues["IBLOCK_TYPE"]));
	while($ar_iblock_type = $db_iblock_type->Fetch())
	{
	   if($arIBType = CIBlockType::GetByIDLang($ar_iblock_type["ID"], LANG))
	   {
		  $arIBlockType[$arIBType["ID"]] = "[".$arIBType["ID"]."] ".$arIBType["NAME"];
	   }
	}
	
	$arFilterIBlock['TYPE'] = $arCurrentValues["IBLOCK_TYPE"];
}
else
{
	$arIBlockType = CIBlockParameters::GetIBlockTypes();
	
	$arFilterIBlock['TYPE'] = $arCurrentValues["IBLOCK_TYPE_LIST"];
}

$rsIBlock = CIBlock::GetList(Array("sort" => "asc"), $arFilterIBlock);

$iblocks = array();

//$arIBlockOut = array();

$arSKUProps = array();
$arProps = array();

$dbIBlock = CIBlock::GetList(Array("sort" => "asc"), Array("ACTIVE"=>"Y"));
while ($arIb = $dbIBlock->Fetch()) {
	$dbProperty = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $arIb['ID'], "USER_TYPE"=>"SKU"));
	while($arProperty  =  $dbProperty->Fetch())
		$arSKUProps['PROPERTY_'.$arProperty['CODE']] = "[{$arIb['CODE']}] [{$arProperty['CODE']}] {$arProperty['NAME']}";
}

while($arr = $rsIBlock->Fetch())
{
	if (CModule::IncludeModule('catalog') && $arCurrentValues['IBLOCK_CATALOG'] != 'N') {
		if(!($arCatalog = CCatalog::GetById($arr["ID"]))) continue;
		if($arCatalog["PRODUCT_IBLOCK_ID"] != 0) continue;
	}
	$arIBlock[$arr["ID"]] = $arIBlockType[$arr["IBLOCK_TYPE_ID"]]." / ".$arr["NAME"];
        $iblocks[] = $arr["ID"];
		
//	if(!in_array($arr["ID"], $arCurrentValues["IBLOCK_ID_IN"])) {
//		$arIBlockOut[$arr["ID"]] = $arIBlockType[$arr["IBLOCK_TYPE_ID"]]." / ".$arr["NAME"];
//	}
    if (empty($arCurrentValues["IBLOCK_ID_IN"][0])) {
		$dbProperty = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $arr['ID']));
		while($arProperty  =  $dbProperty->Fetch())
			$arProps[$arProperty['CODE']] = "[{$arProperty['CODE']}] {$arProperty['NAME']}";
	}
}

$arIBlockAll = $arIBlock;
$arIBlock[0] = GetMessage("ALL");
ksort($arIBlock);

if (is_array($arCurrentValues["IBLOCK_ID_IN"])) {
	foreach ($arCurrentValues["IBLOCK_ID_IN"] as $key => $id) {
		if (!array_key_exists($id, $arIBlock))
			unset($arCurrentValues["IBLOCK_ID_IN"][$key]);
	}
    if (!empty($arCurrentValues["IBLOCK_ID_IN"][0])) {
    	foreach ($arCurrentValues["IBLOCK_ID_IN"] as $id) {
			$dbProperty = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $id));
			while($arProperty  =  $dbProperty->Fetch())
				$arProps[$arProperty['CODE']] = "[{$arProperty['CODE']}] {$arProperty['NAME']}";
    	}
	}
}

ksort($arProps);
array_unshift($arProps, '');

if (is_array($arCurrentValues['IBLOCK_ID_IN']) && !empty($arCurrentValues['IBLOCK_ID_IN'][0])) {
	$arIblockID = $arCurrentValues['IBLOCK_ID_IN'];
}
else {
	$arIblockID = $iblocks;
}

$arPrice = array("YS_EMPTY" => "-----");
if (!CModule::IncludeModule("catalog") || $arCurrentValues['PRICE_FROM_IBLOCK'] == 'Y') {
	foreach ($arIblockID as $id)
		if ($id > 0) {
			$rsPrice = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $id, array("LOGIC" => "OR", array("PROPERTY_TYPE" => "S"), array("PROPERTY_TYPE" => "N"))));
			while ($arr = $rsPrice->Fetch())
				if (!in_array($arr["NAME"], $arPrice))
					$arPrice[$arr["CODE"]] = $arr["NAME"];
		}
} else {
	$rsPrice = CCatalogGroup::GetList($v1 = "sort", $v2 = "asc");
	while ($arr = $rsPrice->Fetch()) $arPrice[$arr["NAME"]] = "[" . $arr["NAME"] . "] " . $arr["NAME_LANG"];
}

$arPhoto = array(''=>GetMessage("NO_PHOTO"), 'ys_fields'=>GetMessage("GET_OVER_FIELDS")) ;

foreach($arIblockID as $id)
if($id > 0)
{
	$rsPhoto = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $id,  "PROPERTY_TYPE" => "F"));
    while($arr = $rsPhoto->Fetch())		
			$arPhoto[$arr["CODE"]] = $arr["NAME"];
}

//$arIBlockOut[0] = GetMessage("NO");
//ksort($arIBlockOut);

function getSecPath($id){
	CModule::IncludeModule('iblock');
	$pathAr = array();	
	$nav = CIBlockSection::GetNavChain(false, $id);
	$path = "";
	while($arNav = $nav->GetNext()){
		$path .= " / ".$arNav["NAME"];
	}
	return $path;
}

if(count($arCurrentValues["IBLOCK_ID_IN"]) > 0 && empty($arCurrentValues["IBLOCK_ID_IN"][0]) || !$arCurrentValues["IBLOCK_ID_IN"]){
	if (!count($iblocks)) $iblocks = '0';
	$rsIBlockSection = CIBlockSection::GetList(Array("sort" => "asc"), Array("IBLOCK_ID" => $iblocks, "ACTIVE"=>"Y", "INCLUDE_SUBSECTIONS" => "Y"));
}
else
{
	$rsIBlockSection = CIBlockSection::GetList(Array("sort" => "asc"), Array("IBLOCK_ID" => $arCurrentValues["IBLOCK_ID_IN"], "ACTIVE"=>"Y", "INCLUDE_SUBSECTIONS" => "Y"));	
}
	
$arIBlockSection[0] = GetMessage("ALL");	
	
while($arr = $rsIBlockSection->Fetch())
{	
	$arIBlockSection[$arr["ID"]] = $arIBlockAll[$arr["IBLOCK_ID"]].getSecPath($arr["ID"]);
}

natsort($arIBlockSection);

$arUserFields_S = array("-" => " ");
$arUserFields = $GLOBALS["USER_FIELD_MANAGER"]->GetUserFields("IBLOCK_".$arCurrentValues["IBLOCK_ID"]."_SECTION");
foreach($arUserFields as $FIELD_NAME=>$arUserField)
	if($arUserField["USER_TYPE"]["BASE_TYPE"] == "string")
		$arUserFields_S[$FIELD_NAME] = $arUserField["LIST_COLUMN_LABEL"]? $arUserField["LIST_COLUMN_LABEL"]: $FIELD_NAME;



$arAscDesc = array(
	"asc" => GetMessage("IBLOCK_SORT_ASC"),
	"desc" => GetMessage("IBLOCK_SORT_DESC"),
);


$arOrder = $arPrice;

$arSKUName = array('PRODUCT_AND_SKU_NAME'=>GetMessage('PRODUCT_AND_SKU_NAME'), 'PRODUCT_NAME'=>GetMessage('PRODUCT_NAME'), 'SKU_NAME'=>GetMessage('SKU_NAME')) ;


$arComponentParameters = array(
	"GROUPS" => array(
		"SKU" => array(
			"NAME" => GetMessage("SKU_GROUP_NAME")
		),
		"PRICES" => array(
			"NAME" => GetMessage("IBLOCK_PRICES"),
		),
		"OLD_PRICES" => array(
			"NAME" => GetMessage("OLD_PRICES"),
		),
		"DELIVERY" => array(
			"NAME" => GetMessage("DELIVERY"),
		),
		"COMMON" => array(
			"NAME" => GetMessage("COMMON"),
		),
		"PERFORMANCE" => array(
			"NAME" => GetMessage("PERFORMANCE")
		),
	),
	
	"PARAMETERS" => array(
		
		"IBLOCK_TYPE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("IBLOCK_TYPE"),
			"TYPE" => "STRING",
			"DEFAULT" => 'catalog_%',
			"REFRESH" => "Y",
		),
		
		"IBLOCK_TYPE_LIST" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("IBLOCK_TYPE_LIST"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlockType,
			"MULTIPLE" => "Y",
            "REFRESH" => "Y",
		),
		
		"IBLOCK_CATALOG" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("IBLOCK_CATALOG"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
			"REFRESH" => "Y",
		),
		
		"IBLOCK_ID_IN" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("IBLOCK_IBLOCK_IN"),
			"TYPE" => "LIST",		
			"VALUES" => $arIBlock,
			"MULTIPLE" => "Y",
            "REFRESH" => "Y",
		),
		
/*		"IBLOCK_ID_EX" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("IBLOCK_IBLOCK_EX"),
			"TYPE" => "LIST",		
			"VALUES" => $arIBlockOut,
			"MULTIPLE" => "Y",
		),
*/		
		"IBLOCK_SECTION" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("IBLOCK_SECTION"),
			"TYPE" => "LIST",			
			"VALUES" => 	$arIBlockSection,
			"MULTIPLE" => "Y",
			"DEFAULT" => "0",
			"SIZE" => 10,
		),
		
		"DO_NOT_INCLUDE_SUBSECTIONS" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("DO_NOT_INCLUDE_SUBSECTIONS"),
			"TYPE" => "CHECKBOX",				
			"DEFAULT" => "N"
		),

		"IBLOCK_AS_CATEGORY" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("IBLOCK_AS_CATEGORY"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y"
		),
		
         "SITE" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("SITE"),
			"TYPE" => "STRING",
			"DEFAULT" => "mysite.com",
		),
		
		"COMPANY" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("COMPANY"),
			"TYPE" => "STRING",
			"DEFAULT" => "My company",
		),

		"SKU_NAME" => array(
			"PARENT" => "SKU",
			"NAME" => GetMessage("SKU_NAME_PARAM"),
			"TYPE" => "LIST",				
			"MULTIPLE" => "N",
			"VALUES" => $arSKUName,
			"DEFAULT" => "PRODUCT_AND_SKU_NAME",
		),
            
		"SKU_PROPERTY" => array (
			"PARENT" => "SKU",
			"NAME" => GetMessage("SKU_PROPERTY"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"VALUES"	=> $arSKUProps,
			"DEFAULT"	=> "PROPERTY_CML2_LINK"
		),
		"FILTER_NAME" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("IBLOCK_FILTER_NAME_IN"),
			"TYPE" => "STRING",
			"DEFAULT" => "arrFilter",
		),
		
		"MORE_PHOTO" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("MORE_PHOTO"),
			"TYPE" => "LIST",
			"VALUES" =>  $arPhoto,
			"DEFAULT" => "MORE_PHOTO",
			"ADDITIONAL_VALUES" => "Y",
		),

		/*"PROPERTY_CODE" => array(
			"PARENT" => "VISUAL",
			"NAME" => GetMessage("IBLOCK_PROPERTY"),
			"TYPE" => "STRING",
			"MULTIPLE" => "Y",
			"VALUES" => "",
		),*/

		"OLD_PRICE_LIST" => array(
			"PARENT" => "OLD_PRICES",
			"NAME" => GetMessage("OLD_PRICE_LIST"),
			"TYPE" => "LIST",
			"VALUES" => array(
				"TYPE_PRICE" => GetMessage("TYPE_PRICE_NAME"),
				"PROP_PRICE" => GetMessage("PROP_PRICE_NAME"),  
				"FROM_DISCOUNT" => GetMessage("PRICE_DISCOUNT_NAME"), 
				),
			"REFRESH" => "Y",
			"DEFAULT"=> "FROM_DISCOUNT"
		),
		
		
		"IBLOCK_QUANTITY" => array(
			"PARENT" => "PRICES",
			"NAME" => GetMessage("IBLOCK_QUANTITY"),
			"TYPE" => "LIST",	
			"VALUES" => $arOrder,
		),
		
		"IBLOCK_ORDER" => array(
			"PARENT" => "PRICES",
			"NAME" => GetMessage("IBLOCK_ORDER"),
			"TYPE" => "CHECKBOX",				
			"DEFAULT" => "N"
		),
            
        "CURRENCY" => array(
			"PARENT" => "PRICES",
			"NAME" => GetMessage("IBLOCK_CURRENCY"),
			"TYPE" => "LIST",
            "VALUES" => array(
				"RUB" => GetMessage("RUB"),
				"USD" => GetMessage("USD"),  // not may be base
				"EUR" => GetMessage("EUR"), // not may be base
				"UAH" => GetMessage("UAH"),
				"BYR" => GetMessage("BYR"),
				"KZT" => GetMessage("KZT"),
			),
			"DEFAULT" => "RUB"
		),
		
		"CURRENCIES_PROP" => array(
			"PARENT" => "PRICES",
			"NAME" => GetMessage("CURRENCIES_PROP"),
			"TYPE" => "STRING",
		),
		
		"CURRENCIES_CONVERT" => array(
			"PARENT" => "PRICES",
			"NAME" => GetMessage("CURRENCIES_CONVERT"),
			"TYPE" => "LIST",	
			"VALUES" => array(
				"NOT_CONVERT" => GetMessage("NOT_CONVERT"),
				"RUB" => GetMessage("RUB"), 
				"USD "=> GetMessage("USD"),  // not may be base
				"EUR" => GetMessage("EUR"), // not may be base
				"UAH" => GetMessage("UAH"),
				"BYR" => GetMessage("BYR"),
				"KZT" => GetMessage("KZT"),
			),
			"DEFAULT" => "NOT_CONVERT",
		),

		"LOCAL_DELIVERY_COST" => array(
			"PARENT" => "DELIVERY",
			"NAME" => GetMessage("LOCAL_DELIVERY_COST"),
			"TYPE" => "STRING",				
			"DEFAULT" => ""
		),
		
		"NAME_PROP" => array(
			"PARENT" => "COMMON",
			"NAME" => GetMessage("NAME_PROP"),
			"TYPE" => "LIST",	
			"VALUES" => $arProps,
			"DEFAULT" => ""
		),
		
		"DETAIL_TEXT_PRIORITET" => array(
			"PARENT" => "COMMON",
			"NAME" => GetMessage("DETAIL_TEXT_PRIORITET"),
			"TYPE" => "CHECKBOX",				
			"DEFAULT" => "N"
		),

		"DISCOUNTS" => array(
			"PARENT" => "PERFORMANCE",
			"NAME" => GetMessage("DISCOUNTS"),
			"TYPE" => "LIST",
			"VALUES" => array(
				"PRICE_ONLY" => GetMessage("PRICE_ONLY"),
				"DISCOUNT_CUSTOM" => GetMessage("DISCOUNT_CUSTOM"),
				"DISCOUNT_API" => GetMessage("DISCOUNT_API"),
				),
			"DEFAULT" => "DISCOUNT_CUSTOM"
		),
		
		"CACHE_TIME"  =>  Array("DEFAULT" => 3600),
		"CACHE_FILTER" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("IBLOCK_CACHE_FILTER"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"CACHE_NON_MANAGED" => array(
			"PARENT" => "CACHE_SETTINGS",
			"NAME" => GetMessage("CACHE_NON_MANAGED"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"PARAMS" => Array(
			"PARENT" => "COMMON",
			"NAME" => GetMessage("PARAMS"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $arProps,		
		),
		"COND_PARAMS" => Array(
			"PARENT" => "COMMON",
			"NAME" => GetMessage("COND_PARAMS"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $arProps,		
		),
		"SELF_SALES_NOTES" => array(
			"PARENT" => "COMMON",
			"NAME" => GetMessage("SELF_SALES_NOTES"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
			"REFRESH" => "Y",
		),
	),
);

if ($arCurrentValues['IBLOCK_CATALOG'] == 'N')
{
	$arComponentParameters["PARAMETERS"]['CHECK_PRICE_FROM_PROM'] = array (
		"PARENT" => "PRICES",
		"NAME" => GetMessage("CHECK_PRICE_FROM_PROM"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
		"REFRESH" => "Y",
	);

}
$arComponentParameters['PARAMETERS']['PRICE_CODE'] = array(
	"PARENT" => "PRICES",
	"NAME" => GetMessage("IBLOCK_PRICE_CODE"),
	"TYPE" => "LIST",
	"MULTIPLE" => "Y",
	"VALUES" => $arPrice,
);


if (isset($arComponentParameters["PARAMETERS"]['CHECK_PRICE_FROM_PROM'])
	&& $arCurrentValues['CHECK_PRICE_FROM_PROM'] == 'Y')
{
	$arComponentParameters["PARAMETERS"]['PROP_FOR_PRICE'] = array(
		"PARENT" => "PRICES",
		"NAME" => GetMessage("PROP_FOR_PRICE"),
		"TYPE" => "LIST",
		"MULTIPLE" => "N",
		"VALUES" => $arProps,
	);
}

if ( CModule::IncludeModule('catalog') && $arCurrentValues['PRICE_FROM_IBLOCK'] != 'Y')
{
	$arComponentParameters["PARAMETERS"]["PRICE_CODE"]["MULTIPLE"] = "Y";

	unset($arComponentParameters["PARAMETERS"]["IBLOCK_QUANTITY"]);
	unset($arComponentParameters["PARAMETERS"]["CURRENCIES_PROP"]);
	unset($arComponentParameters["PARAMETERS"]["CURRENCY"]);
} else {
	unset($arComponentParameters["PARAMETERS"]["CURRENCIES_CONVERT"]);
	unset($arComponentParameters["PARAMETERS"]["DISCOUNTS"]);
}
if ( !CModule::IncludeModule('catalog') ) {
	unset($arComponentParameters["PARAMETERS"]["IBLOCK_CATALOG"]);
}
if($arCurrentValues["IBLOCK_TYPE"]	!= "") unset($arComponentParameters["PARAMETERS"]["IBLOCK_TYPE_LIST"]);

if(empty($arCurrentValues["IBLOCK_CATALOG"])) {
	$arCurrentValues["IBLOCK_CATALOG"] = 'N';
}

if(empty($arCurrentValues["SELF_SALES_NOTES"])) {
	$arCurrentValues["SELF_SALES_NOTES"] = 'N';
}

if($arCurrentValues["SELF_SALES_NOTES"]	== 'N'){
	$arComponentParameters["PARAMETERS"]["SALES_NOTES_NAMES"] = array(
			"PARENT" => "COMMON",
			"NAME" => GetMessage("SALES_NOTES_NAMES"),
			"TYPE" => "LIST",	
			"VALUES" => $arProps,
			"DEFAULT" => "",
		);
} else {
	$arComponentParameters["PARAMETERS"]["SELF_SALES_NOTES_INPUT"] = array(
			"PARENT" => "COMMON",
			"NAME" => GetMessage("SELF_SALES_NOTES_INPUT"),
			"TYPE" => "STRING",				
			"DEFAULT" => "",
		);
}

if(empty($arCurrentValues["OLD_PRICE_LIST"] )) {
	$arCurrentValues["OLD_PRICE_LIST"] = 'TYPE_PRICE';
}

if ($arCurrentValues["OLD_PRICE_LIST"] == 'TYPE_PRICE')
{
	
	$arComponentParameters["PARAMETERS"]["OLD_PRICE_CODE"] = array(
			"PARENT" => "OLD_PRICES",
			"NAME" => GetMessage("IBLOCK_OLD_PRICE_CODE"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"VALUES" => $arPrice
		);
} elseif ($arCurrentValues["OLD_PRICE_LIST"] == "PROP_PRICE")
{

	$arComponentParameters["PARAMETERS"]["OLD_PRICE_CODE"] = array(
			"PARENT" => "OLD_PRICES",
			"NAME" => GetMessage("IBLOCK_OLD_PRICE_CODE"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"VALUES" => $arProps
		);
} 
$GLOBALS['YS_YM_IBLOCK_ID'] = $iblocks;
?>
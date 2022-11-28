<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule("iblock"))die();

/*
foreach($arCurrentValues["IBLOCK_ID_IN"] as $id)
if ($id > 0)
{
    $rsProp = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $id,  array("LOGIC" => "OR", array("PROPERTY_TYPE" => "L"),
		array("PROPERTY_TYPE" => "E"), array("PROPERTY_TYPE" => "N") ) ) );

    while($arr = $rsProp->Fetch())
	{
        if (!in_array($arr["NAME"], $arProp) && ($arr["PROPERTY_TYPE"] == "E" || $arr["PROPERTY_TYPE"] == "L" ||
			$arr["PROPERTY_TYPE"] == "S" || $arr["PROPERTY_TYPE"] == "N") )
		{
            $arProp[$arr["CODE"]] = $arr["NAME"];
		}
	}
}

	$arProp["EMPTY"] = "				";
	natsort($arProp);
*/
foreach($arCurrentValues["IBLOCK_ID_IN"] as $id)
if ($id > 0)
{
	$arProp = array();
	
	if ( CModule::IncludeModule('catalog') )
	{
		$andreytroll = CCatalog::GetList(array(),array("PRODUCT_IBLOCK_ID"=> $id), false, false, array());
		$check = $andreytroll->Fetch();
			
		$rsProp = CIBlockProperty::GetList(array("sort" => "desc"), array("IBLOCK_ID" => $check["IBLOCK_ID"],
			array("LOGIC" => "OR", array("PROPERTY_TYPE" => "L"),
			array("PROPERTY_TYPE" => "E"), array("PROPERTY_TYPE" => "N") ) ) );
			
		while( $arr = $rsProp->Fetch() )
		{
			if ( !in_array($arr["NAME"], $arProp) && ($arr["PROPERTY_TYPE"] == "E" || $arr["PROPERTY_TYPE"] == "L" ||
				$arr["PROPERTY_TYPE"] == "S" || $arr["PROPERTY_TYPE"] == "N") )
			{
				$arProp[$arr["CODE"]] = "SKU_".$arr["NAME"];
			}
		}
	}
		
	$rsProp = CIBlockProperty::GetList(array("sort" => "desc"), array("IBLOCK_ID" => $id,  array("LOGIC" => "OR",
		array("PROPERTY_TYPE" => "L"), array("PROPERTY_TYPE" => "E"), array("PROPERTY_TYPE" => "E"), array("PROPERTY_TYPE" => "N") ) ) );
		
	while ( $arr = $rsProp->Fetch() )
	{
		if ( !in_array($arr["NAME"], $arProp) && ($arr["PROPERTY_TYPE"] == "E" || $arr["PROPERTY_TYPE"] == "L" ||
			$arr["PROPERTY_TYPE"] == "S" || $arr["PROPERTY_TYPE"] == "N") )
		{
			$arProp[$arr["CODE"]] = $arr["NAME"];
		}
	}
}

	$arProp["EMPTY"] = "				"; 
	natsort($arProp);

$arTemplateParameters = array(
	"DEVELOPER" => Array(
		"PARENT" => "YENISITE_YM_VENDOR",
		"NAME" => GetMessage("DEVELOPER"),
		"TYPE" => "LIST",
		"VALUES" => $arProp,		
	),
	"COUNTRY" => Array(
		"PARENT" => "YENISITE_YM_VENDOR",
		"NAME" => GetMessage("COUNTRY"),
		"TYPE" => "LIST",
		"VALUES" => $arProp,
	)
);


?> 
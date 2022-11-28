<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule("iblock"))die();

global $arComponentParameters;

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
	$arProp = $arComponentParameters["PARAMETERS"]["NAME_PROP"]["VALUES"];

$arTemplateParameters = array(
	"PARAMS" => Array(
		"PARENT" => "COMMON",
		"NAME" => GetMessage("PARAMS"),
		"TYPE" => "LIST",
		"MULTIPLE" => "Y",
		"VALUES" => $arProp,		
	),
	
	"COND_PARAMS" => Array(
		"PARENT" => "COMMON",
		"NAME" => GetMessage("COND_PARAMS"),
		"TYPE" => "LIST",
		"MULTIPLE" => "Y",
		"VALUES" => $arProp,		
	),
);


?> 
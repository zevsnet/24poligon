<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arProperty = $arProperty_Offers = array();
if(\Bitrix\Main\Loader::includeModule('iblock'))
{
	if(0 < intval($arCurrentValues['IBLOCK_ID']))
	{
		$rsProp = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("IBLOCK_ID"=>$arCurrentValues["IBLOCK_ID"], "ACTIVE"=>"Y"));
		while ($arr=$rsProp->Fetch())
		{
			if($arr["PROPERTY_TYPE"] == "F")
				$arProperty[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
		}

		$arOffers = CIBlockPriceTools::GetOffersIBlock($arCurrentValues["IBLOCK_ID"]);
		$OFFERS_IBLOCK_ID = is_array($arOffers)? $arOffers["OFFERS_IBLOCK_ID"]: 0;
		$arProperty_Offers = array();
		if($OFFERS_IBLOCK_ID)
		{
			$rsProp = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("IBLOCK_ID"=>$OFFERS_IBLOCK_ID, "ACTIVE"=>"Y"));
			while($arr=$rsProp->Fetch())
			{
				if($arr["PROPERTY_TYPE"] == "F")
					$arProperty_Offers[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
			}
		}
	}
}

$arTemplateParameters = array(
	"SHOW_RATING" => Array(
		"NAME" => GetMessage("SHOW_RATING"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"SHOW_GALLERY" => Array(
		"NAME" => GetMessage("SHOW_GALLERY_NAME"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
		"REFRESH" => "Y",
	),
	"MAX_GALLERY_ITEMS" => Array(
		"NAME" => GetMessage("MAX_GALLERY_ITEMS_NAME"),
		"TYPE" => "SELECTBOX",
		"VALUES" => array(2=>2, 3=>3, 4=>4, 5=>5),
		"DEFAULT" => "5",
		"HIDDEN" => ($arCurrentValues["SHOW_GALLERY"] == "Y" ? "N" : "Y"),
	),
	"ADD_PICT_PROP" => Array(
		"NAME" => GetMessage("ADD_PICT_PROP_NAME"),
		"TYPE" => "LIST",
		"VALUES" => $arProperty,
		"DEFAULT" => "MORE_PHOTO",
		"HIDDEN" => ($arCurrentValues["SHOW_GALLERY"] == "Y" ? "N" : "Y"),
	),
	"ADD_PICT_PROP_OFFER" => Array(
		"NAME" => GetMessage("ADD_PICT_PROP_OFFER_NAME"),
		"TYPE" => "LIST",
		"VALUES" => $arProperty_Offers,
		"DEFAULT" => "MORE_PHOTO",
		"HIDDEN" => ($arCurrentValues["SHOW_GALLERY"] == "Y" ? "N" : "Y"),
	),
);
?>

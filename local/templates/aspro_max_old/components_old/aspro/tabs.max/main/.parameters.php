<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$boolCatalog = CModule::IncludeModule("catalog");
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
	"TITLE_BLOCK" => Array(
		"NAME" => GetMessage("TITLE_BLOCK_NAME"),
		"TYPE" => "STRING",
		"DEFAULT" => GetMessage("BLOCK_NAME"),
	),
	"TITLE_BLOCK_ALL" => Array(
		"NAME" => GetMessage("TITLE_BLOCK_ALL_NAME"),
		"TYPE" => "STRING",
		"DEFAULT" => GetMessage("BLOCK_ALL_NAME"),
	),
	"ALL_URL" => Array(
		"NAME" => GetMessage("ALL_URL_NAME"),
		"TYPE" => "STRING",
		"DEFAULT" => "sale/",
	),
	"DISPLAY_BOTTOM_PAGER" => Array(
		"NAME" => GetMessage("DISPLAY_BOTTOM_PAGER_NAME"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"SHOW_ONE_CLICK_BUY" => Array(
		"NAME" => GetMessage("SHOW_ONE_CLICK_BUY_NAME"),
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
	/*"SHOW_PROPS" => Array(
		"NAME" => GetMessage("SHOW_PROPS"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
		"REFRESH" => "Y",
		"HIDDEN" => ($arCurrentValues["SHOW_GALLERY"] == "Y" ? "Y" : "N"),
	),*/
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

if ($boolCatalog)
{
	global $USER_FIELD_MANAGER;
	$arStore = array();
	$storeIterator = CCatalogStore::GetList(
		array(),
		array('ISSUING_CENTER' => 'Y'),
		false,
		false,
		array('ID', 'TITLE')
	);
	while ($store = $storeIterator->GetNext())
		$arStore[$store['ID']] = "[".$store['ID']."] ".$store['TITLE'];

	$userFields = $USER_FIELD_MANAGER->GetUserFields("CAT_STORE", 0, LANGUAGE_ID);
	$propertyUF = array();

	foreach($userFields as $fieldName => $userField)
		$propertyUF[$fieldName] = $userField["LIST_COLUMN_LABEL"] ? $userField["LIST_COLUMN_LABEL"] : $fieldName;

	$arTemplateParameters['USER_FIELDS'] = array(
			"PARENT" => "STORE_SETTINGS",
			"NAME" => GetMessage("STORE_USER_FIELDS"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => $propertyUF,
		);
	$arTemplateParameters['FIELDS'] = array(
		'NAME' => GetMessage("STORE_FIELDS"),
		'PARENT' => 'STORE_SETTINGS',
		'TYPE'  => 'LIST',
		'MULTIPLE' => 'Y',
		'ADDITIONAL_VALUES' => 'Y',
		'VALUES' => array(
			'TITLE'  => GetMessage("STORE_TITLE"),
			'ADDRESS'  => GetMessage("ADDRESS"),
			// 'DESCRIPTION'  => GetMessage('DESCRIPTION'),
			// 'PHONE'  => GetMessage('PHONE'),
			// 'SCHEDULE'  => GetMessage('SCHEDULE'),
			// 'EMAIL'  => GetMessage('EMAIL'),
			// 'IMAGE_ID'  => GetMessage('IMAGE_ID'),
			// 'COORDINATES'  => GetMessage('COORDINATES'),
		)
	);
}
?>

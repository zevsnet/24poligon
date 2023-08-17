<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
	return;

if($_REQUEST["src_site"])
{
	$catalogIblockID = \Bitrix\Main\Config\Option::get("aspro.max", "CATALOG_IBLOCK_ID", "", $_REQUEST["src_site"]);
	if($catalogIblockID)
	{
		$arProperty_N = array();
		$arProperty_X = array();
		$arProperty_S = array();
		if (0 < intval($arCurrentValues['IBLOCK_ID']))
		{
			$rsProp = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("IBLOCK_ID"=>$catalogIblockID, "ACTIVE"=>"Y"));
			while ($arr=$rsProp->Fetch())
			{
				if($arr["PROPERTY_TYPE"] != "F")
					$arProperty[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];

				if($arr["PROPERTY_TYPE"]=="N")
					$arProperty_N[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
				if($arr["PROPERTY_TYPE"]=="S")
					$arProperty_S[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];

				if($arr["PROPERTY_TYPE"]!="F")
				{
					if($arr["MULTIPLE"] == "Y" && $arr["PROPERTY_TYPE"] == "L")
						$arProperty_XL[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
					elseif($arr["PROPERTY_TYPE"] == "L")
						$arProperty_X[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
					elseif($arr["PROPERTY_TYPE"] == "E" && $arr["LINK_IBLOCK_ID"] > 0)
						$arProperty_X[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
				}
			}
		}
	}
}


$arPrice = array();
if (\Bitrix\Main\Loader::includeModule('catalog'))
{
	$arPrice = CCatalogIBlockParameters::getPriceTypesList();

	$arStore = array();
	global $USER_FIELD_MANAGER;
	$storeIterator = CCatalogStore::GetList(
		array(),
		array('ISSUING_CENTER' => 'Y'),
		false,
		false,
		array('ID', 'TITLE')
	);
	while ($store = $storeIterator->GetNext())
		$arStore[$store['ID']] = "[".$store['ID']."] ".$store['TITLE'];
}

$arTemplateParameters = array(
	"BANNER_TYPE_THEME_CHILD" => Array(
		"PARENT" => "BASE",
		"NAME" => GetMessage("BANNER_TYPE_THEME_CHILD"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	),
	"SECTION_ID" => Array(
		"PARENT" => "BASE",
		"NAME" => GetMessage("T_SECTION_ID"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	),
	/*"WIDE_BANNER" => Array(
		"NAME" => GetMessage("T_WIDE_BANNER"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	),*/
	"NEWS_COUNT2" => Array(
		"PARENT" => "BASE",
		"NAME" => GetMessage("T_IBLOCK_DESC_LIST_CONT2"),
		"TYPE" => "STRING",
		"DEFAULT" => "20",
	),
	'PRICE_CODE' => array(
		// 'PARENT' => 'DETAIL_SETTINGS',
		'NAME' => GetMessage('PRICE_CODE_TITLE'),
		'TYPE' => 'LIST',
		'MULTIPLE' => 'Y',
		'VALUES' => $arPrice,
	),
	'STORES' => array(
		// 'PARENT' => 'DETAIL_SETTINGS',
		'NAME' => GetMessage('STORES'),
		'TYPE' => 'LIST',
		'MULTIPLE' => 'Y',
		'VALUES' => $arStore,
		'ADDITIONAL_VALUES' => 'Y'
	),
);

if (CModule::IncludeModule('currency'))
{
	$arTemplateParameters['CONVERT_CURRENCY'] = array(
		'PARENT' => 'PRICES',
		'NAME' => GetMessage('CP_BCS_CONVERT_CURRENCY'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
		'REFRESH' => 'Y',
	);

	if (isset($arCurrentValues['CONVERT_CURRENCY']) && 'Y' == $arCurrentValues['CONVERT_CURRENCY'])
	{
		$arCurrencyList = array();
		$rsCurrencies = CCurrency::GetList(($by = 'SORT'), ($order = 'ASC'));
		while ($arCurrency = $rsCurrencies->Fetch())
		{
			$arCurrencyList[$arCurrency['CURRENCY']] = $arCurrency['CURRENCY'];
		}
		$arTemplateParameters['CURRENCY_ID'] = array(
			'PARENT' => 'PRICES',
			'NAME' => GetMessage('CP_BCS_CURRENCY_ID'),
			'TYPE' => 'LIST',
			'VALUES' => $arCurrencyList,
			'DEFAULT' => CCurrency::GetBaseCurrency(),
			"ADDITIONAL_VALUES" => "Y",
		);
	}
}
?>

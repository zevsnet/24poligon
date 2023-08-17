<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Web\Json,
	Bitrix\Main\Loader,
	Bitrix\Main\ModuleManager,
	Bitrix\Iblock,
	Bitrix\Catalog,
	Bitrix\Currency;

//$arGalleryType = array('big' => GetMessage('GALLERY_BIG'), 'small' => GetMessage('GALLERY_SMALL'));

/* get component template pages & params array */
$arPageBlocksParams = array();
if(\Bitrix\Main\Loader::includeModule('aspro.max')){
	$arPageBlocks = CMax::GetComponentTemplatePageBlocks(__DIR__);
	$arPageBlocksParams = CMax::GetComponentTemplatePageBlocksParams($arPageBlocks);
	CMax::AddComponentTemplateModulePageBlocksParams(__DIR__, $arPageBlocksParams); // add option value FROM_MODULE
}

if (!Loader::includeModule('iblock'))
	return;
$catalogIncluded = Loader::includeModule('catalog');

CBitrixComponent::includeComponentClass('bitrix:catalog.section'); 


/*prop file in catalog*/

$catalogID = (intval($arCurrentValues['IBLOCK_CATALOG_ID']) > 0) ? intval($arCurrentValues['IBLOCK_CATALOG_ID']) : '135';
$arIBlocks=Array();
	$db_iblock = CIBlock::GetList(Array("SORT"=>"ASC"), Array("SITE_ID"=>$_REQUEST["site"], "TYPE" => ($arCurrentValues["IBLOCK_CATALOG_TYPE"]!="-"?$arCurrentValues["IBLOCK_CATALOG_TYPE"]:"")));
	while($arRes = $db_iblock->Fetch()) $arIBlocks[$arRes["ID"]] = $arRes["NAME"]." [".$arRes["CODE"]."]";

	$arTypesEx = CIBlockParameters::GetIBlockTypes(Array("-"=>" "));
//$catalogID = \Bitrix\Main\Config\Option::get("aspro.max", "CATALOG_IBLOCK_ID", \Bitrix\Main\Config\Option::get("aspro.max", "CATALOG_IBLOCK_ID", '135'));

$rsProps = CIBlockProperty::GetList(
	array('SORT' => 'ASC', 'ID' => 'ASC'),
	array('IBLOCK_ID' => $catalogID, 'ACTIVE' => 'Y')
);
while ($arProp = $rsProps->Fetch())
{
	$strPropName = '['.$arProp['ID'].']'.('' != $arProp['CODE'] ? '['.$arProp['CODE'].']' : '').' '.$arProp['NAME'];
	if ('' == $arProp['CODE'])
		$arProp['CODE'] = $arProp['ID'];
	$arAllPropList[$arProp['CODE']] = $strPropName;
	if ('F' == $arProp['PROPERTY_TYPE'])
		$arFilePropListCatalog[$arProp['CODE']] = $strPropName;
	if($arProp["PROPERTY_TYPE"]=="S")
		$arProperty_S[$arProp["CODE"]] = $strPropName;
	if($arProp["MULTIPLE"] == "Y" && $arProp["PROPERTY_TYPE"] == "L")
		$arProperty_XL[$arProp["CODE"]] = $strPropName;
}

/*end prop file in catalog*/


$arListView = array(
	'slider' => GetMessage("SLIDER_VIEW"),
	'block' => GetMessage("BLOCK_VIEW"),
);

$arPrice = array();
$arSort = CIBlockParameters::GetElementSortFields(
	array('SHOWS', 'SORT', 'TIMESTAMP_X', 'NAME', 'ID', 'ACTIVE_FROM', 'ACTIVE_TO'),
	array('KEY_LOWERCASE' => 'Y')
);
if (\Bitrix\Main\Loader::includeModule("catalog"))
{
	$arSort = array_merge($arSort, CCatalogIBlockParameters::GetCatalogSortFields(), array("PROPERTY_MINIMUM_PRICE"=>GetMessage("SORT_PRICES_MINIMUM_PRICE"), "PROPERTY_MAXIMUM_PRICE"=>GetMessage("SORT_PRICES_MAXIMUM_PRICE"), "REGION_PRICE"=>GetMessage("SORT_PRICES_REGION_PRICE")));
	if (isset($arSort['CATALOG_AVAILABLE'])) {
		unset($arSort['CATALOG_AVAILABLE']);
	}

	$rsPrice=CCatalogGroup::GetList($v1="sort", $v2="asc");
	while($arr=$rsPrice->Fetch())
	{
		$arPrice[$arr["NAME"]] = "[".$arr["NAME"]."] ".$arr["NAME_LANG"];
	}
	if ((isset($arCurrentValues['IBLOCK_CATALOG_ID']) && (int)$arCurrentValues['IBLOCK_CATALOG_ID']) > 0)
	{
		$arSKU = CCatalogSKU::GetInfoByProductIBlock($arCurrentValues['IBLOCK_CATALOG_ID']);
		$boolSKU = !empty($arSKU) && is_array($arSKU);
	}
}
else
{
	$arPrice = $arProperty_N;
}
$arAscDesc = array(
		"asc" => GetMessage("IBLOCK_SORT_ASC"),
		"desc" => GetMessage("IBLOCK_SORT_DESC"),
	);
$arRegionPrice = $arPrice;
$arPrice  = array_merge(array("MINIMUM_PRICE"=>GetMessage("SORT_PRICES_MINIMUM_PRICE"), "MAXIMUM_PRICE"=>GetMessage("SORT_PRICES_MAXIMUM_PRICE"), "REGION_PRICE"=>GetMessage("SORT_PRICES_REGION_PRICE")), $arPrice);

$arTemplateParameters = array_merge($arPageBlocksParams, array(
	'SHOW_DETAIL_LINK' => array(
		'PARENT' => 'LIST_SETTINGS',
		'NAME' => GetMessage('SHOW_DETAIL_LINK'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	),
	'SHOW_FILTER_DATE' => array(
		'PARENT' => 'LIST_SETTINGS',
		'NAME' => GetMessage('SHOW_FILTER_DATE'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	),
	'IMAGE_POSITION' => array(
		'PARENT' => 'LIST_SETTINGS',
		'SORT' => 250,
		'NAME' => GetMessage('IMAGE_POSITION'),
		'TYPE' => 'LIST',
		'VALUES' => array(
			'left' => GetMessage('IMAGE_POSITION_LEFT'),
			'right' => GetMessage('IMAGE_POSITION_RIGHT'),
		),
		'DEFAULT' => 'left',
	),
	'LINE_ELEMENT_COUNT_LIST' => array(
		'PARENT' => 'LIST_SETTINGS',
		'SORT' => 700,
		'NAME' => GetMessage('T_LINE_ELEMENT_COUNT_LIST'),
		'TYPE' => 'STRING',
		'DEFAULT' => 3,
	),
	'SHOW_MAX_ELEMENT' => array(
		'PARENT' => 'DETAIL_SETTINGS',
		'SORT' => 600,
		'NAME' => GetMessage('T_SHOW_MAX_ELEMENT'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
	),
	'USE_SHARE' => array(
		'PARENT' => 'DETAIL_SETTINGS',
		'SORT' => 600,
		'NAME' => GetMessage('USE_SHARE'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
	),
	/*"LIST_VIEW" => array(
		"NAME" => GetMessage("LIST_VIEW"),
		"TYPE" => "LIST",
		"PARENT" => "DETAIL_SETTINGS",
		"VALUES" => $arListView,
		"ADDITIONAL_VALUES" => "N",
		"DEFAULT" => "slider"
	),*/
	'LINKED_ELEMENST_PAGE_COUNT' => array(
		'SORT' => 704,
		'NAME' => GetMessage('LINKED_ELEMENST_PAGE_COUNT'),
		'TYPE' => 'TEXT',
		"PARENT" => "DETAIL_SETTINGS",
		'DEFAULT' => '20',
	),
	"SHOW_DISCOUNT_PERCENT_NUMBER" => array(
		"PARENT" => "DETAIL_SETTINGS",
		'NAME' => GetMessage('SHOW_DISCOUNT_PERCENT_NUMBER_NAME'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
	),
	'S_ASK_QUESTION' => array(
		'SORT' => 700,
		'NAME' => GetMessage('S_ASK_QUESTION'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'S_ORDER_SERVISE' => array(
		'SORT' => 701,
		'NAME' => GetMessage('S_ORDER_SERVISE'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'FORM_ID_ORDER_SERVISE' => array(
		'SORT' => 701,
		'NAME' => GetMessage('T_FORM_ID_ORDER_SERVISE'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'T_GALLERY' => array(
		'SORT' => 702,
		'NAME' => GetMessage('T_GALLERY'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'T_DOCS' => array(
		'SORT' => 703,
		'NAME' => GetMessage('T_DOCS'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	/*'T_SERVICES' => array(
		'SORT' => 706,
		'NAME' => GetMessage('T_SERVICES'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),*/
	'T_MAX_LINK' => array(
		'SORT' => 707,
		'NAME' => GetMessage('T_MAX_LINK'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'T_PREV_LINK' => array(
		'SORT' => 707,
		'NAME' => GetMessage('T_PREV_LINK'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	"SHOW_ASK_BLOCK" => array(
		"PARENT" => "LIST_SETTINGS",
		'NAME' => GetMessage('T_SHOW_ASK_BLOCK'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
	),
	"SHOW_BORDER_ELEMENT" => array(
		"PARENT" => "LIST_SETTINGS",
		'NAME' => GetMessage('T_SHOW_BORDER_ELEMENT'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
	),
	"USE_BG_IMAGE_ALTERNATE" => Array(
		"PARENT" => "LIST_SETTINGS",
		"NAME" => GetMessage("T_USE_BG_IMAGE_ALTERNATE"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	),
	"BG_POSITION" => Array(
		"PARENT" => "LIST_SETTINGS",
		"NAME" => GetMessage("BG_POSITION_NAME"),
		"TYPE" => "LIST",
		"VALUES" => array(
			"top left" => GetMessage("TOP_LEFT"),
			"top center" => GetMessage("TOP_CENTER"),
			"top right" => GetMessage("TOP_RIGHT"),
			"center left" => GetMessage("CENTER_LEFT"),
			"center" => GetMessage("CENTER_CENTER"),
			"center right" => GetMessage("CENTER_RIGHT"),
			"bottom left" => GetMessage("BOTTOM_LEFT"),
			"bottom center" => GetMessage("BOTTOM_CENTER"),
			"bottom right" => GetMessage("BOTTOM_RIGHT")
		),
		"DEFAULT" => "",
	),
	"TYPE_IMG" => Array(
		"PARENT" => "LIST_SETTINGS",
		"NAME" => GetMessage("TYPE_IMG_NAME"),
		"TYPE" => "LIST",
		"VALUES" => array("md" => GetMessage("MD_IMG"), "lg" => GetMessage("BIG_IMG")),
		"DEFAULT" => "lg",
	),
	"SIZE_IN_ROW" => Array(
		"PARENT" => "LIST_SETTINGS",
		"NAME" => GetMessage("SIZE_IN_ROW_NAME"),
		"TYPE" => "LIST",
		"VALUES" => array( 4 => 4, 3 => 3),
		"DEFAULT" => 4,
	),
	"TITLE_SHOW_FON" => Array(
		"PARENT" => "LIST_SETTINGS",
		"NAME" => GetMessage("TITLE_SHOW_FON_NAME"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
		//'HIDDEN' => ((isset($arCurrentValues['FON_BLOCK_2_COLS']) && $arCurrentValues['FON_BLOCK_2_COLS'] == 'Y') ? 'N' : 'Y'),
	),
	"SIDE_LEFT_BLOCK" => Array(
		"PARENT" => "BASE",
		"NAME" => GetMessage("SIDE_LEFT_BLOCK_NAME"),
		"TYPE" => "LIST",
		"VALUES" => array("LEFT" => GetMessage("T_LEFT"), "RIGHT" => GetMessage("T_RIGHT"), "FROM_MODULE" => GetMessage("FROM_MODULE_PARAMS")),
		"DEFAULT" => "FROM_MODULE",
	),
	"TYPE_LEFT_BLOCK" => Array(
		"PARENT" => "BASE",
		"NAME" => GetMessage("TYPE_LEFT_BLOCK_NAME"),
		"TYPE" => "LIST",
		"VALUES" => array("FROM_MODULE" => GetMessage("FROM_MODULE_PARAMS"),"1" => GetMessage("T_FULL"), "2" => GetMessage("T_TYPE_LEFT_BLOCK_2"), "3" => GetMessage("T_TYPE_LEFT_BLOCK_3"), "4" => GetMessage("T_TYPE_LEFT_BLOCK_4")),
		"DEFAULT" => "FROM_MODULE",
	),
	"SIDE_LEFT_BLOCK_DETAIL" => Array(
		"PARENT" => "BASE",
		"NAME" => GetMessage("SIDE_LEFT_BLOCK_DETAIL_NAME"),
		"TYPE" => "LIST",
		"VALUES" => array("LEFT" => GetMessage("T_LEFT"), "RIGHT" => GetMessage("T_RIGHT"), "FROM_MODULE" => GetMessage("FROM_MODULE_PARAMS")),
		"DEFAULT" => "FROM_MODULE",
	),
	"TYPE_LEFT_BLOCK_DETAIL" => Array(
		"PARENT" => "BASE",
		"NAME" => GetMessage("TYPE_LEFT_BLOCK_DETAIL_NAME"),
		"TYPE" => "LIST",
		"VALUES" => array("FROM_MODULE" => GetMessage("FROM_MODULE_PARAMS"),"1" => GetMessage("T_FULL"), "2" => GetMessage("T_TYPE_LEFT_BLOCK_2"), "3" => GetMessage("T_TYPE_LEFT_BLOCK_3"), "4" => GetMessage("T_TYPE_LEFT_BLOCK_4")),
		"DEFAULT" => "FROM_MODULE",
	),
	"GALLERY_TYPE" => Array(
		"PARENT" => "DETAIL_SETTINGS",
		"NAME" => GetMessage("GALLERY_TYPE"),
		"TYPE" => "LIST",
		"VALUES" => array("small" => GetMessage("GALLERY_SMALL"),"big" => GetMessage("GALLERY_BIG")),
		"DEFAULT" => "small",
	),
	"STAFF_TYPE_DETAIL" => Array(
		"PARENT" => "DETAIL_SETTINGS",
		"NAME" => GetMessage("STAFF_TYPE_DETAIL_NAME"),
		"TYPE" => "LIST",
		"VALUES" => array( 'list' => GetMessage('T_LIST'), 'block' => GetMessage('T_BLOCK')),
		"DEFAULT" => 'list',
	),
	"IBLOCK_LINK_NEWS_ID" => Array( 
            "NAME" => GetMessage("IBLOCK_LINK_NEWS_NAME"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "", 
        ), 
        "IBLOCK_LINK_SERVICES_ID" => Array( 
            "NAME" => GetMessage("IBLOCK_LINK_SERVICES_NAME"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "", 
        ), 
        "IBLOCK_LINK_TIZERS_ID" => Array( 
            "NAME" => GetMessage("IBLOCK_LINK_TIZERS_NAME"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "", 
        ),  
        "IBLOCK_LINK_REVIEWS_ID" => Array( 
            "NAME" => GetMessage("IBLOCK_LINK_REVIEWS_NAME"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "", 
        ),
	"IBLOCK_LINK_STAFF_ID" => Array( 
            "NAME" => GetMessage("IBLOCK_LINK_STAFF_NAME"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "", 
        ),
	"IBLOCK_LINK_VACANCY_ID" => Array( 
            "NAME" => GetMessage("IBLOCK_LINK_VACANCY_NAME"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "", 
        ),
	"IBLOCK_LINK_BLOG_ID" => Array( 
            "NAME" => GetMessage("IBLOCK_LINK_BLOG_NAME"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "", 
        ),
	"IBLOCK_LINK_PROJECTS_ID" => Array( 
            "NAME" => GetMessage("IBLOCK_LINK_PROJECTS_NAME"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "", 
        ),
	"IBLOCK_LINK_BRANDS_ID" => Array( 
            "NAME" => GetMessage("IBLOCK_LINK_BRANDS_NAME"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "", 
        ),
	"IBLOCK_LINK_LANDINGS_ID" => Array( 
            "NAME" => GetMessage("IBLOCK_LINK_LANDINGS_NAME"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "", 
        ),
	"BLOCK_SERVICES_NAME" => Array( 
            "NAME" => GetMessage("BLOCK_SERVICES_NAME_TITLE"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "",
        ),
	"BLOCK_NEWS_NAME" => Array( 
            "NAME" => GetMessage("BLOCK_NEWS_NAME_TITLE"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "",
        ),
	"BLOCK_TIZERS_NAME" => Array( 
            "NAME" => GetMessage("BLOCK_TIZERS_NAME_TITLE"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "",
        ),
	"BLOCK_REVIEWS_NAME" => Array( 
            "NAME" => GetMessage("BLOCK_REVIEWS_NAME_TITLE"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "",
        ),
	"BLOCK_STAFF_NAME" => Array( 
            "NAME" => GetMessage("BLOCK_STAFF_NAME_TITLE"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "",
        ),
	"BLOCK_VACANCY_NAME" => Array( 
            "NAME" => GetMessage("BLOCK_VACANCY_NAME_TITLE"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "",
        ),
	"BLOCK_PROJECTS_NAME" => Array( 
            "NAME" => GetMessage("BLOCK_PROJECTS_NAME_TITLE"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "",
        ),
	"BLOCK_BRANDS_NAME" => Array( 
            "NAME" => GetMessage("BLOCK_BRANDS_NAME_TITLE"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "",
        ),
	"BLOCK_BLOG_NAME" => Array( 
            "NAME" => GetMessage("BLOCK_BLOG_NAME_TITLE"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "",
        ),
	"BLOCK_LANDINGS_NAME" => Array( 
            "NAME" => GetMessage("BLOCK_LANDINGS_NAME_TITLE"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "",
        ),
	'T_GOODS' => array(
		'SORT' => 704,
		'NAME' => GetMessage('T_GOODS'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	"DETAIL_LINKED_GOODS_SLIDER" => Array( 
		"NAME" => GetMessage("DETAIL_LINKED_GOODS_SLIDER_TITLE"), 
		"TYPE" => "CHECKBOX", 
		"DEFAULT" => "Y", 
		"PARENT" => "DETAIL_SETTINGS", 
	),
	"IBLOCK_LINK_PARTNERS_ID" => Array( 
            "NAME" => GetMessage("IBLOCK_LINK_PARTNERS_NAME"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "", 
        ),
	"BLOCK_PARTNERS_NAME" => Array( 
            "NAME" => GetMessage("BLOCK_PARTNERS_NAME_TITLE"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "",
        ),
	"TAGS_SECTION_COUNT" => Array( 
            "NAME" => GetMessage("TAGS_SECTION_COUNT_TITLE"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "",
            "PARENT" => "DETAIL_SETTINGS",
        ),
	"DISPLAY_LINKED_PAGER" => Array( 
            "NAME" => GetMessage("DISPLAY_LINKED_PAGER_TITLE"), 
            "TYPE" => "CHECKBOX", 
			"DEFAULT" => "Y",
			"PARENT" => "DETAIL_SETTINGS",
        ),
	"MAX_GALLERY_ITEMS" => Array(
			"NAME" => GetMessage("MAX_GALLERY_ITEMS_NAME"),
			"TYPE" => "DETAIL_SETTINGS",
			"VALUES" => array(2=>2, 3=>3, 4=>4, 5=>5),
			"DEFAULT" => "5",
			"HIDDEN" => ($arCurrentValues["SHOW_GALLERY_GOODS"] == "Y" ? "N" : "Y"),
			"PARENT" => "DETAIL_SETTINGS",
		),
	"SHOW_GALLERY_GOODS" => Array(
			"NAME" => GetMessage("SHOW_GALLERY_NAME"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
			"REFRESH" => "Y",
			"PARENT" => "DETAIL_SETTINGS",
		),
	'HIDE_NOT_AVAILABLE' => array(
			'PARENT' => 'DETAIL_SETTINGS',
			'NAME' => GetMessage('CP_BC_HIDE_NOT_AVAILABLE'),
			'TYPE' => 'LIST',
			'DEFAULT' => 'N',
			'VALUES' => array(
				'Y' => GetMessage('CP_BC_HIDE_NOT_AVAILABLE_HIDE'),
				'L' => GetMessage('CP_BC_HIDE_NOT_AVAILABLE_LAST'),
				'N' => GetMessage('CP_BC_HIDE_NOT_AVAILABLE_SHOW')
			),
			'ADDITIONAL_VALUES' => 'N'
		),
	'HIDE_NOT_AVAILABLE_OFFERS' => array(
			'PARENT' => 'DETAIL_SETTINGS',
			'NAME' => GetMessage('CP_BC_HIDE_NOT_AVAILABLE_OFFERS'),
			'TYPE' => 'LIST',
			'DEFAULT' => 'N',
			'VALUES' => array(
				'Y' => GetMessage('CP_BC_HIDE_NOT_AVAILABLE_OFFERS_HIDE'),
				'L' => GetMessage('CP_BC_HIDE_NOT_AVAILABLE_OFFERS_SUBSCRIBE'),
				'N' => GetMessage('CP_BC_HIDE_NOT_AVAILABLE_OFFERS_SHOW')
			)
		),
	'CONVERT_CURRENCY' => array(
			'PARENT' => 'DETAIL_SETTINGS',
			'NAME' => GetMessage('CP_BC_CONVERT_CURRENCY'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N',
			'REFRESH' => 'Y',
	),
	'CURRENCY_ID' => array(
			'PARENT' => 'DETAIL_SETTINGS',
			'NAME' => GetMessage('CP_BC_CURRENCY_ID'),
			'TYPE' => 'LIST',
			'VALUES' => Currency\CurrencyManager::getCurrencyList(),
			'DEFAULT' => Currency\CurrencyManager::getBaseCurrency(),
			"ADDITIONAL_VALUES" => "Y",
			"HIDDEN" => (isset($arCurrentValues['CONVERT_CURRENCY']) && $arCurrentValues['CONVERT_CURRENCY'] == 'Y' ? "N" : "Y"),
		),
	"ADD_DETAIL_TO_SLIDER" => Array(
		"NAME" => GetMessage("ADD_DETAIL_TO_SLIDER_NAME"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
		"PARENT" => "DETAIL_SETTINGS",
		),
	"SHOW_MEASURE" => Array(
				"NAME" => GetMessage("SHOW_MEASURE"),
				"TYPE" => "CHECKBOX",
				"DEFAULT" => "N",
		),
	"SHOW_DISCOUNT_PERCENT_NUMBER" => array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('CP_BC_TPL_SHOW_DISCOUNT_PERCENT_NUMBER'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N',
		),
	"SHOW_DISCOUNT_PERCENT" => array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('CP_BC_TPL_SHOW_DISCOUNT_PERCENT'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N',
		),
	"SHOW_DISCOUNT_TIME" => Array(
			'PARENT' => 'VISUAL',
			"NAME" => GetMessage("SHOW_DISCOUNT_TIME"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
	"SHOW_ONE_CLICK_BUY" => Array(
			"NAME" => GetMessage("SHOW_ONE_CLICK_BUY"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
			"REFRESH" => "N",
			"PARENT" => "DETAIL_SETTINGS",
		),
	"SHOW_OLD_PRICE" => array(
			'PARENT' => 'DETAIL_SETTINGS',
			'NAME' => GetMessage('CP_BC_TPL_SHOW_OLD_PRICE'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N',
		),
	"SHOW_RATING" => Array(
			'PARENT' => 'DETAIL_SETTINGS',
			"NAME" => GetMessage("SHOW_RATING"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
	"DISPLAY_WISH_BUTTONS" => array(
			"NAME" => GetMessage("DISPLAY_WISH_BUTTONS"),
			"TYPE" => "CHECKBOX",
			"MULTIPLE" => "N",
			"ADDITIONAL_VALUES" => "N",
			"DEFAULT" => "Y",
			'PARENT' => 'DETAIL_SETTINGS',
		),
	"STIKERS_PROP" => array(
			"PARENT" => "DETAIL_SETTINGS",
			"NAME" => GetMessage("STIKERS_PROP_TITLE"),
			"TYPE" => "LIST",
			"DEFAULT" => "-",
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => array_merge(Array("-"=>" "), $arProperty_XL),
		),
	"SALE_STIKER" =>array(
			"PARENT" => "DETAIL_SETTINGS",
			"NAME" => GetMessage("SALE_STIKER"),
			"TYPE" => "LIST",
			"DEFAULT" => "-",
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => array_merge(Array("-"=>" "), $arProperty_S),
		),
	"SHOW_SECTIONS_FILTER" => array(
			"NAME" => GetMessage("SHOW_SECTIONS_FILTER"),
			"TYPE" => "CHECKBOX",
			"MULTIPLE" => "N",
			"ADDITIONAL_VALUES" => "N",
			"DEFAULT" => "Y",
			"PARENT" => "DETAIL_SETTINGS",
		),
	"LINKED_PROPERTY_CODE" => array(
			"PARENT" => "DETAIL_SETTINGS",
			"NAME" => GetMessage("LINKED_PROPERTY_CODE_TITLE"),
			"TYPE" => "LIST",
			'MULTIPLE' => 'Y',
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => $arAllPropList,
		),
	"SHOW_COUNT_ELEMENTS" => Array( 
		"NAME" => GetMessage("SHOW_COUNT_ELEMENTS_TITLE"), 
		"TYPE" => "CHECKBOX", 
		"DEFAULT" => "Y", 
		"PARENT" => "DETAIL_SETTINGS", 
	),
	"LINKED_ELEMENT_TAB_SORT_FIELD" => array(
		"PARENT" => "DETAIL_SETTINGS",
		"NAME" => GetMessage("LINKED_ELEMENT_TAB_SORT_FIELD"),
		"TYPE" => "LIST",
		"VALUES" => $arSort,
		"ADDITIONAL_VALUES" => "Y",
		"DEFAULT" => "sort",
	),
	"LINKED_ELEMENT_TAB_SORT_ORDER" => array(
		"PARENT" => "DETAIL_SETTINGS",
		"NAME" => GetMessage("LINKED_ELEMENT_TAB_SORT_ORDER"),
		"TYPE" => "LIST",
		"VALUES" => $arAscDesc,
		"DEFAULT" => "asc",
		"ADDITIONAL_VALUES" => "Y",
	),
	"LINKED_ELEMENT_TAB_SORT_FIELD2" => array(
		"PARENT" => "DETAIL_SETTINGS",
		"NAME" => GetMessage("LINKED_ELEMENT_TAB_SORT_FIELD2"),
		"TYPE" => "LIST",
		"VALUES" => $arSort,
		"ADDITIONAL_VALUES" => "Y",
		"DEFAULT" => "id",
	),
	"LINKED_ELEMENT_TAB_SORT_ORDER2" => array(
		"PARENT" => "DETAIL_SETTINGS",
		"NAME" => GetMessage("LINKED_ELEMENT_TAB_SORT_ORDER2"),
		"TYPE" => "LIST",
		"VALUES" => $arAscDesc,
		"DEFAULT" => "desc",
		"ADDITIONAL_VALUES" => "Y",
	),
	
));


$arTemplateParameters["SORT_REGION_PRICE"] = Array(
	"SORT"=>200,
	"NAME" => GetMessage("SORT_REGION_PRICE"),
	"TYPE" => "LIST",
	"VALUES" => $arRegionPrice,
	"DEFAULT" => array("BASE"),
	"PARENT" => "DETAIL_SETTINGS",
	"MULTIPLE" => "N",
);

$arTemplateParameters['SECTIONS_TAGS_DEPTH_LEVEL'] = array(
	'NAME' => GetMessage('SECTIONS_TAGS_DEPTH_LEVEL'),
	'SORT' => 709,
	'TYPE' => 'TEXT',
	'PARENT' => 'DETAIL_SETTINGS',
	'DEFAULT' => '2'
);

$arTemplateParameters['ADD_PICT_PROP'] = array(
		'PARENT' => 'DETAIL_SETTINGS',
		'NAME' => GetMessage('CP_BC_TPL_ADD_PICT_PROP'),
		'TYPE' => 'LIST',
		'MULTIPLE' => 'N',
		'ADDITIONAL_VALUES' => 'N',
		'REFRESH' => 'N',
		'DEFAULT' => '-',
		'VALUES' => $arFilePropListCatalog
);

$arTemplateParameters["IBLOCK_CATALOG_TYPE"] = Array(
	"SORT"=>200,
	"NAME" => GetMessage("IBLOCK_CATALOG_TYPE"),
	"TYPE" => "LIST",
	"VALUES" => $arTypesEx,
	"PARENT" => "DETAIL_SETTINGS",
	"MULTIPLE" => "N",
	"REFRESH" => "Y",
);

$arTemplateParameters["IBLOCK_CATALOG_ID"] = Array(
		"SORT"=>200,
		"NAME" => GetMessage("IBLOCK_CATALOG_ID"),
		"TYPE" => "LIST",
		"VALUES" => $arIBlocks,
		"PARENT" => "DETAIL_SETTINGS",
		"MULTIPLE" => "N",
		"REFRESH" => "Y",
	);

$arTemplateParameters['DETAIL_BLOCKS_ALL_ORDER'] = array(  
	'PARENT' => 'DETAIL_SETTINGS', 
	'NAME' => GetMessage('CP_BC_TPL_CONTENT_BLOCKS_ALL_ORDER'), 
	'TYPE' => 'CUSTOM', 
	'JS_FILE' => CatalogSectionComponent::getSettingsScript('/bitrix/components/bitrix/catalog.section', 'dragdrop_order'), 
	'JS_EVENT' => 'initDraggableOrderControl', 
	'JS_DATA' => Json::encode(array( 
		'docs' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_DOCS'), 
		'brands' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_BRANDS'), 
		'gallery' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_GALLERY'), 
		'desc' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_DESC'), 
		'char' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_CHAR'), 
		'tizers' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_TIZERS'),  
		'services' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_SERVICES'), 
		'news' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_NEWS'), 
		'blog' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_BLOG'), 
		'vacancy' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_VACANCY'), 
		'reviews' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_REVIEWS'),
		'comments' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_COMMENTS'), 
		'goods' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_GOODS'), 
		'projects' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_PROJECTS'), 
		'landings' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_LANDINGS'),
		'partners' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_PARTNERS'),
		'form_order' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_FORM_ORDER'),
		'staff' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_STAFF'), 
	)), 
	'DEFAULT' => 'tizers,desc,goods,docs,gallery,form_order,news,services,reviews,projects,staff,brands,char,blog,vacancy,landings,comments,partners'  
);

$arTemplateParameters['DETAIL_USE_COMMENTS'] = array(
	'PARENT' => 'DETAIL_SETTINGS',
	'NAME' => GetMessage('CP_BC_TPL_DETAIL_USE_COMMENTS'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'Y',
	'REFRESH' => 'Y'
);

$arPrice = array();
if (\Bitrix\Main\Loader::includeModule('catalog'))
{
	$arPrice = CCatalogIBlockParameters::getPriceTypesList();
	$arTemplateParameters['PRICE_CODE'] = array(
		'PARENT' => 'DETAIL_SETTINGS',
		'NAME' => GetMessage('PRICE_CODE_TITLE'),
		'TYPE' => 'LIST',
		'MULTIPLE' => 'Y',
		'VALUES' => $arPrice,
	);
	$arTemplateParameters['PRICE_VAT_INCLUDE'] = array(
		"PARENT" => "DETAIL_SETTINGS",
		"NAME" => GetMessage("IBLOCK_PRICE_VAT_INCLUDE"),
		"TYPE" => "CHECKBOX",
		"REFRESH" => "N",
		"DEFAULT" => "Y",
	);

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

	$userFields = $USER_FIELD_MANAGER->GetUserFields("CAT_STORE", 0, LANGUAGE_ID);
	$propertyUF = array();

	foreach($userFields as $fieldName => $userField)
		$propertyUF[$fieldName] = $userField["LIST_COLUMN_LABEL"] ? $userField["LIST_COLUMN_LABEL"] : $fieldName;

	$arTemplateParameters['STORES'] = array(
		'PARENT' => 'DETAIL_SETTINGS',
		'NAME' => GetMessage('STORES'),
		'TYPE' => 'LIST',
		'MULTIPLE' => 'Y',
		'VALUES' => $arStore,
		'ADDITIONAL_VALUES' => 'Y'
	);
	/*$arTemplateParameters['HIDE_NOT_AVAILABLE'] = array(
		'PARENT' => 'DETAIL_SETTINGS',
		'NAME' => GetMessage('T_HIDE_NOT_AVAILABLE'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
	);*/
}

if ('N' != $arCurrentValues['DETAIL_USE_COMMENTS'])
{
	if (\Bitrix\Main\ModuleManager::isModuleInstalled("blog"))
	{
		$arTemplateParameters['DETAIL_BLOG_USE'] = array(
			'PARENT' => 'DETAIL_SETTINGS',
			'NAME' => GetMessage('CP_BC_TPL_DETAIL_BLOG_USE'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N',
			'REFRESH' => 'Y'
		);
		if (isset($arCurrentValues['DETAIL_BLOG_USE']) && $arCurrentValues['DETAIL_BLOG_USE'] == 'Y')
		{
			$arTemplateParameters['DETAIL_BLOG_URL'] = array(
				'PARENT' => 'DETAIL_SETTINGS',
				'NAME' => GetMessage('CP_BC_DETAIL_TPL_BLOG_URL'),
				'TYPE' => 'STRING',
				'DEFAULT' => 'catalog_comments'
			);
			$arTemplateParameters['COMMENTS_COUNT'] = array(
				'PARENT' => 'DETAIL_SETTINGS',
				'NAME' => GetMessage('T_COMMENTS_COUNT'),
				'TYPE' => 'STRING',
				'DEFAULT' => '5'
			);
			$arTemplateParameters['BLOG_TITLE'] = array(
				'PARENT' => 'DETAIL_SETTINGS',
				'NAME' => GetMessage('BLOCK_TITLE_TAB'),
				'TYPE' => 'STRING',
				'DEFAULT' => GetMessage('S_COMMENTS_VALUE')
			);
			$arTemplateParameters['DETAIL_BLOG_EMAIL_NOTIFY'] = array(
				'PARENT' => 'DETAIL_SETTINGS',
				'NAME' => GetMessage('CP_BC_TPL_DETAIL_BLOG_EMAIL_NOTIFY'),
				'TYPE' => 'CHECKBOX',
				'DEFAULT' => 'N'
			);
		}
	}

	$boolRus = false;
	$langBy = "id";
	$langOrder = "asc";
	$rsLangs = CLanguage::GetList($langBy, $langOrder, array('ID' => 'ru',"ACTIVE" => "Y"));
	if ($arLang = $rsLangs->Fetch())
	{
		$boolRus = true;
	}

	if ($boolRus)
	{
		$arTemplateParameters['DETAIL_VK_USE'] = array(
			'PARENT' => 'DETAIL_SETTINGS',
			'NAME' => GetMessage('CP_BC_TPL_DETAIL_VK_USE'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N',
			'REFRESH' => 'Y'
		);

		if (isset($arCurrentValues['DETAIL_VK_USE']) && 'Y' == $arCurrentValues['DETAIL_VK_USE'])
		{
			$arTemplateParameters['VK_TITLE'] = array(
				'PARENT' => 'DETAIL_SETTINGS',
				'NAME' => GetMessage('BLOCK_TITLE_TAB'),
				'TYPE' => 'STRING',
				'DEFAULT' => GetMessage('S_VK_VALUE')
			);
			$arTemplateParameters['DETAIL_VK_API_ID'] = array(
				'PARENT' => 'DETAIL_SETTINGS',
				'NAME' => GetMessage('CP_BC_TPL_DETAIL_VK_API_ID'),
				'TYPE' => 'STRING',
				'DEFAULT' => 'API_ID'
			);
		}
	}

	$arTemplateParameters['DETAIL_FB_USE'] = array(
		'PARENT' => 'DETAIL_SETTINGS',
		'NAME' => GetMessage('CP_BC_TPL_DETAIL_FB_USE'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
		'REFRESH' => 'Y'
	);

	if (isset($arCurrentValues['DETAIL_FB_USE']) && 'Y' == $arCurrentValues['DETAIL_FB_USE'])
	{
		$arTemplateParameters['FB_TITLE'] = array(
			'PARENT' => 'DETAIL_SETTINGS',
			'NAME' => GetMessage('BLOCK_TITLE_TAB'),
			'TYPE' => 'STRING',
			'DEFAULT' => GetMessage('S_FB_VALUE')
		);
		$arTemplateParameters['DETAIL_FB_APP_ID'] = array(
			'PARENT' => 'DETAIL_SETTINGS',
			'NAME' => GetMessage('CP_BC_TPL_DETAIL_FB_APP_ID'),
			'TYPE' => 'STRING',
			'DEFAULT' => ''
		);
	}
}

?>
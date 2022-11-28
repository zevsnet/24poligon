<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$APPLICATION->IncludeComponent(
	"aspro:catalog.delivery.max",
	".default",
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"SET_PAGE_TITLE" => "Y",
		"DELIVERY_NO_SESSION" => "Y",
		"DELIVERY_WITHOUT_PAY_SYSTEM" => "Y",
		"PAY_FROM_ACCOUNT" => "N",
		"SPOT_LOCATION_BY_GEOIP" => "Y",
		"USE_LAST_ORDER_DATA" => "Y",
		"USE_PROFILE_LOCATION" => "N",
		"SAVE_IN_SESSION" => "Y",
		"CALCULATE_EACH_DELIVERY_WITH_EACH_PAYSYSTEM" => "N",
		"SHOW_LOCATION_SOURCE" => "N",
		"CHANGEABLE_FIELDS" => array(
			0 => "LOCATION",
			1 => "QUANTITY",
			2 => "ADD_BASKET",
		),
		"SHOW_DELIVERY_PARENT_NAMES" => "Y",
		"SHOW_MESSAGE_ON_CALCULATE_ERROR" => "Y",
		"PREVIEW_SHOW_DELIVERY_PARENT_ID" => array(
			0 => "2",
			1 => "3",
			2 => "9",
		),
		"PRODUCT_ID" => $productId,
		"PRODUCT_QUANTITY" => $quantity,
		"LOCATION_CODE" => "",
		"USER_PROFILE_ID" => "",
		"PERSON_TYPE_ID" => "",
		"PAY_SYSTEM_ID" => "",
		"DELIVERY_ID" => "",
		"ADD_BASKET" => "N",
		"BUYER_STORE_ID" => "",
		"USE_CUSTOM_MESSAGES" => "N",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>
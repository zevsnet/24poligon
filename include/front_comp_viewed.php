<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
global $TEMPLATE_OPTIONS;
$IsViewedTypeLocal = $TEMPLATE_OPTIONS['VIEWED_TYPE']['CURRENT_VALUE'] === 'LOCAL';
$arViewedIDs = CMShop::getViewedProducts();
?>
<?if($arViewedIDs):?>
	<div class="similar_products_wrapp">
		<?if(!$IsViewedTypeLocal):?>
			<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.viewed.products", 
	"main", 
	array(
		"COMPONENT_TEMPLATE" => "main",
		"IBLOCK_TYPE" => "aspro_mshop_catalog",
		"IBLOCK_ID" => "95",
		"SHOW_FROM_SECTION" => "N",
		"SECTION_ID" => "",
		"SECTION_CODE" => "",
		"SECTION_ELEMENT_ID" => "",
		"SECTION_ELEMENT_CODE" => "",
		"DEPTH" => "",
		"HIDE_NOT_AVAILABLE" => "N",
		"SHOW_DISCOUNT_PERCENT" => "Y",
		"PRODUCT_SUBSCRIPTION" => "N",
		"SHOW_NAME" => "Y",
		"SHOW_IMAGE" => "Y",
		"MESS_BTN_BUY" => "",
		"MESS_BTN_DETAIL" => "",
		"MESS_BTN_SUBSCRIBE" => "",
		"PAGE_ELEMENT_COUNT" => "10",
		"LINE_ELEMENT_COUNT" => "5",
		"TEMPLATE_THEME" => "",
		"DETAIL_URL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "N",
		"SHOW_OLD_PRICE" => "Y",
		"PRICE_CODE" => array(
			0 => "Розничная цена",
		),
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"CONVERT_CURRENCY" => "N",
		"BASKET_URL" => "/basket/",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_QUANTITY_VARIABLE" => "",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"SHOW_PRODUCTS_95" => "Y",
		"PROPERTY_CODE_95" => array(
			0 => "HIT",
			1 => "",
		),
		"CART_PROPERTIES_95" => array(
			0 => "",
			1 => "",
		),
		"ADDITIONAL_PICT_PROP_95" => "MORE_PHOTO",
		"LABEL_PROP_95" => "-",
		"TITLE_BLOCK" => GetMessage("VIEWED_BEFORE"),
		"DISPLAY_WISH_BUTTONS" => "Y",
		"DISPLAY_COMPARE" => "Y",
		"PROPERTY_CODE_96" => array(
			0 => "",
			1 => "",
		),
		"CART_PROPERTIES_96" => array(
			0 => "",
			1 => "",
		),
		"ADDITIONAL_PICT_PROP_96" => "MORE_PHOTO",
		"OFFER_TREE_PROPS_96" => array(
			0 => "-",
		),
		"SHOW_MEASURE" => "Y",
		"PROPERTY_CODE_100" => array(
			0 => "",
			1 => "",
		),
		"CART_PROPERTIES_100" => array(
			0 => "",
			1 => "",
		),
		"ADDITIONAL_PICT_PROP_100" => "MORE_PHOTO",
		"OFFER_TREE_PROPS_100" => array(
		)
	),
	false
);?>
		<?else:?>
			<?$APPLICATION->IncludeComponent(
				"aspro:catalog.viewed.market",
				"slider",
				array(
					"TITLE_BLOCK" => GetMessage('VIEWED_BEFORE'),
					"VIEW_TYPE_IN_TAB" => "N",
					"SHOW_OLD_PRICE" => "Y",
					"DISPLAY_WISH_BUTTONS" => "Y",
					"DISPLAY_COMPARE" => "Y",
					"SHOW_MEASURE" => "Y",
					"SHOW_DISCOUNT_PERCENT" => "Y",
				),
				false
			);?>
		<?endif;?>
	</div>
<?endif;?>
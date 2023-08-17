<?
use Bitrix\Main\Loader;

include_once('const.php');
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

Loader::includeModule('aspro.max');

$context = \Bitrix\Main\Application::getInstance()->getContext();
$request = $context->getRequest();

if ($request["CLEAR_ALL"] === "Y") {
	Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("basket-allitems-block");
	Loader::includeModule('sale');
	
	$type = "BASKET";
	if ($request["TYPE"]) {
		switch ($request["TYPE"]) {
			case 2:
				$type="DELAY";
				break;
			case 3:
				$type="SUBSCRIBE";
				break;
			case 4:
				$type="NOT_AVAILABLE";
				break;			
			default:
				
				break;
		}
	}

	$arItems = CMax::getBasketItems($iblockID, "ID");
	if ($request["TYPE"] == "all" || $request["CLEAR_ALL"] == "Y")
	{
		foreach ($arItems as $key => $arItem)
		{
			
			foreach ($arItem as $id){
				if ($key === 'SERVICES') {
					CSaleBasket::Delete($id["item_id"]);
				}
				else {
					CSaleBasket::Delete($id);
				}
			}
		}
	}
	else
	{
		foreach ($arItems[$type] as $id)
		{
			CSaleBasket::Delete($id);
		}
	}

	Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("basket-allitems-block", "");
}
elseif ($request["delete_top_item"]=="Y") {
	Loader::includeModule('sale');
	CSaleBasket::Delete($request["delete_top_item_id"]);
}

CMaxCache::ClearCacheByTag('sale_basket');
CMax::clearBasketCounters();


<?
use Bitrix\Main\Localization\Loc,
    Bitrix\Main\Web\Json,
	Bitrix\Main\SystemException,
	Bitrix\Main\Loader,
    Aspro\Max\Product\Quantity;

include_once('const.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

$arResult = [
	'success' => true,
	'error' => '',
    'amount' => '',
];

try {
    if (!Loader::includeModule(PUBLIC_VENDOR_MODULE_ID)) {
		throw new SystemException('Error include module '.PUBLIC_VENDOR_MODULE_ID);
	}
    
    $context = Bitrix\Main\Application::getInstance()->getContext();
    $request = $context->getRequest();
    $post = $request->getPostList();
    
    $ids = is_array($request->get('ids')) && $request->get('ids') ? $request->get('ids') : [];
    $stores = is_array($request->get('stores')) && $request->get('stores') ? $request->get('stores') : [];

    $arResult['amount'] = Quantity::getStoresAmount($ids, $stores);
}
catch (SystemException $e) {
	$arResult['error'] = $e->getMessage();
	$arResult['success'] = false;
}

die(Json::encode($arResult));

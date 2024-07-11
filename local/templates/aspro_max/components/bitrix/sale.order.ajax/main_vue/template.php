<?
use Bitrix\Main, Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Basket;
use Bitrix\Sale\Fuser;
use Bitrix\Sale\Internals\BasketTable;

if($_REQUEST['DEV'] == 'Y'){
    include $_SERVER['DOCUMENT_ROOT'] . '/local/webpack/dist/index.php';
}else{
    include $_SERVER['DOCUMENT_ROOT'] . '/local/webpack/build/index.php';
}

$context = Main\Application::getInstance()->getContext();
$request = $context->getRequest();
$server = $context->getServer();
# Если передан ИД заказа, открываем страницу confirm
if (strlen($request->get('ORDER_ID')) > 0 || $_REQUEST['ORDER_ID']) {
    include($server->getDocumentRoot() . $templateFolder . '/confirm.php');
    return;
}

if (true) {

    if ($_REQUEST['DELIVERY']) {
    } else {
        $_REQUEST['DELIVERY'] = 'Y';
    }
    $arParams['ALLOW_USER_PROFILES'] = $arParams['ALLOW_USER_PROFILES'] === 'Y' ? 'Y' : 'N';
    $arParams['SKIP_USELESS_BLOCK'] = $arParams['SKIP_USELESS_BLOCK'] === 'N' ? 'N' : 'Y';

    if (!isset($arParams['SHOW_ORDER_BUTTON'])) {
        $arParams['SHOW_ORDER_BUTTON'] = 'final_step';
    }

    $arParams['SHOW_TOTAL_ORDER_BUTTON'] = $arParams['SHOW_TOTAL_ORDER_BUTTON'] === 'Y' ? 'Y' : 'N';
    $arParams['SHOW_PAY_SYSTEM_LIST_NAMES'] = $arParams['SHOW_PAY_SYSTEM_LIST_NAMES'] === 'N' ? 'N' : 'Y';
    $arParams['SHOW_PAY_SYSTEM_INFO_NAME'] = $arParams['SHOW_PAY_SYSTEM_INFO_NAME'] === 'N' ? 'N' : 'Y';
    $arParams['SHOW_DELIVERY_LIST_NAMES'] = $arParams['SHOW_DELIVERY_LIST_NAMES'] === 'N' ? 'N' : 'Y';
    $arParams['SHOW_DELIVERY_INFO_NAME'] = $arParams['SHOW_DELIVERY_INFO_NAME'] === 'N' ? 'N' : 'Y';
    $arParams['SHOW_DELIVERY_PARENT_NAMES'] = $arParams['SHOW_DELIVERY_PARENT_NAMES'] === 'N' ? 'N' : 'Y';
    $arParams['SHOW_STORES_IMAGES'] = $arParams['SHOW_STORES_IMAGES'] === 'N' ? 'N' : 'Y';

    if (!isset($arParams['BASKET_POSITION'])) {
        $arParams['BASKET_POSITION'] = 'after';
    }

    $arParams['SHOW_BASKET_HEADERS'] = $arParams['SHOW_BASKET_HEADERS'] === 'Y' ? 'Y' : 'N';
    $arParams['DELIVERY_FADE_EXTRA_SERVICES'] = $arParams['DELIVERY_FADE_EXTRA_SERVICES'] === 'Y' ? 'Y' : 'N';
    $arParams['SHOW_COUPONS_BASKET'] = $arParams['SHOW_COUPONS_BASKET'] === 'N' ? 'N' : 'Y';
    $arParams['SHOW_COUPONS_DELIVERY'] = $arParams['SHOW_COUPONS_DELIVERY'] === 'N' ? 'N' : 'Y';
    $arParams['SHOW_COUPONS_PAY_SYSTEM'] = $arParams['SHOW_COUPONS_PAY_SYSTEM'] === 'Y' ? 'Y' : 'N';
    $arParams['SHOW_NEAREST_PICKUP'] = $arParams['SHOW_NEAREST_PICKUP'] === 'Y' ? 'Y' : 'N';
    $arParams['DELIVERIES_PER_PAGE'] = isset($arParams['DELIVERIES_PER_PAGE']) ? (int)$arParams['DELIVERIES_PER_PAGE'] : 9;
    $arParams['PAY_SYSTEMS_PER_PAGE'] = isset($arParams['PAY_SYSTEMS_PER_PAGE']) ? (int)$arParams['PAY_SYSTEMS_PER_PAGE'] : 9;
    $arParams['PICKUPS_PER_PAGE'] = isset($arParams['PICKUPS_PER_PAGE']) ? (int)$arParams['PICKUPS_PER_PAGE'] : 5;
    $arParams['SHOW_PICKUP_MAP'] = $arParams['SHOW_PICKUP_MAP'] === 'N' ? 'N' : 'Y';
    $arParams['SHOW_MAP_IN_PROPS'] = $arParams['SHOW_MAP_IN_PROPS'] === 'Y' ? 'Y' : 'N';
    $arParams['USE_YM_GOALS'] = $arParams['USE_YM_GOALS'] === 'Y' ? 'Y' : 'N';
    $arParams['USE_ENHANCED_ECOMMERCE'] = isset($arParams['USE_ENHANCED_ECOMMERCE']) && $arParams['USE_ENHANCED_ECOMMERCE'] === 'Y' ? 'Y' : 'N';
    $arParams['DATA_LAYER_NAME'] = isset($arParams['DATA_LAYER_NAME']) ? trim($arParams['DATA_LAYER_NAME']) : 'dataLayer';
    $arParams['BRAND_PROPERTY'] = isset($arParams['BRAND_PROPERTY']) ? trim($arParams['BRAND_PROPERTY']) : '';

    $useDefaultMessages = !isset($arParams['USE_CUSTOM_MAIN_MESSAGES']) || $arParams['USE_CUSTOM_MAIN_MESSAGES'] != 'Y';

    if ($useDefaultMessages || !isset($arParams['MESS_AUTH_BLOCK_NAME'])) {
        $arParams['MESS_AUTH_BLOCK_NAME'] = Loc::getMessage('AUTH_BLOCK_NAME_DEFAULT');
    }

    if ($useDefaultMessages || !isset($arParams['MESS_REG_BLOCK_NAME'])) {
        $arParams['MESS_REG_BLOCK_NAME'] = Loc::getMessage('REG_BLOCK_NAME_DEFAULT');
    }

    if ($useDefaultMessages || !isset($arParams['MESS_BASKET_BLOCK_NAME'])) {
        $arParams['MESS_BASKET_BLOCK_NAME'] = Loc::getMessage('BASKET_BLOCK_NAME_DEFAULT');
    }

    if ($useDefaultMessages || !isset($arParams['MESS_REGION_BLOCK_NAME'])) {
        $arParams['MESS_REGION_BLOCK_NAME'] = Loc::getMessage('REGION_BLOCK_NAME_DEFAULT');
    }

    if ($useDefaultMessages || !isset($arParams['MESS_PAYMENT_BLOCK_NAME'])) {
        $arParams['MESS_PAYMENT_BLOCK_NAME'] = Loc::getMessage('PAYMENT_BLOCK_NAME_DEFAULT');
    }

    if ($useDefaultMessages || !isset($arParams['MESS_DELIVERY_BLOCK_NAME'])) {
        $arParams['MESS_DELIVERY_BLOCK_NAME'] = Loc::getMessage('DELIVERY_BLOCK_NAME_DEFAULT');
    }

    if ($useDefaultMessages || !isset($arParams['MESS_BUYER_BLOCK_NAME'])) {
        $arParams['MESS_BUYER_BLOCK_NAME'] = Loc::getMessage('BUYER_BLOCK_NAME_DEFAULT');
    }

    if ($useDefaultMessages || !isset($arParams['MESS_BACK'])) {
        $arParams['MESS_BACK'] = Loc::getMessage('BACK_DEFAULT');
    }

    if ($useDefaultMessages || !isset($arParams['MESS_FURTHER'])) {
        $arParams['MESS_FURTHER'] = Loc::getMessage('FURTHER_DEFAULT');
    }

    if ($useDefaultMessages || !isset($arParams['MESS_EDIT'])) {
        $arParams['MESS_EDIT'] = Loc::getMessage('EDIT_DEFAULT');
    }

    if ($useDefaultMessages || !isset($arParams['MESS_ORDER'])) {
        $arParams['MESS_ORDER'] = Loc::getMessage('ORDER_DEFAULT');
    }

    if ($useDefaultMessages || !isset($arParams['MESS_PRICE'])) {
        $arParams['MESS_PRICE'] = Loc::getMessage('PRICE_DEFAULT');
    }

    if ($useDefaultMessages || !isset($arParams['MESS_PERIOD'])) {
        $arParams['MESS_PERIOD'] = Loc::getMessage('PERIOD_DEFAULT');
    }

    if ($useDefaultMessages || !isset($arParams['MESS_NAV_BACK'])) {
        $arParams['MESS_NAV_BACK'] = Loc::getMessage('NAV_BACK_DEFAULT');
    }

    if ($useDefaultMessages || !isset($arParams['MESS_NAV_FORWARD'])) {
        $arParams['MESS_NAV_FORWARD'] = Loc::getMessage('NAV_FORWARD_DEFAULT');
    }

    $useDefaultMessages = !isset($arParams['USE_CUSTOM_ADDITIONAL_MESSAGES']) || $arParams['USE_CUSTOM_ADDITIONAL_MESSAGES'] != 'Y';

    if ($useDefaultMessages || !isset($arParams['MESS_PRICE_FREE'])) {
        $arParams['MESS_PRICE_FREE'] = Loc::getMessage('PRICE_FREE_DEFAULT');
    }

    if ($useDefaultMessages || !isset($arParams['MESS_ECONOMY'])) {
        $arParams['MESS_ECONOMY'] = Loc::getMessage('ECONOMY_DEFAULT');
    }

    if ($useDefaultMessages || !isset($arParams['MESS_REGISTRATION_REFERENCE'])) {
        $arParams['MESS_REGISTRATION_REFERENCE'] = Loc::getMessage('REGISTRATION_REFERENCE_DEFAULT');
    }

    if ($useDefaultMessages || !isset($arParams['MESS_AUTH_REFERENCE_1'])) {
        $arParams['MESS_AUTH_REFERENCE_1'] = Loc::getMessage('AUTH_REFERENCE_1_DEFAULT');
    }

    if ($useDefaultMessages || !isset($arParams['MESS_AUTH_REFERENCE_2'])) {
        $arParams['MESS_AUTH_REFERENCE_2'] = Loc::getMessage('AUTH_REFERENCE_2_DEFAULT');
    }

    if ($useDefaultMessages || !isset($arParams['MESS_AUTH_REFERENCE_3'])) {
        $arParams['MESS_AUTH_REFERENCE_3'] = Loc::getMessage('AUTH_REFERENCE_3_DEFAULT');
    }

    if ($useDefaultMessages || !isset($arParams['MESS_ADDITIONAL_PROPS'])) {
        $arParams['MESS_ADDITIONAL_PROPS'] = Loc::getMessage('ADDITIONAL_PROPS_DEFAULT');
    }

    if ($useDefaultMessages || !isset($arParams['MESS_USE_COUPON'])) {
        $arParams['MESS_USE_COUPON'] = Loc::getMessage('USE_COUPON_DEFAULT');
    }

    if ($useDefaultMessages || !isset($arParams['MESS_COUPON'])) {
        $arParams['MESS_COUPON'] = Loc::getMessage('COUPON_DEFAULT');
    }

    if ($useDefaultMessages || !isset($arParams['MESS_PERSON_TYPE'])) {
        $arParams['MESS_PERSON_TYPE'] = Loc::getMessage('PERSON_TYPE_DEFAULT');
    }

    if ($useDefaultMessages || !isset($arParams['MESS_SELECT_PROFILE'])) {
        $arParams['MESS_SELECT_PROFILE'] = Loc::getMessage('SELECT_PROFILE_DEFAULT');
    }

    if ($useDefaultMessages || !isset($arParams['MESS_REGION_REFERENCE'])) {
        $arParams['MESS_REGION_REFERENCE'] = Loc::getMessage('REGION_REFERENCE_DEFAULT');
    }

    if ($useDefaultMessages || !isset($arParams['MESS_PICKUP_LIST'])) {
        $arParams['MESS_PICKUP_LIST'] = Loc::getMessage('PICKUP_LIST_DEFAULT');
    }

    if ($useDefaultMessages || !isset($arParams['MESS_NEAREST_PICKUP_LIST'])) {
        $arParams['MESS_NEAREST_PICKUP_LIST'] = Loc::getMessage('NEAREST_PICKUP_LIST_DEFAULT');
    }

    if ($useDefaultMessages || !isset($arParams['MESS_SELECT_PICKUP'])) {
        $arParams['MESS_SELECT_PICKUP'] = Loc::getMessage('SELECT_PICKUP_DEFAULT');
    }

    if ($useDefaultMessages || !isset($arParams['MESS_INNER_PS_BALANCE'])) {
        $arParams['MESS_INNER_PS_BALANCE'] = Loc::getMessage('INNER_PS_BALANCE_DEFAULT');
    }

    if ($useDefaultMessages || !isset($arParams['MESS_ORDER_DESC'])) {
        $arParams['MESS_ORDER_DESC'] = Loc::getMessage('ORDER_DESC_DEFAULT');
    }

    $useDefaultMessages = !isset($arParams['USE_CUSTOM_ERROR_MESSAGES']) || $arParams['USE_CUSTOM_ERROR_MESSAGES'] != 'Y';

    if ($useDefaultMessages || !isset($arParams['MESS_PRELOAD_ORDER_TITLE'])) {
        $arParams['MESS_PRELOAD_ORDER_TITLE'] = Loc::getMessage('PRELOAD_ORDER_TITLE_DEFAULT');
    }

    if ($useDefaultMessages || !isset($arParams['MESS_SUCCESS_PRELOAD_TEXT'])) {
        $arParams['MESS_SUCCESS_PRELOAD_TEXT'] = Loc::getMessage('SUCCESS_PRELOAD_TEXT_DEFAULT');
    }

    if ($useDefaultMessages || !isset($arParams['MESS_FAIL_PRELOAD_TEXT'])) {
        $arParams['MESS_FAIL_PRELOAD_TEXT'] = Loc::getMessage('FAIL_PRELOAD_TEXT_DEFAULT');
    }

    if ($useDefaultMessages || !isset($arParams['MESS_DELIVERY_CALC_ERROR_TITLE'])) {
        $arParams['MESS_DELIVERY_CALC_ERROR_TITLE'] = Loc::getMessage('DELIVERY_CALC_ERROR_TITLE_DEFAULT');
    }

    if ($useDefaultMessages || !isset($arParams['MESS_DELIVERY_CALC_ERROR_TEXT'])) {
        $arParams['MESS_DELIVERY_CALC_ERROR_TEXT'] = Loc::getMessage('DELIVERY_CALC_ERROR_TEXT_DEFAULT');
    }

    if ($useDefaultMessages || !isset($arParams['MESS_PAY_SYSTEM_PAYABLE_ERROR'])) {
        $arParams['MESS_PAY_SYSTEM_PAYABLE_ERROR'] = Loc::getMessage('PAY_SYSTEM_PAYABLE_ERROR_DEFAULT');
    }
}


# Если нет товаров, открываем страницу empty
if ($arParams['DISABLE_BASKET_REDIRECT'] === 'Y' && $arResult['SHOW_EMPTY_BASKET']) {
    include($server->getDocumentRoot() . $templateFolder . '/empty.php');
    return;
}

global $USER;

# Вывод шаблона компонта
$signer = new Main\Security\Sign\Signer;
$signedParams = $signer->sign(base64_encode(serialize($arParams)), 'sale.order.ajax');
$basketUser = Fuser::getId();
$basket = Basket::loadItemsForFUser($basketUser, SITE_ID);
$basketItems = $basket->getBasketItems();


//Получить информацию по пользователю
global $USER;
$user_id = $USER->GetID();
$arInfoUser = CUser::GetList($by, $order, ['ID' => $user_id])->Fetch();

//$arRowsElement = $arResult['JS_DATA']['GRID']['ROWS'];
//$arKeyId =[];
//foreach ($arRowsElement as $item) {
//    $arKeyId[] = $item['data']['PRODUCT_ID'];
//}
//$arElementsInfo = Poligon\Core\Iblock\Helper::getElement(['IBLOCK_ID'=> Poligon\Core\Variables::IBLOCK_ID_CATALOG_OFFERS,'ID'=>$arKeyId],['ID','PROPERTY_SIZE_EU']);
//\_::d($arElementsInfo);
//foreach ($arRowsElement as $key => $item) {
//    foreach ($arElementsInfo as $itemInfo) {
//        if($itemInfo['ID'] == $item['data']['PRODUCT_ID']){
//            $arRowsElement[$key]['data']['prop'] = $itemInfo['PROP'];
//        }
//    }
//}
//$arResult['JS_DATA']['GRID']['ROWS'] = $arRowsElement;
$CITY = '';
foreach ($arResult['ORDER_PROP']['USER_PROPS_Y'] as $itemProp) {
    if($itemProp['CODE'] =='LOCATION'){
        unset($itemProp['~VARIANTS']);
        foreach ($itemProp['VARIANTS'] as $VARIANT) {
            if($VARIANT['ID']==$itemProp['VALUE']){
                $CITY = $VARIANT['CITY_NAME'];
            }
        }
    }
    if($itemProp['CODE'] =='CITY'){
        if($itemProp['VALUE'] == ''){
            $itemProp['VALUE'] = $CITY;
            if($arResult['JS_DATA']['ORDER_PROP']['properties'][4]['VALUE'] == ''){
                $arResult['JS_DATA']['ORDER_PROP']['properties'][4]['VALUE'] = $CITY;
            }
        }
    }
}

$var = [
    'type' => 'order',
    'order' => $arResult['JS_DATA'],
    'info' => [
        'CITY_AUTO_DETECT' => $CITY,
        'isDebugMode' => true,
        'isAuthorized' => $USER->IsAuthorized(),
        'isAdmin' => $USER->IsAdmin(),
        'templatePath' => SITE_TEMPLATE_PATH,
        'store' => $_REQUEST['store'] ?: '',
        'delivery' => $_REQUEST['delivery'] == 'Y' ? 'Y' : 'N',
        'deliveryIdList' => [
            'delivery' => [72,83,86,94],
            'deliveryPickup' => [73,81,40,85,93],
            'pickupSdek' => 73,
            'pickupPochta' => 85,
            'pickupBoxbery' => 93,
//            'pickupDPD' => 73,
//            'pickupEnergy' => 73,
        ],
        //'BUYER_STORE_XML' => General::getStoresInfoID($_REQUEST['store'] ?: $arResult['BUYER_STORE'], 'XML_ID'),
        'deliveryPickupId' => [40]
    ],
    'formData' => [
        'actionUrl' => $APPLICATION->GetCurPage(),
        'sessid' => bitrix_sessid(),
        'location_type' => 'code',
        //'BUYER_STORE' => $_REQUEST['store'] ?: $arResult['BUYER_STORE'],
        'via_ajax' => 'N'
    ],
    'component' => [
        'signedParamsString' => $signedParams,
        'siteId' => $component->getSiteId(),
        'ajaxUrl' => $component->getPath() . '/ajax.php',
    ],
    'user_info' => $arInfoUser,
    'DPDData' => ['cities' => []]
];
?>
    <div id="order" data-application='<?= htmlspecialchars(json_encode($var)) ?>'>
        <div class="sb_preloader"></div>
    </div>

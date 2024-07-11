<?php

namespace Poligon\Core\Controller;


use Bitrix\Currency\CurrencyManager;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Context;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Loader;
use Bitrix\Sale\Basket;
use Bitrix\Sale\BasketItemCollection;
use Bitrix\Sale\Fuser;
use Bitrix\Sale\Order;
use Bitrix\Sale\ProductTable;
use CCatalogSku;
use CFile;
use CIBlockElement;
use CMaxCondition;
use COption;
use CUser;
use Poligon\Core\Iblock\Helper;
use Poligon\Core\Order\DaData\City;
use Poligon\Core\Order\DaData\Street;


Loader::includeModule('sale');
\Bitrix\Main\Loader::includeModule('currency');
\Bitrix\Main\Loader::includeModule('catalog');

class Sale extends Controller
{


    //Пример вызова
    /*
      BX.ajax.runAction('Poligon:core.api.Sale.getSignet',{}).then(function (res) {});
      */
    public function configureActions()
    {
        return [
            'add' => ['prefilters' => []],
            'addOrder' => ['prefilters' => []],
            'addOrderItem' => ['prefilters' => []],
            'addProductBasket' => ['prefilters' => []],
            'dadatacity' => ['prefilters' => []],
            'dadataaddress' => ['prefilters' => []],
            'clearAndAddBasket' => ['prefilters' => []],
            'getBasketCount' => ['prefilters' => []],
            'getSignet' => ['prefilters' => []],
            'getPlusProduct' => ['prefilters' => []],

        ];
    }


    public function getPlusProductAction($product_id)
    {
        $arIdElementParent = [];
        $tmpOffersParent = [];
        $obElementsOffers = CIBlockElement::GetList([], ['IBLOCK_ID' => 182, 'ID' => $product_id], false, false, ['ID', 'PROPERTY_CML2_LINK']);
        while ($arElementOffer = $obElementsOffers->GetNext()) {
            $arIdElementParent[] = $arElementOffer['PROPERTY_CML2_LINK_VALUE'];
            $tmpOffersParent[$arElementOffer['ID']] = $arElementOffer['PROPERTY_CML2_LINK_VALUE'];
        }
        unset($obElementsOffers);
        unset($arElementOffer);
        $arResultTmp = [];
        $obElements = CIBlockElement::GetList([], ['IBLOCK_ID' => 180, 'ID' => $arIdElementParent], false, false, ['ID', 'PROPERTY_EXPANDABLES_FILTER']);
        while ($arElement = $obElements->GetNext()) {

            $cond = new CMaxCondition();
            try {
                $arTmpExp = \Bitrix\Main\Web\Json::decode($arElement['~PROPERTY_EXPANDABLES_FILTER_VALUE']);
                $arExpandablesFilter = $cond->parseCondition($arTmpExp, []);
            } catch (\Exception $e) {
                $arExpandablesFilter = array();
            }

            $arResultTmp[$arElement['ID']] = $arExpandablesFilter;
        }
        unset($obElements);
        unset($arElement);
        $arResult = [];
        foreach ($tmpOffersParent as $keyIdOffers => $idParent) {
            foreach ($arResultTmp as $keyId => $item) {
                if ($keyId == $idParent) {
                    $arFilterId = $item['ID'];
                    $obElements = CIBlockElement::GetList([], ['IBLOCK_ID' => 180, 'ID' => $arFilterId]);
                    $arResInfo = [];
                    while ($obElement = $obElements->GetNextElement()) {
                        $arFields = $obElement->GetFields();
                        $arFields['PROP'] = $obElement->GetProperties();
                        $arInfo = [
                            'NAME' => $arFields['NAME'],
                            'DETAIL_PICTURE' => $arFields['DETAIL_PICTURE'],
                            'DETAIL_PAGE_URL' => $arFields['DETAIL_PAGE_URL'],
                        ];
                        if ($arInfo['DETAIL_PICTURE']) {
                            $arInfo['DETAIL_PICTURE'] = CFile::GetPath($arInfo['DETAIL_PICTURE']);
                        }
                        $arResInfo[] = $arInfo;
                    }
                    $arResult[$keyIdOffers] = $arResInfo;//$item['ID'];
                }
            }
        }
        return $arResult;
    }

    public function getSignetAction()
    {
        $signer = new \Bitrix\Main\Security\Sign\Signer;
        $signedTemplate = $signer->sign('main_vue', 'sale.basket.basket');
        $signedParams = $signer->sign(base64_encode(serialize(array(
            "COLUMNS_LIST" => array(
                0 => "NAME",
                1 => "DISCOUNT",
                2 => "PROPS",
                3 => "DELETE",
                4 => "DELAY",
                5 => "TYPE",
                6 => "PRICE",
                7 => "QUANTITY",
                8 => "SUM",
            ),
            "OFFERS_PROPS" => array(),
            "PATH_TO_ORDER" => SITE_DIR . "order/",
            "HIDE_COUPON" => "N",
            "PRICE_VAT_SHOW_VALUE" => "N",
            "COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
            "USE_PREPAYMENT" => "N",
            "SET_TITLE" => "N",
            "AJAX_MODE_CUSTOM" => "Y",
            "SHOW_MEASURE" => "Y",
            "PICTURE_WIDTH" => "100",
            "PICTURE_HEIGHT" => "100",
            "SHOW_FULL_ORDER_BUTTON" => "Y",
            "SHOW_FAST_ORDER_BUTTON" => "Y",
            "COMPONENT_TEMPLATE" => "v2",
            "QUANTITY_FLOAT" => "N",
            "ACTION_VARIABLE" => "action",
            "TEMPLATE_THEME" => "blue",
            "AUTO_CALCULATION" => "Y",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO",
            "USE_GIFTS" => "Y",
            "GIFTS_PLACE" => "BOTTOM",
            "GIFTS_BLOCK_TITLE" => "Выберите один из подарков",
            "GIFTS_HIDE_BLOCK_TITLE" => "N",
            "GIFTS_TEXT_LABEL_GIFT" => "Подарок",
            "GIFTS_PRODUCT_QUANTITY_VARIABLE" => "quantity",
            "GIFTS_PRODUCT_PROPS_VARIABLE" => "prop",
            "GIFTS_SHOW_OLD_PRICE" => "Y",
            "GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",
            "GIFTS_SHOW_NAME" => "Y",
            "GIFTS_SHOW_IMAGE" => "Y",
            "GIFTS_MESS_BTN_BUY" => "Выбрать",
            "GIFTS_MESS_BTN_DETAIL" => "Подробнее",
            "GIFTS_PAGE_ELEMENT_COUNT" => "4",
            "GIFTS_CONVERT_CURRENCY" => "N",
            "GIFTS_HIDE_NOT_AVAILABLE" => "N",
            "EMPTY_BASKET_HINT_PATH" => SITE_DIR . "catalog/",
            "DEFERRED_REFRESH" => "Y",
            "USE_DYNAMIC_SCROLL" => "Y",
            "SHOW_FILTER" => "Y",
            "SHOW_RESTORE" => "Y",
            "COLUMNS_LIST_EXT" => array(
                0 => "PREVIEW_PICTURE",
                1 => "DISCOUNT",
                2 => "PROPS",
                3 => "DELETE",
                4 => "DELAY",
                5 => "SUM",
            ),
            "COLUMNS_LIST_MOBILE" => array(
                0 => "PREVIEW_PICTURE",
                1 => "DISCOUNT",
                2 => "DELETE",
                3 => "DELAY",
                4 => "SUM",
            ),
            "TOTAL_BLOCK_DISPLAY" => array(
                0 => "top",
                1 => "bottom",
            ),
            "DISPLAY_MODE" => "extended",
            "PRICE_DISPLAY_MODE" => "Y",
            "SHOW_DISCOUNT_PERCENT" => "Y",
            "DISCOUNT_PERCENT_POSITION" => "bottom-right",
            "PRODUCT_BLOCKS_ORDER" => "props,sku,columns",
            "USE_PRICE_ANIMATION" => "Y",
            "LABEL_PROP" => array(),
            "CORRECT_RATIO" => "Y",
            "COMPATIBLE_MODE" => "Y",
            "ADDITIONAL_PICT_PROP_136" => "-",
            "ADDITIONAL_PICT_PROP_137" => "-",
            "ADDITIONAL_PICT_PROP_180" => "-",
            "ADDITIONAL_PICT_PROP_182" => "-",
            "BASKET_IMAGES_SCALING" => "adaptive",
            "USE_ENHANCED_ECOMMERCE" => "N"
        ))), 'sale.basket.basket');
        return [
            'signedTemplate' => $signedTemplate,
            'signedParams' => $signedParams,
            'basketSessid' => $_SESSION['fixed_session_id'],
        ];
    }

    public function addAction($fio, $email, $phone)
    {
        //получаем эти данные и привязываем к ИД пользователя
        $idUser = \Bitrix\Sale\Fuser::getId();
        //Ищем брошенное оформление данного пользователя
        $IBLOCK_ID_RESERV = Helper::getIdByCode('sale_order');
        $arElement = Helper::getElement([
            'IBLOCK_ID' => $IBLOCK_ID_RESERV,
            'NAME' => $idUser,
        ]);
        $obElNew = new CIBlockElement();
        $arNewFieldElem = [];
        $arNewFieldElem['IBLOCK_ID'] = $IBLOCK_ID_RESERV;
        $arNewFieldElem['NAME'] = $idUser;
        $arNewFieldElem['PROPERTY_VALUES']['FUSER_ID'] = $idUser;
        if (!empty($fio)) {
            $arNewFieldElem['PROPERTY_VALUES']['FIO'] = $fio;
        }
        if (!empty($email)) {
            $arNewFieldElem['PROPERTY_VALUES']['EMAIL'] = $email;
        }
        if (!empty($phone)) {
            $arNewFieldElem['PROPERTY_VALUES']['PHONE'] = $phone;
        }
        $arNewFieldElem['PROPERTY_VALUES']['SALE_BASKET'] = $_SERVER['HTTP_ORIGIN'] . '/bitrix/admin/sale_basket.php?FUSER_ID=' . $idUser . '&SITE_ID=s1&USER_ID=&action=order_basket&lang=ru';
        if (empty($arElement)) {
            //Создаем новую запись
            $obElNew->Add($arNewFieldElem);
        } else {
            //Обновляем поля записи
            $idUpdateElement = $arElement[0]['ID'];
            CIBlockElement::SetPropertyValuesEx($idUpdateElement, $IBLOCK_ID_RESERV,
                $arNewFieldElem['PROPERTY_VALUES']);
        }
    }


    public static function getDefaultData($userProps)
    {
        $userEmail = isset($userProps['EMAIL']) ? trim((string)$userProps['EMAIL']) : '';
        $userPhone = $userProps['PHONE'];
        if (!$userPhone) {
            return false;
        }
        if (!$userEmail) {
            $userEmail = $userPhone . '@autouser.autouser';
        }
        $newName = '';
        $newLastName = '';
        $fio = isset($userProps['FIO']) ? trim((string)$userProps['FIO']) : '';
        if (!empty($fio)) {
            $arNames = explode(' ', $fio);
            if (isset($arNames[1])) {
                $newName = $arNames[1];
                $newLastName = $arNames[0];
            } else {
                $newName = $arNames[0];
            }
        }
        $groupIds = [];
        $defaultGroups = Option::get('main', 'new_user_registration_def_group', '');
        if (!empty($defaultGroups)) {
            $groupIds = explode(',', $defaultGroups);
        }
        $arPolicy = $GLOBALS["USER"]->GetGroupPolicy($groupIds);
        $passwordMinLength = (int)$arPolicy['PASSWORD_LENGTH'];
        if ($passwordMinLength <= 0) {
            $passwordMinLength = 6;
        }
        $passwordChars = array(
            'abcdefghijklnmopqrstuvwxyz',
            'ABCDEFGHIJKLNMOPQRSTUVWXYZ',
            '0123456789',
        );
        if ($arPolicy['PASSWORD_PUNCTUATION'] === 'Y') {
            $passwordChars[] = ",.<>/?;:'\"[]{}\|`~!@#\$%^&*()-_+=";
        }
        $newPassword = randString($passwordMinLength + 2, $passwordChars);
        return array(
            'EMAIL' => $userEmail,
            'LOGIN' => $userPhone,
            'NAME' => $newName,
            'LAST_NAME' => $newLastName,
            'PASSWORD' => $newPassword,
            'PASSWORD_CONFIRM' => $newPassword,
            'GROUP_ID' => $groupIds
        );
    }

    public static function getUserId($phone, $fio = '')
    {

        global $USER, $APPLICATION;
        if (!$USER->IsAuthorized()) {
            // get phone auth params
            list($bPhoneAuthSupported, $bPhoneAuthShow, $bPhoneAuthRequired, $bPhoneAuthUse) = [];//PhoneAuth::getOptions();
            $bPhoneAuthSupported = true;

            $email = '';

            if (!isset($email) || trim($email) == '') {
                if ($phone) {
                    $phoneNumber = \Bitrix\Main\UserPhoneAuthTable::normalizePhoneNumber($phone);
                    $login = NormalizePhone((string)$phone, 3);

                    if ($bPhoneAuthShow) {
                        $rsUserByPhone = \Bitrix\Main\UserPhoneAuthTable::getList([
                            'select' => array('USER_ID'),
                            'filter' => array('=PHONE_NUMBER' => $phoneNumber),
                        ]);
                        $nUserCount = (int)($rsUserByPhone->getSelectedRowsCount());
                        if ($nUserCount == 1) {
                            $ar_user = $rsUserByPhone->Fetch();
                            $registeredUserID = $ar_user['USER_ID'];
                            $user_exists = true;
                        }

                    } else {
                        $rsUser = CUser::GetList($by = "ID", $order = "ASC", array("LOGIN_EQUAL" => $login));
                        $nUserCount = (int)($rsUser->SelectedRowsCount());
                        if ($nUserCount == 1) {
                            $ar_user = $rsUser->Fetch();
                            $registeredUserID = $ar_user['ID'];

                            $user_exists = true;
                        } elseif ($nUserCount > 1) {
                            return ['message' => 'Пользователь с таким логином (телефоном) уже существует'];
                        }
                    }
                } else {
                    $login = 'user_' . substr((microtime(true) * 10000), 0, 12);
                }
                $user_registered = true;
            }

            if ($user_registered && !$user_exists) {
                $userPassword = randString(10);
                $username = explode(' ', $fio);
                $email = $login . '@sneax.ru';
                // register user
                $captcha = COption::GetOptionString('main', 'captcha_registration', 'N');
                if ($captcha == 'Y') {
                    COption::SetOptionString('main', 'captcha_registration', 'N');
                }

                if ($bPhoneAuthSupported && $bPhoneAuthShow) {
                    if (empty($phone) && $bPhoneAuthRequired) {
                        return ['message' => 'Не указан или неправильно указан телефон.'];
                    }

                    $phoneNumber = \Bitrix\Main\UserPhoneAuthTable::normalizePhoneNumber($phone);
                    $arUserByPhone = \Bitrix\Main\UserPhoneAuthTable::getList([
                        'select' => array('USER_ID'),
                        'filter' => array('=PHONE_NUMBER' => $phoneNumber),
                    ])->fetch();
                    if ($arUserByPhone) {
                        return ['message' => 'Ошибка регистрации нового пользователя: пользователь с номером телефона ' . $phoneNumber . ' уже существует.'];
                    }
                    $newUser = $USER->Register($login, $username[1], $username[0], $userPassword, $userPassword, $email, $SITE_ID, '', 0, false, $phone);
                } else {
                    $newUser = $USER->Register($login, $username[1], $username[0], $userPassword, $userPassword, $email);
                }

                // $newUser = $USER->Add(array("LOGIN"=>$login, "NAME"=>$username[0], "LAST_NAME"=>$username[1], "PASSWORD"=>$userPassword,  "CONFIRM_PASSWORD"=>$userPassword, "EMAIL"=>$email));
                if ($captcha == 'Y') {
                    COption::SetOptionString('main', 'captcha_registration', 'Y');
                }
                if ($newUser['TYPE'] == 'ERROR') {
                    return ['message' => 'Ошибка регистрации пользователя.' . $newUser['MESSAGE']];
                } else {
                    $registeredUserID = $newUser['ID'];
                    // $registeredUserID = $newUser;

                    if (!empty($phone) && ($arParams["AUTO_LOGOUT"] == "Y")) {
                        $USER->Update($registeredUserID, array('PERSONAL_PHONE' => $phone));
                    }
                    if (!empty($username[2])) {
                        $USER->Update($registeredUserID, array('SECOND_NAME' => $username[2]));
                    }

                    //$USER->Logout();

                }
            }
        } else {
            $registeredUserID = $USER->GetID();
        }
        return $registeredUserID;
    }

    public function addOrderAction($phone)
    {
        global $APPLICATION;
        if (!Loader::IncludeModule('sale')) {
            return ['error' => 404];
        }
        $userId = self::getUserId($phone);
        //$userId = 2531;
        $siteId = \Bitrix\Main\Context::getCurrent()->getSite();
        $order = Order::create($siteId, $userId);
        $order->setPersonTypeId(1); // ИД типа пользователя
        $basket = Basket::loadItemsForFUser(\CSaleBasket::GetBasketUserID(),
            Context::getCurrent()->getSite())->getOrderableItems();
        $order->setBasket($basket);
        $shipmentCollection = $order->getShipmentCollection();
        $shipment = $shipmentCollection->createItem();
        $shipment->setFields(array(
            'DELIVERY_ID' => 6,
            'DELIVERY_NAME' => 'Самовывоз',
            'CURRENCY' => $order->getCurrency()
        ));
        $shipmentItemCollection = $shipment->getShipmentItemCollection();
        foreach ($order->getBasket() as $item) {
            $shipmentItem = $shipmentItemCollection->createItem($item);
            $shipmentItem->setQuantity($item->getQuantity());
        }
        $paymentCollection = $order->getPaymentCollection();
        $extPayment = $paymentCollection->createItem();
        $extPayment->setFields(array(
            'PAY_SYSTEM_ID' => 5,
            'PAY_SYSTEM_NAME' => 'Наличные',
            'SUM' => $order->getPrice()
        ));
        $order->doFinalAction(true);
        $propertyCollection = $order->getPropertyCollection();
        foreach ($propertyCollection->getGroups() as $group) {
            foreach ($propertyCollection->getGroupProperties($group['ID']) as $property) {
                $p = $property->getProperty();
                if ($p["CODE"] == "PHONE") {
                    $property->setValue($phone);
                }
            }
        }
        $order->setField('CURRENCY', 'RUB');
        $COMMENTS = 'Заказ оформлен через АПИ. ' . 'Номер телефона: ' . $phone;
//        $detect = new \Mobile_Detect;
//        if ($detect->isMobile() && !$detect->isTablet()) {
//            $COMMENTS .= ' С мобмльного телефона';
//        }
        $order->setField('USER_DESCRIPTION', $COMMENTS);
        $r = $order->save();
        $orderId = $order->GetId();
        if (!$r->isSuccess()) {
            return ['error' => 404, 'message' => "Ошибка оформления" . print_r($r->getErrorMessages(), true)];
        } else {
            return [
                'error' => 200,
                'orderId' => $orderId,
                'message' => "Ваш заказ " . $orderId . " оформлен"
            ];
        }
    }

    public function addOrderItemAction($fio, $phone, $item, $quantity)
    {
        Loader::IncludeModule('catalog');
        if (!Loader::IncludeModule('sale')) {
            return ['error' => 404];
        }

        // Получаем информацию о товаре
        $product = ProductTable::getList([
            'filter' => ['ID' => $item],
            'select' => ['NAME', 'PRICE']
        ])->fetch();

        // Создаем временную корзину
        $basket = Basket::create(SITE_ID);
        $basketItem = $basket->createItem('catalog', $item);

        $basketItem->setFields([
            'QUANTITY' => $quantity,
            'CURRENCY' => 'RUB',
            'LID' => SITE_ID,
            'PRODUCT_ID' => $item,
            'NAME' => $product['NAME'],
            'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
            // Другие поля товара, если необходимо
        ]);


        $userId = self::getUserId($phone, $fio);
        // Создаем новый заказ на основе корзины
        $order = Order::create(SITE_ID, $userId);
        $order->setBasket($basket);

        $order->setPersonTypeId(1); // ИД типа пользователя

        $shipmentCollection = $order->getShipmentCollection();
        $shipment = $shipmentCollection->createItem();
        $shipment->setFields(array(
            'DELIVERY_ID' => 2,
            'DELIVERY_NAME' => 'Самовывоз',
            'CURRENCY' => $order->getCurrency()
        ));
        $shipmentItemCollection = $shipment->getShipmentItemCollection();
        foreach ($order->getBasket() as $item) {
            $shipmentItem = $shipmentItemCollection->createItem($item);
            $shipmentItem->setQuantity($item->getQuantity());
        }
        $paymentCollection = $order->getPaymentCollection();
        $extPayment = $paymentCollection->createItem();
        $extPayment->setFields(array(
            'PAY_SYSTEM_ID' => 2,
            'PAY_SYSTEM_NAME' => 'Тинькофф Банк',
            'SUM' => $order->getPrice()
        ));
        $order->doFinalAction(true);
        $propertyCollection = $order->getPropertyCollection();
        foreach ($propertyCollection->getGroups() as $group) {
            foreach ($propertyCollection->getGroupProperties($group['ID']) as $property) {
                $p = $property->getProperty();
                if ($p["CODE"] == "PHONE") {
                    $property->setValue($phone);
                }
            }
        }
        $order->setField('CURRENCY', 'RUB');
        $COMMENTS = 'Заказ оформлен через АПИ. ' . 'Номер телефона: ' . $phone;
        $order->setField('USER_DESCRIPTION', $COMMENTS);
        $r = $order->save();
        $orderId = $order->GetId();
        if (!$r->isSuccess()) {
            return ['error' => 404, 'message' => "Ошибка оформления" . print_r($r->getErrorMessages(), true)];
        } else {
            return [
                'error' => 200,
                'orderId' => $orderId,
                'message' => "Ваш заказ " . $orderId . " оформлен"
            ];
        }
    }


    public function addProductBasketAction($productId, $type = 'plus')
    {
        $basket = Basket::loadItemsForFUser(Fuser::getId(), Context::getCurrent()->getSite());
        $quantity = 1;
        if ($item = $basket->getExistsItem('catalog', $productId)) {
            if ($type == 'plus') {
                $item->setField('QUANTITY', $item->getQuantity() + $quantity);
            } else if ($type == 'minus') {
                $item->setField('QUANTITY', $item->getQuantity() - $quantity);
            } else if ($type == 'delete') {
                $item->delete();
            }
        } else {
            $item = $basket->createItem('catalog', $productId);
            $item->setFields(array(
                'QUANTITY' => $quantity,
                'CURRENCY' => CurrencyManager::getBaseCurrency(),
                'LID' => Context::getCurrent()->getSite(),
                'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
            ));
        }
        $basket->save();

        $incProduct = 0;
        /** @var Bitrix\Sale\BasketItem $basketItemBase */
        foreach ($basket->getBasketItems() as $basketItemBase) {
            if ($basketItemBase->getField('DELAY') != 'Y') {
                $incProduct++;
            }
        }
        return ['count_basket' => $incProduct, 'basket' => self::getBasketAction()];
    }

    public function getBasketAction()
    {
        $basket = Basket::loadItemsForFUser(Fuser::getId(), Context::getCurrent()->getSite());

        $price = $basket->getPrice(); // Цена с учетом скидок
        $fullPrice = $basket->getBasePrice(); // Цена без учета скидок

        $basketItems = $basket->getBasketItems(); // массив объектов Sale\BasketItem
        $countBasket = count($basket->getBasketItems());
        ob_start();
        ?>
        <div class="main-container">
            <div class="h-menu-r__overlay"></div>
            <div class="h-menu-r__inner">
                <div class="basket-menu__header">
                    <button class="menu-popup-header__back h-menu-r__back">
                        <svg class="icon icon--arrow-left">
                            <use href="<?= SITE_TEMPLATE_PATH ?>/img/sprite.svg#arrow-left"></use>
                        </svg>
                        <span>Назад</span>
                    </button>
                    <div class="basket-menu__header-inner">
                        <div class="basket-menu__header-title">
                            <h3 class="menu-popup-header__title">корзина</h3>
                            <div class="basket-menu__number"><?= $countBasket ?> товара</div>
                        </div>
                        <div class="basket-menu__number"><?= $countBasket ?> товара</div>
                        <?
                        if ($countBasket > 0):?>
                            <button class="btn-trash btn-trash_all">
                                <svg class="icon icon--trash">
                                    <use href="<?= SITE_TEMPLATE_PATH ?>/img/sprite.svg#trash"></use>
                                </svg>
                            </button>
                        <? endif; ?>
                        <button class="h-menu-r__close h-menu-r__back">
                            <svg class="icon icon--close-w">
                                <use href="<?= SITE_TEMPLATE_PATH ?>/img/sprite.svg#close-2"></use>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="h-menu-r__wrap">
                    <div class="h-menu-r__body">
                        <ul class="basket-list">
                            <?
                            //Есть один товар в наличие
                            $showPickup = false;
                            ?>
                            <? foreach ($basketItems as $basketItem): ?>

                                <li class="basket-list__item" data-id="<?= $basketItem->getProductId(); ?>">
                                    <?php
                                    $mxResult = CCatalogSku::GetProductInfo($basketItem->getProductId());
                                    if (Helper::getAvalibleElement($basketItem->getProductId()) > 0) {
                                        $showPickup = true;
                                    }
                                    if (is_array($mxResult)) {
                                        $dbProps = CIBlockElement::GetList(
                                            false,
                                            [
                                                'ID' => $mxResult['ID'],//$good[array_key_first($good)]['ID'],
                                                'IBLOCK_ID' => 1,
                                            ],
                                            false,
                                            ['nTopCount' => '1'],
                                            ['ID', 'NAME', 'DETAIL_PICTURE', 'PROPERTY_SIZE_EU']
                                        );
                                        if ($arFields = $dbProps->GetNext()) {

                                            if ($arFields["DETAIL_PICTURE"] != false) {
                                                $imgPath = CFile::GetPath($arFields["DETAIL_PICTURE"]);
                                            } else {
                                                $imgPath = false;
                                            }
                                        };
                                    } else {
                                        ShowError('Это не торговое предложение');
                                    } ?>
                                    <div class="basket-list__img-w"><a
                                                href="<? $basketItem->getField['DETAIL_PAGE_URL'] ?>"
                                                class="basket-list__img"
                                                style="background-image: url(<?= $imgPath ?>);"></a></div>
                                    <div class="basket-list__cont">
                                        <?
                                        if ($arFields['IBLOCK_ID'] == \WebDvl\Core\Iblock\Helper::getIdByCode('catalog_offer')) {
                                            if ($arFields['PROPERTY_SIZE_EU']) {
                                                ?>
                                                <div class="basket-list__size">
                                                Размер <?= $arFields['PROPERTY_SIZE_EU']['VALUE'] ?></div><?
                                            }
                                        }
                                        ?>
                                        <h3 class="basket-list__name"><a
                                                    href=""><?= $basketItem->getField('NAME'); ?></a></h3>
                                        <div class="price">
                                            <div class="price__current"><?= Helper::getFormatPrice($basketItem->getPrice()); ?></div>
                                        </div>
                                        <div class="basket-list__footer">
                                            <div class="spinner">
                                                <button class="spinner__btn spinner__btn--down"></button>
                                                <input class="spinner__input" type="text"
                                                       data-id="<?= $basketItem->getProductId() ?>"
                                                       value="<?= $basketItem->getQuantity(); ?>">
                                                <button class="spinner__btn spinner__btn--up"></button>
                                            </div>
                                            <button class="to-favorites"><span>В избранное</span>
                                                <div class="to-favorites__icon-w">
                                                    <svg class="icon icon--heart-straight">
                                                        <use href="<?= SITE_TEMPLATE_PATH ?>/img/sprite.svg#heart-straight"></use>
                                                    </svg>
                                                    <svg class="icon icon--heart">
                                                        <use href="<?= SITE_TEMPLATE_PATH ?>/img/sprite.svg#heart"></use>
                                                    </svg>
                                                </div>
                                            </button>

                                        </div>
                                        <button class="btn-trash sb_btn_product">
                                            <svg class="icon icon--trash">
                                                <use href="<?= SITE_TEMPLATE_PATH ?>/img/sprite.svg#trash"></use>
                                            </svg>
                                        </button>
                                    </div>
                                </li>
                            <? endforeach ?>
                        </ul>
                        <? if (count($basketItems) != 0): ?>
                            <ul class="inf-list">
                                <li class="inf-list__item">
                                    <svg class="icon icon--tag">
                                        <use href="<?= SITE_TEMPLATE_PATH ?>/img/sprite.svg#tag"></use>
                                    </svg>
                                    <?
                                    $procent = Option::get('webdvl.core', 'bonus_procent', 1);
                                    $bonus = ($price / 100) * $procent;
                                    ?>
                                    <div>За заказ вы получите до <b><?= round($bonus) ?> бонусов</b> (
                                        =<?= round($bonus) ?> ₽)
                                    </div>
                                </li>
                                <? if ($showPickup == false): ?>
                                    <li class="inf-list__item">
                                        <svg class="icon icon--airplane-tilt-2">
                                            <use href="<?= SITE_TEMPLATE_PATH ?>/img/sprite.svg#airplane-tilt-2"></use>
                                        </svg>
                                        <div><b>Доставка</b> — среда, 27 сентября, или позже, от 550 ₽</div>
                                    </li>
                                <? else: ?>
                                    <li class="inf-list__item">
                                        <svg class="icon icon--box-2">
                                            <use href="<?= SITE_TEMPLATE_PATH ?>/img/sprite.svg#box-2"></use>
                                        </svg>
                                        <div><b>Забрать в магазине</b> — Сегодня, или позже, бесплатно</div>
                                    </li>
                                <? endif; ?>
                            </ul>
                        <? else: ?>
                            <p>Ваша Корзинка Пустая :(</p>
                        <? endif; ?>
                    </div>
                </div>
                <?
                if ($price > 0):?>
                    <div class="basket-menu__footer">
                        <a href="/order/" class="btn btn--viking">оформить заказ
                            на <?= Helper::getFormatPrice($price) ?></a>
                    </div>
                <? endif; ?>
            </div>
        </div>
        <?
        $basketMini = ob_get_clean();
        return $basketMini;
    }

    /**
     * Вернет количество товаров в корзине
     * @return false|string
     * @throws ArgumentException
     * @throws ArgumentTypeException
     * @throws NotImplementedException
     */
    public function getBasketCountAction()
    {
        $basket = Basket::loadItemsForFUser(Fuser::getId(), Context::getCurrent()->getSite());
        /** @var BasketItemCollection $basketItems */
        $basketItems = $basket->getBasketItems(); // массив объектов Sale\BasketItem
        $countBasket = count($basket->getBasketItems());
        return $countBasket;
    }

    public function clearAndAddBasketAction($item, $quantity)
    {
        global $USER;
        $userId = $USER->GetID();
        $basket = Basket::loadItemsForFUser(Fuser::getId(), Context::getCurrent()->getSite());


        $basket->clearCollection();
        $basket->save();
        self::addProductBasketAction($item);

    }

    public static function dadatacityAction($city)
    {
        return (new City())->setData([
            'query' => $city
        ])->execute();
    }
    public static function dadataaddressAction($address,$city)
    {
        return (new Street())->setData([
            'query' => $address,
            'city' => $city
        ])->execute();
    }
}

<?

use Bitrix\Main\Loader;
use Bitrix\Main\SystemException;
use Bitrix\Main\Event;
use Bitrix\Sale;
use Bitrix\Sale\Delivery\Services\Manager;
use Bitrix\Catalog\PriceTable;
if ($_REQUEST['mode'] !== 'import') { // FIX 1c

    \Bitrix\Main\Loader::includeModule('poligon.core');
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/test/sb_site/init.php')) {
        include_once $_SERVER['DOCUMENT_ROOT'] . '/test/sb_site/init.php';
    }


    //require_once("sb_tools/init.php"); //подключение общих классов
    //require_once("sb_site/init.php");


    // Roistat content BEGIN


    AddEventHandler("iblock", "OnBeforeIBlockElementAdd", "sort_pic");
    AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", "sort_pic");
    function sort_pic(&$arFields)
    {
        if (empty($arFields['DETAIL_PICTURE'])) {
            $arFields['SORT'] = 90000;
        }

        /*
                $arParams = array("replace_space"=>"-","replace_other"=>"-");
                $CODE = Cutil::translit(trim($arFields['NAME']),"ru",$arParams);
                $arFields['NAME'] = trim($arFields['NAME']);
                $arFields['CODE'] = trim($CODE);
                $arFields['SEARCHABLE_CONTENT'] = trim($arFields['SEARCHABLE_CONTENT']);
        */
    }

    $eventManager = \Bitrix\Main\EventManager::getInstance();
    $eventManager->addEventHandler('sale', 'OnSaleOrderSaved', 'rsOnAddOrder');

    function rsOnAddOrder(Event $event)
    {
        if (!$event->getParameter('IS_NEW')) return;
        /** @var Sale\Order $order */
        $order = $event->getParameter('ENTITY');
        $basket = $order->getBasket();
        $propertyCollection = $order->getPropertyCollection();

        $products = array();
        $items = $basket->getBasketItems();
        foreach ($items as $item) {
            $products[] = array(
                'id' => $item->getId(),
                'name' => $item->getField('NAME'),
                'price' => $item->getPrice(),
                'count' => $item->getQuantity(),
            );
        }
        $list = null;
        foreach ($basket->getListOfFormatText() as $item) {
            $list .= $item . "\n";
        }

        $price = $order->getPrice();
        $discount = $order->getDiscountPrice();
        $description = $order->getField('USER_DESCRIPTION');
        $userName = null;
        $phone = null;
        $email = null;
        $address = null;
        $location = null;


        foreach ($propertyCollection as $property) {
            $code = $property->getField('CODE');
            $value = $property->getValue();
            // Если в заказе есть какие либо доп. поля, их нужно указать тут.
            switch ($code) {
                case 'PHONE':
                    $phone = $value;
                    break;
                case 'EMAIL':
                    $email = $value;
                    break;
                case 'F_NAME':
                    $userName = $value;
                    break;
                case 'LOCATION':
                    $location = CSaleLocation::GetByID(CSaleLocation::getLocationIDbyCODE($value));
                    break;
                case 'ADDRESS':
                    $address = $value;
                    break;
            }
        }

        $paymentCollection = $order->getPaymentCollection();
        $paymentName = $paymentCollection['0']->getPaymentSystemName();
        $deliverySystemId = $order->getDeliverySystemId();
        $managerById = Manager::getById($deliverySystemId['0']);
        $deliveryName = $managerById['NAME'];
        $form_name = "Корзина";

        // Следующим образом можно быстро определить не в 1 клик ли заказ
        if (array_key_exists('BUY_MODE', $_REQUEST) !== false) {
            $form_name = "В 1 клик";
            $userName = iconv('UTF-8', SITE_CHARSET, $_REQUEST['NAME']);
            $phone = $_REQUEST['PHONE'];
            $email = $_REQUEST['EMAIl'];
        }

        $comment = "{$description} \n";

        $comment .= "\n\nСписок товаров:\n" .
            "{$list}\n\n" .
            "Способ доставки: {$deliveryName}\n" .
            "Способ оплаты: {$paymentName}\n";

        if ($order->getDeliveryPrice() > 0) {
            $comment .= 'Доставка - ' . number_format($order->getDeliveryPrice(), 0, '', ' ') . " руб\n";
        }

        if ($discount > 0) {
            $comment .= 'Скидка - ' . number_format($discount, 0, '', ' ') . " руб\n";
        }
        $comment .= "Итого - {$price} руб";

        $roistatData = array(
            'roistat' => isset($_COOKIE['roistat_visit']) ? $_COOKIE['roistat_visit'] : null,
            'key' => 'ZTI4NzNmNWNkNjc5MzIwZTM2NWE2YTM0Nzg3ZDUxY2U6MjEwNDg1',
            'comment' => $comment,
            'title' => "Заказ № " . $order->getId(),
            'name' => $userName,
            'email' => $email,
            'phone' => $phone,
            'is_need_check_order_in_processing' => '0',
            'is_need_check_order_in_processing_append' => '0',
            'is_skip_sending' => '0',
            'fields' => array(
                "roistat_marker" => isset($_COOKIE['roistat_marker']) ? $_COOKIE['roistat_marker'] : "-",
                "form" => $form_name,
                "location" => $location,
                "adress" => $address,
                "shipping_method" => $deliveryName,
                "payment_method" => $paymentName,
                "price" => $price,
                "price_delivery" => $order->getDeliveryPrice(),
                "discount" => $discount,
                "products" => $products,
                "comment" => $description,
            ),
        );
        file_get_contents("https://cloud.roistat.com/api/proxy/1.0/leads/add?" . http_build_query($roistatData));
    }

    // Roistat END


    function clean_expire_cache($path = "")
    {
        if (!class_exists("CFileCacheCleaner")) {
            require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/classes/general/cache_files_cleaner.php");
        }
        $curentTime = mktime();
        if (defined("BX_CRONTAB") && BX_CRONTAB === true) $endTime = time() + 5; //Если на кроне, то работаем 5 секунд
        else $endTime = time() + 1; //Если на хитах, то не более секунды
        //Работаем со всем кешем
        $obCacheCleaner = new CFileCacheCleaner("all");
        if (!$obCacheCleaner->InitPath($path)) {
            //Произошла ошибка
            return "clean_expire_cache();";
        }
        $obCacheCleaner->Start();
        while ($file = $obCacheCleaner->GetNextFile()) {
            if (is_string($file)) {
                $date_expire = $obCacheCleaner->GetFileExpiration($file);
                if ($date_expire) {
                    if ($date_expire < $curentTime) {
                        unlink($file);
                    }
                }
                if (time() >= $endTime) break;
            }
        }
        if (is_string($file)) {
            return "clean_expire_cache(\"" . $file . "\");";
        } else {
            return "clean_expire_cache();";
        }
    }

    class AllProductDiscount
    {
        /**
         * @return XML_ID|array
         * @throws SystemException
         * @throws \Bitrix\Main\LoaderException
         */
        public static function getFull($arrFilter = array(), $arSelect = array())
        {
            if (!Loader::includeModule('sale')) throw new SystemException('Не подключен модуль Sale');

            //Все товары со скидкой!!!
            // Группы пользователей
            global $USER;
            $arUserGroups = $USER->GetUserGroupArray();
            if (!is_array($arUserGroups)) $arUserGroups = array($arUserGroups);
            // Достаем старым методом только ID скидок привязанных к группам пользователей по ограничениям
            $actionsNotTemp = \CSaleDiscount::GetList(array("ID" => "ASC"), array("USER_GROUPS" => $arUserGroups), false, false, array("ID"));
            while ($actionNot = $actionsNotTemp->fetch()) {
                $actionIds[] = $actionNot['ID'];
            }
            $actionIds = array_unique($actionIds);
            sort($actionIds);
            // Подготавливаем необходимые переменные для разборчивости кода
            global $DB;
            $conditionLogic = array('Equal' => '=', 'Not' => '!', 'Great' => '>', 'Less' => '<', 'EqGr' => '>=', 'EqLs' => '<=');
            $arSelect = array_merge(array("ID", "IBLOCK_ID", "XML_ID"), $arSelect);
            $city = 'MSK';
            // Теперь достаем новым методом скидки с условиями. P.S. Старым методом этого делать не нужно из-за очень высокой нагрузки (уже тестировал)
            $actions = \Bitrix\Sale\Internals\DiscountTable::getList(array(
                'select' => array("ID", "ACTIONS_LIST"),
                'filter' => array("ACTIVE" => "Y", "USE_COUPONS" => "N", "DISCOUNT_TYPE" => "P", "LID" => SITE_ID,
                    "ID" => $actionIds,
                    array(
                        "LOGIC" => "OR",
                        array(
                            "<=ACTIVE_FROM" => $DB->FormatDate(date("Y-m-d H:i:s"), "YYYY-MM-DD HH:MI:SS", \CSite::GetDateFormat("FULL")),
                            ">=ACTIVE_TO" => $DB->FormatDate(date("Y-m-d H:i:s"), "YYYY-MM-DD HH:MI:SS", \CSite::GetDateFormat("FULL"))
                        ),
                        array(
                            "=ACTIVE_FROM" => false,
                            ">=ACTIVE_TO" => $DB->FormatDate(date("Y-m-d H:i:s"), "YYYY-MM-DD HH:MI:SS", \CSite::GetDateFormat("FULL"))
                        ),
                        array(
                            "<=ACTIVE_FROM" => $DB->FormatDate(date("Y-m-d H:i:s"), "YYYY-MM-DD HH:MI:SS", \CSite::GetDateFormat("FULL")),
                            "=ACTIVE_TO" => false
                        ),
                        array(
                            "=ACTIVE_FROM" => false,
                            "=ACTIVE_TO" => false
                        ),
                    ))
            ));
            // Перебираем каждую скидку и подготавливаем условия фильтрации для CIBlockElement::GetList
            while ($arrAction = $actions->fetch()) {
                $arrActions[$arrAction['ID']] = $arrAction;
            }
            foreach ($arrActions as $actionId => $action) {
                $arPredFilter = array_merge(array("ACTIVE_DATE" => "Y", "CAN_BUY" => "Y"), $arrFilter); //Набор предустановленных параметров
                $arFilter = $arPredFilter; //Основной фильтр
                $dopArFilter = $arPredFilter; //Фильтр для доп. запроса
                $dopArFilter["=XML_ID"] = array(); //Пустое значения для первой отработки array_merge
                //Магия генерации фильтра
                foreach ($action['ACTIONS_LIST']['CHILDREN'] as $condition) {
                    foreach ($condition['CHILDREN'] as $keyConditionSub => $conditionSub) {
                        $cs = $conditionSub['DATA']['value']; //Значение условия
                        $cls = $conditionLogic[$conditionSub['DATA']['logic']]; //Оператор условия
                        //$arFilter["LOGIC"]=$conditionSub['DATA']['All']?:'AND';
                        $CLASS_ID = explode(':', $conditionSub['CLASS_ID']);

                        if ($CLASS_ID[0] == 'ActSaleSubGrp') {
                            foreach ($conditionSub['CHILDREN'] as $keyConditionSubElem => $conditionSubElem) {
                                $cse = $conditionSubElem['DATA']['value']; //Значение условия
                                $clse = $conditionLogic[$conditionSubElem['DATA']['logic']]; //Оператор условия
                                //$arFilter["LOGIC"]=$conditionSubElem['DATA']['All']?:'AND';
                                $CLASS_ID_EL = explode(':', $conditionSubElem['CLASS_ID']);

                                if ($CLASS_ID_EL[0] == 'CondIBProp') {
                                    $arFilter["IBLOCK_ID"] = $CLASS_ID_EL[1];
                                    $arFilter[$clse . "PROPERTY_" . $CLASS_ID_EL[2]] = array_merge((array)$arFilter[$clse . "PROPERTY_" . $CLASS_ID_EL[2]], (array)$cse);
                                    $arFilter[$clse . "PROPERTY_" . $CLASS_ID_EL[2]] = array_unique($arFilter[$clse . "PROPERTY_" . $CLASS_ID_EL[2]]);
                                } elseif ($CLASS_ID_EL[0] == 'CondIBName') {
                                    $arFilter[$clse . "NAME"] = array_merge((array)$arFilter[$clse . "NAME"], (array)$cse);
                                    $arFilter[$clse . "NAME"] = array_unique($arFilter[$clse . "NAME"]);
                                } elseif ($CLASS_ID_EL[0] == 'CondIBElement') {
                                    $arFilter[$clse . "ID"] = array_merge((array)$arFilter[$clse . "ID"], (array)$cse);
                                    $arFilter[$clse . "ID"] = array_unique($arFilter[$clse . "ID"]);
                                } elseif ($CLASS_ID_EL[0] == 'CondIBTags') {
                                    $arFilter[$clse . "TAGS"] = array_merge((array)$arFilter[$clse . "TAGS"], (array)$cse);
                                    $arFilter[$clse . "TAGS"] = array_unique($arFilter[$clse . "TAGS"]);
                                } elseif ($CLASS_ID_EL[0] == 'CondIBSection') {
                                    $arFilter[$clse . "SECTION_ID"] = array_merge((array)$arFilter[$clse . "SECTION_ID"], (array)$cse);
                                    $arFilter[$clse . "SECTION_ID"] = array_unique($arFilter[$clse . "SECTION_ID"]);
                                } elseif ($CLASS_ID_EL[0] == 'CondIBXmlID') {
                                    $arFilter[$clse . "XML_ID"] = array_merge((array)$arFilter[$clse . "XML_ID"], (array)$cse);
                                    $arFilter[$clse . "XML_ID"] = array_unique($arFilter[$clse . "XML_ID"]);
                                } elseif ($CLASS_ID_EL[0] == 'CondBsktAppliedDiscount') { //Условие: Были применены скидки (Y/N)
                                    foreach ($arrActions as $tempAction) {
                                        if (($tempAction['SORT'] < $action['SORT'] && $tempAction['PRIORITY'] > $action['PRIORITY'] && $cse == 'N') || ($tempAction['SORT'] > $action['SORT'] && $tempAction['PRIORITY'] < $action['PRIORITY'] && $cse == 'Y')) {
                                            $arFilter = false;
                                            break 4;
                                        }
                                    }
                                }
                            }
                        } elseif ($CLASS_ID[0] == 'CondIBProp') {
                            $arFilter["IBLOCK_ID"] = $CLASS_ID[1];
                            $arFilter[$cls . "PROPERTY_" . $CLASS_ID[2]] = array_merge((array)$arFilter[$cls . "PROPERTY_" . $CLASS_ID[2]], (array)$cs);
                            $arFilter[$cls . "PROPERTY_" . $CLASS_ID[2]] = array_unique($arFilter[$cls . "PROPERTY_" . $CLASS_ID[2]]);
                        } elseif ($CLASS_ID[0] == 'CondIBName') {
                            $arFilter[$cls . "NAME"] = array_merge((array)$arFilter[$cls . "NAME"], (array)$cs);
                            $arFilter[$cls . "NAME"] = array_unique($arFilter[$cls . "NAME"]);
                        } elseif ($CLASS_ID[0] == 'CondIBElement') {
                            $arFilter[$cls . "ID"] = array_merge((array)$arFilter[$cls . "ID"], (array)$cs);
                            $arFilter[$cls . "ID"] = array_unique($arFilter[$cls . "ID"]);
                        } elseif ($CLASS_ID[0] == 'CondIBTags') {
                            $arFilter[$cls . "TAGS"] = array_merge((array)$arFilter[$cls . "TAGS"], (array)$cs);
                            $arFilter[$cls . "TAGS"] = array_unique($arFilter[$cls . "TAGS"]);
                        } elseif ($CLASS_ID[0] == 'CondIBSection') {
                            $arFilter[$cls . "SECTION_ID"] = array_merge((array)$arFilter[$cls . "SECTION_ID"], (array)$cs);
                            $arFilter[$cls . "SECTION_ID"] = array_unique($arFilter[$cls . "SECTION_ID"]);
                        } elseif ($CLASS_ID[0] == 'CondIBXmlID') {
                            $arFilter[$cls . "XML_ID"] = array_merge((array)$arFilter[$cls . "XML_ID"], (array)$cs);
                            $arFilter[$cls . "XML_ID"] = array_unique($arFilter[$cls . "XML_ID"]);
                        } elseif ($CLASS_ID[0] == 'CondBsktAppliedDiscount') { //Условие: Были применены скидки (Y/N)
                            foreach ($arrActions as $tempAction) {
                                if (($tempAction['SORT'] < $action['SORT'] && $tempAction['PRIORITY'] > $action['PRIORITY'] && $cs == 'N') || ($tempAction['SORT'] > $action['SORT'] && $tempAction['PRIORITY'] < $action['PRIORITY'] && $cs == 'Y')) {
                                    $arFilter = false;
                                    break 3;
                                }
                            }
                        }
                    }
                }
                if ($arFilter !== false && $arFilter != $arPredFilter) {
                    if (!isset($arFilter['=XML_ID'])) {
                        //Делаем запрос по каждому из фильтров, т.к. один фильтр не получится сделать из-за противоречий условий каждой скидки
                        $res = \CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
                        while ($ob = $res->GetNextElement()) {
                            $arFields = $ob->GetFields();
                            $poductsArray['IDS'][] = $arFields["ID"];
                        }
                    } elseif (!empty($arFilter['=XML_ID'])) {
                        //Подготавливаем массив для отдельного запроса
                        $dopArFilter['=XML_ID'] = array_unique(array_merge($arFilter['=XML_ID'], $dopArFilter['=XML_ID']));
                    }
                }
            }

            if (isset($dopArFilter) && !empty($dopArFilter['=XML_ID'])) {
                //Делаем отдельный запрос по конкретным XML_ID
                $res = \CIBlockElement::GetList(array(), $dopArFilter, false, array("nTopCount" => count($dopArFilter['=XML_ID'])), $arSelect);
                while ($ob = $res->GetNextElement()) {
                    $arFields = $ob->GetFields();
                    $poductsArray['IDS'][] = $arFields["ID"];
                }
            }

            //$poductsArray['ids'] = array_unique($poductsArray['ids']);
            //$poductsArray['ids'] = $poductsArray['ids'];

            return $poductsArray;
        }
    }

} // fix 1C  if ( $_REQUEST[ 'mode' ] == 'import' )

AddEventHandler("iblock", "OnBeforeIBlockSectionUpdate", "MyOnBeforeIBlockSectionUpdate");
function MyOnBeforeIBlockSectionUpdate(&$arFields)
{
    if (in_array($arFields["ID"], [3424, 3425, 3426, 3427, 3428, 3429]) && ($arFields["IBLOCK_ID"] == 180)) {
        unset($arFields["ACTIVE"]);
    }
}



if (isset($_SERVER['HTTP_REFERER']))
{
    /*
    *   При просмотре сайта через Яндекс.Метрику
    *   не запрещать показывать сайт во фрейме
    */

    $metrikaHosts = [
        'webvisor.com',
        'metrika.yandex',
        'metrika.yandex.ru',
        'metrika.yandex.ua',
        'metrika.yandex.com',
        'metrika.yandex.by',
        'metrika.yandex.kz',
        $_SERVER['HTTP_HOST'],
    ];

    $refHost = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);

    if (in_array($refHost, $metrikaHosts))
    {
        define('BX_SECURITY_SKIP_FRAMECHECK', true);
    }
}
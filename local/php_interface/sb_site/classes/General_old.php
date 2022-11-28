<?php
/**
 * Created by PhpStorm.
 * User: dnkolosov
 * Date: 09.02.2017
 * Time: 9:21
 */

namespace SB\Site;

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Application;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Sale;
use COption;

class General_old
{

    private static $catalogType;

    static function getSubDomain($removeWWW = true)
    {
        if ($removeWWW) {
            $arSearch[] = 'www.';
        }

        $arSearch[] = SITE_SERVER_NAME != null ? SITE_SERVER_NAME : $_SERVER['SERVER_NAME'];

        return trim(str_replace($arSearch, '', $_SERVER['SERVER_NAME']), '.');
    }

    static function getPartnerCode()
    {
        if (!$code = self::getSubDomain()) {
            return false;
        }

        if (self::getSubDomain() == $_SERVER['SERVER_NAME']) {
            $arPartners = Partner::getByFilter(['PROPERTY_ALIAS' => $code]);

            if (!$arPartners) {
                return false;
            }

            if (count($arPartners) > 1) {
                throw new \Exception('more than one alias');
            }

            return $arPartners[0]->getData('CODE');
        }

        return $code;
    }

    static function setCatalogType($value)
    {
        if (!$value) {
            throw new ArgumentNullException('value is require parameter');
        }

        self::$catalogType = $value;
    }

    static function getCatalogType()
    {
        return self::$catalogType;
    }

    static function getRealURI()
    {
        $uri = $_SERVER["REAL_FILE_PATH"] ? $_SERVER["REAL_FILE_PATH"] : $_SERVER["PHP_SELF"];

        //        if($_SERVER["QUERY_STRING"])
        //            $uri .= "?" . $_SERVER["QUERY_STRING"];

        return $uri;
    }

    static function getPathForCatalog($path = '/', $replace = [])
    {
        if ($replace) {
            $path = str_replace('#REAL#', $replace[self::getCatalogType()], $path);
        }

        return self::getCatalogType() === 'man' ? "/man{$path}" : $path;
    }

    static function showMessage($arMess, $container = 'div', $template = 'main', $type = 'ERROR')
    {
        global $APPLICATION;

        if (!is_array($arMess)) {
            $arMess = Array("MESSAGE" => $arMess, "TYPE" => $type);
        }

        if ($arMess["MESSAGE"] == "") {
            return;
        }

        $APPLICATION->IncludeComponent("bitrix:system.show_message", $template, Array(
            "MESSAGE" => $arMess["MESSAGE"],
            "STYLE" => ($arMess["TYPE"] == "OK" ? "note-text" : "error-text"),
            "TAG" => $container
        ), null, array("HIDE_ICONS" => "Y"));
    }

    static function sort($a, $b)
    {
        //предопределенное положение полей, остальные идут после них
        $arOrder = Array(
            "LOGIN",
            "EMAIL",
            "PASSWORD",
            "CONFIRM_PASSWORD"
        );

        $aKey = array_search($a, $arOrder);
        $bKey = array_search($b, $arOrder);

        if ($aKey === $bKey) {
            return 0;
        }

        //если равен false, то его нет в массиве
        if ($aKey === false) {
            return 1;
        }

        //если равен false, то его нет в массиве
        if ($bKey === false) {
            return -1;
        }

        return ($aKey < $bKey) ? -1 : 1;
    }

    static function getPicture($photo)
    {
        $arImg = explode("/", $photo);
        $newName = '_' . end($arImg);
        end($arImg);
        $key = key($arImg);

        $arImg[$key] = $newName;
        $img = implode("/", $arImg);

        return "/upload/catalog_import/images{$img}";
    }

    static function checkMobile($userAgent)
    {
        return preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',
            $userAgent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',
            substr($userAgent, 0, 4)) ? true : false;
    }

    static function sortFilterValues($a, $b)
    {
        if ($a["UPPER"] === $b["UPPER"]) {
            return 0;
        }

        return $a["UPPER"] < $b["UPPER"] ? -1 : 1;
    }

    static function getMinPrice(array $arCatalogPrices = array())
    {
        $boolStartMin = true;
        $dblMinPrice = 0;

        foreach ($arCatalogPrices as $key => $priceResult) {
            if ($priceResult['CAN_BUY'] !== 'Y') {
                continue;
            }

            if ($boolStartMin) {
                $dblMinPrice = $priceResult['DISCOUNT_VALUE'];
                $strMinCode = $key;
                $boolStartMin = false;
            } else {
                $dblComparePrice = $priceResult['DISCOUNT_VALUE'];

                if ($dblMinPrice > $dblComparePrice) {
                    $dblMinPrice = $dblComparePrice;
                    $strMinCode = $key;
                }
            }
        }

        if ($strMinCode) {
            return $arCatalogPrices[$strMinCode];
        }
    }


    /*
     * Возращается массив с GeoIP
     */
    static public function getNameCityGeoIP()
    {

        $locId = $_COOKIE['YS_GEO_IP_LOC_ID'];
        $arRes = \CSaleLocation::GetList(array(), array(
            'ID' => $locId,
            'CITY_LID' => LANGUAGE_ID
        ), false, false, array())->Fetch();

        return $arRes;
    }

    /*
     * Вернем из инфоблока информацию по городу GeoIP
     */
    static public function getInfoCityGeoIP()
    {
        $IBLOCK_ID_CITY = 27;
        $arCityGeoIP = CityManager::getInstance()->getCity()->code;

        $ElementCity = \SB_OLD\IBlock::getElement(array(
            "IBLOCK_ID" => IntVal($IBLOCK_ID_CITY),
            "ACTIVE" => "Y",
            "%CODE" => $arCityGeoIP
        ), array(
            'ID',
            'NAME',
            'PROPERTY_BIT_ADRESS',
            'PROPERTY_BIT_PHONE',
            'PROPERTY_BIT_EMAIL',
            'PROPERTY_BIT_TIME_WORCK',
            'PROPERTY_BIT_META'
        ));

        if (isset($ElementCity)) {
            $arInfoCity = array();
            $arInfoCity['ID'] = $ElementCity['ID'];
            $arInfoCity['NAME'] = $ElementCity['NAME'];
            $arInfoCity['ADDRESS'] = $ElementCity['PROPERTY_BIT_ADRESS_VALUE']['TEXT'];
            $arInfoCity['PHONE'] = $ElementCity['PROPERTY_BIT_PHONE_VALUE']['TEXT'];
            $arInfoCity['EMAIL'] = $ElementCity['PROPERTY_BIT_EMAIL_VALUE']['TEXT'];
            $arInfoCity['TIME_WORCK'] = $ElementCity['PROPERTY_BIT_TIME_WORCK_VALUE']['TEXT'];
            $arInfoCity['META'] = $ElementCity['PROPERTY_BIT_META_VALUE']['TEXT'];

            return $arInfoCity;
        }
        return false;
    }

    /*
        * Узнаем e-mail пользователя по заказу.
        */
    static public function getOwnerEmail($orderId)
    {
        $order = Sale\Order::load($orderId);
        $propertyCollection = $order->getPropertyCollection();
        $emailPropValue = $propertyCollection->getUserEmail()->getValue();
        if (isset($emailPropValue)) {
            return $emailPropValue;
        }

        return false;
    }

    /*
        * Узнаем e-mail пользователя по заказу.
        */
    static public function getUserEmail($orderId)
    {

        $arUser = \Bitrix\Main\UserTable::getById($orderId)->fetch();
        if (isset($arUser['EMAIL'])) {
            return $arUser['EMAIL'];
        }

        return false;
    }

    static public function Log($namefile, $message)
    {
        $patch = '/home/bitrix/ext_www/dev.aitech1.ru/local/log/';
        $patch .= $namefile;
        file_put_contents($patch, print_r($message, true) . "\r\n", FILE_APPEND);
    }

    public static function InDir($strDir): bool
    {
        /** @global \CMain $APPLICATION */
        global $APPLICATION;
        return (0 === strpos($APPLICATION->GetCurPage(true), $strDir));
    }

    public static function getPropOrder($ORDER_ID,$runtime = ['LAST_NAME', 'NAME', 'SECOND_NAME', 'CONTACT_PERSON', 'PHONE', 'EMAIL', 'CITY', 'ADDRESS'])
    {
        \CModule::IncludeModule('sale');
        \CModule::IncludeModule('main');
        \CModule::IncludeModule('catalog');

        $arSelect = array(
            'ID',
            'DATE_INSERT',
            'DELIVERY_ID',
            'PAY_SYSTEM_ID',
            'PERSON_TYPE_ID',
            'USER_ID',
        );

        foreach ($runtime as $valSelect) {
            $arSelect['PROP__' . $valSelect] = $valSelect . '.VALUE';
        }
        $arRuntime = array();
        if ($runtime) {
            foreach ($runtime as $item) {
                $arRuntime[] = new \Bitrix\Main\Entity\ReferenceField(
                    $item,
                    '\Bitrix\Sale\Internals\OrderPropsValueTable',
                    array(
                        '=this.ID' => 'ref.ORDER_ID',
                        '=ref.CODE' => new \Bitrix\Main\DB\SqlExpression('?s', $item)
                    )
                );
            }
        }
        $orderFilter = array('=ID' => $ORDER_ID);
        $params = array(
            'filter' => $orderFilter,
            'select' => $arSelect,
            'runtime' => $arRuntime
        );
        $dbOrderList = \Bitrix\Sale\Internals\OrderTable::getList($params);
        while ($arOrderList = $dbOrderList->fetch())
        {
            if ($arOrderList['DELIVERY_ID']) {
                $arOrderList['~DELIVERY_ID'] = $arOrderList['DELIVERY_ID'];
                $arOrderList['DELIVERY_NAME'] = Sale\Delivery\Services\Manager::getObjectById($arOrderList['DELIVERY_ID'])->getNameWithParent();
                $arOrderList['DELIVERY_STORE'] = self::getStoresName($ORDER_ID);
            }

            if ($arOrderList['PAY_SYSTEM_ID']) {
                $arOrderList['PAY_SYSTEM_ID'] = \Bitrix\Sale\PaySystem\Manager::getObjectById($arOrderList['PAY_SYSTEM_ID'])->getField('NAME');
            }

            if ($arOrderList['PROP__LOCATION']) {
                $arOrderList['PROP__LOCATION'] = self::getLocation($arOrderList['PROP__LOCATION']);
            }
            EventHandlers::OnSaleComponentOrderOneStepCompleteHandler($ORDER_ID,$arOrderList);

            return $arOrderList;
        }
    }

    function getLocation($location)
    {
        $place = '';
        $separator = ', ';
        $svd = ['COUNTRY', 'REGION', 'CITY', 'STREET'];

        if (\Bitrix\Main\Loader::includeModule('sale')) {
            if (strlen($location) == 10)
                $arFilter = array('=CODE' => $location);
            else
                $arFilter = array('=ID' => $location);
            $result = \Bitrix\Sale\Location\LocationTable::getPathToNodeByCondition($arFilter, array(
                'select' => array('CHAIN' => 'NAME.NAME', 'DETAIL' => 'TYPE.CODE'),
                'filter' => array('NAME.LANGUAGE_ID' => LANGUAGE_ID)
            ));
            while ($element = $result->Fetch())
                if (in_array($element['DETAIL'], $svd))
                    $place .= $element['CHAIN'] . $separator;
        }

        return substr($place, 0, (strlen($place) - strlen($separator)));
    }

    function getDefLocationTypes()
    {
        return serialize(array('COUNTRY', 'REGION', 'CITY'));
    }

    public static function getStoresName($ORDER_ID)
    {
        \CModule::IncludeModule('catalog');
        $rsShipment = \Bitrix\Sale\Internals\ShipmentTable::getList(['filter' => ['ORDER_ID' => $ORDER_ID]]);

        while ($arShipment = $rsShipment->fetch()) {
            $rsExtraService = \Bitrix\Sale\Internals\ShipmentExtraServiceTable::getList(['filter' => ['SHIPMENT_ID' => $arShipment['ID']]]);
            while ($arExtraService = $rsExtraService->fetch()) {
                if (!!$arExtraService['VALUE']) {

                    $arStore = \Bitrix\Catalog\StoreTable::getById($arExtraService['VALUE'])->fetch();
                    return ' ' . $arStore['TITLE'];
                }
            }
        }
        return '';
    }

    public static function getOrderListArray($PRODUCT_ID)
    {
        \CModule::IncludeModule("sale");
        \CModule::IncludeModule("catalog");
        \CModule::IncludeModule("iblock");


        $arSelect = Array('ID', 'NAME', 'PROPERTY_CML2_ARTICLE');
        $arFilter = Array("IBLOCK_ID" => IntVal(\SB\Site\Variables::IBLOCK_ID_CATALOG), 'ID' => $PRODUCT_ID);
        $res = \CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect)->GetNext();
        $result = false;
        if ($res) {
            $result = $res['PROPERTY_CML2_ARTICLE_VALUE'];
        }
        return $result;

//        $rsElement = \CIblockElement::getList(array(), array("IBLOCK_ID" => 2, "ID" => $PRODUCT_ID), false, false, array("ID", "NAME", "PROPERTY_ARTICLE"));
//
//        if ($arElement = $rsElement->GetNext()) {
//            //$arElement["PROPERTY_ARTICLE_VALUE"];
//           // \_::d($arElement);
//        }

    }

    public static function getOrderListTable($ORDER_ID)
    {
        \CModule::IncludeModule("sale");
        \CModule::IncludeModule("main");

        $order = \Bitrix\Sale\Order::load($ORDER_ID);
        $basketList = '<table><thead><tr><th>Наименование</th><th>Кол-во</th><th>Цена</th><th>Сумма</th></tr></thead>';
        $basketList .= '<tbody>';
        /** @var Basket $basket */
        if ($basket = $order->getBasket()) {
            $basketItems = $basket->getBasketItems();
            foreach ($basketItems as $item) {
//                $basketList .='<tr><td>'.$item->getField('CML2_ARTICLE').'</td>';
                $basketList .= '<td>' . $item->getField('NAME') . '</td>';
                $basketList .= '<td>' . $item->getQuantity() . '</td>';
                $basketList .= '<td>' . $item->getPrice() . '</td>';
                $basketList .= '<td>' . $item->getQuantity() * $item->getPrice() . '</td></tr>';
            }
        }
        $basketList .= '</tbody>';
        $basketList .= '</table>';
        return $basketList;
    }

    public function getElementGarant($IBLOCK_ID, $arSelect = array(), $arFilter = array())
    {
        \CModule::IncludeModule("sale");
        \CModule::IncludeModule("main");
        $arResult = array();

        $res = \CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
        while ($ob = $res->GetNextElement()) {

            $arFields = $ob->GetFields();
            $arFields['PROPERTY'] = $ob->GetProperties();
            $arResult[] = $arFields;
        }
        return $arResult;
    }


    /*
     * ДЛЯ OnBeforeStoreProductUpdate
     * Создать фаил прайса добавленных товаров
     */
    static public function creatOutFilePrice($arElements)
    {
        \CModule::IncludeModule("iblock");
        \CModule::IncludeModule("catalog");

        $arSelect = ["ID", "IBLOCK_SECTION_ID", "NAME", "PROPERTY_CML2_ARTICLE"];
        $arFilter = [
            "IBLOCK_ID" => IntVal(\SB\Site\Variables::CATALOG_IBLOCK_ID),
            'ID' => $arElements,
            'ACTIVE' => 'Y'
        ];
        $res = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        $arElementsCatalog = [];
        while ($ob = $res->Fetch()) {
            $arElementsCatalog[] = $ob;
        }
        $arIdSection = array_column($arElementsCatalog, 'IBLOCK_SECTION_ID');


        $arSectionsCatalog = \Bitrix\Iblock\SectionTable::getList(array(
                'select' => array('ID', 'NAME', 'IBLOCK_SECTION_ID', 'DEPTH_LEVEL'),
                'filter' => array('IBLOCK_ID' => \SB\Site\Variables::CATALOG_IBLOCK_ID)
            )
        )->fetchAll();
        foreach ($arSectionsCatalog as $key => &$arSection) {
            $isElementUnset = true;
            foreach ($arElementsCatalog as $arElement) {
                if ($arSection['ID'] == $arElement['IBLOCK_SECTION_ID']) {
                    $isElementUnset = false;
                    $db_res = \CPrice::GetList(
                        array(),
                        array(
                            "PRODUCT_ID" => $arElement['ID'],
                            "CATALOG_GROUP_ID" => [1]//\SB\Site\Variables::PRICE_GROUP_CATALOG
                        )
                    );

                    $arPriceProduct = array();
                    while ($ar_res = $db_res->Fetch()) {
                        $arPrice = array(
                            'PRICE' => $ar_res["PRICE"],
                            'CATALOG_GROUP_ID' => $ar_res["CATALOG_GROUP_ID"],
                            'CATALOG_GROUP_NAME' => $ar_res["CATALOG_GROUP_NAME"],
                            'CAN_ACCESS' => $ar_res["CAN_ACCESS"],
                            'CAN_BUY' => $ar_res["CAN_BUY"]
                        );
                        $arPriceProduct[] = $arPrice;
                    }

                    $arElement['PRICE'] = $arPriceProduct;
                    $arElement['ARTICLE'] = $arElement['PROPERTY_CML2_ARTICLE_VALUE'];
                    $arSection['ITEMS'][] = $arElement;
                }
            }
//        \_::d($isElementUnset);
//        if ($isElementUnset) {
//            \_::d($arSectionsCatalog[$key]);
//            unset($arSectionsCatalog[$key]);
//        }else{
//            \_::d($arSectionsCatalog[$key]);
//        }

        }
        unset($arElementsCatalog);
        foreach ($arSectionsCatalog as $key => &$arSection) {
            if (self::isChildrenSection($arSectionsCatalog, $arSection['ID'])) {
                //есть что-то
            } else {
                if ($arSection['ITEMS']) {
                } else {
                    unset($arSectionsCatalog[$key]);
                }
            }

        }

        global $USER;

        $result = \Bitrix\Main\GroupTable::getList(array(
            'select' => array('ID', 'NAME'),
            'filter' => array(
                '=UserGroup:GROUP.USER_ID' => $USER->GetID(),
                '=ACTIVE' => 'Y',
                array(
                    'LOGIC' => 'OR',
                    '=UserGroup:GROUP.DATE_ACTIVE_FROM' => null,
                    '<=UserGroup:GROUP.DATE_ACTIVE_FROM' => date('d.m.Y'),
                ),
                array(
                    'LOGIC' => 'OR',
                    '=UserGroup:GROUP.DATE_ACTIVE_TO' => null,
                    '>=UserGroup:GROUP.DATE_ACTIVE_TO' => date('d.m.Y'),
                ),
                array(
                    'LOGIC' => 'OR',
                    '!=ANONYMOUS' => 'Y',
                    '=ANONYMOUS' => null
                )
            )
        ));
        $groups = array();
        $groups['ARTICLE'] = array('NAME' => 'Артикул');
        $groups[] = array('ID' => 5, 'NAME' => 'Розница');
        /*while ($row = $result->fetch()) {
            if (in_array($row['ID'], \SB\Site\Variables::PRICE_GROUP_USER))
                $groups[] = $row;
        }*/


        if ($arSectionsCatalog) {
            /*Создаем Эксель файл*/
            try {

                $document = new \PHPExcel();
            } catch (\Exception $exception) {

            }


            $sheet = $document->setActiveSheetIndex(0); // Выбираем первый лист в документе

            $columnPosition = 0; // Начальная координата x
            $startLine = 0; // Начальная координата y

// Вставляем заголовок в "A2"
            $sheet->setCellValueByColumnAndRow($columnPosition, $startLine, 'Прайс лист от' . date('d.m.Y h:i'));
            $startLine++;
// Массив с названиями столбцов
            /*TODO: проверить группы пользователя*/


// Указатель на первый столбец
            $currentColumn = $columnPosition;

// Формируем шапку
            $sheet->setCellValueByColumnAndRow($currentColumn, $startLine, 'Название');
            $currentColumn++;
            foreach ($groups as $column) {

                $sheet->setCellValueByColumnAndRow($currentColumn, $startLine, $column['NAME']);
                // Смещаемся вправо
                $currentColumn++;
            }
// Формируем тело
            foreach ($arSectionsCatalog as $arSection) {
                // Перекидываем указатель на следующую строку
                $startLine++;
                // Указатель на первый столбец
                $currentColumn = $columnPosition;

                /*TODO: Можно задать заливку цветом для понятности что это группа*/
                $sheet->setCellValueByColumnAndRow($currentColumn, $startLine, $arSection['NAME']);

                $sheet->getStyleByColumnAndRow($currentColumn, $startLine)
                    ->getFill()
                    ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('e6e4e4');
                if (!empty($arSection['ITEMS'])) {
                    foreach ($arSection['ITEMS'] as $arItem) {
                        $startLine++;
                        $currentColumn = $columnPosition;
                        $sheet->setCellValueByColumnAndRow($currentColumn, $startLine, $arItem['NAME']);
                        $currentColumn++;
                        $sheet->setCellValueByColumnAndRow($currentColumn, $startLine, $arItem['ARTICLE']);
                        foreach ($arItem['PRICE'] as $keyPrice => $arPrice) {
                            if ($arPrice['CAN_BUY'] == 'Y') {
                                $currentColumn++;
                                $sheet->setCellValueByColumnAndRow($currentColumn, $startLine, $arPrice['PRICE']);
                            }
                        }
                    }
                }
            }


            $objWriter = \PHPExcel_IOFactory::createWriter($document, 'Excel2007');

            $newNameFile = $_SERVER['DOCUMENT_ROOT'] . '/upload/tmp/price_' . date('d.m.Y') . '.php';
            $NameFileForEmail = '/upload/tmp/price_' . date('d.m.Y') . '.xlsx';

            $objWriter->save(str_replace('.php', '.xlsx', $newNameFile));
        }
        unset($arSectionsCatalog);

        return $NameFileForEmail;
    }

    /*
        * ДЛЯ OnBeforeStoreProductUpdate
     */
    static public function isChildrenSection($arSection, $id)
    {
        foreach ($arSection as $Section) {
            if ($Section['IBLOCK_SECTION_ID'] == $id) {
                if ($Section['ITEMS']) {
                    return true;
                }
            }
        }
        return false;
    }

    /*
        * ДЛЯ OnBeforeStoreProductUpdate
      */
    static public function addPosting($linkFilePrice)
    {
        \CModule::IncludeModule("subscribe");
        $body = "<a href='{$linkFilePrice}'>Скачать прайс лист товаров</a>";

        $arFields = Array(
            "FROM_FIELD" => 'sale@aitech1.ru',
            "SUBJECT" => 'Поступление новых товаров за неделю от ' . date('d.m.Y H:i:s'),
            "BODY_TYPE" => "html",
            "BODY" => $body,
            "DIRECT_SEND" => "Y",
            "CHARSET" => 'utf-8',
            "STATUS" => 'D',
            "RUB_ID" => [3],
        );

        // Полностью схема генерации выпуска из скрипта выглядит так:
        $cPosting = new \CPosting;
        $dbResPosting = $cPosting->GetList();
        $ID = false;
        while ($res = $dbResPosting->Fetch()) {
            if (strpos($res['SUBJECT'], 'Поступление новых товаров') === false) {
            } else {
                $ID = $res['ID'];
                break;
            }
        }
        if ($ID) {
            $cPosting->Update($ID, $arFields);
            $cPosting->ChangeStatus($ID, 'P');
            $cPosting->AutoSend($ID);
        } else {
            $ID = $cPosting->Add($arFields);
            if ($ID) {
                $cPosting->ChangeStatus($ID, 'P');
                $cPosting->AutoSend($ID);
            }
        }
    }

}



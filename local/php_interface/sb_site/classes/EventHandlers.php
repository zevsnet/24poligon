<?php
namespace SB\Site;


use Bitrix\Catalog\Model\Product;
use Bitrix\Main\Loader;
use CCatalogProduct;
use CIBlockElement;
use CSaleOrderProps;
use CSaleOrderPropsValue;
use SB\Site\Bitrix\SBElement;
use SB\Site\Dadata\SuggestClient;
use SB\Tools\Log;


class EventHandlers
{

    static public function addEventHandlers()
    {
        AddEventHandler('sale', 'OnSaleComponentOrderOneStepComplete',
            [__CLASS__, "OnSaleComponentOrderOneStepCompleteHandler"]);
        AddEventHandler('sale', 'OnSaleComponentOrderShowAjaxAnswer',
            [__CLASS__, "OnSaleComponentOrderShowAjaxAnswerHandler"]);
        AddEventHandler("ipol.sdek", 'onCalculate', [__CLASS__, "onCalculateHandler"]);
        AddEventHandler("ipol.sdek", 'onBeforeShipment', [__CLASS__, "onBeforeShipmentHandler"]);
//        \Bitrix\Main\EventManager::getInstance()->addEventHandler('sale', 'onSaleDeliveryServiceCalculate',[__CLASS__, 'onSaleDeliveryServiceCalculateHandler']);

        //Событие создает нагрузку при обмене
        //AddEventHandler("catalog", "OnBeforeProductUpdate", [__CLASS__, 'OnBeforeProductUpdate']);
        //Для товаров
        AddEventHandler("iblock", "OnAfterIBlockElementUpdate", [__CLASS__, 'OnAfterIBlockElementUpdate']);
        AddEventHandler("iblock", "OnBeforeIBlockSectionUpdate", [__CLASS__, 'OnBeforeIBlockSectionUpdate']);
        AddEventHandler("catalog", "OnBeforeCatalogStoreUpdate", [__CLASS__, 'OnBeforeCatalogStoreUpdate']);
        AddEventHandler("main", "OnBeforeEventAdd", [__CLASS__, 'OnBeforeEventAdd']);


        AddEventHandler('main', 'OnEpilog', Array(__CLASS__, "OnEpilog"));
        AddEventHandler('main', 'OnPageStart', Array(__CLASS__, "Redirect301"));

    }

    public static function OnSaleComponentOrderOneStepCompleteHandler($ID, $arFields)
    {
        Loader::includeModule('sale');
        if ($arFields['ID'] > 0 && $arFields['USER_ID'] > 0) {
            $arFIO = array();
            $arADDRESS = array();

            $rsProp = CSaleOrderPropsValue::GetList(array(), array('ORDER_ID' => $arFields['ID']));
            while ($arProp = $rsProp->Fetch()) {


                switch ($arProp['CODE']) {
                    case 'F_NAME':
                    case 'F_LAST_NAME':
                    case 'F_SECOND_NAME':
                        $arFIO[$arProp['CODE']] = $arProp['VALUE'];
                        break;
                    case 'ZIP':
                    case 'CITY':
                    case 'STREET':
                    case 'BUILDING':
                    case 'APARTAMENT':
                        $arADDRESS[$arProp['CODE']] = $arProp['VALUE'];
                        break;
                }
            }
            $FIO = trim($arFIO['F_LAST_NAME'] . ' ' . $arFIO['F_NAME'] . ' ' . $arFIO['F_SECOND_NAME']);
            $ADDRESS = trim($arADDRESS['ZIP'] . ', ' . $arADDRESS['LOCATION'] . ' ' . $arADDRESS['ADDRESS']);
            $BIK = trim($arADDRESS['BIK']);

            if ($FIO != '') {
                if ($arProp = CSaleOrderProps::GetList(array(), array(
                    'CODE' => 'FIO',
                    'PERSON_TYPE_ID' => $arFields['PERSON_TYPE_ID']
                ))->Fetch()) {
                    CSaleOrderPropsValue::Add(array(
                        'ORDER_ID' => $arFields['ID'],
                        'ORDER_PROPS_ID' => $arProp['ID'],
                        'NAME' => $arProp['NAME'],
                        'CODE' => 'FIO',
                        'VALUE' => $FIO
                    ));
                }
            }
            unset($arProp);

            if ($ADDRESS != '') {
                if ($arProp = CSaleOrderProps::GetList(array(), array(
                    'CODE' => 'ADDRESS',
                    'PERSON_TYPE_ID' => $arFields['PERSON_TYPE_ID']
                ))->Fetch()) {
                    CSaleOrderPropsValue::Add(array(
                        'ORDER_ID' => $arFields['ID'],
                        'ORDER_PROPS_ID' => $arProp['ID'],
                        'NAME' => $arProp['NAME'],
                        'CODE' => 'ADDRESS',
                        'VALUE' => $ADDRESS
                    ));
                }
            }
        }

    }

    public static function OnSaleComponentOrderShowAjaxAnswerHandler(&$result)
    {
        $properties = $result['order']['ORDER_PROP']['properties'];
        foreach ($properties as $key => $prop) {
            if ($prop['CODE'] == 'PHONE') {
                $strValue = $result['order']['ORDER_PROP']['properties'][$key]['VALUE'][0];
                $strValueOne = substr($result['order']['ORDER_PROP']['properties'][$key]['VALUE'][0], 0, 1);
                if (strlen($strValue) == 11 && $strValueOne == 7) {
                    $result['order']['ORDER_PROP']['properties'][$key]['VALUE'][0] = substr($strValue, 1);
                }
            }
        }
    }

    public static function onCalculateHandler(&$arReturn, $profile, $arConfig, $arOrder)
    {
        Log::addToLog($_SERVER['DOCUMENT_ROOT'] . "/local/log/" . __FUNCTION__ . '.log', $_REQUEST);
    }

    public static function onBeforeShipmentHandler(&$order, $arItems)
    {
    }

    public static function onSaleDeliveryServiceCalculateHandler(\Bitrix\Main\Event $event)
    {
        /** @var  \Bitrix\Sale\Delivery\CalculationResult $RESULT */
        $RESULT = $event->getParameter('RESULT');
        $SHIPMENT = $event->getParameter('SHIPMENT');
        $DELIVERY_ID = $event->getParameter('DELIVERY_ID');
        //$RESULT->setDeliveryPrice(0);

    }

    public static function OnBeforeProductUpdate($ID, &$arFields)
    {
        \CModule::IncludeModule('iblock');
        \CModule::IncludeModule('catalog');
        $res = \CIblockElement::GetByID($ID);


        if ($el = $res->getNext()) {
            //Простой товар обрабатываем всегда
            $nel = new \CIblockElement();
            if (isset($arFields['QUANTITY'])) {
                if ($arFields['QUANTITY'] == 0 && $el['ACTIVE'] == 'Y') {
                    $nel->Update($ID, array('ACTIVE' => 'N'));
                    Product::update($ID, ['AVAILABLE' => 'N']);
                } elseif ($arFields['QUANTITY'] > 0 && $el['ACTIVE'] == 'N') {
                    $nel->Update($ID, array('ACTIVE' => 'Y'));
//                    Product::update($ID, ['AVAILABLE'=>'Y']);
                }
            }

            //Если есть родитель, то ставим активность в зависимости от наличия доступных детей.
            $IB_TP = Variables::IBLOCK_ID_CATALOG_OFFERS; //Предложения
            $LINK = 'CML2_LINK';

            $link_val = \CIblockElement::GetList(array(), array('ID' => $ID), false, false,array('*', 'PROPERTY_' . $LINK));
            $ar_link = $link_val->GetNext();
            if (is_array($ar_link) && isset($ar_link['PROPERTY_' . $LINK . '_VALUE'])) { //если есть свойство, значит есть парент
                $par_res = CIblockElement::GetList(array(), array('ID' => $ar_link['PROPERTY_' . $LINK . '_VALUE']));
                if ($ar_par = $par_res->getNext()) { //парент получен
                    ////////определяем какая нужна активность
                    $res = CIblockElement::GetList(array(),array('IBLOCK_ID' => $IB_TP, 'PROPERTY_' . $LINK => $ar_link['PROPERTY_' . $LINK . '_VALUE']));
                    $children = array();
                    while ($el = $res->GetNext()) {
                        if ($el['ID'] != $ID) {
                            $children[] = $el['ID'];
                        }
                    }
                    $res = CCatalogProduct::GetList(array(), array('ID' => $children, '>QUANTITY' => 0), false,array('nTopCount' => 10));
                    $count = (count($children) == 0 ? 0 : $res->SelectedRowsCount()) + ($arFields['QUANTITY'] > 0 ? 1 : 0);

                    $status = $count > 0 ? 'Y' : 'N';
                    if ($ar_par['ACTIVE'] !== $status) { //ставим активность мамки, если не совпадает

                        $IB = new CIblockElement();
                        $IB->Update($ar_par['ID'], array('ACTIVE' => $status));
//                        Product::update($ar_par['ID'], ['AVAILABLE'=>$status]);
                    }
                }
            }
        }

        return true;
    }

    public static function OnAfterIBlockElementUpdate(&$arFields){

        //Если есть родитель, то ставим активность в зависимости от наличия доступных детей.
        $IB_TP = Variables::IBLOCK_ID_CATALOG_OFFERS; //Предложения
        $LINK = 'CML2_LINK';
        $children = [];
        ////////определяем какая нужна активность
        if($IB_TP !=$arFields['IBLOCK_ID']){
            $res = CIblockElement::GetList([],['IBLOCK_ID' => $IB_TP, 'PROPERTY_' . $LINK => $arFields['ID']],false,false,['ID']);
            while ($el = $res->GetNext()) {
                if ($el['ID'] != $arFields['ID']) {
                    $children[] = $el['ID'];
                }
            }
        }

        if ($children) {
            $res_avalible = CCatalogProduct::GetList(array(), array('ID' => $children, '>QUANTITY' => 0), false, array('nTopCount' => 10));
            $count = (count($children) == 0 ? 0 : $res_avalible->SelectedRowsCount()) + ($arFields['QUANTITY'] > 0 ? 1 : 0);
            $status = $count > 0 ? 'Y' : 'N';
            if ($arFields['ACTIVE'] != $status) { //ставим активность мамки, если не совпадает
                SBElement::activeElement(Variables::IBLOCK_ID_CATALOG_OFFERS, $arFields['ID'],$status);
                Product::update($arFields['ID'], ['AVAILABLE' => $status]);
            }

            $arOffers_Avalible = [];
            while ($el_avalible = $res_avalible->GetNext()) {
                $arOffers_Avalible[$el_avalible['ID']] = $el_avalible;
            }
            $elOffers = new CIBlockElement();
            foreach ($children as $childID) {
                $elOffers->Update($childID, ['ACTIVE' => $arOffers_Avalible[$childID] ? 'Y' : 'N']);
            }
            unset($elOffers);
        }else{
            $res_avalible = CCatalogProduct::GetList(array(), array('ID' => $arFields['ID'], '>QUANTITY' => 0), false, array('nTopCount' => 10));
            $count = $res_avalible->SelectedRowsCount() + ($arFields['QUANTITY'] > 0 ? 1 : 0);
            $status = $count > 0 ? 'Y' : 'N';

            if ($arFields['ACTIVE'] != $status) { //ставим активность мамки, если не совпадает
                SBElement::activeElement($arFields['IBLOCK_ID'], $arFields['ID'],'Y');
                Product::update($arFields['ID'], ['AVAILABLE' => $status]);
            }
        }



    }

    public static function OnBeforeIBlockSectionUpdate(&$arFields){

        switch ($arFields['CODE']){

            case 'hit_product':
            case 'news_product':
            case 'rosgvardiya':
                    $arFields['ACTIVE'] = 'Y';
              //  return false;
                break;
        }
    }

    static public function OnBeforeEventAdd(&$event, &$lid, &$arFields, &$message_id, &$files)
    {
        switch ($event) {
            case 'SALE_NEW_ORDER':
            case 'NEW_ONE_CLICK_BUY':
                if (!Loader::includeModule('sale')) {
                    return true;
                }
                $ORDER_ID = $arFields['ORDER_REAL_ID'];
                $arFields['SB_ORDER_PICKUP'] = \SB\Site\General::getPickup($ORDER_ID);
               // $arFields['SB_ORDER_LIST'] = \SB\Site\General::getOrderListTable($ORDER_ID);
                break;
        }
    }

    public function OnEpilog()
    {
        global $APPLICATION, $arEditMeta;
        if (!empty($arEditMeta)) {
            foreach ($arEditMeta as $keyMeta => $arMeta) {
                if (!empty($arMeta)) {
                    foreach ($arMeta as $key => $Item) {
                        $tmpStrMeta = $APPLICATION->GetProperty($keyMeta);
                        $tmpNewStrMeta = str_replace($key, $Item, $tmpStrMeta);
                        $APPLICATION->SetPageProperty($keyMeta, $tmpNewStrMeta);
                        switch ($keyMeta){
                            case 'title':
                            case 'description':
                                $APPLICATION->SetPageProperty('og:' . $keyMeta, $tmpNewStrMeta);
                                break;
                        }
                    }
                }
            }
        }

        if (!defined('ERROR_404') && intval($_GET["PAGEN_1"]) > 0) {
            $APPLICATION->SetPageProperty("title",$APPLICATION->GetPageProperty("title") . "– " . intval($_GET["PAGEN_1"]) . " страница");
        }
    }

    public static function Redirect301()
    {
        // $ip = \Bitrix\Main\Service\GeoIp\Manager::getRealIp();
        // if (\Bitrix\Main\Loader::includeModule('rover.geoip')) {
            // $arInfo = \Rover\GeoIp\Location::getInstance($ip);
            // if($arInfo->getLanguage() != 'ru'){
                // die();
            // }
        // }

        $redirectsDetector = RedirectsDetector::getInstance();
        $redirectsDetector->detectRedirects();
    }

    /**
     * Блокировка изменения названий складов
     * @param $id
     * @param $arFields
     */
    static public function OnBeforeCatalogStoreUpdate($id, &$arFields)
    {
        if ($_GET['mode'] == 'import'){
            unset($arFields['TITLE']);
            unset($arFields['ADDRESS']);
        }
    }
}
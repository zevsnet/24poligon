<?php

namespace Poligon\Core\Sale;

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Sale\Location\LocationTable;
use Bitrix\Sale\Order;
use CCatalogStore;
use CSaleUser;
use Poligon\Core\Controller\Sale;
use Poligon\Core\Iblock\Helper;
use Poligon\Core\Pickup\Sdek;

Loader::includeModule('sale');

class Events
{
    const INNER_PAY_SYSTEM_ID = 1;

    /**
     * Подлючаемся к событиям Инфоблока
     */
    static public function addEventHandlers()
    {

        \Bitrix\Main\EventManager::getInstance()->addEventHandler('sale', 'OnSaleComponentOrderShowAjaxAnswer', [__CLASS__, 'OnSaleComponentOrderShowAjaxAnswer']);
    }
    private static function getCityValue($order)
    {
        foreach ($order->getPropertyCollection() as $propertyValue) {
            if ($propertyValue->getPropertyObject()->getField('CODE') === 'LOCATION') {
                $codeLocation = $propertyValue->getField('VALUE');
                $arCity = LocationTable::getRow([
                    'filter' => [
                        '=CODE' => $codeLocation,
                        '=NAME.LANGUAGE_ID' => 'ru',
                    ],
                    'select' => [
                        'LOCATION_NAME' => 'NAME.NAME',
                        'ID',
                        'PARENT_ID'
                    ]
                ]);

                if (!empty($arCity['LOCATION_NAME'])) {
                    return $arCity['LOCATION_NAME'];
                }
            }
        }
        return null;
    }
    public static function OnSaleComponentOrderShowAjaxAnswer(&$result)
    {
        global $USER;
        $userId = null;
        $siteId = Application::getInstance()->getContext()->getSite();
        if ($USER->IsAuthorized()) {
            $userId = $USER->GetID();
        } else {
            $userId = CSaleUser::GetAnonymousUserID();
        }
        $order = Order::create($siteId, $userId);

        $context = \Bitrix\Main\Application::getInstance()->getContext();
        $siteId = $context->getSite();
        if (is_array($result)) {
            //\_::dd($_REQUEST['order']['locations']);

//            foreach ($_REQUEST['order']['ORDER_PROP']['USER_PROPS_Y'] as $itemProp) {
//                if($itemProp['CODE'] =='LOCATION'){
//                    $lastValue = $_REQUEST['order']['locations'][$itemProp['ID']]['lastValue'];
//
//
//                    unset($itemProp['~VARIANTS']);
//                    foreach ($itemProp['VARIANTS'] as $VARIANT) {
//                        if($VARIANT['ID']==$itemProp['VALUE']){
//                            $CITY = $VARIANT['CITY_NAME'];
//                        }
//                    }
//                }
//                if($itemProp['CODE'] =='CITY'){
//                    if($itemProp['VALUE'] == ''){
//                        $itemProp['VALUE'] = $CITY;
//                    }
//                }
//            }


            switch ($_REQUEST['order']['DELIVERY_ID']) {
                case 40://Магазин
                  //Получаем все склады
                        $arStore = self::getStore();
                    $nameCityLocation = ($_REQUEST['order']['ORDER_PROP_60'])?:self::getCityValue($order);
                    $arPoint = [];
                    $arBuyerSelect = $_REQUEST['order']['BUYER_STORE'] ?: 0;
                    $i = $arBuyerSelect ?: 0;
                    foreach ($result['order']['STORE_LIST'] as $item) {
                        $arTmp = [
                            'id' => $item['ID'],
                            'title' => $item['TITLE'],
                            'description' => $item['ADDRESS'],
                            'lat' => (float)$item['GPS_N'],
                            'lon' => (float)$item['GPS_S'],
                            'selected' => $i === 0
                        ];
//$arStore[$item['ID']]
                        if(strtolower($arStore[$item['ID']]['REGION_NAME']) == strtolower($nameCityLocation)){
                            if ($arBuyerSelect == $item['ID']) {
                                $arTmp['selected'] = true;
                            }
                            $arPoint[] = $arTmp;
                            $i++;
                        }

                    }
                    $result['delivery']['pickup'] = $arPoint;
                    $result['delivery']['serviceFields'] = [];
                    break;
                case 73://СДЭК Самовывоз
                    $pickupList = (new Sdek($order))->getPickupList();
                    $result['delivery']['pickup'] = $pickupList;
                    $result['delivery']['serviceFields'] = [];
                    break;
                case 81://Постамат СДЭК
                    break;
                case 85://Почта России

                    break;
                case 83://Dostavista
                    break;
            }

            foreach ($result['order']['DELIVERY'] as $itemDelivery) {
                $itemDelivery['selected'] = $itemDelivery['CHECKED'] == 'Y' ? true : false;
                $result['delivery']['items'][] = [
                    'id' => $itemDelivery['ID'],
                    'description' => $itemDelivery['DESCRIPTION'],
                    'price' => $itemDelivery['PRICE'],
                    'price_format' => $itemDelivery['PRICE_FORMAT'],
                    'name' => $itemDelivery['NAME'],
                    'title' => $itemDelivery['NAME'],
                    'selected' => $itemDelivery['CHECKED'] == 'Y' ? true : false

                ];
            }

            if($_REQUEST['order']['ORDER_PROP_60']){
                $arTmpCityInfo = Sale::dadatacityAction($_REQUEST['order']['ORDER_PROP_60']);
                $result['locations']['44']['lastValue'] = $arTmpCityInfo[0]['CODE'];
                foreach ($result['order']['ORDER_PROP']['properties'] as $key => $property) {
                    if( $property['CODE'] == 'LOCATION'){
                        $result['order']['ORDER_PROP']['properties'][$key]['VALUE'][0] = $arTmpCityInfo[0]['CODE'];
                    }
                }
            }

        }
    }

    public static function getStore()
    {
        $dbResult = CCatalogStore::GetList(
            array('PRODUCT_ID' => 'ASC', 'ID' => 'ASC'),
            array('ACTIVE' => 'Y'),
            false, false,
            [
                'ID',
                'TITLE',
                'UF_REGION',
            ]
        );
        $arStore = [];
        while ($arTmp = $dbResult->Fetch()) {
            $arTmp['UF_REGION'] = unserialize($arTmp['UF_REGION']);
            if ($arTmp['UF_REGION']) {
                $arTmp['REGION_NAME'] = self::getRegion($arTmp['UF_REGION']);

            }
            $arStore[$arTmp['ID']] = $arTmp;
        }

        return $arStore;
    }

    public static function getRegion($id)
    {
            $obElements = \CIBlockElement::GetList([],['IBLOCK_ID'=>Helper::getIdByCode('aspro_max_regions'),'ID'=>$id]);
            while ($obElement = $obElements->GetNextElement()){
                $arFields = $obElement->GetFields();
                $arFields['PROP'] = $obElement->GetProperties();
               return $arFields['NAME'];
            }
    }
}
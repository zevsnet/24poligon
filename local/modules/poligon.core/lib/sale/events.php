<?php

namespace Poligon\Core\Sale;

use Bitrix\Catalog\v2\Property\PropertyCollection;
use Bitrix\Sale\Order;
use SB\Site\Dadata\SuggestClient;
use SB\Site\Variables;

class Events
{
    /**
     * Подлючаемся к событиям Магазина
     */
    static public function addEventHandlers()
    {
//
        \Bitrix\Main\EventManager::getInstance()->addEventHandler('sale', 'OnSaleOrderSaved',
            [__CLASS__, 'OnSaleOrderSaved']);
    }

    function OnSaleOrderSaved(\Bitrix\Main\Event $event)
    {

        $isNew = $event->getParameter("IS_NEW");

        if ($isNew) {
            /** @var Order $order */
            $order = $event->getParameter("ENTITY");

            /** @var PropertyCollection $propertyCollection */
            $propertyCollection = $order->getPropertyCollection();
            $arPropTmp = $propertyCollection->getArray();
            $arPropOrder = [];
            foreach ($arPropTmp['properties'] as $item) {
                $arPropOrder[$item['CODE']] = $item['VALUE'][0];
            }
            $obDaData = new SuggestClient(Variables::DADATA_TOKEN, Variables::DADATA_SECTER_KEY);
            $arRes = $obDaData->getBank($arPropOrder['BIK']);
            $suggestions = $arRes['suggestions'][0];
            if ($suggestions) {

                $BANK = $suggestions['value'];//BANK//Банк получателя
                $NUM_COR = $suggestions['data']['correspondent_account'];//NUM_COR//Номер кор.счета

                $arResInfo = $obDaData->suggest('party',['query'=>$arPropOrder['INN'],'count' => 1, 'status' => ['ACTIVE']]);
                $suggestionsInfo = $arResInfo['suggestions'][0];
                $UR_ADDRESS = $suggestionsInfo['data']['address']['unrestricted_value'];
                foreach ($propertyCollection as $property) {
                    switch ($property->getField('CODE')) {
                        case 'CONTACT':
                            $property->setValue($arPropOrder['F_LAST_NAME'] . ' ' . $arPropOrder['F_NAME'] . ' ' . $arPropOrder['F_SECOND_NAME']  );
                            break;
                        case 'UR_ADDRESS':
                            $property->setValue($UR_ADDRESS);
                            break;
                        case 'BANK':
                            $property->setValue($BANK);
                            break;
                        case 'NUM_COR':
                            $property->setValue($NUM_COR);
                            break;
                    }

                }
            }

//            foreach ($propertyCollection as $property) {
//                switch ($property->getField('CODE')) {
//                    case 'CONTACT':
//                        $property->setValue($arPropOrder['F_LAST_NAME'] . ' ' . $arPropOrder['F_NAME'] . ' ' . $arPropOrder['F_SECOND_NAME']  );
//                        break;
//
//                }
//
//            }
            $order->save();
//            \_::dd();
        }
    }
}

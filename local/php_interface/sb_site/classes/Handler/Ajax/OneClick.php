<?php

namespace SB\Site\Handler\Ajax;

use Bitrix\Main\HttpRequest;

use CFormCrm;
use CFormResult;
use CSaleUser;
use SB\Handler\Ajax;

use Bitrix\Main,
    Bitrix\Main\Localization\Loc as Loc,
    Bitrix\Main\Loader,
    Bitrix\Main\Config\Option,
    Bitrix\Sale\Delivery,
    Bitrix\Sale\PaySystem,
    Bitrix\Sale,
    Bitrix\Sale\Order,
    Bitrix\Sale\DiscountCouponsManager,
    Bitrix\Main\Context;


class OneClick extends Ajax
{
    public function add()
    {
        if (\CModule::IncludeModule("sale")) {

            global $USER;
            $request = $_REQUEST;
            \Bitrix\Main\Loader::includeModule("sale");
            \Bitrix\Main\Loader::includeModule("catalog");
            if (self::correctValue($request) !== false) {


                // Допустим некоторые поля приходит в запросе
                $request = Context::getCurrent()->getRequest();
                $PRODUCT_ID = $request["PRODUCT_ID"];
                $PARENT_ID = $request["PARENT_ID"];
                $PHONE = $request["PHONE"];
                $NAME = $request["FIO"];

// Массив товаров

                $arItems =
                    array(
                        'PRODUCT_ID' => $PRODUCT_ID,
                        'CURRENCY' => \Bitrix\Currency\CurrencyManager::getBaseCurrency(),
                        'LID' => \Bitrix\Main\Context::getCurrent()->getSite(),
                        'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
                        'QUANTITY' => 1
                    );

// Создаем и наполняем корзину
                $basket = \Bitrix\Sale\Basket::create(SITE_ID);

                $basketItem = $basket->createItem("catalog", $PRODUCT_ID);
                $basketItem->setFields($arItems);


// Создаем заказ и привязываем корзину, перерасчет происходит автоматически
                $order = \Bitrix\Sale\Order::create(SITE_ID,
                    $USER->GetID() ? $USER->GetID() : CSaleUser::GetAnonymousUserID());
                $order->setPersonTypeId(1);
                $order->setBasket($basket);

// Создание отгрузки

                $shipmentCollection = $order->getShipmentCollection();
                $shipment = $shipmentCollection->createItem(
                    \Bitrix\Sale\Delivery\Services\Manager::getObjectById(1)
                );
                $shipmentItemCollection = $shipment->getShipmentItemCollection();
                /** @var \Bitrix\Sale\BasketItem $basketItem */
                foreach ($basket as $basketItem) {
                    $item = $shipmentItemCollection->createItem($basketItem);
                    $item->setQuantity($basketItem->getQuantity());
                }
// Создание оплаты
                $paymentCollection = $order->getPaymentCollection();
                $payment = $paymentCollection->createItem(
                    \Bitrix\Sale\PaySystem\Manager::getObjectById(1)
                );
                $payment->setField("SUM", $order->getPrice());
                $payment->setField("CURRENCY", $order->getCurrency());
// Coхраняем заказ
                // Устанавливаем свойства
                $propertyCollection = $order->getPropertyCollection();
                $phoneProp = $propertyCollection->getPhone();
                $phoneProp->setValue($PHONE);
                $nameProp = $propertyCollection->getPayerName();
                $nameProp->setValue($NAME);

                $result = $order->save();

                if (!$result->isSuccess()) {
                    //$result->getError();

                } else {
                    $orderId = $result->GetId();
                }

                $this->getResult()->addData('orderId', $orderId);
                $this->getResult()->addData('status', true);
            } else {
                $this->getResult()->addData('status', false);
            }
        }
    }

    public function correctValue($arData)
    {
        $arError = [];
        $isErrorStop = false;

        foreach ($arData as $key => $value) {

            if ($value == '') {
                $arError[$key] = true;
                $isErrorStop = true;
            } else {
                $arError[$key] = false;
            }
        }


        $this->getResult()->addData('errors', $arError);
        $this->getResult()->addData('isErrorStop', $isErrorStop);

        if ($isErrorStop) {
            return false;
            $this->getResult()->addData('status', false);
        } else {
            $this->getResult()->addData('status', true);
            return true;
        }
    }

    public function myFunction()
    {
        /** добавление ошибки */
        $this->getResult()->addError('test', 1);
        /** установка статуса ответа */
        $this->getResult()->setStatus(true);
        /** добавление результата */
        $this->getResult()->addData('dataKey', 'dataValue');

        /** @var HttpRequest $request получение запроса */
        $request = $this->getRequest();

        /** @var array $params получение (get/post/cookie) */
        $params = $this->getParams();
    }
}
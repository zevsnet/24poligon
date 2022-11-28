<?php

namespace SB\Site\Handler\Ajax;

use Bitrix\Main\HttpRequest;

use SB\Handler\Ajax;
use Bitrix\Sale,
    Bitrix\Main\Context;


class Basket extends Ajax
{
    public function update()
    {
        if (\CModule::IncludeModule("sale")) {

            global $USER;

            \Bitrix\Main\Loader::includeModule("sale");
            \Bitrix\Main\Loader::includeModule("catalog");

            // �������� ��������� ���� �������� � �������
            $request = Context::getCurrent()->getRequest();
            $quantity = $request['QUANTITY'];
            $productId = $request['PRODUCT_ID'];

            // ��������� ������� ��� �������� ������������
            $basket = \Bitrix\Sale\Basket::loadItemsForFUser(
                \Bitrix\Sale\Fuser::getId(),
                \Bitrix\Main\Context::getCurrent()->getSite()
            );

            //������� ������ �������
            $basketItems = $basket->getBasketItems();
            /** @var Sale\BasketItem $item */
            foreach ($basketItems as $item) {
                if ($item->getProductId() == $productId)
                    $item->setField('QUANTITY', $quantity);
            }
            $basket->save();

            $this->getResult()->addData(false, 'save');
        }
    }

    public function del()
    {
        if (\CModule::IncludeModule("sale")) {
            $request = Context::getCurrent()->getRequest();
            $productId = $request['PRODUCT_ID'];
            /** Sale\Basket $order ������ ������ */
            $basket = \Bitrix\Sale\Basket::loadItemsForFUser(
                \Bitrix\Sale\Fuser::getId(),
                \Bitrix\Main\Context::getCurrent()->getSite()
            );
            /** int $id ID ������ */
            $test = $basket->getItemById($productId);
            $basket->getItemById($productId)->delete();
            $basket->save();

            $fullPrice = $basket->getBasePrice();
            $fullPrice = \CCurrencyLang::CurrencyFormat($fullPrice, 'RUB');

            $this->getResult()->addData('check', 'save');
            $this->getResult()->addData('fullPrice', $fullPrice);
        }
    }

    public function delAll()
    {
        if (\CModule::IncludeModule("sale")) {
            $request = Context::getCurrent()->getRequest();
            $productId = $request['PRODUCT_ID'];
            /** Sale\Basket $order ������ ������ */
            $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(),
                \Bitrix\Main\Context::getCurrent()->getSite());
            /** int $id ID ������ */

            $collectionBasket = $basket->getBasketItems();
            /** @var Sale\BasketItem $item */
            foreach ($collectionBasket as $item) {
                $item->delete();
            }
            $basket->save();
            $this->getResult()->addData(false, 'save');
        }
    }

    //���� �������
    public function updateSmallBasket()
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();
        ob_start();
        $APPLICATION->IncludeComponent('bitrix:sale.basket.basket.line', 'main', $_POST['arParams']);

        $html = ob_get_clean();
        $this->getResult()->addData('html', $html);
    }

    public function updateItem()
    {
        if (\CModule::IncludeModule("sale")) {

            global $USER;

            \Bitrix\Main\Loader::includeModule("sale");
            \Bitrix\Main\Loader::includeModule("catalog");

            // �������� ��������� ���� �������� � �������
            $request = Context::getCurrent()->getRequest();
            $quantity = $request['QUANTITY'];
            $productId = $request['PRODUCT_ID'];

            // ��������� ������� ��� �������� ������������
            $basket = \Bitrix\Sale\Basket::loadItemsForFUser(
                \Bitrix\Sale\Fuser::getId(),
                \Bitrix\Main\Context::getCurrent()->getSite()
            );

            //������� ������ �������
            $basketItems = $basket->getBasketItems();
            /** @var Sale\BasketItem $item */


            $itemIndex = -1;

            foreach ($basketItems as $index => $item) {
                if ($item->getProductId() == $productId) {
                    $item->setField('QUANTITY', $quantity);
                    $itemIndex = $index;
                }
            }

            $basket->save();

            $fullPrice = $basket->getBasePrice();

            if ($itemIndex >= 0) {
                $price = \CCurrencyLang::CurrencyFormat($basketItems[$itemIndex]->getFinalPrice(), 'RUB');
            }

            $fullPrice = \CCurrencyLang::CurrencyFormat($fullPrice, 'RUB');

            $this->getResult()->addData('price', $price);
            $this->getResult()->addData('fullPrice', $fullPrice);
        }
    }
}
<?php

namespace SB\Site;

use Bitrix\Catalog\Model\Product;
use Bitrix\Catalog\StoreProductTable;
use Bitrix\Catalog\StoreTable;
use Bitrix\Main\Entity\ExpressionField;
use Bitrix\Main\Loader;
use Bitrix\Sale;
use Bitrix\Sale\Fuser;
use CIBlockElement;
use SB\Log;
use SB\Site\Bitrix\SBElement;


class Store
{
    /*
     * Проверка товаров на складе
     */
    public static function getElementToStore($STORE_ID)
    {
        //Пометка что заказ доступен полностью
        // 0- доступен
        // 1- частично
        // 2- недоступен
        $isStore = 0;
        $isStoreChast = false;
        $isStoreNotAvalible = false;

        $basketStorage = Sale\Basket\Storage::getInstance(Fuser::getId(), Variables::SITE_ID_PROECT);
        $basket = $basketStorage->getBasket();

        /** @var Sale\BasketItem $item */
        foreach ($basket as $key => $item) {
            $arStoreInfo = Element::getStoreID($item->getProductId(), $STORE_ID);

            if ($arStoreInfo['REAL_AMOUNT'] <= 0) {
                $isStoreNotAvalible = true;
            } else {


                if ($item->getQuantity() > +$arStoreInfo['REAL_AMOUNT']) {
                    $isStoreNotAvalible = true;
                } else {
                    $isStoreChast = true;
                }
            }
        }
        if ($isStoreChast == true && $isStoreNotAvalible == true) {
            $isStore = 1;
        } elseif ($isStoreChast == false && $isStoreNotAvalible == true) {
            $isStore = 2;
        } elseif ($isStoreChast == true && $isStoreNotAvalible == false) {
            $isStore = 0;
        }
        return $isStore;
    }

    /**/
    public static function OnStoreProductUpdateHandler($ID, $arFields)
    {
        $ELEMENT_ID = $ID;


        Loader::includeModule('catalog');
        $obElement2Stores = StoreProductTable::getList([
            'filter' => ['PRODUCT_ID' => $ELEMENT_ID],
            'select' => ['SUM'],
            'runtime' => [
                new ExpressionField('SUM', 'SUM(AMOUNT)')
            ]
        ]);
        $sumQuantity = $obElement2Stores->fetch()['SUM'];
        if(empty($sumQuantity)){
            $sumQuantity = 0;
        }

        if($sumQuantity = 0){
            SBElement::activeElement($arFields['IBLOCK_ID'],$ELEMENT_ID,'N');
        }
        Product::update($ELEMENT_ID, [
            'QUANTITY' => $sumQuantity,
            'VAT_INCLUDED' => 'Y',
            'VAT_ID' => 1,

        ]);
    }

    public static function getAllElementUpdateStores($arFilter, $arSelect = ['ID'])
    {
        \Bitrix\Main\Loader::includeModule('iblock');

        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
        while ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
            self::OnStoreProductUpdateHandler($arFields['ID'],$arFields);
        }
    }

    public static function OnSuccessCatalogImport1CHandler($arParams, $ABS_FILE_NAME)
    {
        //General::addLink2saleSection();
        //\SB\Site\General::addLink2saleSection(['NAME_SECTION_RU' => 'Универсиада', 'CODE_SECTION' => 'universiada', 'CODE_PROP' => 'UNIVERSIADA','CODE_FIND_PROP'=>'PROPERTY_UNIVERSIADA']);
        //\SB\Site\General::addLink2saleSection(['NAME_SECTION_RU' => '9мая', 'CODE_SECTION' => '9mai', 'CODE_PROP' => '_23_FEVRALYA','CODE_FIND_PROP'=>'PROPERTY__23_FEVRALYA']);
        //\SB\Site\General::addLink2saleSection(['NAME_SECTION_RU' => 'Юнармия', 'CODE_SECTION' => 'unarmia', 'CODE_PROP' => '_SFERA_DEYATELNOSTI','CODE_FIND_PROP'=>'PROPERTY_SFERA_DEYATELNOSTI'],'Юнармия');


    }

    /**
     * вернет все склады Активные
     */
    public static function getStoresAllID()
    {
        $arStore = StoreTable::getList(['filter' => [
            'ACTIVE' => 'Y',
            '!ID'=>[50,51,52]
        ]])->fetchAll();
        
        return array_column($arStore, 'ID');
    }

    /**
     * Присвоить Телефон складу
     */
    public static function setPhoneStore($store_id,$phone)
    {

        \CCatalogStore::Update($store_id,['PHONE'=>$phone]);
    }

    public static function getBasket2OneClick(){
        $basketStorage = Sale\Basket\Storage::getInstance(Fuser::getId(),SITE_ID);
        $basket = $basketStorage->getBasket();
        $strBasket = '';
        /** @var Sale\BasketItem $item */
        foreach ($basket as $key => $item) {
//            $strBasket  .= '<a href="https://24poligon.ru'.$item->getField('DETAIL_PAGE_URL').'">'.$item->getField('NAME').'</a></br>';
            $strBasket  .= $item->getField('NAME') . ' (' . $item->getFinalPrice(). ' руб.),';
        }
        return $strBasket;
    }
}

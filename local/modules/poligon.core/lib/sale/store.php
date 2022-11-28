<?php

namespace Poligon\Core\Sale;

use Bitrix\Catalog\StoreTable;
use Bitrix\Main\Loader;


Loader::includeModule('sale');

class Store
{
    /**
     * вернет все склады Активные
     */
    public static function getStoresAllID()
    {
        $arStore = StoreTable::getList(['filter' => ['ACTIVE' => 'Y']])->fetchAll();
        return array_column($arStore, 'ID');
    }
}
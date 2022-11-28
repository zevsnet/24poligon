<?php
namespace Poligon\Core\Sale;

use Bitrix\Main\Loader;
use Bitrix\Sale\Internals\OrderPropsTable;


Loader::includeModule('sale');

class Helper
{
    /**
     * Возращет Ид свойст
     * @param $CODE
     * @param $PERSON_TYPE_ID
     *
     * @return bool
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function getIdPropOrder($CODE,$PERSON_TYPE_ID)
    {
        $arRes = OrderPropsTable::getList(['filter' => ['CODE' => $CODE,'PERSON_TYPE_ID'=>$PERSON_TYPE_ID]])->fetchAll();
        return $arRes[0]['ID'] ?: false;
    }
}

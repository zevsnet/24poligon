<?php
namespace Poligon\Core\Aspro;

use Bitrix\Iblock\ElementTable;
use Poligon\Core\Variables;

class Events
{
    const MODUL_NAME = 'aspro.max';

    /**
     * Подлючаемся к событиям Инфоблока
     */
    static public function addEventHandlers()
    {

        //AddEventHandler(self::MODUL_NAME, "OnAsproRegionalityAddSelectFieldsAndProps", [__CLASS__, 'OnAsproRegionalityAddSelectFieldsAndProps']);
    }

}
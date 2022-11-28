<?php

namespace SB\Site\Bitrix;

use Bitrix\Main\Loader;
use CIBlock;
use CIBlockSection;

/**
 * Created by PhpStorm.
 * User: nfzakirov
 * Date: 14.08.2018
 * Time: 17:03
 */
class SBIblock
{
    public static function getIblockId($code)
    {
        Loader::includeModule('iblock');
        $res = CIBlock::GetList([], ["=CODE" => $code], true);
        if ($ar_res = $res->Fetch()) {
            return $ar_res['ID'];
        }
        return false;
    }

    public static function getIdSection($arFilter)
    {
        Loader::includeModule('include');
        $db_list = CIBlockSection::GetList([], $arFilter, true);
        if ($arElement = $db_list->Fetch()) {
            return $arElement['ID'];
        }
        return false;
    }
}
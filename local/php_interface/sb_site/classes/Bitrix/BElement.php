<?php

namespace SB\Site\Bitrix;

use Bitrix\Main\Loader;
use CIBlock;
use CIBlockElement;
use CIBlockSection;


class BElement
{

    public static function getElement($arFilter, $CODE_PROP_IMG = false, $arSelect = ['*'], $arNavStartParam = false)
    {
        Loader::includeModule('iblock');
        Loader::includeModule('file');

        $res = CIBlockElement::GetList([], $arFilter, false, $arNavStartParam, $arSelect);
        $arResult = false;
        while ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();


            $arFields['PROP'] = $ob->GetProperties();
            if ($CODE_PROP_IMG) {
                $arFields['IMG'] = false;
                if ($arFields['PROP'][$CODE_PROP_IMG]['VALUE'][0])
                    $arFields['IMG'] = \CFile::GetPath($arFields['PROP'][$CODE_PROP_IMG]['VALUE'][0]);
            }
            $arResult[] = $arFields;
        }
        return $arResult;
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
    public static function getIblockId($code)
    {
        Loader::includeModule('iblock');
        $res = CIBlock::GetList([], ["=CODE" => $code], true);
        if ($ar_res = $res->Fetch()) {
            return $ar_res['ID'];
        }
        return false;
    }

}
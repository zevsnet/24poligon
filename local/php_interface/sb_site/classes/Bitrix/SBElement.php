<?php

namespace SB\Site\Bitrix;

use Bitrix\Iblock\PropertyIndex\Manager;
use Bitrix\Iblock\SectionElementTable;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use CIBlockElement;
use SB\Site\General;

Loader::includeModule('iblock');

class SBElement
{

    public static function getElement($arFilter, $arSelect = ['*'], $arNavStartParam = false, $arOrder = [])
    {
        Loader::includeModule('iblock');
        if ($arNavStartParam) {
            $arNavStartParam = ["nPageSize" => $arNavStartParam];
        }
        $res = CIBlockElement::GetList($arOrder, $arFilter, false, $arNavStartParam, $arSelect);
        $arResult = false;
        $isPropSelect = self::isPropSelect($arSelect);

        while ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();

            $prop = $ob->GetProperties();

            if ($prop) {
                $arFields['PROP'] = $prop;

            } else {
                $keyProp = array_keys($arFields);
                foreach ($arSelect as $item) {
                    $keyFields = self::getKeyProp($keyProp, $item);
                    if (self::isPropSelect([$keyFields])) {
                        $newKey = str_replace(['PROPERTY_', '_VALUE'], '', $keyFields);
                        $arFields['PROP'][$newKey]['ID'] = $arFields[$keyFields . '_ID'];
                        $arFields['PROP'][$newKey]['CODE'] = $newKey;
                        $arFields['PROP'][$newKey]['~CODE'] = strtolower($newKey);
                        $arFields['PROP'][$newKey]['VALUE'] = $arFields[$keyFields];

                        if ($arFields['PROPERTY_CML2_TRAITS_DESCRIPTION']) {
                            $arFields['PROP'][$newKey]['DESCRIPTION'] = $arFields['PROPERTY_CML2_TRAITS_DESCRIPTION'];
                        }
                        unset($arFields[$keyFields . '_ID']);
                        unset($arFields[$keyFields]);
                        unset($arFields['~' . $keyFields . '_ID']);
                        unset($arFields['~' . $keyFields]);
                    }
                }
            }
            if ($arNavStartParam['nPageSize'] == 1) {
                return $arFields;
            } else {
                $arResult[] = $arFields;
            }
        }
        return $arResult;
    }

    //вернуть секции(разделы)
    public static function getSections($arFilter, $arSelect = ['*'], $arNavStartParam = false)
    {
        Loader::includeModule('iblock');
        $res = \CIBlockSection::GetList(['SORT' => 'ASC'], $arFilter, false, $arSelect);

        $arResult = false;
        while ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
            $arResult[$arFields['ID']] = $arFields;

        }
        return $arResult;
    }

    private static function isPropSelect(array $arSelect)
    {
        foreach ($arSelect as $item) {
            if (strpos($item, 'PROPERTY') !== false) {
                return true;
            }
        }
        return false;
    }

    private static function getKeyProp($keyProp, $keyFind)
    {
        foreach ($keyProp as $item) {
            if (strpos($item, strtoupper($keyFind)) !== false) {
                return $item;
            }
        }
        return false;
    }

    //------------------------------ Обращения к базе не через BitrixAPI
    //Дактивация элемента с обходом всех событей
    public static function activeElement($IBLOCK_ID, $ID, $ACTIVE = 'N')
    {
        global $DB;
        if (!$IBLOCK_ID) {
            return 'Не введен ИД инфоблока';
        }
        if (!$ID) {
            return 'Не введен ИД товара';
        }
        $connection = Application::getConnection();
        $sql = "UPDATE b_iblock_element  SET ACTIVE='" . $ACTIVE . "' WHERE ACTIVE='Y' AND IBLOCK_ID='" . $IBLOCK_ID . "' AND ID='" . $ID . "'";

        $connection->query($sql);
    }

    public static function activeSection($IBLOCK_ID, $ID, $ACTIVE = 'N')
    {
        global $DB;
        if (!$IBLOCK_ID) {
            return 'Не введен ИД инфоблока';
        }
        if (!$ID) {
            return 'Не введен ИД товара';
        }
        $connection = Application::getConnection();
        $sql = "UPDATE b_iblock_section   SET ACTIVE='" . $ACTIVE . "' WHERE ACTIVE='Y' AND IBLOCK_ID='" . $IBLOCK_ID . "' AND ID='" . $ID . "'";

        $connection->query($sql);
    }

    public static function setSection2Params($arParams)
    {
        global $DB;
        $res = $DB->Query("SELECT IBLOCK_ELEMENT_ID FROM b_iblock_element_property WHERE `IBLOCK_PROPERTY_ID` = '" . $arParams['IBLOCK_PROPERTY_ID'] . "' AND `VALUE`='" . $arParams['ENUM_VALUE_NAME'] . "'");
        //self::clearSection2Params($arParams);
        $arIdElement = [];
        while ($arr = $res->Fetch()) {
            $arIdElement[]=$arr['IBLOCK_ELEMENT_ID'];
//            $ar_new_groups = General::getGroupElement($arr['IBLOCK_ELEMENT_ID'], $arParams['SECTION_ID']);
//            CIBlockElement::SetElementSection($arr['IBLOCK_ELEMENT_ID'], $ar_new_groups, true, 0, $arParams['SECTION_ID']);
////            CIBlockElement::SetElementSection($arr['IBLOCK_ELEMENT_ID'], $ar_new_groups);
//
//            Manager::updateElementIndex(180, $arr['IBLOCK_ELEMENT_ID']);
        }
        foreach ($arIdElement as $itemId) {
            SectionElementTable::add([
                'IBLOCK_SECTION_ID'=> $arParams['SECTION_ID'],
                'IBLOCK_ELEMENT_ID'=> $itemId,
            ]);
       }


        //активируем раздел
        self::activeSection($arParams['IBLOCK_ID'], $arParams['SECTION_ID'], 'Y');
    }

    public static function clearSection2Params($arParams)
    {
        global $DB;
        $arTesSection = SectionElementTable::getList([
            'filter'=>[
                'IBLOCK_SECTION_ID'=> $arParams['SECTION_ID']
            ]
        ])->fetchAll();
        \_::d($arTesSection);
//        foreach ($arTesSection as $item) {
//            SectionElementTable::delete([
//                'IBLOCK_SECTION_ID' =>$item['IBLOCK_SECTION_ID']
//            ]);
//        }
    }


}
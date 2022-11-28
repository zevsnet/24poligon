<?php
namespace Poligon\Core\Iblock;

use Bitrix\Iblock\ElementTable;
use Poligon\Core\Variables;

class Events
{
    /**
     * Подлючаемся к событиям Инфоблока
     */
    static public function addEventHandlers()
    {
        /*Отмена изменения символьного кода*/
        AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", [__CLASS__, 'DoIBlockBeforeSave']);
        AddEventHandler("catalog", "OnBeforeCatalogStoreUpdate", [__CLASS__, 'OnBeforeCatalogStoreUpdate']);
        AddEventHandler("catalog", "OnBeforeCatalogStoreAdd", [__CLASS__, 'OnBeforeCatalogStoreAdd']);
        /* Обновление полей при изменении/добавлении элемента (для выгрузки на маркетплейсы) */
        AddEventHandler("iblock", "OnAfterIBlockElementAdd", [__CLASS__,"OnAfterIBlockElementUpdateHandler"]);
        AddEventHandler("iblock", "OnAfterIBlockElementUpdate",[__CLASS__,"OnAfterIBlockElementUpdateHandler"]);
    }
    /**
     * @param $arFields
     *
     * Блокировка изменения  полей
     * [CODE,NAME,DETAIL_TEXT]
     */
    static function DoIBlockBeforeSave(&$arFields)
    {
        if ($arFields['IBLOCK_ID'] == Variables::IBLOCK_CATALOG_ID && $_GET['mode'] == 'import') {
            $arElement2Section = Helper::getGroupElement($arFields['ID']);
            $arElement2Section = array_unique(array_merge([$arFields['IBLOCK_SECTION_ID']],array_keys($arElement2Section)));
            unset($arFields["CODE"]);
            unset($arFields["NAME"]);
            unset($arFields["DETAIL_TEXT"]);

            $arFields['IBLOCK_SECTION'] = $arElement2Section;

            $arTovPlusId = Helper::getPropId(['IBLOCK_ID' => $arFields['IBLOCK_ID'],'CODE' => Variables::PROP_CODE_TOV_PLUS]);
            if ($arFields['PROPERTY_VALUES'][$arTovPlusId]) {
                $arMoreGoodsId = Helper::getPropId(['IBLOCK_ID' => $arFields['IBLOCK_ID'],'CODE' => Variables::PROP_CODE_MORE_GOODS]);

                $arNewID = [];
                foreach ($arFields['PROPERTY_VALUES'][$arTovPlusId] as $key => $arTov) {
                    if ($arTov['VALUE']) {
                        $arNewID[] = $arTov['VALUE'];
                    }
                }

                if (count($arNewID) > 0) {
                    $arIdElement = ElementTable::getList(['filter'=>[
                        'IBLOCK_ID' => $arFields['IBLOCK_ID'],
                        'XML_ID' => $arNewID
                    ],'select'=>['ID']])->fetchAll();
                    $arFields['PROPERTY_VALUES'][$arMoreGoodsId] = array_column($arIdElement,'ID');

                } else {
                    $arFields['PROPERTY_VALUES'][$arMoreGoodsId] = [];
                }
            }
        }
    }

    function OnAfterIBlockElementUpdateHandler(&$arFields)
    {
        $translitParams = array(
            "change_case" => "L",
            "replace_space"=>"_",
            "replace_other"=>"_"
        );
        $arSelect = Array(
            "ID",
            "IBLOCK_ID",
            "IBLOCK_SECTION_ID",
            "NAME",
            "DATE_ACTIVE_FROM",
            "PROPERTY_*"
        );
        $arFilter = Array(
            "ID" => $arFields["ID"],
            "IBLOCK_ID"=>4,
            "ACTIVE_DATE"=>"Y",
            "ACTIVE"=>"Y"
        );
        $res = \CIBlockElement::GetList(
            Array(),
            $arFilter,
            false,
            Array(),
            $arSelect
        );

        while($ob = $res->GetNextElement()):

            $arElement_fields = $ob->GetFields();
            $arElement_props = $ob->GetProperties();
            $ELEMENT_ID = $arElement_fields["ID"];
            $IBLOCK_SECTION_ID = $arElement_fields["IBLOCK_SECTION_ID"];
            $resSection = \CIBlockSection::GetByID($IBLOCK_SECTION_ID);
            if($ar_res_section = $resSection->GetNext()):
                $translit_FID_UTM_COMPAIGN = \Cutil::translit($ar_res_section['NAME'],"ru",$translitParams);
            endif;

            $translit_FID_VENDOR = \Cutil::translit($arElement_props["FP_PROIZVODITEL"]["VALUE"],"ru",$translitParams);

            $FID_UTM_COMPAIGN = $translit_FID_UTM_COMPAIGN;
            $FID_UTM_CONTENT = $translit_FID_VENDOR;
            $FID_WEIGHT = $arElement_props["FP_VES"]["VALUE"];
            $FID_DIAMETR = $arElement_props["FP_DIAMETR"]["VALUE"];
            $FID_THICKNESS = $arElement_props["FP_TOLSHINA"]["VALUE"];
            $FID_LENGTH = $arElement_props["FP_DLINA"]["VALUE"];
            $FID_WIDTH = $arElement_props["FP_SHIRINA"]["VALUE"];
            $FID_COLOR = $arElement_props["FP_CVET"]["VALUE"];

            \CIBlockElement::SetPropertyValuesEx($arFields["ID"], false, array(
                "FID_UTM_COMPAIGN" => $FID_UTM_COMPAIGN,
                "FID_UTM_CONTENT" => $FID_UTM_CONTENT,
                "FID_WEIGHT" => preg_replace('/[a-zA-Zа-яА-Я]/', '', $FID_WEIGHT),
                "FID_DIAMETR" => preg_replace('/[a-zA-Zа-яА-Я]/', '', $FID_DIAMETR),
                "FID_THICKNESS" => preg_replace('/[a-zA-Zа-яА-Я]/', '', $FID_THICKNESS),
                "FID_LENGTH" => preg_replace('/[a-zA-Zа-яА-Я]/', '', $FID_LENGTH),
                "FID_WIDTH" => preg_replace('/[a-zA-Zа-яА-Я]/', '', $FID_WIDTH),
                "FID_COLOR" => $FID_COLOR
            ));

            if($arElement_props["SKIDKA"]["VALUE"] == "" && $arElement_props["OLD_PRICE"]["VALUE"] == ""):
                \CIBlockElement::SetPropertyValuesEx($arFields["ID"], false, array(
                    "SHOW_PROMOTION_MODAL" => false
                ));
            else:
                \CIBlockElement::SetPropertyValuesEx($arFields["ID"], false, array(
                    "SHOW_PROMOTION_MODAL" => 19//Что Это?
                ));
            endif;

        endwhile;
    }

    /**
     * Блокировка Обновления Складов
     *
     * @param $id
     * @param $arFields
     */
    static function OnBeforeCatalogStoreUpdate($id, &$arFields)
    {
        if ($_GET['mode'] == 'import') {
            unset($arFields['TITLE']);
            unset($arFields['ACTIVE']);
            unset($arFields['CODE']);
            unset($arFields['ADDRESS']);
            unset($arFields['GPS_N']);
            unset($arFields['GPS_S']);
            unset($arFields['PHONE']);
            unset($arFields['SCHEDULE']);
        }

    }

    /**
     * Все новые Склады олжны быть не Активными!!!
     *
     * @param $arFields
     */
    static function OnBeforeCatalogStoreAdd(&$arFields)
    {
        if ($_GET['mode'] == 'import') {
            $arFields['ACTIVE'] = 'N';
        }
    }
}
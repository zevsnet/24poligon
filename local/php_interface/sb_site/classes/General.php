<?php
/**
 * Created by PhpStorm.
 * User: dnkolosov
 * Date: 09.02.2017
 * Time: 9:21
 */

namespace SB\Site;

use Bitrix\Main\Loader;
use CCatalogProduct;
use CIBlock;
use CIBlockElement;
use CIBlockProperty;
use CIBlockPropertyEnum;
use CIBlockSection;
use CModule;
use CSaleDiscount;
use CSite;
use SB\Site\Bitrix\BElement;
use SB\Site\Bitrix\SBElement;

class General
{


    public static function IsOrderNewPage()
    {
        return CSite::InDir(SITE_DIR . 'order_new/');
    }

    /**
     * спользовалось раньше на событие итзменения товара, для присвоения по параметрам к разделу
     * @param $arFields['IBLOCK_ID'] - ид инфоблока
     * @param $arFields['SECTION_NAME'] - название раздела
     * @param $arFields['SECTION_ID'] - название раздела
     * @param $arFields['CODE_PROP'] код свойства
     * @param $arFields['ENUM_VALUE_NAME'] - значение свойства
     *
     */
    public static function setNew($arFields)
    {
        //$arFields['CODE_PROP']
        //NOVINKA
        //HIT
        if(empty($arFields['SECTION_ID']))
            $arFields['SECTION_ID'] = BElement::getIdSection(['IBLOCK_ID' => $arFields['IBLOCK_ID'],'=NAME' => $arFields['SECTION_NAME']]);
        
        $arElement = SBElement::getElement(['IBLOCK_ID' => $arFields['IBLOCK_ID'], 'ID' => $arFields['ID']], ['*'], 1);

        if ($arElement) {
            if ($arElement['PROP'][$arFields['CODE_PROP']]['VALUE'] == $arFields['ENUM_VALUE_NAME']) {
                $ar_new_groups = General::getGroupElement($arElement['ID'], $arFields['SECTION_ID']);

                CIBlockElement::SetElementSection($arElement['ID'], $ar_new_groups);
                $obSection = new CIBlockSection();
                $obSection->Update($arFields['SECTION_ID'], ['ACTIVE' => 'Y']);

            } else {
                $ar_new_groups = General::getGroupElement($arElement['ID']);
                unset($ar_new_groups[$arFields['SECTION_ID']]);
                CIBlockElement::SetElementSection($arElement['ID'], $ar_new_groups);
            }
        }
    }

    /*Эта функция вспомогательная, чтоб смотреть подразделы*/
    public static function GetAllSectionInSel($SECTION_ID, $arParent)
    {
        $arR = array();
        for ($i = 0, $k = count($arParent[$SECTION_ID]); $i < $k; $i++) {
            array_push($arR, $arParent[$SECTION_ID][$i]);
            if (isset($arParent[$arParent[$SECTION_ID][$i]])) { //Если ребёнок является родителем
                $arR = array_merge($arR, GetAllSectionInSel($arParent[$SECTION_ID][$i], $arParent));
            }
        }
        return $arR;
    }

    public static function DeactivationSection()
    {
        Loader::includeModule('iblock');
        $bs = new \CIBlockSection;
        $arSection = SBElement::getSections(['IBLOCK_ID' => Variables::IBLOCK_ID_CATALOG], ['ID', 'NAME']);
        foreach ($arSection as $item) {
            $activeElements = \CIBlockSection::GetSectionElementsCount($item['ID'],
                Array("CNT_ACTIVE" => "Y", 'CATALOG_AVAILABLE' => 'Y'));
            if ($activeElements <= 0) {
                $bs->Update($item['ID'], ['ACTIVE' => 'N']);
            }
        }

        return '\SB\Site\General::DeactivationSection();';
    }

    public static function getPickup($ORDER_ID)
    {
        //#SB_ORDER_PICKUP#
        $str = '';
        return $str;
    }

    public function EditData($DATA) // конвертирует формат даты с 04.11.2008 в 04 Ноября, 2008
    {
        $MES = array(
            "01" => "Января",
            "02" => "Февраля",
            "03" => "Марта",
            "04" => "Апреля",
            "05" => "Мая",
            "06" => "Июня",
            "07" => "Июля",
            "08" => "Августа",
            "09" => "Сентября",
            "10" => "Октября",
            "11" => "Ноября",
            "12" => "Декабря"
        );
        $arData = explode(".", $DATA);
        $d = ($arData[0] < 10) ? substr($arData[0], 1) : $arData[0];

        $newData = $d . " " . $MES[$arData[1]] . " " . $arData[2];
        return $newData;
    }

    public function getarWaterMark($sizeImage = 120, $fileID = false, $isOriginal = false)
    {
        //debug_print_backtrace();

        \CModule::IncludeModule('catalog');
        \CModule::IncludeModule('main');

        //WATER_MARK_LINK
        $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_*");
        $arFilter = Array("IBLOCK_ID" => IntVal(\SB\Site\Variables::IBLOCK_ID_SETTINGS), 'CODE' => '24Poligon');
        $res = \CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize" => 1), $arSelect);
        $ob = $res->GetNextElement();
        $arProps = $ob->GetProperties();

        $inputFile = \CFile::GetPath($fileID);
        $arInfo = \CFile::GetImageSize($_SERVER['DOCUMENT_ROOT'] . $inputFile);
        $arInfo = min($arInfo[0], $arInfo[1]);
        if ($arInfo < $sizeImage) {
            $sizeImage = $arInfo;
        }

        $sizeImage_ = $sizeImage - $sizeImage / 2;
        if ($isOriginal) {
            $sizeImage = $arInfo;
            $sizeImage_ = $sizeImage;
        }

        $StartWaterMark = \CFile::ResizeImageGet($arProps['WATER_MARK']['VALUE'],
            ["width" => $sizeImage_, "height" => $sizeImage_], BX_RESIZE_IMAGE_PROPORTIONAL_ALT);


        $StartWaterMark = $StartWaterMark['src'];

        $arWMRes = [
            [
                "name" => "watermark",
                "position" => ($arProps['WATER_POSITION']['VALUE_XML_ID']) ?: 'center',
                "type" => "image",
                "size" => "real",
                "file" => $_SERVER['DOCUMENT_ROOT'] . $StartWaterMark,
                "fill" => "exact",
            ]
        ];

        return \CFile::ResizeImageGet($fileID, array("width" => $sizeImage, "height" => $sizeImage),
            BX_RESIZE_IMAGE_PROPORTIONAL, true, []/*$arWMRes*/);


    }

    public function deleteProperty($IBLOCK_ID, $nonDeleteArray = [])
    {
        $deletedCount = 0;

        if (\CModule::IncludeModule("iblock")) {
            $properties = \CIBlockProperty::GetList(array("sort" => "asc", "name" => "asc"),
                array("ACTIVE" => "Y", "IBLOCK_ID" => $IBLOCK_ID));
            while ($prop_fields = $properties->GetNext()) {
                if (!in_array($prop_fields["ID"], $nonDeleteArray)) {
                    // Строка удаления свойств
                    if (\CIBlockProperty::Delete($prop_fields["ID"])) {
                        $deletedCount++;
                    }
                }
            }
        }

        echo 'Удалено свойств: ' . $deletedCount;
    }

    static public function addCupon2NewUser($USER_ID = false)
    {
        $couponResult = null;
        \CModule::IncludeModule('sale');

        $saleDiscountDb = CSaleDiscount::GetList(
            array("SORT" => "ASC"),
            array(
                "LID" => SITE_ID,
                "ACTIVE" => "Y",
                'XML_ID' => 'sb_reg_user'
            ),
            false,
            false,
            array()
        );

        $saleDiscountId = null;
        if ($saleDiscount = $saleDiscountDb->Fetch()) {
            $saleDiscountId = $saleDiscount['ID'];
        }

        if ($saleDiscountId) {
            $coupon = \Bitrix\Sale\Internals\DiscountCouponTable::generateCoupon(true);
            if (!$USER_ID) {
                global $USER;
                $USER_ID = ($USER->GetID()) ?: 0;
            }
            $addDb = \Bitrix\Sale\Internals\DiscountCouponTable::add(array(
                'DISCOUNT_ID' => $saleDiscountId,
                'COUPON' => $coupon,
                'TYPE' => \Bitrix\Sale\Internals\DiscountCouponTable::TYPE_ONE_ORDER,
                'MAX_USE' => 1,
                'USER_ID' => $USER_ID,
                'DESCRIPTION' => ''
            ));

            if ($addDb->isSuccess()) {
                $couponResult = $coupon;
            }
        }

        return $couponResult;
    }

    static public function getPropCodeSize($IBLOCK_ID, $NAME_VALUE)
    {
        CModule::IncludeModule('iblock');
        $res = CIBlock::GetProperties($IBLOCK_ID, [], ["NAME" => $NAME_VALUE]);
        $arCodeRes = [];
        while ($res_arr = $res->Fetch()) {
            switch ($res_arr['CODE']) {
//                case 'RAZMER_9':break;
                default:
                    $arCodeRes[$res_arr['ID']] = $res_arr['CODE'];
            }
        }

        return $arCodeRes;
    }

    static public function getTitleNew($qFindKey = 'title')
    {
        global $APPLICATION, $arEditMeta;

        $tmpNewStrMeta = false;
        if ($arEditMeta[$qFindKey]) {
            foreach ($arEditMeta[$qFindKey] as $key => $Item) {
                $tmpStrMeta = $APPLICATION->GetProperty($qFindKey);
                $tmpNewStrMeta = str_replace($key, $Item, $tmpStrMeta);
            }
        }
        return $tmpNewStrMeta;
    }



    public static function getGroupElement($ELEMENT_ID, $ID_SECTION = false)
    {
        Loader::includeModule('iblock');
        $db_old_groups = CIBlockElement::GetElementGroups($ELEMENT_ID, true);
        $ar_new_groups = [];
        while ($ar_group = $db_old_groups->Fetch()) {
            $ar_new_groups[$ar_group["ID"]] = $ar_group["ID"];
        }
//        if ($ID_SECTION) {
//            $ar_new_groups[$ID_SECTION] = $ID_SECTION;
//        }
        return $ar_new_groups;
    }

    /**
     * @param $count - Количество доступности
     */
    public static function updateQuantityElements($IBLOCK_ID, $count)
    {
        Loader::includeModule('iblock');

        $newq = new CCatalogProduct;
        $arFilter = array(
            'IBLOCK_ID' => $IBLOCK_ID,//Номер инфоблока
        );
        $res = CIBlockElement::GetList(false, $arFilter, array('IBLOCK_ID', 'ID'));
        while ($el = $res->GetNext()) {
            $ID = $el['ID'];
            $ar_res = CCatalogProduct::GetByID($ID);
            $newq->Update($ID, array('QUANTITY' => $count));//количество
        }
    }


}
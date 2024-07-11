<?php


namespace Poligon\Core\Iblock;


use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\PropertyTable;
use Bitrix\Main\Application;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\Loader;
use CIBlock;
use CIBlockElement;
use CIBlockProperty;
use CIBlockPropertyEnum;
use Poligon\Core\Variables;

Loader::includeModule('iblock');
Loader::includeModule('catalog');

class Helper
{
    public static function getIdByCode($code)
    {
        $res = CIBlock::GetList([], ["=CODE" => $code], true);
        if ($ar_res = $res->Fetch()) {
            return $ar_res['ID'];
        }
        return false;
    }

    public static function getElIdByCode($elementCode, $iblockId = false, $ttl = 86400 * 30)
    {
        $filter = ['CODE' => $elementCode];
        if ($iblockId) {
            $filter["=IBLOCK_ID"] = $iblockId;
        }
        $arElement = ElementTable::getList([
            'filter' => $filter,
            'select' => ['ID']
        ])->fetch();
        return $arElement['ID'];

    }

    public static function getSearchNameElements($IBLOCK_ID, $QUERY)
    {

        $connection = Application::getConnection();
        $sql = "SELECT `ID`,`IBLOCK_SECTION_ID`,`SEARCHABLE_CONTENT` FROM b_iblock_element WHERE `ACTIVE`='Y' AND IBLOCK_ID='" . $IBLOCK_ID . "' AND SEARCHABLE_CONTENT like '%" . $QUERY . "%'";
        $obRes = $connection->query($sql);
        $arRes = $obRes->fetchAll();
        $arElemntsID = [];
        foreach ($arRes as $arRe) {
            $arElemntsID[] = $arRe['ID'];
        }

        return $arElemntsID;
    }

    public static function getPropertyId($propertyCode, $iblockId)
    {
        $propertyIterator = PropertyTable::getList([
            'filter' => ['IBLOCK_ID' => $iblockId, 'CODE' => $propertyCode],
            'select' => ['ID', 'NAME', 'CODE']
        ]);
        $propertyList = [];
        foreach ($propertyIterator as $property) {
            $propertyList[$property['CODE']] = $property;
        }
        return $propertyList;
    }

    public static function exportSection2CSV()
    {

        $linkFileExport = $_SERVER['DOCUMENT_ROOT'] . '/upload/section.csv';
        if (file_exists($linkFileExport)) {
            file_put_contents($linkFileExport, 'ID;ID_PARENT;NAME;XML_ID');
        }
        $obSections = \CIBlockSection::GetList([], ['IBLOCK_ID' => Helper::getIdByCode('aspro_max_catalog')]);
        while ($arSection = $obSections->Fetch()) {

            file_put_contents($linkFileExport, implode(';', [
                    $arSection['ID'],
                    $arSection['IBLOCK_SECTION_ID'],
                    $arSection['XML_ID'],
                    $arSection['NAME'],
                    $arSection['XML_ID'],
                ]) . "\n", FILE_APPEND);
        }
    }


//вернуть элементы
    public static function getElement($arFilter, $arSelect = ['*'], $arNavStartParam = false, $arOrder = [])
    {
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

    public static function getSection($arFilter, $arSelect = ['*'], $arNavStartParam = false)
    {
        $res = \CIBlockSection::GetList(['SORT' => 'ASC'], $arFilter, false, $arSelect);

        $arResult = false;
        if ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
            $arFields['ITEMS'] = [];
            return $arFields;

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

    /*
     * Вернет все группы элемента ()?
     */
    public static function getGroupElement($ELEMENT_ID, $ID_SECTION = false)
    {
        $db_old_groups = CIBlockElement::GetElementGroups($ELEMENT_ID, true);
        $ar_new_groups = [];
        if ($ID_SECTION) {
            $ar_new_groups[$ID_SECTION] = $ID_SECTION;
        }
        while ($ar_group = $db_old_groups->Fetch()) {
            if ($ar_group["ID"] != '') {
                $ar_new_groups[$ar_group["ID"]] = $ar_group["ID"];
            }
        }
        return $ar_new_groups;
    }

    /**
     * @param $arFields
     *
     * @return bool|mixed
     * @throws \Bitrix\Main\LoaderException
     *
     * По CODE - свойства вернем ID свойства
     * @todo  Нужно кэширование!!!
     */
    public static function getPropId($arFields)
    {
        $properties = CIBlockProperty::GetList(Array("sort" => "asc", "name" => "asc"), $arFields);
        while ($prop_fields = $properties->GetNext()) {
            return $prop_fields["ID"];
        }
        return false;
    }

    /**
     * Вернет количество
     *
     * @param $SECTION_ID
     * @param $PAGE_ELEMENT_COUNT
     */
    public static function getCountPage2Section($SECTION_CODE, $PAGE_ELEMENT_COUNT)
    {
        switch ($SECTION_CODE) {
            case 'truba-kvadratnaya':
            case 'truba-pryamougolnaya':
                return 1000;
                break;
        }
        return $PAGE_ELEMENT_COUNT;
    }

    /**
     * Вернет СЕО поля для товара
     *
     * @param $IBLOCK_ID
     * @param $ELEMENT_ID
     * @param $REGION - todo: нереализован нужен будет для получения для разных регионов
     *
     * @throws \Bitrix\Main\LoaderException
     */
    public static function getSeoProp2Element($IBLOCK_ID, $ELEMENT_ID, $REGION = '')
    {
        return new \Bitrix\Iblock\InheritedProperty\ElementValues($IBLOCK_ID, $ELEMENT_ID);
    }

    /**
     * Вернет цену в нужном формате
     * @param $getPrice
     */
    public static function getFormatPrice($getPrice)
    {
        return CurrencyFormat($getPrice, 'RUB');
    }

    /**
     * Добавляет/Обновляет элемент инфоблока
     *
     * @param $arFields - Поля елемента. Если передат 'ID' - то выполняется Update
     * @param bool $prepareFields - Флаг обработки полей, заполняет некоторые поля (ACTIVE,CODE,MODIFIED_BY)
     * @param bool $checkXmlId - Флаг Получения эле елемента по XML_ID. В случаи нахождения элемента выполняется Update
     *
     * @return int|null
     * @throws ArgumentNullException
     * @throws \Bitrix\Main\LoaderException
     */
    public function saveElement($arFields, $prepareFields = true, $checkXmlId = true)
    {
        static $Element;

        $elementId = !empty($arFields['ID']) ? $arFields['ID'] : null;
        unset($arFields['ID']);

        if (!$elementId && empty($arFields['IBLOCK_ID'])) {
            throw new ArgumentNullException('ID или IBLOCK_ID обязательно');
        }

        $iBlockId = $arFields['IBLOCK_ID'];

        if ($Element === null) {
            $Element = new \CIBlockElement;
        }

        $arProperties = $arFields['PROPERTIES'] ?: array();
        unset($arFields['PROPERTIES']);
        Loader::includeModule('iblock');

        // Если не передан ИД элемента и параметр проверять  по XML_ID установлен, то ищем элемент по XML_ID, и получаем ИД элемента для update
        if (!$elementId && $checkXmlId && !empty($arFields['XML_ID'])) {
            $arElement = ElementTable::getRow([
                'filter' => [
                    'IBLOCK_ID' => $iBlockId,
                    'XML_ID' => $arFields['XML_ID']
                ],
                'select' => ['ID']
            ]);
            if (!empty($arElement['ID'])) {
                $elementId = $arElement['ID'];
            }
        }


        if ($prepareFields && !array_key_exists('MODIFIED_BY', $arFields)) {
            global $USER;
            if ($USER->GetID()) {
                $arFields['MODIFIED_BY'] = $USER->GetID();
            }
        }

        if ($elementId) {
            $Element->Update($elementId, $arFields);
        } else {
            // Обрабатываем поля
            if ($prepareFields) {
                if (!array_key_exists('ACTIVE', $arFields)) {
                    $arFields['ACTIVE'] = 'Y';
                }

                if (!array_key_exists('CODE', $arFields) && array_key_exists('NAME', $arFields)) {
                    $arFields['CODE'] = \Cutil::translit($arFields['NAME'], 'ru');
                }
            }

            $elementId = $Element->Add($arFields);
        }

        // Сохраняем свойства
        if ($arProperties && $elementId) {
            \CIBlockElement::SetPropertyValuesEx($elementId, $iBlockId, $arProperties);
        }

        return $elementId;
    }

    /**
     * Вернет ИД значения свойства
     *
     * @param $CODE_PROP
     * @param $VALUE
     *
     * @return mixed
     */
    public static function getIdEnumListProp($CODE_PROP, $XML_ID)
    {
        $property_enums = CIBlockPropertyEnum::GetList(Array("ID" => "ASC", "SORT" => "ASC"),
            Array("IBLOCK_ID" => Helper::getIdByCode(Variables::IBLOCK_CATALOG_CODE), "CODE" => $CODE_PROP));
        while ($enum_fields = $property_enums->GetNext()) {
            if ($enum_fields["XML_ID"] == $XML_ID) {
                return $enum_fields["ID"];
            }
        }
        return false;
    }

    public static function countLine(){
        switch($_SERVER['REQUEST_URI']){
            case '/catalog/?q=rasprodazha':
                return 4; break;
            default:
                return 3;
        }
    }
}

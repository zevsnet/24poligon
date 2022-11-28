<?

namespace SB\Bitrix\Tools;

use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\IblockTable;
use Bitrix\Iblock\PropertyIndex\Manager;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\DB\Exception;
use Bitrix\Main\DB\SqlQueryException;
use Bitrix\Main\Loader;
use \CIBlockElement as CIBlockElement;
use \CIBlockSection as CIBlockSection;
use SB\Bitrix\Tools;

/**
 * Class IBlock
 * @todo отрефакторить (выкинуть ненужное)
 * @package SB\Bitrix\Tools
 */
class IBlock
{
    /**
     * Выводит свойство из DISPLAY_PROPERTIES
     * @param array $arItem - элемент для поиска
     * @param string $name - имя свойства
     * @param string $field - поле свойства
     * @return mixed|null
     */
    public static function getDisplayProperty(array $arItem, $name, $field = 'DISPLAY_VALUE')
    {
        return $arItem['DISPLAY_PROPERTIES'][$name][$field] ?? null;
    }

    /**
     * Выводит свойство из PROPERTIES
     * @param array $arItem - элемент для поиска
     * @param string $name - имя свойства
     * @param string $field - поле свойства
     * @return mixed|null
     */
    public static function getProperty(array $arItem, $name, $field = 'VALUE')
    {
        return $arItem['PROPERTIES'][$name][$field] ?? null;
    }

    /**
     * надстройка над \CIBlockElement::GetList, только выводит 1 элемент, сделано для уменьшения параметров
     * @see \CIBlockElement::GetList()
     * @param array $arFilter
     * @param array $arSelectFields
     * @param bool $arGroupBy
     * @return array
     */
    public function getElement(array $arFilter = Array(), array $arSelectFields = array(), $arGroupBy = false): array
    {
        $arOrder = array();
        $arNavStartParams = array('nPageSize' => 1);
        $res = CIBlockElement::GetList($arOrder, $arFilter, $arGroupBy, $arNavStartParams, $arSelectFields);

        if ($ob = $res->GetNextElement()) {
            return $ob->GetFields();
        }

        return [];
    }

    /**
     * Добавляет/Обновляет элемент инфоблока
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

        if(!$elementId && empty($arFields['IBLOCK_ID'])) {
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
     * отчистить инфоблок от элементов
     * @param int $iBlockId
     * @return bool
     * @throws \Bitrix\Main\LoaderException
     */
    public function clearIBlock(int $iBlockId) : bool
    {
        Loader::includeModule('iblock');
        global $DB;
        $version = IblockTable::getRow([
            'filter' => [
                'ID' => $iBlockId
            ],
            'select' => [
                'VERSION'
            ]
        ])['VERSION'];

        $DB->StartTransaction();

        if($version === 2)
        {
            if(!$DB->Query("TRUNCATE TABLE b_iblock_element_prop_m{$iBlockId}"))
            {
                $DB->Rollback();
                return false;
            }
            if(!$DB->Query("TRUNCATE TABLE b_iblock_element_prop_s{$iBlockId}"))
            {
                $DB->Rollback();
                return false;
            }
        }
        else
        {
            if(!$DB->Query("DELETE FROM b_iblock_element_property WHERE IBLOCK_ELEMENT_ID IN( SELECT ID FROM b_iblock_element WHERE IBLOCK_ID = {$iBlockId} )"))
            {
                $DB->Rollback();
                return false;
            }
        }

        if(!$DB->Query("DELETE FROM b_iblock_element WHERE IBLOCK_ID = {$iBlockId}"))
        {
            $DB->Rollback();
            return false;
        }

        $DB->Commit();

        Manager::deleteIndex($iBlockId);

        \CIBlock::clearIblockTagCache($iBlockId);

        return true;
    }


    /**
     * Копирование инфоблока
     * @param int $iBlockId - ид инфоблока
     * @param array $arParams - параметры, которые нужно заменить
     * @param bool $properties - флаг копирования свойств
     *
     * @return bool
     * @throws \Bitrix\Main\DB\SqlQueryException
     * @throws ArgumentNullException
     * @throws Exception
     * @throws \Bitrix\Main\LoaderException
     */
    public static function iBlockCopy(int $iBlockId, array $arParams = [], $properties = true): bool
    {
        if (!$iBlockId) {
            throw new ArgumentNullException('invalid parameter IBLOCK_ID');
        }

        Loader::includeModule('iblock');

        $ib = new \CIBlock;
        $arFields = \CIBlock::GetArrayByID($iBlockId);
        $arFields['GROUP_ID'] = \CIBlock::GetGroupPermissions($iBlockId);

        $arFields = array_merge($arFields, $arParams);

        unset($arFields["ID"]);


        $id = $ib->Add($arFields);
        if (!$id) {
            throw new SqlQueryException($ib->LAST_ERROR);
        }


        if ($properties) {
            static::propertyCopy($iBlockId, $id);
        }

        return $id;
    }

    /**
     * Копирование свойст инфоблока
     * @param int $iBlockIdFrom - ид инфоблока от куда копировать
     * @param int $iBlockIdTo - ид инфоблока куда копировать
     * @param array $arFilter - фильтр для свойства
     * @throws SqlQueryException
     */
    public static function propertyCopy(int $iBlockIdFrom, int $iBlockIdTo, array $arFilter = [])
    {
        $ibp = new \CIBlockProperty;
        $dbProperty = \CIBlockProperty::GetList([], array_merge(['IBLOCK_ID' => $iBlockIdFrom], $arFilter));
        while ($arProperty = $dbProperty->Fetch()) {
            if ($arProperty['PROPERTY_TYPE'] === 'L') {
                $dbPropertyEnum = \CIBlockPropertyEnum::GetList(
                    [],
                    [
                        'IBLOCK_ID' => $iBlockIdFrom,
                        'ID' => $arProperty['ID']
                    ]
                );

                while ($arEnum = $dbPropertyEnum->Fetch()) {
                    $arProperty['VALUES'][] = Array(
                        'VALUE' => $arEnum['VALUE'],
                        'DEF' => $arEnum['DEF'],
                        'SORT' => $arEnum['SORT']
                    );
                }
            }
            $arProperty['IBLOCK_ID'] = $iBlockIdTo;
            unset($arProperty['ID']);

            $propId = $ibp->Add($arProperty);
            if (!$propId) {
                throw new SqlQueryException($ibp->LAST_ERROR);
            }
        }
    }
}
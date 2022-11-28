<?php
/**
 * Created by PhpStorm.
 * User: dnkolosov
 * Date: 16.03.2018
 * Time: 16:24
 * @author Denis Kolosov <kdnn@mail.ru>
 */

namespace SB\Bitrix\Entity;

use Bitrix\Iblock\IblockTable;
use Bitrix\Iblock\PropertyTable;
use Bitrix\Main\Loader;
use SB\Util\Entity\PropertyValue;

/**
 * класс собирающий сущность для работы с элементами инфоблока и его свойствами через ORM.
 *
 * Обращаться к свойствам можно через 'PROPERTY_"PROPERTY_CODE"'
 *
 * Если необходимо выбрать значения свойства сгруппированно, используйте PROPERTY_"PROPERTY_CODE".VALUE_ARRAY
 *
 * TODO: не корректно фильтрует по свойству типа "Число", сейчас преобразуется значение в строку
 *
 * Class Element
 * @package SB\Site
 * @example Bitrix\Entity\Element.php 2 пример работы с классом
 */
class Element
{
    protected static $entityInstance = array();

    /**
     * собирает сущность элементов для определенного инфоблока
     * @param int $iBlockId
     * @return mixed|null
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     */
    final public static function compileEntityByIBlock(int $iBlockId)
    {
        if ($iBlockId <= 0) {
            return null;
        }

        if (!isset(self::$entityInstance[$iBlockId])) {
            Loader::includeModule('iblock');
            $className = 'Element' . $iBlockId . 'Table';
            $entityName = "\\SB\\Bitrix\\Entity\\" . $className;

            $entity = '
			namespace SB\Bitrix\Entity;
			class ' . $className . ' extends \Bitrix\Iblock\ElementTable
			{
				public static function getMap()
				{
					$fields = parent::getMap();
					
					' . self::buildProperty($iBlockId) . '
					
					$fields["PROPERTY"] = new \Bitrix\Main\Entity\ReferenceField(
                        "PROPERTY",
                        "Bitrix\Iblock\Property",
                        array(
                            "=this.IBLOCK_ID" => "ref.IBLOCK_ID"
                        )
                    );
					
					return $fields;
				}
				
				public static function getList(array $parameters = array())
                {
                    $parameters["filter"]["IBLOCK_ID"] = ' . $iBlockId . ';
                    return parent::getList($parameters);
                }
			}';
            eval($entity);
            self::$entityInstance[$iBlockId] = $entityName;
        }

        return self::$entityInstance[$iBlockId];
    }

    /**
     * собирает свойства, обращаться через PROPERTY_"CODE"
     * @param int $iBlockId
     * @return string
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     */
    protected static function buildProperty(int $iBlockId): string
    {
        $arProperties = [];
        $dbProperty = PropertyTable::getList([
            'filter' => ['IBLOCK_ID' => $iBlockId],
            'select' => ['ID', 'CODE', 'VERSION', 'MULTIPLE'],
//            'cache' => [
//                'ttl' => 3600
//            ]
        ]);
        while ($arProperty = $dbProperty->fetch()) {
            if((int) $arProperty['VERSION'] === IblockTable::PROPERTY_STORAGE_COMMON) {
                $arProperties[] = '$fields["PROPERTY_' . $arProperty['CODE'] . '"] = new \Bitrix\Main\Entity\ReferenceField(
                    "PROPERTY_' . $arProperty['CODE'] . '",
                    "SB\Bitrix\Model\PropertyValueTable",
                    array(
                        "ref.IBLOCK_PROPERTY_ID" => new \Bitrix\Main\DB\SqlExpression("?i", ' . $arProperty['ID'] . '),
                        "=this.ID" => "ref.IBLOCK_ELEMENT_ID"
                    )
                );';
            } elseif($arProperty['MULTIPLE'] === 'Y') {
                $arProperties[] = '$fields["PROPERTY_' . $arProperty['CODE'] . '"] = new \Bitrix\Main\Entity\ReferenceField(
                    "PROPERTY_' . $arProperty['CODE'] . '",
                    "' . static::compilePropertyEntity($iBlockId, (int)$arProperty['ID'], true) . '",
                    array(
                        "ref.IBLOCK_PROPERTY_ID" => new \Bitrix\Main\DB\SqlExpression("?i", ' . $arProperty['ID'] . '),
                        "=this.ID" => "ref.IBLOCK_ELEMENT_ID"
                    )
                );';
            } else {
                $arProperties[] = '$fields["PROPERTY_' . $arProperty['CODE'] . '"] = new \Bitrix\Main\Entity\ReferenceField(
                    "PROPERTY_' . $arProperty['CODE'] . '",
                    "' . static::compilePropertyEntity($iBlockId, (int)$arProperty['ID']) . '",
                    array(
                        "=this.ID" => "ref.IBLOCK_ELEMENT_ID"
                    )
                );';
            }
        }


        return implode("\n\n", $arProperties);
    }

    /**
     * собирает сущность свойства, если оно в отдельных таблицах
     * @param int $iBlockId
     * @param int $propertyId
     * @param bool $multiple
     * @return mixed|null
     * @throws \Bitrix\Main\LoaderException
     */
    protected static function compilePropertyEntity(int $iBlockId, int $propertyId, $multiple = false)
    {
        return PropertyValue::compileEntity($iBlockId, $propertyId, $multiple);
    }
}
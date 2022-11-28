<?php

namespace SB\Util\Entity;

use Bitrix\Main\Loader;

/**
 * Сборщик сущностей для свойств инфоблока
 *
 * Class PropertyValue
 * @package SB\Util\Entity
 */
class PropertyValue
{
    protected static $entityInstance = [];

    /**
     * Собирает сущности для свойств
     * @param int $iBlockId
     * @param int $propertyId
     * @param bool $multiple
     * @return mixed|null
     * @throws \Bitrix\Main\LoaderException
     */
    final public static function compileEntity(int $iBlockId, int $propertyId, bool $multiple = false)
    {
        if ($iBlockId <= 0 || $propertyId <= 0) {
            return null;
        }

        if (!isset(self::$entityInstance[$iBlockId][$propertyId])) {
            Loader::includeModule('iblock');
            $className = 'PropertyValue' . $iBlockId . 'Prop' . $propertyId . 'Table';
            $entityName = "\\SB\\Bitrix\\Entity\\" . $className;

            $entity = $multiple ? static::buildMultiply($className, $iBlockId) : static::buildSingle($className,
                $iBlockId, $propertyId);

            eval($entity);
            self::$entityInstance[$iBlockId][$propertyId] = $entityName;
        }

        return self::$entityInstance[$iBlockId][$propertyId];
    }

    /**
     * Собирает класс для multiple-свойства
     *
     * @param $className
     * @param int $iBlockId
     * @return string
     */
    protected static function buildMultiply($className, int $iBlockId): string
    {
        return '
			namespace SB\Bitrix\Entity;
			class ' . $className . ' extends \SB\Bitrix\Model\PropertyMultipleValueTable
			{
				protected static $iBlockId = ' . $iBlockId . ';
			}';
    }

    /**
     * Собирает класс для single-свойства
     *
     * @param $className
     * @param int $iBlockId
     * @param int $propertyId
     * @return string
     */
    protected static function buildSingle($className, int $iBlockId, int $propertyId): string
    {
        return '
            namespace SB\Bitrix\Entity;
			class ' . $className . ' extends \SB\Bitrix\Model\PropertySingleValueTable
			{
				protected static $iBlockId = ' . $iBlockId . ';
				protected static $propertyId = ' . $propertyId . ';
			}';
    }
}

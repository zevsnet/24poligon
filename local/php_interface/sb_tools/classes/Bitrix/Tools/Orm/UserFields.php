<?php

namespace SB\Bitrix\Tools\Orm;

use Bitrix\{
    Main\Entity\Base, Main\Entity\DataManager
};

class UserFields
{
    /**
     * @param Base|string|DataManager $entity
     * @param string $userFieldId
     * @return Base
     */
    public static function attachToEntity($entity, string $userFieldId): Base
    {
        if ($entity instanceof Base) {
            $entity = $entity->getDataClass();
        }

        /** @var string|DataManager $className */
        $className = static::getUserFieldsEntityClassName($entity, $userFieldId);
        if (!class_exists($className, false)) {
            static::compileEntity($className, $entity, $userFieldId);
        }

        return $className::getEntity();
    }

    /**
     * @param string $dataClassName
     * @param string $userFieldId
     * @return string|DataManager
     */
    protected static function getUserFieldsEntityClassName(string $dataClassName, string $userFieldId): string
    {
        $baseClass = preg_replace('@^(.+)Table$@', '$1',
            basename(str_replace('\\', '/', $dataClassName)));

        $ufCamel = strtr(ucwords(str_replace('_', ' ', strtolower($userFieldId))), [' ' => '']);

        return sprintf('%sWithUf%sTable', $baseClass, $ufCamel);
    }

    /**
     * Генерирует код класса `$className`, отнаследованного от `$baseClass`
     * с объявлением метода `getUfId()`, возвращающем значение `$ufID`,
     * и объявляет этот класс при помощи `eval()`
     *
     * @param string $className
     * @param string $baseClass
     * @param string $userFieldId
     *
     * @uses eval()
     */
    protected static function compileEntity($className, $baseClass, $userFieldId)
    {
        $export = sprintf(
            'class %s extends %s {' . PHP_EOL
            . 'public static function getUfId() {' . PHP_EOL
            . 'return %s;' . PHP_EOL
            . '}' . PHP_EOL
            . '}',
            $className,
            $baseClass,
            var_export($userFieldId, true)
        );

        eval($export);
    }
}
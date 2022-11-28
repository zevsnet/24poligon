<?php

namespace SB\Bitrix\Tools;

use Bitrix\Main\Entity;
use SB\Bitrix\Entity\DynamicBase;

class OrmHelper
{
    protected static $ufEntity = array();

    /**
     * @param string $dbTableName
     * @param array $arFieldMap
     * @param string|null $connectionName
     * @param bool $uts
     * @param bool $utm
     *
     * @return DynamicBase
     */
    public static function createDynamicBase(
        $dbTableName,
        array $arFieldMap = array(),
        $connectionName = null,
        $uts = false,
        $utm = false
    ): DynamicBase {
        return new DynamicBase($dbTableName, $arFieldMap, $connectionName, $uts, $utm);
    }

    /**
     * @param \Bitrix\Main\Entity\Query $query
     * @return int
     * @throws \Bitrix\Main\Db\SqlQueryException
     */
    public static function getCountByQuery(Entity\Query $query): int
    {
        $queryClone = clone $query;
        $queryClone->setOrder([])
            ->setOffset(0)
            ->setLimit(0);

        $sql = sprintf(
            'SELECT COUNT(*) FROM (%s) AS A',
            $queryClone->getQuery()
        );

        $result = $queryClone->getEntity()->getConnection()->queryScalar($sql);

        return (int)$result ?: 0;
    }
}
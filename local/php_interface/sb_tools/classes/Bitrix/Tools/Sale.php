<?php

namespace SB\Bitrix\Tools;

use Bitrix\Catalog\GroupAccessTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Type\Collection;

/**
 * Class Sale
 * @package SB\Bitrix\Tools
 */
class Sale
{
    /**
     * Возвращает массив типов цен, доступных для покупки, по списку групп пользователя
     *
     * @param array $userGroups
     *
     * @return array
     *
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     */
    public static function getAllowedPriceTypes(array $userGroups): array
    {
        Loader::includeModule('sale');
        Loader::includeModule('catalog');

        static $priceTypeCache = [];

        Collection::normalizeArrayValuesByInt($userGroups);
        if (empty($userGroups)) {
            return [];
        }

        $cacheKey = 'U' . implode('_', $userGroups);
        if (!isset($priceTypeCache[$cacheKey])) {
            $priceTypeCache[$cacheKey] = [];
            $priceIterator = GroupAccessTable::getList([
                'select' => ['CATALOG_GROUP_ID'],
                'filter' => ['@GROUP_ID' => $userGroups, '=ACCESS' => GroupAccessTable::ACCESS_BUY],
                'order' => ['CATALOG_GROUP_ID' => 'ASC']
            ]);
            while ($priceType = $priceIterator->fetch()) {
                $priceTypeId = (int)$priceType['CATALOG_GROUP_ID'];
                $priceTypeCache[$cacheKey][$priceTypeId] = $priceTypeId;
                unset($priceTypeId);
            }
            unset($priceType, $priceIterator);
        }

        return $priceTypeCache[$cacheKey];
    }
}
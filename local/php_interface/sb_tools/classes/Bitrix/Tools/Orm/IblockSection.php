<?php

namespace SB\Bitrix\Tools\Orm;

use Bitrix\Iblock\SectionTable;
use Bitrix\Main\Entity\Base;

class IblockSection
{
    /**
     * Возвращает сущность раздела инфоблока с привязанными пользовательскими полями
     * @param int $iblockId
     * @return Base
     * @todo \Bitrix\Iblock\Model\Section::compileEntityByIblock($iblockId)
     */
    public static function getIBlockSectionEntity(int $iblockId): Base
    {
        return UserFields::attachToEntity(
            SectionTable::getEntity(),
            static::getIBlockSectionUserFieldID($iblockId)
        );
    }

    /**
     * @param int $iblockId
     * @return string
     */
    public static function getIBlockSectionUserFieldID($iblockId): string
    {
        return sprintf('IBLOCK_%u_SECTION', $iblockId);
    }
}
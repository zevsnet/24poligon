<?php

namespace SB\Bitrix\Tools\Orm;

use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\SectionTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Entity\Query;
use Bitrix\Main\LoaderException;
use Bitrix\Main\SystemException;

class IblockElement
{
    const REF_ELEMENT_PROPERTY = 1;
    const REF_ELEMENT_UF_SECTION = 2;
    const REF_ELEMENT_SECTIONS = 4;
    const REF_ELEMENT_UF_SECTIONS = 6;

    /**
     * @param int $iblockId
     * @param int $refMask
     *
     * @return Query
     * @throws ArgumentException
     * @throws LoaderException
     * @throws SystemException
     */
    public static function getIBlockElementQuery(int $iblockId, $refMask = 0): Query
    {
        $query = ElementTable::query();
        $query->addFilter('=IBLOCK_ID', $iblockId);

        if ($refMask & static::REF_ELEMENT_PROPERTY) {
            $propEnt = IblockProperties::getIBlockPropertiesEntity($iblockId);
            $query->registerRuntimeField('PROPERTY', array(
                'data_type' => $propEnt,
                'reference' => array(
                    '=this.ID' => 'ref.IBLOCK_ELEMENT_ID'
                )
            ));
        }

        if ($refMask & static::REF_ELEMENT_SECTIONS) {
            $query->registerRuntimeField('SECTIONS', array(
                'data_type' => 'Bitrix\Iblock\SectionElement',
                'reference' => array(
                    '=this.ID' => 'ref.IBLOCK_ELEMENT_ID'
                )
            ));
        }

        if ($refMask & static::REF_ELEMENT_UF_SECTION) {
            $sectionEnt = UserFields::attachToEntity(
                SectionTable::getEntity(),
                IblockSection::getIBlockSectionUserFieldID($iblockId)
            );

            if ($refMask & static::REF_ELEMENT_SECTIONS) {
                $query->registerRuntimeField('SECTION_REF', array(
                    'data_type' => $sectionEnt,
                    'reference' => array(
                        '=this.SECTIONS.IBLOCK_SECTION_ID' => 'ref.ID'
                    )
                ));
            } else {
                $query->registerRuntimeField('SECTION_REF', array(
                    'data_type' => $sectionEnt,
                    'reference' => array(
                        '=this.IBLOCK_SECTION_ID' => 'ref.ID'
                    )
                ));
            }
        }

        return $query;
    }
}
<?php

namespace SB\Bitrix\Tools\Orm;

use Bitrix\{
    Main\ArgumentException, Main\DB, Main\Entity\Base, Iblock\PropertyTable, Main\LoaderException, Main\SystemException
};
use SB\Bitrix\Entity\HighLoadBlock;
use SB\Bitrix\Tools\OrmHelper;

class IblockProperties
{

    /**
     * @param int $iblockId
     * @return Base
     * @throws ArgumentException
     * @throws LoaderException
     * @throws SystemException
     */
    public static function getIBlockPropertiesEntity(int $iblockId): Base
    {
        $props = static::getIblockProperties([
            '=IBLOCK_ID' => $iblockId
        ]);

        if (empty($props)) {
            return null;
        }

        return static::getEntityByProp($iblockId, $props);
    }

    /**
     * @param array $filter
     * @param array $select
     * @return array
     */
    protected static function getIblockProperties(array $filter = [], array $select = []): array
    {
        $query = PropertyTable::query()
            ->setFilter(array_merge(['ACTIVE' => true], $filter))
            ->setSelect($select ?: ['*'])
            ->exec();

        return $query->fetchAll();
    }

    /**
     * @param int $iblockId
     * @param array $properties
     * @return Base
     * @throws ArgumentException
     * @throws LoaderException
     * @throws SystemException
     */
    protected static function getEntityByProp(int $iblockId, array $properties): Base
    {
        $entity = OrmHelper::createDynamicBase(
            static::getIBlockSinglePropTableName($iblockId),
            [
                'IBLOCK_ELEMENT_ID' => [
                    'data_type' => 'integer',
                    'primary' => true
                ]
            ]
        );

        $multipleRef = null;
        foreach ($properties as $prop) {
            if ($prop['MULTIPLE'] === 'Y') {
                if (empty($multipleRef)) {
                    $multipleRef = OrmHelper::createDynamicBase(
                        static::getIBlockMultiplePropTableName($iblockId),
                        static::getMultiplePropertyTableMap()
                    );
                }
                static::addMultipleProperty($entity, $multipleRef, $prop);
            } else {
                static::addSingleProperty($entity, $prop);
            }
        }

        return $entity;
    }

    /**
     * @param int $iblockId
     * @return string
     */
    public static function getIBlockSinglePropTableName(int $iblockId): string
    {
        return sprintf('b_iblock_element_prop_s%u', $iblockId);
    }

    /**
     * @param int $iblockId
     * @return string
     */
    public static function getIBlockMultiplePropTableName(int $iblockId): string
    {
        return sprintf('b_iblock_element_prop_m%u', $iblockId);
    }

    /**
     * @return array
     */
    public static function getMultiplePropertyTableMap(): array
    {
        return [
            'ID' => [
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true
            ],
            'IBLOCK_ELEMENT_ID' => [
                'data_type' => 'integer',
                'required' => true
            ],
            'IBLOCK_PROPERTY_ID' => [
                'data_type' => 'integer',
                'required' => true
            ],
            'VALUE' => [
                'data_type' => 'text',
                'required' => true
            ],
            'VALUE_ENUM' => [
                'data_type' => 'integer'
            ],
            'VALUE_NUM' => [
                'data_type' => 'float'
            ],
            'DESCRIPTION' => [
                'data_type' => 'string'
            ]
        ];
    }

    /**
     * @param Base $entity
     * @param Base $ref
     * @param $prop
     * @throws LoaderException
     * @throws ArgumentException
     * @throws SystemException
     */
    protected static function addMultipleProperty(Base $entity, Base $ref, $prop)
    {
        $fieldName = $prop['CODE'];
        $colName = static::getMultiplePropertyColname($prop);
        $refFieldName = sprintf('%s_REF', $prop['CODE']);

        $entity->addField(array(
            'data_type' => $ref,
            'reference' => array(
                '=this.IBLOCK_ELEMENT_ID' => 'ref.IBLOCK_ELEMENT_ID',
                '=ref.IBLOCK_PROPERTY_ID' => new DB\SqlExpression('?i', $prop['ID'])
            )
        ), $refFieldName);

        $entity->addField(array(
            'expression' => array(
                '%s',
                sprintf('%s.%s', $refFieldName, $colName)
            )
        ), $fieldName);

        $entity->addField(
            array(
                'expression' => array(
                    '%s',
                    sprintf('%s.ID', $refFieldName)
                )
            ),
            sprintf(
                '%s_VALUE_ID', $fieldName
            )
        );

        if ($colName !== 'VALUE') {
            $entity->addField(
                array(
                    'expression' => array(
                        '%s',
                        sprintf('%s.VALUE', $refFieldName)
                    )
                ),
                sprintf(
                    '%s_VALUE', $fieldName
                )
            );
        }

        if ($prop['WITH_DESCRIPTION'] === 'Y') {
            $entity->addField(
                array(
                    'expression' => array(
                        '%s',
                        sprintf('%s.DESCRIPTION', $refFieldName)
                    )
                ),
                sprintf(
                    '%s_DESCRIPTION', $fieldName
                )
            );
        }

        $rref = static::getEntityRefFieldByProperty($prop,
            sprintf('%s.%s', $refFieldName, $colName));
        if (!empty($rref)) {
            $entity->addField($rref, sprintf('%s_REFERENCE', $fieldName));
        }
    }

    protected static function getMultiplePropertyColname($prop): string
    {
        switch ($prop['PROPERTY_TYPE']) {
            case PropertyTable::TYPE_ELEMENT:
            case PropertyTable::TYPE_NUMBER:
            case PropertyTable::TYPE_SECTION:
                return 'VALUE_NUM';

            case PropertyTable::TYPE_LIST:
                return 'VALUE_ENUM';

            default:
                return 'VALUE';
        }
    }

    /**
     * @param array $prop
     * @param string $colname
     * @return array
     * @throws ArgumentException
     * @throws LoaderException
     * @throws SystemException
     */
    protected static function getEntityRefFieldByProperty(array $prop, $colname = ''): array
    {
        $result = [];

        if (empty($colname)) {
            $colname = $prop['CODE'];
        }

        switch ($prop['PROPERTY_TYPE']) {
            case PropertyTable::TYPE_ELEMENT:
                $result['data_type'] = '\Bitrix\Iblock\Element';
                $result['reference'] = [
                    sprintf('=this.%s', $colname) => 'ref.ID'
                ];
                break;
            case PropertyTable::TYPE_FILE:
                $result['data_type'] = '\Bitrix\Main\File';
                $result['reference'] = [
                    sprintf('=this.%s', $colname) => 'ref.ID'
                ];
                break;
            case PropertyTable::TYPE_LIST:
                $result['data_type'] = '\Bitrix\Iblock\PropertyEnumeration';
                $result['reference'] = [
                    sprintf('=this.%s', $colname) => 'ref.ID'
                ];
                break;
            case PropertyTable::TYPE_SECTION:
                $result['data_type'] = '\Bitrix\Iblock\Section';
                $result['reference'] = [
                    sprintf('=this.%s', $colname) => 'ref.ID'
                ];
                break;
            case PropertyTable::TYPE_STRING:
                if ($prop['USER_TYPE'] === 'UserID') {
                    $result['data_type'] = '\Bitrix\Main\User';
                    $result['reference'] = [
                        sprintf('=this.%s', $colname) => 'ref.ID'
                    ];
                } elseif ($prop['USER_TYPE'] === 'ElementXmlID') {
                    $result['data_type'] = '\Bitrix\Iblock\Element';
                    $result['reference'] = [
                        sprintf('=this.%s', $colname) => 'ref.XML_ID'
                    ];
                } elseif ($prop['USER_TYPE'] === 'directory') {
                    $curSettings = unserialize($prop['USER_TYPE_SETTINGS']);
                    $hlBlock = new HighLoadBlock($curSettings['TABLE_NAME']);
                    $result['data_type'] = $hlBlock->getDataManager();
                    $result['reference'] = array(
                        sprintf('=this.%s', $colname) => 'ref.UF_XML_ID',
                    );
                }
                break;
        }

        return $result;
    }

    /**
     * @param Base $entity
     * @param $prop
     * @throws ArgumentException
     * @throws LoaderException
     * @throws SystemException
     */
    protected static function addSingleProperty(Base $entity, $prop)
    {
        /**
         * @type string
         */
        $fieldName = $prop['CODE'];
        /**
         * @type string
         */
        $colname = static::getSinglePropertyColname($prop['ID']);

        /**
         * @type array
         */
        $attrs = static::getEntityFieldAttrsByProperty($prop);
        $attrs['column_name'] = $colname;
        $entity->addField($attrs, $fieldName);

        $ref = static::getEntityRefFieldByProperty($prop);
        if (!empty($ref)) {
            $entity->addField($ref, sprintf('%s_REFERENCE', $fieldName));
        }

        if ($prop['WITH_DESCRIPTION'] === 'Y') {
            $entity->addField(
                [
                    'data_type' => 'string',
                    'column_name' => static::getSingleDescriptionColname($prop['ID'])
                ],
                sprintf('%s_DESCRIPTION', $fieldName)
            );
        }
    }

    protected static function getSinglePropertyColname($propId): string
    {
        return sprintf('PROPERTY_%u', $propId);
    }

    protected static function getEntityFieldAttrsByProperty(array $prop): array
    {
        $result = [];

        switch ($prop['PROPERTY_TYPE']) {
            case PropertyTable::TYPE_ELEMENT:
            case PropertyTable::TYPE_FILE:
            case PropertyTable::TYPE_LIST:
            case PropertyTable::TYPE_SECTION:
                $result['data_type'] = 'integer';
                break;
            case PropertyTable::TYPE_NUMBER:
                $result['data_type'] = 'float';
                break;
            case PropertyTable::TYPE_STRING:
                if ($prop['USER_TYPE'] === 'HTML') {
                    $result['data_type'] = 'text';
                    $result['serialized'] = true;
                } else {
                    $result['data_type'] = 'string';
                }
                break;
        }

        return $result;
    }

    protected static function getSingleDescriptionColname($propId): string
    {
        return sprintf('DESCRIPTION_%u', $propId);
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: dnkolosov
 * Date: 16.03.2018
 * Time: 16:24
 * @author Denis Kolosov <kdnn@mail.ru>
 */

namespace SB\Bitrix\Model;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\ExpressionField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\TextField;
use SB\Util\Entity\PropertyValue;

/**
 * класс описывающий таблицу немножественного свойства инфоблока
 *
 * Class PropertySingleValueTable
 * @package SB\Bitrix\Model
 * @see PropertyValue
 */
abstract class PropertySingleValueTable extends DataManager
{
    protected static $iBlockId;
    protected static $propertyId;

    public static function getTableName()
    {
        return 'b_iblock_element_prop_s' . static::$iBlockId;
    }

    /**
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     */
    public static function getMap()
    {
        return [
            'IBLOCK_ELEMENT_ID' => new IntegerField('IBLOCK_ELEMENT_ID', array(
                'primary' => true
            )),

            'IBLOCK_PROPERTY_ID' => new ExpressionField('IBLOCK_PROPERTY_ID', static::$propertyId),


            'PROPERTY' => new ReferenceField(
                'PROPERTY',
                '\Bitrix\Iblock\Property',
                array('=this.IBLOCK_PROPERTY_ID' => 'ref.ID'),
                array('join_type' => 'INNER')
            ),
            'ELEMENT' => new ReferenceField(
                'ELEMENT',
                '\Bitrix\Iblock\Element',
                array('=this.IBLOCK_ELEMENT_ID' => 'ref.ID')
            ),

            'VALUE' => new TextField('PROPERTY_' . static::$propertyId),

            'VALUE_ARRAY' => new ExpressionField('VALUE_ARRAY',
                'GROUP_CONCAT(DISTINCT %s)',
                'VALUE',
                array(
                    'fetch_data_modification' => function () {
                        return array(
                            function ($value) {
                                return explode(',', $value);
                            }
                        );
                    })
            )
        ];
    }
}
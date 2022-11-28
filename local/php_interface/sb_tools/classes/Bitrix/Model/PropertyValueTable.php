<?php
/**
 * Created by PhpStorm.
 * User: dnkolosov
 * Date: 16.03.2018
 * Time: 16:24
 * @author Denis Kolosov <kdnn@mail.ru>
 */

namespace SB\Bitrix\Model;

use Bitrix\Iblock\PropertyTable;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\ExpressionField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\TextField;

/**
 * класс описывающий таблицу свойства инфоблока в общей таблице
 *
 * Class PropertyValueTable
 * @package SB\Bitrix\Model
 */
class PropertyValueTable extends DataManager
{
    public static function getTableName()
    {
        return 'b_iblock_element_property';
    }

    /**
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     */
    public static function getMap()
    {
        return [
            'ID' => new IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true,
            )),
            'IBLOCK_PROPERTY_ID' => new IntegerField('IBLOCK_PROPERTY_ID'),
            'IBLOCK_ELEMENT_ID' => new IntegerField('IBLOCK_ELEMENT_ID'),
            'VALUE_STRING' => new TextField('VALUE'),
            'VALUE_ENUM' => new TextField('VALUE_ENUM'),
            'VALUE_NUM' => new IntegerField('VALUE_NUM'),
            'DESCRIPTION' => new TextField('DESCRIPTION'),

            'PROPERTY' => new ReferenceField(
                'PROPERTY',
                '\Bitrix\Iblock\Property',
                array('=this.IBLOCK_PROPERTY_ID' => 'ref.ID')
            ),

            'ELEMENT' => new ReferenceField(
                'ELEMENT',
                '\Bitrix\Iblock\Element',
                array('=this.IBLOCK_ELEMENT_ID' => 'ref.ID')
            ),

            'VALUE' => new ExpressionField('VALUE',
                '(IF(%s = "' . PropertyTable::TYPE_NUMBER . '", %s, IF(%s = "' . PropertyTable::TYPE_LIST . '", %s, %s)))',
                [
                    'PROPERTY.PROPERTY_TYPE',
                    'VALUE_NUM',
                    'PROPERTY.PROPERTY_TYPE',
                    'VALUE_ENUM',
                    'VALUE_STRING'
                ]
            ),

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
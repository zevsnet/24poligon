<?php

namespace Poligon\Core\Pickup;


use Bitrix\Sale\Location\LocationTable;
use Bitrix\Sale\Order;
use SB\Site\Order\IOrderable;

abstract class PickupList
{
    private $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    final public function getOrder()
    {
        return $this->order;
    }

    abstract public function getPickupList();

    final protected function getCityValue()
    {
        foreach ($this->getOrder()->getPropertyCollection() as $propertyValue) {
            if ($propertyValue->getPropertyObject()->getField('CODE') === 'LOCATION') {
                $codeLocation = $propertyValue->getField('VALUE');
                $arCity = LocationTable::getRow([
                    'filter' => [
                        '=CODE' => $codeLocation,
                        '=NAME.LANGUAGE_ID' => 'ru',
                    ],
                    'select' => [
                        'LOCATION_NAME' => 'NAME.NAME',
                        'ID',
                        'PARENT_ID'
                    ]
                ]);

                if (!empty($arCity['LOCATION_NAME'])) {
                    return $arCity['LOCATION_NAME'];
                }
            }
        }
        return null;
    }

    final protected function getFullAddress(): array
    {
        $list = [];
        foreach ($this->getOrder()->getPropertyCollection() as $propertyValue) {

            if ($propertyValue->getPropertyObject()->getField('CODE') === 'LOCATION') {

                $codeLocation = $propertyValue->getField('VALUE');
                $arCity = LocationTable::getRow([
                    'filter' => [
                        '=CODE' => $codeLocation,
                        '=NAME.LANGUAGE_ID' => 'ru',
                    ],
                    'select' => [
                        'LOCATION_NAME' => 'NAME.NAME',
                        'ID',
                        'PARENT_ID'
                    ]
                ]);
                $arCityParentID = LocationTable::getRow([
                    'filter' => [
                        '=ID' => $arCity['PARENT_ID'],
                        '=NAME.LANGUAGE_ID' => 'ru',
                    ],
                    'select' => [
                        'LOCATION_NAME' => 'NAME.NAME',
                        'ID',
                        'PARENT_ID'
                    ]
                ]);

                $list['LOCATION']['PARENT_ID'] = $arCityParentID['LOCATION_NAME'];
                $list['LOCATION']['CHILDREN_ID'] = $arCity['LOCATION_NAME'];
            }
        }

        return $list;
    }
}

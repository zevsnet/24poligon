<?php

namespace Poligon\Core\Pickup;


use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Loader;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Sale\Location\LocationTable;
use Ipolh\DPD\DB\Terminal\Table as TerminalTable;

class Dpd extends PickupList
{

    public function getPickupList()
    {
        if (!Loader::includeModule('ipol.dpd')) {
            throw new \Exception('Модуль dpd не установлен');
        }

        $list = [];

        if ($itemsPickUp = $this->getCityList()) {
            $list = $this->formatList($itemsPickUp);
        }

        return $list;
    }

    private function getOrderSum()
    {
        return $this->getOrder()->getPrice();
    }

    private function getCityList()
    {
        return TerminalTable::getList([
            'filter' => [
                'LOCATION_ENTITY.CODE' => $this->getOrder()->getPropertyCollection()->getDeliveryLocation()->getValue(),
                '=NPP_AVAILABLE' => 'Y',
                '>NPP_AMOUNT' => $this->getOrderSum()
            ],
            'select' => [
                'CODE',
                'NAME',
                'ADDRESS_FULL',
                'LATITUDE',
                'LONGITUDE',
                'PARCEL_SHOP_TYPE'
            ],
            'runtime' => [
                new ReferenceField(
                    'LOCATION_ENTITY',
                    LocationTable::class,
                    Join::on('this.LOCATION_ID', 'ref.ID')
                )
            ]
        ])->fetchAll();
    }

    private function formatList($list)
    {
        $i      = 0;
        $result = [];
        foreach ($list as $key => $e) {
            $name = $e['NAME'];
            if ($e['PARCEL_SHOP_TYPE'] === 'ПВП') {
                $name .= ' (Пункт выдачи)';
            } else if ($e['PARCEL_SHOP_TYPE'] === 'П') {
                $name .= ' (Постамат)';
            }
            $result[] = [
                'id' => $e['CODE'],
                'title' => $name,
                'description' => $e['ADDRESS_FULL'],
                'lat' => (float)($e['LATITUDE']),
                'lon' => (float)($e['LONGITUDE']),
                'selected' => $i++ === 0
            ];
        }
        return $result;
    }
}

<?php


namespace Poligon\Core\Pickup;


use Bitrix\Main\Application;
use Bitrix\Main\IO\File;
use Bitrix\Main\Web\Json;
use Ipol\Fivepost\Bitrix\Handler\LocationsDelivery;
use Ipol\Fivepost\Core\Delivery\CargoItem;
use Ipol\Fivepost\Core\Entity\Money;
use Ipol\Fivepost\LocationsHandler;
use Ipol\Fivepost\LocationsTable;
use Ipol\Fivepost\PointsHandler;
use Ipol\Fivepost\PointsTable;

class Fivepost extends PickupList
{

    public function getPickupList()
    {
        $list = [];
        if ($city = $this->getCityValue()) {
            if ($itemsPickUp = $this->getListByCity($city)) {

                $list = $this->formatList($itemsPickUp->getData());
            }
        }

        return $list;
    }

    private function getListByCity($city)
    {
        foreach ($this->getOrder()->getPropertyCollection() as $propertyValue) {
            if ($propertyValue->getPropertyObject()->getField('CODE') === 'LOCATION') {
                $code = $propertyValue->getField('VALUE');
            }
        }
        $location = LocationsTable::getList([
            'select' => ['ID', 'LOCALITY_FIAS_CODE', 'BITRIX_CODE'],
            'filter' => ['=BITRIX_CODE' => $code],
            'limit' => 1, // Just for sure
        ])->fetch();
        $obPoints = null;
        if (!empty($location)) {
            $obPoints = PointsHandler::getPoints(false, false, $location['BITRIX_CODE']);
        }

        return $obPoints;
    }

    private function formatList($list)
    {
        $i = 0;
        $result = [];
        foreach ($list['POINTS'] as $key => $e) {
            if ($e['ADDRESS_LAT'] && $e['ADDRESS_LNG']) {

                $result[] = [
                    'id' => $key,
                    'title' => $e['NAME'],
                    'description' => $e['FULL_ADDRESS'],
                    'description2' => $e['ADDITIONAL'],
                    'lat' => (float)($e['ADDRESS_LAT']),
                    'lon' => (float)($e['ADDRESS_LNG']),
                    'selected' => $i++ === 0
                ];
            }
        }

        return $result;
    }
}

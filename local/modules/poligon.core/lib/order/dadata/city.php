<?php

namespace Poligon\Core\Order\DaData;

use Bitrix\Main\Entity\ExpressionField;
use Bitrix\Sale\Location\Admin\LocationHelper;
use Bitrix\Sale\Location\LocationTable;
use Bitrix\Sale\Location\Search\Finder;


class City extends DaData
{
    public function execute()
    {
        $query = $this->getByKey('query');
        $params = [
            'select' =>
                [
                    'CODE',
                    'TYPE_ID',
                    'VALUE' => 'ID',
                    'CHILD_CNT',
                    'LEFT_MARGIN',
                    'RIGHT_MARGIN',
                    'ID',
                    'LOCATION_NAME' => 'NAME.NAME'
                ],
            'filter' => [
                'PHRASE' => $query,
                '=NAME.LANGUAGE_ID' => 'ru',
                '=TYPE.CODE' => ['CITY', 'VILLAGE'],
//                '=COUNTRY_ID' => $this->getByKey('countryId'),
            ],
            'limit' => 5,
            'offset' => 0,
        ];
        $res = Finder::find($params)->fetchAll();
        foreach ($res as &$location) {
            // битрикс сказал, сделать так
            // hack to repair ORM
            if (!isset($location['ID']))
                $location['ID'] = $location['VALUE'];
            $location['DISPLAY'] = LocationHelper::getLocationStringByCode($location['CODE'], ['INVERSE' => true]);
            $location['ZIP'] = LocationHelper::getZipByLocation($location['CODE'])->fetch()['XML_ID'];
        }

        return $res;
    }
}

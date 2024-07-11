<?php


namespace Poligon\Core\Order\DaData;


class Street extends DaData
{
    public function execute()
    {
        $query      = $this->getByKey('query');
        $countryIso = 'RU';
        $city       = $this->getByKey('city');
        $data = [
            'query' => $query,
//            'from_bound' => ['value' => 'street'],
//            'to_bound' => ['value' => 'street'],
            'locations' => [
                [
                    'country_iso_code' => $countryIso,
                    'city' => $city
                ],
                [
                    'country_iso_code' => $countryIso,
                    'settlement' => $city
                ]
            ],
            "restrict_value" => true
        ];
        $res = $this->send($data)['suggestions'];;

        return $res;
    }
}

<?php


namespace Poligon\Core\Order\DaData;


class House extends DaData
{
    public function execute()
    {
        $query      = $this->getByKey('query');
        $countryIso = $this->getByKey('countryIso');
        $city       = $this->getByKey('city');
        $street       = $this->getByKey('street');

        $data = [
            'query' => $query,
            'from_bound' => ['value' => 'house'],
            'to_bound' => ['value' => 'house'],
            'locations' => [
                [
                    'country_iso_code' => $countryIso,
                    'city' => $city,
                    'street' => $street
                ],
                [
                    'country_iso_code' => $countryIso,
                    'settlement' => $city,
                    'street' => $street
                ]
            ],
            "restrict_value" => true
        ];

        return $this->send($data)['suggestions'];
    }
}

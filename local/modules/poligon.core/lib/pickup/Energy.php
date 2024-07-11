<?php


namespace Poligon\Core\Pickup;

use SB\Site\Provider\Energy as EnergyManager;

class Energy extends PickupList
{
    public function getPickupList()
    {
        $list = [];

        if ($city = $this->getCityValue()) {
            if ($itemsPickUp = $this->getListByCity($city)) {
                $list = $this->formatList($itemsPickUp);
            }
        }

        return $list;
    }

    private function getListByCity($city)
    {
        $configuration = new EnergyManager\Configuration();
        $manager       = new EnergyManager\Manager\City($configuration);
        $list          = $manager->getList();
        $list          = $this->clearIsoCounty($list);

        foreach ($list as $item) {
            if (strtolower($item['name']) == strtolower($city)) {
                if(is_array($item['warehouses'])){
                    return array_filter($item['warehouses'], function ($item){
                        return $item['isIssuer'] === 1;
                    });
                }
                return [];
            }
        }

        return null;
    }

    private function clearIsoCounty($list)
    {
        $pattern = "/\,\s?((KZ)|(BY))/i";

        foreach ($list as &$item) {
            $item['name'] = preg_replace($pattern, "", $item['name']);
        }
        unset($item);

        return $list;
    }

    private function formatList($list)
    {
        $i = 0;
        return array_map(function ($e) use (&$i) {
            return [
                'id' => strval($e['id']),
                'title' => $e['title'],
                'description' => $e['address'],
                'zip' => $e['zipcode'],
                'lat' => $e['latitude'],
                'lon' => $e['longitude'],
                'selected' => $i++ === 0
            ];
        }, $list);
    }
}

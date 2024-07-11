<?php

namespace Poligon\Core\Pickup;


use Bitrix\Main\Application;
use Bitrix\Main\IO\File;
use Bitrix\Main\Web\Json;

class Sdek extends PickupList
{
    private $pathPickupList = '/bitrix/js/ipol.sdek/list.json';

    public function getPickupList()
    {
        $list = [];
        //ORDER_PROP_60 - todo: нужо получить  ключ через сойства и этосимвольный код CITY
        $city = $_REQUEST['order']['ORDER_PROP_60']?:$this->getCityValue();
        //$city = $this->getCityValue();
        if ($city) {
            if ($itemsPickUp = $this->getListByCity($city)) {
                $list = $this->formatList($itemsPickUp);
            }
        }

        return $list;
    }

    private function getListByCity($city)
    {
        $fullPath = Application::getDocumentRoot() . $this->pathPickupList;
        $file = new File($fullPath);
        $json = $file->getContents();
        $list = Json::decode($json);
        $pickupList = null;
        $cityId = null;

        foreach ($list['CITY'] as $key => $item){
            if($item == $city){
                $cityId = $key;
                break;
            }
        }

        if(isset($list['PVZ'][$cityId])){
            $pickupList = $list['PVZ'][$cityId];
        }

        return $pickupList;
    }

    private function formatList($list)
    {
        $i = 0;
        $result = [];
        foreach ($list as $key => $e){
            if($e['cY'] && $e['cX'])
            $result[] = [
                'id' => $key,
                'title' => $e['Name'],
                'description' => $e['Address'],
                'lat' => (float) ($e['cY']),
                'lon' => (float) ($e['cX']),
                'selected' => $i++ === 0
            ];
        }

        return $result;
    }
}

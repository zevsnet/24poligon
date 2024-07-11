<?php


namespace Poligon\Core\Pickup;

use Bitrix\Main\Application;
use Bitrix\Main\IO\File;
use Bitrix\Main\Web\Json;
use Ipol\Ozon\Option;
use Ipol\Ozon\Ozon\OzonApplication;

class Ozon extends PickupList {

    public function getPickupList() {
        $list = [];
        if ($cityName = $this->getCityValue()) {
            if ($itemsPickUp = $this->getListByCity($cityName)) {
                $list = $this->formatList($itemsPickUp);
            }
        }
        return $list;
    }

    private function getListByCity($cityName) {
        $client_id = \COption::GetOptionString('ipol.ozon', 'clientId');
        $secret_key = \COption::GetOptionString('ipol.ozon', 'clientSecret');
        $OZON_app = new OzonApplication($client_id, $secret_key);
        $deliv_list = $OZON_app->deliveryVariants($cityName);
        if($deliv_list->isSuccess()) {
            return $deliv_list->getResponse()->getData()->getFields();
        }
    }

    private function formatList($itemList) {
        $list = [];
        foreach ($itemList as $index => $item) {
            $selectet = null;
            if($index == 0) $selectet = 1;
            $list[] = [
                'id' => (string)$item['id'],
                'title' => $item['placement'],
                'description' => $item['name'],
                'lat' => (float) $item['lat'],
                'lon' => (float) $item['long'],
                'selected' => $selectet
            ];
        }
        return $list;
    }

}
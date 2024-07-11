<?php

namespace Poligon\Core\Pickup;

use Bitrix\Main\Application;
use Bitrix\Main\IO\File;
use Bitrix\Main\Web\Json;

class RussianPost extends PickupList {

    public function getPickupList() {
        $list = [];
        if ($cityName = $this->getCityValue()) {
            if ($itemsPickUp = $this->getListByCity($cityName)) {
                $list = $itemsPickUp;
            }
        }
        return $list;
    }

    private function getCityCoordinates($cityName, $yandex) {

        $currentCityObjectData = null;

        $RussianPostCordinates = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/sb_site/classes/Order/Pickup/RussianPostCordinates.txt");
        if($RussianPostCordinates) {
            $RussianPostCordinates = json_decode($RussianPostCordinates, true);
        } else {
            $RussianPostCordinates = [];
        }

        /*if(isset($RussianPostCordinates[$cityName])) {
            $currentCityObjectData = $RussianPostCordinates[$cityName];
        } else {*/
        $ch = curl_init('https://geocode-maps.yandex.ru/1.x/?format=json&apikey=48fcac3e-2bec-4f0c-bb20-6d585106d044&geocode='.urlencode($yandex));
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_GET, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $yandexGeoObject = curl_exec($ch);
        $yandexGeoObject = json_decode($yandexGeoObject, true);
        $yandexGeoObject = $yandexGeoObject['response']['GeoObjectCollection']['featureMember'];
        curl_close($ch);

        foreach ($yandexGeoObject as $itemGeoObject) {
            
            if($itemGeoObject['GeoObject']['name'] == $cityName) {
                $currentCityObjectData = $itemGeoObject['GeoObject']['boundedBy']['Envelope'];
                $RussianPostCordinates[$cityName] = $currentCityObjectData;
                $RussianPostCordinates = json_encode($RussianPostCordinates);
                /*$file_RussianPostCordinates = $_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/sb_site/classes/Order/Pickup/RussianPostCordinates.txt";
                $fw = fopen($file_RussianPostCordinates, "w+");
                fwrite($fw, $RussianPostCordinates);
                fclose($fw);*/
                break;
            } else {

                $arrName = explode(' ', str_replace('ё', 'е', $itemGeoObject['GeoObject']['name']));
                $arrCurName = explode(' ', str_replace('ё',  'е', $cityName));

                if(is_array($arrName) && is_array($arrCurName)) {
                    if(count($arrName) == count($arrCurName)) {
                        if(count($arrName) == 2) {
                            if($arrName[0] == $arrCurName[1] && $arrName[1] == $arrCurName[0]) {
                                $currentCityObjectData = $itemGeoObject['GeoObject']['boundedBy']['Envelope'];
                                $RussianPostCordinates[$cityName] = $currentCityObjectData;
                                $RussianPostCordinates = json_encode($RussianPostCordinates);
                                /*$file_RussianPostCordinates = $_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/sb_site/classes/Order/Pickup/RussianPostCordinates.txt";
                                $fw = fopen($file_RussianPostCordinates, "w+");
                                fwrite($fw, $RussianPostCordinates);
                                fclose($fw);*/
                                break;
                            }
                        } elseif (count($arrName) > 2) {
                            $resultSearch = [];
                            foreach ($arrCurName as $nameItem) {
                                if(in_array($nameItem, $arrName)) {
                                    $resultSearch[] = 1;
                                } else {
                                    $resultSearch[] = 0;
                                }
                            }
                            if(!in_array('0', $resultSearch)) {
                                $currentCityObjectData = $itemGeoObject['GeoObject']['boundedBy']['Envelope'];
                                $RussianPostCordinates[$cityName] = $currentCityObjectData;
                                $RussianPostCordinates = json_encode($RussianPostCordinates);
                                /*$file_RussianPostCordinates = $_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/sb_site/classes/Order/Pickup/RussianPostCordinates.txt";
                                $fw = fopen($file_RussianPostCordinates, "w+");
                                fwrite($fw, $RussianPostCordinates);
                                fclose($fw);*/
                                break;
                            }
                        }
                    }
                }
            }
        }
        //}

        return $currentCityObjectData;
    }

    private function getListByCity($cityName) {
        $arMapPoint = $this->getCityCoordinates($cityName, $this->getFullAddress()['LOCATION']['PARENT_ID'].'+'.$this->getFullAddress()['LOCATION']['CHILDREN_ID']);
        $ValidPVZlist = [];

        if($arMapPoint) {
            $zoom_index = 1;

            $arMapPoint['lowerCorner'] = explode(' ', $arMapPoint['lowerCorner']);
            $arMapPoint['upperCorner'] = explode(' ', $arMapPoint['upperCorner']);

            while ($zoom_index < 6) {

                $x_diff = $arMapPoint['upperCorner'][0] - $arMapPoint['lowerCorner'][0];
                $y_diff = $arMapPoint['upperCorner'][1] - $arMapPoint['lowerCorner'][1];
                $x_diff = $x_diff * $zoom_index;
                $y_diff = $y_diff * $zoom_index;
                $x_diff_change = $x_diff / 2;
                $y_diff_change = $y_diff / 2;
                $x_center = ($arMapPoint['upperCorner'][0] + $arMapPoint['lowerCorner'][0]) / 2;
                $y_center = ($arMapPoint['upperCorner'][1] + $arMapPoint['lowerCorner'][1]) / 2;
                $arMapPoint['upperCorner'][0] = $x_center + $x_diff_change;
                $arMapPoint['lowerCorner'][0] = $x_center - $x_diff_change;
                $arMapPoint['upperCorner'][1] = $y_center + $y_diff_change;
                $arMapPoint['lowerCorner'][1] = $y_center - $y_diff_change;

                $ch = curl_init('https://widget.pochta.ru/api/pvz?settings_id=16931&pvzType[]=russian_post&pageSize=200&page=1&currentTopRightPoint[]='.$arMapPoint['lowerCorner'][0].'&currentTopRightPoint[]='.$arMapPoint['lowerCorner'][1].'&currentBottomLeftPoint[]='.$arMapPoint['upperCorner'][0].'&currentBottomLeftPoint[]='.$arMapPoint['upperCorner'][1].'&');
                curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
                curl_setopt($ch, CURLOPT_GET, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $PVZlist = curl_exec($ch);
                $PVZlist = json_decode($PVZlist, true);
                curl_close($ch);

                if(count($PVZlist['data']) > 0) {
                    foreach ($PVZlist['data'] as $PVZindex => $pvzItem) {
                        $selected = 0;
                        if($PVZindex == 0) $selected = 1;
                        $ValidPVZlist[] = [
                            'id' => (string)$pvzItem['id'],
                            'title' => $pvzItem['address']['place'].' '.$pvzItem['address']['street'].' '.$pvzItem['address']['house'],
                            'description' => $pvzItem['deliveryPointIndex'],
                            'lat' => $pvzItem['geo']['coordinates'][1],
                            'lon' => $pvzItem['geo']['coordinates'][0],
                            'selected' => $selected
                        ];
                    }
                    return $ValidPVZlist;
                }
                $zoom_index++;
            }
            if(count($ValidPVZlist) == 0) {
                return false;
            }

        } return false;
    }
}
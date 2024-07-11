<?php


namespace Poligon\Core\Pickup;
use Bitrix\Main\Text\Encoding;
use Bitrix\Main\Web\Json;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Sale\Order;
use Throwable;
use Bitrix\Main\Data\Cache;
use Bitrix\Main\Web\HttpClient;

class Boxberry extends PickupList
{
    private static $cache;
    private $boxberry;
    private static $httpClient;
    public function __construct(Order $order)
    {
        parent::__construct($order);

        $this->boxberry = new \CBoxberry();
        $this->boxberry->initApi();
    }

    public function getPickupList()
    {
        if (!Loader::includeModule('up.boxberrydelivery')) {
            throw new \Exception('Модуль boxberry не установлен');
        }

        $list = [];

        if ($cityName = $this->getCityValue()) {
            $cityListTmp = $this->boxberry->listCitiesFull();
//            $cityList = $this->getCityList();//Получаем все города
            if ($itemsPickUp = $this->getListByCity($cityListTmp, $cityName)) {
                $list = $this->formatList($itemsPickUp);
            }
        }

        return $list;
    }

    private function getCityList()
    {
        //$self = $this;
//        return Cache::getInstance()->initialize(['boxberryCityList'], function () use ($self) {
            return $this->boxberry->listCitiesFull();
//        });
    }

    private function getListByCity($cityList, $cityName){
        $self = $this;
        $city = null;
        foreach ($cityList as $item){
            if($item['Name'] == $cityName && (bool) ($item['PickupPoint']) === true){
                $city = $item;
                break;
            }
        }

        if($city === null){
            return null;
        }


        $apiToken = trim(Option::get("up.boxberrydelivery", 'API_TOKEN'));
        $apiUrl = trim(Option::get("up.boxberrydelivery", 'API_URL'));
        $params = [
            'token' => $apiToken,
            'method' => 'ListPoints',
            'CityCode' => $city['Code'],
            'prepaid' => 1,
        ];

        return self::makeHttpRequest($apiUrl, 'GET', $params);

//        return Cache::getInstance()->initialize(['boxberryPickupList', $city['Code']], function () use ($self, $city) {
//            return $self->boxberry->method_exec('ListPoints', [
//                'CityCode=' . $city['Code'] . '&prepaid=1'
//            ]);
//        });
    }
    private static function makeHttpRequest($url, $method = 'POST', $params = [], $headers = [], $cacheTime = 86400)
    {
        $cacheKey = md5($url . $method . serialize($params) . serialize($headers));

        $params = self::convertEncoding($params);

        $cache = self::getCache();

        if ($cache->startDataCache($cacheTime, $cacheKey)) {

            $http = self::getHttpClient();

            if ($method === 'POST') {

                $http->clearHeaders();

                $http->setHeader('Content-Type', 'application/x-www-form-urlencoded');

                foreach ($headers as $headerName => $headerValue) {
                    $http->setHeader($headerName, $headerValue);
                }

                if (is_array($params)) {
                    $postData = http_build_query($params, '', '&');
                } else {
                    $postData = $params;
                }

                $postData = self::convertEncoding($postData);
                $response = $http->post($url, $postData);
            } elseif ($method === 'GET') {
                $url .= '?' . http_build_query($params, '', '&');
                $response = $http->get($url);
            } else {
                return false;
            }

            if ($response) {
                $responseBody = $http->getResult();
                $statusCode = $http->getStatus();

                if ($statusCode === 200) {
                    $cache->endDataCache($responseBody);
                } else {
                    self::logRequest($url, $method, $params, $headers, $responseBody);

                    return false;
                }
            } else {
                return false;
            }
        } else {
            $responseBody = $cache->getVars();
        }

        //self::logRequest($url, $method, $params, $headers, $responseBody);

        try {
            return Json::decode($responseBody);
        } catch (Throwable $e) {
            return false;
        }
    }
    private static function convertEncoding($data, $targetCharset = 'UTF-8') {
        if (is_array($data)) {
            $convertedData = [];
            foreach ($data as $key => $value) {
                $convertedData[$key] = self::convertEncoding($value, $targetCharset);
            }
        } else {
            $sourceCharset = Encoding::detectUtf8($data) ? 'UTF-8' : 'CP1251';
            $convertedData = Encoding::convertEncoding($data, $sourceCharset, $targetCharset);
        }

        return $convertedData;
    }
    private static function getCache()
    {
        if (!isset(self::$cache)) {
            self::$cache = Cache::createInstance();
        }

        return self::$cache;
    }
    private function formatList($list)
    {
        $i = 0;
        $result = [];
        foreach ($list as $key => $e){
            $coords = explode(',', $e['GPS']);
            $result[] = [
                'id' => $e['Code'],
                'title' => $e['AddressReduce'],
                'description' => $e['Address'],
                'lat' => (float) ($coords[0]),
                'lon' => (float) ($coords[1]),
                'selected' => $i++ === 0
            ];
        }

        return $result;
    }
    private static function getHttpClient()
    {
        if (!isset(self::$httpClient)) {
            self::$httpClient = new HttpClient();
        }

        return self::$httpClient;
    }

}

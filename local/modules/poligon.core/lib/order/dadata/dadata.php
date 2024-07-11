<?php

namespace Poligon\Core\Order\DaData;
use Bitrix\Main\Web\HttpClient;
use Bitrix\Main\Web\Json;


abstract class DaData
{
    private $data;
    private $key = 'd5a81f2f8be901c03e4f595b2bf9c2d7dc296495';
    private $url = 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address';

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    protected function getByKey($key)
    {
        return $this->data[$key];
    }

    protected function getData(){
        return $this->data;
    }


    protected function send($data)
    {
        $httpClient = new HttpClient();
        $httpClient->setHeader('Authorization', 'Token ' . $this->key);
        $httpClient->setHeader('Content-Type', 'application/json');
        $httpClient->setHeader('Accept', 'application/json');
        $response = $httpClient->post($this->getUrl(), Json::encode($data));
        return Json::decode($response);
    }

    protected function getUrl(){
        return $this->url;
    }


    abstract public function execute();
}

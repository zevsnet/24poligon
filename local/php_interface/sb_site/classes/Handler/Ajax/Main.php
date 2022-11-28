<?php

namespace SB\Site\Handler\Ajax;

use Bitrix\Main\HttpRequest;
use Bitrix\Main\Loader;
use Bitrix\Sale\Fuser;
use Bitrix\Sale\Location\Admin\LocationHelper;
use Bitrix\Sale\Location\LocationTable;
use CIBlockElement;
use SB\Handler\Ajax;

use SB\Site\Bitrix\SBElement;
use SB\Site\Bitrix\SBIblock;
use SB\Site\Dadata\LocationConverter;
use SB\Site\Variables;
use SB\Tools\Log;

class Main extends Ajax
{

//Прописан но нельзя использовать
    public function bitrixAddress()
    {
        $query = $_REQUEST['query'];
        $country = $_REQUEST['country'] ?: ['id' => 24];

        if (!\is_string($query)) {
            return;
        }

        $locationConverter = new LocationConverter();
        $this->getResult()->addData('suggestions', $locationConverter->getBitrixSuggestions($query, $country, 'city'));

    }

    public function bitrixCity()
    {
        $query = $_REQUEST['query'];
        $country = $_REQUEST['country'] ?: ['id' => 24];

        if (!\is_string($query)) {
            return;
        }

        $locationConverter = new LocationConverter();
        $this->getResult()->addData('suggestions', $locationConverter->getBitrixSuggestions($query, $country, 'city'));

    }

    public function zip2city()
    {
        $query = $_REQUEST['query'];
        if (!\is_string($query)) {
            return;
        }

        $locationConverter = new LocationConverter();
        $this->getResult()->addData('suggestions', $locationConverter->getBitrixSuggestions($query));

    }

//Прописан но нельзя использовать
    public function daDataAddress()
    {
        $obDaData = new Dadata\SuggestClient(Variables::DADATA_TOKEN, Variables::DADATA_SECTER_KEY);
        $query = $_REQUEST['query'];
        $locations = [$_REQUEST['locations']];
        $this->addResult(false, $obDaData->getAddress($query, $locations));
    }

//Прописан но нельзя использовать
    public function daDataFio()
    {
        $obDaData = new Dadata\SuggestClient(Variables::DADATA_TOKEN, Variables::DADATA_SECTER_KEY);
        $query = $_REQUEST['query'];
        $this->addResult(false, $obDaData->getFIO($query));
    }

//Прописан но нельзя использовать
    public function daDataEmail()
    {
        $obDaData = new Dadata\SuggestClient(Variables::DADATA_TOKEN, Variables::DADATA_SECTER_KEY);
        $query = $_REQUEST['query'];
        $this->addResult(false, $obDaData->getEmail($query));
    }

//Прописан но нельзя использовать
    public function daDataParty()
    {
        $obDaData = new Dadata\SuggestClient(Variables::DADATA_TOKEN, Variables::DADATA_SECTER_KEY);
        $query = $_REQUEST['query'];
        $this->addResult(false, $obDaData->getParty($query));
    }

//Прописан но нельзя использовать
    public function daDataBank()
    {
        $obDaData = new Dadata\SuggestClient(Variables::DADATA_TOKEN, Variables::DADATA_SECTER_KEY);
        $query = $_REQUEST['query'];
        $this->addResult(false, $obDaData->getBank($query));
    }

    public function getLocationNameByCode()
    {
        Loader::includeModule('sale');

        $parameters = [
            'select' => [
                'CODE',
                'LOCATION_NAME' => 'NAME.NAME',
            ],
            'filter' => [
                '=CODE' => $_REQUEST['code'],
                '=NAME.LANGUAGE_ID' => 'ru',
            ],
            'limit' => 1
        ];

        $location = LocationTable::getRow($parameters);
        $location['DISPLAY'] = LocationHelper::getLocationStringByCode($_REQUEST['code']);

        $this->getResult()->addData(false, $location);
    }

    public function getPVZ2City()
    {
        $IBLOCK_ID = SBIblock::getIblockId('aspro_optimus_shops');
        $arElement = SBElement::getElement(["IBLOCK_ID" => $IBLOCK_ID], ['*']);
        $placemarks = array();
        foreach ($arElement as $item) {
            $LatLan = explode(',', $item['PROP']['MAP']['VALUE']);
            if ($LatLan) {
                $item['LATITUDE'] += $LatLan[0];
                $item['LONGITUDE'] += $LatLan[1];
            }


            $item['ADDRESS_FULL'] = $item['PROP']['ADDRESS']['VALUE'];

            $item['ADDRESS_DESCR'] .= $item['PROP']['PHONE']['VALUE'][0] . "<br/>";
            $item['ADDRESS_DESCR'] .= $item['PROP']['EMAIL']['VALUE'] . " <br/>";
            $item['ADDRESS_DESCR'] .= $item['PROP']['SCHEDULE']['VALUE'][0] . "<br/>";

            $item['ZIP'] .= $item['PROP']['ZIP']['VALUE']?:'660000';


            ob_start();
            include($_SERVER['DOCUMENT_ROOT'] . '/local/placemark.php');
            $balloonContent = str_replace(PHP_EOL, '', ob_get_clean());

            $placemarks[] = array(
                'CODE' => $item['ID'],
                'ID' => $item['ID'],
                'TITLE' => htmlspecialchars_decode($item['NAME'] . ', ' . $item['PROP']['ADDRESS']['VALUE']),
                'TEXT' => $balloonContent,
                'LAT' => +$item['LATITUDE'],
                'LON' => +$item['LONGITUDE'],
                'ADDR' => $item['ADDRESS_FULL'],
                'ZIP' => $item['ZIP'],
            );
        }
        if ($placemarks) {
            $this->getResult()->addData('CITY', $placemarks);
        } else {
            $this->getResult()->getStatus(false);

        }

    }

    public function setSaveBasket()
    {
        $basketUser = Fuser::getId();
        $PAN = $_REQUEST['PAN'];
        $STORE = $_REQUEST['STORE'];
        $DELIVERY = strpos($STORE, '_def') ? 'Y' : 'N';
        $IBLOCK_ID = SBIblock::getIblockId(Variables::IBLOCK_CODE_SETTING_BASKET);
        $PROP = array();
        $PROP['PAN'] = $PAN;
        $PROP['STORE'] = $STORE;
        $PROP['DELIVERY'] = $DELIVERY;

        $arLoadProductArray = Array(
            "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
            "IBLOCK_ID" => $IBLOCK_ID,
            "PROPERTY_VALUES" => $PROP,
            "NAME" => $basketUser,
            "ACTIVE" => "Y",            // активен
        );
        $arElement = SBElement::getElement(["IBLOCK_ID" => $IBLOCK_ID, 'NAME' => $basketUser, '=PROPERTY_ORDER' => false], ['ID', 'NAME']);
        if ($arElement) {
            $arElement = $arElement[0];
            $el = new CIBlockElement;
            if ($res = $el->Update($arElement['ID'], $arLoadProductArray)) {
                $this->getResult()->addData('status', +$arElement['ID']);
            }
        } else {
            $el = new CIBlockElement;
            if ($PRODUCT_ID = $el->Add($arLoadProductArray))
                $this->getResult()->addData('status', $PRODUCT_ID);
            else
                $this->getResult()->addData('status', $el->LAST_ERROR);
        }
    }

    public function reCaptcha3()
    {
        if (isset($_REQUEST['token']) && isset($_REQUEST['action'])) {
            $captcha_token = $_REQUEST['token'];
            $captcha_action = $_REQUEST['action'];
        } else {
            die('Error');
        }
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $params = [
            'secret' => '6LdU95kUAAAAAMqZPLC26EbAO1YZXzI89EhJkFJw',
            'response' => $captcha_token,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);
        if (!empty($response)) {
            $decoded_response = json_decode($response);
        }
        //Log::addToLog($_SERVER['DOCUMENT_ROOT'] . '/local/log/reCapcha3.log', $decoded_response);
        $success = false;


        if ($decoded_response && $decoded_response->success && $decoded_response->action == $captcha_action && $decoded_response->score > 0) {
            $success = $decoded_response->success;
            // обрабатываем данные формы, которая защищена капчей
        } else {
            // прописываем действие, если пользователь оказался ботом
        }

        $this->getResult()->addData('status', $success);
    }
}
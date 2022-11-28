<?php

namespace SB\Site\Dadata;

use Bitrix\Main\Entity\ExpressionField;

use Bitrix\Main\Loader;
use Bitrix\Sale\Discount;
use Bitrix\Sale\Location\Admin\LocationHelper;
use Bitrix\Sale\Location\LocationTable;
use SB\Site\Variables;

class LocationConverter
{
    /** @var SuggestClient */
    protected $obDaData = null;

    /**
     * LocationConverter constructor.
     */
    public function __construct()
    {
        $this->obDaData = new SuggestClient(Variables::DADATA_TOKEN, Variables::DADATA_SECTER_KEY);
    }

    public function getBitrixSuggestions(string $query, array $country = [],$typeFind = 'city')
    {

        if (\strlen($query) <= 2) {
            return [];
        }

        if ($country['name']) {
            $countryName = $country['name'];
        }

        if ($country['id']) {
            $countryId = $country['id'];
        }


        if ($countryName) {
            $locations = [
                'country' => $countryName
            ];

        }
        $daDataResult = $this->obDaData->getCityAddress($query, $locations ?? [], $typeFind);

        return $this->getBitrixSuggestionsByDaDataSuggestions($daDataResult['suggestions'] ?? [], $countryId ?? null);
    }
    public function getZip2CitySuggestions(string $query)
    {
        if (\strlen($query) <= 2) {
            return [];
        }

//        $daDataResult = $this->obDaData->getCityAddress($query, $locations ?? [], $typeFind);
//        return $this->getBitrixSuggestionsByDaDataSuggestions($daDataResult['suggestions'] ?? [], $countryId ?? null);
    }

    protected function getBitrixSuggestionsByDaDataSuggestions($suggestions, $countryId = null)
    {
        Loader::includeModule('sale');
        if (empty($suggestions)) {
            return [];
        }

        try {
            $select = [
                'CODE',
                'LOCATION_NAME' => 'NAME.NAME',
//                'PARENT_NAME' => 'PARENT.NAME.NAME',
                'SCORE',
            ];
            $filter = [
                '=NAME.LANGUAGE_ID' => 'ru',
                '>SCORE' => 0,
                '=TYPE_ID' => [3, 5, 6]
            ];

            $runtime = [];
            $order = [
                'SCORE' => 'DESC',
                'TYPE.SORT' => 'ASC',
            ];
            $limit = 10;
            $group = [
                'CODE'
            ];

            $arQuery = [];
            $newsuggestions = [];
            foreach ($suggestions as $index => $suggestion) {
//                \_::d($suggestion);
                $scoreMultiplier = (count($suggestions) - $index) + 1;

                //$typeQuery = false;
                $city = strtoupper($suggestion['data']['city']);
                $region = strtoupper($suggestion['data']['region']);
                $settlement = strtoupper($suggestion['data']['settlement']);

                if ($city) {
                    $arQuery[] = 'if( %1$s LIKE ' . "'{$city}', " . (100 * $scoreMultiplier) . ', 0)';
                    $arQuery[] = 'if( %1$s LIKE ' . "'%%{$city}%%', " . (10 * $scoreMultiplier) . ', 0)';
                    $suggestion['sb_type'] = 'city';
                    $newsuggestions[$city] = $suggestion;

                }

                if ($settlement) {
                    $arQuery[] = 'if( %1$s LIKE ' . "'{$settlement}', " . (100 * $scoreMultiplier) . ', 0)';
                    $arQuery[] = 'if( %1$s LIKE ' . "'%%{$settlement}%%', " . (10 * $scoreMultiplier) . ', 0)';

                }

                if ($region) {
                    $arQuery[] = 'if( %1$s LIKE ' . "'{$region}', " . (100 * $scoreMultiplier) . ', 0)';
                    $arQuery[] = 'if( %1$s LIKE ' . "'%%{$region}%%', " . (10 * $scoreMultiplier) . ', 0)';

//                    $suggestion['sb_type'] = 'region';
                    $newsuggestions[$region] = $suggestion;

                }

            }

            $query = implode(' + ', $arQuery);


            $runtime[] = new ExpressionField('SCORE', "({$query})", 'NAME.NAME_UPPER');

            if (empty($runtime)) {
                return [];
            }

            $parameters = compact(
                'select',
                'filter',
                'order',
                'runtime',
                'limit',
                'group'

            );

            $result = LocationTable::getList($parameters)->fetchAll();
            foreach ($result as $key => &$location) {

//                $location['DISPLAY'] = $location['LOCATION_NAME']; //\Bitrix\Sale\Location\Admin\LocationHelper::getLocationStringByCode($location['CODE']);
                $location['ZIP'] = LocationHelper::getZipByLocation($location['CODE'])->fetch()['XML_ID'] ?: $suggestion['data']['postal_code'];
                $location['data'] = $newsuggestions[strtoupper($location['LOCATION_NAME'])];
                $location['DISPLAY'] = \Bitrix\Sale\Location\Admin\LocationHelper::getLocationStringByCode($location['CODE']);

            }

            unset($location);

        } catch (\RuntimeException $e) {
            return [];
        }

        return $result;
    }

}
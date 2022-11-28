<?php

namespace SB\Site\Dadata;

class SuggestClient
{
    protected $url,
        $token,
        $secretKey;

    public function __construct(
        $token,
        $secretKey,
        $url = 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/'
    )
    {
        $this->token = $token;
        $this->secretKey = $secretKey;
        $this->url = $url;
    }

    public function suggest(string $type, $fields)
    {
        $result = false;
        if ($ch = curl_init($this->url . $type)) {
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Token ' . $this->token
            ));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            $result = json_decode($result, true);
            curl_close($ch);
        }
        return $result;
    }

    public function getCity(string $query, array $locations = [])
    {
        return $this->suggest(
            'address',
            [
                'geoLocation' => false,
                'query' => $query,
                'count' => 10,
                'locations' => $locations,
                'from_bound' => ['value' => 'city']

            ]
        );
    }

    public function getAddress(string $query, array $locations = [])
    {
        return $this->suggest(
            'address',
            [
                'query' => $query,
                'count' => 10,
                'locations' => $locations,
                'from_bound' => ['value' => 'city']
            ]
        );
    }

    public function getCityAddress(string $query, array $locations = [], $typeFind = 'city')
    {
        return $this->suggest(
            'address',
            [
                'query' => $query,
                'count' => 10,
                'locations' => $locations,
                'from_bound' => ['value' => 'city'],
                'to_bound' => ['value' => 'settlement']//TODO:Поиск только по региону city
            ]
        );
    }


    public function getParty($query)
    {
        return $this->suggest('party', ['query' => $query, 'count' => 3, 'status' => ['ACTIVE']]);
    }

    public function getBank($query)
    {
        return $this->suggest('bank', ['query' => $query, 'count' => 3, 'status' => ['ACTIVE']]);
    }

    public function getFIO($query)
    {
        return $this->suggest('fio', ['query' => $query, 'count' => 3]);
    }

    public function getEmail($query)
    {
        return $this->suggest('email', ['query' => $query, 'count' => 3]);
    }
}
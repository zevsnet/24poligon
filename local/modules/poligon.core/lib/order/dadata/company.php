<?php


namespace Poligon\Core\Order\DaData;


class Company extends DaData
{
    private $url = 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/party';

    public function execute()
    {
        $query = $this->getByKey('query');

        $data = [
            'query' => $query
        ];

        $response = $this->send($data);

        return $this->formatResponse($response);
    }

    protected function getUrl()
    {
        return $this->url;
    }

    private function formatResponse($response)
    {
        $response = $response['suggestions'];

        if (empty($response) || !is_array($response)) {
            return [];
        }

        $result = array_map(function ($e) {
            return [
                'value' => $e['value'],
                'inn' => $e['data']['inn'] ?? null,
                'kpp' => $e['data']['kpp'] ?? null,
                'ogrn' => $e['data']['ogrn'] ?? null
            ];
        }, $response);

        return $result;
    }
}

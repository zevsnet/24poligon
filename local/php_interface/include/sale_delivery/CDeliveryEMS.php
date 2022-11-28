<?php



use Bitrix\Main\Web\HttpClient,
    Bitrix\Main\Loader;
use SB\Traits\SB;


Loader::includeModule('sale');


class CDeliveryEMS extends \Bitrix\Sale\Delivery\Services\Base
{
    public static $MODULE_ID = 'sb.energy';
    /** @var CEnergy */
    public static $obEnergy;
    public static $arAuth;

    protected static $FIX_LOCATIONS_ARRAY = [
        'алма-ата' => 'алматы',
        'смоленское село' => 'Смоленское(Алтай)'
    ];

    public function Init()
    {
        $base_currency = 'RUB';
        return array(
            'SID' => 'cdeliveryems',
            'NAME' => 'CDeliveryEMS',
            'DESCRIPTION' => 'Служба доставки Энергия. Транспортная компания',
            'DESCRIPTION_INNER' => 'Служба доставки Энергия. Транспортная компания',
            'BASE_CURRENCY' => $base_currency,
            'HANDLER' => __FILE__,
            'DBGETSETTINGS' => array('Sb\Energy\CDeliveryEnergy', 'GetSettings'),
            'DBSETSETTINGS' => array('Sb\Energy\CDeliveryEnergy', 'SetSettings'),
            'GETCONFIG' => array('Sb\Energy\CDeliveryEnergy', 'GetConfig'),
            'COMPABILITY' => array('Sb\Energy\CDeliveryEnergy', 'Compability'),
            'CALCULATOR' => array('Sb\Energy\CDeliveryEnergy', 'Calculate'),
            'PROFILES' => array(
                '1' => array(
                    'TITLE' => 'Доставка до склада',
                    'DESCRIPTION' => '',
                    'RESTRICTIONS_WEIGHT' => [0],
                    'RESTRICTIONS_SUM' => [0],
                ),
                '2' => array(
                    'TITLE' => 'Авиа',
                    'DESCRIPTION' => '',
                    'RESTRICTIONS_WEIGHT' => array(0),
                    'RESTRICTIONS_SUM' => array(0),
                ),
                '3' => array(
                    'TITLE' => 'Ж/д',
                    'DESCRIPTION' => '',
                    'RESTRICTIONS_WEIGHT' => array(0),
                    'RESTRICTIONS_SUM' => array(0),
                ),
                '4' => [
                    'TITLE' => 'Доставка до двери',
                    'DESCRIPTION' => '',
                    'RESTRICTIONS_WEIGHT' => [0],
                    'RESTRICTIONS_SUM' => [0],
                ],
            )
        );

    }

    public function GetConfig()
    {
        $arConfig = array(
            'CONFIG_GROUPS' => array(
                'all' => 'Параметры',
            ),

            'CONFIG' => array(
                'DELIVERY_PRICE_2' => array(
                    'TYPE' => 'STRING',
                    'DEFAULT' => '',
                    'TITLE' => 'Фиксированная стоимость доставки Авиа',
                    'GROUP' => 'all'
                ),
                'DELIVERY_PRICE_1' => array(
                    'TYPE' => 'STRING',
                    'DEFAULT' => '',
                    'TITLE' => 'Фиксированная стоимость доставки Автотранспортом',
                    'GROUP' => 'all'
                ),
                'DELIVERY_PRICE_3' => array(
                    'TYPE' => 'STRING',
                    'DEFAULT' => '',
                    'TITLE' => 'Фиксированная стоимость доставки ж/д',
                    'GROUP' => 'all'
                ),
                'CITY_LOCAL' => array(
                    'TYPE' => 'CHECKBOX',
                    'DEFAULT' => 'Y',
                    'TITLE' => 'Разрешить доставку по городу',
                    'GROUP' => 'all'
                ),
                'PLACE' => array(
                    'TYPE' => 'DROPDOWN',
                    'DEFAULT' => 'DELIM',
                    'TITLE' => 'Тип учета мест при доставке',
                    'VALUES' => array(
                        'ALL' => 'Все товары в одном месте (Меры складываются)',
                        'DELIM' => 'Каждый товар в отдельном месте',
                    ),
                    'GROUP' => 'all'
                ),
                'CURRENCY' => array(
                    'TYPE' => 'DROPDOWN',
                    'DEFAULT' => '1',
                    'TITLE' => 'Валюта. В какой валюте будет возвращаться ответ API',
                    'VALUES' => array(
                        1 => 'Российский рубль',
                        4 => 'Казахский тенге',
                        99 => 'Китайский юань',
                        8 => 'Киргизский сом',
                        5 => 'Белорусский рубль'
                    ),
                    'GROUP' => 'all'
                )
            ),
        );
        return $arConfig;
    }

    public function GetSettings($strSettings)
    {
        return unserialize($strSettings, null);
    }

    public function SetSettings($arSettings)
    {
        return serialize($arSettings);
    }

    public function Calculate($profile, $arConfig, $arOrder, $STEP, $TEMP = false)
    {
        if (!empty($arConfig['DELIVERY_PRICE_' . $profile]['VALUE'])) {
            return array(
                'RESULT' => 'OK',
                'VALUE' => (int)$arConfig['DELIVERY_PRICE_' . $profile]['VALUE']
            );
        }
    }

}
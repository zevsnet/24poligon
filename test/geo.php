<?require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$ip = \Bitrix\Main\Service\GeoIp\Manager::getRealIp();
if (Bitrix\Main\Loader::includeModule('rover.geoip')) {
    $arInfo = \Rover\GeoIp\Location::getInstance($ip);
    if($arInfo->getLanguage() != 'ru'){
        die();
    }
}

\_::d('te');
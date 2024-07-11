<?
// подключение служебной части пролога
use SB\Site\Dadata\SuggestClient;
use SB\Site\Variables;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
addBasket();



function addBasket(){

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://24poligon.ru/ajax/item.php?prop%5B%5D=0&quantity=1&add_item=Y&rid=&offers=N&iblockID=180&part_props=N&add_props=Y&props=%22%22&item=309669&basket_props=',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => array(
            'Cookie: PHPSESSID=W3Ub1Vz8o08WAmMefbdMLu4sIcrLAc2M; ct_cookies_test=%7B%22cookies_names%22%3A%5B%22ct_timestamp%22%5D%2C%22check_value%22%3A%22dde33efd1eb4d0ffe612a86338b095de%22%7D; ct_timestamp=1720669032'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
}
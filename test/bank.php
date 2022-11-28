<?
// подключение служебной части пролога
use SB\Site\Dadata\SuggestClient;
use SB\Site\Variables;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
$obDaData = new SuggestClient(Variables::DADATA_TOKEN, Variables::DADATA_SECTER_KEY);
$arRes = $obDaData->getBank('044525593');
$suggestions = $arRes['suggestions'][0];
if ($suggestions) {
    $BANK = $suggestions['value'];//BANK//Банк получателя
    $NUM_COR = $suggestions['data']['correspondent_account'];//NUM_COR//Номер кор.счета
}

$arRes = $obDaData->suggest('party',['query'=>'7728168971','count' => 1, 'status' => ['ACTIVE']]);
$suggestions = $arRes['suggestions'][0];
\_::d($suggestions['data']['address']['unrestricted_value']);
<?
// подключение служебной части пролога
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

//Получаем список магазинов из инфоблока
//$obElementsShops = CIBlockElement::GetList([],[
//    'IBLOCK_ID'=>165,
//    'ACTIVE'=>'Y',
//]);
//while ($obElem = $obElementsShops->GetNextElement()){
//    $
//    \_::d($arElem);
//}
$arFilter = [
    'IBLOCK_ID'=>165,
    'ACTIVE'=>'Y',
];
$arElements = \SB\Site\Bitrix\SBElement::getElement($arFilter);

foreach ($arElements as $arElement) {
       $STORE_ID = $arElement['PROP']['STORE_ID']['VALUE'];
       if(empty($STORE_ID)){
           echo 'Магазин ' . $arElement['ID'] . ' не имеет привязки к складу';
           continue;
       }

       \SB\Site\Store::setPhoneStore($STORE_ID,implode(', ',$arElement['PROP']['PHONE']['VALUE']));
}
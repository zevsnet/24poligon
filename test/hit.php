<?
// подключение служебной части пролога
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

//\SB\Site\Bitrix\SBElement::setSection2Params([
//    'IBLOCK_ID' => \SB\Site\Variables::IBLOCK_ID_CATALOG,
//    'SECTION_ID' => '3391', // id - раздела куда присвоить
//    'IBLOCK_PROPERTY_ID' => '11898', // id свойства
//    'ENUM_VALUE_NAME' => '99273'//ид - значение списка
//]);


//Проставим товарам отметку что он является Хитом

$arIdProduct = [160271,160274,162962,160247,160261,160241,160268];
foreach ($arIdProduct as $item) {
    CIBlockElement::SetPropertyValuesEx($item,\Poligon\Core\Iblock\Helper::getIdByCode(\Poligon\Core\Variables::IBLOCK_CATALOG_CODE),['HIT'=>\Poligon\Core\Iblock\Helper::getIdEnumListProp('HIT','HIT')]);
}
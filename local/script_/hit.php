<?
$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__) . "/../..");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

//Проставим товарам отметку что он является Хитом
$arIdProduct = [160271,160274,162962,160247,160261,160241,160268];
foreach ($arIdProduct as $item) {
    CIBlockElement::SetPropertyValuesEx($item,\Poligon\Core\Iblock\Helper::getIdByCode(\Poligon\Core\Variables::IBLOCK_CATALOG_CODE),['HIT'=>\Poligon\Core\Iblock\Helper::getIdEnumListProp('HIT','HIT')]);
}
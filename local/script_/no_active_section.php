<?
// подключение служебной части пролога
use Bitrix\Catalog\ProductTable;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
/*
Снимаем активность групп разделов в которых нет товаров
*/
$res = CIBlockSection::GetList([$by => $order],
    [
        'IBLOCK_ID' => \SB\Site\Variables::IBLOCK_ID_CATALOG,
        'ACTIVE' => 'Y',
        'GLOBAL_ACTIVE' => 'Y'
    ], true, ['ID']);

while ($section = $res->GetNext()) {
    $arElements = \SB\Site\Bitrix\SBElement::getElement([
        'IBLOCK_ID' => \SB\Site\Variables::IBLOCK_ID_CATALOG,
        'INCLUDE_SUBSECTIONS' => 'Y',
        'ACTIVE' => 'Y',
        'SECTION_ID' => $section['ID']
    ], ['ID']);
    $isDeactivation = true;
    foreach ($arElements as $arElement) {
        $ar_res = CCatalogProduct::GetList([], ['ID' => $arElement['ID'], 'AVAILABLE' => 'Y']);
        if ($ar_res->Fetch()) {
            $isDeactivation = false;
        }
        if (!$isDeactivation) {
            break;
        }
    }
    if($isDeactivation){
        \SB\Site\Bitrix\SBElement::activeSection(\SB\Site\Variables::IBLOCK_ID_CATALOG,$section['ID'],'N');
    }
}
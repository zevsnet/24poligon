<? use SB\Site\Bitrix\SBElement;

$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__) . "/../..");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

/*Деактивируем товар если у него нет картинки*/
$res = CIblockElement::GetList([],['IBLOCK_ID' => \SB\Site\Variables::IBLOCK_ID_CATALOG,
    [
        'LOGIC'=>'AND',
        '=PREVIEW_PICTURE' => false,
        '=DETAIL_PICTURE' => false,

    ],
    'ACTIVE' => 'Y'],false, false, ['ID']);
while ($el = $res->GetNext()) {
//    SBElement::activeElement(\SB\Site\Variables::IBLOCK_ID_CATALOG, $el['ID'], 'N');
}
<?
// подключение служебной части пролога
use SB\Site\Bitrix\SBElement;
use SB\Site\Variables;
$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__) . "/../..");

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
global $USER;
//if (!$USER->IsAdmin()){
//    \_::d('Упс...');
//    return;
//};

\_::d('Картинки для разделов у которых нет картинки');
//\_::dd('Stop');
$arFilter = [];
$arFilter['IBLOCK_ID'] = Variables::IBLOCK_ID_CATALOG;
$arFilter['ACTIVE'] = 'Y';
$arFilter['GLOBAL_ACTIVE'] = 'Y';
//$arFilter['=PREVIEW_PICTURE'] = false;
//$arFilter['=PICTURE'] = false;
$obSection = CIBlockSection::GetList(['ID' => 'ASC'], $arFilter);

$obSectionUpdate = new CIBlockSection();
while ($arSection = $obSection->Fetch()) {

    $arElement = SBElement::getElement([
            'IBLOCK_ID' => Variables::IBLOCK_ID_CATALOG,
            'SECTION_ID' => $arSection['ID'],
            'INCLUDE_SUBSECTIONS' => 'Y',
            '!DETAIL_PICTURE' => false,
            $arFilter['ACTIVE'] = 'Y'
        ]
        , ['ID', 'DETAIL_PICTURE'], 1);
    if ($arElement) {

        $obSectionUpdate->Update($arSection['ID'], [
            'PICTURE' => CFile::MakeFileArray($arElement['DETAIL_PICTURE']),
            'DETAIL_PICTURE' => CFile::MakeFileArray($arElement['DETAIL_PICTURE'])
        ]);
    }
}

\_::d('Finish');
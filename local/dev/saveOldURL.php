<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php"); ?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php"); ?>
<?
if ($_REQUEST['type'] == 'OLD') {
    //setPropertyURL(\SB\Site\Variables::IBLOCK_ID_CATALOG, 'OLD_ELEMENT_URL');
} elseif ($_REQUEST['type'] == 'NEW') {
    setPropertyURL(\SB\Site\Variables::IBLOCK_ID_CATALOG, 'NEW_ELEMENT_URL');

}

function setPropertyURL($IBLOCK_ID, $PROP, $SECTION_ID = false, $strReplace = false)
{
    \Bitrix\Main\Loader::includeModule('iblock');
    $arFilter = Array("IBLOCK_ID" => $IBLOCK_ID);
    if ($SECTION_ID) {
        $arFilter['SECTION_ID'] = $SECTION_ID;
        $arFilter['INCLUDE_SUBSECTIONS'] = 'Y';
    }
    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, ['*']);
    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        if ($strReplace) {
            $arFields['DETAIL_PAGE_URL'] = str_replace('catalog', $strReplace, $arFields['DETAIL_PAGE_URL']);
        }
        CIBlockElement::SetPropertyValuesEx($arFields['ID'], $IBLOCK_ID, [$PROP => ['VALUE' => $arFields['DETAIL_PAGE_URL']]]);
    }
}

function addPropertyURL($IBLOCK_ID, $PROP, $SECTION_ID = false, $strReplace = false)
{
    \Bitrix\Main\Loader::includeModule('iblock');
    $arFilter = Array("IBLOCK_ID" => $IBLOCK_ID);
    if ($SECTION_ID) {
        $arFilter['SECTION_ID'] = $SECTION_ID;
        $arFilter['INCLUDE_SUBSECTIONS'] = 'Y';
    }
    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, ['*']);
    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        $arProp = $ob->GetProperty($PROP);

        if ($strReplace) {
            $arFields['DETAIL_PAGE_URL'] = str_replace('catalog', $strReplace, $arFields['DETAIL_PAGE_URL']);
        }
        $newPropValue = [];
        if (!isArray($arProp['VALUE'], $arFields['DETAIL_PAGE_URL'])) {
            foreach ($arProp['VALUE'] as $item) {
                if ($item !== $arFields['DETAIL_PAGE_URL']) {
                    $newPropValue[]['VALUE'] = $item;
                }
            }
            $newPropValue[]['VALUE'] = $arFields['DETAIL_PAGE_URL'];
        }

        CIBlockElement::SetPropertyValuesEx($arFields['ID'], $IBLOCK_ID, [$PROP => $newPropValue]);

    }
}

function isArray($arProp, $url)
{
    foreach ($arProp as $item) {
        if ($item === $url) {
            return true;
        }
    }
    return false;
}

\_::d('FINISH');


//\Bitrix\Main\Loader::includeModule('iblock');
//
//
//$arFilter = Array("IBLOCK_ID" => IntVal(\SB\Site\Variables::IBLOCK_ID_CATALOG), "ACTIVE_DATE" => "Y", "ACTIVE" => "Y");
//
//$res = CIBlockElement::GetList(Array(), $arFilter, false, false, ['*']);
//while ($ob = $res->GetNextElement()) {
//    $arFields = $ob->GetFields();
//    CIBlockElement::SetPropertyValuesEx($arFields['ID'], \SB\Site\Variables::IBLOCK_ID_CATALOG, ['OLD_ELEMENT_URL'=>['VALUE'=>$arFields['DETAIL_PAGE_URL']]]);
//}
//\_::d('FINISH');

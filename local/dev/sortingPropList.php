<?
// подключение служебной части пролога
use SB\Site\EditProps;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('iblock');

$IBLOCK_ID = \SB\Site\Variables::IBLOCK_ID_CATALOG;
$IBLOCK_ID_OFFERS = \SB\Site\Variables::IBLOCK_ID_CATALOG_OFFERS;
$ID_CODE_PROPERTY = 'Размер';
if ($_REQUEST['RUN'] != 'zevsnet') {
    return;
}
if ($_REQUEST['IBLOCK_ID']) {
    $IBLOCK_ID = $_REQUEST['IBLOCK_ID'];
}

if ($_REQUEST['PROP']) {
    $ID_CODE_PROPERTY = $_REQUEST['PROP'];
}

$OFFER_TREE_PROPS = \SB\Site\General::getPropCodeSize($IBLOCK_ID, $ID_CODE_PROPERTY);
$ELEMENT_PROPS = \SB\Site\General::getPropCodeSize($IBLOCK_ID_OFFERS, $ID_CODE_PROPERTY);

if ($_REQUEST['PROP_FILTER'] == 'Y') {
    $ent = new EditProps();
    foreach ($OFFER_TREE_PROPS as $keyID => $sb_val) {

        $ent->setSmartFilter($keyID, 'Y');
    }
    foreach ($ELEMENT_PROPS as $keyID => $sb_val) {
        $ent->setSmartFilter($keyID, 'Y');
    }
}
if ($_REQUEST['PROP_SKU'] == 'Y') {
    $ent = new EditProps();
    foreach ($OFFER_TREE_PROPS as $keyID => $sb_val) {

//        $ent->setSmartFilter($keyID, 'Y');
//        $ent->setFeatureProps($keyID, 'Y', 'DETAIL_PAGE_SHOW');
//        $ent->setFeatureProps($keyID, 'Y', 'LIST_PAGE_SHOW');
        $ent->setFeatureProps($keyID, 'Y', 'IN_BASKET');
        $ent->setFeatureProps($keyID, 'Y', 'OFFER_TREE');
    }
}
if ($_REQUEST['SORT'] == 'Y') {
    foreach ($OFFER_TREE_PROPS as $sb_val) {
        $property_enums = CIBlockPropertyEnum::GetList(Array("VALUE" => "ASC"),
            Array("IBLOCK_ID" => $IBLOCK_ID, "CODE" => $sb_val));
        $arProps = [];
        while ($enum_fields = $property_enums->GetNext()) {
            $arProps[] = $enum_fields;
        }
        $ibpenum = new CIBlockPropertyEnum;
        $SORT = 10;
        foreach ($arProps as $arProp) {

            $arProp['SORT'] = $SORT;
            $arProp['TMP_ID'] = '';
            $arProp['~TMP_ID'] = '';

            $ibpenum->Update($arProp['ID'], $arProp);
            $SORT += 10;
        }
    }
}
\_::d('Okey');
<?
// подключение служебной части пролога
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

function getArrKey(){
    $IBLOCK_ID = \SB\Site\Variables::IBLOCK_ID_CATALOG_OFFERS;
    $properties = CIBlockProperty::GetList(Array("sort" => "asc", "name" => "asc"),
        Array("ACTIVE" => "Y", "IBLOCK_ID" => $IBLOCK_ID));
    $arPropCopy = [];
    while ($prop_fields = $properties->GetNext()) {
        $arPropCopy[$prop_fields['NAME']][] = $prop_fields['XML_ID'];
    }

    foreach ($arPropCopy as $key => $item) {
        if (count($item) == 1) {
            unset($arPropCopy[$key]);
        }else{
            $arPropCopy[$key] = [
                'MAIN' => $item[0],
                'FULL' => $item
            ];
        }
    }

    return $arPropCopy;
}

\_::d(getArrKey());
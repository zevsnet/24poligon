<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
\CModule::IncludeModule('iblock');

$IBLOCK_ID = 106;

$obRes = CIBlockElement::GetList([], [
    'IBLOCK_ID' => $IBLOCK_ID,
'ID'=>47312
], false, false, ['ID', 'NAME']);
while ($res = $obRes->GetNextElement()) {
    $arElement = $res->fields;
    $arElementProp = $res->GetProperties();
    updatePriceElement($arElement['ID']);
\_::d($arElement);
break;

}

function updatePriceElement($ELEMENT)
{
    \CModule::IncludeModule('iblock');
    \CModule::IncludeModule('catalog');
    $CATALOG_GROUP_ID = 5;
    $res_ = \CPrice::GetList(
        array(),
        array(
            "PRODUCT_ID" => $ELEMENT,
            "CATALOG_GROUP_ID" => $CATALOG_GROUP_ID
        )
    );
$arFields = Array(
            "PRODUCT_ID" => $ELEMENT,
            "CATALOG_GROUP_ID" => $CATALOG_GROUP_ID,
            "PRICE" => $arr['PRICE'],
"CURRENCY" => "RUB",
        );

    if ($arr = $res_->Fetch()) {

        
        \CPrice::Update($arr["ID"], $arFields);

    }else{
\_::d($arFields);
	\CPrice::Add($arFields);
	}
}
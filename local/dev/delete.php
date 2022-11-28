<?
$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__) . "/../..");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
\CModule::IncludeModule("iblock");
$result = \CIBlockElement::GetList
(
    array(),
    array
    (
        'IBLOCK_ID'=>182,
        'ACTIVE'=>'N'
	),
	false,
	array("nTopCount" => 10000),
	array("ID")
);

while($element = $result->Fetch())
    \CIBlockElement::Delete($element['ID']);

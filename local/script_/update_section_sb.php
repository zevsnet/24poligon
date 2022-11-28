<? use SB\Site\General;

$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__) . "/../..");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
//$arFields = [];
//$arFields['IBLOCK_ID'] = \SB\Site\Variables::IBLOCK_ID_CATALOG;
//$arFields['NAME_SECTION'] = 'Новинки';
//$arFields['CODE_PROP'] = 'NOVINKA';
//$arFields['ENUM_VALUE_NAME'] = 'Новинка';
//General::setNew($arFields);
//
//$arFields['NAME_SECTION'] = 'КОЛЛЕКЦИЯ ЗИМА 2020';
//$arFields['CODE_PROP'] = 'KOLLEKTSIYA_SEZON';
//$arFields['ENUM_VALUE_NAME'] = 'зима';
//General::setNew($arFields);
//
//$arFields['NAME_SECTION'] = 'РОСГВАРДИЯ';
//$arFields['CODE_PROP'] = 'ROSGVARDIYA';
//$arFields['ENUM_VALUE_NAME'] = 'Да';
//General::setNew($arFields);

$obSection = new CIBlockSection();
$obSection->Update(3393,['ACTIVE'=>'Y']);
$obSection->Update(3419,['ACTIVE'=>'Y']);

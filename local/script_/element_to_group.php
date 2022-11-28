<?$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__) . "/../..");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
//$obSection = new CIBlockSection();
//$obSection->Update(3393,['ACTIVE'=>'Y']);
//$obSection->Update(3419,['ACTIVE'=>'Y']);

//Росгвардия
\SB\Site\Bitrix\SBElement::setSection2Params([
    'IBLOCK_ID' => \SB\Site\Variables::IBLOCK_ID_CATALOG,
    'SECTION_ID' => '3400', // id - раздела куда присвоить
    'IBLOCK_PROPERTY_ID' => '11775', // id свойства
    'ENUM_VALUE_NAME' => '96566'//ид - значение списка
]);
//НОВИНКА
\SB\Site\Bitrix\SBElement::setSection2Params([
    'IBLOCK_ID' => \SB\Site\Variables::IBLOCK_ID_CATALOG,
    'SECTION_ID' => '3419', // id - раздела куда присвоить
    'IBLOCK_PROPERTY_ID' => '11541', // id свойства
    'ENUM_VALUE_NAME' => '94746'//ид - значение списка
]);
//Коллекция(сезон) - ЗИМА
\SB\Site\Bitrix\SBElement::setSection2Params([
    'IBLOCK_ID' => \SB\Site\Variables::IBLOCK_ID_CATALOG,
    'SECTION_ID' => '3393', // id - раздела куда присвоить
    'IBLOCK_PROPERTY_ID' => '11532', // id свойства
    'ENUM_VALUE_NAME' => '94709'//ид - значение списка
]);
//Коллекция(сезон) - ВЕСНА
\SB\Site\Bitrix\SBElement::setSection2Params([
    'IBLOCK_ID' => \SB\Site\Variables::IBLOCK_ID_CATALOG,
    'SECTION_ID' => '3393', // id - раздела куда присвоить
    'IBLOCK_PROPERTY_ID' => '11532', // id свойства
    'ENUM_VALUE_NAME' => '94708'//ид - значение списка
]);

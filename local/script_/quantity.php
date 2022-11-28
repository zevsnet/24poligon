<?
// подключение служебной части пролога
$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__) . "/../..");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
//Проставим всем товарам количество доступности
//\SB\Site\General::updateQuantityElements(182,0);
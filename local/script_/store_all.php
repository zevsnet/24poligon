<?

use SB\Site\Variables;

$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__) . "/../..");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

/*ѕроставл€ем общий остаток всем товарам*/
\SB\Site\Store::getAllElementUpdateStores([
    'IBLOCK_ID'=>Variables::IBLOCK_ID_CATALOG]);

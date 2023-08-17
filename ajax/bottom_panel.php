<?
use Bitrix\Main\Localization\Loc,
    Bitrix\Main\Web\Json,
	Bitrix\Main\SystemException,
	Bitrix\Main\Loader;

include_once('const.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

if (!Loader::includeModule(PUBLIC_VENDOR_MODULE_ID)) {
    throw new SystemException('Error include module '.PUBLIC_VENDOR_MODULE_ID);
}
?>

<?
$url = SITE_DIR;
// fix ajax url
if($url != $_SERVER['REQUEST_URI']){
    $_SERVER['QUERY_STRING'] = '';
    $_SERVER['REQUEST_URI'] = $url;
    $APPLICATION->sDocPath2 = GetPagePath(false, true);
    $APPLICATION->sDirPath = GetDirPath($APPLICATION->sDocPath2);
}
?>

<?//$APPLICATION->ShowHeadScripts();?>
<?$APPLICATION->ShowCSS();?>

<?\Aspro\Functions\CAsproMax::showBottomPanel();?>


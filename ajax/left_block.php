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

<?$APPLICATION->ShowHeadScripts();?>
<?$APPLICATION->ShowCSS();?>

<?
//set filter
if ($_SESSION['ASPRO_FILTER']['arSideRegionLink']) {
    $GLOBALS['arSideRegionLink'] = $_SESSION['ASPRO_FILTER']['arSideRegionLink'];
}
if ($_SESSION['ASPRO_FILTER']['arRegionLink']) {
    if ($GLOBALS['arSideRegionLink']) {
        $GLOBALS['arSideRegionLink'] = array_merge($GLOBALS['arSideRegionLink'], $_SESSION['ASPRO_FILTER']['arRegionLink']);
    } else {
        $GLOBALS['arSideRegionLink'] = $_SESSION['ASPRO_FILTER']['arRegionLink'];
    }
}?>

<?

Loc::loadMessages($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/header.php');
?>

<?
//form wrapper
$bShowCallBackBlock = strpos(CMax::GetFrontParametrValue("SHOW_CALLBACK"), 'INNER_MENU') !== false;
$bShowQuestionBlock = strpos(CMax::GetFrontParametrValue("SHOW_QUESTION"), 'INNER_MENU') !== false;
$bShowReviewBlock = strpos(CMax::GetFrontParametrValue("SHOW_REVIEW"), 'INNER_MENU') !== false;
?>

<?CMax::ShowPageType('left_block');?>


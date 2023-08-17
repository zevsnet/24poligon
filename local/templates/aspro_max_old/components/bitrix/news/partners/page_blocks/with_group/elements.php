<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	define("STATISTIC_SKIP_ACTIVITY_CHECK", true);
	define('NOT_CHECK_FILE_PERMISSIONS', true);
	define('PUBLIC_AJAX_MODE', true);
	define('NO_KEEP_STATISTIC', 'Y');
	define('STOP_STATISTICS', true);

	$siteId = isset($_POST['SITE_ID']) && is_string($_POST['SITE_ID']) ? $_POST['SITE_ID'] : '';
	$siteId = mb_substr(preg_replace('/[^a-z0-9_]/i', '', $siteId), 0, 2);
	if (!empty($siteId) && is_string($siteId)) {
		define('SITE_ID', $siteId);
	}
	
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
}?>
<?
$pathToFile = "";

if(isset($this)){
	$arPathFile = explode($this->__folder,__FILE__);
	$pathToFile = $this->__folder . $arPathFile[1];
}
?>
<?$APPLICATION->IncludeComponent(
	"aspro:wrapper.block.max",
	"brands_by_group",
	Array(
		"AJAX_PATH" => $pathToFile,
		"LETTERS" => $arFilterLetters,
		"LETTER" => $letterRequest,
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"SHOW_AJAX_HEAD" => "N"
	)
);?>
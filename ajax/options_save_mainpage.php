<?
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
define('NO_AGENT_CHECK', true);
define('STATISTIC_SKIP_ACTIVITY_CHECK', true);
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
if((isset($_POST['NAME']) && $_POST['NAME']) && (isset($_POST['VALUE']) && $_POST['VALUE']))
{
	if(\Bitrix\Main\Loader::includeModule('aspro.max'))
	{
		$_SESSION['THEME'][SITE_ID][$_POST['NAME']] = $_POST['VALUE'];
	}
}
?>
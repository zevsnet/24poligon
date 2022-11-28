<?
if ( file_exists( $_SERVER["DOCUMENT_ROOT"]."/local/modules/askaron.parallel1c/admin/askaron_parallel1c_exchange_admin.php" ) )
{
	require_once($_SERVER["DOCUMENT_ROOT"]."/local/modules/askaron.parallel1c/admin/askaron_parallel1c_exchange_admin.php");
}
else
{
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/askaron.parallel1c/admin/askaron_parallel1c_exchange_admin.php");
}

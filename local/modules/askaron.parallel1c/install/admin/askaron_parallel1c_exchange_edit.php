<?
if ( file_exists( $_SERVER["DOCUMENT_ROOT"]."/local/modules/askaron.parallel1c/admin/askaron_parallel1c_exchange_edit.php" ) )
{
	require_once($_SERVER["DOCUMENT_ROOT"]."/local/modules/askaron.parallel1c/admin/askaron_parallel1c_exchange_edit.php");
}
else
{
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/askaron.parallel1c/admin/askaron_parallel1c_exchange_edit.php");
}

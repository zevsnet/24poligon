<?
if ( file_exists( $_SERVER["DOCUMENT_ROOT"]."/local/modules/askaron.parallel1c/admin/aaa_askaron_parallel1c_exchange.php" ) )
{
	require_once($_SERVER["DOCUMENT_ROOT"]."/local/modules/askaron.parallel1c/admin/aaa_askaron_parallel1c_exchange.php");
}
else
{
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/askaron.parallel1c/admin/aaa_askaron_parallel1c_exchange.php");
}
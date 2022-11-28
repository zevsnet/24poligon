<?

define('LOG_FILENAME', $_SERVER['DOCUMENT_ROOT'].'/ajax/js_error.txt');
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php'); 
$request = \Bitrix\Main\Context::getCurrent()->getRequest(); 
if ($request->get('data')) 
{
	AddMessage2Log(print_r($request->get('data'), true));
}
<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arTemplateParameters = array(
	'SHOW_DETAIL_LINK' => array(
		'NAME' => GetMessage('SHOW_DETAIL_LINK'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	),
	'TITLE' => array(
		'NAME' => GetMessage('TITLE'),
		'TYPE' => 'STRING',
		'DEFAULT' => '',
	),
	'SHOW_ALL_TITLE' => array(
		'NAME' => GetMessage('SHOW_ALL_TITLE'),
		'TYPE' => 'STRING',
		'DEFAULT' => '',
	),
	'SEND_MESSAGE_BUTTON_TEXT' => array(
		'NAME' => GetMessage('SEND_MESSAGE_BUTTON_TEXT'),
		'TYPE' => 'STRING',
		'DEFAULT' => '',
	),
);
?>

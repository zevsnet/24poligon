<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arTemplateParameters = array(
	'SHOW_BONUS_PAGE' => array(
		'NAME' => GetMessage('SHOW_BONUS_PAGE_TITLE'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
		"PARENT" => "BASE",
	),
	'PATH_TO_BONUS' => array(
		'NAME' => GetMessage('PATH_TO_BONUS_TITLE'),
		'TYPE' => 'STRING',
		'DEFAULT' => '={SITE_DIR."personal/bonus/"}',
		"COLS" => 25,
		"PARENT" => "URL_TEMPLATES",
	),
	
);

?>
<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if($arCurrentValues["TYPE_BLOCK"] != "type2")
	$arValues = array('bg' => GetMessage('BG_IMG'), 'sm' => GetMessage('SM_IMG'), 'md' => GetMessage('MD_IMG'), 'lg' => GetMessage('BIG_IMG'));
else
	$arValues = array('md' => GetMessage('MD_IMG'), 'sm' => GetMessage('SM_IMG'), 'sm no-img' => GetMessage('NO_IMG'));

$arTemplateParameters = array(
	'TYPE_BLOCK' => Array(
		'NAME' => GetMessage('TYPE_BLOCK_NAME'),
		'TYPE' => 'LIST',
		'VALUES' => array('type1' => GetMessage('TYPE_BLOCK_TYPE1'), 'type2' => GetMessage('TYPE_BLOCK_TYPE2')),
		'DEFAULT' => 'type1',
		'REFRESH' => 'Y',
	),
	'REVERCE_IMG_BLOCK' => array(
		'NAME' => ($arCurrentValues["TYPE_BLOCK"] != "type2" ? GetMessage('REVERCE_IMG_BLOCK_L_NAME') : GetMessage('REVERCE_IMG_BLOCK_R_NAME')),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
	),
	'TYPE_IMG' => Array(
		'NAME' => GetMessage('TYPE_IMG_NAME'),
		'TYPE' => 'LIST',
		'VALUES' => $arValues,
		'DEFAULT' => 'lg',
	),
	'REGION' => array(
		'NAME' => GetMessage('REGION'),
		'TYPE' => 'STRING',
		'DEFAULT' => '={$arRegion}',
	),
);
if($arCurrentValues["TYPE_BLOCK"] == "type2")
{
	$arTemplateParameters['TIZERS_IBLOCK_ID'] = array(
		'NAME' => GetMessage('TIZERS_IBLOCK_ID_NAME'),
		'TYPE' => 'STRING',
		'DEFAULT' => '',
	);
	$arTemplateParameters['COUNT_BENEFIT'] = array(
		'NAME' => GetMessage('COUNT_BENEFIT_NAME'),
		'TYPE' => 'STRING',
		'DEFAULT' => '4',
	);
	$arTemplateParameters['BENEFIT_COL'] = array(
		'NAME' => GetMessage('BENEFIT_COL_NAME'),
		'TYPE' => 'STRING',
		'DEFAULT' => '2',
	);
}
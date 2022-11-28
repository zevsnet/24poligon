<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Loader;

if(!Loader::includeModule("iblock"))
	return;

$arSectionFields = CIBlockParameters::GetSectionFieldCode(
	GetMessage("SORT"),
	"DATA_SOURCE",
	array()
);
$arSectionFields['MULTIPLE'] = 'N';
$arSectionFields['SIZE'] = '1';
$arSectionFields['DEFAULT'] = 'SORT';

$arSectionFields2 = $arSectionFields;
$arSectionFields2['NAME'] = GetMessage("SORT_2");
$arSectionFields2['DEFAULT'] = 'ID';

$arTemplateParameters = array(
	"TITLE_BLOCK_SHOW" => array(
		"NAME" => GetMessage("TITLE_BLOCK_SHOW"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => 'Y',
		"REFRESH" => "Y",
	),
	'SORT' => $arSectionFields,
	'SORT_ORDER' => array(
		"NAME" => GetMessage("SORT_ORDER"),
		'PARENT' => "DATA_SOURCE",
		"TYPE" => "LIST",
		"VALUES" => array(
			'ASC' => GetMessage("SORT_ASC"),
			'DESC' => GetMessage("SORT_DESC"),
		),
		'DEFAULT' => 'ASC',
	),
	'SORT_2' => $arSectionFields2,
	'SORT_ORDER_2' => array(
		"NAME" => GetMessage("SORT_ORDER_2"),
		'PARENT' => "DATA_SOURCE",
		"TYPE" => "LIST",
		"VALUES" => array(
			'ASC' => GetMessage("SORT_ASC"),
			'DESC' => GetMessage("SORT_DESC"),
		),
		'DEFAULT' => 'ASC',
	),
);

if($arCurrentValues["TITLE_BLOCK_SHOW"] != "N")
	{
		$arTemplateParameters["TITLE_BLOCK"] = array(
			"NAME" => GetMessage("TITLE_BLOCK_NAME"),
			"TYPE" => "STRING",
			"DEFAULT" => GetMessage("BLOCK_NAME"),
		);
		$arTemplateParameters["TITLE_BLOCK_ALL"] = array(
			"NAME" => GetMessage("TITLE_BLOCK_ALL_NAME"),
			"TYPE" => "STRING",
			"DEFAULT" => GetMessage("BLOCK_ALL_NAME"),
		);
		$arTemplateParameters["ALL_URL"] = array(
			"NAME" => GetMessage("ALL_URL_NAME"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		);
	}
?>

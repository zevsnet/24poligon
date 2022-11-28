<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("ASKARON_PARALLEL1C_CD_BCI1_NAME")." 20.0.0",
	"DESCRIPTION" => GetMessage("ASKARON_PARALLEL1C_CD_BCI1_DESCRIPTION"),
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "askaron_components",
		"NAME" => GetMessage("ASKARON_COMPONENTS_GROUP_NAME"),
		"CHILD" => array(
			"ID" => "askaron_parallel1c",
			"NAME" => GetMessage("ASKARON_PARALLEL1C_GROUP_NAME"),
		)
	),
);

?>
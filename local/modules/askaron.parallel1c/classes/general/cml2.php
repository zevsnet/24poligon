<?php
$arAskaronParallelsSettings = \Askaron\Parallel1c\Tools::getSettingsByPath();

$ver = $arAskaronParallelsSettings["CML2_IMPORT_VER"];
if ($ver == "last" || $ver == "20.0.200")
{
	require_once "cml2_ib20_0_200.php";

	class CAskaronParallelsIBlockCMLImport extends CAskaronParallelsIBlockCMLImport_IB20_0_200
	{

	}
}
else
{
	class CAskaronParallelsIBlockCMLImport extends CIBlockCMLImport
	{
	}
}
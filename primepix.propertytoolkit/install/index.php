<?
IncludeModuleLangFile(__FILE__);

class primepix_propertytoolkit extends CModule {

	var $MODULE_ID  = 'primepix.propertytoolkit'; 
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $strError   = '';
	
	function __construct()
	{
		$arModuleVersion = array();
		include(dirname(__FILE__) . "/version.php");

		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

		$this->MODULE_NAME = GetMessage("primepix.propertytoolkit_MODULE_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("primepix.propertytoolkit_MODULE_DESC");

		$this->PARTNER_NAME = GetMessage("primepix.propertytoolkit_PARTNER_NAME");
		$this->PARTNER_URI = GetMessage("primepix.propertytoolkit_PARTNER_URI");
	}

	function InstallEvents()
	{
		return TRUE;
	}

	function UnInstallEvents()
	{
		return TRUE;
	}

	function InstallFiles($arParams = array())
	{
		CopyDirFiles($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.$this->MODULE_ID.'/install/js', $_SERVER['DOCUMENT_ROOT'].'/bitrix/js/'.$this->MODULE_ID.'/', $ReWrite = TRUE, $Recursive = TRUE);
		CopyDirFiles($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.$this->MODULE_ID.'/install/admin', $_SERVER['DOCUMENT_ROOT'].'/bitrix/admin/', $ReWrite = TRUE, $Recursive = TRUE);
		return TRUE;
	}

	function UnInstallFiles()
	{
		DeleteDirFilesEx('/bitrix/js/'.$this->MODULE_ID);
		DeleteDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/" .$this->MODULE_ID. "/install/admin", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin");
		return TRUE;
	}

	function DoInstall()
	{
		global $APPLICATION;
		$this->InstallFiles();
		RegisterModule($this->MODULE_ID);
	}

	function DoUninstall()
	{
		global $APPLICATION;
		UnRegisterModule($this->MODULE_ID);
		$this->UnInstallFiles();
	}
}

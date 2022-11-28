<?
IncludeModuleLangFile(__FILE__);

if (class_exists('askaron_parallel1c')) return;

class askaron_parallel1c extends CModule
{  
	var $MODULE_ID = "askaron.parallel1c";
	public $MODULE_VERSION;
	public $MODULE_VERSION_DATE;
	public $MODULE_NAME;
	public $MODULE_DESCRIPTION;
	public $PARTNER_NAME;
	public $PARTNER_URI;
	public $MODULE_GROUP_RIGHTS = 'Y';
	// first modules '8.0.7', 2009-06-29
	// htmlspecialcharsbx was added in '11.5.9', 2012-09-13
	
	public $NEED_MAIN_VERSION = '8.0.7';
	public $NEED_MODULES = array("iblock");

	public $MY_DIR = "bitrix";
	
	public function __construct()
	{
		$arModuleVersion = array();

		$path = str_replace('\\', '/', __FILE__);
		$dir = substr($path, 0, strlen($path) - strlen('/index.php'));
		include($dir.'/version.php');

		$check_last = "/local/modules/".$this->MODULE_ID."/install/index.php";
		$check_last_len = strlen($check_last);

		if ( substr($path, -$check_last_len) == $check_last )
		{
			$this->MY_DIR = "local";
		}

		if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion))
		{
			$this->MODULE_VERSION = $arModuleVersion['VERSION'];
			$this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
		}
		
		// !Twice! Marketplace bug. 2013-03-13
		$this->PARTNER_NAME = "Askaron Systems";
		$this->PARTNER_NAME = GetMessage("ASKARON_PARALLEL1C1_PARTNER_NAME");
		$this->PARTNER_URI = 'http://askaron.ru/';

		$this->MODULE_NAME = GetMessage('ASKARON_PARALLEL1C1_MODULE_NAME');
		$this->MODULE_DESCRIPTION = GetMessage('ASKARON_PARALLEL1C1_MODULE_DESCRIPTION');

		//$this->MODULE_SORT = 1;
	}

	public function DoInstall()
	{
		global $APPLICATION;
		global $DB;
		
		global $askaron_parallel1c_global_errors;
		$askaron_parallel1c_global_errors = array();

		if (is_array($this->NEED_MODULES) && !empty($this->NEED_MODULES))
			foreach ($this->NEED_MODULES as $module)
				if (!IsModuleInstalled($module))
					$askaron_parallel1c_global_errors[] = GetMessage('ASKARON_PARALLEL1C1_NEED_MODULES', array('#MODULE#' => $module));
				
		if ( strlen($this->NEED_MAIN_VERSION) > 0  && version_compare(SM_VERSION, $this->NEED_MAIN_VERSION) < 0)
		{
			$askaron_parallel1c_global_errors[] = GetMessage( 'ASKARON_PARALLEL1C1_NEED_RIGHT_VER', array('#NEED#' => $this->NEED_MAIN_VERSION) );
		}
		
		if ( strtolower($DB->type) != 'mysql' )
		{
			 $askaron_parallel1c_global_errors[] = GetMessage('ASKARON_PARALLEL1C1_ONLY_MYSQL_ERROR');
		}
		
		if ( count( $askaron_parallel1c_global_errors ) == 0 )
		{
			$connection = \Bitrix\Main\Application::getConnection();
			$isTableExists = $connection->isTableExists( "b_askaron_parallel1c_exchange" );
			$bNewDB = !$isTableExists;

			if ( $this->InstallDB() )
			{
				$this->InstallFiles();

				$this->InstallEvents();

				RegisterModule("askaron.parallel1c");


				if ($bNewDB)
				{
					\Bitrix\Main\Loader::includeModule( $this->MODULE_ID );
					\Askaron\Parallel1c\Tools::installDefaultSettings();
				}
			}
			else
			{
				$askaron_parallel1c_global_errors[] = GetMessage('ASKARON_PARALLEL1C1_INSTALL_TABLE_ERROR');
			};
			
		}
		
		$APPLICATION->IncludeAdminFile( GetMessage("ASKARON_PARALLEL1C1_INSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/".$this->MY_DIR."/modules/".$this->MODULE_ID."/install/step.php");
		return true;
	}

	public function DoUninstall()
	{
		global $APPLICATION, $step;
		$RIGHT = $APPLICATION->GetGroupRight( $this->MODULE_ID );
		if ($RIGHT>="W")
		{
			$step = IntVal($step);
			if ($step < 2)
			{
				$APPLICATION->IncludeAdminFile(GetMessage("ASKARON_PARALLEL1C1_UNINSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"] . "/" . $this->MY_DIR . "/modules/" . $this->MODULE_ID . "/install/unstep1.php");
			}
			elseif ($step == 2)
			{
				// Tables
				if ($_REQUEST["savedata"] != "Y")
				{

					// remove items with events
					\Bitrix\Main\Loader::includeModule( $this->MODULE_ID );
					$res = \Askaron\Parallel1c\ExchangeTable::getList(
						array(
							"select" => array( "ID" ),
						)
					);
					if ( $arFields = $res->fetch() )
					{
						\Askaron\Parallel1c\ExchangeTable::delete( $arFields["ID"] );
					}

					$this->UnInstallDB();
					//DeleteDirFilesEx("/bitrix/tools/askaron_parallel1c_settings.php");
				}

				//message types and templates
//				if($_REQUEST["save_templates"] != "Y")
//				{
//					$this->UnInstallEvents();
//				}

				$this->UnInstallFiles();

				//UnRegisterModuleDependences("main", "OnPageStart", $this->MODULE_ID, "CAskaronParallel1c", "OnPageStart");

				UnRegisterModule('askaron.parallel1c');

				$APPLICATION->IncludeAdminFile(GetMessage("ASKARON_PARALLEL1C1_UNINSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"] . "/" . $this->MY_DIR . "/modules/" . $this->MODULE_ID . "/install/unstep2.php");
				return true;
			}
		}
	}

	function InstallFiles($arParams = array())
	{
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/".$this->MY_DIR."/modules/".$this->MODULE_ID."/install/admin/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin/");
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/".$this->MY_DIR."/modules/".$this->MODULE_ID."/install/themes/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/themes/", true, true);
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/".$this->MY_DIR."/modules/".$this->MODULE_ID."/install/components/askaron/",	$_SERVER["DOCUMENT_ROOT"]."/bitrix/components/askaron/", true, true);//component

		CheckDirPath( $_SERVER["DOCUMENT_ROOT"]."/upload/1c_catalog_copy_askaron_parallel1c/" );
	}

	function UnInstallFiles( $arParams = array() )
	{
		DeleteDirFiles($_SERVER["DOCUMENT_ROOT"]."/".$this->MY_DIR."/modules/".$this->MODULE_ID."/install/admin/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin");		
		DeleteDirFiles($_SERVER["DOCUMENT_ROOT"]."/".$this->MY_DIR."/modules/".$this->MODULE_ID."/install/themes/.default/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/themes/.default");//css
		DeleteDirFilesEx("/bitrix/themes/.default/icons/".$this->MODULE_ID."/");//icons

		//DeleteDirFilesEx("/bitrix/components/askaron/askaron.parallel1c.catalog.import.1c/");
		DeleteDirFilesEx("/bitrix/components/askaron/askaron.parallel1c.catalog.import.1c.17.6.3/");
		DeleteDirFilesEx("/bitrix/components/askaron/askaron.parallel1c.catalog.import.1c.20.0.0/");

		DeleteDirFilesEx( "/upload/1c_catalog_copy_askaron_parallel1c/" );
	}
	
	function InstallDB()
	{
		$result = true;

		global $APPLICATION, $DB;

		if (!$DB->Query("SELECT 'x' FROM b_askaron_parallel1c_exchange", true)) $EMPTY = "Y"; else $EMPTY = "N";

		$errors = false;

		if ($EMPTY=="Y")
		{
			$path = $_SERVER["DOCUMENT_ROOT"]."/".$this->MY_DIR."/modules/".$this->MODULE_ID."/install/db/".strtolower($DB->type)."/install.sql";
			$errors = $DB->RunSQLBatch( $path );
		}

		if (!empty( $errors ))
		{
			$APPLICATION->ThrowException( implode("", $errors) );
			$result = false;
		}

		return $result;
	}

	function UnInstallDB( )
	{
		// TODO: remove temporary files and tables

		global $APPLICATION, $DB;

		$errors = $DB->RunSQLBatch( $_SERVER["DOCUMENT_ROOT"]."/".$this->MY_DIR."/modules/".$this->MODULE_ID."/install/db/".strtolower($DB->type)."/uninstall.sql" );


		if (!empty($errors))
		{
			$APPLICATION->ThrowException(implode("", $errors));
			return false;
		}

		return true;
	}
	
	function InstallEvents()
	{
//		$dbEvent = CEventMessage::GetList($b="ID", $order="ASC", Array("EVENT_NAME" => 'ASKARON_PARALLEL1C1_NEW_REVIEW'));
//		if(!($dbEvent->Fetch()))
//		{
//
//			$et=new CEventType;
//			$et->Add(array
//			(
//				"LID"=>LANG,
//				"EVENT_NAME"=>"ASKARON_PARALLEL1C1_NEW_REVIEW",
//				"NAME"=>GetMessage("ASKARON_PARALLEL1C1_NEW_REVIEW_EVENT_NAME"),
//				"DESCRIPTION"=>GetMessage("ASKARON_PARALLEL1C1_NEW_REVIEW_EVENT_DESCRIPTION")
//			));
//
//			$arSites=array();
//			$sites=CSite::GetList(($b=""), ($o=""), Array() );
//			while ($site=$sites->Fetch())
//			{
//				$arSites[]=$site["LID"];
//			}
//
//			if( count($arSites)>0 )
//			{
//				$emess=new CEventMessage;
//				$emess->Add(array(
//					"ACTIVE"=>"Y",
//					"EVENT_NAME"=>"ASKARON_PARALLEL1C1_NEW_REVIEW",
//					"LID"=>$arSites,
//					"EMAIL_FROM"=>"#DEFAULT_EMAIL_FROM#",
//					"EMAIL_TO"=>"#EMAIL_TO#",
//					"SUBJECT"=>"#SUBJECT#",
//					"MESSAGE"=>"#MESSAGE#",
//					"BODY_TYPE"=>"text",
//				));
//			}
//		}
	}
	
	function UnInstallEvents()
	{
//		$eventM=new CEventMessage;
//		$dbEvent=CEventMessage::GetList($b="ID", $order="ASC", Array("EVENT_NAME"=>"ASKARON_PARALLEL1C1_NEW_REVIEW"));
//		while ($arEvent=$dbEvent->Fetch())
//		{
//			$eventM->Delete($arEvent["ID"]);
//		}
//
//		CEventType::Delete("ASKARON_PARALLEL1C1_NEW_REVIEW");
	}	
}
?>
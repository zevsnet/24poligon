<?
namespace Askaron\Parallel1c;

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

class Tools
{
	static private $MODULE_ID="askaron.parallel1c";

	static public function installDefaultSettings()
	{
		$list = array();
		$path = $_SERVER["DOCUMENT_ROOT"].getLocalPath("modules/".self::$MODULE_ID."/lang/ru/default_settings.php");
		//include( $path ); file can be cached by PHP

		$code = file_get_contents( $path );
		$code=trim($code);
		$code=trim($code, "<?>");

		eval($code); // set $list variable

		foreach ( $list as $arItem )
		{
			\Askaron\Parallel1c\ExchangeTable::add($arItem);
		}
	}

	static public function removeTmpByCode( $code )
	{
		if ( strlen($code) > 0 )
		{
			$table_name = "b_xml_tree_askaron_parallel1c_".$code;
			$catalog_name = "1c_catalog_askaron_parallel1c_".$code;

			$connection = \Bitrix\Main\Application::getConnection();
			if ( $connection->isTableExists( $table_name ) )
			{
				$connection->dropTable($table_name);
			}

			DeleteDirFilesEx("/upload/".$catalog_name."/");
		}
	}

	static public function getSettingsByPath()
	{
		$arResult = array();

		static $arCache = false;
		if ( is_array( $arCache ) )
		{
			$arResult = $arCache;
		}
		else
		{
			$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
			$path = $request->getRequestedPage();

			$res = \Askaron\Parallel1c\ExchangeTable::getList(
				array(
					"filter" => array(
						"=PATH" => $path
					),
					"limit" => 1,
				)
			);
			if ( $arFields = $res->fetch() )
			{

				$arFields["ALLOW_DEACTIVATE_IBLOCKS_ARRAY_KEYS"] = array();

//				7.0.0 - new format
//				$arFields["ALLOW_DEACTIVATE_IBLOCKS"] = trim( $arFields["ALLOW_DEACTIVATE_IBLOCKS"] );
//				if ( strlen( $arFields["ALLOW_DEACTIVATE_IBLOCKS"] ) > 0 )
//				{
//					$arId = explode(",", $arFields["ALLOW_DEACTIVATE_IBLOCKS"]);
//					foreach ($arId as $id)
//					{
//						$id = intval($id);
//						if ( $id > 0 )
//						{
//							$arFields["ALLOW_DEACTIVATE_IBLOCKS_ARRAY_KEYS"][ $id ] = true;
//						}
//					}
//				}

				if ( is_array( $arFields["ALLOW_DEACTIVATE_IBLOCKS"] ) )
				{
					foreach (  $arFields["ALLOW_DEACTIVATE_IBLOCKS"] as $key=>$value )
					{
						$arFields["ALLOW_DEACTIVATE_IBLOCKS_ARRAY_KEYS"][ $value ] = true;
					}
				}

				$arResult = $arFields;
			}

			$arCache = $arResult;
		}

		return $arResult;
	}
}

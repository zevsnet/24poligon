<?
IncludeModuleLangFile(__FILE__);

class CAskaronParallel1c
{
	public static function IsNewElement( $xml_id, $IBLOCK_ID )
	{
		$result = true; // new

		$res = \Bitrix\Iblock\ElementTable::getList(array(
			'filter' => array(
				'IBLOCK_ID' => $IBLOCK_ID,
				'=XML_ID' => $xml_id,
			),
			"limit" => 1,
			'select' => array("ID"),
		));

		if ( $arFields = $res->fetch() )
		{
			$result = false;
		}

		return $result;
	}

	public static function GetCatalogImportVersions()
	{
		$arResult = array(
			"last" => array(
				"CODE" => "last",
				"SORT" => 10000,
				"NAME" => GetMessage( "askaron_parallel1c_include_last_version" ),
			),
			"20.0.0" => array(
				"CODE" => "20.0.0",
				"SORT" => 20,
				"NAME" =>  GetMessage( "askaron_parallel1c_include_founded" )." catalog 20.0.0"
			),
			"17.6.3" => array(
				"CODE" => "17.6.3",
				"SORT" => 10,
				"NAME" => GetMessage( "askaron_parallel1c_include_founded" )." catalog 17.6.3"
			),
		);

		return $arResult;
	}

	public static function GetCml2ImportVersions()
	{
		$arResult = array(
			"bitrix" => array(
				"CODE" => "bitrix",
				"SORT" => 20000,
				"NAME" => GetMessage( "askaron_parallel1c_include_cml2_bitrix_version" ),
			),
			"last" => array(
				"CODE" => "last",
				"SORT" => 10000,
				"NAME" => GetMessage( "askaron_parallel1c_include_cml2_last_version" ),
			),
			"20.0.200" => array(
				"CODE" => "20.0.200",
				"SORT" => 20,
				"NAME" =>  GetMessage( "askaron_parallel1c_include_20_0_200" ),
			),
		);

		return $arResult;
	}
}

// my cml2 class
$arClasses = array(
	"CAskaronParallelsIBlockCMLImport" => "classes/general/cml2.php",
);

\Bitrix\Main\Loader::registerAutoLoadClasses("askaron.parallel1c", $arClasses);

?>
<?
namespace Askaron\Parallel1c;

class Import
{
	// $ibPrefix: "1c_catalog-"
	public static function clearFileNewOnly( $ABS_FILE_NAME, $ibPrefix = "" )
	{
		//lo($ibPrefix);

		$arResult = array(
			//"finished" => false,
			"message" => "",
			"error_message" => "",
		);


		//lo( $path, '$path' );
		\Bitrix\Main\Loader::includeModule("iblock");

		// 1. Парсим файлик на части
		$arParse = self::parseImportFile($ABS_FILE_NAME);
		if ( strlen( $arParse["ERROR"] ) > 0 )
		{
			$arResult["error_message"] = $arParse["ERROR"];
		}

		$IBLOCK_ID = "";


		// 2. Узнаём из файлика инфоблок
		if ( strlen($arResult["error_message"]) <= 0 )
		{
			$IBLOCK_XML_ID = self::getIblockXmlId( $arParse );

			if ( strlen( $IBLOCK_XML_ID ) > 0 )
			{
				$res = \Bitrix\Iblock\IblockTable::getList(array(
					'filter' => array('=XML_ID' => $ibPrefix.$IBLOCK_XML_ID ),
					'select' => array("ID"),
				));
				if ( $arFields = $res->fetch() )
				{
					$IBLOCK_ID = $arFields["ID"];
				}
				else
				{
					$arResult["error_message"] = "Инфоблок не найден";
				}
			}
			else
			{
				$arResult["error_message"] = "Внешний код инфоблока не найден";
			}
		}

		//dd($arParse, '$arFileParts');
		//dd($IBLOCK_ID, '$IBLOCK_ID');


		// 3. Пытаемся понять какие из наших товаров НОВЫЕ
		$arNewElementKeys = array();

		if ( strlen($arResult["error_message"]) <= 0 )
		{
			if ($arParse["ELEMENTS_STR"] )
			{
				require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/classes/general/xml.php');

				foreach ($arParse["ELEMENTS_STR"] as $key => $str )
				{
					$xml = new \CDataXML();
					$xml->LoadString($str);

					$xml_id = $xml->SelectNodes("/Товар/Ид")->textContent();
					//lo($xml_id, '$xml_id');

					if ( strlen($xml_id) > 0 )
					{

						if (  \CAskaronParallel1c::IsNewElement( $xml_id, $IBLOCK_ID ) )
						{
							$arNewElementKeys[$key] = $key;
						}
					}
					else
					{
						$arResult["error_message"] = "Ошибка разбора XML. Товар без Ид";
						break;
					}
				}
			}
		}

		//lo($arResult);

		if ( strlen($arResult["error_message"]) <= 0 )
		{
			if ( count($arParse["ELEMENTS_STR"]) > 0 )
			{
				// Сборка файла, если товары в нём вообще были.
				// Бывает что есть сначала файлы с выгрузкой метаданных, а товаров в них нет.

				//$arFileInfo = pathinfo($ABS_FILE_NAME);
				//$file = $arFileInfo["dirname"]."/".$arFileInfo["filename"]."test.".$arFileInfo["extension"];

				$file = $ABS_FILE_NAME;

				file_put_contents($file, $arParse["BEFORE_STR"]);

				foreach ($arNewElementKeys as $key => $value)
				{
					file_put_contents($file, $arParse["ELEMENTS_STR"][$key], FILE_APPEND);
				}

				file_put_contents($file, $arParse["AFTER_STR"], FILE_APPEND);

				$arResult["message"] = "Файл импорта обновлён. Было всего товаров " . count($arParse["ELEMENTS_STR"]) . ". Осталось новых товаров " . count($arNewElementKeys) . ".";
			}
		}


		return $arResult;
	}

	public static function parseImportFile( $ABS_FILE_NAME )
	{
		$arResult = array(
			"ABS_FILE_NAME" => $ABS_FILE_NAME, // файл
			"STR" => "", // содержимое файла
			"BEFORE_STR" => "", // часть файла до товаров
			"MIDDLE_STR" => "", // товары
			"AFTER_STR" => "", // после товаров
			"ELEMENTS_STR" => array(), // товары по отдельности.
			"ERROR" => "", // ошибка.
		);

		if ( file_exists( $ABS_FILE_NAME ) )
		{
			$arResult["STR"] = file_get_contents( $ABS_FILE_NAME );

			if ( strpos($arResult["STR"], "<?xml" ) === 0 || strpos($arResult["STR"], "<?xml" ) === 1 )
			{
				// Наиболее быстрый и простой способ поделить файл на части
				$begin = strpos( $arResult["STR"], '<Товары>' );
				$end = strpos( $arResult["STR"], '</Товары>' );

				if ($begin !== false && $end !== false )
				{
					$start = $begin + 8;
					$length = $end - $start;

					$arResult[ "BEFORE_STR" ] = substr( $arResult["STR"], 0, $start );
					$arResult[ "MIDDLE_STR" ] = substr( $arResult["STR"], $start, $length );
					$arResult[ "AFTER_STR" ] = substr( $arResult["STR"], $end );
				}

				if ( strlen( $arResult[ "MIDDLE_STR" ] ) > 0 )
				{
					$res = preg_match_all('|<Товар>.*?</Товар>|s', $arResult[ "MIDDLE_STR" ], $arElements, PREG_SET_ORDER );

					if ( $res !== false && is_array( $arElements ) )
					{
						foreach ($arElements as $arElement)
						{
							if ( isset($arElement[0]) )
							{
								$arResult["ELEMENTS_STR"][] = $arElement[0];
							}
						}
					}
				}

// 				TODO. А можно было бы использовать нормальную медленную библиотеку, но формат выгрузки не менялся никогда.
//				$myXML = new \XMLReader();
//				$myXML->xml($ABS_FILE_NAME);
//
//				while ($myXML->read())
//				{
//					if ($myXML->nodeType == \XMLReader::ELEMENT)
//					{
//						$tag = $myXML->name; //make $tag contain the name of the tag
//						dd($tag);
//					}
//				}
//				$myXML->close();
//
//				dd($arElemetns);
			}
			else
			{
				$arResult["ERROR"] = "Файл не xml";
			}
		}
		else
		{
			$arResult["ERROR"] = "Файл не найден";
		}


		return $arResult;
	}


	private static function getIblockXmlId( $arParse )
	{

		$str = $arParse["STR"];

		$result = "";

		$begin = strpos( $str, '<ИдКлассификатора>' );
		$end = strpos( $str, '</ИдКлассификатора>' );
		if ($begin !== false && $end !== false )
		{
			$start = $begin + 18;
			$length = $end - $start;

			$result = substr( $str, $start, $length );
		}

		return $result;
	}
}

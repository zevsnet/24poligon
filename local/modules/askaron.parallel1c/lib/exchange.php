<?
namespace Askaron\Parallel1c;

use Bitrix\Main\Entity;
use Bitrix\Main\Type;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class ExchangeTable extends Entity\DataManager
{
	private static $arItemBeforeDelete = array();

	public static function getTableName()
    {
        return 'b_askaron_parallel1c_exchange';
    }
	
    public static function getMap()
    {
		//global $DB;
		//new Bitrix\Main\Entity\IntegerField;
		
		$fieldsMap = array(
			'ID' => array(
				'data_type' => 'integer',
				'primary' => true,
				'autocomplete' => true,
				'title' => "ID",
			),
//			'ACTIVE' => array(
//				'data_type' => 'boolean',
//				'values' => array("N", "Y"),
//				'default_value' => "Y",
//				'title' => GetMessage("ASKARON_PARALLELS1C_HANDLER_TABLE_ACTIVE"),
//			),
			'CODE' => array(
				'data_type' => 'text',
				'title' => GetMessage("ASKARON_PARALLELS1C_HANDLER_TABLE_CODE"),
			),
//			'SORT' => array(
//				'data_type' => 'integer',
//				'title' => GetMessage("ASKARON_PARALLELS1C_HANDLER_TABLE_SORT"),
//			),
			'NAME' => array(
				'data_type' => 'text',
				'title' => GetMessage("ASKARON_PARALLELS1C_HANDLER_TABLE_CODE"),
			),
			'PATH' => array(
				'data_type' => 'text',
				'title' => GetMessage("ASKARON_PARALLELS1C_HANDLER_TABLE_CODE"),
			),
			'DISALLOW_ALL' => array(
				'data_type' => 'boolean',
				'values' => array("N", "Y"),
				'default_value' => "N",
				'title' => "DISALLOW_ALL",
			),
			'PARAMS' => array(
				'data_type' => 'text',
				'title' => GetMessage("ASKARON_HANDLERS1C_HANDLER_TABLE_PARAMS"),
				'serialized' => true
			),
			'MODIFIED_BY' => array(
				'data_type' => 'integer',
				'title' => GetMessage("ASKARON_PARALLELS1C_HANDLER_TABLE_MODIFIED_BY"),
				'default_value' => function (){
					global $USER;
					if ( is_object($USER) && $USER instanceof \CUser && $USER->IsAuthorized() )
					{
						return $USER->GetID();
					}
				},
			),
			'MODIFIED_BY_USER' => array(
				'data_type' => 'Bitrix\Main\User',
				'reference' => array('=this.MODIFIED_BY' => 'ref.ID')
			),
			"TIMESTAMP_X" => new Entity\DatetimeField( 'TIMESTAMP_X', array(
//				'default_value' => new \Bitrix\Main\DB\SqlExpression(
//					\Bitrix\Main\Application::getConnection()->getSqlHelper()->getCurrentDateTimeFunction()
//				),
				'default_value' => new Type\DateTime(),
				'title' => GetMessage("ASKARON_PARALLELS1C_HANDLER_TABLE_TIMESTAMP_X"),
			)),
			'IMPORT_XML_NEW_ONLY' => array(
				'data_type' => 'boolean',
				'values' => array("N", "Y"),
				'default_value' => "N",
				'title' => "IMPORT_XML_NEW_ONLY",
			),
			'DISALLOW_IMPORT_XML_STEP' => array(
				'data_type' => 'boolean',
				'values' => array("N", "Y"),
				'default_value' => "N",
				'title' => "DISALLOW_IMPORT_XML_STEP",
			),
			'DISALLOW_OFFERS_XML_STEP' => array(
				'data_type' => 'boolean',
				'values' => array("N", "Y"),
				'default_value' => "N",
				'title' => "DISALLOW_OFFERS_XML_STEP",
			),
			'DISALLOW_PRICES_XML_STEP' => array(
				'data_type' => 'boolean',
				'values' => array("N", "Y"),
				'default_value' => "N",
				'title' => "DISALLOW_PRICES_XML_STEP",
			),
			'DISALLOW_RESTS_XML_STEP' => array(
				'data_type' => 'boolean',
				'values' => array("N", "Y"),
				'default_value' => "N",
				'title' => "DISALLOW_RESTS_XML_STEP",
			),
			'DISALLOW_DEACTIVATE_STEP' => array(
				'data_type' => 'boolean',
				'values' => array("N", "Y"),
				'default_value' => "N",
				'title' => GetMessage("ASKARON_PARALLELS1C_HANDLER_DISALLOW_DEACTIVATE_STEP"),
			),
			'ALLOW_DEACTIVATE_IBLOCKS' => array(
				'data_type' => 'text',
				'title' => "ALLOW_DEACTIVATE_IBLOCKS",
				'serialized' => true
			),
			'COMPONENT_IMPORT_VER' => array(
				'data_type' => 'text',
				'default_value' => "last",
				'title' => "COMPONENT_IMPORT_VER",
			),
			'CML2_IMPORT_VER' => array(
				'data_type' => 'text',
				'default_value' => "bitrix",
				'title' => "CML2_IMPORT_VER",
			),
		);

		return $fieldsMap;
    }

	public static function onBeforeUpdate( Entity\Event $event)
	{
		$result = new \Bitrix\Main\Entity\EventResult;
		$data = $event->getParameter('fields');

		$modifyFieldList = array();

		// MODIFIED_BY
		if ( !array_key_exists( "MODIFIED_BY", $data) )
		{
			$currentUserId = null;

			global $USER;
			if ( is_object($USER) && $USER instanceof \CUser && $USER->IsAuthorized() )
			{
				$currentUserId = $USER->GetID();
			}

			$modifyFieldList["MODIFIED_BY"] = $currentUserId;
		}

		// TIMESTAMP_X - not null !
		if ( !isset( $data["TIMESTAMP_X"] ) || !is_object( $data["TIMESTAMP_X"] ) )
		{
			$modifyFieldList["TIMESTAMP_X"] = new \Bitrix\Main\Type\DateTime();
		}

		if ($modifyFieldList)
		{
			$result->modifyFields($modifyFieldList);
		}

//		$result->addError(new Entity\EntityError(
//			'test error'
//		));

		return $result;
	}


	public static function OnAfterAdd ( Entity\Event $event )
	{
//		$arParameters =  $event->getParameters();
//
//		$ID = $arParameters["id"];
//		if ( $ID > 0 )
//		{
//			Init::generateInitClassFile($ID);
//		}
//
//		Init::generateInitFile();
	}

	public static function OnAfterUpdate ( Entity\Event $event )
	{
//		$arParameters =  $event->getParameters();
//
//		$ID = $arParameters["id"]["ID"];
//		if ( $ID > 0 )
//		{
//			Init::generateInitClassFile($ID);
//		}
//
//		Init::generateInitFile();
	}

	public static function OnBeforeDelete ( Entity\Event $event )
	{
		$arParameters =  $event->getParameters();

		$ID = $arParameters["id"]["ID"];
		if ( $ID > 0 )
		{
			$result = \Askaron\Parallel1c\ExchangeTable::getById( $ID );
			if ( $arFields = $result->fetch() )
			{
				self::$arItemBeforeDelete = $arFields;
			}
		}
	}


	public static function OnAfterDelete ( Entity\Event $event )
	{
		$arParameters =  $event->getParameters();

		$ID = $arParameters["id"]["ID"];
		if ( $ID > 0 && $ID == self::$arItemBeforeDelete[ "ID" ] )
		{
			\Askaron\Parallel1c\Tools::removeTmpByCode( self::$arItemBeforeDelete[ "CODE" ] );
		}
	}
}


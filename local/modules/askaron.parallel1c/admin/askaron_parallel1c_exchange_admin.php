<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

require_once( dirname(__FILE__)."/../prolog.php" );

use Bitrix\Main\Localization\Loc;
use \Bitrix\Main\UI\AdminPageNavigation;

Loc::loadMessages(__FILE__);

$module_id = "askaron.parallel1c";

// messages
$install_status=CModule::IncludeModuleEx($module_id);

// demo expired (3)
if( $install_status==3 )
{
	$APPLICATION->SetTitle(GetMessage("askaron_parallel1c_exchange_admin_title"));
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

	CAdminMessage::ShowMessage(
		Array(
			"TYPE"=>"ERROR",
			"MESSAGE"=>GetMessage("askaron_parallel1c_prolog_status_demo_expired"),
			"DETAILS"=>GetMessage("askaron_parallel1c_prolog_buy_html"),
			"HTML"=>true
		)
	);
}
else
{

	$RIGHT=$APPLICATION->GetGroupRight($module_id);
	if( $RIGHT=="D" )
	{
		$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
	}

	$strError = "";
	$strWarning = "";
	$strOk = "";



//	if (!CModule::IncludeModule('forum'))
//	{
//		$strError = GetMessage("askaron_parallel1c_forum_not_installed");
//	}
//	elseif (!CModule::IncludeModule('iblock'))
//	{
//		$strError = GetMessage("askaron_parallel1c_iblock_not_installed");
//	}


	$tableId = "tbl_askaron_parallel1c_exchange_admin";
	$oSort = new  \CAdminSorting($tableId, "ID", "asc", 'by', 'order');
	$lAdmin = new \CAdminList($tableId, $oSort);

	$arFilter = array();

	$queryOrder = array(
		$by => $order
	);

	if(($arID = $lAdmin->GroupAction()) && $RIGHT>="W")
	{
		if($_REQUEST['action_target']=='selected')
		{
			// select all

//			$arItems=\Askaron\Parallel1c\Settings::GetArray();
//			$arID =  array();
//			foreach ( $arItems as $arItem )
//			{
//				$arID[] = $arItem["CODE"];
//			}


			$queryObject = \Askaron\Parallel1c\ExchangeTable::getList(array(
				'select' => array(
					"ID",
				),
				'filter' => $arFilter,
				'order' => $queryOrder,
			));

			$arID =  array();
			while ( $arFields =  $queryObject->fetch() )
			{
				$arID[] = $arFields["ID"];
			}
		}

		if ($arID)
		{
			@set_time_limit(0);
		}

		foreach($arID as $ID)
		{
			$ID = trim($ID);

			if(strlen($ID)<=0)
				continue;



			switch($_REQUEST['action'])
			{
				case "delete":

					$result = \Askaron\Parallel1c\ExchangeTable::delete($ID);
					if (!$result->isSuccess())
						$lAdmin->AddGroupError(implode('<br>', $result->getErrorMessages()), $result);
					unset( $result );

					break;

//					@set_time_limit(0);
//					$DB->StartTransaction();
//					if(!CRubric::Delete($ID))
//					{
//						$DB->Rollback();
//						$lAdmin->AddGroupError(GetMessage("rub_del_err"), $ID);
//					}
//					$DB->Commit();
//					break;

//				case "activate":
//				case "deactivate":
//					$fields = array(
//						'ACTIVE' => ($_REQUEST['action'] == 'activate' ? 'Y' : 'N')
//					);
//					$result = \Askaron\Parallel1c\HandlerTable::update($ID, $fields);
//					if (!$result->isSuccess())
//						$lAdmin->AddGroupError( implode('<br>', $result->getErrorMessages()), $ID);
//					unset( $result );
//					unset( $fields );
//					break;


//					$cData = new CRubric;
//					if(($rsData = $cData->GetByID($ID)) && ($arFields = $rsData->Fetch()))
//					{
//						$arFields["ACTIVE"]=($_REQUEST['action']=="activate"?"Y":"N");
//						if(!$cData->Update($ID, $arFields))
//							$lAdmin->AddGroupError(GetMessage("rub_save_error").$cData->LAST_ERROR, $ID);
//					}
//					else
//						$lAdmin->AddGroupError(GetMessage("rub_save_error")." ".GetMessage("rub_no_rubric"), $ID);
//					break;
			}
		}
	}

	$headers = array(
		array(
			"id"        => "ID",
			"content"   => "ID",
			"sort"      => "ID",
			"default"   => true
		),
		array(
			"id"        => "CODE",
			"content"   => GetMessage("askaron_parallel1c_exchange_admin_code"),
			"sort"      => "CODE",
			"default"   => true
		),
		array(
			"id"        => "NAME",
			"content"   => GetMessage("askaron_parallel1c_exchange_admin_name"),
			"sort"      => "NAME",
			"default"   => true,
			//'align' 	=> 'right',
			//"title"		=> GetMessage('askaron_parallel1c_exchange_admin_sort_name'),
		),
		array(
			"id"        => "PATH",
			"content"   => GetMessage("askaron_parallel1c_exchange_admin_path"),
			"sort"      => "PATH",
			"default"   => true,
			//'align' 	=> 'right',
			//"title"		=> GetMessage('askaron_parallel1c_exchange_admin_sort_name'),
		),
		array(
			"id"        => "MODIFIED_BY",
			"content"   => GetMessage("askaron_parallel1c_exchange_admin_modified_by"),
			//"sort"      => "MODIFIED_BY",
			"default"   => true
		),
		array(
			"id"        => "TIMESTAMP_X",
			"content"   => GetMessage("askaron_parallel1c_exchange_admin_timestamp_x"),
			"sort"      => "TIMESTAMP_X",
			"default"   => true
		),
		array(
			"id"        => "COMPONENT_IMPORT_VER",
			"content"   => GetMessage("askaron_parallel1c_exchange_admin_component_import_ver"),
			"sort"      => "COMPONENT_IMPORT_VER",
			"default"   => true
		),
		array(
			"id"        => "CML2_IMPORT_VER",
			"content"   => GetMessage("askaron_parallel1c_exchange_admin_cml2_import_ver"),
			"sort"      => "CML2_IMPORT_VER",
			"default"   => true
		),
	);

	$lAdmin->addHeaders($headers);


	$nav = new AdminPageNavigation('askaron_parallel1c_exchange_admin');


	$queryObject = \Askaron\Parallel1c\ExchangeTable::getList(array(
		'select' => array(
			"*",
			"MODIFIED_BY_USER_LOGIN" => "MODIFIED_BY_USER.LOGIN",
			//"MODIFIED_BY_USER_NAME" => "MODIFIED_BY_USER.NAME",
			//"MODIFIED_BY_USER_LAST_NAME" => "MODIFIED_BY_USER.LAST_NAME",
		),
		'filter' => $arFilter,
		//'group' => array(),
		'order' => $queryOrder,
		'count_total'=>true,
		'offset' => $nav->getOffset(),
		'limit' => $nav->getLimit()
	));

	$nav->setRecordCount( $queryObject->getCount() );
	$lAdmin->setNavigation($nav, Loc::getMessage('CS_PAGES'));

	//$arDescriptions = array();

	$arCatalogImportVersions = \CAskaronParallel1c::GetCatalogImportVersions();
	$arCml2ImportVersions = \CAskaronParallel1c::GetCml2ImportVersions();


	while($arFields = $queryObject->Fetch())
	{
		//d($arFields);

//		$arDescription = array();
//
//		if ( isset( $arDescriptions[ $arFields["CODE"] ] ) )
//		{
//			$arDescription = $arDescriptions[ $arFields["CODE"] ];
//		}
//		else
//		{
//			$arDescription = \Askaron\Handlers1c\Manager::getDescriptionAndClassByCode( $arFields["CODE"]  );
//			$arDescriptions["CODE"] = $arDescription;
//		}

		//d($arDescription);

		$arData = $arFields["DATA"];

		$row =& $lAdmin->AddRow( $arFields["ID"], $arFields );

//		$str = "";
//		if( $arFields["ACTIVE"]=="Y" )
//		{
//			$str='<div class="lamp-green" style="float:left;"></div>&nbsp;'.GetMessage("askaron_handlers1c_handler_admin_yes");
//		}
//		else
//		{
//			$str='<div class="lamp-red" style="float:left;"></div>&nbsp;'.GetMessage("askaron_handlers1c_handler_admin_no");
//		}
//
//		$row->AddViewField("ACTIVE", $str);


		//d($arFields);
//
//		$str = "";
//		if ($arDescription)
//		{
//			$str = '<strong>' . htmlspecialcharsbx($arDescription["DATA"]["NAME"]) . '</strong>';
//
//			if (!$arDescription["INSTALL_AVAILABLE"])
//			{
//				$str .= ' <span style="color: red;">';
//				$str .= GetMessage("askaron_handlers1c_handler_admin_not_available");
//				if (strlen($arDescription["INSTALL_DATA_ERROR"]) > 0)
//				{
//					$str .= " (" . htmlspecialcharsbx($arDescription["INSTALL_DATA_ERROR"]) . ")";
//				}
//				$str .= '</span>';
//			}
//		}
//		else
//		{
//			$str .= ' <span style="color: red;">';
//			$str .= GetMessage("askaron_handlers1c_handler_admin_not_found");
//			$str .= '</span>';
//		}

//		$str .= "<br>".htmlspecialcharsbx( $arDescription["DATA"]["DESCRIPTION"] );
//
//		$str_comment = htmlspecialcharsbx( $arFields["COMMENT"] );
//		$str_comment = str_replace( "\r\n", "<br>", $str_comment );
//		$str_comment = str_replace( "\n", "<br>", $str_comment );
//		$str .= "<br><br>".$str_comment;
//
//		$row->AddViewField("HANDLER", $str);



		$str = "";
		if ($arFields["MODIFIED_BY"])
		{
			$str_user_edit = 'user_edit.php?lang='.LANG.'&ID='.$arFields["MODIFIED_BY"];
			$str = '[<a href="'.$str_user_edit.'">' .$arFields["MODIFIED_BY"] . '</a>] '.htmlspecialcharsbx( $arFields["MODIFIED_BY_USER_LOGIN"] );
		}
		$row->AddViewField("MODIFIED_BY", $str);

		$str = "";
		if ($arFields["COMPONENT_IMPORT_VER"])
		{
			$str = $arCatalogImportVersions[ $arFields["COMPONENT_IMPORT_VER"] ][ "NAME" ];
		}
		$row->AddViewField("COMPONENT_IMPORT_VER", $str);

		$str = "";
		if ($arFields["CML2_IMPORT_VER"])
		{
			$str = $arCml2ImportVersions[ $arFields["CML2_IMPORT_VER"] ][ "NAME" ];
		}
		$row->AddViewField("CML2_IMPORT_VER", $str);



//		if ($arDescription)
//		{
//			$str = "";
//			if (strlen($arDescription["DATA"]["AUTHOR_NAME"]) > 0)
//			{
//				if (strlen($arDescription["DATA"]["AUTHOR_URL"]) > 0)
//				{
//					$str = '<a target="_blank" href="' . htmlspecialcharsbx($arDescription["DATA"]["AUTHOR_URL"]) . '" >' . htmlspecialcharsbx($arDescription["DATA"]["AUTHOR_NAME"]) . '</a>';
//				}
//				else
//				{
//					$str = htmlspecialcharsbx($arDescription["DATA"]["AUTHOR_NAME"]);
//				}
//			}
//
//			if (strlen($arDescription["DATA"]["HELP_URL"]) > 0)
//			{
//				$str .= '<br><br>';
//				$str .= '<a target="_blank" href="' . htmlspecialcharsbx($arDescription["DATA"]["HELP_URL"]) . '" >' . GetMessage("askaron_handlers1c_handler_admin_doc") . '</a>';
//			}
//
//			$row->AddViewField("AUTHOR", $str);
//		}



//		$version = "<strong>".$arData["VERSION"]."</strong>";
//
//
//		if ($stmp = MakeTimeStamp($arData["VERSION_DATE"], "YYYY-MM-DD HH:MI:SS"))
//		{
//			$version .= "<br>".ConvertTimeStamp( $stmp, "SHORT" );
//		}
//
//		$row->AddViewField("VERSION", $version);

//		$str = "";
//		if ( $arDescription["TYPE"] == "USER" )
//		{
//			$str = GetMessage("askaron_handlers1c_handler_admin_type_user");
//		}
//		elseif ( $arDescription["TYPE"] == "DEFAULT" )
//		{
//			$str = GetMessage("askaron_handlers1c_handler_admin_type_default");
//		}
//
//		$row->AddViewField("TYPE", $str);


//		$arActions = Array();
//		if ( $arDescription["INSTALL_AVAILABLE"] )
//		{
//			$back_url = "askaron_handlers1c_handler_admin.php?lang=".LANG;
//
//			$arActions[] = array(
//				"ICON" => "edit",
//				"DEFAULT" => true,
//				"TEXT" => GetMessage("askaron_handlers1c_handler_admin_edit"),
//				"ACTION" => $lAdmin->ActionRedirect("/bitrix/admin/askaron_handlers1c_handler_edit.php?lang=".
//					LANG."&ID=". $arFields["ID"]."&back_url=".urlencode($back_url) ),
//				'DEFAULT' => true,
//			);
//		}

		$arActions = Array();

		$back_url = "askaron_parallel1c_exchange_admin.php?lang=".LANG;

		$arActions[] = array(
			"ICON" => "edit",
			"DEFAULT" => true,
			"TEXT" => GetMessage("askaron_parallel1c_exchange_admin_edit"),
			"ACTION" => $lAdmin->ActionRedirect("/bitrix/admin/askaron_parallel1c_exchange_edit.php?lang=".
				LANG."&ID=". $arFields["ID"]."&back_url=".urlencode($back_url) ),
			'DEFAULT' => true,
		);



		if ( $RIGHT >= "W")
		{

//			if ($arFields['ACTIVE'] == 'Y')
//			{
//				$arActions[] = array(
//					'ICON' => 'deactivate',
//					'TEXT' => Loc::getMessage('askaron_handlers1c_handler_admin_deactivate'),
//					'ACTION' => $lAdmin->ActionDoGroup($arFields['ID'], 'deactivate'),
//					'DEFAULT' => false,
//				);
//			}
//			else
//			{
//				$arActions[] = array(
//					'ICON' => 'activate',
//					'TEXT' => Loc::getMessage('askaron_handlers1c_handler_admin_activate'),
//					'ACTION' => $lAdmin->ActionDoGroup($arFields['ID'], 'activate'),
//					'DEFAULT' => false,
//				);
//			}
//
//			// separator
//			$arActions[] = array("SEPARATOR"=>true);

			$arActions[]=array(
				"ICON"=>"delete",
				"TEXT"=>GetMessage("askaron_parallel1c_exchange_admin_delete"),
				"ACTION"=>"if(confirm('".GetMessage('askaron_parallel1c_exchange_admin_delete_confirm')."')) ".$lAdmin->ActionDoGroup($arFields["ID"], "delete"),
			);
		}

		$row->AddActions($arActions);
	}


	$footerArray = array(array('title' => Loc::getMessage('CS_LIST_SELECTED'),
		'value' => $queryObject->getCount()));

	$lAdmin->addFooter($footerArray);

	if ($RIGHT>="W")
	{
		$arActionTable = array(
			"delete" => Loc::getMessage("MAIN_ADMIN_LIST_DELETE"),
//			"activate" => Loc::getMessage("MAIN_ADMIN_LIST_ACTIVATE"),
//			"deactivate" => Loc::getMessage("MAIN_ADMIN_LIST_DEACTIVATE"),
		);
		$lAdmin->AddGroupActionTable( $arActionTable );
	}

	$back_url = "askaron_parallel1c_exchange_admin.php?lang=".LANG;

	$aContext=array(
		array(
			"TEXT"=>GetMessage("askaron_parallel1c_exchange_admin_add"),
			"LINK"=>"/bitrix/admin/askaron_parallel1c_exchange_edit.php?lang=".LANG."&back_url=".urlencode($back_url),
			"TITLE"=>GetMessage("askaron_parallel1c_exchange_admin_add"),
			"ICON"=>"btn_new",
		),
	);

	$lAdmin->AddAdminContextMenu($aContext);



	$lAdmin->CheckListMode();

	// Title
	$APPLICATION->SetTitle(GetMessage("askaron_parallel1c_exchange_admin_title"));
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
	?>



	<?

	// demo (2)
	if( $install_status==2 )
	{
		CAdminMessage::ShowMessage(
			Array(
				"TYPE"=>"OK",
				"MESSAGE"=>GetMessage("askaron_parallel1c_prolog_status_demo"),
				"DETAILS"=>GetMessage("askaron_parallel1c_prolog_buy_html"),
				"HTML"=>true
			)
		);
	}
	?>


	<?if ( strlen($strError) > 0 ):?>
	<?
	CAdminMessage::ShowMessage(
		Array(
			"TYPE"=>"ERROR",
			"MESSAGE"=> $strError,
			"HTML"=>true
		)
	);
	?>

<?else:?>

	<?if (strlen($strWarning) > 0 ):?>
		<?
		//		CAdminMessage::ShowMessage(
		//			Array(
		//				"TYPE"=>"ERROR",
		//				"MESSAGE"=> $strWarning,
		//				"HTML"=>true
		//			)
		//		);
		?>
	<?endif?>

	<?if ( strlen( $strOk ) > 0 ):?>
		<?
		//		CAdminMessage::ShowMessage(
		//			Array(
		//				"TYPE"=>"OK",
		//				"MESSAGE"=> htmlspecialchars( $strOk ),
		//				"DETAILS"=>"",
		//				"HTML"=>true
		//			)
		//		);
		?>
	<?endif?>


	<?$lAdmin->DisplayList();?>

	<?/*
	<?=BeginNote();?>
		<?=GetMessage("askaron_parallel1c_prolog_module_description", array("#LANG#" => LANG ) );?>
	<?=EndNote();?>
	*/?>
<?endif?>

<?}?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>
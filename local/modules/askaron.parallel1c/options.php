<?
###################################################
# askaron.parallel1c module
# Copyright (c) 2011-2017 Askaron Systems ltd.
# http://askaron.ru
# mailto:mail@askaron.ru
###################################################

IncludeModuleLangFile(__FILE__);
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/options.php");
require_once( "prolog.php" );

$module_id = "askaron.parallel1c";
$install_status = CModule::IncludeModuleEx($module_id);

$arGroups = array(
//	"group1" => array(
//		"NAME" => GetMessage("ASKARON_PARALLEL1C1_LOG_GROUP"),
//		"HELP" => GetMessage("ASKARON_PARALLEL1C1_LOG_GROUP_HELP"),
//	),
	"debug" => array(
		"NAME" => GetMessage("askaron_parallel1c_header_debug"),
	),
);

$arOptions = array(

);

$arOptions[] = array(
	"CODE" => "copy_exchange_files",
	"SITE_ID" => "",
	"SHOW_EXACT_SITE_VALUE" => false,
	"NAME" => GetMessage("askaron_parallel1c_copy_exchange_files"),
	"TYPE" => "CHECKBOX",
	"HELP" => GetMessage("askaron_parallel1c_copy_exchange_files_help"),
	"GROUP" => "debug",
);


//$arOptions[] = array(
//	"CODE" => "log_trace",
//	"SITE_ID" => "",
//	"SHOW_EXACT_SITE_VALUE" => false,
//	"NAME" => GetMessage("askaron_pro1c_log_trace"),
//	"TYPE" => "INTEGER",
//	"HELP" => GetMessage("askaron_pro1c_log_trace_help"),
//	"MIN" => 0,
//	"GROUP" => "group1",
//);
//
//$arOptions[] = array(
//	"CODE" => "log_max_size",
//	"SITE_ID" => "",
//	"SHOW_EXACT_SITE_VALUE" => false,
//	"NAME" => GetMessage("askaron_pro1c_log_max_size"),
//	"NAME2" => GetMessage("askaron_pro1c_log_max_size_2"),
//	"TYPE" => "INTEGER",
//	"MIN" => 1,
//	"HELP" => GetMessage("askaron_pro1c_log_max_size_help"),
//	"GROUP" =>"group1",
//);


if( $install_status==0 )
{
	// module not found (0)
}
elseif( $install_status==3 )
{
	//demo expired (3)
	CAdminMessage::ShowMessage(
		Array(
			"TYPE"=>"ERROR",
			"MESSAGE" => GetMessage("askaron_parallel1c_prolog_status_demo_expired"),
			"DETAILS"=> GetMessage("askaron_parallel1c_prolog_buy_html"),
			"HTML"=>true
		)
	);	
}
else
{

	$RIGHT = $APPLICATION->GetGroupRight($module_id);
	$RIGHT_W = ($RIGHT>="W");
	$RIGHT_R = ($RIGHT>="R");

	if ($RIGHT_R)
	{	
		$arErrors = array();
		$arSettings = array();		

		if (
			$REQUEST_METHOD=="POST"
			&& strlen($Update)>0
			&& $RIGHT_W
			&& check_bitrix_sessid()
		)
		{
			foreach ( $arOptions as $key => $arOption )
			{
				if ( $arOption["TYPE"] == "CHECKBOX" )
				{
					if ( isset( $_REQUEST[ "arrOptions__".$key ] ) && $_REQUEST[ "arrOptions__".$key ] == "Y" )
					{
						COption::SetOptionString($module_id, $arOption["CODE"], "Y", false, $arOption["SITE_ID"] );
					}
					else
					{
						COption::SetOptionString($module_id, $arOption["CODE"], "N", false, $arOption["SITE_ID"] );
					}
				}

				if ( $arOption["TYPE"] == "TEXT" )
				{
					if ( isset( $_REQUEST[ "arrOptions__".$key ] ) )
					{
						COption::SetOptionString( $module_id, $arOption["CODE"], $_REQUEST[ "arrOptions__".$key ], false, $arOption["SITE_ID"] );
					}
				}

				if ( $arOption["TYPE"] == "INTEGER"
					//|| $arOption["TYPE"] == "LOCATION"
				)
				{
					if ( isset( $_REQUEST[ "arrOptions__".$key ] ) )
					{
						if ( strlen( $_REQUEST[ "arrOptions__".$key ] ) > 0 )
						{
							$val = intval( $_REQUEST[ "arrOptions__".$key ] );
							$min = $arOption["MIN"];

							if ( strlen( $min ) > 0 && $val < $min )
							{
								$val = $min;
							}

							COption::SetOptionString( $module_id, $arOption["CODE"], $val, false, $arOption["SITE_ID"] );
						}
					}
				}

//				if ( $arOption["TYPE"] == "IMAGE" )
//				{
//					$arFile = $_FILES[ "arrOptions__".$key];
//					$arFile["del"] = $_REQUEST[ "arrOptions__".$key."_del" ];
//					$arFile["MODULE_ID"] = $module_id;
//
//					$check_image_error = CFile::CheckImageFile( $arFile );
//
//					if ( strlen( $check_image_error ) > 0 )
//					{
//						$arWarnings[] = $check_image_error;
//					}
//					else
//					{
//						if ( strlen($arFile["name"]) > 0 || strlen($arFile["del"] ) > 0 )
//						{
//							$arFile["old_file"] = COption::GetOptionString( $module_id, $arOption["CODE"], "", $arOption["SITE_ID"], true );
//							$val = CFile::SaveFile( $arFile, $module_id );
//							COption::SetOptionString( $module_id, $arOption["CODE"], $val, false, $arOption["SITE_ID"] );
//						}
//					}
//				}
			}
		}


		if (
			$REQUEST_METHOD=="POST"
			&& $RIGHT_W
			&& strlen($RestoreDefaults)>0
			&& check_bitrix_sessid()
		)
		{
// save some options value
//			$arSaveOptions = array(
//				"random_value" => "",
//			);
//			foreach ( $arSaveOptions as $key => $value )
//			{
//				$arSaveOptions[ $key ] = COption::GetOptionString( $module_id, $key );
//			}

			// remove all
			COption::RemoveOption( $module_id );

//			restore
//			foreach ( $arSaveOptions as $key => $value )
//			{
//				COption::SetOptionString( $module_id, $key, $value );
//			}



			$z = CGroup::GetList($v1="id",$v2="asc", array("ACTIVE" => "Y", "ADMIN" => "N"), $get_users_amount = "N");
			while($zr = $z->Fetch())
			{
				$APPLICATION->DelGroupRight($module_id, array($zr["ID"]));
			}
		}

		// get optioons values
		$arDisplayOptions = array();

		foreach ( $arOptions as $key=> $arOption )
		{
			$arOptionAdd = $arOption;

			$option_value = COption::GetOptionString( $module_id, $arOption["CODE"], "", $arOption["SITE_ID"], $arOption["SHOW_EXACT_SITE_VALUE"] );

			$arOptionAdd["INPUT_ID"] = "option_".$key;
			$arOptionAdd["INPUT_NAME"] = "arrOptions__".$key;
			$arOptionAdd["~INPUT_VALUE"] = $option_value;
			$arOptionAdd["INPUT_VALUE"] = htmlspecialcharsbx( $option_value );

			$arDisplayOptions[ $key ] = $arOptionAdd;
		}

		foreach ( $arGroups as $group_key => $arGroup )
		{
			$arGroups[$group_key]["~NAME"] = $arGroup["NAME"];
			$arGroups[$group_key]["NAME"] = htmlspecialcharsbx( $arGroup["NAME"] );
		}
		
		
//		if ( count( $arErrors ) > 0 )
//		{
//			CAdminMessage::ShowMessage(
//				Array(
//					"TYPE"=>"ERROR",
//					"MESSAGE" => GetMessage("askaron_parallel1c_error_save_header"),
//					"DETAILS"=> implode( "<br />", $arErrors ),
//					"HTML"=>true
//				)
//			);
//		}
		
		//demo (2)
		if ( $install_status == 2 )
		{
			CAdminMessage::ShowMessage(
				Array(
					"TYPE"=>"OK",
					"MESSAGE" => GetMessage("askaron_parallel1c_prolog_status_demo"),
					"DETAILS"=> GetMessage("askaron_parallel1c_prolog_buy_html"),
					"HTML"=>true
				)
			);
		}
		
		
		
		$aTabs = array(
			array("DIV" => "edit1", "TAB" => GetMessage("MAIN_TAB_SET"), "ICON" => "", "TITLE" => GetMessage("MAIN_TAB_TITLE_SET")),		
			array("DIV" => "edit2", "TAB" => GetMessage("MAIN_TAB_RIGHTS"), "ICON" => "", "TITLE" => GetMessage("MAIN_TAB_TITLE_RIGHTS")),
		);

		$tabControl = new CAdminTabControl("tabControl", $aTabs);
		$tabControl->Begin();

		?>

		<form method="post" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=htmlspecialcharsbx($mid)?>&lang=<?=LANGUAGE_ID?>&mid_menu=<?=urlencode($_REQUEST["mid_menu"])?>">
			<?=bitrix_sessid_post()?>
			<?$tabControl->BeginNextTab();?>
			<tr>
				<td valign="top" colspan="2">
					<?/*

					<?CAdminMessage::ShowMessage(
						Array(
							"TYPE"=>"OK",
							"MESSAGE" => GetMessage("askaron_parallel1c_options_about"),
							"DETAILS"=> GetMessage("askaron_parallel1c_options_about_html", array( "#LANG#" => LANGUAGE_ID ) ),
							"HTML"=>true
						)
					);?>
					*/?>

					<?=BeginNote();?>
						<?=GetMessage("askaron_parallel1c_options_about", array("#LANG#" => LANG ) );?>
					<?=EndNote();?>
				</td>
			</tr>


			<?foreach ( $arGroups as $group_key => $arGroup ):?>
				<?if ( strlen($arGroup["NAME"]) > 0 ):?>
					<tr class="heading">
						<td valign="top" colspan="2" align="center"><?=$arGroup["NAME"]?></td>
					</tr>
				<?endif?>

				<?if ( strlen( $arGroup["HELP"] ) > 0 ):?>

					<tr>
						<td valign="top" colspan="2">
							<?=BeginNote();?>
							<?=$arGroup["HELP"];?>
							<?=EndNote();?>
						</td>
					</tr>
				<?endif?>

				<?foreach ( $arDisplayOptions as $key => $arInput  ):?>

					<?if ( $group_key == $arInput["GROUP"] ):?>
						<tr>
							<td valign="top" width="40%" class="field-name"><label for="<?=$arInput["INPUT_ID"]?>"><?=$arInput["NAME"]?></label></td>
							<td valign="top" width="60%">
								<?if ( $arInput["TYPE"] == "CHECKBOX" ):?>
									<input
										type="checkbox"
										value="Y"
										id="<?=$arInput["INPUT_ID"]?>"
										<?if ( $arInput["INPUT_VALUE"] == "Y" ):?>
											checked="checked"
										<?endif?>
										name="<?=$arInput["INPUT_NAME"]?>"
										/>

									<?=$arInput["NAME2"]?>
								<?endif?>

								<?if ( ($arInput["TYPE"] == "TEXT" && $arInput["ROWS"] <= 1) || $arInput["TYPE"] == "INTEGER" ):?>
									<input
										type="text"
										value="<?=$arInput["INPUT_VALUE"]?>"
										id="<?=$arInput["INPUT_ID"]?>"
										name="<?=$arInput["INPUT_NAME"]?>"
										<?//size="40"?>
										/>

									<?=$arInput["NAME2"]?>
								<?endif?>

								<?if ( $arInput["TYPE"] == "LOCATION" ):?>

									<?$APPLICATION->IncludeComponent(
										"bitrix:sale.location.selector.search",
										"",
										Array(
											"CACHE_TIME" => "36000000",
											"CACHE_TYPE" => "A",
											"CODE" => "",
											"COMPONENT_TEMPLATE" => ".default",
											"EXCLUDE_SUBTREE" => "",
											"FILTER_BY_SITE" => "N",
											"FILTER_SITE_ID" => $arInput["SITE_ID"],
											"ID" => $arInput["INPUT_VALUE"],
											"INPUT_NAME" => $arInput["INPUT_NAME"],
											"JSCONTROL_GLOBAL_ID" => "",
											"JS_CALLBACK" => "",
											"PROVIDE_LINK_BY" => "id",
											"SEARCH_BY_PRIMARY" => "N",
											"SHOW_DEFAULT_LOCATIONS" => "N"
										),
										null,
										Array(
											'HIDE_ICONS' => 'N'
										)
									);?>
								<?endif?>

								<?if ( $arInput["TYPE"] == "TEXT" && $arInput["ROWS"] > 1  ):?>
									<textarea id="<?=$arInput["INPUT_ID"]?>" name="<?=$arInput["INPUT_NAME"]?>" rows="<?=$arInput["ROWS"]?>" cols="<?=$arInput["COLS"]?>"><?=$arInput["INPUT_VALUE"]?></textarea>
								<?endif?>

								<?if ( $arInput["TYPE"] == "IMAGE" ):?>

									<?=CFile::InputFile( $arInput["INPUT_NAME"], 20,  $arInput["~INPUT_VALUE"], "/upload/");?>

									<?if (strlen($arInput["~INPUT_VALUE"])>0):?>
										<br><?=CFile::ShowImage( $arInput["~INPUT_VALUE"], 150, 150, "border=0", "", true );?>
									<?endif;?>
								<?endif?>

								<?if ( strlen( $arInput["HELP"] ) > 0 ):?>
									<?=BeginNote();?>
									<?=$arInput["HELP"];?>
									<?=EndNote();?>
								<?endif?>
							</td>
						</tr>
					<?endif?>

				<?endforeach?>

			<?endforeach?>
			<?$tabControl->BeginNextTab();?>
			<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");?>
			<?$tabControl->Buttons();?>		
			<input <?if(!$RIGHT_W) echo "disabled" ?> type="submit" name="Update" value="<?=GetMessage("MAIN_SAVE")?>" title="<?=GetMessage("MAIN_OPT_SAVE_TITLE")?>">
			<input <?if(!$RIGHT_W) echo "disabled" ?> type="submit" name="RestoreDefaults" title="<?echo GetMessage("MAIN_HINT_RESTORE_DEFAULTS")?>" OnClick="return confirm('<?echo AddSlashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>')" value="<?echo GetMessage("MAIN_RESTORE_DEFAULTS")?>">
			<?$tabControl->End();?>
		</form>

	<?
	}
}
?>
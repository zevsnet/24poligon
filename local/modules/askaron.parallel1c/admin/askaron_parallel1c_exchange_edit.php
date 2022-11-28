<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

require_once( dirname(__FILE__)."/../prolog.php" );

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

// messages
$module_id = "askaron.parallel1c";
$install_status=CModule::IncludeModuleEx("askaron.parallel1c");


$arGroups = array(
	"group1" => array(
		"NAME" => GetMessage("askaron_parallel1c_exchange_edit_group1"),
		"HELP" => GetMessage("askaron_parallel1c_exchange_edit_group1_help"),
	),
	"group4" => array(
		"NAME" => GetMessage("askaron_parallel1c_exchange_edit_group4"),
		"HELP" => GetMessage("askaron_parallel1c_exchange_edit_group4_help"),
	),
	"group2" => array(
		"NAME" => GetMessage("askaron_parallel1c_exchange_edit_group2"),
		"HELP" => GetMessage("askaron_parallel1c_exchange_edit_group2_help"),
	),
	"group3" => array(
		"NAME" => GetMessage("askaron_parallel1c_exchange_edit_group3"),
		"HELP" => GetMessage("askaron_parallel1c_exchange_edit_group3_help"),
	),
	"group5" => array(
		"NAME" => GetMessage("askaron_parallel1c_exchange_edit_group5"),
		"HELP" => GetMessage("askaron_parallel1c_exchange_edit_group5_help"),
	),
);




$arOptions = array();


$arOptions[] = array(
	"CODE" => "CODE",
	"NAME" => GetMessage("askaron_parallel1c_exchange_edit_code"),
	"NAME2" => "",
	"TYPE" => "TEXT",
	"DEFAULT_VALUE" => "",
	"VALUE" => null,
	"REQUIRED" => true,
	//"ROWS" => 1,
	//"COLS" => 40,
	"SIZE" => 40,
	"HELP" => GetMessage("askaron_parallel1c_exchange_edit_code_help"),
	"GROUP" => "group1",
);

$arOptions[] = array(
	"CODE" => "NAME",
	"NAME" => GetMessage("askaron_parallel1c_exchange_edit_name"),
	"NAME2" => "",
	"TYPE" => "TEXT",
	"DEFAULT_VALUE" => "",
	"VALUE" => null,
	"REQUIRED" => true,
	"ROWS" => 3,
	"COLS" => 80,
	//"SIZE" => 80,
	"HELP" => GetMessage("askaron_parallel1c_exchange_edit_name_help"),
	"GROUP" => "group1",
);

$arOptions[] = array(
	"CODE" => "PATH",
	"NAME" => GetMessage("askaron_parallel1c_exchange_edit_path"),
	"NAME2" => "",
	"TYPE" => "TEXT",
	"DEFAULT_VALUE" => "",
	"VALUE" => null,
	"REQUIRED" => true,
	//"ROWS" => 1,
	//"COLS" => 80,
	"SIZE" => 80,
	"HELP" => GetMessage("askaron_parallel1c_exchange_edit_path_help"),
	"GROUP" => "group1",
);





$arOptions[] = array(
	"CODE" => "DISALLOW_IMPORT_XML_STEP",
	"NAME" => GetMessage("askaron_parallel1c_exchange_disallow_import_xml_step"),
	"NAME2" => "",
	"TYPE" => "CHECKBOX",
	"DEFAULT_VALUE" => "N",
	"VALUE" => null,
	//"ROWS" => 1,
	//"COLS" => 80,
	"HELP" => GetMessage("askaron_parallel1c_exchange_disallow_import_xml_step_help"),
	"GROUP" => "group2",
);


$arOptions[] = array(
	"CODE" => "DISALLOW_OFFERS_XML_STEP",
	"NAME" => GetMessage("askaron_parallel1c_exchange_disallow_offers_xml_step"),
	"NAME2" => "",
	"TYPE" => "CHECKBOX",
	"DEFAULT_VALUE" => "N",
	"VALUE" => null,
	//"ROWS" => 1,
	//"COLS" => 80,
	"HELP" => GetMessage("askaron_parallel1c_exchange_disallow_offers_xml_step_help"),
	"GROUP" => "group2",
);

$arOptions[] = array(
	"CODE" => "DISALLOW_PRICES_XML_STEP",
	"NAME" => GetMessage("askaron_parallel1c_exchange_disallow_prices_xml_step"),
	"NAME2" => "",
	"TYPE" => "CHECKBOX",
	"DEFAULT_VALUE" => "N",
	"VALUE" => null,
	//"ROWS" => 1,
	//"COLS" => 80,
	"HELP" => GetMessage("askaron_parallel1c_exchange_disallow_prices_xml_step_help"),
	"GROUP" => "group2",
);

$arOptions[] = array(
	"CODE" => "DISALLOW_RESTS_XML_STEP",
	"NAME" => GetMessage("askaron_parallel1c_exchange_disallow_rests_xml_step"),
	"NAME2" => "",
	"TYPE" => "CHECKBOX",
	"DEFAULT_VALUE" => "N",
	"VALUE" => null,
	//"ROWS" => 1,
	//"COLS" => 80,
	"HELP" => GetMessage("askaron_parallel1c_exchange_disallow_rests_xml_step_help"),
	"GROUP" => "group2",
);


$arOptions[] = array(
	"CODE" => "DISALLOW_DEACTIVATE_STEP",
	"NAME" => GetMessage("askaron_parallel1c_exchange_disallow_deactivate_step"),
	"NAME2" => "",
	"TYPE" => "CHECKBOX",
	"DEFAULT_VALUE" => "N",
	"VALUE" => null,
	//"ROWS" => 1,
	//"COLS" => 80,
	"HELP" => GetMessage("askaron_parallel1c_exchange_disallow_deactivate_step_help"),
	"GROUP" => "group2",
);


//$arOptions[] = array(
//	"CODE" => "ALLOW_DEACTIVATE_IBLOCKS",
//	"NAME" => GetMessage("askaron_parallel1c_exchange_allow_deactivate_iblocks"),
//	"NAME2" => "",
//	"TYPE" => "TEXT",
//	"DEFAULT_VALUE" => "",
//	"VALUE" => null,
//	"REQUIRED" => false,
//	//"ROWS" => 1,
//	//"COLS" => 40,
//	"SIZE" => 40,
//	"HELP" => GetMessage("askaron_parallel1c_exchange_allow_deactivate_iblocks_help"),
//	"GROUP" => "group4",
//);

\Bitrix\Main\Loader::includeModule( "iblock" );

$arValues = array();
$res = \CIBlock::GetList(
	Array(),
	Array("CHECK_PERMISSIONS" => "N"),
	false
);
while($ar_res = $res->Fetch())
{
	$arValues[ $ar_res["ID"] ] = "[".$ar_res["ID"]."] ".$ar_res["NAME"];
}

$arOptions[] = array(
	"CODE" => "ALLOW_DEACTIVATE_IBLOCKS",
	"NAME" => GetMessage("askaron_parallel1c_exchange_allow_deactivate_iblocks"),
	"NAME2" => "",
	"TYPE" => "LIST",
	"MULTIPLE" => "Y",
	"VIEW" => "CHECKBOX",
	"DEFAULT_VALUE" => array(),
	"VALUE" => array(),
	"VALUES" => $arValues,
	"REQUIRED" => false,
	//"ROWS" => 1,
	//"COLS" => 40,
	"HELP" => GetMessage("askaron_parallel1c_exchange_allow_deactivate_iblocks_help"),
	"GROUP" => "group4",
);


$arOptions[] = array(
	"CODE" => "IMPORT_XML_NEW_ONLY",
	"NAME" => GetMessage("askaron_parallel1c_exchange_import_xml_new_only"),
	"NAME2" => "",
	"TYPE" => "CHECKBOX",
	"DEFAULT_VALUE" => "N",
	"VALUE" => null,
	//"ROWS" => 1,
	//"COLS" => 80,
	"HELP" => GetMessage("askaron_parallel1c_exchange_import_xml_new_only_help"),
	"GROUP" => "group3",
);



$arCatalogImportVersions = \CAskaronParallel1c::GetCatalogImportVersions();
$arValues = array();
foreach ($arCatalogImportVersions as $key=>$arItem)
{
	$arValues[$key] = array(
		"NAME" => $arItem["NAME"],
		"VALUE" => $key,
	);
}

$arOptions[] = array(
	"CODE" => "COMPONENT_IMPORT_VER",
	"NAME" => GetMessage("askaron_parallel1c_exchange_edit_component_import_ver"),
	"NAME2" => "",
	"TYPE" => "SELECT",
	"DEFAULT_VALUE" => "",
//	"DEFAULT_VALUE" => "last",
	"VALUE" => null,
	"VALUES" => $arValues,
	"REQUIRED" => true,
	//"ROWS" => 1,
	//"COLS" => 80,
	"HELP" => GetMessage("askaron_parallel1c_exchange_edit_component_import_ver_help"),
	"GROUP" => "group5",
);

$arCatalogImportVersions = \CAskaronParallel1c::GetCml2ImportVersions();
$arValues = array();
foreach ($arCatalogImportVersions as $key=>$arItem)
{
	$arValues[$key] = array(
		"NAME" => $arItem["NAME"],
		"VALUE" => $key,
	);
}

$arOptions[] = array(
	"CODE" => "CML2_IMPORT_VER",
	"NAME" => GetMessage("askaron_parallel1c_exchange_edit_cml2_import_ver"),
	"NAME2" => "",
	"TYPE" => "SELECT",
	"DEFAULT_VALUE" => "",
//	"DEFAULT_VALUE" => "last",
	"VALUE" => null,
	"VALUES" => $arValues,
	"REQUIRED" => true,
	//"ROWS" => 1,
	//"COLS" => 80,
	"HELP" => GetMessage("askaron_parallel1c_exchange_edit_cml2_import_ver_help"),
	"GROUP" => "group5",
);



// demo expired (3)
if( $install_status==3 )
{
	$APPLICATION->SetTitle(GetMessage("askaron_parallel1c_exchange_edit_title"));
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
	$RIGHT = $APPLICATION->GetGroupRight($module_id);
	$RIGHT_W = ($RIGHT>="W");
	$RIGHT_R = ($RIGHT>="R");


	if (!$RIGHT_R)
	{
		$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
	}


	$strError = "";
	//$strWarning = "";
	$arWarnings = array();
	$strOk = "";

	$ID = intval($_REQUEST["ID"]);

	$arElement = array();

	if ($ID > 0)
	{
		$res = \Askaron\Parallel1c\ExchangeTable::getById($ID);
		if ($arRes = $res->fetch())
		{
			$arElement = $arRes;
		}
		else
		{
			$strError = GetMessage("askaron_parallel1c_exchange_edit_id_not_found", array("#ID#" => $ID));
		}
	}


	// default values
//	$arFormFields = array(
//		"ACTIVE" => array(
//			"VALUE" => "Y",
//			"CODE" => "ACTIVE",
//		),
//		"SORT" => array(
//			"VALUE" => "500",
//			"CODE" => "SORT",
//		),
//		"COMMENT" => array(
//			"VALUE" => "",
//			"CODE" => "COMMENT",
//		),
//	);



	if ( strlen($strError) <= 0 )
	{
		foreach ( $arOptions as $key => $arItem )
		{
			if ( isset( $arElement[ $arItem["CODE"] ] ) )
			{
				$arOptions[$key]["VALUE"] = $arElement[ $arItem["CODE"] ];
			}
			else
			{
				$arOptions[$key]["VALUE"] = $arItem["DEFAULT_VALUE"];
			}
		}


		if(
			($_REQUEST["save"]!=""||$_REQUEST["apply"]!="")
			&&
			$RIGHT_W		  // check if user have permission to write data
			&&
			check_bitrix_sessid()	 // check Session Id
		)
		{

//			$arFormFields["ACTIVE"]["VALUE"] = ($_REQUEST["ACTIVE"] !== "Y" ? "N" : "Y");
//			$arFormFields["SORT"]["VALUE"] = intval($_REQUEST["SORT"]);
//			$arFormFields["COMMENT"]["VALUE"] = $_REQUEST["COMMENT"];

			foreach ( $arOptions as $key => $arOption )
			{
				if ( $arOption["TYPE"] == "CHECKBOX" )
				{
					if ( isset( $_REQUEST[ "arrOptions__".$key ] ) && $_REQUEST[ "arrOptions__".$key ] == "Y" )
					{
						$arOptions[$key][ "VALUE" ] = "Y";
					}
					else
					{
						$arOptions[$key][ "VALUE" ] = "N";
					}
				}

				if ( $arOption["TYPE"] == "TEXT" )
				{
					if ( isset( $_REQUEST[ "arrOptions__".$key ] ) )
					{
						$arOptions[$key][ "VALUE" ] = $_REQUEST[ "arrOptions__".$key ];
					}
				}

				if ( $arOption["TYPE"] == "SELECT" )
				{
					if ( isset( $_REQUEST[ "arrOptions__".$key ] ) )
					{
						$arOptions[$key][ "VALUE" ] = $_REQUEST[ "arrOptions__".$key ];
					}
				}

				if ($arOption["TYPE"] == "LIST")
				{
					if ($arOption["MULTIPLE"] == "Y")
					{
						if ( isset( $_REQUEST[ "arrOptions__".$key ] ) )
						{
							$arOptions[$key][ "VALUE" ] = $_REQUEST[ "arrOptions__".$key ];
						}
						else
						{
							$arOptions[$key][ "VALUE" ] = array();
						}
					}
					else
					{
						if ( isset( $_REQUEST[ "arrOptions__".$key ] ) )
						{
							$arOptions[$key][ "VALUE" ] = $_REQUEST[ "arrOptions__".$key ];
						}
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

							$arOptions[$key][ "VALUE" ] = $_REQUEST[ "arrOptions__".$key ];
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
//
//							$arOptions[$key][ "VALUE" ] = $val;
//						}
//					}
//				}
			}

			$arFieldsUpdate = array();

			foreach ( $arOptions as $key => $arOption )
			{
				$arFieldsUpdate[ $arOption["CODE"] ] = $arOption["VALUE"];
			}

			foreach ( $arOptions as $key => $arOption )
			{
				if ( $arOption["REQUIRED"] && strlen( $arOption["VALUE"] ) <= 0 )
				{
					$arWarnings[] = Loc::getMessage(
							"askaron_parallel1c_exchange_edit_field_is_empty",
							array( "#FIELD#" => $arOption["NAME"] )
					);
				}

			}

			if (!$arWarnings)
			{
				$res = false;

				//dd($arFieldsUpdate);

				if ($ID > 0)
				{
					$res = \Askaron\Parallel1c\ExchangeTable::update($ID, $arFieldsUpdate);
				}
				else
				{
					$res = \Askaron\Parallel1c\ExchangeTable::add($arFieldsUpdate);
				}

				if ($res->isSuccess())
				{
					if ($_REQUEST["save"] != "")
					{
						LocalRedirect("askaron_parallel1c_exchange_admin.php?lang=" . LANG);
					}

					if ($_REQUEST["apply"] != "")
					{
						$url = $APPLICATION->GetCurPage(true)."?ID=".$res->getId()."&OK=Y&lang=".LANG;
						LocalRedirect($url);
					}
				}
				else
				{
					$errors = $res->getErrors();

					foreach ($errors as $error)
					{
						$arWarnings[] = $error->getMessage();
					}
				}
			}
		}
	}

	$arDisplayOptions = array();

	foreach ( $arOptions as $key=> $arOption )
	{
		$arOptionAdd = $arOption;

		$option_value = $arOption["VALUE"];

		$arOptionAdd["INPUT_ID"] = "option_".$key;
		$arOptionAdd["INPUT_NAME"] = "arrOptions__".$key;
		$arOptionAdd["~INPUT_VALUE"] = $option_value;

		if (is_array( $option_value ) )
		{
			$arOptionAdd["INPUT_VALUE"] = $option_value;
		}
		else
		{
			$arOptionAdd["INPUT_VALUE"] = htmlspecialcharsbx($option_value);
		}

		$arDisplayOptions[ $key ] = $arOptionAdd;
	}

	foreach ( $arGroups as $group_key => $arGroup )
	{
		$arGroups[$group_key]["~NAME"] = $arGroup["NAME"];
		$arGroups[$group_key]["NAME"] = htmlspecialcharsbx( $arGroup["NAME"] );
	}



	if ( $_REQUEST["OK"] === "Y" )
	{
		$strOk = Loc::getMessage("askaron_parallel1c_exchange_edit_save_ok");
	}

	$aTabs=array(
		array("DIV"=>"edit1",
			"TAB"=>GetMessage("askaron_parallel1c_exchange_edit_title"),
			"ICON"=>"main_user_edit",
			"TITLE"=>GetMessage("askaron_parallel1c_exchange_edit_title")
		),
	);
	$tabControl=new CAdminTabControl("tabControl", $aTabs);

	// Title
	$APPLICATION->SetTitle(GetMessage("askaron_parallel1c_exchange_edit_title"));
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
	?>

	<?//d($arDescription);?>
	<?//d($arElement);?>

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


		<?if ( $arWarnings ):?>
			<?
			CAdminMessage::ShowMessage(
				Array(
					"TYPE"=>"ERROR",
					"MESSAGE"=> implode( "<br>", $arWarnings ),
					"HTML"=>true
				)
			);
			?>
		<?endif?>

		<?if ( strlen( $strOk ) > 0 ):?>
			<?
			CAdminMessage::ShowMessage(
				Array(
					"TYPE"=>"OK",
					"MESSAGE"=> htmlspecialchars( $strOk ),
					"DETAILS"=>"",
					"HTML"=>true
				)
			);
			?>
		<?endif?>

		<?if ( strlen( $arDescription["DATA"]["DESCRIPTION_DETAIL_HTML"] ) ):?>
			<?=BeginNote();?>
				<?=$arDescription["DATA"]["DESCRIPTION_DETAIL_HTML"]?>
			<?=EndNote();?>
		<?endif?>

		<?if ( strlen( $arDescription["DATA"]["HELP_URL"] ) > 0 ):?>
			<?=BeginNote();?>
				<a target="_blank" href="<?=htmlspecialcharsbx( $arDescription["DATA"]["HELP_URL"] )?>"><?=GetMessage("askaron_parallel1c_exchange_edit_doc")?></a>
			<?=EndNote();?>
		<?endif?>


		<form method="POST" action="<?echo $APPLICATION->GetCurPageParam( "", array("OK") )?>" ENCTYPE="multipart/form-data" name="post_form">

			<?echo bitrix_sessid_post();?>
			<?
			$tabControl->Begin();
			?>
			<?
			$tabControl->BeginNextTab();
			?>

			<?if ( $arElement["ID"] > 0 ):?>
				<tr>
					<td valign="top"><strong>ID</strong></td>
					<td><?=$arElement["ID"]?></td>
				</tr>
			<?endif?>

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

							<td valign="top" width="40%" class="field-name"><?
								if ( $arInput["REQUIRED"] )
								{
									?><strong><label for="<?= $arInput["INPUT_ID"] ?>"><?= $arInput["NAME"] ?></label></strong><?
								}
								else
								{
									?><label for="<?= $arInput["INPUT_ID"] ?>"><?= $arInput["NAME"] ?></label><?
								}
							?></td>
							<td valign="top" width="60%">
								<?if ( $arInput["TYPE"] == "SELECT" ):?>

									<select id="<?=$arInput["INPUT_ID"]?>" name="<?=$arInput["INPUT_NAME"]?>">
										<?foreach( $arInput["VALUES"] as $key=>$arValue):?>
											<option
												<?if ( $arInput["INPUT_VALUE"] == $arValue["VALUE"] ):?>
													selected
												<?endif?>
													value="<?=htmlspecialcharsbx($key)?>"
											><?=$arValue["NAME"]?></option>
										<?endforeach?>
									</select>

									<?=$arInput["NAME2"]?>
								<?endif?>

								<?if ( $arInput["TYPE"] == "LIST"  ):?>
									<?if ( $arInput["MULTIPLE"] == "Y"  ):?>

										<?if ( $arInput["VALUES"]):?>
											<?$index = 0;?>

											<?
												$arValuesChecked = $arInput["VALUE"];
												if ( !is_array( $arValuesChecked ) )
												{
													$arValuesChecked = array();
												}
											?>

											<?foreach ( $arInput["VALUES"] as $value=>$item ):?>

												<input id="<?= $arInput["INPUT_ID"] ?>_<?=$index?>" name="<?= $arInput["INPUT_NAME"] ?>[]" type="checkbox" value="<?=htmlspecialcharsbx($value)?>"
													<?if ( in_array($value, $arValuesChecked )  ):?>
														checked
													<?endif?>
												> <label for="<?= $arInput["INPUT_ID"] ?>_<?=$index?>"><?=htmlspecialcharsbx($item)?></label>
												<div style="clear: both; height: 2px"></div>
												<?$index++;?>
											<?endforeach?>
										<?endif?>

										<?=$arInput["NAME2"]?>
									<?else:?>
										<?if ( $arInput["VALUES"]):?>
											<?$index = 0;?>
											<?foreach ( $arInput["VALUES"] as $value=>$item ):?>

												<input id="<?= $arInput["INPUT_ID"] ?>_<?=$index?>" name="<?= $arInput["INPUT_NAME"] ?>" type="radio" value="<?=htmlspecialcharsbx($value)?>"
													<?if ( ''.$value == ''.$arInput["VALUE"] ):?>
														checked
													<?endif?>
												> <label for="<?= $arInput["INPUT_ID"] ?>_<?=$index?>"><?=htmlspecialcharsbx($item)?></label>
												<div style="clear: both; height: 2px"></div>
												<?$index++;?>
											<?endforeach?>
										<?endif?>
									<?endif?>
								<?endif?>


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
											<?if ( $arInput["SIZE"] > 0 ):?>
												size="<?=$arInput["SIZE"]?>"
											<?endif?>
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

			<?
			//finish form show buttons "Save" and "Apply"

			$back_url = "askaron_parallel1c_exchange_admin.php?lang=".LANG;
			if ( strlen( $_REQUEST["back_url"] ) > 0 )
			{
				$back_url = $_REQUEST["back_url"];
			}


			$tabControl->Buttons(
				array(
					"back_url" => $back_url,
				)
			);
			?>
			<?
			// finish tabs
			$tabControl->End();
			?>
		</form>
	<?endif?>

<?}?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>
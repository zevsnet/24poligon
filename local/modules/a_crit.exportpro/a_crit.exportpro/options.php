<?
$module_id = "a_crit.exportpro";

$POST_RIGHT = $APPLICATION->GetGroupRight( $module_id );

CUtil::InitJSCore( array( "ajax", "jquery" ) );
$APPLICATION->AddHeadScript( "/bitrix/js/iblock/iblock_edit.js" );
$APPLICATION->AddHeadScript( "/bitrix/js/a_crit.exportpro/main.js" );
$t = CJSCore::getExtInfo( "jquery" );

if( !is_array( $t ) || !isset( $t["js"] ) || !file_exists( $DOCUMENT_ROOT.$t["js"] ) ){
    try{
        throw new SystemException( GetMessage( "ACRIT_EXPORTPRO_JQUERY_REQUIRE" ) );
    }
    catch( SystemException $exception ){
        global $lastException;
        $lastException = $exception->getMessage();
    }
}

if( $POST_RIGHT >= "R" ){
	IncludeModuleLangFile( $_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/options.php" );
	CModule::IncludeModule( $module_id );
	IncludeModuleLangFile( __FILE__ );

//    $bExistBitrixCloudMonitoring = CExportproInformer::CheckBitrixCloudMonitoring( GetMessage( "SC_SET_BITRIX_CLOUD_MONITORING_EMAIL" ) );

//    //AcritLicence::Show();

	$aTabs = array(
		array(
		    "DIV" => "edit1",
			"TAB" => GetMessage( "MAIN_TAB_RIGHTS" ),
            "ICON" => "main_settings",
            "TITLE" => GetMessage( "MAIN_TAB_TITLE_RIGHTS" )
		),
		array(
            "DIV" => "edit2",
            "TAB" => GetMessage( "MAIN_TAB_AGENTS" ),
            "ICON" => "main_settings",
            "TITLE" => GetMessage( "MAIN_TAB_TITLE_AGENTS" )
        ),
        array(
            "DIV" => "edit3",
            "TAB" => GetMessage( "MAIN_TAB_SUPPORT" ),
            "ICON" => "main_settings",
            "TITLE" => GetMessage( "MAIN_TAB_TITLE_SUPPORT" )
        ),
	);

	$tabControl = new CAdminTabControl( "tabControl", $aTabs );

	if( ( $REQUEST_METHOD == "POST" ) && ( strlen( $Update.$Apply.$RestoreDefaults ) > 0 ) && ( $POST_RIGHT == "W" ) && check_bitrix_sessid() ){
        if( isset( $_REQUEST["ACRITMENU_GROUPNAME"] ) && ( strlen( trim( $_REQUEST["ACRITMENU_GROUPNAME"] ) ) > 0 ) ){
            COption::SetOptionString( "a_crit.exportpro", "acritmenu_groupname", trim( $_REQUEST["ACRITMENU_GROUPNAME"] ) );
        }
		$Update = $Update.$Apply;
		ob_start();
		require_once( $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php" );
		ob_end_clean();
		if( strlen( $_REQUEST["back_url_settings"] ) > 0 ){
		    if( ( strlen( $Apply ) > 0) || ( strlen( $RestoreDefaults ) > 0 ) ){
				LocalRedirect( $APPLICATION->GetCurPage()."?mid=".urlencode( $module_id )."&lang=".urlencode( LANGUAGE_ID )."&back_url_settings=".urlencode( $_REQUEST["back_url_settings"] )."&".$tabControl->ActiveTabParam() );
			}
			else{
				LocalRedirect( $_REQUEST["back_url_settings"] );
			}
		}
		else{
			LocalRedirect( $APPLICATION->GetCurPage()."?mid=".urlencode( $module_id )."&lang=".urlencode( LANGUAGE_ID )."&".$tabControl->ActiveTabParam() );
		}
	}

    require __DIR__."/admin/auto_tests.php";?>

	<form method="post" action="<?=$APPLICATION->GetCurPage()?>?mid=<?=urlencode( $module_id )?>&amp;lang=<?=LANGUAGE_ID?>">
		<?$tabControl->Begin();
	    $tabControl->BeginNextTab();
	    require_once( $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php" );

	    $tabControl->BeginNextTab();?>

        <tr>
            <td class="heading" colspan="2"><?=GetMessage( "SC_SET_CRON_AGENTS_OPTIONS" );?></td>
        </tr>
        <tr>
            <td colspan="2" class="adm-detail-content-cell" align="center">
                <a class="adm-btn adm-btn-save" onclick="javascript:SetCronAgentOptions();"><?=GetMessage( "ACRIT_EXPORTPRO_SET_CRON_AGENTS_OPTIONS_BUTTON" )?></a>
            </td>
        </tr>
        <tr>
            <td class="heading" colspan="2"><?=GetMessage( "SC_SET_CRON_AGENTS_INFO" );?></td>
        </tr>
        <tr>
            <td colspan="2">
                <?=GetMessage( "ACRIT_EXPORTPRO_SET_CRON_AGENTS_OPTIONS_STEPS" );?>
            </td>
        </tr>

        <?$tabControl->BeginNextTab();?>
        <tr>
            <td class="heading" colspan="2"><?=GetMessage( "ACRITMENU_GROUPNAME_LABEL" );?></td>
        </tr>
        <tr>
            <td colspan="2" class="adm-detail-content-cell" align="center">
                <input type="text" name="ACRITMENU_GROUPNAME" value="<?=COption::GetOptionString( "a_crit.exportpro", "acritmenu_groupname" );?>"/>
            </td>
        </tr>
        <tr>
            <td class="heading" colspan="2"><?=GetMessage( "SC_MARKET_CATEGORIES" );?></td>
        </tr>
        <tr>
            <td colspan="2" class="adm-detail-content-cell" align="center">
                <a class="adm-btn adm-btn-save" onclick="javascript:UpdateMarketCategories();">
                    <?=GetMessage( "ACRIT_EXPORTPRO_MARKET_CATEGORIES_UPDATE_BUTTON" );?>
                </a>
            </td>
        </tr>


        <tr>
            <td class="heading" colspan="2"><?=GetMessage( "SC_FRM_1" );?></td>
        </tr>
        <tr>
            <td valign="top" class="adm-detail-content-cell-l">
                <span class="required">*</span><?=GetMessage( "SC_FRM_2" );?><br/>
                <small><?=GetMessage( "SC_FRM_3" );?></small>
            </td>
            <td valign="top" class="adm-detail-content-cell-r">
                <textarea cols="60" rows="6" name="ticket_text_proxy" id="ticket_text_proxy"><?=htmlspecialcharsbx( implode( "\n", $arAutoProblemsToSupportMessage ) );?></textarea>
            </td>
        </tr>
        <tr>
            <td class="adm-detail-content-cell-l"></td>
            <td class="adm-detail-content-cell-r">
                <input type="button" value="<?=GetMessage( "SC_FRM_4" );?>" onclick="SubmitToSupport()" name="submit_button">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <?=BeginNote();?>
                    <?=GetMessage( "SC_TXT_1" );?> <a href="<?=GetMessage( "A_SUPPORT_URL" );?>"><?=GetMessage( "A_SUPPORT_URL" );?></a>
                <?=EndNote();?>
            </td>
        </tr>

        <tr>
			<td colspan="2">
				<?=GetMessage( "ACRIT_EXPORTPRO_RECOMMENDS" );?>
			</td>
		</tr>

		<?$tabControl->Buttons();?>
		<input <?if( $POST_RIGHT < "W" ) echo "disabled"?> class="adm-btn-save" type="submit" name="Update" value="<?=GetMessage( "MAIN_SAVE" )?>" title="<?=GetMessage( "MAIN_OPT_SAVE_TITLE" )?>">
		<input <?if( $POST_RIGHT < "W" ) echo "disabled"?> type="submit" name="Apply" value="<?=GetMessage( "MAIN_OPT_APPLY" )?>" title="<?=GetMessage( "MAIN_OPT_APPLY_TITLE" )?>">
		<?if( strlen( $_REQUEST["back_url_settings"] ) > 0 ){?>
			<input <?if( $POST_RIGHT < "W" ) echo "disabled"?> type="button" name="Cancel" value="<?=GetMessage( "MAIN_OPT_CANCEL" )?>" title="<?=GetMessage( "MAIN_OPT_CANCEL_TITLE" )?>" onclick="window.location = '<?=htmlspecialcharsbx( CUtil::addslashes( $_REQUEST["back_url_settings"] ) )?>'">
			<input type="hidden" name="back_url_settings" value="<?=htmlspecialcharsbx( $_REQUEST["back_url_settings"] )?>">
		<?}?>
		<input <?if( $POST_RIGHT < "W" ) echo "disabled"?> type="submit" name="RestoreDefaults" title="<?=GetMessage( "MAIN_HINT_RESTORE_DEFAULTS" )?>" OnClick="return confirm( '<?=AddSlashes( GetMessage( "MAIN_HINT_RESTORE_DEFAULTS_WARNING" ) )?>' )" value="<?=GetMessage( "MAIN_RESTORE_DEFAULTS" )?>">
		<?=bitrix_sessid_post();?>
		<?$tabControl->End();?>
	</form>


<?}?>

<script type="text/javascript">
    function SubmitToSupport(){
        //var frm = document.forms.fticket;
        //
        //frm.ticket_text.value = BX( 'ticket_text_proxy' ).value;
        //
        //if( frm.ticket_text.value == '' ){
        //    alert( '<?//=GetMessage( "SC_NOT_FILLED" )?>//' );
        //    return;
        //}
        //
        //frm.submit();
    }
</script>
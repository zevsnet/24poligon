<?if( !check_bitrix_sessid() ) return;?>
<?echo CAdminMessage::ShowNote( GetMessage( "ACRIT_EXPORTPRO_MODULE" ) );

IncludeModuleLangFile(__FILE__);
?>

<table id="install_instruction">
	<tr>
        <td>
            <?=GetMessage( "ACRIT_EXPORTPRO_RECOMMENDS" );?>
        </td>
    </tr>
    <tr class="">
		<td>
            <form action="/bitrix/admin/partner_modules.php" method="GET">
                <input type="submit" class="adm-btn-save" value="<?=GetMessage( "MOD_BACK" )?>" />
            </form>
			<form action="/bitrix/admin/partner_modules.php" method="GET">
				<input type="hidden" name="id" value="a_crit.exportpro">
                <input type="hidden" name="lang" value="<?=LANGUAGE_ID?>">
                <input type="hidden" name="install" value="Y">
                <input type="hidden" name="sessid" value="<?=bitrix_sessid()?>">
                <input type="hidden" name="step" value="2">
                <input type="submit" class="adm-btn-save" value="<?=GetMessage( "ACRIT_EXPORTPRO_MOD_INSTALL" )?>" />
			</form>
		</td>
	</tr>
</table>
<style>
    table#install_instruction{
        width: 100%;
    }
    table#install_instruction form{
        height: 40px;
        margin-top: 20px;
        display: inline-block;
    }
    table#install_instruction tr td{
        font-size: 13px;
        line-height: 17px;
    }
</style>
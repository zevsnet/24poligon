<?

CModule::IncludeModule('idex.seo');
IncludeModuleLangFile(__FILE__);

$POST_RIGHT = $APPLICATION->GetGroupRight("idex.seo");
if ($POST_RIGHT < "W") $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

// определим все вкладки

## объявлем список вкладок
$aTabs = array(
	array(
		"DIV" => "idexseo_tab2", 
		"TAB" => "Настройки", 
		"ICON" => "sys_menu_icon", 
		"TITLE" => "Настройки",
	),	
	array(
		"DIV" => "idexseo_permission", 
		"TAB" => GetMessage("PERMISSION"), 
		"ICON" => "sys_menu_icon", 
		"TITLE" => GetMessage("PERMISSION_HEADER"),
	),	
);



// определим список доступных прав доступа :)
$arAllRight = array(
	"D"	=> GetMessage("RIGHT_D"),
	"W"	=> GetMessage("RIGHT_W"),
	"C"	=> GetMessage("RIGHT_C"),
	"M"	=> GetMessage("RIGHT_M"),
);

// get module rights
$obModules = CModule::GetList();
while ($arModule = $obModules->Fetch()){	
	if($arModule["ID"] == "idex.seo"){
        $arRights = $APPLICATION->GetDefaultRightList();
	} else {
		$info = CModule::CreateModuleObject($arModule["ID"]);
		$arModules["REFERENCE"][] = $info->MODULE_NAME;
		$arModules["REFERENCE_ID"][] = $arModule["ID"];
	}
}

// get site user groups
$obUserGroups = CGroup::GetList(($by="id"), ($order="asc"), array());
while($arUserGroups_one = $obUserGroups->Fetch()){
	$arUserGroups_one["RIGHT"] = $APPLICATION->GetGroupRight("idex.seo", array($arUserGroups_one["ID"]));
	$arUserGroups[] = $arUserGroups_one;
}

$arAllOptions = array(
	"idex.seo" => Array(		
		array("add_jquery", "Добавить JQUERY на сайт", COption::GetOptionString("idex.seo", "add_jquery", "Y"), Array("checkbox", "Y")),
	), 
);

$tabControl = new CAdminTabControl("tabControl", $aTabs);

if ($REQUEST_METHOD == "POST" && check_bitrix_sessid()) {
	
	$redirect = false;
	
	if(strlen($save) > 0 || strlen($apply) > 0) {
		foreach($arAllOptions as $cat=>$params){
			foreach($params as $param) {				
				COption::SetOptionString("idex.seo", $param[0], $_REQUEST[$param[0]]);
			}
		}
		$redirect = true;
		## save module rights
		
		foreach($_POST["RIGHTS"] as $gid=>$rid){
			if(!empty($rid)) { $APPLICATION->SetGroupRight("idex.seo", $gid, $rid); }
		}		
		
	}		
	
	if($redirect) { 
		$url = $APPLICATION->GetCurPage();
		$url .= "?mid=".urlencode($mid);
		$url .= "&lang=".urlencode(LANGUAGE_ID);
		$url .= "&back_url_settings=".urlencode($_REQUEST["back_url_settings"]);
		$url .= "&".$tabControl->ActiveTabParam();
		LocalRedirect($url);
	}
}

function ShowParamsHTMLByArray($arParams) {
	foreach($arParams as $Option){
	 	__AdmSettingsDrawRow("idex.seo", $Option);
	}
}

$tabControl->Begin();
?>

<form method="post" name="direct_settings_form" action="<?=$APPLICATION->GetCurPage()?>?mid=<?=urlencode($mid)?>&amp;lang=<?=LANGUAGE_ID?>">
	<?=bitrix_sessid_post();?>    
    <? $tabControl->BeginNextTab(); ?>
        <? ShowParamsHTMLByArray($arAllOptions["idex.seo"]);?>
	<? $tabControl->BeginNextTab();?>
	<? foreach($arUserGroups as $arUserGroup):?>
	<tr>
		<td width="50%"><?=$arUserGroup["NAME"]?>:</td>
		<td width="50%"><?=SelectBoxFromArray("RIGHTS[".$arUserGroup["ID"]."]", $arRights, $arUserGroup["RIGHT"]);?></td>
	</tr>
	<? endforeach;?>		
    <? 
    $tabControl->Buttons(
        array(
            "disabled" => false,
            "back_url" => $_REQUEST["back_url"],
        )				
    );?>  
    <? $tabControl->End();?>
</form>
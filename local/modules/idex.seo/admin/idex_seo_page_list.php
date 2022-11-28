<? require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php"); ?>
<?
IncludeModuleLangFile(__FILE__);
$APPLICATION->SetTitle(GetMessage('IDEX_SEO_LIST_TITLE'));
$POST_RIGHT = $APPLICATION->GetGroupRight("idex.seo");

if($POST_RIGHT == "D") {
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

CModule::IncludeModule("idex.seo");

global  $USER;
$by = !empty($_REQUEST["by"]) ? $_REQUEST["by"]:"ID";
$order = !empty($_REQUEST["order"]) ? $_REQUEST["order"]:"DESC";

$sTableID = "tbl_idex_seo_list"; ## 
$oSort = new CAdminSorting($sTableID, $by, $order); 
$lAdmin = new CAdminList($sTableID, $oSort); 

$FilterArr = Array(
	"find_id",
	"find_url",
	"find_active",	
	"find_query",
	"find_use_get",
	"find_use_inheritance"
);
$lAdmin->InitFilter($FilterArr);

if($find_url) {
	$find_url_prep = '%' . $find_url . '%';	
}
if($find_query) {
	$find_query_prep = '%' . $find_query . '%';	
}

$arFilter = array(
	"ID" => $find_id,
	"ACTIVE" => $find_active,	
	"URL" => $find_url_prep,
	"QUERY" => $find_query_prep,
	"USE_GET" => $find_use_get,
	"USE_INHERITANCE" => $find_use_inheritance,
);


## сохранение отредактированных элементов
if($lAdmin->EditAction()){		
	foreach($_REQUEST["FIELDS"] as $ID=>$arFields){
		## если небыло изменений в данной строке то прерываем ее обработку
		if(!$lAdmin->IsUpdated($ID)) continue;
		
		foreach($arFields as $k => $v) {
			$arFields[$k] =  trim($v);
		}
			
		## сохраняем изменения 		
		$upd = CIdexPageDb::Update($ID, $arFields);				
		
		if(!$upd){
			$lAdmin->AddGroupError(CIdexPageDb::$LAST_ERROR, $ID);	
		}
	}
}

## обработка одиночных и групповых действий
if($arID = $lAdmin->GroupAction()){
	
	if($_REQUEST['action_target']=='selected') {
		$rsData = CIdexSeoPage::GetList(array($by=>$order), $arFilter);
		while($arRes = $rsData->Fetch())
			$arID[] = $arRes['ID'];
	}
	
	
	foreach($arID as $ID){
		if(strlen($ID) <= 0) continue;
	   	$ID = intval($ID);
		
		## для каждого элемента совершим требуемое действие
		switch($_REQUEST['action']){
			## удаление
			case "delete":
				@set_time_limit(0);
				
				## удаляем запись
				CIdexPageDb::Delete($ID);
			break;
			case "activate":
				CIdexPageDb::Update($ID, array("ACTIVE" => "Y"));
			break;
			case "deactivate":
				CIdexPageDb::Update($ID, array("ACTIVE" => "N"));
			break;
		}
	}
}

## формируем заголовки таблицы
$lAdmin->AddHeaders(array(
	array(  
		"id"       => "ID",
		"content"  => "ID",
		"sort"     => "ID",
		"default"  => true,
	),
	array(  
		"id"    	=> "ACTIVE",
		"content"  	=> GetMessage('IDEX_SEO_ACTIVE'),
		"sort"     	=> "ACTIVE",
		"align"   	=> "center",
		"default"  	=> true,
	),
	array(  
		"id"    	=> "URL",
		"content"  	=> GetMessage('IDEX_SEO_URL'),
		"sort"     	=> "URL",
		"align"   	=> "left",
		"default"  	=> true,
	),
	array(  
		"id"    	=> "QUERY",
		"content"  	=> GetMessage('IDEX_SEO_QUERY'),
		"sort"     	=> "QUERY",
		"align"   	=> "left",
		"default"  	=> true,
	),
	array(  
		"id"    	=> "USE_GET",
		"content"  	=> GetMessage('IDEX_SEO_USE_GET'),
		"sort"     	=> "USE_GET",
		"align"   	=> "center",
		"default"  	=> true,
	),	
	array(  
		"id"    	=> "USE_INHERITANCE",
		"content"  	=> GetMessage('IDEX_SEO_USE_INHERITANCE'),
		"sort"     	=> "USE_INHERITANCE",
		"align"   	=> "center",
		"default"  	=> true,
	),
	array(  
		"id"    	=> "LINK",
		"content"  	=> GetMessage('IDEX_SEO_LINK'),
		"sort"     	=> false,
		"align"   	=> "right",
		"default"  	=> true,
	),	
	array(  
		"id"    	=> "TITLE",
		"content"  	=> GetMessage('IDEX_SEO_USE_TITLE'),
		"sort"     	=> "TITLE",
		"align"   	=> "left",
		"default"  	=> true,
	),
	array(  
		"id"    	=> "BROWSER_TITLE",
		"content"  	=> GetMessage('IDEX_SEO_BROWSER_TITLE'),
		"sort"     	=> "BROWSER_TITLE",
		"align"   	=> "left",
		"default"  	=> false,
	),
	array(  
		"id"    	=> "KEYWORDS",
		"content"  	=> GetMessage('IDEX_SEO_KEYWORDS'),
		"sort"     	=> "KEYWORDS",
		"align"   	=> "left",
		"default"  	=> false,
	),
	array(  
		"id"    	=> "DESCRIPTION",
		"content"  	=> GetMessage('IDEX_SEO_DESCRIPTION'),
		"sort"     	=> "DESCRIPTION",
		"align"   	=> "left",
		"default"  	=> false,
	),
	array(  
		"id"    	=> "SEO_TEXT",
		"content"  	=> GetMessage('IDEX_SEO_SEO_TEXT'),
		"sort"     	=> "SEO_TEXT",
		"align"   	=> "left",
		"default"  	=> false,
	),
    array(
        "id"    	=> "SEO_TEXT_2",
        "content"  	=> GetMessage('IDEX_SEO_SEO_TEXT_2'),
        "sort"     	=> "SEO_TEXT_2",
        "align"   	=> "left",
        "default"  	=> false,
    ),
));



## получаем 
$obList = CIdexSeoPage::GetList(array($by => $order), $arFilter);
## преобразуем список в экземпляр класса CAdminResult
$rsData = new CAdminResult($obList, $sTableID);
$rsData->NavStart();
$lAdmin->NavText($rsData->GetNavPrint(GetMessage("NAV")));

while($arRes = $rsData->NavNext()){  
	## создаем строку. результат - экземпляр класса CAdminListRow
	$row =& $lAdmin->AddRow($arRes["ID"], $arRes);  
	
	$row->AddInputField("URL", array("size"=>20));	
	$row->AddInputField("QUERY", array("size"=>20));			
	$row->AddInputField("TITLE", array("size"=>30));	
	$row->AddInputField("BROWSER_TITLE", array("size"=>30));		
	$row->AddInputField("KEYWORDS", array("size"=>30));
	$row->AddInputField("DESCRIPTION", array("size"=>30));
	$row->AddInputField("SEO_TEXT", array("size"=>30));	
	$row->AddInputField("SEO_TEXT_2", array("size"=>30));

	
	$row->AddCheckField("ACTIVE"); 
	$row->AddCheckField("USE_GET");
	$row->AddCheckField("USE_INHERITANCE");	

	$arRes['LINK'] = $arRes['URL'] . ($arRes['QUERY'] ? '?':'') . $arRes['QUERY'];

	$row->AddField(
		"LINK", 
		'<a target="_blank" href="'.$arRes['LINK'].'">'.GetMessage('IDEX_SEO_GO').'</a>'
	);

	// сформируем контекстное меню
	$arActions = Array();
	
	// редактирование элемента
	$arActions[] = array(
		"ICON" => "edit",
		"DEFAULT" => true,
		"TEXT" => GetMessage('IDEX_SEO_LINK'),
		"ACTION" => $lAdmin->ActionRedirect($arRes['LINK'])
	);
	
	// удаление элемента
	if ($POST_RIGHT >= "W"){
		$arActions[] = array(
			"ICON" => "delete",
			"TEXT" => "Удалить",
			"ACTION" => "if(confirm('Удалить?')) ".$lAdmin->ActionDoGroup($arRes["ID"], "delete")
		);
	}
	
	
	if ($POST_RIGHT >= "W" && ($arRes['ACTIVE'] != 'Y')){
		$arActions[] = array(
			"ICON" => "activate",
			"TEXT" => GetMessage("IDEX_SEO_ACTIVATE"),
			"ACTION" => $lAdmin->ActionDoGroup($arRes["ID"], "activate")
		);
	}


	if ($POST_RIGHT >= "W" && ($arRes['ACTIVE'] == 'N')){
		$arActions[] = array(
			"ICON" => "deactivate",
			"TEXT" => GetMessage("IDEX_SEO_DEACTIVATE"),
			"ACTION" => $lAdmin->ActionDoGroup($arRes["ID"], "deactivate")
		);
	}
	
	// применим контекстное меню к строке
  	$row->AddActions($arActions);
}


## групповые действия
$lAdmin->AddGroupActionTable(Array(
	"delete"=>GetMessage("MAIN_ADMIN_LIST_DELETE"), ## удалить выбранные элементы
	"activate"=>GetMessage("MAIN_ADMIN_LIST_ACTIVATE"), ## активировать выбранные элементы
	"deactivate"=>GetMessage("MAIN_ADMIN_LIST_DEACTIVATE"), ## деактивировать выбранные элементы
));

## резюме таблицы
$lAdmin->AddFooter(
  array(
    array("title"=>GetMessage("MAIN_ADMIN_LIST_SELECTED"), "value"=>$rsData->SelectedRowsCount()), ## кол-во элементов
    array("counter"=>true, "title"=>GetMessage("MAIN_ADMIN_LIST_CHECKED"), "value"=>"0"), ## счетчик выбранных элементов
  )
);

## сформируем меню
$aContext = array(
);

$lAdmin->AddAdminContextMenu($aContext);

## альтернативный вывод
$lAdmin->CheckListMode();

?>
<? require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php"); ?>
<?
$oFilter = new CAdminFilter(
  $sTableID."_filter",
  array(   
	GetMessage('IDEX_SEO_URL'),
	GetMessage('IDEX_SEO_QUERY'),
	GetMessage('IDEX_SEO_ACTIVE'),	
	GetMessage('IDEX_SEO_USE_GET'),
	GetMessage('IDEX_SEO_USE_INHERITANCE'),	
  )
);

?>
<form name="find_form" method="get" action="<?=$APPLICATION->GetCurPage();?>">
<? $oFilter->Begin();?>
	<tr>
	  <td>ID:</td>
	  <td>
		<input type="text" name="find_id" size="47" value="<?=htmlspecialchars($find_id)?>">
	  </td>
	</tr>
	<tr>
		<td><?=GetMessage('IDEX_SEO_URL')?>:</td>
		<td><input type="text" name="find_url" value="<?=htmlspecialcharsex($find_url)?>" size="47"></td>
	</tr>
	<tr>
		<td><?=GetMessage('IDEX_SEO_QUERY')?>:</td>
		<td><input type="text" name="find_query" value="<?=htmlspecialcharsex($find_query)?>" size="47"></td>
	</tr>
	<tr>
		<td><?=GetMessage('IDEX_SEO_ACTIVE')?>:</td>
		<td>
			<select name="find_active">
				<option value=""><?=GetMessage('IDEX_SEO_ANY')?></option>
				<option value="Y"<? if($find_active=="Y")echo " selected"?>><?=GetMessage('IDEX_SEO_Y')?></option>
				<option value="N"<? if($find_active=="N")echo " selected"?>><?=GetMessage('IDEX_SEO_N')?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td><?=GetMessage('IDEX_SEO_USE_GET')?>:</td>
		<td>
			<select name="find_use_get">
				<option value=""><?=GetMessage('IDEX_SEO_ANY')?></option>
				<option value="Y"<? if($find_use_get=="Y")echo " selected"?>><?=GetMessage('IDEX_SEO_Y')?></option>
				<option value="N"<? if($find_use_get=="N")echo " selected"?>><?=GetMessage('IDEX_SEO_N')?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td><?=GetMessage('IDEX_SEO_USE_INHERITANCE')?>:</td>
		<td>
			<select name="find_use_inheritance">
				<option value=""><?=GetMessage('IDEX_SEO_ANY')?></option>
				<option value="Y"<? if($find_use_inheritance=="Y")echo " selected"?>><?=GetMessage('IDEX_SEO_Y')?></option>
				<option value="N"<? if($find_use_inheritance=="N")echo " selected"?>><?=GetMessage('IDEX_SEO_N')?></option>
			</select>
		</td>
	</tr>	


<?
$oFilter->Buttons(array("table_id"=>$sTableID,"url"=>$APPLICATION->GetCurPage(),"form"=>"find_form"));
$oFilter->End();
?>
</form>
<?


## выведем таблицу списка элементов
$lAdmin->DisplayList();
?>
<? require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin_after.php"); ?>
<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

$arRes =  array('status' => 'OK');
CModule::IncludeModule('idex.seo');
$POST_RIGHT = $APPLICATION->GetGroupRight('idex.seo');
if ($POST_RIGHT == 'D') {
	$arRes['html'] = GetMessage('IDEX_SEO_NO_ACCESS');
	echo CIdexSeo::json_encode($arRes); die;
}

switch($_POST['action']) {
	case 'save':
		$arFields = $_POST;
		$arFields['URL'] = $arFields['PATH'];
		$res = CIdexSeoPage::Add($arFields);
		echo json_encode($arRes); die;
	break;
	case 'delete':
		$res = CIdexSeoPage::Delete($_POST['id']);
		echo json_encode($arRes); die;	
	break;
	default:
		$url = urldecode($_POST['url']);
		$obPage = new CIdexSeoPage($url, $check = true);	
	break;
}
ob_start();
?>
<form method='post' id='idex_seo_form'>
	<input type="hidden" name="action" id="idex_seo_action" value="">
    <input type="hidden" name="USE_INHERITANCE" value="N">
    <input type="hidden" name="USE_GET" value="N">
	<table class='idex_seo_table' style="width: 95%">
		<tr>
			<td colspan='2'><?=GetMessage('IDEX_SEO_FORM_TITLE')?></td>
		</tr>
		<? if($obPage->fields['NO_MATCH'] == 'Y') { ?>
			<tr>
				<td colspan="2"><b>Данные унаследованы со страницы: <?=$obPage->fields['URL']?><?=$obPage->fields['QUERY']?'?':''?><?=$obPage->fields['QUERY']?></b></td>
			</tr>
		<? } elseif($obPage->fields['ID']) { ?>
			<tr>
				<td colspan="2">
					<b>Данные взяты со страницы: <?=$obPage->fields['URL']?><?=$obPage->fields['QUERY']?'?':''?><?=$obPage->fields['QUERY']?></b>
					<a href="javascript:;" onclick="CIdexSeo.Delete(<?=$obPage->fields['ID']?>)" style="color: red; padding-left: 5px;">( Удалить )</a>
				</td>
			</tr>
		<? } ?>
		<tr>
			<td width="160">Адрес страницы:</td>
			<td align="left">
				<input type="hidden" value="<?=$obPage->path?>" name="PATH">
				<?=$obPage->path?></b>
			</td>
		</tr>

		<tr>
			<td colspan="2" align="center"><b>SEO параметры</b></td>
		</tr>		
		<tr style="height: 40px;">
			<td>Заголовок браузера:</td>
			<td align="left">
				<input type="text" name="BROWSER_TITLE" value="<?=$obPage->fields['BROWSER_TITLE']?>" style="width:100%">
			</td>
		</tr>		
		<tr style="height: 40px;">
			<td>Заголовок страницы:</td>
			<td align="left">
				<input type="text" name="TITLE" value="<?=$obPage->fields['TITLE']?>" style="width:100%">
			</td>
		</tr>	
		<tr style="height: 40px;" >
			<td>Ключевые слова (keywords):</td>
			<td align="left">
				<input type="text" name="KEYWORDS" value="<?=$obPage->fields['KEYWORDS']?>" style="width:100%">
			</td>
		</tr>
		<tr>
			<td style="vertical-align: top;">Описание страницы (description):</td>
			<td align="left">
				<textarea name="DESCRIPTION" style="width:100%"><?=$obPage->fields['DESCRIPTION']?></textarea>
                <br><br>
			</td>
		</tr>
		<tr>
			<td style="vertical-align: top;">Seo text:</td>
			<td align="left">

                <?$APPLICATION->IncludeComponent("bitrix:fileman.light_editor","",Array(
                        "CONTENT" => $obPage->fields['SEO_TEXT'],
                        "INPUT_NAME" => "SEO_TEXT",
                        "INPUT_ID" => "",
                        "WIDTH" => "100%",
                        "HEIGHT" => "300px",
                        "RESIZABLE" => "Y",
                        "AUTO_RESIZE" => "Y",
                        "VIDEO_ALLOW_VIDEO" => "Y",
                        "VIDEO_MAX_WIDTH" => "640",
                        "VIDEO_MAX_HEIGHT" => "480",
                        "VIDEO_BUFFER" => "20",
                        "VIDEO_LOGO" => "",
                        "VIDEO_WMODE" => "transparent",
                        "VIDEO_WINDOWLESS" => "Y",
                        "VIDEO_SKIN" => "/bitrix/components/bitrix/player/mediaplayer/skins/bitrix.swf",
                        "USE_FILE_DIALOGS" => "Y",
                        "ID" => "",
                        "JS_OBJ_NAME" => "",
						"TOOLBAR_CONFIG" => [
							'Source',
							'Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat',
							'CreateLink', 'DeleteLink', 'Image', 'Video',
							'BackColor', 'ForeColor',
							'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyFull',
							//'=|=',
							'InsertOrderedList', 'InsertUnorderedList', 'Outdent', 'Indent',
							'StyleList', 'HeaderList',
							'FontList', 'FontSizeList',
						]
                    )
                );?>

                <br><br>
			</td>
		</tr>
	</table>
</form>
<?
$form = ob_get_clean();
$form = str_replace(array("\r\n","\t"), "", $form);
$arRes['html'] = $APPLICATION->ConvertCharset($form, LANG_CHARSET, 'UTF-8');
header('Content-type: text/json; charset=UTF-8');
echo json_encode($arRes, true); die;
?>

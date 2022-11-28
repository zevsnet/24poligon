<?//$APPLICATION->ShowHeadScripts();?>
<?$APPLICATION->ShowAjaxHead();?>
<?
$_SESSION['REVIEW_SORT_PROP'] = $_REQUEST['sort'];
$_SESSION['REVIEW_SORT_ORDER'] = $_REQUEST['order'];
?>
<?
$ajaxData = array(
	'IBLOCK_ID' => $arParams['IBLOCK_ID'],
	'ELEMENT_ID' => $arElement['ID'],
	'SITE_ID' => SITE_ID,
);
?>
<script>
	var data = <?=CUtil::PhpToJSObject($ajaxData)?>;
	$.ajax({
		url: <?=CUtil::PhpToJSObject($_REQUEST['ajax_url'])?>+'?'+<?=CUtil::PhpToJSObject(bitrix_sessid_get())?>,
		type: 'post',
		data: data,
		success: function(html){
			$('<?="#".$_REQUEST['containerId']?>').replaceWith(html);
		}
	});
</script>
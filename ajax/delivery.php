<?
$isInline = strpos($_SERVER['SCRIPT_NAME'], '/ajax/') === false ? 'Y' : 'N';
$isPreview = isset($_POST['is_preview']) && $_POST['is_preview'] === 'Y' ? 'Y' : 'N';
$isPopup = $isInline === 'N' && $isPreview === 'N' ? 'Y' : 'N';

$productId = isset($_REQUEST['product_id']) && intval($_REQUEST['product_id']) > 0 ? intval($_REQUEST['product_id']) : false;
$quantity = isset($_REQUEST['quantity']) && floatval($_REQUEST['quantity']) > 0 ? floatval($_REQUEST['quantity']) : 0;

if($isInline === 'N'){
	require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

	if($isPopup === 'Y'){
		$GLOBALS['APPLICATION']->ShowAjaxHead();
		$areaIndex = 1000;
	}
	else{
		$areaIndex = isset($_POST['index']) && intval($_POST['index']) > 0 ? intval($_POST['index']) : 1001;
	}

	if($GLOBALS['APPLICATION']->GetShowIncludeAreas()){
		$GLOBALS['APPLICATION']->editArea = new CEditArea();
		$GLOBALS['APPLICATION']->editArea->includeAreaIndex = array(0 => $areaIndex);
		if($isPopup === 'Y'){
			?><style>.bx-core-adm-dialog, div.bx-component-opener, .bx-core-popup-menu{z-index:3001 !important;}</style><?
		}
	}
}

if($isPopup === 'Y'){
	?><a href="#" class="close jqmClose"><?=CMax::showIconSvg('', SITE_TEMPLATE_PATH.'/images/svg/Close.svg')?></a><?
}

include __DIR__.'/../include/comp_catalog_delivery.php';

if($isInline === 'N'){
	require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php');
}
?>
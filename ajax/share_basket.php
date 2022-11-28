<?
$isInline = strpos($_SERVER['SCRIPT_NAME'], '/ajax/') === false ? 'Y' : 'N';
$isPopup = $isInline === 'N' ? 'Y' : 'N';

if($isPopup === 'Y'){
	// preload site header !!!it`s need!!!
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
	$GLOBALS['APPLICATION']->RestartBuffer();

	// show ajax css, js, strings
	$GLOBALS['APPLICATION']->ShowAjaxHead();

	if($GLOBALS['APPLICATION']->GetShowIncludeAreas()){
		$areaIndex = isset($_POST['index']) && intval($_POST['index']) > 0 ? intval($_POST['index']) : 1000;
		$GLOBALS['APPLICATION']->editArea = new CEditArea();
		$GLOBALS['APPLICATION']->editArea->includeLevel = 0;
		$GLOBALS['APPLICATION']->editArea->includeAreaIndex = array(0 => $areaIndex);

		// open div of editing this page
		?><div id="bx_incl_area_<?=$areaIndex?>"><style>.bx-core-adm-dialog, div.bx-component-opener, .bx-core-popup-menu{z-index:3001 !important;}</style><?
	}

	?><a href="#" class="close jqmClose"><?=CMax::showIconSvg('', SITE_TEMPLATE_PATH.'/images/svg/Close.svg')?></a><?
}

$shareBasketPageUrl = Bitrix\Main\Config\Option::get(CMax::moduleID, 'SHARE_BASKET_PAGE_URL', '#'.'SITE_DIR'.'#'.'sharebasket/', SITE_ID);
$shareBasketPageUrl = str_replace('#'.'SITE_DIR'.'#', SITE_DIR, $shareBasketPageUrl).'/index.php';
$shareBasketPageUrl = preg_replace('/\/{2,}/', '/', $shareBasketPageUrl);

include __DIR__.'/..'.$shareBasketPageUrl;

if($isPopup === 'Y'){
	if($GLOBALS['APPLICATION']->GetShowIncludeAreas()){
		// close div of editing this page
		?></div><?
	}

	require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php');
	die();
}

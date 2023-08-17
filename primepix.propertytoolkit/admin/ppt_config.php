<?
// main vars
global $APPLICATION;
$MODULE_ID       = 'primepix.propertytoolkit';
$IBLOCK_ID       = (int)$_GET['iblock'];
$IBLOCK_TYPE     = $_GET['types'];
$FOLDER          = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/log/' . $MODULE_ID . '/';
$ACTION_PATH     = 'undo-action.log';
$LOG_PATH        = 'history.log';
$PATH_TO_VIEWS   = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . $MODULE_ID . '/views/';
$DIR             = $APPLICATION->GetCurDir();
$PROPERTY_ID     = !empty($_GET["PROPERTY_ID"]) ? $_GET["PROPERTY_ID"] : '';

// check filter input and validate
if (function_exists('filter_input')) {
	$ELEMENT_ID = filter_input(INPUT_GET, 'element', FILTER_VALIDATE_INT);
} else {
	$ELEMENT_ID = isset($_GET['element']) ? (int)$_GET['element'] : NULL;
}

// include module
CModule::IncludeModule($MODULE_ID);

// create logs path if nessessary
$LOG_ACCESSABLE = true;
if (!is_dir($FOLDER)) {
	$LOG_ACCESSABLE = mkdir($FOLDER, BX_DIR_PERMISSIONS, TRUE);
}

// get last action file
$CANCEL_POSSIBLE = false;
if ($LOG_ACCESSABLE) {
	$CANCEL_POSSIBLE = CActionSaver::getInstance()->init($FOLDER . $ACTION_PATH)->checkFile();
}

// save last options
if ($IBLOCK_ID) {
	COption::SetOptionString($MODULE_ID, 'last_iblock', $IBLOCK_ID);
}
if ($IBLOCK_TYPE) {
	COption::SetOptionString($MODULE_ID, 'last_type', $IBLOCK_TYPE);
}

// get iblocks
$firstIblock = 0;
CModule::IncludeModule('iblock');
$dbRes = CIBlock::GetList(array('ID'=>'ASC'),array('ACTIVE_DATE' => 'Y', 'ACTIVE' => 'Y'));
if ($arRes = $dbRes->Fetch())  {  
    $firstIblock = $arRes['ID']; 
  	$firstType   = $arRes['IBLOCK_TYPE_ID'];
}

$IBLOCK_ID       = COption::GetOptionString($MODULE_ID, 'last_iblock', $firstIblock);
$IBLOCK_TYPE     = COption::GetOptionString($MODULE_ID, 'last_type', $firstType);

if (!$IBLOCK_ID || empty($IBLOCK_ID)) {
	$IBLOCK_ID   = $firstIblock;
}
if (!$IBLOCK_TYPE || empty($IBLOCK_TYPE)) {
	$IBLOCK_TYPE   = $firstType;
}


// include pages lang file
IncludeModuleLangFile($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/primepix.propertytoolkit/admin/pages.php');

// init scripts & styles
CJSCore::RegisterExt('my_ext', array(
	'js'   => '/bitrix/js/' . $MODULE_ID . '/scripts.js',
	'css'  => '/bitrix/js/' . $MODULE_ID . '/styles.css',
	'lang' => '/bitrix/modules/' . $MODULE_ID . '/lang/ru/admin/js.php',
	'rel'  => array('jquery')
));
CJSCore::RegisterExt('tablesort', array(
	'js'   => '/bitrix/js/' . $MODULE_ID . '/tablesort.min.js',
	'css'  => '/bitrix/js/' . $MODULE_ID . '/styles.css',
	'lang' => '',
	'rel'  => array('my_ext')
));
CJSCore::Init('tablesort');
?>
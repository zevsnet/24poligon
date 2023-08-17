<?
define('STOP_STATISTICS', TRUE);
define('NO_AGENTS_CHECK', TRUE);

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');
require_once('ppt_config.php');

// include module
CModule::IncludeModule($MODULE_ID);

$ACTION = (empty($_REQUEST['ACTION']) ? 'NO-ACTION' : $_REQUEST['ACTION']);
$TYPE   = (empty($_REQUEST['TYPE']) ? 'NO-TYPE' : $_REQUEST['TYPE']);

// init logger & actionSaver
if (!is_dir($FOLDER)) {
	mkdir($FOLDER, BX_DIR_PERMISSIONS, TRUE);
}

$actionFile = $FOLDER . $ACTION_PATH;
$logFile    = $FOLDER . $LOG_PATH;

CActionSaver::getInstance()->init($actionFile);
CPptLogger::getInstance()->init($logFile);
CPptLogger::getInstance()->log(sprintf('ACTION: %s', $ACTION));

$actManager = new CActionManager(CFactory::createOperator($TYPE));

switch($ACTION) {

	case 'CONVERT_TO_NUMBER':
		$propsIds = $_REQUEST['PROPS'];
		$actManager->actionConvertProps2Number($propsIds);
		break;

	case 'CONVERT_TO_LIST':
		$propsIds = $_REQUEST['PROPS'];
		$actManager->actionConvertProps2List($propsIds);
		break;

	case 'CONVERT_TO_STRING':
		$propsIds = $_REQUEST['PROPS'];
		$actManager->actionConvertProps2String($propsIds);
		break;

	case 'EDIT':
		$object  = $_REQUEST['OBJECT'];
		$propId  = $_REQUEST['PROPEL'];
		$propVal = $_REQUEST['PROPVAL'];
		$actManager->actionEditProps($object, $propId, $propVal);
		break;

	case 'DELETE_PROPS':
		$propsIDs = $_REQUEST['PROPS'];
		$actManager->actionDeleteProps($propsIDs);
		break;

	case 'DELETE_VALUES':
		$valuesIds = $_REQUEST['PROPS'];
		$propId    = $_REQUEST['PROPERTY_ID'];
		$actManager->actionDeletePropsValues($propId, $valuesIds);
		break;

	case 'DELETE_UNUSED_VALUES':
		$propsIds = $_REQUEST['PROPS'];
		$actManager->actionDeleteUnusedValues($propsIds);
		break;

	case 'MERGE_PROPS':
		$propsIds = $_REQUEST['PROPS'];
		$leading  = intval($_POST['LEADING']);
		$actManager->actionMergeProps($propsIds, $leading);
		break;

	case 'MERGE_VALUES':
		$valuesIds = $_REQUEST['PROPS'];
		$leading   = intval($_POST['LEADING']);
		$actManager->actionMergeValues($valuesIds, $leading);
		break;

	case 'MERGE_ALL_VALUES':
		$groups = $_REQUEST['GROUPS'];
		$actManager->actionMergeAllValues($groups);
		break;

	case 'MOVE_VALUES':
		$valuesIds    = $_REQUEST['PROPS'];
		$prop2Move    = intval($_POST['PROP_TO_MOVE']);
		$propFromMove = intval($_POST['PROP_FROM_MOVE']);
		$actManager->actionMoveValues($valuesIds, $prop2Move, $propFromMove);
		break;

	case 'EDIT_LINKS':
		$propId  = $_REQUEST['PROPEL'];
		$valueId = $_REQUEST['PROPVAL'];
		$links   = $_REQUEST['LINKS'];
		$actManager->actionEditLinks($propId, $valueId, $links);
		break;

	case 'CLEAR_ALL_SAME_LINKS':
		$propsIds = $_REQUEST['PROPS'];
		$actManager->actionClearAllSameLinks($propsIds);
		break;

	case 'CREATE_PROP':
		$iblockId = $_REQUEST['IBLOCK_ID'];
		$params   = $_REQUEST['PARAMS'];
		$actManager->actionCreateProp($iblockId, $params);
		break;

	case 'CANCEL':
		$actManager->actionCancel();
		break;

	default:
		die('no action');
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin_after.php');
?>
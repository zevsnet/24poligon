<?
global $DBType;

$MODULE_ID = 'primepix.propertytoolkit';

CModule::AddAutoloadClasses(
	$MODULE_ID,
	array(
		'CPptLogger'         => 'classes/general/CPptLogger.php',
		'CActionSaver'       => 'classes/general/CActionSaver.php',
		'CFactory'           => 'classes/general/CFactory.php',
		'CActionManager'     => 'classes/general/CActionManager.php',
		'CPptUtils'          => 'classes/general/CPptUtils.php',
		'CDbBaseOperator'    => 'classes/' . $DBType . '/CDbBaseOperator.php',
		'CDbListOperator'    => 'classes/' . $DBType . '/CDbListOperator.php',
		'CDbNumberOperator'  => 'classes/' . $DBType . '/CDbNumberOperator.php',
		'CDbStringOperator'  => 'classes/' . $DBType . '/CDbStringOperator.php',
	)
);

?>
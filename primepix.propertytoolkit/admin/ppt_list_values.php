<?
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_admin_before.php");
require_once('ppt_config.php');
$APPLICATION->SetTitle(
	GetMessage($MODULE_ID . '_MODULE_NAME_PAGE') . 
	GetMessage($MODULE_ID . '_PROPS_VALUES_EDIT')
);

// operator
$dbOp = new CDbListOperator;

// build table
$propTable     = '';
$propTableRows = array();

//get values table rows
$dbProp = $dbOp->getValuesList($PROPERTY_ID);
$hashArr  = array();

while($arProp = $dbProp->Fetch()) {

	$hash = md5( preg_replace('@\s{2,}@i', ' ', $arProp['VALUE']) );
	$hashArr[$hash][] = $arProp['VALUE_ID'];

	//get products ID, connected with value
	$dbProducts = $dbOp->getConnectedElems($PROPERTY_ID, $arProp['VALUE_ID']);

	$productsEditLinks = array();
	$productsEditIds   = array();
	while($arProducts = $dbProducts->Fetch()) {

		$productsEditLinks[] = sprintf('<div class="product" data-id="%s"><a class="-view-products" \
			href="%s?IBLOCK_ID=%d&type=%s&ID=%d" target="_blank">%d</a><div class="hint">%s</div></div>',
			$arProducts["IBLOCK_ELEMENT_ID"],
			SITE_SERVER_NAME . "/bitrix/admin/iblock_element_edit.php",
			$IBLOCK_ID,
			$IBLOCK_TYPE,
			$arProducts["IBLOCK_ELEMENT_ID"],
			$arProducts["IBLOCK_ELEMENT_ID"],
			$arProducts["ELEMENT_NAME"]
		);
		$productsEditIds[] = $arProducts["IBLOCK_ELEMENT_ID"];
	
	}

	// add table row
	$propTableRows[] = CPptUtils::getView(
		$PATH_TO_VIEWS . 'table_list_values_row',
		array(
			'arProp'            => $arProp,
			'hash'              => $hash,
			'productsEditLinks' => join(',', $productsEditLinks),
			'productsEditIds'   => join(',', $productsEditIds),
			'MODULE_ID'         => $MODULE_ID
		),
		TRUE
	);

	if (!isset($multiple)) {
		$multiple = ($arProp['MULTIPLE'] == 'Y') ? true : false;
	}

}

// add table header
$propTable = CPptUtils::getView(
	$PATH_TO_VIEWS . 'table_list_values_header',
	array('MODULE_ID' => $MODULE_ID),
	TRUE
);

// add table body
$propTable .= CPptUtils::getView(
	$PATH_TO_VIEWS . 'table_body',
	array('TABLE_ROWS' => join('', $propTableRows)),
	TRUE
);

// add select form
$selectForm = CpptUtils::getView(
	$PATH_TO_VIEWS . 'select_form',
	array(
		'MODULE_ID'  => $MODULE_ID, 
		'IBLOCK_ID'  => $IBLOCK_ID, 
		'DIR'        => $DIR,
		'ELEMENT_ID' => $ELEMENT_ID,
		'selected'   => 'LIST',
		'multiple'   => $multiple
	),
	TRUE
);

$dublicateCount = 0;
if (!empty($hashArr)) {
	foreach ($hashArr as $hash) {
		if (count($hash) > 1) {
			$dublicateCount++;
		}
	}
}


require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');

if (!$LOG_ACCESSABLE) {

	CAdminMessage::ShowMessage(GetMessage($MODULE_ID . '_WARNING_NO_LOG_ACCESS'));
	require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');
	exit();
}


?>

<div class="list-container -toolkit-list-container">

	<?=$selectForm?>

	<?if (!empty($hashArr)):?>
		<form action="" method="POST" class="properties-form">
			<div class="table-wrap">
				<?=$propTable?>
			</div>
			<div class="footer-panel -footer-panel">
				<div class="controls-wrap">
					<button class="adm-btn -delete -values" data-prop-id="<?=$PROPERTY_ID?>"><?=GetMessage($MODULE_ID . "_ACTION_DELETE")?></button>
					<?if ($dublicateCount > 0):?>
						<button class="-merge-all-values -view-help adm-btn" data-prop-id="<?=$PROPERTY_ID?>">
							<?=GetMessage($MODULE_ID . "_ACTION_MERGE_ALL_VALUES")?> (<?=$dublicateCount?>)
							<div class="hint"><?=GetMessage($MODULE_ID . "_ACTION_MERGE_ALL_VALUES_HINT")?></div>
						</button>
					<?endif;?>
					<input type="text" class="-prop2move" placeholder="<?=GetMessage($MODULE_ID . "_COLUMN_ID_VALUES_HEADER")?>">
					<button class="-move -view-help adm-btn" data-prop-id="<?=$PROPERTY_ID?>">
						<?=GetMessage($MODULE_ID . "_ACTION_MOVE_VALUES")?>
						<div class="hint"><?=GetMessage($MODULE_ID . "_ACTION_MOVE_VALUES_HINT")?></div>
					</button>
					<button class="-cancel -view-help cancel-action adm-btn" <?=$CANCEL_POSSIBLE ? 'style="display: inline-block"' : ''?>>
						<?=GetMessage($MODULE_ID . "_ACTION_CANCEL")?>
						<div class="hint"><?=GetMessage($MODULE_ID . "_ACTION_CANCEL_HINT")?></div>
					</button>
				</div>
				<div class="loader-wrap">
					<div class="page-loader -ajax-loader"></div>
				</div>
			</div>
		</form>
		<a class="merge-link -merge -values" href="#mergeProps" data-propd-id="<?=$PROPERTY_ID?>"><?=GetMessage($MODULE_ID . "_ACTION_MERGE")?></a>
		<div class="hint-container -hint-container"></div>
		<div class="message-box -message-box"></div>
	<?else:?>
		<h3><?=GetMessage($MODULE_ID . "_NO_VALUES")?></h3>
	<?endif?>
</div>

<? require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/epilog_admin.php"); ?>

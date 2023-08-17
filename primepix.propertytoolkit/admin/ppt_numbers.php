<?
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_admin_before.php");
require_once('ppt_config.php');
$APPLICATION->SetTitle(
	GetMessage($MODULE_ID . '_MODULE_NAME_PAGE') . 
	GetMessage($MODULE_ID . '_PROPS_TYPE_NUMBER')
);

// operator
$dbOp = new CDbNumberOperator;

// get main table rows
$dbRes = $dbOp->getPropsList($IBLOCK_ID, $ELEMENT_ID);

// save id's and rows and values first 10
$connectedValues = array();

while($arRes = $dbRes->Fetch()) {
	$propsIDs[] = $arRes['ID'];
	$arProps[]  = $arRes;

	// get connected values
	$dbValues = $dbOp->getConnectedLinks($arRes['ID'], 10);
	while ($arValue = $dbValues->Fetch()) {
		$connectedValues[$arRes['ID']][] = $arValue['VALUE'];
	}

}

$propTable      = '';
$propTableRows  = array();
$unusedProps    = 0;
$propsWithSame  = array();
$propsSameCount = 0;


if (!empty($arProps)) {

	$dbSame = $dbOp->getPropsWithSameLinks($IBLOCK_ID);

	while ($arSame = $dbSame->Fetch()) {
		$propsWithSame[$arSame['ID']] = $arSame['ID'];
		$propsSameCount += --$arSame['COUNT'];
	}

	foreach($arProps as $key => $arProp) {

		$isPropEmpty  = false;
		$dataSame     = false;
		$previewBlock = '';

		if ($arProp['VQTY'] == 0) {
			$unusedProps++;
			$isPropEmpty = true;
		}

		if (in_array($arProp['ID'], $propsWithSame)) {
			$dataSame = true;
		}
		$dataEmpty = $isPropEmpty ? 'true' : 'false';


		if (!empty($connectedValues[$arProp['ID']])) {
			$previewBlock  = '<div class="hint">' . join("; <br>", $connectedValues[$arProp['ID']]) . '</div>';
		}

		$variantsViewBlock = sprintf('<a class="-view-values" href="%s?PROPERTY_ID=%d&ACTION=%s" target="_blank">%s</a> %s',
			'ppt_number_values.php',
			$arProp["ID"],
			'VIEW',
			GetMessage($MODULE_ID . '_ACTION_VALUES_EDIT'),
			$previewBlock
		);
		$propConvertBlock = sprintf('<a class="-convert-single convert-link" href="" data-id="%d" data-action="%s">%s</a>',
			$arProp['ID'],
			'CONVERT_TO_LIST',
			GetMessage($MODULE_ID . '_ACTION_CONVERT_TO_LIST')
		);
		$propConvertBlock .= sprintf('<a class="-convert-single convert-link" href="" data-id="%d" data-action="%s">%s</a>',
			$arProp['ID'],
			'CONVERT_TO_STRING',
			GetMessage($MODULE_ID . '_ACTION_CONVERT_TO_STRING')
		);

		// add table row
		$propTableRows[] = CPptUtils::getView(
			$PATH_TO_VIEWS . 'table_number_row',
			array(
				'variantsViewBlock' => $variantsViewBlock,
				'arProp'            => $arProp,
				'dataEmpty'         => $dataEmpty,
				'dataSame'          => $dataSame,
				'convertBlock'      => $propConvertBlock
			),
			TRUE
		);
	}
}

// add table header
$propTable = CPptUtils::getView(
	$PATH_TO_VIEWS . 'table_number_header',
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
		'selected'   => 'NUMBER',

	),
	TRUE
);

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');

if (!$LOG_ACCESSABLE) {

	CAdminMessage::ShowMessage(GetMessage($MODULE_ID . '_WARNING_NO_LOG_ACCESS'));
	require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/epilog_admin.php");
	exit();
}
?>
	
<div class="list-container -toolkit-list-container">
	
	<?=$selectForm?>

	<?if (!empty($arProps)):?>
		<form action="" method="POST" class="properties-form">
			<div class="table-wrap">
				<?=$propTable?>
			</div>
			<div class="footer-panel -footer-panel">
				<div class="controls-wrap">
					<button class="adm-btn -delete -properties"><?=GetMessage($MODULE_ID . "_ACTION_DELETE")?></button>
					<button class="adm-btn -open-popup" type="submit" name="save"><?=GetMessage($MODULE_ID . '_CREATE_PROP_BUTTON')?></button>
					<?if ($propsSameCount > 0):?>
						<button class="adm-btn -clear-all-same-links -view-help" data-action="CLEAR_ALL_SAME_LINKS">
							<?=GetMessage($MODULE_ID . "_ACTION_CLEAR_ALL_SAME_LINKS")?> (<span class="-count"><?=$propsSameCount?></span>)
							<div class="hint"><?=GetMessage($MODULE_ID . "_ACTION_CLEAR_ALL_SAME_LINKS_HINT")?></div>
						</button>
					<?endif?>
					<?if ($unusedProps > 0):?>
						<button class="-delete-empty-props -view-help adm-btn">
							<?=GetMessage($MODULE_ID . "_ACTION_DELETE_ALL_WITHOUT_VALUES")?> (<?=$unusedProps?>)
							<div class="hint"><?=GetMessage($MODULE_ID . "_ACTION_DELETE_ALL_WITHOUT_VALUES_HINT")?></div>
						</button>
					<?endif;?>
					<button class="-cancel cancel-action -view-help adm-btn" <?=$CANCEL_POSSIBLE ? 'style="display: inline-block"' : ''?>>
						<?=GetMessage($MODULE_ID . "_ACTION_CANCEL")?>
						<div class="hint"><?=GetMessage($MODULE_ID . "_ACTION_CANCEL_HINT")?></div>
					</button>
				</div>
				<div class="loader-wrap">
					<div class="page-loader -ajax-loader"></div>
				</div>
			</div>
		</form>
	
		<a class="merge-link -merge -properties" href="#mergeProps"><?=GetMessage($MODULE_ID . "_ACTION_MERGE")?></a>
		<div class="hint-container -hint-container"></div>
		<div class="message-box -message-box"></div>
	<?else:?>
		<h3><?=GetMessage($MODULE_ID . "_NO_RESULTS")?></h3>
	<?endif?>
</div>

<? require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/epilog_admin.php"); ?>

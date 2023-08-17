<?
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');
require_once('ppt_config.php');
$APPLICATION->SetTitle(
	GetMessage($MODULE_ID . '_MODULE_NAME_PAGE') . 
	GetMessage($MODULE_ID . '_PROPS_TYPE_LIST')
);

// operator
$dbOp = new CDbListOperator;

// get main table rows
$dbRes = $dbOp->getPropsList($IBLOCK_ID, $ELEMENT_ID);

// save id's & rows
while($arRes = $dbRes->Fetch()) {
	$propsIDs[] = $arRes['ID'];
	$arProps[]  = $arRes;
}

// get enum values
if (!empty($propsIDs)) {

	$dbSubRes  = $dbOp->getEnumValues($propsIDs);

	$emptyPropsCount     = 0;
	$emptyPropsValsCount = 0;

	while($arSubRes = $dbSubRes->Fetch()) {

		$propertyID = $arSubRes['PROPERTY_ID'];
		$enumValues[ $propertyID ][] = $arSubRes['VALUE'];

		$dbProducts = $dbOp->getConnectedElems($propertyID, $arSubRes['ID']);
		$productsCount = intval($dbProducts->SelectedRowsCount());
		if ($productsCount <= 0) {
			$emptyPropsValsCount++;
		}
	}

	$propsWithSame  = array();
	$propsSameCount = 0;
	$dbSame = $dbOp->getPropsWithSameLinks($IBLOCK_ID);

	while ($arSame = $dbSame->Fetch()) {
		$propsWithSame[$arSame['ID']] = $arSame['ID'];
		$propsSameCount += --$arSame['COUNT'];
	}

}




// build table
$propTable         = '';
$propTableRows     = array();
$convertPropsCount = 0;

if (!empty($arProps)) {

	foreach($arProps as $key => $arProp) {

		$impossible2Convert = true;
		$dataSame     = false;
		$isPropEmpty  = true;
		$posValCount  = 0;
		$previewBlock = '';
		$propValues   = $enumValues[ $arProp['ID'] ];

		// check if prop empty
		if (empty($propValues)) {

			$emptyPropsCount++;

		} else {

			$isPropEmpty = false;
			// check if impossible to convert to number %)
			foreach ($propValues as $value) {
				if (preg_match('/^[\.,\d]+$/i' , trim($value))) {
					$posValCount++;
				}
			}

		}

		if ($posValCount != 0 && $posValCount == count($enumValues[ $arProp['ID'] ])) {
			$impossible2Convert = false;
			$convertPropsCount++;
		}

		if (!empty($enumValues[ $arProp['ID'] ])) {
			$previewValues = array_slice($enumValues[ $arProp['ID'] ], 0, 30);
			$previewBlock  = '<div class="hint">' . join("; <br>", $previewValues) . '</div>';
		}

		if (in_array($arProp['ID'], $propsWithSame)) {
			$dataSame = true;
		}
		$dataEmpty     = $isPropEmpty ? 'true'  : 'false';
		
		$variantsViewBlock = sprintf('<a class="-view-values" href="%s?PROPERTY_ID=%d&ACTION=%s" target="_blank">%s</a> %s',
			'ppt_list_values.php',
			$arProp["ID"],
			'VIEW',
			GetMessage($MODULE_ID . '_ACTION_VALUES_EDIT'),
			count($previewValues) != 0 ? $previewBlock : ''
		);

		$propConvertBlock = sprintf('<a class="-convert-single convert-link" href="" data-id="%d" data-action="%s">%s</a>',
			$arProp['ID'],
			'CONVERT_TO_STRING',
			GetMessage($MODULE_ID . '_ACTION_CONVERT_TO_STRING')
		);

		if (!$impossible2Convert) {
			$propConvertBlock .= sprintf('<a class="-convert-single convert-link" href="" data-id="%d" data-action="%s">%s</a>',
				$arProp["ID"],
				'CONVERT_TO_NUMBER',
				GetMessage($MODULE_ID . '_ACTION_CONVERT_TO_NUM')
			);
		}
		
		// add table row
		$propTableRows[] = CPptUtils::getView(
			$PATH_TO_VIEWS . 'table_list_row',
			array(
				'arProp'           => $arProp,
				'dataEmpty'        => $dataEmpty,
				'dataSame'         => $dataSame,
				'variantsBlock'    => $variantsViewBlock,
				'convertBlock'     => $propConvertBlock
			),
			TRUE
		);
	}
}

// add table header
$propTable = CPptUtils::getView(
	$PATH_TO_VIEWS . 'table_list_header',
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
		'selected'   => 'LIST'),
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
					<button class="adm-btn first -delete -properties"><?=GetMessage($MODULE_ID . "_ACTION_DELETE")?></button>
					<button class="adm-btn -open-popup" type="submit" name="save"><?=GetMessage($MODULE_ID . '_CREATE_PROP_BUTTON')?></button>
					<?if ($propsSameCount > 0):?>
						<button class="adm-btn -clear-all-same-links -view-help" data-action="CLEAR_ALL_SAME_LINKS">
							<?=GetMessage($MODULE_ID . "_ACTION_CLEAR_ALL_SAME_LINKS")?> (<span class="-count"><?=$propsSameCount?></span>)
							<div class="hint"><?=GetMessage($MODULE_ID . "_ACTION_CLEAR_ALL_SAME_LINKS_HINT")?></div>
						</button>
					<?endif?>
					<?if ($convertPropsCount > 1):?>
						<button class="adm-btn alert -convert-all -view-help" data-possible="<?=$posPropCount?>" data-action="CONVERT_TO_NUMBER">
							<?=GetMessage($MODULE_ID . "_ACTION_CONVERT_TO_NUM_ALL")?> (<span class="-count"><?=$convertPropsCount?></span>)
							<div class="hint"><?=GetMessage($MODULE_ID . "_ACTION_CONVERT_TO_NUM_ALL_HINT")?></div>
						</button>
					<?endif;?>
					<?if ($emptyPropsValsCount > 0):?>
						<button class="adm-btn alert  -delete-unused-values -view-help">
							<?=GetMessage($MODULE_ID . "_ACTION_DELETE_ALL_UNUSED")?> (<?=$emptyPropsValsCount?>)
							<div class="hint"><?=GetMessage($MODULE_ID . "_ACTION_DELETE_ALL_UNUSED_HINT")?></div>
						</button>
					<?endif;?>
					<?if ($emptyPropsCount > 0):?>
						<button class="adm-btn alert -delete-empty-props -view-help">
							<?=GetMessage($MODULE_ID . "_ACTION_DELETE_ALL_WITHOUT_VALUES")?> (<?=$emptyPropsCount?>)
							<div class="hint"><?=GetMessage($MODULE_ID . "_ACTION_DELETE_ALL_WITHOUT_VALUES_HINT")?></div>
						</button>
					<?endif;?>
					<button class="adm-btn cancel-action -cancel  -view-help" <?=$CANCEL_POSSIBLE ? 'style="display: inline-block"' : ''?>>
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
		<div class="message-box -message-box"></div>
		<div class="hint-container -hint-container"></div>
	<?else:?>
		<h3><?=GetMessage($MODULE_ID . "_NO_RESULTS")?></h3>
	<?endif?>
</div>

<? require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/epilog_admin.php"); ?>

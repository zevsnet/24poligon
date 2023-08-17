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
$dbRes = $dbOp->getPropsList($IBLOCK_ID);

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
}

$convertPropsCount = 0;

// build table
$propTable         = '';
$propTableRows     = array();
$convertPropsCount = 0;
$arHeaders         = array(
	array('NO_SORT' => 'no-sort'),
	array('NO_SORT' => 'no-sort'),
	array('NAME' => GetMessage($MODULE_ID . '_COLUMN_ID_HEADER')),
	array('NAME' => GetMessage($MODULE_ID . '_COLUMN_NAME_HEADER'), 'DEFAULT' => 'sort-up'),
	array('NAME' => GetMessage($MODULE_ID . '_COLUMN_CODE_HEADER')),
	array('NAME' => GetMessage($MODULE_ID . '_COLUMN_PQTY_HEADER')),
	array('NAME' => GetMessage($MODULE_ID . '_COLUMN_VQTY_HEADER')),
	array('NAME' => GetMessage($MODULE_ID . '_COLUMN_VALUES_HEADER'), 'NO_SORT'  => 'no-sort'),
	array('NAME' => GetMessage($MODULE_ID . '_COLUMN_CONVERT_HEADER'), 'NO_SORT' => 'no-sort'),
);

if (!empty($arProps)) {

	foreach($arProps as $key => $arProp) {

		$impossible2Convert = true;
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

		$dataEmpty     = $isPropEmpty ? 'true' : 'false';

		$variantsViewBlock = sprintf('<a class="-view-values" href="%s?PROPERTY_ID=%d&ACTION=%s" target="_blank">%s</a> %s',
			'ppt_values.php',
			$arProp["ID"],
			'VIEW',
			GetMessage($MODULE_ID . '_ACTION_VALUES_EDIT'),
			count($previewValues) != 0 ? $previewBlock : ''
		);

		$propConvertBlock = FALSE;
		if (!$impossible2Convert) {
			$propConvertBlock = sprintf('<div class="convert">
					<a class="-convert-single convert-link" href="" data-id="%d" data-action="%s">%s</a>
				</div>',
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
				'variantsBlock'    => $variantsViewBlock,
				'convertBlock'     => $propConvertBlock
			),
			TRUE
		);
	}
}

// add table header
$propTable = CPptUtils::getView(
	$PATH_TO_VIEWS . 'table_header',
	$arHeaders
);

// add table body
$propTable .= CPptUtils::getView(
	$PATH_TO_VIEWS . 'table_body',
	array('TABLE_ROWS' => join('', $propTableRows)),
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
	<? echo BeginNote() . GetMessage($MODULE_ID . '_WARNING_NOTE') . EndNote();?>
	<form action="" method="POST" class="select-form -select-form">
		<table>
			<tr>
				<td>
					<button class="adm-btn -open-full-screen"><?=GetMessage($MODULE_ID . '_FULL_SCREEN_BUTTON')?></button>
				</td>
			</tr>
			<tr>
				<td>
					<?=GetIBlockDropDownListEx($IBLOCK_ID, "types", "iblock", false, "", "");?>
				</td>
			</tr>
			<tr>
				<td>
					<select class="-type-select" name="props_type" data-type="LIST">
						<option value="LIST" data-url="<?=$DIR?>ppt_index.php" selected>
							<?=GetMessage($MODULE_ID . "_PROPS_TYPE_LIST")?>
						</option>
						<option value="NUMBER" data-url="<?=$DIR?>ppt_numbers.php">
							<?=GetMessage($MODULE_ID . "_PROPS_TYPE_NUMBER")?>
						</option>
						<option value="STRING" data-url="<?=$DIR?>ppt_strings.php">
							<?=GetMessage($MODULE_ID . "_PROPS_TYPE_STRING")?>
						</option>
					</select>
				</td>
			</tr>
			<br>
			<tr>
				<td>
					<input class="-select-form-submit adm-btn-save" type="submit" name="save" value="<?=GetMessage($MODULE_ID . '_SUBMIT_BUTTON')?>">
				</td>
			</tr>
		</table>
	</form>

	<?if (!empty($arProps)):?>
		<form action="" method="POST" class="properties-form">
			<div class="table-wrap">
				<?=$propTable?>
			</div>
			<div class="footer-panel -footer-panel">
				<div class="controls-wrap">
					<button class="adm-btn first -delete -properties"><?=GetMessage($MODULE_ID . "_ACTION_DELETE")?></button>
					<?if ($convertPropsCount > 1):?>
						<button class="adm-btn alert -convert-all" data-possible="<?=$posPropCount?>" data-action="CONVERT_TO_NUMBER">
							<?=GetMessage($MODULE_ID . "_ACTION_CONVERT_TO_NUM_ALL")?> (<span class="-count"><?=$convertPropsCount?></span>)
						</button>
					<?endif;?>
					<?if ($emptyPropsValsCount > 0):?>
						<button class="adm-btn alert  -delete-unused-values">
							<?=GetMessage($MODULE_ID . "_ACTION_DELETE_ALL_UNUSED")?> (<?=$emptyPropsValsCount?>)
						</button>
					<?endif;?>
					<?if ($emptyPropsCount > 0):?>
						<button class="adm-btn alert -delete-empty-props">
							<?=GetMessage($MODULE_ID . "_ACTION_DELETE_ALL_WITHOUT_VALUES")?> (<?=$emptyPropsCount?>)
						</button>
					<?endif;?>
					<button class="adm-btn cancel-action -cancel " <?=$CANCEL_POSSIBLE ? 'style="display: inline-block"' : ''?>>
						<?=GetMessage($MODULE_ID . "_ACTION_CANCEL")?>
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

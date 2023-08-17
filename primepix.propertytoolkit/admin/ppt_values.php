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
$arHeaders     = array(
	array('NO_SORT' => 'no-sort'),
	array('NO_SORT' => 'no-sort'),
	array('NAME' => GetMessage($MODULE_ID . '_COLUMN_ID_VALUES_HEADER')),
	array('NAME' => GetMessage($MODULE_ID . '_COLUMN_NAME_VALUES_HEADER'), 'DEFAULT' => 'sort-up'),
	array('NAME' => GetMessage($MODULE_ID . '_COLUMN_VALUE_ID_HEADER')),
	array('NAME' => GetMessage($MODULE_ID . '_COLUMN_VALUE_HEADER')),
	array('NAME' => GetMessage($MODULE_ID . '_COLUMN_ID_PRODUCTS_HEADER')),
);

//get values table rows
$dbProp = $dbOp->getValuesList($PROPERTY_ID);
$hashArr  = array();

while($arProp = $dbProp->Fetch()) {

	$hash = md5( preg_replace('@\s{2,}@i', ' ', $arProp['VALUE']) );
	$hashArr[$hash][] = $arProp['VALUE_ID'];

	//get products ID, connected with value
	$dbProducts = $dbOp->getConnectedElems($PROPERTY_ID, $arProp['VALUE_ID']);

	$productsEditLinks = array();
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
	
	}

	// add table row
	$propTableRows[] = CPptUtils::getView(
		$PATH_TO_VIEWS . 'table_values_row',
		array(
			'arProp'         => $arProp,
			'hash'           => $hash,
			'productEditStr' => join(', ' , $productsEditLinks)
		),
		TRUE
	);

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
	<? echo BeginNote() . GetMessage($MODULE_ID . '_WARNING_NOTE') . EndNote();?>
	<form action="" method="GET" class="select-form -select-form">
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

	<?if (!empty($hashArr)):?>
		<form action="" method="POST" class="properties-form">
			<div class="table-wrap">
				<?=$propTable?>
			</div>
			<div class="footer-panel -footer-panel">
				<div class="controls-wrap">
					<button class="adm-btn -delete -values" data-prop-id="<?=$PROPERTY_ID?>"><?=GetMessage($MODULE_ID . "_ACTION_DELETE")?></button>
					<?if ($dublicateCount > 0):?>
						<button class="-merge-all-values adm-btn" data-prop-id="<?=$PROPERTY_ID?>">
							<?=GetMessage($MODULE_ID . "_ACTION_MERGE_ALL_VALUES")?> (<?=$dublicateCount?>)
						</button>
					<?endif;?>
					<input type="text" class="-prop2move" placeholder="<?=GetMessage($MODULE_ID . "_COLUMN_ID_VALUES_HEADER")?>">
					<button class="-move adm-btn" data-prop-id="<?=$PROPERTY_ID?>">
						<?=GetMessage($MODULE_ID . "_ACTION_MOVE_VALUES")?>
					</button>
					<button class="-cancel cancel-action adm-btn" <?=$CANCEL_POSSIBLE ? 'style="display: inline-block"' : ''?>>
						<?=GetMessage($MODULE_ID . "_ACTION_CANCEL")?>
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

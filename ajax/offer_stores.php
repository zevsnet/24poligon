<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>


	<div class="title">Наличие по складам</div>
	<a class="jqmClose close"></a>
	<span class="add_item_point"></span>
	

	
	<?$APPLICATION->IncludeComponent("bitrix:catalog.store.amount", "shop", array(
						"PER_PAGE" => "10",
						"USE_STORE_PHONE" => $_REQUEST["USE_STORE_PHONE"],
						"SCHEDULE" => $_REQUEST["USE_STORE_SCHEDULE"],
						"USE_MIN_AMOUNT" => $_REQUEST["USE_MIN_AMOUNT"],
						"MIN_AMOUNT" => $_REQUEST["MIN_AMOUNT"],
						"ELEMENT_ID" => intval($_REQUEST["ELEMENT_ID"]),
						"STORE_PATH"  =>  $_REQUEST["STORE_PATH"],
						"MAIN_TITLE"  =>  "",
					), $component
				);?>

<script>
	$('.add_item_point').closest('.popup').jqmAddClose('.jqmClose');
	$('.add_item_point').closest('.popup').jqmAddClose('.proceed');
</script>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>
<?define("STATISTIC_SKIP_ACTIVITY_CHECK", "true");?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?if(\Bitrix\Main\Loader::includeModule('aspro.max')):?>
	<?if($_POST["ELEMENT_ID"]):?>
		<?$elementID = $_POST["ELEMENT_ID"];?>
		<?if($_POST["OFFERS_ID"])
		{
			if($_GET["oid"])
			{
				$elementID = ((in_array($_GET["oid"], $_POST["OFFERS_ID"])) ? (int)$_GET["oid"] : current($_POST["OFFERS_ID"]));
			}
		}?>
		<?$APPLICATION->IncludeComponent("bitrix:catalog.store.amount", "main", array(
				"PER_PAGE" => "10",
				"USE_STORE_PHONE" => $_POST["USE_STORE_PHONE"],
				"SCHEDULE" => $_POST["SCHEDULE"],
				"USE_MIN_AMOUNT" => $_POST["USE_MIN_AMOUNT"],
				"MIN_AMOUNT" => $_POST["MIN_AMOUNT"],
				"ELEMENT_ID" => $elementID,
				"STORE_PATH"  =>  $_POST["STORE_PATH"],
				"MAIN_TITLE"  =>  $_POST["MAIN_TITLE"],
				"MAX_AMOUNT"=>$_POST["MAX_AMOUNT"],
				"USE_ONLY_MAX_AMOUNT" => $_POST["USE_ONLY_MAX_AMOUNT"],
				"SHOW_EMPTY_STORE" => $_POST['SHOW_EMPTY_STORE'],
				"SHOW_GENERAL_STORE_INFORMATION" => $_POST['SHOW_GENERAL_STORE_INFORMATION'],
				"USE_ONLY_MAX_AMOUNT" => $_POST["USE_ONLY_MAX_AMOUNT"],
				"USER_FIELDS" => $_POST['USER_FIELDS'],
				"FIELDS" => $_POST['FIELDS'],
				"STORES" => $_POST['STORES'],
				"KEY_MAP" => \Bitrix\Main\Config\Option::get('fileman', 'yandex_map_api_key'),
				"STORE_AMOUNT_VIEW" => CMax::GetFrontParametrValue("STORE_AMOUNT_VIEW", $_POST["SITE_ID"]),
				"CACHE_GROUPS" => "Y",
				"STORES_FILTER_ORDER" => $_POST['STORES_FILTER_ORDER'],
				"STORES_FILTER" => $_POST['STORES_FILTER'],
				"CACHE_TYPE" => "N",
			),
			false
		);?>
	<?endif;?>
<?endif;?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>
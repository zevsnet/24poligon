<?define("STATISTIC_SKIP_ACTIVITY_CHECK", "true");?>
<?define('STOP_STATISTICS', true);
define('PUBLIC_AJAX_MODE', true);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?if(\Bitrix\Main\Loader::includeModule('aspro.max')):?>
	<?$context = \Bitrix\Main\Context::getCurrent();
	$request = $context->getRequest();?>
	<?$arTheme = CMax::GetBackParametrsValues(SITE_ID);?>
	<?if ($request["ELEMENT_ID"]
		&& $request->isPost()
		&& CMax::checkAjaxRequest()
		&& $arTheme['USE_STORE_QUANTITY'] == 'Y'):?>
	<?
	$arRegion = CMaxRegionality::getCurrentRegion();
	$arRegionStores = $arStoresFileds = array();

	if ($arTheme['LIST_STORES']) {
		$arRegionStores = (array)explode(',', $arTheme['LIST_STORES']);
	}
	if ($arTheme['STORES_FIELDS']) {
		$arStoresFileds = (array)explode(',', $arTheme['STORES_FIELDS']);
	}

	if ($arRegion) {
		if ($arRegion['LIST_STORES']) {
			if (reset($arRegion['LIST_STORES']) != 'component') {
				$arRegionStores = array_values($arRegion['LIST_STORES']);
			} elseif ($request['STORES']) {
				$arRegionStores = $request['STORES'];
			}
		}
	} elseif ($request['STORES']) {
		$arRegionStores = $request['STORES'];
	}
	?>

		<div class="js-info-block rounded3 ">
			<?$APPLICATION->IncludeComponent("bitrix:catalog.store.amount", "popup", array(
					"PER_PAGE" => "100",
					"USE_STORE_PHONE" => "N",
					"SCHEDULE" => "N",
					"USE_MIN_AMOUNT" => "N",
					"MIN_AMOUNT" => "",
					"ELEMENT_ID" => $request["ELEMENT_ID"],
					"CACHE_GROUPS" => "Y",
					//"CACHE_TYPE" => "A", bug fix clear cache
					"CACHE_TYPE" => "N",
					"STORES" => $arRegionStores,
					"STORE_PATH" => "/contacts/stores/#store_id#/",
					"STORES_FILTER_ORDER" => $arTheme['STORES_FILTER_ORDER'],
					"STORES_FILTER" => $arTheme['STORES_FILTER'],
					"SHOW_EMPTY_STORE" => $arTheme['SHOW_EMPTY_STORE'],
					"SHOW_GENERAL_STORE_INFORMATION" => $arTheme['SHOW_GENERAL_STORE_INFORMATION'],
					// "USE_ONLY_MAX_AMOUNT" => $_POST["USE_ONLY_MAX_AMOUNT"],
					"FIELDS" => $arStoresFileds,
					"USER_FIELDS" => [],
				),
				false, array('HIDE_ICONS' => 'Y')
			);?>
		</div>
	<?endif;?>
<?endif;?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>
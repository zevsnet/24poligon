<?define("STATISTIC_SKIP_ACTIVITY_CHECK", "true");?>
<?define('STOP_STATISTICS', true);
define('PUBLIC_AJAX_MODE', true);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
$context = \Bitrix\Main\Application::getInstance()->getContext();
$request = $context->getRequest();

$form_id = htmlspecialcharsbx($request['form_id']) ?? false;
$type = htmlspecialcharsbx($request['type']) ?? false;

if (\Bitrix\Main\Loader::includeModule("aspro.max")) {
	global $arRegion;
	if (!$arRegion) {
		$arRegion = CMaxRegionality::getCurrentRegion();
	}
	CMax::GetValidFormIDForSite($form_id);
}?>
<?if($form_id === 'fast_view'):?>
	<?include('fast_view.php');?>
<?elseif($form_id === 'fast_view_sale'):?>
	<?include('fast_view_sale.php');?>
<?elseif($form_id === 'fast_view_services'):?>
	<?include('fast_view_services.php');?>
<?elseif($form_id === 'city_chooser'):?>
	<?include('city_chooser.php');?>
<?elseif($form_id === 'subscribe'):?>
	<?include('subscribe.php');?>
<?elseif($form_id === 'TABLES_SIZE'):?>
	<?$url_sizes = htmlspecialcharsbx(isset($request['url']) && $request['url'] ? $_SERVER['DOCUMENT_ROOT'] . $request['url'] : '');?>
	<?if(
		$url_sizes &&
		strpos(realpath($url_sizes), $_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include') === 0 &&
		file_exists($url_sizes)
	):?>
		<a href="#" class="close jqmClose"><?=CMax::showIconSvg('', SITE_TEMPLATE_PATH.'/images/svg/Close.svg')?></a>
		<div class="form">
			<div class="form_head">
				<h2><?=\Bitrix\Main\Localization\Loc::getMessage('TABLES_SIZE_TITLE');?></h2>
			</div>
			<div class="form_body">
				<?include($url_sizes);?>
			</div>
		</div>
	<?endif;?>
<?elseif($form_id === 'delivery'):?>
	<?include('delivery.php');?>
<?elseif($form_id === 'share_basket'):?>
	<?include('share_basket.php');?>
<?elseif($type === 'auth'):?>
	<?include_once('auth.php');?>
<?elseif($type === 'subscribe'):?>
	<?include_once('subscribe_news.php');?>
<?elseif($type === 'review'):?>
	<?include('review.php');?>
<?elseif($type === 'marketing'):?>
	<?include('marketing.php');?>
<?elseif($form_id !== 'one_click_buy'):?>
	<?

	if (\Bitrix\Main\Loader::includeModule('form')) {
		$arFilter = array('ACTIVE' => 'Y', 'ID' => $form_id);
		$resForms = CForm::GetList($by='s_sort', $order='ask', $arFilter, $is_filtered);

		$form = $resForms->Fetch();
		$formCode = $form['SID'];
		$formName = $form['NAME'];
	} else {
		$formCode = $form_id;
	}
	
	$formType = CMax::GetFrontParametrValue($formCode . '_FORM');

	if($formType == 'CRM') {
		echo '<div id="bx24_form_inline_second"></div>';
		$bitrix24 = @file_get_contents($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/forms/'.$formCode.'_FORM.php');

		if(strpos($bitrix24, '/bitrix/header.php') !== false){
			$patternHeader = '/\<\?\s*require\(\$_SERVER\[\"DOCUMENT_ROOT\"]\.\"\/bitrix\/header\.php\"\);\s*\$APPLICATION->SetTitle\(\"\"\);\s*\?\>/s';
			$bitrix24 = preg_replace($patternHeader, '', $bitrix24);
			$patternFooter = '/\<\?\s*require\(\$_SERVER\[\"DOCUMENT_ROOT\"\]\.\"\/bitrix\/footer\.php\"\);\?\>/s';
			$bitrix24 = preg_replace($patternFooter, '', $bitrix24);
		}

		$pattern = '/script\s*id\s*=\s*[\'\"](\s*\w*\s*)[\'\"]/s';
		$replacement = 'script id="$1_2"';
		$bitrix24 = preg_replace($pattern, $replacement, $bitrix24);

		$pattern = '/b24form\s*\({\s*\w*".*:\s*(".*\s*"(?!\,)\s*})\);/s';
		preg_match($pattern, $bitrix24, $matches);
		$need = str_replace('}', ', "node": document.getElementById("bx24_form_inline_second")}', $matches[0]);
		$bitrix24 = str_replace($matches[0], $need, $bitrix24);

		if(!$bitrix24):?>
			<div class="form">
				<a href="#" class="close jqmClose" onclick="window.b24form = false;"><?=CMax::showIconSvg('', SITE_TEMPLATE_PATH.'/images/svg/Close.svg')?></a>
				<div class="form_head">
					<h2><?=$formName?></h2>
				</div>
				<div class="form_body">
					File not found or file is empty
				</div>
				<div class="form_footer"></div>
			</div>
		<?else:?>
			<a href="#" class="close jqmClose" onclick="window.b24form = false;"><?=CMax::showIconSvg('', SITE_TEMPLATE_PATH.'/images/svg/Close.svg')?></a>
			<?print_r($bitrix24);
		endif;

	} else {
		$APPLICATION->IncludeComponent(
			"bitrix:form",
			"popup",
			Array(
				"AJAX_MODE" => "Y",
				"SEF_MODE" => "N",
				"WEB_FORM_ID" => $form_id,
				"START_PAGE" => "new",
				"SHOW_LIST_PAGE" => "N",
				"SHOW_EDIT_PAGE" => "N",
				"SHOW_VIEW_PAGE" => "N",
				"SUCCESS_URL" => "",
				"SHOW_ANSWER_VALUE" => "N",
				"SHOW_ADDITIONAL" => "N",
				"SHOW_STATUS" => "N",
				"EDIT_ADDITIONAL" => "N",
				"EDIT_STATUS" => "Y",
				"NOT_SHOW_FILTER" => "",
				"NOT_SHOW_TABLE" => "",
				"CHAIN_ITEM_TEXT" => "",
				"CHAIN_ITEM_LINK" => "",
				"IGNORE_CUSTOM_TEMPLATE" => "N",
				"USE_EXTENDED_ERRORS" => "Y",
				"CACHE_GROUPS" => "N",
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "3600000",
				"AJAX_OPTION_JUMP" => "N",
				"AJAX_OPTION_STYLE" => "Y",
				"AJAX_OPTION_HISTORY" => "N",
				"SHOW_LICENCE" => CMax::GetFrontParametrValue('SHOW_LICENCE'),
				"HIDDEN_CAPTCHA" => CMax::GetFrontParametrValue('HIDDEN_CAPTCHA'),
				"VARIABLE_ALIASES" => Array(
					"action" => "action"
				)
			)
		);
	}?>
<?endif;?>
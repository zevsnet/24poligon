<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS",true);
define("BX_NO_ACCELERATOR_RESET", true);
define("BX_CRONTAB", true);
define("STOP_STATISTICS", true);
define("NO_AGENT_STATISTIC", "Y");
define("DisableEventsCheck", true);
define("NO_AGENT_CHECK", true);

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Dompdf\Dompdf,
	Bitrix\Main\Loader,
	Bitrix\Main\Config\Option,
	Tanais\Order\Specification,
	Bitrix\Main\Application as app;

$request = app::getInstance()->getContext()->getRequest();
$returnDataType = $request->get("type");

if (!$returnDataType) {
	return;
}

Loader::includeModule("sale");
Loader::includeModule("tanais.order");

$allowBasketSpec2Print = Option::get("tanais.order", "ALLOW_BASKET_SPEC_TO_PRINT", "Y");
$allowBasketSpec2DownloadPdf = Option::get("tanais.order", "ALLOW_BASKET_SPEC_TO_DOWNLOAD_PDF", "Y");

$spec = new Specification();
$arResult = $spec->getSpecData();

$getRenderedTemplate = function () use ($arResult) {
	ob_start();
	require "../specification.php";
	return ob_get_clean();
};

$renderedTemplate = $getRenderedTemplate();

if ($returnDataType == "html" && $allowBasketSpec2Print == "Y")
{
	echo "<style type=\"text/css\">@page { size: auto;  margin: 0mm; }</style>";
	echo $renderedTemplate;
}
elseif ($returnDataType == "pdf" && $allowBasketSpec2DownloadPdf == "Y")
{
	$dompdf = new Dompdf();
	$dompdf->loadHtml(
		"<style type=\"text/css\">body { font-family: DejaVu Sans !important; }</style>" .
		$renderedTemplate
	);

	$dompdf->setPaper("A4", "landscape");
	$dompdf->render();
	$dompdf->stream("specification.pdf");
}
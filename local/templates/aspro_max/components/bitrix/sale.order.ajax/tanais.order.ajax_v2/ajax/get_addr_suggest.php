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

use Bitrix\Main\Loader,
	Dadata\DadataClient,
	Bitrix\Main\Config\Option,
	Bitrix\Main\Application as app,
	GuzzleHttp\Exception\ClientException;

$session = app::getInstance()->getSession();
$request = app::getInstance()->getContext()->getRequest();

$addrQuery = $request->get("addr");

Loader::includeModule("tanais.order");

if (Option::get("tanais.order", "USE_ADDR_AUTOCOMPLATE", "Y") !== "Y") {
	return;
}

if (!$request->isAjaxRequest()) {
	return;
}

$daDataToken = Option::get("tanais.order", "DADATA_TOKEN", "");
$daDataSecret = Option::get("tanais.order", "DADATA_SECRET", "");

$resultData4Client = [
	"success" => false,
	"data" => [],
	"errors" => []
];

if (!check_bitrix_sessid()) {
	$resultData4Client["errors"][] = "The session has expired";
	echo json_encode($resultData4Client);
	return;
}

if (empty($daDataToken)) {
	$resultData4Client["errors"][] = "Invalid DaData Token";
	echo json_encode($resultData4Client);
	return;
}

if (empty($addrQuery)) {
	$resultData4Client["errors"][] = "Address is empty";
	echo json_encode($resultData4Client);
	return;
}

if (empty($daDataSecret)) {
	$daDataSecret = null;
}

$dadata = new DadataClient($daDataToken, $daDataSecret);

try {

	$kwargs = [];
	$locSession = $session["tanais.order"]["location"];

	if (null !== $locSession)
	{
		$kwargs = [
			"restrict_value" => true,
			"locations" => [
				[
					"city" => $locSession["name"]
				]
			]
		];
	}

	$result = $dadata->suggest(
		"address",
		$addrQuery,
		5,
		$kwargs
	);

	if (is_array($result) && !empty($result)) {
		$resultData4Client["success"] = true;
		$resultData4Client["data"] = $result;
	}
	else {
		$resultData4Client["errors"][] = "Empty result";
	}
}
catch (ClientException $e) {
	$resultData4Client["errors"][] = $e->getMessage();
}

echo json_encode($resultData4Client);
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
	Bitrix\Main\Config\Option,
	Tanais\Order\OneClickOrder,
	Bitrix\Main\Localization\Loc,
	Bitrix\Main\Application as app,
	Tanais\Order\OneClickOrderException;

Loc::loadMessages(__FILE__);

$request = app::getInstance()->getContext()->getRequest();

$resultData4Client = [
	"success" => false,
	"data" => [],
	"errors" => []
];

if (!$request->isAjaxRequest()) {
	return;
}

Loader::includeModule("sale");
Loader::includeModule("tanais.order");

if (Option::get("tanais.order", "USE_BUY_ONE_CLICK", "Y") !== "Y") {
	return;
}

$fullName = $request->get("full_name");
$email = $request->get("email");
$phone = $request->get("phone");
$comment = $request->get("comment");
$captcha_word = $request->get("captcha_word");
$captcha_code = $request->get("captcha_code");

if (!check_bitrix_sessid()) {
	$resultData4Client["errors"][] = Loc::getMessage("BUY_ONE_CLICK_INACTIVE_SESSION");
	echo json_encode($resultData4Client);
	return;
}

if(
	Option::get("tanais.order", "BUY_ONE_CLICK_USE_CAPTCHA", "N") === "Y" &&
	!$GLOBALS["APPLICATION"]->CaptchaCheckCode($captcha_word, $captcha_code)
) {
	$resultData4Client["errors"][] = Loc::getMessage("BUY_ONE_CLICK_INCORRECT_CAPTCHA");
	echo json_encode($resultData4Client);
	return;
}

if (!$fullName || !$email || !\check_email($email) || !$phone) {
	$resultData4Client["errors"][] = Loc::getMessage("BUY_ONE_CLICK_INVALID_INPUT_PARAMETERS");
	echo json_encode($resultData4Client);
	return;
}

$oneClickOrder = new OneClickOrder(
	$fullName,
	$phone,
	$email,
	$comment
);

try {
	$orderResult = $oneClickOrder->createOrder();
} catch (OneClickOrderException $e) {
	$resultData4Client["errors"][] = $e->getMessage();
	echo json_encode($resultData4Client);
	return;
}

if ($orderResult->isSuccess()) {
	$resultData4Client["success"] = true;
	$resultData4Client["data"]["ORDER_ID"] = $orderResult->getId();
}
else {
	$resultData4Client["errors"] = $orderResult->getErrorMessages();
}

echo json_encode($resultData4Client);
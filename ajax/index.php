<? use SB\Model\Ajax\Response;

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php"); ?>
<?php
define('STATISTIC_SKIP_ACTIVITY_CHECK', true); //не учитывается контроль активности в проактивной защите

try {
    $ajaxResponse = new SB\Model\Ajax\Response();

    $route = new \SB\Router\Ajax();

    $ajaxAnswer = $route->execute();
} catch (Throwable $throwable) {
    $ajaxResponse->addError($throwable->getMessage());
    $ajaxAnswer = $ajaxResponse;
} finally {
    header('Content-Type: application/json; charset=' . LANG_CHARSET);
    echo json_encode($ajaxAnswer, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT );
}
die();
?>
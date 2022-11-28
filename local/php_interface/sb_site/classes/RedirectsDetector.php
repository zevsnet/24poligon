<?php
namespace SB\Site;

use \Bitrix\Main\Loader;

Loader::includeModule("highloadblock");

use \Bitrix\Highloadblock;
use \Bitrix\Main;

class RedirectsDetector {
    private static $instance;
    private static $highloadBlockId = 11;

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private $highloadBlockObject;

    private function __construct() {
        $highloadblockTable = Highloadblock\HighloadBlockTable::getById(self::$highloadBlockId)->fetch();
        $entity = Highloadblock\HighloadBlockTable::compileEntity($highloadblockTable);
        $this->highloadBlockObject = $entity->getDataClass();
    }

    public function detectRedirects() {
        $request = Main\Context::getCurrent()->getRequest();
        $requestPage = $request->getRequestedPage();
        $requestDir  = $request->getRequestedPageDirectory() . '/';

        $explodedRequestPage = explode('/', $requestPage);

        $isIndexPage = $explodedRequestPage[count($explodedRequestPage) - 1] == 'index.php';

        $from = $isIndexPage ? $requestDir : $requestPage;
        // var_dump($from);
        // die();

        $redirectsRequest = $this->highloadBlockObject::getList([
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array("UF_FROM" => $from)
        ]);

        $redirect = $redirectsRequest->Fetch();
        if ($redirect['UF_FROM'] == $from) {
            $this->redirect($redirect['UF_TO']);
        }

        $to = $this->getCommomnRedirect($from);
        if ($to) {
            $this->redirect($to);
        }
    }

    public function addRedirect($from, $to) {
        $result = $this->highloadBlockObject::add([
            'UF_FROM' => $from,
            'UF_TO' => $to
        ]);
    }

    private function getCommomnRedirect($from) {
        $fromExploded = explode('/', $from);
        array_pop($fromExploded);
        $lastUrlElement = array_pop($fromExploded);
        $fromExplodedUrl = implode('/', $fromExploded).'/';
        $commonUrl = $fromExplodedUrl.'*';

        $redirectsRequest = $this->highloadBlockObject::getList([
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array("UF_FROM" => $commonUrl)
        ]);

        $redirect = $redirectsRequest->Fetch();
        if ($redirect) {
            $to = $redirect['UF_TO'];
            $to = str_replace('*', $lastUrlElement, $to).'/';
            return $to;
        }

        if ($fromExplodedUrl != '/') {
            $commonUrl = $this->getCommomnRedirect($fromExplodedUrl);
            if ($commonUrl) {
                $commonUrl .= $lastUrlElement.'/';
            }
            return $commonUrl;
        }

        return false;
    }

    private function redirect($to) {
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: " . $to);
        exit();
    }
}
<?php
namespace SB;
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/sb_tools/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/sb_tools/autoload.php';

$main = new \SB\Main();
$main->init();

<?php

namespace SB\Site;


define('SB\ROOT', $_SERVER['DOCUMENT_ROOT']);
define('SB\PHP_INTERFACE_PATH', \SB\ROOT . '/test/');
include('classes/debugger/debugger.php');

/**
 * Автозагрузчик классов
 */
spl_autoload_register(function ($class) {
    $classDir = '/classes/';
    $fileExtension = '.php';
    if (strpos($class, __NAMESPACE__ . '\\') !== false) {
        $class = str_replace(__NAMESPACE__ . '\\', '', $class);
        $className = str_replace('\\', DIRECTORY_SEPARATOR, $class);
        $filePath = __DIR__ . $classDir . $className . $fileExtension;

        if (file_exists($filePath)) {
            /** @noinspection PhpIncludeInspection */
            require_once $filePath;
        }
    }
});


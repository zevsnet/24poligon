<?php

namespace SB\Site;

spl_autoload_register(function ($class) {
    $classDir = '/classes/';
    $fileExtension = '.php';
    try{
        if (strpos($class, __NAMESPACE__ . '\\') !== false) {
            $class = str_replace(__NAMESPACE__ . '\\', '', $class);
            $className = str_replace('\\', DIRECTORY_SEPARATOR, $class);
            $filePath = __DIR__ . $classDir . $className . $fileExtension;

            if (file_exists($filePath)) {
                /** @noinspection PhpIncludeInspection */
                
                    require_once $filePath;
                
            }
        }
    }
    catch (Exception $e) {
               echo "<div style='display:none' id='errorSpl'>".$e->getMessage()."</div>";
    }

});

//\SB\Site\EventHandlers::addEventHandlers();
<?php

use Bitrix\Main\Loader;


class poligon_core extends CModule
{
    public $MODULE_ID = 'poligon.core';
    public $PARTNER_URI = '';
    public $PARTNER_NAME = '24Poligon';

    public function __construct()
    {
        $arModuleVersion = [];
        $versionPath = dirname(__FILE__);
        include($versionPath . '/version.php');
        if (!empty($arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }
        $this->MODULE_NAME = 'Модуль вспомогательный';
    }

    function DoInstall()
    {
        $this->InstallEvents();
        RegisterModule($this->MODULE_ID);

        Loader::includeModule($this->MODULE_ID);

    }

    function DoUninstall()
    {
        UnRegisterModule($this->MODULE_ID);
    }

    function InstallEvents()
    {
        return true;
    }

}
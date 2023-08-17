<?
IncludeModuleLangFile(__FILE__);

Class idex_seo extends CModule
{
    var $MODULE_ID = "idex.seo";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;
    var $MODULE_DIR;
    var $MODULE_GROUP_RIGHTS = "Y";

    function idex_seo()
    {
        $arModuleVersion = array();
        include( $_SERVER['DOCUMENT_ROOT']."/local/modules/idex.seo/install/version.php");
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = GetMessage("IDEX_SEO_INSTALL_NAME");
        $this->MODULE_DESCRIPTION = GetMessage("IDEX_SEO_INSTALL_DESCRIPTION");
        $this->PARTNER_NAME = "idex group Уже Изменен";
        $this->PARTNER_URI = '';

        $this->MODULE_DIR = $_SERVER['DOCUMENT_ROOT']."/local/modules/idex.seo/";

    }


    function InstallDB($arParams = array())
    {
        global $DB, $APPLICATION;
        $this->errors = false;
        $DB->StartTransaction();
        // Database tables creation
        if (!$DB->Query("SELECT 'x' FROM bm_idex_seo_pages WHERE 1=0", true)) {
            $this->errors = $DB->RunSQLBatch($this->MODULE_DIR . "/install/db/" . strtolower($DB->type) . "/install.sql");
        }

        if ($this->errors !== false) {
            $DB->RollBack();
            $APPLICATION->ThrowException(implode("<br>", $this->errors));
            return false;
        } else {
            $DB->Commit();
            RegisterModule($this->MODULE_ID);
            CModule::IncludeModule($this->MODULE_ID);
            RegisterModuleDependences("main", "OnBeforeProlog", $this->MODULE_ID, "CIdexSeo", "ShowPanel");
            RegisterModuleDependences("main", "OnEndBufferContent", $this->MODULE_ID, "CIdexSeo", "ReplaceContent");
            RegisterModuleDependences("main", "OnEpilog", $this->MODULE_ID, "CIdexSeo", "SetSeoParams");
            //BitrixModuleInstaller::Add($this->MODULE_ID);
            return true;
        }
    }

    function UnInstallDB($arParams = Array())
    {
        global $DB;
        $this->errors = false;

        if (!array_key_exists("save_tables", $arParams) || $arParams["save_tables"] != "Y") {
            $this->errors = $DB->RunSQLBatch($this->MODULE_DIR . "/install/db/" . strtolower($DB->type) . "/uninstall.sql");
        }

        UnRegisterModuleDependences("main", "OnBeforeProlog", $this->MODULE_ID, "CIdexSeo", "ShowPanel");
        UnRegisterModuleDependences("main", "OnEndBufferContent", $this->MODULE_ID, "CIdexSeo", "ReplaceContent");
        UnRegisterModuleDependences("main", "OnEpilog", $this->MODULE_ID, "CIdexSeo", "SetSeoParams");
        UnRegisterModule($this->MODULE_ID);

        return true;
    }

    function InstallFiles()
    {
        CopyDirFiles(
            $this->MODULE_DIR . "/install/admin/",
            $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin/"
        );
        CopyDirFiles(
            $this->MODULE_DIR . "/install/themes/",
            $_SERVER["DOCUMENT_ROOT"] . "/bitrix/themes/",
            true,
            true
        );
        CopyDirFiles(
            $this->MODULE_DIR . "/install/js/",
            $_SERVER["DOCUMENT_ROOT"] . "/bitrix/js/",
            true,
            true
        );
        return true;
    }

    function UnInstallFiles()
    {
        DeleteDirFiles(
            $this->MODULE_DIR . "/install/admin/",
            $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin/"
        );
        DeleteDirFiles(
            $this->MODULE_DIR . "/install/themes/.default/",
            $_SERVER["DOCUMENT_ROOT"] . "/bitrix/themes/.default/"
        );
        DeleteDirFilesEx("/bitrix/themes/.default/icons/" . $this->MODULE_ID);
        DeleteDirFilesEx("/bitrix/js/" . $this->MODULE_ID);
        return true;
    }

    function DoInstall()
    {
        global $APPLICATION, $step;

        $step = IntVal($step);
        if ($step < 2) {
            $APPLICATION->IncludeAdminFile(GetMessage("IDEX_SEO_INSTALL_TITLE"), $this->MODULE_DIR . "/install/step1.php");
        } elseif ($step == 2) {
            if ($this->InstallDB()) {
                $this->InstallFiles();
            } else {
                die('wtf');
            }
            $GLOBALS["errors"] = $this->errors;
            $APPLICATION->IncludeAdminFile(GetMessage("IDEX_SEO_INSTALL_TITLE"), $this->MODULE_DIR . "/install/step2.php");
        }

    }

    function DoUninstall()
    {
        global $APPLICATION, $step;

        $step = IntVal($step);
        if ($step < 2) {
            $APPLICATION->IncludeAdminFile(GetMessage("IDEX_SEO_UNINSTALL_TITLE"), $this->MODULE_DIR . "/install/unstep1.php");
        } elseif ($step == 2) {
            $this->UnInstallDB(array(
                "save_tables" => $_REQUEST["save_tables"],
            ));
            $this->UnInstallFiles();
            $GLOBALS["errors"] = $this->errors;
            $APPLICATION->IncludeAdminFile(GetMessage("IDEX_SEO_UNINSTALL_TITLE"), $this->MODULE_DIR . "/install/unstep2.php");
        }

    }

    function DeleteDirFiles($frDir, $toDir, $arExept = array())
    {
        if (is_dir($_SERVER["DOCUMENT_ROOT"] . $frDir)) {
            $d = dir($_SERVER["DOCUMENT_ROOT"] . $frDir);
            while ($entry = $d->read()) {
                if ($entry == "." || $entry == "..") {
                    continue;
                }
                if (in_array($entry, $arExept)) {
                    continue;
                }
                DeleteDirFilesEx($toDir . "/" . $entry);
            }
            $d->close();
        }
    }

}
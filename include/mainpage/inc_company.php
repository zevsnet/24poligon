<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>

<div class="wrap_md">
    <div>
        <? $APPLICATION->IncludeComponent("bitrix:main.include", "front", Array(
            "AREA_FILE_SHOW" => "file",
            "PATH" => SITE_DIR . "include/mainpage/company/front_info.php",
            "EDIT_TEMPLATE" => ""
        )); ?>
    </div>
</div>
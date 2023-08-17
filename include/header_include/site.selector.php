<?$siteSelectorName = CMax::GetFrontParametrValue('SITE_SELECTOR_NAME');?>
<?$APPLICATION->IncludeComponent(
    "bitrix:main.site.selector",
    $arParams['TEMPLATE_SITE_SELECTOR'] === "mobile" ? "mobile" : "main",
    array(
        "SITE_LIST" => $arParams['SITE_LIST'], 
        "CACHE_TYPE" => "A", 
        "CACHE_TIME" => "3600",
        'SITE_SELECTOR_NAME' => $siteSelectorName,
    ),
    false,
    array("HIDE_ICONS" => "Y")
);?>
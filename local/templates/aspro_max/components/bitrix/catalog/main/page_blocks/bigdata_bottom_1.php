<?
$arParams['BIG_DATA_MODE'] = "Y";
$arParams["RCM_PROD_ID"] = $arResult['ID'];
$arParams["PRODUCT_ROW_VARIANTS"] = "[{'VARIANT':'6','BIG_DATA':true}]";
$arParams["DISPLAY_ELEMENT_SLIDER"] =	0;
$arParams["RCM_TYPE"] = (isset($arParams['BIG_DATA_RCM_TYPE']) ? $arParams['BIG_DATA_RCM_TYPE'] : '');
$arParams["SHOW_FROM_SECTION"] = $arParams['BIGDATA_SHOW_FROM_SECTION'];
$arParams['FILTER_HIT_PROP'] = 'bigdata';

if ($arParams["BIGDATA_SET_COUNT_BOTTOM"] !== "N"){
     $arParams["BIGDATA_COUNT_BOTTOM"] = ($arParams['BIGDATA_COUNT_BOTTOM'] ? $arParams['BIGDATA_COUNT_BOTTOM'] : 10);
} else {
    $arParams["BIGDATA_COUNT_BOTTOM"] = ($arParams['BIGDATA_COUNT'] ? $arParams['BIGDATA_COUNT'] : 10);
}
?>
<?include($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/detail.linked_products_block.php');?>
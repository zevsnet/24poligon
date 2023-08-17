<?if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true ) die();?>
<?$this->setFrameMode(true);?>

<?global $isHideLeftBlock, $arTheme;?>
	
<?
if(isset($arParams["TYPE_LEFT_BLOCK"]) && $arParams["TYPE_LEFT_BLOCK"]!='FROM_MODULE'){
	$arTheme['LEFT_BLOCK']['VALUE'] = $arParams["TYPE_LEFT_BLOCK"];
}

if(isset($arParams["SIDE_LEFT_BLOCK"]) && $arParams["SIDE_LEFT_BLOCK"]!='FROM_MODULE'){
	$arTheme['SIDE_MENU']['VALUE'] = $arParams["SIDE_LEFT_BLOCK"];
}
?>
<?
if(!$isHideLeftBlock && $APPLICATION->GetProperty("HIDE_LEFT_BLOCK_LIST") == "Y"){
	$APPLICATION->SetPageProperty("HIDE_LEFT_BLOCK", "Y");
}
?>
<?// intro text?>
<div class="text_before_items"><?$APPLICATION->IncludeComponent(
		"bitrix:main.include",
		"",
		Array(
			"AREA_FILE_SHOW" => "page",
			"AREA_FILE_SUFFIX" => "inc",
			"EDIT_TEMPLATE" => ""
		)
	);?></div>
<?if((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || (strtolower($_REQUEST['ajax']) == 'y'))
{
	$APPLICATION->RestartBuffer();
}?>
<?
// get section items count and subsections
$arItemFilter = CMax::GetCurrentSectionElementFilter($arResult["VARIABLES"], $arParams, false);
$arSubSectionFilter = CMax::GetCurrentSectionSubSectionFilter($arResult["VARIABLES"], $arParams, false);
$itemsCnt = CMaxCache::CIBlockElement_GetList(array("CACHE" => array("TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]), "CACHE_GROUP" => array($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()))), $arItemFilter, array());
$arSubSections = CMaxCache::CIBlockSection_GetList(array("CACHE" => array("TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]), "MULTI" => "Y", "CACHE_GROUP" => array($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()))), $arSubSectionFilter, false, array("ID"));


?>

<?$this->SetViewTarget('product_share');?>
	<?if($arParams['USE_RSS'] !== 'N'):?>
		<div class="colored_theme_hover_bg-block">
			<?=CMax::ShowRSSIcon($arResult['FOLDER'].$arResult['URL_TEMPLATES']['rss']);?>
		</div>
	<?endif;?>
<?$this->EndViewTarget();?>

<?/* start tags */?>
<?
if(isset($arItemFilter['CODE']))
{
	unset($arItemFilter['CODE']);
	unset($arItemFilter['SECTION_CODE']);
}
if(isset($arItemFilter['ID']))
{
	unset($arItemFilter['ID']);
	unset($arItemFilter['SECTION_ID']);
}
?>
<?
$arTags = array();

$arElements = CMaxCache::CIblockElement_GetList(array('CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'MULTI' => 'Y')), $arItemFilter, false, false, array('ID', 'TAGS'));

foreach($arElements as $arElement)
{
	if($arElement['TAGS'])
	{
		$arTags[] = explode(',', $arElement['TAGS']);
	}
}
?>
<?$this->__component->__template->SetViewTarget('under_sidebar_content');?>
<div>
	<?$APPLICATION->IncludeComponent(
		"bitrix:search.tags.cloud",
		"main",
		Array(
			"CACHE_TIME" => "86400",
			"CACHE_TYPE" => "A",
			"CHECK_DATES" => "Y",
			"COLOR_NEW" => "3E74E6",
			"COLOR_OLD" => "C0C0C0",
			"COLOR_TYPE" => "N",
			"TAGS_ELEMENT" => $arTags,
			"FILTER_NAME" => $arParams["FILTER_NAME"],
			"FONT_MAX" => "50",
			"FONT_MIN" => "10",
			"PAGE_ELEMENTS" => "150",
			"PERIOD" => "",
			"PERIOD_NEW_TAGS" => "",
			"SHOW_CHAIN" => "N",
			"SORT" => "NAME",
			"TAGS_INHERIT" => "Y",
			"URL_SEARCH" => SITE_DIR."search/index.php",
			"WIDTH" => "100%",
			"arrFILTER" => array("iblock_aspro_max_content"),
			"arrFILTER_iblock_aspro_max_content" => array($arParams["IBLOCK_ID"])
		), $component, array('HIDE_ICONS' => 'Y')
	);?>
</div>
<?$this->__component->__template->EndViewTarget();?>
<?/* end tags */?>

<?if(!$itemsCnt && !$arSubSections):?>
	<div class="alert alert-warning"><?=GetMessage("SECTION_EMPTY")?></div>
<?else:?>
	<?// sections?>
	<?@include_once('page_blocks/'.$arParams["SECTIONS_TYPE_VIEW"].'.php');?>

	<?// section elements?>
	<?if(strlen($arParams["FILTER_NAME"])):?>
		<?$arTmpFilter = $GLOBALS[$arParams["FILTER_NAME"]];?>
		<?$GLOBALS[$arParams["FILTER_NAME"]] = array_merge((array)$GLOBALS[$arParams["FILTER_NAME"]], $arItemFilter);?>
	<?else:?>
		<?$arParams["FILTER_NAME"] = "arrFilterServ";?>
		<?$GLOBALS[$arParams["FILTER_NAME"]] = $arItemFilter;?>
	<?endif;?>

	<?@include_once('page_blocks/'.$arParams["SECTION_ELEMENTS_TYPE_VIEW"].'.php');?>

	<?if(strlen($arParams["FILTER_NAME"])):?>
		<?$GLOBALS[$arParams["FILTER_NAME"]] = $arTmpFilter;?>
	<?endif;?>
	
<?endif;?>
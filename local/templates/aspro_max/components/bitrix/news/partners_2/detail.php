<?if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true ) die();?>
<?$this->setFrameMode(true);?>
<?
// get element
$arItemFilter = CMax::GetCurrentElementFilter($arResult["VARIABLES"], $arParams);
$arElement = CMaxCache::CIblockElement_GetList(array("CACHE" => array("TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]), "MULTI" => "N")), $arItemFilter, false, false, array("ID", 'PREVIEW_TEXT', "IBLOCK_SECTION_ID", 'PREVIEW_PICTURE', 'DETAIL_PICTURE'));
?>

<?global $arTheme, $isHideLeftBlock, $isWidePage;?>
<?
if(isset($arParams["TYPE_LEFT_BLOCK_DETAIL"]) && $arParams["TYPE_LEFT_BLOCK_DETAIL"]!='FROM_MODULE'){
	$arTheme['LEFT_BLOCK']['VALUE'] = $arParams["TYPE_LEFT_BLOCK_DETAIL"];
}

if(isset($arParams["SIDE_LEFT_BLOCK_DETAIL"]) && $arParams["SIDE_LEFT_BLOCK_DETAIL"]!='FROM_MODULE'){
	$arTheme['SIDE_MENU']['VALUE'] = $arParams["SIDE_LEFT_BLOCK_DETAIL"];
}

if($arTheme['HIDE_SUBSCRIBE']['VALUE'] == 'Y'){
	$arParams["USE_SUBSCRIBE_IN_TOP"] = "N";
}
?>

<?
if(!$isHideLeftBlock && $APPLICATION->GetProperty("HIDE_LEFT_BLOCK_DETAIL") == "Y"){
	$APPLICATION->SetPageProperty("HIDE_LEFT_BLOCK", "Y");
	$APPLICATION->AddViewContent('container_inner_class', ' contents_page ');
	$APPLICATION->AddViewContent('wrapper_inner_class', ' wide_page ');
	if(!$isWidePage){
		$APPLICATION->AddViewContent('right_block_class', ' maxwidth-theme ');		
	}
}
?>

<?if(!$arElement && $arParams['SET_STATUS_404'] !== 'Y'):?>
	<div class="alert alert-warning"><?=GetMessage("ELEMENT_NOTFOUND")?></div>
<?elseif(!$arElement && $arParams['SET_STATUS_404'] === 'Y'):?>
	<?CMax::goto404Page();?>
<?else:?>
	
	<?CMax::AddMeta(
		array(
			'og:description' => $arElement['PREVIEW_TEXT'],
			'og:image' => (($arElement['PREVIEW_PICTURE'] || $arElement['DETAIL_PICTURE']) ? CFile::GetPath(($arElement['PREVIEW_PICTURE'] ? $arElement['PREVIEW_PICTURE'] : $arElement['DETAIL_PICTURE'])) : false),
		)
	);?>

	<?if($arElement):?>
		<?$this->SetViewTarget('product_share');?>
			<?if($arParams["USE_SHARE"] == "Y"):?>
				<?\Aspro\Functions\CAsproMax::showShareBlock('top')?>
			<?endif;?>
			<?if($arParams['USE_RSS'] !== 'N'):?>
				<div class="colored_theme_hover_bg-block">
					<?=CMax::ShowRSSIcon($arResult['FOLDER'].$arResult['URL_TEMPLATES']['rss']);?>
				</div>
			<?endif;?>
			<?if($arParams["USE_SUBSCRIBE_IN_TOP"] == "Y"):?>
				<div><div class="colored_theme_hover_bg-block dark_link animate-load" data-event="jqm" data-param-type="subscribe" data-name="subscribe" title="<?=GetMessage('SUBSCRIBE_TEXT')?>">
					<?=CMax::showIconSvg("subscribe", SITE_TEMPLATE_PATH."/images/svg/subscribe_insidepages.svg", "", "colored_theme_hover_bg-el-svg", true, false);?>
				</div></div>
			<?endif;?>
		<?$this->EndViewTarget();?>
	<?endif;?>
	
	<?//element?>
	<?$sViewElementTemplate = ($arParams["ELEMENT_TYPE_VIEW"] == "FROM_MODULE" ? $arTheme["PARTNERS_2_PAGE_DETAIL"]["VALUE"] : $arParams["ELEMENT_TYPE_VIEW"]);?>
	<?@include_once('page_blocks/'.$sViewElementTemplate.'.php');?>
	<?/*
	if(is_array($arElement["IBLOCK_SECTION_ID"]) && count($arElement["IBLOCK_SECTION_ID"]) > 1){
		CMax::CheckAdditionalChainInMultiLevel($arResult, $arParams, $arElement);
	}*/
	?>
<?endif;?>
<div style="clear:both"></div>
<div class="bottom-links-block">
	<a class="muted back-url url-block" href="<?=$arResult['FOLDER'].$arResult['URL_TEMPLATES']['news']?>">
		<?=CMax::showIconSvg("return_to_the_list", SITE_TEMPLATE_PATH."/images/svg/return_to_the_list.svg", "");?>
	<span class="font_upper back-url-text"><?=($arParams["T_PREV_LINK"] ? $arParams["T_PREV_LINK"] : GetMessage('BACK_LINK'));?></span></a>

	<?if($arParams["SHOW_MAX_ELEMENT"] == "Y" && $arElementNext):?>
		<a class="muted next-url url-block" href="<?=$arElementNext['DETAIL_PAGE_URL']?>">
		<span class="font_upper next-url-text"><?=($arParams["T_MAX_LINK"] ? $arParams["T_MAX_LINK"] : GetMessage('MAX_LINK'));?></span>
		<?=CMax::showIconSvg("next_element", SITE_TEMPLATE_PATH."/images/svg/return_to_the_list.svg", "");?>    
		</a>
	<?endif;?>

	<?if($arParams["USE_SHARE"] == "Y" && $arElement):?>
		<?\Aspro\Functions\CAsproMax::showShareBlock('bottom')?>
	<?endif;?>
</div>
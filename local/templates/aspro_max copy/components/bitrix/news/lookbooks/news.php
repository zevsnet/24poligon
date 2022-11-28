<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
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
<?
$arItemFilter = CMax::GetIBlockAllElementsFilter($arParams);
$arItems = CMaxCache::CIblockElement_GetList(array("CACHE" => array("TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), $arItemFilter, false, false, array('ID', 'IBLOCK_SECTION_ID'));
$itemsCnt = count($arItems);
?>

<?$this->SetViewTarget('product_share');?>
	<?if($arParams['USE_RSS'] !== 'N'):?>
		<div class="colored_theme_hover_bg-block">
			<?=CMax::ShowRSSIcon($arResult['FOLDER'].$arResult['URL_TEMPLATES']['rss']);?>
		</div>
	<?endif;?>
<?$this->EndViewTarget();?>


<?
// add tabs
if($arItems) {
	$arSections = array();
	foreach($arItems as $arItem) {
		if( !isset($arSections[ $arItem['IBLOCK_SECTION_ID'] ]) ) {
			$res = CIBlockSection::GetNavChain($arParams['IBLOCK_ID'], $arItem['IBLOCK_SECTION_ID'], array('ID', 'NAME'));
			if($section = $res->Fetch()) {
				$arSections[ $section['ID'] ] = $section;
			}
		}
	}

	$bHasSection = (isset($_GET['section_id']) && (int)$_GET['section_id']);
	$sectionGet = ($bHasSection ? (int)$_GET['section_id'] : 0);
	if($arSections):?>
		<div class="select_head_wrap">
			<div class="menu_item_selected font_upper_md rounded3 bordered visible-xs font_xs darken"><span></span>
				<?=CMax::showIconSvg("down", SITE_TEMPLATE_PATH.'/images/svg/trianglearrow_down.svg', '', '', true, false);?>
			</div>
			<div class="head-block top bordered-block rounded3 clearfix srollbar-custom">
				<div class="item-link font_upper_md  <?=($bHasSection ? '' : 'active');?>">
					<div class="title">
						<?if($bHasSection):?>
							<a class="btn-inline dark_link" href="<?=$arResult['FOLDER'];?>"><?=GetMessage('ALL_SECTIONS');?></a>
						<?else:?>
							<span class="btn-inline darken"><?=GetMessage('ALL_SECTIONS');?></span>
						<?endif;?>
					</div>
				</div>
				<?foreach($arSections as $section):
					$bSelected = ($bHasSection && $section['ID'] == $sectionGet);?>
					<div class="item-link font_upper_md <?=($bSelected ? 'active' : '');?>">
						<div class="title btn-inline darken">
							<?if($bSelected):?>
								<span class="btn-inline darken"><?=$section['NAME'];?></span>
							<?else:?>
								<a class="btn-inline dark_link" href="<?=$APPLICATION->GetCurPageParam('section_id='.$section['ID'], array('section_id'));?>"><?=$section['NAME'];?></a>
							<?endif;?>
						</div>
					</div>
				<?endforeach;?>
			</div>
		</div>
	<?endif;
	if($bHasSection)
	{
		$arResult["VARIABLES"]["SECTION_ID"] = $sectionGet;
	}
}
?>

<?if(!$itemsCnt):?>
	<div class="alert alert-warning"><?=GetMessage("SECTION_EMPTY")?></div>
<?else:?>
	<?if((isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") || (strtolower($_REQUEST['ajax']) == 'y'))
	{
		$APPLICATION->RestartBuffer();
	}?>

	<?//global $arTheme;?>
	<?// section elements?>
	<?$sViewElementsTemplate = ($arParams["SECTION_ELEMENTS_TYPE_VIEW"] == "FROM_MODULE" ? $arTheme["LOOKBOOKS_PAGE"]["VALUE"] : $arParams["SECTION_ELEMENTS_TYPE_VIEW"]);?>
	<?@include_once('page_blocks/'.$sViewElementsTemplate.'.php');?>

	<?if((isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") || (strtolower($_REQUEST['ajax']) == 'y'))
	{
		die();
	}?>
<?endif;?>
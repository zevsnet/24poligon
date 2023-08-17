<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?// intro text?>

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

if($arParams['CACHE_GROUPS'] == 'Y')
{
	$arItemFilter['CHECK_PERMISSIONS'] = 'Y';
	$arItemFilter['GROUPS'] = $GLOBALS["USER"]->GetGroups();
}

$itemsCnt = CMaxCache::CIblockElement_GetList(array("CACHE" => array("TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), $arItemFilter, array());?>

<?if(!$itemsCnt):?>
	<div class="alert alert-warning"><?=GetMessage("SECTION_EMPTY")?></div>
<?else:?>
	
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
	<?
	global $NavNum; 
	$context = \Bitrix\Main\Application::getInstance()->getContext();
	if($NavNum){
		$pagen = $NavNum;
	}else{
		$pagen = 2;
	}
	$numPage = $context->getRequest()->get("PAGEN_".$pagen) ?? 1;
	$arGroup =  array("iNumPage" => $numPage, "nPageSize" => $arParams['NEWS_COUNT']);
	$arSelect = array('ID', 'NAME','PREVIEW_TEXT', 'DETAIL_PAGE_URL', 'PREVIEW_PICTURE', 'DETAIL_PICTURE', 'DATE_CREATE');
	$arElement = CMaxCache::CIblockElement_GetList(array("CACHE" => array("TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]), "MULTI" => "Y"), $arParams['SORT_BY1'] => $arParams['SORT_ORDER1'], $arParams['SORT_BY2'] => $arParams['SORT_ORDER2']), $arItemFilter, false, $arGroup, $arSelect);
	if($pagen != $NavNum){
		$NavNum = $pagen - 1;
	}

	$arSite = \CSite::GetByID(SITE_ID)->Fetch();

	$CURRENT_PAGE = (CMain::IsHTTPS()) ? "https://" : "http://";
	$CURRENT_PAGE .= $_SERVER["HTTP_HOST"];
	$SITE_DOMAIN = $CURRENT_PAGE;
	$CURRENT_PAGE .= $APPLICATION->GetCurUri();

	foreach($arElement as $key => $element):
		$arSchema[] = array(
			"@context" => "https://schema.org",
			"@type" => "NewsArticle",
			"url" => $CURRENT_PAGE,
			"publisher" => array(
				"@type" => "Organization",
      			"name" => $arSite['NAME']
			),
			"headline" => $element['NAME'],
			"articleBody" => $element['PREVIEW_TEXT'],
			"datePublished" => $element['DATE_CREATE']
		);
		if($element['PREVIEW_PICTURE']){
			$arSchema[$key]['image'][] = $SITE_DOMAIN.CFile::GetPath($element['PREVIEW_PICTURE']);
		}
		if($element['DETAIL_PICTURE']){
			$arSchema[$key]['image'][] = $SITE_DOMAIN.CFile::GetPath($element['DETAIL_PICTURE']);
		}
	endforeach;
	?>
	<script type="application/ld+json"><?=str_replace("'", "\"", CUtil::PhpToJSObject($arSchema, false, true));?></script>
	<?$this->__component->__template->SetViewTarget('under_sidebar_content');?>
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
	<?$this->__component->__template->EndViewTarget();?>
	<?/* end tags */?>
	
	
	
	<?$arItems = CMaxCache::CIBLockElement_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']))), $arItemFilter, false, false, array('ID', 'NAME', 'ACTIVE_FROM'));
	$arYears = array();
	if($arItems)
	{
		foreach($arItems as $arItem)
		{
			if($arItem['ACTIVE_FROM'])
			{
				if($arDateTime = ParseDateTime($arItem['ACTIVE_FROM'], FORMAT_DATETIME))
					$arYears[$arDateTime['YYYY']] = $arDateTime['YYYY'];
			}
		}
		if($arYears)
		{
			if($arParams['USE_FILTER'] != 'N')
			{
				rsort($arYears);
				$bHasYear = (isset($_GET['year']) && (int)$_GET['year']);
				$year = ($bHasYear ? (int)$_GET['year'] : 0);?>
				<div class="select_head_wrap">
					<div class="menu_item_selected font_upper_md rounded3 bordered visible-xs font_xs darken"><span></span>
						<?=CMax::showIconSvg("down", SITE_TEMPLATE_PATH.'/images/svg/trianglearrow_down.svg', '', '', true, false);?>
					</div>
					<div class="head-block top bordered-block rounded3 clearfix srollbar-custom">
						<div class="item-link font_upper_md  <?=($bHasYear ? '' : 'active');?>">
							<div class="title">
								<?if($bHasYear):?>
									<a class="btn-inline dark_link" href="<?=$arResult['FOLDER'];?>"><?=GetMessage('ALL_TIME');?></a>
								<?else:?>
									<span class="btn-inline darken"><?=GetMessage('ALL_TIME');?></span>
								<?endif;?>
							</div>
						</div>
						<?foreach($arYears as $value):
							$bSelected = ($bHasYear && $value == $year);?>
							<div class="item-link font_upper_md <?=($bSelected ? 'active' : '');?>">
								<div class="title btn-inline darken">
									<?if($bSelected):?>
										<span class="btn-inline darken"><?=$value;?></span>
									<?else:?>
										<a class="btn-inline dark_link" href="<?=$APPLICATION->GetCurPageParam('year='.$value, array('year'));?>"><?=$value;?></a>
									<?endif;?>
								</div>
							</div>
						<?endforeach;?>
					</div>
				</div>
				<?
				if($bHasYear)
				{
					$GLOBALS[$arParams["FILTER_NAME"]][] = array(
						">=DATE_ACTIVE_FROM" => ConvertDateTime("01.01.".$year, FORMAT_DATE,''),
                        "<DATE_ACTIVE_FROM" => ConvertDateTime("01.01.".($year+1), FORMAT_DATE,''),
					);
				}?>
			<?}
		}
	}?>
	

	
		<div class="sub_container fixed_wrapper">
		<div class="row">
			<div class="col-md-12">
			    
	<?if((isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") || (strtolower($_REQUEST['ajax']) == 'y'))
	{
		$APPLICATION->RestartBuffer();
	}?>
	<?// section elements?>
	<?$sViewElementsTemplate = ($arParams["SECTION_ELEMENTS_TYPE_VIEW"] == "FROM_MODULE" ? $arTheme["NEWS_PAGE"]["VALUE"] : $arParams["SECTION_ELEMENTS_TYPE_VIEW"]);?>
	<?@include_once('page_blocks/'.$sViewElementsTemplate.'.php');?>
	<?if((isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") || (strtolower($_REQUEST['ajax']) == 'y'))
	{
		die();
	}?>

	
			</div>
			
		</div>
		</div>
	
<?endif;?>
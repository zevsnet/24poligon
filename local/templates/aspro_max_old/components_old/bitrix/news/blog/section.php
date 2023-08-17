<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>

<?global $isHideLeftBlock, $arTheme;?>
<?
if(isset($arParams["TYPE_LEFT_BLOCK"]) && $arParams["TYPE_LEFT_BLOCK"]!='FROM_MODULE'){
	$arTheme['LEFT_BLOCK']['VALUE'] = $arParams["TYPE_LEFT_BLOCK"];
}

if(isset($arParams["SIDE_LEFT_BLOCK"]) && $arParams["SIDE_LEFT_BLOCK"]!='FROM_MODULE'){
	$arTheme['SIDE_MENU']['VALUE'] = $arParams["SIDE_LEFT_BLOCK"];
}?>

<?
if(!$isHideLeftBlock && $APPLICATION->GetProperty("HIDE_LEFT_BLOCK_LIST") == "Y"){
	$APPLICATION->SetPageProperty("HIDE_LEFT_BLOCK", "Y");
}
?>

<?$bIsHideLeftBlock = ($APPLICATION->GetProperty("HIDE_LEFT_BLOCK") == "Y");?>

<?
// geting section items count and section [ID, NAME]
$arItemFilter = CMax::GetCurrentSectionElementFilter($arResult["VARIABLES"], $arParams);//var_dump($arResult["VARIABLES"]);
$arSectionFilter = CMax::GetCurrentSectionFilter($arResult["VARIABLES"], $arParams);

if($arParams['CACHE_GROUPS'] == 'Y')
{
	$arSectionFilter['CHECK_PERMISSIONS'] = 'Y';
	$arSectionFilter['GROUPS'] = $GLOBALS["USER"]->GetGroups();
}

$arSection = CMaxCache::CIblockSection_GetList(array("CACHE" => array("TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]), "MULTI" => "N")), $arSectionFilter, false, array('ID', 'DESCRIPTION', 'PICTURE', 'DETAIL_PICTURE'), true);
CMax::AddMeta(
	array(
		'og:description' => $arSection['DESCRIPTION'],
		'og:image' => (($arSection['PICTURE'] || $arSection['DETAIL_PICTURE']) ? CFile::GetPath(($arSection['PICTURE'] ? $arSection['PICTURE'] : $arSection['DETAIL_PICTURE'])) : false),
	)
);
?>


<?if(!$arSection && $arParams['SET_STATUS_404'] !== 'Y'):?>
	<?// get element start
	$arItemElementFilter = CMax::GetCurrentElementFilter($arResult['VARIABLES'], $arParams);
	if ($arItemElementFilter) {
		foreach ($arItemElementFilter as $key => $value) {
			if ($key == 'SECTION_CODE') {
				$arItemElementFilter['CODE'] = $value;
				$arResult['VARIABLES']['ELEMENT_CODE'] = $value;
				unset($arItemElementFilter[$key]);
				unset($arResult['VARIABLES']['SECTION_CODE']);
			}
			if ($key == 'SECTION_ID') {
				$arItemElementFilter['ID'] = $value;
				$arResult['VARIABLES']['ELEMENT_ID'] = $value;
				unset($arItemElementFilter[$key]);
				unset($arResult['VARIABLES']['SECTION_ID']);
			}
		}
		$arItemElementFilter['SECTION_ID'] = $arResult['VARIABLES']['SECTION_ID'] = 0;
	}
	if ($arParams['CACHE_GROUPS'] == 'Y') {
		$arItemElementFilter['CHECK_PERMISSIONS'] = 'Y';
		$arItemElementFilter['GROUPS'] = $GLOBALS["USER"]->GetGroups();
	}
	$arElement = CMaxCache::CIblockElement_GetList(array('CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'MULTI' => 'N')), $arItemElementFilter, false, false, array('ID', 'PREVIEW_TEXT', 'IBLOCK_SECTION_ID', 'PREVIEW_PICTURE', 'DETAIL_PICTURE'));
	if ($arElement) {
		include_once('detail.php');
		return ;
	}
	// get element end?>
	<?\Bitrix\Iblock\Component\Tools::process404(
		trim($arParams["MESSAGE_404"]) ?: GetMessage("ELEMENT_NOTFOUND")
		,true
		,true
		,$arParams["SHOW_404"] === "Y"
		,$arParams["FILE_404"]
	);
	return;?>
	<div class="alert alert-warning"><?=GetMessage("SECTION_NOTFOUND")?></div>
	
	
<?elseif(!$arSection && $arParams['SET_STATUS_404'] === 'Y'):?>
	<?CMax::goto404Page();?>
<?else:?>
	
	<?/*years block*/?>
	<?if (!$arItemFilter["SECTION_ID"]) {
	    $arItemFilter["SECTION_ID"] = $arSection["ID"];
	}?>
	
	<?$arItems = CMaxCache::CIBLockElement_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']))), $arItemFilter, false, false, array('ID', 'NAME', 'ACTIVE_FROM'));
	$arYears = array();
	if ($arItems) {
		foreach ($arItems as $arItem) {
			if ($arItem['ACTIVE_FROM']) {
				if ($arDateTime = ParseDateTime($arItem['ACTIVE_FROM'], FORMAT_DATETIME)) {
					$arYears[$arDateTime['YYYY']] = $arDateTime['YYYY'];
				}
			}
		}
		if ($arYears) {
			if ($arParams['USE_FILTER'] != 'N') {
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
				if ($bHasYear) {
					$GLOBALS[$arParams["FILTER_NAME"]][] = array(
						">=DATE_ACTIVE_FROM" => ConvertDateTime("01.01.".$year, "DD.MM.YYYY"),
						"<DATE_ACTIVE_FROM" => ConvertDateTime("01.01.".($year+1), "DD.MM.YYYY"),
					);
				}?>
			<?}
		}
	}?>
	<?/*end years block*/?>
	
	
	<?$this->SetViewTarget('product_share');?>
		<?if($arParams['USE_RSS'] !== 'N'):?>
			<div class="colored_theme_hover_bg-block">
				<?=CMax::ShowRSSIcon(CComponentEngine::makePathFromTemplate($arResult['FOLDER'].$arResult['URL_TEMPLATES']['rss_section'], array_map('urlencode', $arResult['VARIABLES'])));?>
			</div>
		<?endif;?>
	<?$this->EndViewTarget();?>
	
	<?
	$arAllSections = $aMenuLinksExt = [];
	$arSections = CMaxCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => array('ID'), 'MULTI' => 'N', 'URL_TEMPLATE' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['section'])), array('IBLOCK_ID' => $arParams['IBLOCK_ID'], /*'<=DEPTH_LEVEL' => 2,*/ 'ACTIVE' => 'Y', 'CNT_ACTIVE' => "Y"), false, array('ID', 'SECTION_PAGE_URL', 'DEPTH_LEVEL', 'IBLOCK_SECTION_ID'));
	$arSectionsByParentSectionID = CMaxCache::GroupArrayBy($arSections, array('MULTI' => 'Y', 'GROUP' => array('IBLOCK_SECTION_ID')));
	if ($arSections) {
		CMax::getSectionChilds(false, $arSections, $arSectionsByParentSectionID, $arItemsBySectionID, $aMenuLinksExt, true);
	}
	
	$arAllSections = CMax::getChilds2($aMenuLinksExt);
	?>
	<?
	if (isset($arItemFilter['CODE'])) {
		unset($arItemFilter['CODE']);
		unset($arItemFilter['SECTION_CODE']);
	}
	if (isset($arItemFilter['ID'])) {
		unset($arItemFilter['ID']);
		unset($arItemFilter['SECTION_ID']);
	}
	?>
	<?
	$arTags = array();

	$cur_page = $GLOBALS['APPLICATION']->GetCurPage(true);
	$cur_page_no_index = $GLOBALS['APPLICATION']->GetCurPage(false);

	foreach($arAllSections as $key => $arItem)
	{
		$arElements = CMaxCache::CIblockElement_GetList(array('CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'MULTI' => 'Y')), array_merge($arItemFilter, array("SECTION_ID" => $arItem["PARAMS"]["ID"], "INCLUDE_SUBSECTIONS" => "Y")), false, false, array('ID', 'TAGS', 'IBLOCK_SECTION_ID'));
		if(!$arElements)
			unset($arAllSections[$key]);
		else
		{
			foreach($arElements as $arElement)
			{
				if($arElement['TAGS'] && $arElement['IBLOCK_SECTION_ID'] == $arSection['ID'])
				{
					$arTags[] = explode(',', $arElement['TAGS']);
				}
			}
			$arAllSections[$key]['ELEMENT_COUNT'] = count($arElements);
			$arAllSections[$key]['CURRENT'] = CMenu::IsItemSelected($arItem['LINK'], $cur_page, $cur_page_no_index);
			if ($arItem['CHILD']) {
				foreach ($arItem['CHILD'] as $key2 => $arChild) {
					if (CMenu::IsItemSelected($arChild['LINK'], $cur_page, $cur_page_no_index)) {
						$arAllSections[$key]['CHILD'][$key2]['CURRENT'] = 'darken bold';
						$arAllSections[$key]['CURRENT'] = true;
					}
				}
			}
		}
	}
	if(!$arSections[$arSection['ID']])
	{
		\Bitrix\Iblock\Component\Tools::process404(
			trim($arParams["MESSAGE_404"]) ?: GetMessage("ELEMENT_NOTFOUND")
			,true
			,true
			,$arParams["SHOW_404"] === "Y"
			,$arParams["FILE_404"]
		);
		return;
	}
	?>
	<?
	// edit/add/delete buttons for edit mode
	$arSectionButtons = CIBlock::GetPanelButtons($arSection['IBLOCK_ID'], 0, $arSection['ID'], array('SESSID' => false, 'CATALOG' => true));
	$this->AddEditAction($arSection['ID'], $arSectionButtons['edit']['edit_section']['ACTION_URL'], CIBlock::GetArrayByID($arSection['IBLOCK_ID'], 'SECTION_EDIT'));
	$this->AddDeleteAction($arSection['ID'], $arSectionButtons['edit']['delete_section']['ACTION_URL'], CIBlock::GetArrayByID($arSection['IBLOCK_ID'], 'SECTION_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<div class="main-section-wrapper" id="<?=$this->GetEditAreaId($arSection['ID'])?>">
		<?$this->__component->__template->SetViewTarget('under_sidebar_content');?>
			<?if($arAllSections):?>

				<div class="categories_block menu_top_block">
					
					<ul class="categories left_menu dropdown">
						<?foreach($arAllSections as $arSection):
							if(isset($arSection['TEXT']) && $arSection['TEXT']):?>
								<li class="categories_item  item v_bottom <?=($arSection['CHILD'] ? 'has-child' : '')?> <?=($arSection['CURRENT'] ? 'current opened' : '');?>">
									<a href="<?=$arSection['LINK'];?>" class="categories_link bordered rounded2">
										<span class="categories_name darken"><?=$arSection['TEXT'];?></span>
										<span class="categories_count muted"><?=$arSection['ELEMENT_COUNT'];?></span>
										<?if ($arSection['CHILD']):?>
											<?=CMax::showIconSvg("down", SITE_TEMPLATE_PATH.'/images/svg/trianglearrow_down.svg', '', '', true, false);?>
											<span class="toggle_block"></span>
										<?endif;?>
									</a>
									<?if ($arSection['CHILD']):?>
										<div class="child_container dropdown">
											<div class="child_wrapp">
												<ul class="child">
													<?foreach ($arSection['CHILD'] as $arChild):?>
														<li class="menu_item hover_color_theme ">
															<a href="<?=$arChild['LINK'];?>">
																<span class="<?=($arChild['CURRENT'] ? $arChild['CURRENT'] : '');?>"><?=$arChild['TEXT'];?></span>
															</a>
														</li>
													<?endforeach;?>
												</ul>
											</div>
										</div>
									<?endif;?>
								</li>
							<?endif;?>
						<?endforeach;?>
					</ul>
				</div>

			<?endif;?>
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
					"FILTER_NAME" => "",
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
				), $component
			);?>
		<?$this->__component->__template->EndViewTarget();?>

		<?//global $arTheme;?>
	    
		
	    
		<?// section elements?>
		<?if((isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") || (strtolower($_REQUEST['ajax']) == 'y'))
		{
			$APPLICATION->RestartBuffer();
		}?>
		<?$sViewElementsTemplate = ($arParams["SECTION_ELEMENTS_TYPE_VIEW"] == "FROM_MODULE" ? $arTheme["BLOG_PAGE"]["VALUE"] : $arParams["SECTION_ELEMENTS_TYPE_VIEW"]);?>
		<?@include_once('page_blocks/'.$sViewElementsTemplate.'.php');?>
		<?if((isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") || (strtolower($_REQUEST['ajax']) == 'y'))
		{
			die();
		}?>
	</div>
<?endif;?>
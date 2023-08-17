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
$itemsCnt = CMaxCache::CIblockElement_GetList(array("CACHE" => array("TAG" => CMaxCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), $arItemFilter, array());


?>

<?$this->SetViewTarget('product_share');?>
	<?if($arParams['USE_RSS'] !== 'N'):?>
		<div class="colored_theme_hover_bg-block">
			<?=CMax::ShowRSSIcon($arResult['FOLDER'].$arResult['URL_TEMPLATES']['rss']);?>
		</div>
	<?endif;?>
<?$this->EndViewTarget();?>

<? 
$showForm = ($arParams['FORM'] == 'Y');
$pathVacancy = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].SITE_DIR."include/vacancy_page/");
//var_dump(file_get_contents($pathVacancy."contact_person.php"));
$showPerson = trim(file_get_contents($pathVacancy."contact_person.php"))!= '' ;
$showEmail = trim(file_get_contents($pathVacancy."contact_email.php"))!= '';
$showPhone = trim(file_get_contents($pathVacancy."contact_phone.php"))!= '';
$showDetailText = trim(file_get_contents($pathVacancy."contact_detail.php"))!= '';
?>

<?if($arParams["SHOW_TOP_VACANCY_BLOCK"] == "Y"):?>
	<div class="vacancy_desc">
		<div class="properties bordered">
			<?if($showForm):?>
				<div class="button_wrap pull-left">
					<span><span class="btn btn-default btn-lg animate-load" data-event="jqm" data-name="resume" data-param-form_id="<?=$arParams["FORM_ID"] ? $arParams["FORM_ID"] : 'RESUME'?>" ><?=($arParams["FORM_BUTTON_TITLE"] ? $arParams["FORM_BUTTON_TITLE"] : GetMessage('FORM_BUTTON_TITLE'));?></span></span>
				</div>
			<?endif;?>
			<div class="wrap<?=(!$showForm ? ' wtform' : '')?>">
				<div class="row">
					
					<div class="property item contact col-lg-5 col-md-12 col-sm-12 col-xs-12 <?=(!$showPerson ? 'hidden' : '')?>">
						<div class="title-prop muted font_upper"><?=GetMessage('CONTACT_PERSON');?></div>
						<div class="value darken">
							<?$APPLICATION->IncludeFile(SITE_DIR."include/vacancy_page/contact_person.php", array(), array( 
			                        "MODE" => "text", 
			                        "NAME" => "contact person", 
			                        "TEMPLATE" => "include_area.php", 
			                    ) 
			                );?>
						</div>
					</div>
					
					
					<div class="property item email col-lg-4 col-md-6 col-sm-6 col-xs-12 <?=(!$showEmail ? 'hidden' : '')?>">
						<div class="title-prop muted font_upper"><?=GetMessage('CONTACT_EMAIL');?></div>
						<div class="value darken">
							<?$APPLICATION->IncludeFile(SITE_DIR."include/vacancy_page/contact_email.php", array(), array( 
			                        "MODE" => "text", 
			                        "NAME" => "contact email", 
			                        "TEMPLATE" => "include_area.php", 
			                    ) 
			                );?>
						</div>
					</div>
					
					<div class="property item phone col-lg-3 col-md-6 col-sm-6 col-xs-12 <?=(!$showPhone ? 'hidden' : '')?>">
						<div class="title-prop muted font_upper"><?=GetMessage('CONTACT_PHONE');?></div>
						<div class="value darken">
							<?$APPLICATION->IncludeFile(SITE_DIR."include/vacancy_page/contact_phone.php", array(), array( 
			                        "MODE" => "text", 
			                        "NAME" => "contact phone", 
			                        "TEMPLATE" => "include_area.php", 
			                    ) 
			                );?>
						</div>
					</div>
					
				</div>
			</div>
			<div class="detailtext <?=(!$showDetailText ? 'hidden' : '')?>">
				<?$APPLICATION->IncludeFile(SITE_DIR."include/vacancy_page/contact_detail.php", array(), array( 
                        "MODE" => "text", 
                        "NAME" => "contact detail", 
                        "TEMPLATE" => "include_area.php", 
                    ) 
                );?>
			</div>
		</div>
	</div>
<?endif;?>


<?if(!$itemsCnt):?>
	<div class="alert alert-warning"><?=GetMessage("SECTION_EMPTY")?></div>
<?else:?>
	<?if((isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") || (strtolower($_REQUEST['ajax']) == 'y'))
	{
		$APPLICATION->RestartBuffer();
	}?>

	<?//global $arTheme;?>
	<?// section elements?>
	<?$sViewElementsTemplate = ($arParams["SECTION_ELEMENTS_TYPE_VIEW"] == "FROM_MODULE" ? $arTheme["VACANCY_PAGE"]["VALUE"] : $arParams["SECTION_ELEMENTS_TYPE_VIEW"]);?>
	<?@include_once('page_blocks/'.$sViewElementsTemplate.'.php');?>

	<?if((isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") || (strtolower($_REQUEST['ajax']) == 'y'))
	{
		die();
	}?>
	
<?endif;?>
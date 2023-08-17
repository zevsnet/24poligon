<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?global $arTheme, $APPLICATION, $arSectionFilter;?>
<?$APPLICATION->AddViewContent('right_block_class', 'catalog_page ');?>

<?$arSectionFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID']);
CMax::makeSectionFilterInRegion($arSectionFilter);?>

<?// region filter for to count elements
if(
	$GLOBALS['arRegion'] &&
	$GLOBALS['arTheme']['USE_REGIONALITY']['VALUE'] === 'Y' &&
	$GLOBALS['arTheme']['USE_REGIONALITY']['DEPENDENT_PARAMS']['REGIONALITY_FILTER_ITEM']['VALUE'] === 'Y'
){
	// unrem this for hide empty sections without region`s products
	//$arSectionFilter['PROPERTY'] = array('LINK_REGION' => $GLOBALS['arRegion']['ID']);

	$arSectionFilter['PROPERTY_LINK_REGION'] = $GLOBALS['arRegion']['ID'];
}?>

<?$sViewElementTemplate = ($arParams["SECTIONS_TYPE_VIEW"] == "FROM_MODULE" ? $arTheme["CATALOG_PAGE_SECTIONS"]["VALUE"] : $arParams["SECTIONS_TYPE_VIEW"]);?>
<?$bShowLeftBlock = ($arTheme["LEFT_BLOCK_CATALOG_ROOT"]["VALUE"] == "Y" && !defined("ERROR_404") && !($arTheme['HEADER_TYPE']['VALUE'] == 28 || $arTheme['HEADER_TYPE']['VALUE'] == 29));?>
<?$APPLICATION->SetPageProperty("HIDE_LEFT_BLOCK", ( $bShowLeftBlock ? 'N' : 'Y' ) );?>
<div class="main-catalog-wrapper">
	<div class="section-content-wrapper <?=($bShowLeftBlock ? 'with-leftblock' : '');?>">
		<?@include_once('page_blocks/'.$sViewElementTemplate.'.php');?>
		<?CMax::get_banners_position('CONTENT_BOTTOM');
		global $bannerContentBottom;
		$bannerContentBottom = true;
		?>
	</div>
	<?if($bShowLeftBlock):?>
		<?CMax::ShowPageType('left_block');?>
	<?endif;?>
</div>

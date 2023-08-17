<?
use CMax as Solution,
	Aspro\Max\Functions\Extensions;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

Extensions::init('searchtitle');

$INPUT_ID = trim($arParams["~INPUT_ID"]);
if(strlen($INPUT_ID) <= 0)
	$INPUT_ID = "title-search-input";
$INPUT_ID = CUtil::JSEscape($INPUT_ID);
$CONTAINER_ID = trim($arParams["~CONTAINER_ID"]);
if(strlen($CONTAINER_ID) <= 0)
	$CONTAINER_ID = "title-search";
$CONTAINER_ID = CUtil::JSEscape($CONTAINER_ID);

global $isFixedTopSearch;
$INPUT_ID_TMP = $INPUT_ID;
if(isset($isFixedTopSearch) && $isFixedTopSearch)
{
	$CONTAINER_ID .= 'tf';
	$INPUT_ID .= 'tf';
}

$bShowSearchType = Aspro\Max\SearchTitle::isNeed2ShowWhere();
if ($bShowSearchType) {
	$searchType = Aspro\Max\SearchTitle::getType();
}
?>
<?if($arParams["SHOW_INPUT"] !== "N"):?>
	<div class="search-wrapper">
		<div id="<?=$CONTAINER_ID?>">
			<form action="<?=$arResult["FORM_ACTION"]?>" class="search<?=($bShowSearchType ? ' search--hastype' : '')?>">
				<div class="search-input-div">
					<input class="search-input" id="<?=$INPUT_ID?>" type="text" name="q" value="" placeholder="<?=GetMessage("CT_BST_SEARCH_BUTTON")?>" size="20" maxlength="50" autocomplete="off" />
				</div>
				<div class="search-button-div">
					<button class="btn btn-search" type="submit" name="s" value="<?=GetMessage("CT_BST_SEARCH_BUTTON2")?>"><?=CMax::showSpriteIconSvg(SITE_TEMPLATE_PATH."/images/svg/header_icons_srite.svg#search", "search2 ", ['WIDTH' => 17,'HEIGHT' => 17]);?></button>

					<?if ($bShowSearchType):?>
						<div class="dropdown-select searchtype">
							<input type="hidden" name="type" value="<?=$searchType?>" />

							<div class="dropdown-select__title darken font_xs">
								<span><?=GetMessage($searchType === 'all' ? 'SEARCH_IN_SITE' : 'SEARCH_IN_CATALOG')?></span>
								<?=CMax::showIconSvg("search-down", SITE_TEMPLATE_PATH.'/images/svg/trianglearrow_down.svg', '', '', true, false);?>
							</div>

							<div class="dropdown-select__list dropdown-menu-wrapper" role="menu">
								<!--noindex-->
								<div class="dropdown-menu-inner rounded3">
									<div class="dropdown-select__list-item font_xs">
										<span class="dropdown-select__list-link<?=($searchType === 'all' ? ' dropdown-select__list-link--current' : ' darken')?>" data-type="all">
											<span><?=GetMessage('SEARCH_IN_SITE_FULL')?></span>
										</span>
									</div>
									<div class="dropdown-select__list-item font_xs">
										<span class="dropdown-select__list-link<?=($searchType === 'catalog' ? ' dropdown-select__list-link--current' : ' darken')?>" data-type="catalog">
											<span><?=GetMessage('SEARCH_IN_CATALOG_FULL')?></span>
										</span>
									</div>
								</div>
								<!--/noindex-->
							</div>
						</div>
					<?endif;?>

					<span class="close-block inline-search-hide"><span class="svg svg-close close-icons"></span></span>
				</div>
			</form>
		</div>
	</div>
<?endif;?>
<script type="text/javascript">
	var jsControl = new JCTitleSearch3({
		//'WAIT_IMAGE': '/bitrix/themes/.default/images/wait.gif',
		'AJAX_PAGE' : '<?=CUtil::JSEscape(POST_FORM_ACTION_URI)?>',
		'CONTAINER_ID': '<?=$CONTAINER_ID?>',
		'INPUT_ID': '<?=$INPUT_ID?>',
		'INPUT_ID_TMP': '<?=$INPUT_ID_TMP?>',
		'MIN_QUERY_LEN': 2
	});
</script>
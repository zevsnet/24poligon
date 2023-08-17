<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */

$this->setFrameMode(true);

$class_block="s_".$this->randString();

$arTab=array();
$arParams["DISPLAY_BOTTOM_PAGER"] = "Y";
$arParams['SET_TITLE'] = 'N';
$arTmp = reset($arResult["TABS"]);
$arParams["FILTER_HIT_PROP"] = $arTmp["CODE"];
$arParamsTmp = urlencode(serialize($arParams));

if($arResult["SHOW_SLIDER_PROP"]):?>
	<div class="content_wrapper_block <?=$templateName;?>">
		<div class="maxwidth-theme">
			<div class="tab_slider_wrapp specials <?=$class_block;?> best_block clearfix" itemscope itemtype="http://schema.org/WebPage">
				<span class='request-data' data-value='<?=$arParamsTmp?>'></span>
				<div class="top_block">
					<?if($arParams['TITLE_BLOCK']):?>
						<h3><?=$arParams['TITLE_BLOCK'];?></h3>
					<?endif;?>
					<div class="right_block_wrapper">
						<div class="tabs_wrapper <?=$arParams['TITLE_BLOCK_ALL'] && $arParams['ALL_URL'] ? 'with_link' : ''?>">
							<ul class="tabs ajax">
								<?$i=1;
								foreach($arResult["TABS"] as $code => $arTab):?>
									<li data-code="<?=$code?>" class="font_xs <?=($i==1 ? "cur clicked" : "")?>"><span class="muted777"><?=$arTab["TITLE"];?></span></li>
									<?$i++;?>
								<?endforeach;?>
							</ul>
						</div>
						<?if($arParams['TITLE_BLOCK_ALL'] && $arParams['ALL_URL']):?>
							<a href="<?=$arParams['ALL_URL'];?>" class="font_upper muted"><?=$arParams['TITLE_BLOCK_ALL'];?></a>
						<?endif;?>
					</div>
				</div>
				<ul class="tabs_content">
					<?$j=1;?>
					<?foreach($arResult["TABS"] as $code => $arTab):?>
						<li class="tab <?=$code?>_wrapp <?=($j == 1 ? "cur opacity1" : "");?>" data-code="<?=$code?>" data-filter="<?=($arTab["FILTER"] ? urlencode(serialize($arTab["FILTER"])) : '');?>">
							<div class="tabs_slider <?=$code?>_slides wr">
								<?if(strtolower($_REQUEST['ajax']) == 'y')
									$APPLICATION->RestartBuffer();?>
								<?if($j++ == 1)
								{
									if($arTab["FILTER"])
										$GLOBALS[$arParams["FILTER_NAME"]] = $arTab["FILTER"];

									include(str_replace("//", "/", $_SERVER["DOCUMENT_ROOT"].SITE_DIR."include/mainpage/comp_catalog_ajax.php"));
								}?>
								<?if(strtolower($_REQUEST['ajax']) == 'y')
									CMax::checkRestartBuffer(true, 'catalog_tab');?>
							</div>
						</li>
					<?endforeach;?>
				</ul>
			</div>
		</div>
	</div>
	<script>try{window.tabsInitOnReady();}catch{}</script>
<?endif;?>
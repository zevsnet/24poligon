<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true );?>
<?if($arResult['SECTIONS']):?>
	<?
	$viewType = $arParams['VIEW_TYPE'];
	$sectionIndex = 0;
	$arParams['SORT'] = $arParams['SORT'] ? $arParams['SORT'] : 'SORT';
	$arParams['SORT_ORDER'] = $arParams['SORT_ORDER'] ? $arParams['SORT_ORDER'] : 'ASC';
	$arParams['SORT_2'] = $arParams['SORT_2'] ? $arParams['SORT_2'] : 'ID';
	$arParams['SORT_ORDER_2'] = $arParams['SORT_ORDER_2'] ? $arParams['SORT_ORDER_2'] : 'ASC';
	$bShowTitle = ($arParams["TITLE_BLOCK"] || $arParams["TITLE_BLOCK_ALL"]) && $arParams["TITLE_BLOCK_SHOW"] != 'N';
	?>
	<div class="content_wrapper_block front_stories <?=$viewType?> <?=$bShowTitle ? '' : 'no-title'?>" data-sort=<?=$arParams['SORT']?> data-sort-order=<?=$arParams['SORT_ORDER']?> data-sort2=<?=$arParams['SORT_2']?> data-sort2-order=<?=$arParams['SORT_ORDER_2']?> >
		
		<?if($arParams['FRONT_PAGE'] == 'Y'):?>
			<div class="maxwidth-theme only-on-front">

			<?if($bShowTitle):?>
				<div class="top_block">
					<h3 class="title_block"><?=$arParams["TITLE_BLOCK"];?></h3>
					<a href="<?=SITE_DIR.$arParams["ALL_URL"];?>" class="pull-right font_upper muted"><?=$arParams["TITLE_BLOCK_ALL"] ;?></a>
				</div>
			<?endif;?>
		<?endif;?>

			<div class="tab_slider_wrapp stories">
				<div class="owl-carousel owl-theme owl-bg-nav loading_state short-nav hidden-dots visible-nav swipeignore"  data-plugin-options='{"nav": true, "margin":32,"autoplay": false, "dots": false, "marginMove": true, "loop": false, "responsive": {"0":{"items": 2, "autoWidth": true, "lightDrag": true, "margin":16},"601":{"items": 4, "autoWidth": false, "lightDrag": false, "margin":32},"768":{"items": 5},"992":{"items": 6}, "1200":{"items": <?=($viewType == 'ROUND' ? '8' : '7')?>}}}'>
					<?foreach($arResult['SECTIONS'] as $arSection):
						if($arParams["COUNT_ELEMENTS"] && !$arSection['ELEMENT_CNT']) {
							continue;
						}
						$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_EDIT"));
						$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_SECTION_DELETE_CONFIRM')));?>
						<div class="item <?=($viewType == 'ROUND' ? 'color-theme-hover' : '')?>" id="<?=$this->GetEditAreaId($arSection['ID']);?>" data-iblock-id=<?=$arParams['IBLOCK_ID']?> data-section-id=<?=$arSection['ID']?> data-index=<?=$sectionIndex?> >
							<div class="img">
								<?if($arSection["PICTURE"]["SRC"]):?>
									<span class="lazy" data-src="<?=$arSection["PICTURE"]["SRC"]?>" style="background-image:url(<?=\Aspro\Functions\CAsproMax::showBlankImg($arSection["PICTURE"]["SRC"]);?>)"></span>
								<?endif;?>
							</div>
							<div class="name font_xs">
								<?=$arSection['NAME'];?>
							</div>
						</div>
						<?$sectionIndex++;?>
					<?endforeach;?>
				</div>
			</div>

		<?if($arParams['FRONT_PAGE'] == 'Y'):?>
			</div>
		<?endif;?>
	</div>
<?endif;?>
<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true );?>
<?if($arResult['SECTIONS']):?>
	<?$bIcons = ($arParams['SHOW_ICONS'] == 'Y');?>
	<div class="content_wrapper_block <?=$templateName;?>">
		<div class="maxwidth-theme">
			<div class="sections_wrapper smalls<?=($bIcons ? ' icons' : '');?>">
				<?if($arParams["TITLE_BLOCK"] || $arParams["TITLE_BLOCK_ALL"]):?>
					<div class="top_block">
						<h3><?=$arParams["TITLE_BLOCK"];?></h3>
						<a href="<?=SITE_DIR.$arParams["ALL_URL"];?>" class="pull-right font_upper muted"><?=$arParams["TITLE_BLOCK_ALL"] ;?></a>
					</div>
				<?endif;?>
				<div class="list items">
					<div class="owl-carousel owl-theme owl-bg-nav short-nav hidden-dots visible-nav loading_state cat_sections items slides swipeignore" data-plugin-options='{"animation": "slide", "useCSS": true, "nav": true, "margin":<?=($bIcons ? ' 30' : '50');?>, "dots": false, "loop": false, "rewind": true, "autoplay": false, "responsive": {"0":{"items": 1, "autoWidth": true, "lightDrag": true},"601":{"items": 3, "autoWidth": false, "lightDrag": false},"768":{"items": 4},"992":{"items": 5}, "1200":{"items": 6}}}'>
						<?foreach($arResult['SECTIONS'] as $arSection):
							$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "ELEMENT_EDIT"));
							$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
							<?if($arParams['USE_FILTER_SECTION'] == 'Y' && $arParams['BRAND_NAME'])
							{
								$arSection["SECTION_PAGE_URL"] .= "filter/brand-is-".$arParams['BRAND_CODE']."/apply/";
							}?>
							<li class="item_block visible height0">
								<div class="item compact" id="<?=$this->GetEditAreaId($arSection['ID']);?>">
									<div class="img shine">
										<?if($bIcons && $arSection["UF_CATALOG_ICON"]):?>
											<?$img = CFile::ResizeImageGet($arSection["UF_CATALOG_ICON"], array( "width" => 40, "height" => 40 ), BX_RESIZE_IMAGE_EXACT, true );?>
											<a href="<?=$arSection["SECTION_PAGE_URL"]?>" class="thumb">
												<?if(strpos($img["src"], ".svg") !== false):?>
													<?=file_get_contents($_SERVER["DOCUMENT_ROOT"].$img["src"]);?>
												<?else:?>
													<img class="lazy" data-src="<?=$img["src"]?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($img["src"]);?>" alt="<?=($arSection["PICTURE"]["ALT"] ? $arSection["PICTURE"]["ALT"] : $arSection["NAME"])?>" title="<?=($arSection["PICTURE"]["TITLE"] ? $arSection["PICTURE"]["TITLE"] : $arSection["NAME"])?>" />
												<?endif;?>
											</a>
										<?else:?>
											<?if($arSection["PICTURE"]["SRC"]):?>
												<?$img = CFile::ResizeImageGet($arSection["PICTURE"]["ID"], array( "width" => 90, "height" => 90 ), BX_RESIZE_IMAGE_EXACT, true );?>
												<a href="<?=$arSection["SECTION_PAGE_URL"]?>" class="thumb"><img  class="lazy" data-src="<?=$img["src"]?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($img["src"]);?>" alt="<?=($arSection["PICTURE"]["ALT"] ? $arSection["PICTURE"]["ALT"] : $arSection["NAME"])?>" title="<?=($arSection["PICTURE"]["TITLE"] ? $arSection["PICTURE"]["TITLE"] : $arSection["NAME"])?>" /></a>
											<?elseif($arSection["~PICTURE"]):?>
												<?$img = CFile::ResizeImageGet($arSection["~PICTURE"], array( "width" => 90, "height" => 90 ), BX_RESIZE_IMAGE_EXACT, true );?>
												<a href="<?=$arSection["SECTION_PAGE_URL"]?>" class="thumb"><img  class="lazy" data-src="<?=$img["src"]?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($img["src"]);?>" alt="<?=($arSection["PICTURE"]["ALT"] ? $arSection["PICTURE"]["ALT"] : $arSection["NAME"])?>" title="<?=($arSection["PICTURE"]["TITLE"] ? $arSection["PICTURE"]["TITLE"] : $arSection["NAME"])?>" /></a>
											<?else:?>
												<a href="<?=$arSection["SECTION_PAGE_URL"]?>" class="thumb"><img class="lazy" data-src="<?=SITE_TEMPLATE_PATH?>/images/svg/noimage_product.svg" src="<?=\Aspro\Functions\CAsproMax::showBlankImg(SITE_TEMPLATE_PATH.'/images/svg/noimage_product.svg');?>" alt="<?=$arSection["NAME"]?>" title="<?=$arSection["NAME"]?>" height="90" /></a>
											<?endif;?>
										<?endif;?>
									</div>
									<div class="name font_sm">
										<a href="<?=$arSection['SECTION_PAGE_URL'];?>" class="dark_link"><?=$arSection['NAME'];?></a>
									</div>
								</div>
							</li>
						<?endforeach;?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?endif;?>
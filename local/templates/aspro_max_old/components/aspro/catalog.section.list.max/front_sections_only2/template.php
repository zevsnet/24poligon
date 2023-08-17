<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
use \Bitrix\Main\Localization\Loc;
?>
<? $this->setFrameMode( true );?>
<?if($arResult['SECTIONS']):?>
	<?
	$bIcons = ($arParams['SHOW_ICONS'] == 'Y');
	$bAddlSlideToAll = $arParams['LAST_LINK_IN_SLIDER'] === 'Y';
	$bRounded = !$bIcons && $arParams['SHAPE_PICTURES'] === 'round';
	$slidesPerView = (int)$arParams['SLIDER_ELEMENTS_COUNT'] > 1 ? (int)$arParams['SLIDER_ELEMENTS_COUNT'] : 6;
	$hrefToAll = SITE_DIR.$arParams["ALL_URL"];
	?>
	<div class="content_wrapper_block <?=$templateName;?>">
		<div class="maxwidth-theme">
			<div class="sections_wrapper smalls<?=($bIcons ? ' icons' : '');?>">
				<?if($arParams["TITLE_BLOCK"] || ($arParams["TITLE_BLOCK_ALL"] && !$bAddlSlideToAll)):?>
					<div class="top_block">
						<h3><?=$arParams["TITLE_BLOCK"];?></h3>
						<?if(!$bAddlSlideToAll):?>
							<a href="<?=$hrefToAll;?>" class="pull-right font_upper muted"><?=$arParams["TITLE_BLOCK_ALL"] ;?></a>
						<?endif;?>
					</div>
				<?endif;?>
				<div class="list items swiper-nav-offset sections-slider-wrap">
					<?
					$countSlides = is_array($arResult['SECTIONS']) ? count($arResult['SECTIONS']) : 0;
					$arOptions = [
						'preloadImages' => false,
						'keyboard' => true,
						'init' => false,
						// 'rewind'=> true,						
						'freeMode' => ['enabled' => true, 'momentum' => true],
						'slidesPerView' => 'auto',
						'spaceBetween' => ($bIcons || $bRounded) ? 32 : 50,
						'pagination' => false,
						'touchEventsTarget' => 'container',
						'type' => 'main_sections',
						'breakpoints' => [
							'601' => [
								'slidesPerView' => 3,
								'freeMode' => false,
							],
							'768' => [
								'slidesPerView' => 4 ,
								'freeMode' => false,
							],
							'992' => [
								'slidesPerView' => $slidesPerView - 1 ,
								'freeMode' => false,
							],
							'1200' => [
								'slidesPerView' => $slidesPerView,
								'freeMode' => false,
							]
						]
					];
					?>
					<div class="swiper slider-solution loading_state1 cat_sections items swipeignore <?= ($bRounded ? 'cat_sections--round' : '')?>" data-plugin-options='<?= \Bitrix\Main\Web\Json::encode($arOptions); ?>'>
						<div class="swiper-wrapper no-shrinked">
							<?foreach($arResult['SECTIONS'] as $arSection):
								$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "ELEMENT_EDIT"));
								$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => Loc::GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
								<?if($arParams['USE_FILTER_SECTION'] == 'Y' && $arParams['BRAND_NAME'])
								{
									$arSection["SECTION_PAGE_URL"] .= "filter/brand-is-".$arParams['BRAND_CODE']."/apply/";
								}?>
								<div class="swiper-slide item_block">
									<div class="item compact" id="<?=$this->GetEditAreaId($arSection['ID']);?>">
										<div class="img shine <?=($bRounded ? 'rounded' : '')?>">
											<?if($bIcons && $arSection["UF_CATALOG_ICON"]):?>
												<?$img = CFile::ResizeImageGet($arSection["UF_CATALOG_ICON"], array( "width" => 40, "height" => 40 ), BX_RESIZE_IMAGE_EXACT, true );?>
												<a href="<?=$arSection["SECTION_PAGE_URL"]?>" class="thumb">
													<?if(strpos($img["src"], ".svg") !== false && \CMax::GetFrontParametrValue('COLORED_CATALOG_ICON') === 'Y'):?>
														<?=\Aspro\Functions\CAsproMax::showSVG([
															'PATH' => $img["src"]
														]);?>
													<?else:?>
														<img class="lazy" data-src="<?=$img["src"]?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($img["src"]);?>" alt="<?=($arSection["PICTURE"]["ALT"] ? $arSection["PICTURE"]["ALT"] : $arSection["NAME"])?>" title="<?=($arSection["PICTURE"]["TITLE"] ? $arSection["PICTURE"]["TITLE"] : $arSection["NAME"])?>" />
													<?endif;?>
												</a>
											<?else:?>
												<?if($arSection["PICTURE"]["SRC"]):?>
													<?$img = CFile::ResizeImageGet($arSection["PICTURE"]["ID"], array( "width" => 150, "height" => 150 ), BX_RESIZE_IMAGE_EXACT, true );?>
													<a href="<?=$arSection["SECTION_PAGE_URL"]?>" class="thumb"><img  class="lazy" data-src="<?=$img["src"]?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($img["src"]);?>" alt="<?=($arSection["PICTURE"]["ALT"] ? $arSection["PICTURE"]["ALT"] : $arSection["NAME"])?>" title="<?=($arSection["PICTURE"]["TITLE"] ? $arSection["PICTURE"]["TITLE"] : $arSection["NAME"])?>" /></a>
												<?elseif($arSection["~PICTURE"]):?>
													<?$img = CFile::ResizeImageGet($arSection["~PICTURE"], array( "width" => 150, "height" => 150 ), BX_RESIZE_IMAGE_EXACT, true );?>
													<a href="<?=$arSection["SECTION_PAGE_URL"]?>" class="thumb"><img  class="lazy" data-src="<?=$img["src"]?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($img["src"]);?>" alt="<?=($arSection["PICTURE"]["ALT"] ? $arSection["PICTURE"]["ALT"] : $arSection["NAME"])?>" title="<?=($arSection["PICTURE"]["TITLE"] ? $arSection["PICTURE"]["TITLE"] : $arSection["NAME"])?>" /></a>
												<?else:?>
													<a href="<?=$arSection["SECTION_PAGE_URL"]?>" class="thumb"><img class="lazy" data-src="<?=SITE_TEMPLATE_PATH?>/images/svg/noimage_product.svg" src="<?=\Aspro\Functions\CAsproMax::showBlankImg(SITE_TEMPLATE_PATH.'/images/svg/noimage_product.svg');?>" alt="<?=$arSection["NAME"]?>" title="<?=$arSection["NAME"]?>" height="" /></a>
												<?endif;?>
											<?endif;?>
										</div>
										<div class="name font_sm">
											<a href="<?=$arSection['SECTION_PAGE_URL'];?>" class="dark_link"><?=$arSection['NAME'];?></a>
										</div>
									</div>
								</div>
							<?endforeach;?>
							<?if($bAddlSlideToAll):?>
								<?
								$textAllItems = $arParams["TITLE_BLOCK_ALL"] ?: Loc::GetMessage('T_BLOCK_ALL_CATALOG');
								?>
								<div class="swiper-slide item_block cat-sections-all">
									<div class="item compact">
										<div class="img cat-sections-all__image <?=($bRounded ? 'rounded' : '')?>">
											<a href="<?=$hrefToAll?>" class="thumb">													
												<?=CMax::showIconSvg("cat_sections", SITE_TEMPLATE_PATH.'/images/svg/catalog/catalog_sections.svg')?>
											</a>
										</div>
										<div class="name font_sm">
											<a href="<?=$hrefToAll?>" class="dark_link"><?=$textAllItems?></a>
										</div>
									</div>
								</div>
							<?endif;?>
						</div>
					</div>
					<?if ($countSlides > 1):?>
						<div class="swiper-button-prev swiper-nav swiper-nav--hide-600"></div>
						<div class="swiper-button-next swiper-nav swiper-nav--hide-600"></div>
					<?endif;?>
				</div>
			</div>
		</div>
	</div>
<?endif;?>
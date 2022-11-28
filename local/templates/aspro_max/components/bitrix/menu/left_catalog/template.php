<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<?if( !empty( $arResult ) ){
	global $arTheme;
	$bRightSide = $arTheme['SHOW_RIGHT_SIDE']['VALUE'] == 'Y';
	$RightContent = $arTheme['SHOW_RIGHT_SIDE']['DEPENDENT_PARAMS']['RIGHT_CONTENT']['VALUE'];
	$bRightBanner = $bRightSide && $RightContent == 'BANNER';
	$bRightBrand = $bRightSide && $RightContent == 'BRANDS';
	?>
	<div class="menu_top_block catalog_block">
		<ul class="menu dropdown">
			<?foreach( $arResult as $key => $arItem ){?>
				<li class="full <?=($arItem["CHILD"] ? "has-child" : "");?> <?=($arItem["SELECTED"] ? "current opened" : "");?> m_<?=strtolower($arTheme["MENU_POSITION"]["VALUE"]);?> v_<?=strtolower($arTheme["MENU_TYPE_VIEW"]["VALUE"]);?>">
					<a class="icons_fa <?=($arItem["CHILD"] ? "parent" : "");?>" href="<?=$arItem["SECTION_PAGE_URL"]?>" >
						<?if($arItem["CHILD"]):?>
								<?=CMax::showIconSvg("right", SITE_TEMPLATE_PATH.'/images/svg/trianglearrow_right.svg', '', '');?>
							<?if(strtolower($arTheme["MENU_TYPE_VIEW"]["VALUE"]) == "bottom" ):?>
								<?=CMax::showIconSvg("down", SITE_TEMPLATE_PATH.'/images/svg/trianglearrow_down.svg', '', '');?>
							<?endif;?>
						<?endif;?>
						<?if($arItem["IMAGES"] && $arTheme["LEFT_BLOCK_CATALOG_ICONS"]["VALUE"] == "Y"){?>
							<span class="image">
								<?if(strpos($arItem["IMAGES"]["src"], ".svg") !== false):?>
									<?=CMax::showIconSvg("cat_icons", $arItem["IMAGES"]["src"]);?>
								<?else:?>
									<img class="lazy" data-src="<?=$arItem["IMAGES"]["src"];?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($arItem["IMAGES"]["src"]);?>" alt="<?=$arItem["NAME"];?>" title="<?=$arItem["NAME"]?>" /></span>
								<?endif;?>
							</span>
						<?}?>
						<span class="name"><?=$arItem["NAME"]?></span>
						<div class="toggle_block"></div>
						<div class="clearfix"></div>
					</a>
					<?if($arItem["CHILD"]){?>
						<div class="dropdown-block <?=strtolower($arTheme["MENU_TYPE_VIEW"]["VALUE"]) == 'bottom' ? 'dropdown' : ''?>">

							<div class="dropdown">

								<ul class="left-menu-wrapper">
									
									<?foreach($arItem["CHILD"] as $arChildItem){?>
										<li class="<?=($arChildItem["CHILD"] ? "has-childs" : "");?> <?if($arChildItem["SELECTED"]){?> current <?}?>">
											<?if($arChildItem["IMAGES"] && $arTheme['SHOW_CATALOG_SECTIONS_ICONS']['VALUE'] == 'Y' && $arTheme["MENU_TYPE_VIEW"]["VALUE"] !== 'BOTTOM'){?>
												<span class="image colored_theme_svg">
													<a href="<?=$arChildItem["SECTION_PAGE_URL"];?>">
														<?if(strpos($arChildItem["IMAGES"]["src"], ".svg") !== false):?>
															<?=CMax::showIconSvg("picture", $arChildItem["IMAGES"]["src"]);?>
														<?else:?>
															<img class="lazy" data-src="<?=$arChildItem["IMAGES"]["src"];?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($arChildItem["IMAGES"]["src"]);?>" alt="<?=$arChildItem["NAME"];?>" />
														<?endif;?>	
													</a>
												</span>
											<?}?>
											<a class="section option-font-bold" href="<?=$arChildItem["SECTION_PAGE_URL"];?>"><span><?=$arChildItem["NAME"];?></span></a>
											<?if($arChildItem["CHILD"]){?>
												<ul class="dropdown">
													<?foreach($arChildItem["CHILD"] as $arChildItem1){?>
														<li class="menu_item <?if($arChildItem1["SELECTED"]){?> current <?}?>">
															<a class="parent1 section1" href="<?=$arChildItem1["SECTION_PAGE_URL"];?>"><span><?=$arChildItem1["NAME"];?></span></a>
														</li>
													<?}?>
												</ul>
											<?}?>
											<div class="clearfix"></div>
										</li>
									<?}?>
								</ul>

								<?if($bRightSide):?>
									<div class="right-side <?=$RightContent?>">
										<div class="right-content">
											<?if($bRightBanner && $arItem['UF_MENU_BANNER']):?>
												<?
												if($GLOBALS['arRegionLink']) {
													$GLOBALS['rightBannersFilter'] = array_merge( $GLOBALS['arRegionLink'], array('ID' => $arItem['UF_MENU_BANNER']) );
												} else {
													$GLOBALS['rightBannersFilter'] = array('ID' => $arItem['UF_MENU_BANNER']);
												}
												$APPLICATION->IncludeComponent(
													"bitrix:news.list",
													"banners",
													array(
														"IBLOCK_TYPE" => "aspro_max_adv",
														"IBLOCK_ID" => CMaxCache::$arIBlocks[SITE_ID]["aspro_max_adv"]["aspro_max_banners_inner"][0],
														"PAGE"		=> $APPLICATION->GetCurPage(),
														'MENU_BANNER' => true,
														'SHOW_ALL_ELEMENTS' => 'N',
														//'MENU_LINK' => $arItem['link'],
														"NEWS_COUNT" => "100",
														"SORT_BY1" => "SORT",
														"SORT_ORDER1" => "ASC",
														"SORT_BY2" => "ID",
														"SORT_ORDER2" => "ASC",
														"FIELD_CODE" => array(
															0 => "NAME",
															2 => "PREVIEW_PICTURE",
														),
														"PROPERTY_CODE" => array(
															0 => "LINK",
															1 => "TARGET",
															2 => "BGCOLOR",
															3 => "SHOW_SECTION",
															4 => "SHOW_PAGE",
															5 => "HIDDEN_XS",
															6 => "HIDDEN_SM",
															7 => "POSITION",
															8 => "SIZING",
														),
														"CHECK_DATES" => "Y",
														"FILTER_NAME" => "rightBannersFilter",
														"DETAIL_URL" => "",
														"AJAX_MODE" => "N",
														"AJAX_OPTION_JUMP" => "N",
														"AJAX_OPTION_STYLE" => "Y",
														"AJAX_OPTION_HISTORY" => "N",
														"CACHE_TYPE" => "A",
														"CACHE_TIME" => "3600000",
														"CACHE_FILTER" => "Y",
														"CACHE_GROUPS" => "N",
														"PREVIEW_TRUNCATE_LEN" => "150",
														"ACTIVE_DATE_FORMAT" => "d.m.Y",
														"SET_TITLE" => "N",
														"SET_STATUS_404" => "N",
														"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
														"ADD_SECTIONS_CHAIN" => "N",
														"HIDE_LINK_WHEN_NO_DETAIL" => "N",
														"PARENT_SECTION" => "",
														"PARENT_SECTION_CODE" => "",
														"INCLUDE_SUBSECTIONS" => "Y",
														"PAGER_TEMPLATE" => ".default",
														"DISPLAY_TOP_PAGER" => "N",
														"DISPLAY_BOTTOM_PAGER" => "N",
														"PAGER_TITLE" => "",
														"PAGER_SHOW_ALWAYS" => "N",
														"PAGER_DESC_NUMBERING" => "N",
														"PAGER_DESC_NUMBERING_CACHE_TIME" => "3600000",
														"PAGER_SHOW_ALL" => "N",
														"AJAX_OPTION_ADDITIONAL" => "",
														"SHOW_DETAIL_LINK" => "N",
														"SET_BROWSER_TITLE" => "N",
														"SET_META_KEYWORDS" => "N",
														"SET_META_DESCRIPTION" => "N",
														"COMPONENT_TEMPLATE" => "banners",
														"SET_LAST_MODIFIED" => "N",
														"COMPOSITE_FRAME_MODE" => "A",
														"COMPOSITE_FRAME_TYPE" => "AUTO",
														"PAGER_BASE_LINK_ENABLE" => "N",
														"SHOW_404" => "N",
														"MESSAGE_404" => ""
													),
													false, array('ACTIVE_COMPONENT' => 'Y', 'HIDE_ICONS' => 'Y')
												);
												?>
											<?elseif($bRightBrand && $arItem['UF_MENU_BRANDS']):?>
												<div class="brands-wrapper">
													<?foreach ($arItem['UF_MENU_BRANDS'] as $brand):?>
														<div class="brand-wrapper">
															<?if($brand['DETAIL_PAGE_URL']):?>
																<a href="<?=$brand['DETAIL_PAGE_URL']?>">
															<?endif;?>
																<img src="<?=CFile::GetPath($brand['PREVIEW_PICTURE'])?>" alt="<?=$brand['NAME']?>" title="<?=$brand['NAME']?>" />
															<?if($brand['DETAIL_PAGE_URL']):?>
																</a>
															<?endif;?>
														</div>
													<?endforeach;?>
												</div>
											<?endif;?>
										</div>
									</div>
								<?endif;?>
							</div>
						</div>
					<?}?>
				</li>
			<?}?>
		</ul>
	</div>
<?}?>
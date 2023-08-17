<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?$this->setFrameMode(true);?>
<?
global $arTheme;
$iVisibleItemsMenu = ($arTheme['MAX_VISIBLE_ITEMS_MENU']['VALUE'] ? $arTheme['MAX_VISIBLE_ITEMS_MENU']['VALUE'] : 10);
?>
<?if($arResult):?>
<?
$MENU_TYPE = $arTheme['MEGA_MENU_TYPE']['VALUE'];
$bRightSide = $arTheme['SHOW_RIGHT_SIDE']['VALUE'] == 'Y';
$RightContent = $arTheme['SHOW_RIGHT_SIDE']['DEPENDENT_PARAMS']['RIGHT_CONTENT']['VALUE'];
$bRightBanner = $bRightSide && $RightContent == 'BANNER';
$bRightBrand = $bRightSide && $RightContent == 'BRANDS';
?>
	<div class="table-menu <?=$bRightSide ? 'with_right' : ''?>">
		<table>
			<tr>
				<?foreach($arResult as $arItem):?>
					<?$bShowChilds = $arParams["MAX_LEVEL"] > 1;
					$bWideMenu = $arItem["PARAMS"]['FROM_IBLOCK'];?>
					<td class="menu-item unvisible <?=($arItem["CHILD"] ? "dropdown" : "")?> <?=($bWideMenu ? 'wide_menu' : '');?> <?=(isset($arItem["PARAMS"]["CLASS"]) ? $arItem["PARAMS"]["CLASS"] : "");?>  <?=($arItem["SELECTED"] ? "active" : "")?>">
						<div class="wrap">
							<a class="<?=($arItem["CHILD"] && $bShowChilds ? "dropdown-toggle" : "")?>" href="<?=$arItem["LINK"]?>">
								<div>
									<?if(isset($arItem["PARAMS"]["ICON"]) && $arItem["PARAMS"]["ICON"]):?>
										<?=CMax::showIconSvg($arItem["PARAMS"]["ICON"], SITE_TEMPLATE_PATH.'/images/svg/'.$arItem["PARAMS"]["ICON"].'.svg', '', '');?>
									<?endif;?>
									<?=$arItem["TEXT"]?>
									<?if(isset($arItem["PARAMS"]["CLASS"]) && $arItem["PARAMS"]["CLASS"] == "catalog"):?>
										<?=CMax::showSpriteIconSvg(SITE_TEMPLATE_PATH."/images/svg/trianglearrow_sprite.svg#trianglearrow_down", "svg-inline-down ".$arItem["PARAMS"]["ICON"], ['WIDTH' => 5,'HEIGHT' => 3, 'INLINE' => 'N']);?>
									<?endif;?>
									<div class="line-wrapper"><span class="line"></span></div>
								</div>
							</a>
							<?if($arItem["CHILD"] && $bShowChilds):?>

								<?$bRightSideShow = ($arItem['PARAMS']['BANNERS'] || $arItem['PARAMS']['BRANDS']) && $bRightSide;?>

								<span class="tail"></span>
								<div class="dropdown-menu <?=$bRightSide ? 'with_right_block '.$RightContent : ''?>" >

									<div class="customScrollbar scrollblock ">

										<?if($bRightSideShow):?>

											<div class="right-side">
												<div class="right-content">
													<?if($bRightBanner && $arItem['PARAMS']['BANNERS']):?>
														<?
														if($GLOBALS['arRegionLink']) {
															$GLOBALS['rightBannersFilter'] = array_merge( $GLOBALS['arRegionLink'], array('ID' => $arItem['PARAMS']['BANNERS']) );
														} else {
															$GLOBALS['rightBannersFilter'] = array('ID' => $arItem['PARAMS']['BANNERS']);
														}
														$APPLICATION->IncludeComponent(
															"bitrix:news.list",
															"banners",
															array(
																"IBLOCK_TYPE" => "aspro_max_adv",
																"IBLOCK_ID" => CMaxCache::$arIBlocks[SITE_ID]["aspro_max_adv"]["aspro_max_banners_inner"][0],
																"PAGE"		=> $APPLICATION->GetCurPage(),
																'MENU_BANNER' => true,
																//'MENU_LINK' => $arItem['link'],
																"NEWS_COUNT" => "100",
																"SHOW_ALL_ELEMENTS" => 'Y',
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
													<?elseif($bRightBrand && $arItem['PARAMS']['BRANDS']):?>
														<div class="brands-wrapper">
															<?foreach ($arItem['PARAMS']['BRANDS'] as $brand):?>
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
									
										<ul class="menu-wrapper <?='menu-type-'.$MENU_TYPE?>">
											<?foreach($arItem["CHILD"] as $arSubItem):?>

												<?if($MENU_TYPE == 2):?>

													<?
													$bHasPicture = ( (isset($arSubItem['PARAMS']['PICTURE']) && $arSubItem['PARAMS']['PICTURE'] || (isset($arSubItem['PARAMS']['SECTION_ICON'])) ) && $arTheme['SHOW_CATALOG_SECTIONS_ICONS']['VALUE'] == 'Y');
													$bIcon = (isset($arSubItem['PARAMS']['SECTION_ICON'])) && $arSubItem['PARAMS']['SECTION_ICON'];?>
													<li class="<?=($arSubItem["SELECTED"] ? "active" : "")?> <?=$bIcon ? 'icon' : ''?> <?=($bHasPicture ? "has_img" : "")?>">
														<?if($bHasPicture && $bWideMenu):

															$arSubItemImg = ( (isset($arSubItem['PARAMS']['SECTION_ICON'])) ? $arSubItem['PARAMS']['SECTION_ICON'] : $arSubItem['PARAMS']['PICTURE'] );
															$arImg = CFile::ResizeImageGet($arSubItemImg, array('width' => 80, 'height' => 80), BX_RESIZE_IMAGE_PROPORTIONAL);
															if(is_array($arImg)):?>
																<div class="menu_img <?=$bIcon ? 'icon' : ''?>">
																	<a href="<?=$arSubItem["LINK"]?>" class="noborder colored_theme_svg">
																		<?if(strpos($arImg["src"], ".svg") !== false && \CMax::GetFrontParametrValue('COLORED_CATALOG_ICON') === 'Y'):?>
																			<?=\Aspro\Functions\CAsproMax::showSVG([
																				'PATH' => $arImg["src"]
																			]);?>
																		<?else:?>
																			<img class="lazy" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($arImg["src"]);?>" data-src="<?=$arImg["src"]?>" alt="<?=$arSubItem["TEXT"]?>" title="<?=$arSubItem["TEXT"]?>" />
																		<?endif;?>
																	</a>
																</div>
															<?endif;?>
														<?endif;?>
														<a href="<?=$arSubItem["LINK"]?>" title="<?=$arSubItem["TEXT"]?>"><span class="name option-font-bold"><?=$arSubItem["TEXT"]?></span></a>
													</li>

												<?else: // type 1?>

													<?$bShowChilds = $arParams["MAX_LEVEL"] > 2;?>
													<?
													$bHasPicture = ( (isset($arSubItem['PARAMS']['PICTURE']) && $arSubItem['PARAMS']['PICTURE'] || (isset($arSubItem['PARAMS']['SECTION_ICON'])) ) && $arTheme['SHOW_CATALOG_SECTIONS_ICONS']['VALUE'] == 'Y');
													$bIcon = (isset($arSubItem['PARAMS']['SECTION_ICON'])) && $arSubItem['PARAMS']['SECTION_ICON'];
													?>
													<li class="<?=($arSubItem["CHILD"] && $bShowChilds ? "dropdown-submenu" : "")?> <?=$bIcon ? 'icon' : ''?> <?=($arSubItem["SELECTED"] ? "active" : "")?> <?=($bHasPicture ? "has_img" : "")?>">
														<?if($bHasPicture && $bWideMenu):
															
															$arSubItemImg = ( (isset($arSubItem['PARAMS']['SECTION_ICON'])) ? $arSubItem['PARAMS']['SECTION_ICON'] : $arSubItem['PARAMS']['PICTURE'] );
															$arImg = CFile::ResizeImageGet($arSubItemImg, array('width' => 60, 'height' => 60), BX_RESIZE_IMAGE_PROPORTIONAL_ALT);
															if(is_array($arImg)):?>
																<div class="menu_img <?=$bIcon ? 'icon' : ''?> colored_theme_svg">
																	<?if(strpos($arImg["src"], ".svg") !== false && \CMax::GetFrontParametrValue('COLORED_CATALOG_ICON') === 'Y'):?>
																		<?=\Aspro\Functions\CAsproMax::showSVG([
																			'PATH' => $arImg["src"]
																		]);?>
																	<?else:?>
																		<img class="lazy" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($arImg["src"]);?>" data-src="<?=$arImg["src"]?>" alt="<?=$arSubItem["TEXT"]?>" title="<?=$arSubItem["TEXT"]?>" />
																	<?endif;?>
																</div>
															<?endif;?>
														<?endif;?>
														<a href="<?=$arSubItem["LINK"]?>" title="<?=$arSubItem["TEXT"]?>"><span class="name"><?=$arSubItem["TEXT"]?></span><?=($arSubItem["CHILD"] && $bShowChilds ? '<span class="arrow"><i></i></span>' : '')?></a>
														<?if($arSubItem["CHILD"] && $bShowChilds):?>
															<?$iCountChilds = count($arSubItem["CHILD"]);?>
															<ul class="dropdown-menu toggle_menu">
																<?foreach($arSubItem["CHILD"] as $key => $arSubSubItem):?>
																	<?$bShowChilds = $arParams["MAX_LEVEL"] > 3;?>
																	<li class="<?=(++$key > $iVisibleItemsMenu ? 'collapsed' : '');?> <?=($arSubSubItem["CHILD"] && $bShowChilds ? "dropdown-submenu" : "")?> <?=($arSubSubItem["SELECTED"] ? "active" : "")?>">
																		<a href="<?=$arSubSubItem["LINK"]?>" title="<?=$arSubSubItem["TEXT"]?>"><span class="name"><?=$arSubSubItem["TEXT"]?></span></a>
																		<?if($arSubSubItem["CHILD"] && $bShowChilds):?>
																			<ul class="dropdown-menu">
																				<?foreach($arSubSubItem["CHILD"] as $arSubSubSubItem):?>
																					<li class="<?=($arSubSubSubItem["SELECTED"] ? "active" : "")?>">
																						<a href="<?=$arSubSubSubItem["LINK"]?>" title="<?=$arSubSubSubItem["TEXT"]?>"><span class="name"><?=$arSubSubSubItem["TEXT"]?></span></a>
																					</li>
																				<?endforeach;?>
																			</ul>

																		<?endif;?>
																	</li>
																<?endforeach;?>
																<?if($iCountChilds > $iVisibleItemsMenu && $bWideMenu):?>
																	<li><span class="colored more_items with_dropdown"><?=\Bitrix\Main\Localization\Loc::getMessage("S_MORE_ITEMS");?></span></li>
																<?endif;?>
															</ul>
														<?endif;?>
													</li>

												<?endif;?>

											<?endforeach;?>
										</ul>

									</div>
								</div>
							<?endif;?>
						</div>
					</td>
				<?endforeach;?>

				<td class="menu-item dropdown js-dropdown nosave unvisible">
					<div class="wrap">
						<a class="dropdown-toggle more-items" href="#">
							<span><?=\Bitrix\Main\Localization\Loc::getMessage("S_MORE_ITEMS");?></span>
						</a>
						<span class="tail"></span>
						<ul class="dropdown-menu"></ul>
					</div>
				</td>

			</tr>
		</table>
	</div>
	<script data-skip-moving="true">
		CheckTopMenuDotted();
	</script>
<?endif;?>
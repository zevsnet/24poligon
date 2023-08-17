<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?$this->setFrameMode(true);?>
<?
global $arTheme;
$iVisibleItemsMenu = ($arTheme['MAX_VISIBLE_ITEMS_MENU']['VALUE'] ? $arTheme['MAX_VISIBLE_ITEMS_MENU']['VALUE'] : 10);
$MENU_TYPE = $arTheme['MEGA_MENU_TYPE']['VALUE'];
$bManyItemsMenu = ($MENU_TYPE == '4');
?>
<?if($arResult):?>
	<?if (!function_exists('showSubItemss')) {
		function showSubItemss($arParams = [
			'HAS_PICTURE' => false,
			'HAS_ICON' => false,
			'WIDE_MENU' => false,
			'SHOW_CHILDS' => false,
			'VISIBLE_ITEMS_MENU' => 0,
			'MAX_LEVEL' => 0,
			'ITEM' => [],
		]){?>
			<?if($arParams['HAS_PICTURE'] && $arParams['WIDE_MENU']):
				$arSubItemImg = ( (isset($arParams['ITEM']['PARAMS']['SECTION_ICON'])) ? $arParams['ITEM']['PARAMS']['SECTION_ICON'] : $arParams['ITEM']['PARAMS']['PICTURE'] );
				$arImg = CFile::ResizeImageGet($arSubItemImg, array('width' => 60, 'height' => 60), BX_RESIZE_IMAGE_PROPORTIONAL_ALT);

				if(is_array($arImg)):?>
					<div class="menu_img <?=$arParams['HAS_ICON'] ? 'icon' : ''?>">
						<a href="<?=$arParams['ITEM']["LINK"]?>" class="noborder img_link colored_theme_svg">
							<?if(strpos($arImg["src"], ".svg") !== false):?>
								<?=CMax::showIconSvg("cat_icons light-ignore", $arImg["src"]);?>
							<?else:?>
								<img class="lazy" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($arImg["src"]);?>" data-src="<?=$arImg["src"]?>" alt="<?=$arParams['ITEM']["TEXT"]?>" title="<?=$arParams['ITEM']["TEXT"]?>" />
							<?endif;?>
						</a>
					</div>
				<?endif;?>
			<?endif;?>
			<?if($arParams['ITEM']["LINK"]):?>
				<a href="<?=$arParams['ITEM']["LINK"]?>" title="<?=$arParams['ITEM']["TEXT"]?>">
			<?endif;?>
			<span class="name <?=$arParams['WIDE_MENU'] ? 'option-font-bold' : ''?>"><?=$arParams['ITEM']["TEXT"]?></span><?=($arParams['ITEM']["CHILD"] && $arParams['SHOW_CHILDS'] ? CMax::showIconSvg("right light-ignore", SITE_TEMPLATE_PATH.'/images/svg/trianglearrow_right.svg', '', '') : '')?>
			<?if($arParams['ITEM']["LINK"]):?>
				</a>
			<?endif;?>
			<?if($arParams['ITEM']["CHILD"] && $arParams['SHOW_CHILDS']):?>
				<?$iCountChilds = count($arParams['ITEM']["CHILD"]);?>
				<ul class="dropdown-menu toggle_menu">
					<?foreach($arParams['ITEM']["CHILD"] as $key => $arSubSubItem):?>
						<?$arParams['SHOW_CHILDS'] = $arParams["MAX_LEVEL"] > 3;?>
						<li class="menu-item <?=(++$key > $arParams['VISIBLE_ITEMS_MENU'] ? 'collapsed' : '');?> <?=($arSubSubItem["CHILD"] && $arParams['SHOW_CHILDS'] ? "dropdown-submenu" : "")?> <?=($arSubSubItem["SELECTED"] ? "active" : "")?>">
							<a href="<?=$arSubSubItem["LINK"]?>" title="<?=$arSubSubItem["TEXT"]?>"><span class="name"><?=$arSubSubItem["TEXT"]?></span></a>
							<?if($arSubSubItem["CHILD"] && $arParams['SHOW_CHILDS']):?>
								<ul class="dropdown-menu">
									<?foreach($arSubSubItem["CHILD"] as $arSubSubSubItem):?>
										<li class="menu-item <?=($arSubSubSubItem["SELECTED"] ? "active" : "")?>">
											<a href="<?=$arSubSubSubItem["LINK"]?>" title="<?=$arSubSubSubItem["TEXT"]?>"><span class="name"><?=$arSubSubSubItem["TEXT"]?></span></a>
										</li>
									<?endforeach;?>
								</ul>

							<?endif;?>
						</li>
					<?endforeach;?>
					<?if($iCountChilds > $arParams['VISIBLE_ITEMS_MENU'] && $arParams['WIDE_MENU']):?>
						<li><span class="colored_theme_hover_text more_items with_dropdown"><?=\Bitrix\Main\Localization\Loc::getMessage("S_MORE_ITEMS").' '.($iCountChilds-$arParams['VISIBLE_ITEMS_MENU']);?></span></li>
					<?endif;?>
				</ul>
			<?endif;?>
		<?}?>
	<?}?>
	<div class="table-menu">
		<table>
			<tr>
				<?foreach($arResult as $arItem):?>
					<?$bShowChilds = $arParams["MAX_LEVEL"] > 1;

					if(!$arItem['TEXT']) continue;
					
					$bWideMenu = (isset($arItem['PARAMS']['CLASS']) && strpos($arItem['PARAMS']['CLASS'], 'wide_menu') !== false);
					$arItem['bManyItemsMenu'] = $bManyItemsMenu;
					if(!$bWideMenu) {
						$arItem['bManyItemsMenu'] = false;
					}?>

					<td class="menu-item unvisible <?=($arItem["CHILD"] ? "dropdown" : "")?> <?=(isset($arItem["PARAMS"]["CLASS"]) ? $arItem["PARAMS"]["CLASS"] : "");?>  <?=($arItem["SELECTED"] ? "active" : "")?>">
						<div class="wrap">
							<a class="<?=($arItem["CHILD"] && $bShowChilds ? "dropdown-toggle" : "")?>" href="<?=$arItem["LINK"]?>">
								<div>
									<?if(isset($arItem["PARAMS"]["ICON"]) && $arItem["PARAMS"]["ICON"]):?>
										<?=CMax::showIconSvg($arItem["PARAMS"]["ICON"], SITE_TEMPLATE_PATH.'/images/svg/'.$arItem["PARAMS"]["ICON"].'.svg', '', '');?>
									<?endif;?>
									<?=$arItem["TEXT"]?>
									<?if(isset($arItem["PARAMS"]["CLASS"]) && strpos($arItem["PARAMS"]["CLASS"], "catalog") !== false):?>
										<?=CMax::showIconSvg("down", SITE_TEMPLATE_PATH.'/images/svg/trianglearrow_down.svg', '', '');?>
									<?endif;?>
								</div>
							</a>

							<?if($arItem["CHILD"] && $bShowChilds):?>
								<?$bRightSide = ($arItem["BANNERS"] || $arResult['BRANDS']) && $bWideMenu;?>
								<span class="tail"></span>
								<div class="dropdown-menu <?=$bRightSide ? 'with_right_block' : ''?> <?=$arItem['bManyItemsMenu'] ? 'long-menu-items' : ''?> <?=$arTheme['SHOW_RIGHT_SIDE']['DEPENDENT_PARAMS']['RIGHT_CONTENT']['VALUE']?>">
									<?if($arItem['bManyItemsMenu']):?>
										<div class="menu-navigation">
											<div class="menu-navigation__sections-wrapper">
												<div class="customScrollbar">
													<div class="menu-navigation__sections">
														<?foreach($arItem["CHILD"] as $arChild):?>
															<div class="menu-navigation__sections-item<?=($arChild['SELECTED'] ? " active" : "");?>">
																<?$bShowImg = ((isset($arChild['PARAMS']['PICTURE']) && $arChild['PARAMS']['PICTURE'] || (isset($arChild['PARAMS']['SECTION_ICON']))) && $arTheme['LEFT_BLOCK_CATALOG_ICONS']['VALUE'] == 'Y');
																$bIcon = (isset($arChild['PARAMS']['SECTION_ICON'])) && $arChild['PARAMS']['SECTION_ICON'];?>

																<a
																	href="<?=$arChild['LINK']?>"
																	class="menu-navigation__sections-item-link font_xs <?=($arChild["SELECTED"] ? "colored_theme_text" : "dark_link")?> <?=($bShowImg ? " menu-navigation__sections-item-link--image" : "");?><?=($arChild['CHILD'] ? " menu-navigation__sections-item-dropdown" : "");?>"
																>
																	<?if($arChild['CHILD']):?>
																		<?=CMax::showIconSvg("right", SITE_TEMPLATE_PATH.'/images/svg/trianglearrow_right.svg', '', '');?>
																	<?endif;?>
																	<?if($bShowImg){?>
																		<span class="image colored_theme_svg ">
																			<?$imageID = ((isset($arChild['PARAMS']['SECTION_ICON'])) ? $arChild['PARAMS']['SECTION_ICON'] : $arChild['PARAMS']['PICTURE']);?>
																			<?$image = CFile::GetPath($imageID);?>
																			<?if(strpos($image, ".svg") !== false):?>
																				<?=CMax::showIconSvg("cat_icons light-ignore", $image);?>
																			<?else:?>
																				<img class="lazy" data-src="<?=$image;?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($image);?>" alt="<?=$arChild["NAME"];?>" title="<?=$arChild["NAME"]?>" />
																			<?endif;?>
																		</span>
																	<?}?>
																	<span class="name"><?=$arChild['TEXT'];?></span>
																</a>
															</div>
														<?endforeach;?>
													</div>
												</div>
											</div>
											<div class="menu-navigation__content">
									<?endif;?>
									<div class="customScrollbar">
										<?if($bRightSide && !$arItem['bManyItemsMenu']):?>
											<div class="right-side">
												<div class="right-content">
													<?if($arItem["BANNERS"]):?>
														<?
														if($GLOBALS['arRegionLink']) {
															$GLOBALS['rightBannersFilter'] = array_merge( $GLOBALS['arRegionLink'], array('ID' => $arItem["BANNERS"]) );
														} else {
															$GLOBALS['rightBannersFilter'] = array('ID' => $arItem["BANNERS"]);
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
													<?elseif($arResult['BRANDS']):?>
														<div class="brands-wrapper">
															<?foreach ($arResult['BRANDS'] as $brand):?>
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

										<ul class="menu-wrapper <?='menu-type-'.$MENU_TYPE?>" >
											<?foreach($arItem["CHILD"] as $arSubItem):?>
												<?if($MENU_TYPE == 2 && $bWideMenu):?>
													<?
													$bHasPicture = ( (isset($arSubItem['PARAMS']['PICTURE']) && $arSubItem['PARAMS']['PICTURE'] || (isset($arSubItem['PARAMS']['SECTION_ICON'])) ) && $arTheme['SHOW_CATALOG_SECTIONS_ICONS']['VALUE'] == 'Y');
													$bIcon = (isset($arSubItem['PARAMS']['SECTION_ICON'])) && $arSubItem['PARAMS']['SECTION_ICON'];
													?>
													<li class="<?=($arSubItem["SELECTED"] ? "active" : "")?> <?=$bIcon ? 'icon' : ''?> <?=($bHasPicture ? "has_img" : "")?>">
														<?if($bHasPicture && $bWideMenu):
															$arSubItemImg = ( (isset($arSubItem['PARAMS']['SECTION_ICON'])) ? $arSubItem['PARAMS']['SECTION_ICON'] : $arSubItem['PARAMS']['PICTURE'] );
															$arImg = CFile::ResizeImageGet($arSubItemImg, array('width' => 80, 'height' => 80), BX_RESIZE_PROPORTIONAL);
															if(is_array($arImg)):?>
																<div class="menu_img <?=$bIcon ? 'icon' : ''?>">
																	<a href="<?=$arSubItem["LINK"]?>" class="noborder colored_theme_svg">
																		<?if(strpos($arImg["src"], ".svg") !== false):?>
																			<?=CMax::showIconSvg("cat_icons light-ignore", $arImg["src"]);?>
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
													<?
													$bShowChilds = $arParams["MAX_LEVEL"] > 2;?>
													<?
													$bHasPicture = ( (isset($arSubItem['PARAMS']['PICTURE']) && $arSubItem['PARAMS']['PICTURE'] || (isset($arSubItem['PARAMS']['SECTION_ICON'])) ) && $arTheme['SHOW_CATALOG_SECTIONS_ICONS']['VALUE'] == 'Y');
													$bIcon = (isset($arSubItem['PARAMS']['SECTION_ICON'])) && $arSubItem['PARAMS']['SECTION_ICON'];
													?>
													<li class="<?=($arSubItem["CHILD"] && $bShowChilds ? "dropdown-submenu" : "")?> <?=$bIcon ? 'icon' : ''?> <?=($arSubItem["SELECTED"] ? "active" : "")?> <?=($bHasPicture ? "has_img" : "")?> parent-items">
														<?if($arItem['bManyItemsMenu']):?>
															<div class="flexbox flex-reverse">
																<?$bRightBanners = ($arSubItem["PARAMS"]["BANNERS"] && $arTheme['SHOW_RIGHT_SIDE']['DEPENDENT_PARAMS']['RIGHT_CONTENT']['VALUE'] == 'BANNER')?>
																<?$bRightBrands = ($arSubItem["PARAMS"]["BRANDS"] && $arSubItem["BRANDS"] && $arTheme['SHOW_RIGHT_SIDE']['DEPENDENT_PARAMS']['RIGHT_CONTENT']['VALUE'] == 'BRANDS')?>
																<?$bRightSide = (($bRightBanners || $bRightBrands) && $arTheme['SHOW_RIGHT_SIDE']['VALUE'] == 'Y');?>
																<?if($bRightSide):?>
																	<div class="right-side">
																		<div class="right-content">
																			<?if($bRightBanners):?>
																				<?
																				if($GLOBALS['arRegionLink']) {
																					$GLOBALS['rightBannersFilter'] = array_merge( $GLOBALS['arRegionLink'], array('ID' => $arSubItem["PARAMS"]["BANNERS"]) );
																				} else {
																					$GLOBALS['rightBannersFilter'] = array('ID' => $arSubItem["PARAMS"]["BANNERS"]);
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
																			<?elseif($bRightBrands):?>
																				<div class="brands-wrapper">
																					<?foreach ($arSubItem["BRANDS"] as $brand):?>
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
																<div class="subitems-wrapper">
																	<ul class="menu-wrapper" >
																		<?foreach($arSubItem["CHILD"] as $arSubItem2):?>
																			<?
																			$bHasPicture = ( (isset($arSubItem2['PARAMS']['PICTURE']) && $arSubItem2['PARAMS']['PICTURE'] || (isset($arSubItem2['PARAMS']['SECTION_ICON'])) ) && $arTheme['SHOW_CATALOG_SECTIONS_ICONS']['VALUE'] == 'Y');
																			$bIcon = (isset($arSubItem2['PARAMS']['SECTION_ICON'])) && $arSubItem2['PARAMS']['SECTION_ICON'];
																			?>
																			<li class="<?=($arSubItem2["CHILD"] && $bShowChilds ? "dropdown-submenu" : "")?> <?=$bIcon ? 'icon' : ''?> <?=($arSubItem2["SELECTED"] ? "active" : "")?> <?=($bHasPicture ? "has_img" : "")?>">
																				<?=showSubItemss([
																					'HAS_PICTURE' => $bHasPicture,
																					'HAS_ICON' => $bIcon,
																					'WIDE_MENU' => true,
																					'SHOW_CHILDS' => $bShowChilds,
																					'VISIBLE_ITEMS_MENU' => $iVisibleItemsMenu,
																					'ITEM' => $arSubItem2,
																					'MAX_LEVEL' => $arParams["MAX_LEVEL"]
																				]);?>
																			</li>
																		<?endforeach;?>
																	</ul>
																</div>
															</div>
														<?else:?>
															<?=showSubItemss([
																'HAS_PICTURE' => $bHasPicture,
																'HAS_ICON' => $bIcon,
																'WIDE_MENU' => $bWideMenu,
																'SHOW_CHILDS' => $bShowChilds,
																'VISIBLE_ITEMS_MENU' => $iVisibleItemsMenu,
																'ITEM' => $arSubItem,
																'MAX_LEVEL' => $arParams["MAX_LEVEL"]
															]);?>
														<?endif;?>
													</li>
												<?endif;?>
											<?endforeach;?>
										</ul>
									</div>
									<?if($arItem['bManyItemsMenu']):?>
											</div>
										</div>
									<?endif;?>
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
		CheckTopMenuPadding();
		CheckTopMenuOncePadding();
		CheckTopMenuDotted();
	</script>
<?endif;?>
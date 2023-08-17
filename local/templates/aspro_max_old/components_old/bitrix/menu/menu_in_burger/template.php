<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?$this->setFrameMode(true);?>
<?
global $arTheme;
$iVisibleItemsMenu = ($arTheme['MAX_VISIBLE_ITEMS_MENU']['VALUE'] ? $arTheme['MAX_VISIBLE_ITEMS_MENU']['VALUE'] : 10);
?>
<?if($arResult):?>

<?$bShowChilds = $arParams["MAX_LEVEL"] > 1;?>

	<div class="burger_menu_wrapper">

		<?if($arResult['EXPANDED']):?>

			<div class="top_link_wrapper">
				
				<div class="menu-item <?=($arResult['EXPANDED']["CHILD"] ? "dropdown" : "")?> <?=(isset($arResult['EXPANDED']["PARAMS"]["CLASS"]) ? $arResult['EXPANDED']["PARAMS"]["CLASS"] : "");?>  <?=($arResult['EXPANDED']["SELECTED"] ? "active" : "")?>">
					
					<div class="wrap">
						<a class="<?=($arResult['EXPANDED']["CHILD"] && $bShowChilds ? "dropdown-toggle" : "")?>" href="<?=$arResult['EXPANDED']["LINK"]?>">
							<div class="link-title color-theme-hover">
								<?if(isset($arResult['EXPANDED']["PARAMS"]["ICON"]) && $arResult['EXPANDED']["PARAMS"]["ICON"]):?>
									<?=CMax::showIconSvg($arResult['EXPANDED']["PARAMS"]["ICON"], SITE_TEMPLATE_PATH.'/images/svg/'.$arResult['EXPANDED']["PARAMS"]["ICON"].'.svg', '', '');?>
								<?endif;?>
								<?=$arResult['EXPANDED']["TEXT"]?>
							</div>
						</a>

						<?if($arResult['EXPANDED']["CHILD"] && $bShowChilds):?>
							<span class="tail"></span>
							<div class="burger-dropdown-menu row">

								<div class="menu-wrapper" >

									<?foreach($arResult['EXPANDED']["CHILD"] as $arSubItem):?>

										<?$bShowChilds = $arParams["MAX_LEVEL"] > 2;?>
										<?$bHasPicture = (isset($arSubItem['PARAMS']['PICTURE']) && $arSubItem['PARAMS']['PICTURE'] && $arTheme['SHOW_CATALOG_SECTIONS_ICONS']['VALUE'] == 'Y');?>
										<div class="col-md-4 <?=($arSubItem["CHILD"] && $bShowChilds ? "dropdown-submenu" : "")?> <?=($arSubItem["SELECTED"] ? "active" : "")?> <?=($bHasPicture ? "has_img" : "")?>">
											<?if($bHasPicture && $bWideMenu):
												$arImg = CFile::ResizeImageGet($arSubItem['PARAMS']['PICTURE'], array('width' => 60, 'height' => 60), BX_RESIZE_PROPORTIONAL_ALT);
												if(is_array($arImg)):?>
													<div class="menu_img">
														<a href="<?=$arSubItem["LINK"]?>" class="noborder img_link">
															<img class="lazy" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($arImg["src"]);?>" data-src="<?=$arImg["src"]?>" alt="<?=$arSubItem["TEXT"]?>" title="<?=$arSubItem["TEXT"]?>" />
														</a>
													</div>
												<?endif;?>
											<?endif;?>
											<a href="<?=$arSubItem["LINK"]?>" class="color-theme-hover" title="<?=$arSubItem["TEXT"]?>">
												<span class="name option-font-bold"><?=$arSubItem["TEXT"]?></span>
											</a>
											<?if($arSubItem["CHILD"] && $bShowChilds):?>

												<?$iCountChilds = count($arSubItem["CHILD"]);?>
												<div class="burger-dropdown-menu toggle_menu">
													<?foreach($arSubItem["CHILD"] as $key => $arSubSubItem):?>
														<?$bShowChilds = $arParams["MAX_LEVEL"] > 3;?>
														<div class="menu-item <?=(++$key > $iVisibleItemsMenu ? 'collapsed' : '');?> <?=($arSubSubItem["CHILD"] && $bShowChilds ? "dropdown-submenu" : "")?> <?=($arSubSubItem["SELECTED"] ? "active" : "")?>">
															<a href="<?=$arSubSubItem["LINK"]?>" title="<?=$arSubSubItem["TEXT"]?>">
																<span class="name color-theme-hover"><?=$arSubSubItem["TEXT"]?></span>
															</a>
															<?if($arSubSubItem["CHILD"] && $bShowChilds):?>
																<div class="burger-dropdown-menu with_padding">
																	<?foreach($arSubSubItem["CHILD"] as $arSubSubSubItem):?>
																		<div class="menu-item <?=($arSubSubSubItem["SELECTED"] ? "active" : "")?>">
																			<a href="<?=$arSubSubSubItem["LINK"]?>" title="<?=$arSubSubSubItem["TEXT"]?>">
																				<span class="name color-theme-hover"><?=$arSubSubSubItem["TEXT"]?></span>
																			</a>
																		</div>
																	<?endforeach;?>
																</div>
																
															<?endif;?>
														</div>
													<?endforeach;?>
												</div>
											<?endif;?>
										</div>

									<?endforeach;?>
								</div>

							</div>

						<?endif;?>
					</div>

				</div>

			</div>
		
			<?unset($arResult['EXPANDED']);?>
		<?endif;?>

		<div class="bottom_links_wrapper row">
			<?foreach($arResult as $arItem):?>					
				<?$bShowChilds = $arParams["MAX_LEVEL"] > 1;?>
				<div class="menu-item col-md-4 unvisible <?=($arItem["CHILD"] ? "dropdown" : "")?> <?=(isset($arItem["PARAMS"]["CLASS"]) ? $arItem["PARAMS"]["CLASS"] : "");?>  <?=($arItem["SELECTED"] ? "active" : "")?>">
					<div class="wrap">
						<a class="<?=($arItem["CHILD"] && $bShowChilds ? "dropdown-toggle" : "")?>" href="<?=$arItem["LINK"]?>">
							<div class="link-title color-theme-hover">
								<?if(isset($arItem["PARAMS"]["ICON"]) && $arItem["PARAMS"]["ICON"]):?>
									<?=CMax::showIconSvg($arItem["PARAMS"]["ICON"], SITE_TEMPLATE_PATH.'/images/svg/'.$arItem["PARAMS"]["ICON"].'.svg', '', '');?>
								<?endif;?>
								<?=$arItem["TEXT"]?>
							</div>
						</a>

						<?if($arItem["CHILD"] && $bShowChilds):?>
							<span class="tail"></span>
							<div class="burger-dropdown-menu">

								<div class="menu-wrapper" >

									<?foreach($arItem["CHILD"] as $arSubItem):?>

										<?$bShowChilds = $arParams["MAX_LEVEL"] > 2;?>
										<?$bHasPicture = (isset($arSubItem['PARAMS']['PICTURE']) && $arSubItem['PARAMS']['PICTURE'] && $arTheme['SHOW_CATALOG_SECTIONS_ICONS']['VALUE'] == 'Y');?>
										<div class="<?=($arSubItem["CHILD"] && $bShowChilds ? "dropdown-submenu" : "")?> <?=($arSubItem["SELECTED"] ? "active" : "")?> <?=($bHasPicture ? "has_img" : "")?>">
											<?if($bHasPicture && $bWideMenu):
												$arImg = CFile::ResizeImageGet($arSubItem['PARAMS']['PICTURE'], array('width' => 60, 'height' => 60), BX_RESIZE_PROPORTIONAL_ALT);
												if(is_array($arImg)):?>
													<div class="menu_img">
														<a href="<?=$arSubItem["LINK"]?>" class="noborder img_link">
															<img class="lazy" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($arImg["src"]);?>" data-src="<?=$arImg["src"]?>" alt="<?=$arSubItem["TEXT"]?>" title="<?=$arSubItem["TEXT"]?>" />
														</a>
													</div>
												<?endif;?>
											<?endif;?>
											<a href="<?=$arSubItem["LINK"]?>" class="color-theme-hover" title="<?=$arSubItem["TEXT"]?>">
												<span class="name option-font-bold"><?=$arSubItem["TEXT"]?></span>
											</a>
											<?if($arSubItem["CHILD"] && $bShowChilds):?>

												<?$iCountChilds = count($arSubItem["CHILD"]);?>
												<div class="burger-dropdown-menu with_padding toggle_menu">
													<?foreach($arSubItem["CHILD"] as $key => $arSubSubItem):?>
														<?$bShowChilds = $arParams["MAX_LEVEL"] > 3;?>
														<div class="menu-item <?=(++$key > $iVisibleItemsMenu ? 'collapsed' : '');?> <?=($arSubSubItem["CHILD"] && $bShowChilds ? "dropdown-submenu" : "")?> <?=($arSubSubItem["SELECTED"] ? "active" : "")?>">
															<a href="<?=$arSubSubItem["LINK"]?>" title="<?=$arSubSubItem["TEXT"]?>">
																<span class="name color-theme-hover"><?=$arSubSubItem["TEXT"]?></span>
															</a>
															<?if($arSubSubItem["CHILD"] && $bShowChilds):?>
																<div class="burger-dropdown-menu with_padding">
																	<?foreach($arSubSubItem["CHILD"] as $arSubSubSubItem):?>
																		<div class="menu-item <?=($arSubSubSubItem["SELECTED"] ? "active" : "")?>">
																			<a href="<?=$arSubSubSubItem["LINK"]?>" title="<?=$arSubSubSubItem["TEXT"]?>">
																				<span class="name color-theme-hover"><?=$arSubSubSubItem["TEXT"]?></span>
																			</a>
																		</div>
																	<?endforeach;?>
																</div>
																
															<?endif;?>
														</div>
													<?endforeach;?>
												</div>
											<?endif;?>
										</div>

									<?endforeach;?>
								</div>

							</div>

						<?endif;?>
					</div>
				</div>
			<?endforeach;?>
		</div>


	</div>
<?endif;?>
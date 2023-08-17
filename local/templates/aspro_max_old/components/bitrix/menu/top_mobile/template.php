<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?$this->setFrameMode(true);?>
<?if($arResult):?>
	<?
	$bShowImagesInMobile = CMax::GetFrontParametrValue("SHOW_ICONS_IN_MOBILE_MENU") == "Y";
	?>
	<div class="menu top top-mobile-menu">
		<ul class="top">
			<?foreach($arResult as $arItem):?>
				<?$bShowChilds = $arParams['MAX_LEVEL'] > 1;
				$bShowImages = $bShowImagesInMobile && isset($arItem['PARAMS']['CLASS']) && (strpos($arItem['PARAMS']['CLASS'], 'no_icons_in_mobile') === false) && (strpos($arItem['PARAMS']['CLASS'], 'catalog') !== false || strpos($arItem['PARAMS']['CLASS'], 'wide_menu') !== false);
				?>
				<?$bParent = $arItem['CHILD'] && $bShowChilds;?>
				<li<?=($arItem['SELECTED'] ? ' class="selected"' : '')?>>
					<a class="dark-color<?=($bParent ? ' parent' : '')?>" href="<?=$arItem["LINK"]?>" title="<?=$arItem["TEXT"]?>">
						<span><?=$arItem['TEXT']?></span>
						<?if($bParent):?>
							<span class="arrow">
								<?=CMax::showSpriteIconSvg(SITE_TEMPLATE_PATH."/images/svg/trianglearrow_sprite.svg#trianglearrow_right", "triangle", ['WIDTH' => 3,'HEIGHT' => 5]);?>
							</span>
						<?endif;?>
					</a>
					<?if($bParent):?>
						<ul class="dropdown">
							<li class="menu_back"><a href="" class="dark-color" rel="nofollow"><?=CMax::showIconSvg('back_arrow', SITE_TEMPLATE_PATH.'/images/svg/return_mm.svg')?><?=GetMessage('MAX_T_MENU_BACK')?></a></li>
							<li class="menu_title"><a href="<?=$arItem['LINK'];?>"><?=$arItem['TEXT']?></a></li>
							<?foreach($arItem['CHILD'] as $arSubItem):?>
								<?$bShowChilds = $arParams['MAX_LEVEL'] > 2;?>
								<?$bParent = $arSubItem['CHILD'] && $bShowChilds;?>
								<li<?=($arSubItem['SELECTED'] ? ' class="selected"' : '')?>>
									<a class="dark-color<?=($bParent ? ' parent' : '')?> top-mobile-menu__link" href="<?=$arSubItem["LINK"]?>" title="<?=$arSubItem["TEXT"]?>">
										<?if($bShowImages):?>
											<?
											$arSubItemImg = ( (isset($arSubItem['PARAMS']['SECTION_ICON'])) ? $arSubItem['PARAMS']['SECTION_ICON'] : $arSubItem['PARAMS']['PICTURE'] );
											$arImg = CFile::ResizeImageGet($arSubItemImg, array('width' => 50, 'height' => 50), BX_RESIZE_IMAGE_PROPORTIONAL_ALT);
											?>
											<?if(is_array($arImg)):?>
												<span class="image top-mobile-menu__image colored_theme_svg">
													<?if(strpos($arImg["src"], ".svg") !== false && \CMax::GetFrontParametrValue('COLORED_CATALOG_ICON') === 'Y'):?>
														<?=\Aspro\Functions\CAsproMax::showSVG([
															'PATH' => $arImg["src"]
														]);?>
													<?else:?>
														<img class="lazy" data-src="<?=$arImg["src"];?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($arImg["src"]);?>" alt="<?=$arSubItem["TEXT"];?>" />
													<?endif;?>
												</span>
											<?endif;?>
										<?endif;?>
										<span class="top-mobile-menu__title"><?=$arSubItem['TEXT']?></span>
										<?if($bParent):?>
											<span class="arrow"><?=CMax::showSpriteIconSvg(SITE_TEMPLATE_PATH."/images/svg/trianglearrow_sprite.svg#trianglearrow_right", "triangle", ['WIDTH' => 3,'HEIGHT' => 5]);?></span>
										<?endif;?>
									</a>
									<?if($bParent):?>
										<ul class="dropdown">
											<li class="menu_back"><a href="" class="dark-color" rel="nofollow"><?=CMax::showIconSvg('back_arrow', SITE_TEMPLATE_PATH.'/images/svg/return_mm.svg')?><?=GetMessage('MAX_T_MENU_BACK')?></a></li>
											<li class="menu_title"><a href="<?=$arSubItem['LINK'];?>"><?=$arSubItem['TEXT']?></a></li>
											<?foreach($arSubItem["CHILD"] as $arSubSubItem):?>
												<?$bShowChilds = $arParams['MAX_LEVEL'] > 3;?>
												<?$bParent = $arSubSubItem['CHILD'] && $bShowChilds;?>
												<li<?=($arSubSubItem['SELECTED'] ? ' class="selected"' : '')?>>
													<a class="dark-color<?=($bParent ? ' parent' : '')?> top-mobile-menu__link" href="<?=$arSubSubItem["LINK"]?>" title="<?=$arSubSubItem["TEXT"]?>">
														<?if($bShowImages):?>
															<?
															$arSubSubItemImg = ( (isset($arSubSubItem['PARAMS']['SECTION_ICON'])) ? $arSubSubItem['PARAMS']['SECTION_ICON'] : $arSubSubItem['PARAMS']['PICTURE'] );
															$arImg = CFile::ResizeImageGet($arSubSubItemImg, array('width' => 50, 'height' => 50), BX_RESIZE_IMAGE_PROPORTIONAL_ALT);
															?>
															<?if(is_array($arImg)):?>
																<span class="image top-mobile-menu__image colored_theme_svg">
																	<?if(strpos($arImg["src"], ".svg") !== false && \CMax::GetFrontParametrValue('COLORED_CATALOG_ICON') === 'Y'):?>
																		<?=\Aspro\Functions\CAsproMax::showSVG([
																			'PATH' => $arImg["src"]
																		]);?>
																	<?else:?>
																		<img class="lazy" data-src="<?=$arImg["src"];?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($arImg["src"]);?>" alt="<?=$arSubSubItem["TEXT"];?>" />
																	<?endif;?>
																</span>
															<?endif;?>
														<?endif;?>
														<span class="top-mobile-menu__title"><?=$arSubSubItem['TEXT']?></span>
														<?if($bParent):?>
															<span class="arrow"><?=CMax::showSpriteIconSvg(SITE_TEMPLATE_PATH."/images/svg/trianglearrow_sprite.svg#trianglearrow_right", "triangle", ['WIDTH' => 3,'HEIGHT' => 5]);?></span>
														<?endif;?>
													</a>
													<?if($bParent):?>
														<ul class="dropdown">
															<li class="menu_back"><a href="" class="dark-color" rel="nofollow"><?=CMax::showIconSvg('back_arrow', SITE_TEMPLATE_PATH.'/images/svg/return_mm.svg')?><?=GetMessage('MAX_T_MENU_BACK')?></a></li>
															<li class="menu_title"><a href="<?=$arSubSubItem['LINK'];?>"><?=$arSubSubItem['TEXT']?></a></li>
															<?foreach($arSubSubItem["CHILD"] as $arSubSubSubItem):?>
																<li<?=($arSubSubSubItem['SELECTED'] ? ' class="selected"' : '')?>>
																	<a class="dark-color top-mobile-menu__link" href="<?=$arSubSubSubItem["LINK"]?>" title="<?=$arSubSubSubItem["TEXT"]?>">
																		<?if($bShowImages):?>
																			<?
																			$arSubSubSubItemImg = ( (isset($arSubSubSubItem['PARAMS']['SECTION_ICON'])) ? $arSubSubSubItem['PARAMS']['SECTION_ICON'] : $arSubSubSubItem['PARAMS']['PICTURE'] );
																			$arImg = CFile::ResizeImageGet($arSubSubSubItemImg, array('width' => 50, 'height' => 50), BX_RESIZE_IMAGE_PROPORTIONAL_ALT);
																			?>
																			<?if(is_array($arImg)):?>
																				<span class="image top-mobile-menu__image colored_theme_svg">
																					<?if(strpos($arImg["src"], ".svg") !== false && \CMax::GetFrontParametrValue('COLORED_CATALOG_ICON') === 'Y'):?>
																						<?=\Aspro\Functions\CAsproMax::showSVG([
																							'PATH' => $arImg["src"]
																						]);?>
																					<?else:?>
																						<img class="lazy" data-src="<?=$arImg["src"];?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($arImg["src"]);?>" alt="<?=$arSubSubSubItem["TEXT"];?>" />
																					<?endif;?>
																				</span>
																			<?endif;?>
																		<?endif;?>
																		<span class="top-mobile-menu__title"><?=$arSubSubSubItem['TEXT']?></span>
																	</a>
																</li>
															<?endforeach;?>
														</ul>
													<?endif;?>
												</li>
											<?endforeach;?>
										</ul>
									<?endif;?>
								</li>
							<?endforeach;?>
						</ul>
					<?endif;?>
				</li>
			<?endforeach;?>
		</ul>
	</div>
<?endif;?>
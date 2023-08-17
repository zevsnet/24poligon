<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?use \Bitrix\Main\Localization\Loc;?>
<?if($arResult):?>
	<?global $arTheme;?>
	<div class="menu_top_block catalog_block">
		<?if(!CMax::IsMainPage()):?>
			<div class="slide-block">
				<div class="slide-block__head title-menu font-bold colored_theme_hover_bg-block darken font_upper_md<?=($_COOKIE['MENU_CLOSED'] == 'Y' ? ' closed' : '');?>" data-id="MENU">
					<?=CMax::showIconSvg("catalog", SITE_TEMPLATE_PATH.'/images/svg/icon_catalog.svg', '', '');?>
					<?=Loc::getMessage('CATALOG_LINK');?>
					<?=CMax::showIconSvg("down colored_theme_hover_bg-el", SITE_TEMPLATE_PATH.'/images/svg/arrow_catalogcloser.svg', '', '', true, false);?>
				</div>
				<div class="slide-block__body">
		<?endif;?>
		<ul class="menu dropdown">
			<?foreach($arResult as $key => $arItem){?>
				<?
				$arItem['IMAGES'] = (isset($arItem['PARAMS']['SECTION_ICON']) ? $arItem['PARAMS']['SECTION_ICON'] : (isset($arItem['PARAMS']['PICTURE']) ? $arItem['PARAMS']['PICTURE'] : $arItem['IMAGES']));

				if(!is_array($arItem['IMAGES'])){
					$arItem['IMAGES'] = CFile::ResizeImageGet($arItem["IMAGES"], array('width' => 60, 'height' => 60), BX_RESIZE_IMAGE_PROPORTIONAL_ALT);
				}
				?>
				<li class="full <?=($arItem["CHILD"] ? "has-child" : "");?> <?=($arItem["SELECTED"] ? "current" : "");?><?($arChildItem["CHILD"] ? "opened" : "")?> m_<?=strtolower(CMax::GetFrontParametrValue("MENU_POSITION"));?> v_<?=strtolower(CMax::GetFrontParametrValue("MENU_TYPE_VIEW"));?>">
					<a class="icons_fa<?=($arItem["CHILD"] ? " parent" : "");?> rounded2 bordered" href="<?=$arItem["SECTION_PAGE_URL"]?>" >
						<?if($arItem["CHILD"]):?>
							<?if(strtolower(CMax::GetFrontParametrValue("MENU_TYPE_VIEW")) != "bottom" ):?>
								<?=CMax::showSpriteIconSvg(SITE_TEMPLATE_PATH."/images/svg/trianglearrow_sprite.svg#trianglearrow_right", "right", ['WIDTH' => 3,'HEIGHT' => 5, 'INLINE' => 'N']);?>
							<?else:?>
								<?=CMax::showSpriteIconSvg(SITE_TEMPLATE_PATH."/images/svg/trianglearrow_sprite.svg#trianglearrow_down", "svg-inline-down", ['WIDTH' => 5,'HEIGHT' => 3, 'INLINE' => 'N']);?>
							<?endif;?>
						<?endif;?>
						<?if($arItem["IMAGES"] && CMax::GetFrontParametrValue("LEFT_BLOCK_CATALOG_ICONS") == "Y"):?>
							<span class="image colored_theme_svg">
								<?if(strpos($arItem["IMAGES"]["src"], ".svg") !== false && \CMax::GetFrontParametrValue('COLORED_CATALOG_ICON') === 'Y'):?>
									<?=\Aspro\Functions\CAsproMax::showSVG([
										'PATH' => $arItem["IMAGES"]["src"]
									]);?>
								<?else:?>
									<img class="lazy" data-src="<?=$arItem["IMAGES"]["src"];?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($arItem["IMAGES"]["src"]);?>" alt="<?=$arItem["NAME"];?>" />
								<?endif;?>
							</span>
						<?endif;?>
						<span class="name"><?=$arItem["NAME"]?></span>
						<span class="toggle_block"></span>
					</a>
					<?if($arItem["CHILD"]):?>
						<ul class="dropdown">
							<?foreach($arItem["CHILD"] as $arChildItem){?>
								<?
								$arChildItem['IMAGES'] = (isset($arChildItem['PARAMS']['SECTION_ICON']) ? $arChildItem['PARAMS']['SECTION_ICON'] : (isset($arChildItem['PARAMS']['PICTURE']) ? $arChildItem['PARAMS']['PICTURE'] : $arChildItem['IMAGES']));

								if(!is_array($arChildItem['IMAGES'])){
									$arChildItem['IMAGES'] = CFile::ResizeImageGet($arChildItem["IMAGES"], array('width' => 60, 'height' => 60), BX_RESIZE_IMAGE_PROPORTIONAL_ALT);
								}
								?>
								<li class="<?=($arChildItem["CHILD"] ? "has-child" : "");?> <?if($arChildItem["SELECTED"]){?> current opened <?}?>">
									<?if($arChildItem["IMAGES"] && CMax::GetFrontParametrValue('SHOW_CATALOG_SECTIONS_ICONS') == 'Y' && CMax::GetFrontParametrValue("MENU_TYPE_VIEW") !== 'BOTTOM'){?>
										<span class="image colored_theme_svg">
											<a href="<?=$arChildItem["SECTION_PAGE_URL"];?>">
												<?if(strpos($arChildItem["IMAGES"]["src"], ".svg") !== false && \CMax::GetFrontParametrValue('COLORED_CATALOG_ICON') === 'Y'):?>
													<?=\Aspro\Functions\CAsproMax::showSVG([
														'PATH' => $arChildItem["IMAGES"]["src"]
													]);?>
												<?else:?>
													<img class="lazy" data-src="<?=$arChildItem["IMAGES"]["src"];?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($arChildItem["IMAGES"]["src"]);?>" alt="<?=$arChildItem["NAME"];?>" />
												<?endif;?>
											</a>
										</span>
									<?}?>
									<a class="section option-font-bold" href="<?=$arChildItem["SECTION_PAGE_URL"];?>">
										<span><?=$arChildItem["NAME"];?></span>
										<?if($arChildItem["CHILD"]):?>
											<?=CMax::showSpriteIconSvg(SITE_TEMPLATE_PATH."/images/svg/trianglearrow_sprite.svg#trianglearrow_down", "svg-inline-down", ['WIDTH' => 5,'HEIGHT' => 3, 'INLINE' => 'N']);?>
											<span class="toggle_block"></span>
										<?endif;?>
									</a>
									<?if($arChildItem["CHILD"]):?>
										<ul class="dropdown">
											<?foreach($arChildItem["CHILD"] as $arChildItem1){?>
												<li class="menu_item <?if($arChildItem1["SELECTED"]){?> current <?}?>">
													<a class="parent1 section1" href="<?=$arChildItem1["SECTION_PAGE_URL"];?>"><span><?=$arChildItem1["NAME"];?></span></a>
												</li>
											<?}?>
										</ul>
									<?endif;?>
								</li>
							<?}?>
						</ul>
					<?endif;?>
				</li>
			<?}?>
		</ul>
		<?if(!CMax::IsMainPage()):?>
				</div>
			</div>
		<?endif;?>
	</div>
<?endif?>
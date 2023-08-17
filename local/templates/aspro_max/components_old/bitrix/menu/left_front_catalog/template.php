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
				<li class="full <?=($arItem["CHILD"] ? "has-child" : "");?> <?=($arItem["SELECTED"] ? "current opened" : "");?> m_<?=strtolower($arTheme["MENU_POSITION"]["VALUE"]);?> v_<?=strtolower($arTheme["MENU_TYPE_VIEW"]["VALUE"]);?>">
					<a class="icons_fa<?=($arItem["CHILD"] ? " parent" : "");?> rounded2 bordered" href="<?=$arItem["SECTION_PAGE_URL"]?>" >
						<?if($arItem["CHILD"]):?>
							<?if(strtolower($arTheme["MENU_TYPE_VIEW"]["VALUE"]) != "bottom" ):?>
								<?=CMax::showIconSvg("right", SITE_TEMPLATE_PATH.'/images/svg/trianglearrow_right.svg', '', '', true, false);?>
							<?else:?>
								<?=CMax::showIconSvg("down", SITE_TEMPLATE_PATH.'/images/svg/trianglearrow_down.svg', '', '', true, false);?>
							<?endif;?>
						<?endif;?>
						<?if($arItem["IMAGES"] && $arTheme["LEFT_BLOCK_CATALOG_ICONS"]["VALUE"] == "Y"):?>
							<span class="image colored_theme_svg">
								<?if(strpos($arItem["IMAGES"]["src"], ".svg") !== false):?>
									<?=CMax::showIconSvg("cat_icons", $arItem["IMAGES"]["src"]);?>
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
								<li class="<?=($arChildItem["CHILD"] ? "has-child" : "");?> <?if($arChildItem["SELECTED"]){?> current opened <?}?>">
									<?if($arChildItem["IMAGES"] && $arTheme['SHOW_CATALOG_SECTIONS_ICONS']['VALUE'] == 'Y' && $arTheme["MENU_TYPE_VIEW"]["VALUE"] !== 'BOTTOM'){?>
										<span class="image colored_theme_svg">
											<a href="<?=$arChildItem["SECTION_PAGE_URL"];?>">
												<?if(strpos($arChildItem["IMAGES"]["src"], ".svg") !== false):?>
													<?=CMax::showIconSvg("cat_icons", $arChildItem["IMAGES"]["src"]);?>
												<?else:?>
													<img class="lazy" data-src="<?=$arChildItem["IMAGES"]["src"];?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($arChildItem["IMAGES"]["src"]);?>" alt="<?=$arChildItem["NAME"];?>" />
												<?endif;?>
											</a>
										</span>
									<?}?>
									<a class="section option-font-bold" href="<?=$arChildItem["SECTION_PAGE_URL"];?>">
										<span><?=$arChildItem["NAME"];?></span>
										<?if($arChildItem["CHILD"]):?>
											<?=CMax::showIconSvg("down", SITE_TEMPLATE_PATH.'/images/svg/trianglearrow_down.svg', '', '', true, false);?>
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
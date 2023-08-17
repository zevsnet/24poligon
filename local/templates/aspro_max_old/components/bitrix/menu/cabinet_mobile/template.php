<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?global $USER, $arTheme;?>
<?$bParent = $arResult && $USER->IsAuthorized();?>
<?$this->setFrameMode(true);?>
<!-- noindex -->
<div class="menu middle">
	<ul>
		<li <?=(CMax::isPersonalPage() ? 'class="selected"' : '')?> >
			<?$link = CMax::GetFrontParametrValue('PERSONAL_PAGE_URL', SITE_ID);?>
			<a rel="nofollow" class="dark-color<?=($bParent ? ' parent' : '')?>" href="<?=$link;?>">
				<?=CMax::showIconSvg("cabinet", SITE_TEMPLATE_PATH."/images/svg/".($USER->IsAuthorized() ? 'user_login' : 'user').".svg");?>
				<span><?=GetMessage('CABINET_LINK2')?></span>
				<?if($bParent):?>
					<span class="arrow"><?=CMax::showSpriteIconSvg(SITE_TEMPLATE_PATH."/images/svg/trianglearrow_sprite.svg#trianglearrow_right", "triangle", ['WIDTH' => 3,'HEIGHT' => 5, 'INLINE' => 'N']);?></span>
				<?endif;?>
			</a>
			<?if($bParent):?>
				<ul class="dropdown">
					<li class="menu_back"><a href="" class="dark-color" rel="nofollow"><?=CMax::showIconSvg('back_arrow', SITE_TEMPLATE_PATH.'/images/svg/return_mm.svg')?><?=GetMessage('MAX_T_MENU_BACK')?></a></li>
					<li class="menu_title"><a href="<?=$link;?>"><?=GetMessage('CABINET_LINK2')?></a></li>
					<?foreach($arResult as $arItem):?>
						<?
						$bShowChilds = $arParams['MAX_LEVEL'] > 1;
						$arItem['CHILD'] = $arItem['CHILD'] ?? [];
						$bParent = $arItem['CHILD'] && $bShowChilds;
						?>
						<?
						if( isset($arItem["PARAMS"]["class"]) && $arItem["PARAMS"]["class"] === 'exit' ){
							$arItem["LINK"].= '&'.bitrix_sessid_get();
						}
						?>
						<li <?=($arItem['SELECTED'] ? 'class="selected"' : '')?> >
							<a class="dark-color<?=($bParent ? ' parent' : '')?>" href="<?=$arItem["LINK"]?>" title="<?=$arItem["TEXT"]?>">
								<span><?=$arItem['TEXT']?></span>
								<?if($bParent):?>
									<span class="arrow"><?=CMax::showSpriteIconSvg(SITE_TEMPLATE_PATH."/images/svg/trianglearrow_sprite.svg#trianglearrow_right", "triangle", ['WIDTH' => 3,'HEIGHT' => 5, 'INLINE' => 'N']);?></span>
								<?endif;?>
							</a>
							<?if($bParent):?>
								<ul class="dropdown">
									<li class="menu_back"><a href="" class="dark-color" rel="nofollow"><?=CMax::showIconSvg('back_arrow', SITE_TEMPLATE_PATH.'/images/svg/return_mm.svg')?><?=GetMessage('MAX_T_MENU_BACK')?></a></li>
									<li class="menu_title"><?=$arItem['TEXT']?></li>
									<?foreach($arItem['CHILD'] as $arSubItem):?>
										<?$bShowChilds = $arParams['MAX_LEVEL'] > 2;?>
										<?$bParent = $arSubItem['CHILD'] && $bShowChilds;?>
										<li<?=($arSubItem['SELECTED'] ? ' class="selected"' : '')?>>
											<a class="dark-color<?=($bParent ? ' parent' : '')?>" href="<?=$arSubItem["LINK"]?>" title="<?=$arSubItem["TEXT"]?>">
												<span><?=$arSubItem['TEXT']?></span>
												<?if($bParent):?>
													<span class="arrow"><?=CMax::showSpriteIconSvg(SITE_TEMPLATE_PATH."/images/svg/trianglearrow_sprite.svg#trianglearrow_right", "triangle", ['WIDTH' => 3,'HEIGHT' => 5, 'INLINE' => 'N']);?></span>
												<?endif;?>
											</a>
											<?if($bParent):?>
												<ul class="dropdown">
													<li class="menu_back"><a href="" class="dark-color" rel="nofollow"><?=CMax::showIconSvg('back_arrow', SITE_TEMPLATE_PATH.'/images/svg/return_mm.svg')?><?=GetMessage('MAX_T_MENU_BACK')?></a></li>
													<li class="menu_title"><?=$arSubItem['TEXT']?></li>
													<?foreach($arSubItem["CHILD"] as $arSubSubItem):?>
														<?$bShowChilds = $arParams['MAX_LEVEL'] > 3;?>
														<?$bParent = $arSubSubItem['CHILD'] && $bShowChilds;?>
														<li<?=($arSubSubItem['SELECTED'] ? ' class="selected"' : '')?>>
															<a class="dark-color<?=($bParent ? ' parent' : '')?>" href="<?=$arSubSubItem["LINK"]?>" title="<?=$arSubSubItem["TEXT"]?>">
																<span><?=$arSubSubItem['TEXT']?></span>
																<?if($bParent):?>
																	<span class="arrow"><?=CMax::showSpriteIconSvg(SITE_TEMPLATE_PATH."/images/svg/trianglearrow_sprite.svg#trianglearrow_right", "triangle", ['WIDTH' => 3,'HEIGHT' => 5, 'INLINE' => 'N']);?></span>
																<?endif;?>
															</a>
															<?if($bParent):?>
																<ul class="dropdown">
																	<li class="menu_back"><a href="" class="dark-color" rel="nofollow"><?=CMax::showIconSvg('back_arrow', SITE_TEMPLATE_PATH.'/images/svg/return_mm.svg')?><?=GetMessage('MAX_T_MENU_BACK')?></a></li>
																	<li class="menu_title"><?=$arSubSubItem['TEXT']?></li>
																	<?foreach($arSubSubItem["CHILD"] as $arSubSubSubItem):?>
																		<li<?=($arSubSubSubItem['SELECTED'] ? ' class="selected"' : '')?>>
																			<a class="dark-color<?=($bParent ? ' parent' : '')?>" href="<?=$arSubSubSubItem["LINK"]?>" title="<?=$arSubSubSubItem["TEXT"]?>">
																				<span><?=$arSubSubSubItem['TEXT']?></span>
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
			<?endif;?>
		</li>
	</ul>
</div>
<!-- /noindex -->
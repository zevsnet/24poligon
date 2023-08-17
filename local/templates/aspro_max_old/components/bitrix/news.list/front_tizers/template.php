<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<?use \Bitrix\Main\Localization\Loc;?>
<?if($arResult['ITEMS']):?>
	<?$bSmallBlock = ($arParams['SMALL_BLOCK'] == 'Y');?>
	<div class="content_wrapper_block <?=$templateName;?>">
		<div class="maxwidth-theme only-on-front">
			<?if($arParams['INCLUDE_FILE']):?>
				<div class="with-text-block-wrapper">
					<div class="row">
						<div class="col-md-3">
							<div class="text_before_items font_md">
								<?$APPLICATION->IncludeComponent(
									"bitrix:main.include",
									"",
									Array(
										"AREA_FILE_SHOW" => "file",
										"PATH" => SITE_DIR."include/mainpage/inc_files/".$arParams['INCLUDE_FILE'],
										"EDIT_TEMPLATE" => ""
									)
								);?>
							</div>
						</div>
						<div class="col-md-9">
			<?endif;?>
			<?
			$sTemplateMobile = (isset($arParams['MOBILE_TEMPLATE']) ? $arParams['MOBILE_TEMPLATE'] : '');
        	$bList = ($sTemplateMobile === 'list');
        	//var_dump($bList);
        	?>
			<div class="item-views tizers <?=$arParams['TYPE_IMG'];?>">
				<div class="items <?=($bSmallBlock ? 'small-block' : '');?> <?=($arParams['TYPE_IMG'] !== 'top' ? ' tops': '');?>">
					<div class="row flexbox <?=($bSmallBlock ? '' : 'justify-center');?> <?=$sTemplateMobile;?><?=($bList ? ' mobile-list' : '');?>">
						<?foreach($arResult['ITEMS'] as $i => $arItem):?>
							<?
							// edit/add/delete buttons for edit mode
							$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
							$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => Loc::getMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
							// use detail link?
							$bDetailLink = isset($arItem['PROPERTIES']['LINK']['VALUE']) && $arItem['PROPERTIES']['LINK']['VALUE'];
							
							// preview image
							$bImage = ($arItem['FIELDS']['PREVIEW_PICTURE'] ? $arItem['FIELDS']['PREVIEW_PICTURE']['ID'] : '');
							if(isset($arItem['DISPLAY_PROPERTIES']['ICON']) && $arItem['DISPLAY_PROPERTIES']['ICON']['VALUE'])
								$bImage = $arItem['DISPLAY_PROPERTIES']['ICON']['VALUE'];

							$col = (round(12/$arParams['SIZE_IN_ROW']));?>
							<div class="item-wrapper col-md-<?=(($arParams['SIZE_IN_ROW'] == 5) ? '2 col-m-20' : $col);?> col-sm-4 col-xs-6 clearfix" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
								<div class="item<?=($arParams['CENTERED'] == 'Y' && $arParams['TYPE_IMG'] == 'top' ? ' text-center' : '');?> <?=($bSmallBlock || $arParams['TYPE_IMG'] !== 'top' ? 'flexbox flexbox--row' : '');?>">
									<?if($bImage):?>
										<?$img = CFile::ResizeImageGet($bImage, array('width' => 60, 'height' => 60), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);?>
										<div class="image shine<?=($arItem['PROPERTIES']['COLORED']['VALUE'] == 'Y' ? ' colored_theme_svg' : '');?><?=($arParams['TYPE_IMG'] !== 'top' ? ' pull-'.$arParams['TYPE_IMG'] : '');?>">
											<?if($bDetailLink):?>
												<a href="<?=$arItem['PROPERTIES']['LINK']['VALUE']?>">
											<?endif;?>
												<?if(strpos($img["src"], ".svg") !== false):?>
													<?=CMax::showIconSvg('tizer_svg', $img["src"]);?>
												<?else:?>
													<img class="lazy" data-src="<?=$img["src"]?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($img["src"]);?>" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"] ? $arItem["PREVIEW_PICTURE"]["ALT"] : $arItem["NAME"])?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"] ? $arItem["PREVIEW_PICTURE"]["TITLE"] : $arItem["NAME"])?>" />
												<?endif;?>
											<?if($bDetailLink):?>
												</a>
											<?endif;?>
										</div>
									<?endif;?>
									<div class="inner-text">
										<div class="title <?=($bSmallBlock ? 'font_xs' : 'font_md');?>">
											<?if($bDetailLink):?>
												<a href="<?=$arItem['PROPERTIES']['LINK']['VALUE']?>">
											<?endif;?>
											<span <?=($bSmallBlock ? 'class="muted777"' : '');?> ><?=$arItem['~NAME'];?></span>
											<?if($bDetailLink):?>
												</a>
											<?endif;?>
										</div>
										
										<?// date active period?>
										<?if(isset($arItem['FIELDS']['PREVIEW_TEXT']) && strlen($arItem['FIELDS']['PREVIEW_TEXT']) && !$bSmallBlock):?>
											<div class="value font_xs muted777"><?=$arItem['FIELDS']['PREVIEW_TEXT']?></div>
										<?endif;?>
									</div>
								</div>
							</div>
						<?endforeach;?>
					</div>
				</div>
			</div>
			<?if($arParams['INCLUDE_FILE']):?>
				</div></div></div>
			<?endif;?>
		</div>
	</div>
<?endif;?>
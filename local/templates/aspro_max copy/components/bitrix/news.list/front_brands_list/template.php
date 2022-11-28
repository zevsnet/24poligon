<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<?if($arResult["ITEMS"]):?>
	<?$bShowTopBlock = ($arParams['TITLE_BLOCK'] || $arParams['TITLE_BLOCK_ALL']);?>
	<?if(!$arParams['IS_AJAX']):?>
	<div class="content_wrapper_block <?=$templateName;?>">
	<div class="maxwidth-theme <?=($bShowTopBlock ? '' : 'no-title')?>">
		<?if($bShowTopBlock):?>
		<div class="with-text-block-wrapper">
			<div class="row">
				<div class="col-md-3">
					<?if($arParams['TITLE_BLOCK'] || $arParams['TITLE_BLOCK_ALL']):?>
						<h3><?=$arParams['TITLE_BLOCK'];?></h3>
						<?// intro text?>
						<?if($arParams['INCLUDE_FILE']):?>
							<div class="text_before_items font_xs">
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
						<?endif;?>
						<a href="<?=SITE_DIR.$arParams['ALL_URL'];?>" class="btn btn-transparent-border-color btn-sm"><?=$arParams['TITLE_BLOCK_ALL'] ;?></a>
					<?endif;?>
				</div>
				<div class="col-md-9">
		<?endif;?>
					<div class="item-views brands">
						<div class="row flexbox list">
	<?endif;?>
							<?foreach( $arResult["ITEMS"] as $arItem ){
								$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
								$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
								?>
								<div class="col-md-2 col-sm-4 col-xs-6 item text-center">
									<div id="<?=$this->GetEditAreaId($arItem['ID']);?>">
										<a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
											<?if( is_array($arItem["PREVIEW_PICTURE"]) ){?>
												<img class="lazy" data-src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($arItem["PREVIEW_PICTURE"]["SRC"]);?>" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"]?$arItem["PREVIEW_PICTURE"]["ALT"]:$arItem["NAME"]);?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"]?$arItem["PREVIEW_PICTURE"]["TITLE"]:$arItem["NAME"]);?>" />
											<?}elseif( is_array($arItem["DETAIL_PICTURE"]) ){?>
												<img class="lazy" data-src="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($arItem["DETAIL_PICTURE"]["SRC"]);?>" alt="<?=($arItem["DETAIL_PICTURE"]["ALT"]?$arItem["DETAIL_PICTURE"]["ALT"]:$arItem["NAME"]);?>" title="<?=($arItem["DETAIL_PICTURE"]["TITLE"]?$arItem["DETAIL_PICTURE"]["TITLE"]:$arItem["NAME"]);?>" />
											<?}else{?>
												<span><?=$arItem["NAME"]?></span>
											<?}?>
										</a>
									</div>
								</div>
							<?}?>
	<?if(!$arParams['IS_AJAX']):?>
						</div>
	<?endif;?>

	<?// bottom pagination?>
		<div class="bottom_nav_wrapper">
			<div class="bottom_nav animate-load-state<?=($arResult['NAV_STRING'] ? ' has-nav' : '');?>" <?=($arParams['IS_AJAX'] ? "style='display: none; '" : "");?> data-parent=".item-views" data-append=" > .row">
				<?if($arParams['DISPLAY_BOTTOM_PAGER']):?>
					<?=$arResult['NAV_STRING']?>
				<?endif;?>
			</div>
		</div>

	<?if(!$arParams['IS_AJAX']):?>
					</div>
		<?if($bShowTopBlock):?>
				</div>
			</div>
		</div>
		<?endif;?>
	</div></div>
	<?endif;?>
<?endif;?>
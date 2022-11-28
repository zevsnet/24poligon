<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<?use \Bitrix\Main\Localization\Loc;?>
<?if($arResult['ITEMS']):?>
	<?$bSlider = (isset($arParams['MOBILE_TEMPLATE']) && $arParams['MOBILE_TEMPLATE'] === 'Y')?>
	<?if(!$arParams['IS_AJAX']):?>
	<div class="content_wrapper_block <?=$templateName;?> <?=$arResult['NAV_STRING'] ? '' : 'without-border'?>">
	<div class="maxwidth-theme only-on-front">
		<?if($arParams['TITLE_BLOCK'] || $arParams['TITLE_BLOCK_ALL']):?>
			<?if($arParams['INCLUDE_FILE']):?>
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
			<?else:?>
				<div class="top_block">
					<h3><?=$arParams['TITLE_BLOCK'];?></h3>
					<a href="<?=SITE_DIR.$arParams['ALL_URL'];?>" class="pull-right font_upper muted"><?=$arParams['TITLE_BLOCK_ALL'] ;?></a>
				</div>
			<?endif;?>
		<?endif;?>
		<div class="item-views collection <?=$arParams['VIEW_TYPE']?>">
			<div class="items flexbox row<?=($arParams['NO_MARGIN']!='Y' && $arParams['VIEW_TYPE'] == 'grey_pict' ? ' margin-10' : '');?> <?=($arParams['NO_MARGIN']=='Y') ? ' margin0 ' : '';?> <?=($bSlider ? ' swipeignore mobile-overflow mobile-margin-16 mobile-compact' : '');?> c_<?=count($arResult['ITEMS']);?>">
		<?endif?>
		<?global $arTheme;
		$col = ($arParams['SIZE_IN_ROW'] ? $arParams['SIZE_IN_ROW'] : 5);
		$size = floor(12/$col);
		$size_md = floor(12/($col-1));
		$bBgImg = ($arParams['VIEW_TYPE'] == 'bg_img');
		$wdPict = ($arParams['VIEW_TYPE'] == 'wd_pict');
		$position = ($arParams['BG_POSITION'] ? ' set-position '.$arParams['BG_POSITION'] : '');
		?>
				<?foreach($arResult['ITEMS'] as $i => $arItem):?>
					<?
					// edit/add/delete buttons for edit mode
					$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
					$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => Loc::getMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
					// use detail link?
					$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen($arItem['DETAIL_TEXT']) ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1) : true);
					
					// preview image
					$arItemImage = (strlen($arItem['FIELDS']['PREVIEW_PICTURE']['SRC']) ? $arItem['FIELDS']['PREVIEW_PICTURE'] : $arItem['FIELDS']['DETAIL_PICTURE']);
					$arImage = ($arItemImage ? CFile::ResizeImageGet($arItemImage, array('width' => 600, 'height' => 600), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true) : array());
					$imageSrc = ($arItemImage ? $arImage['src'] : '');

					$bPreviewText = (isset($arItem['DISPLAY_PROPERTIES']['INDEX_TEXT']) && strlen($arItem['DISPLAY_PROPERTIES']['INDEX_TEXT']['VALUE']));

					if(!$imageSrc)
					{
						$imageSrc = SITE_TEMPLATE_PATH.'/images/svg/noimage_content.svg';
					}
					?>
			    
								    
					<div class="col-lg-<?=$size;?> col-md-<?=$size_md;?> col-sm-4 col-xs-6 col-450xs s_<?=$col;?> item-wrapper ">
						<div class="item text-center <?=($arParams['FILLED']=='Y') ? ' bg-fill-grey ' : ' bg-fill-white ';?> <?=($arParams['NO_MARGIN']!='Y') ? ' rounded3 ' : '';?>  <?=(!$bBgImg && !$wdPict ? ' bordered box-shadow' : '');?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
							<?if($imageSrc):?>
								<div class="image pattern <?=(!$bBgImg ? 'shine' : '');?>">
									<div class="wrap">
										<?if($bDetailLink):?><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?endif;?>
										<span class="bg-fon-img lazy rounded3 <?=($bBgImg ? 'darken-bg-animate' : '');?><?=$position;?>" data-src="<?=$imageSrc;?>" style="background-image:url(<?=\Aspro\Functions\CAsproMax::showBlankImg($imageSrc);?>)"></span>
										<?if($bDetailLink):?></a><?endif;?>
									</div>
								</div>
							<?endif;?>
							<div class="top-info<?=($bPreviewText ? ' animated' : '');?>">
								<div class="title">
									<?if($bDetailLink):?><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?endif;?>
									<?=$arItem['NAME'];?>
									<?if($bDetailLink):?></a><?endif;?>
								</div>
								<?if($bPreviewText):?>
									<div class="wrap <?=(!$bBgImg ? 'muted' : '');?>">
										<div class="font_sxs"><?=$arItem['DISPLAY_PROPERTIES']['INDEX_TEXT']['VALUE']?></div>
									</div>
								<?endif?>
							</div>
						</div>
					</div>
				<?endforeach;?>
	<?if(!$arParams['IS_AJAX']):?>
			</div>
	<?endif;?>

	<?// bottom pagination?>
		<div class="bottom_nav_wrapper">
			<div class="bottom_nav animate-load-state<?=($arResult['NAV_STRING'] ? ' has-nav' : '');?>" <?=($arParams['IS_AJAX'] ? "style='display: none; '" : "");?> data-parent=".item-views" data-append=".items.row">
				<?if($arParams['DISPLAY_BOTTOM_PAGER']):?>
					<?=$arResult['NAV_STRING']?>
				<?endif;?>
			</div>
		</div>

	<?if(!$arParams['IS_AJAX']):?>
		</div>
		<?if($arParams['INCLUDE_FILE']):?>
			</div></div></div>
		<?endif;?>
	</div></div>
	<?endif;?>
<?endif;?>
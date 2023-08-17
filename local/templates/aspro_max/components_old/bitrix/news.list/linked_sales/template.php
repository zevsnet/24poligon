<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<?use \Bitrix\Main\Localization\Loc;?>
<?if($arResult['ITEMS']):?>
	<div class="item-views list-sales-compact <?=$templateName?>">
		<div class="list-sales-compact__icons"><?=CMax::showIconSvg("sale colored", SITE_TEMPLATE_PATH.'/images/svg/catalog/discount_detail.svg', '', '', true, false);?></div>
		<div class="list-sales-compact__title font_upper_xs muted"><?=Loc::getMessage('TITLE_SALE');?></div>
		<div class="list-sales-compact__list">
			<?foreach($arResult['ITEMS'] as $i => $arItem):?>
				<?
				// edit/add/delete buttons for edit mode
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				// use detail link?
				$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen($arItem['DETAIL_TEXT']) ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1) : true);
				$bRedirect = isset($arItem['DISPLAY_PROPERTIES']['REDIRECT']) && strlen($arItem['DISPLAY_PROPERTIES']['REDIRECT']['VALUE']);
				?>

				<div class="list-sales-compact-item" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
					<div class="list-sales-compact-item__title font_sm">
						<?if(!$bRedirect):?>
							<?if($bDetailLink):?><span class="dark-color dotted" data-event="jqm" data-param-form_id="fast_view_sale" data-name="fast_view_sale" data-param-iblock_id="<?=$arItem["IBLOCK_ID"];?>" data-param-id="<?=$arItem["ID"];?>"><?endif;?>
								<?=$arItem['NAME']?>
							<?if($bDetailLink):?></span><?endif;?>
						<?else:?>
							<a class="dark-color dotted" href="<?=$arItem['DISPLAY_PROPERTIES']['REDIRECT']['VALUE']?>"><?=$arItem['NAME']?></a>
						<?endif;?>
					</div>
				</div>
			<?endforeach;?>
		</div>
	</div>
<?endif;?>
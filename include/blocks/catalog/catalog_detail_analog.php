<?
$arOptions = $arConfig['PARAMS'];
$arResult = $arConfig['ITEM'];
?>
<div id="bx_basket_div_<?=$arResult['ID'];?>" class="basket_props_block" style="display: none"></div>
<div class='catalog-item-analog bordered rounded3 flexbox js-notice-block'>
	<?if ($arOptions['BLOCK_TITLE']):?>
		<div class='catalog-item-analog__title'><?=$arOptions['BLOCK_TITLE'];?></div>
	<?endif;?>

	<div class='catalog-item-analog__inner line-block line-block--20 line-block--align-flex-start flexbox--wrap'>
        <?if ($arResult['IMAGE']):?>
			<div class='catalog-item-analog__image line-block__item'>
				<a href='<?=$arResult['URL'];?>' class='js-notice-block__image'>
					<img src='<?=$arResult['IMAGE']['src'];?>' alt='<?=$arResult['NAME'];?>' title='<?=$arResult['NAME'];?>' />
				</a>
			</div>
		<?endif;?>

		<div class='catalog-item-analog__info flex1 line-block__item'>
			<div class='catalog-item-analog__info-name font_sm'>
				<a href='<?=$arResult['URL'];?>' class='dark_link js-notice-block__title'>
                    <?=$arResult['NAME'];?>
                </a>
			</div>

			<?if ($arResult['PRICE']):?>
				<div class='catalog-item-analog__info-price cost prices'><?=$arResult['PRICE'];?></div>
			<?endif;?>
			
			<?if ($arResult['BUTTONS'] && ($arResult['BUTTONS']['BUY'] || $arResult['BUTTONS']['ACTIONS'])):?>
				<div class='catalog-item-analog__info-buttons sm flexbox flexbox--row flexbox--wrap'>
					<?if ($arResult['BUTTONS']['BUY']):?>
						<div class="catalog-item-analog__buy">
							<?=$arResult['BUTTONS']['BUY'];?>
						</div>
					<?endif;?>
					
					<?if ($arResult['BUTTONS']['ACTIONS']):?>
						<div class="catalog-item-analog__actions">
							<?=$arResult['BUTTONS']['ACTIONS'];?>
						</div>
					<?endif;?>
				</div>
			<?endif;?>
		</div>
	</div>

	<?if ($arOptions['BLOCK_NOTE']):?>
		<div class='catalog-item-analog__note'>
			<div class='catalog-item-analog__note-text muted font_sxs'><?=$arOptions['BLOCK_NOTE'];?></div>
		</div>
	<?endif;?>
</div>
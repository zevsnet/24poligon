<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die();?>
<?$this->setFrameMode(true);?>
<?use \Bitrix\Main\Localization\Loc;?>

<?if(!$GLOBALS['bMobileForm']):?>
	<span class="jqmClose close"><?=CMax::showIconSvg('', SITE_TEMPLATE_PATH.'/images/svg/Close.svg')?></span>
<?endif;?>

<div class="services-detail popup-text-info">
	<div class="popup-text-info__title font_exlg"><?=$arResult["NAME"];?></div>
	<div class="popup-text-info__text">

		<?if($arResult["SHOW_BUY_BUTTON"]):?>
			<div class="price_info_block bordered rounded3">
				<?if(isset($arResult["BUTTON_RESULT_PRICE"]['PRICE']) && $arResult["BUTTON_RESULT_PRICE"]['PRICE'] > 0):?>
					<div class="prices">
						<div class="price font_mlg font-bold darken"><?=CurrencyFormat($arResult["BUTTON_RESULT_PRICE"]['PRICE'], $arResult["BUTTON_RESULT_PRICE"]['CURRENCY']);?></div>
						<?if(isset($arResult["BUTTON_RESULT_PRICE"]['BASE_PRICE']) && $arResult["BUTTON_RESULT_PRICE"]['BASE_PRICE'] !== $arResult["BUTTON_RESULT_PRICE"]['PRICE'] ):?>
							<div class="price_old muted font_sm"><?=CurrencyFormat($arResult["BUTTON_RESULT_PRICE"]['BASE_PRICE'], $arResult["BUTTON_RESULT_PRICE"]['CURRENCY']);?></div>
						<?endif;?>
					</div>
				<?endif;?>
			</div>
		<?endif;?>
		
		<?if($arResult['DETAIL_TEXT'] && strlen($arResult['DETAIL_TEXT'])):?>
			<div class="services_detail_text">
				<?$obParser = new CTextParser;?>
				<?=$obParser->html_cut($arResult['DETAIL_TEXT'], 500);?>
			</div>
		<?endif;?>		

		<div class="popup-text-info__btn">
			<a class="btn btn-default btn-lg" href="<?=$arResult["DETAIL_PAGE_URL"];?>"><?=\Bitrix\Main\Localization\Loc::getMessage("MORE_TEXT_LINK");?></a>
		</div>
	</div>
</div>
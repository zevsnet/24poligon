<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
use Bitrix\Main\Localization\Loc;
?>
<?if (isset($templateData['TEMPLATE_LIBRARY']) && !empty($templateData['TEMPLATE_LIBRARY'])){
	$loadCurrency = false;
	if (!empty($templateData['CURRENCIES']))
		$loadCurrency = Bitrix\Main\Loader::includeModule('currency');
	CJSCore::Init($templateData['TEMPLATE_LIBRARY']);
	if ($loadCurrency){?>
		<script type="text/javascript">
			BX.Currency.setCurrencies(<? echo $templateData['CURRENCIES']; ?>);
		</script>
	<?}
}?>
<?if(\Bitrix\Main\Loader::includeModule("aspro.max"))
{
	global $arRegion;
	$arRegion = CMaxRegionality::getCurrentRegion();
}?>
<?if (isset($templateData['OUT_OF_PRODUCTION']) && $templateData['OUT_OF_PRODUCTION']):?>
	<?
	ob_start();
		$APPLICATION->IncludeFile(SITE_DIR . "include/element_detail_out_of_production_title.php", [], ["MODE" => "html"]);
	$out_of_production_text = trim(ob_get_clean());
	
	ob_start();
		$APPLICATION->IncludeFile(SITE_DIR . "include/element_detail_out_of_production_note.php", [], ["MODE" => "html"]);
	$out_of_production_note = trim(ob_get_clean());
	
	$arOptions = [
		'ID' => $templateData['OUT_OF_PRODUCTION']['SHOW_ANALOG']['ID'],
		'SITE_ID' => SITE_ID,
		'PARAMS' => [
			'IBLOCK_ID' => $templateData['OUT_OF_PRODUCTION']['SHOW_ANALOG']['IBLOCK_ID'],
			'DISPLAY_WISH_BUTTONS' => $arParams['DISPLAY_WISH_BUTTONS'],
			'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'] ? "Y" : "N",
			'MESSAGE_FROM' => Loc::getMessage('FROM'),
			'CACHE_TIME' => $arParams['CACHE_TIME'],

			'BASKET_URL' => $arParams['BASKET_URL'],
			'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
			'CURRENCY_ID' => $arParams['CURRENCY_ID'],
			'DEFAULT_COUNT' => $arParams['DEFAULT_COUNT'],
			'PRICE_CODE' => $arParams['PRICE_CODE'],
			'PRICE_VAT_INCLUDE' => $arParams['PRICE_VAT_INCLUDE'],
			'SHOW_COUNTER_LIST' => $arParams['SHOW_COUNTER_LIST'],
			'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
			'SHOW_DISCOUNT_PERCENT_NUMBER' => $arParams['SHOW_DISCOUNT_PERCENT_NUMBER'],
			'SHOW_DISCOUNT_TIME' => $arParams['SHOW_DISCOUNT_TIME'],
			'SHOW_MEASURE' => $arParams['SHOW_MEASURE'],
			'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
			'STORES' => $arParams['STORES'],
			'STORES' => $arParams['STORES'],
			'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'] ? 'Y' : 'N',
			'USE_REGION' => $arParams['USE_REGION'],

			'TEXT' => $out_of_production_text,
			'NOTE' => $out_of_production_note,
		],
	];
	?>
	<div id="js-item-analog" data-params='<?=str_replace('\'', '"', CUtil::PhpToJSObject($arOptions, false))?>'></div>
	<?unset($arOptions); ?>
<?endif;?>
<script type="text/javascript">
	var viewedCounter = {
		path: '/bitrix/components/bitrix/catalog.element/ajax.php',
		params: {
			AJAX: 'Y',
			SITE_ID: "<?= SITE_ID ?>",
			PRODUCT_ID: "<?= $arResult['ID'] ?>",
			PARENT_ID: "<?= $arResult['ID'] ?>"
		}
	};
	BX.ready(
		BX.defer(function(){
			BX.ajax.post(
				viewedCounter.path,
				viewedCounter.params
			);
		})		
	);
	viewItemCounter('<?=$arResult["ID"];?>','<?=current($arParams["PRICE_CODE"]);?>');
</script>
<script>typeof useCountdown === 'function' && useCountdown()</script>
<?
$arScripts = ['swiper', 'swiper_main_styles', 'countdown'];
if (isset($templateData['OUT_OF_PRODUCTION']) && $templateData['OUT_OF_PRODUCTION']['SHOW_ANALOG']) {
	$arScripts[] = 'out_of_production';
}
if (isset($templateData['JS_OBJ'])) {
	$arScripts[] = 'ikSelect';
}
\Aspro\Max\Functions\Extensions::init($arScripts);
?>
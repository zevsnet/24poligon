<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use \Bitrix\Main\Localization\Loc;

global $arBasketPrices;
$sDelayPrice = '';
$iDelayCount = $summ =  0;
$currency = CSaleLang::GetLangCurrency(SITE_ID);
if($arResult['CATEGORIES']['DELAY'])
{
	foreach($arResult['CATEGORIES']['DELAY'] as $arItem)
	{
		++$iDelayCount;
		$summ += $arItem['PRICE'] * $arItem['QUANTITY'];
	}
	$sDelayPrice = CCurrencyLang::CurrencyFormat($summ, $currency, true);
}
if (class_exists('Bitrix\Sale\BasketComponentHelper') && method_exists('Bitrix\Sale\BasketComponentHelper', 'getFUserBasketPrice')) {
	$arResult['TOTAL_PRICE'] = CCurrencyLang::CurrencyFormat(\Bitrix\Sale\BasketComponentHelper::getFUserBasketPrice(\Bitrix\Sale\Fuser::getId(true), SITE_ID), $currency, true);
}

$title_basket =  ($arResult['NUM_PRODUCTS'] ? Loc::getMessage("BASKET_COUNT", array("#PRICE#" => CMax::clearFormatPrice($arResult['TOTAL_PRICE']))) : Loc::getMessage("EMPTY_BLOCK_BASKET"));
$title_delay = ($sDelayPrice ? Loc::getMessage("BASKET_DELAY_COUNT", array("#PRICE#" => CMax::clearFormatPrice($sDelayPrice))) : Loc::getMessage("EMPTY_BLOCK_DELAY"));

$arBasketPrices = array(
	'BASKET_COUNT' => (int)$arResult['NUM_PRODUCTS'],
	'BASKET_SUMM' => $arResult['TOTAL_PRICE'],
	'BASKET_SUMM_TITLE' => $title_basket,
	'BASKET_SUMM_TITLE_SMALL' => Loc::getMessage('EMPTY_BASKET'),
	'DELAY_COUNT' => $iDelayCount,
	'DELAY_SUMM_TITLE' => $title_delay,
);
?>
<?if(isset($arParams['BY_AJAX']) && $arParams['BY_AJAX'] == 'Y'):?>
	<script type="text/javascript">
		var arBasketPrices = <? echo CUtil::PhpToJSObject($arBasketPrices, false, true); ?>;
	</script>
<?else:?>
	<div id="ajax_basket"></div>
<?endif;?>

<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $templateData */
/** @var @global CMain $APPLICATION */
use Bitrix\Main\Loader;

if (isset($templateData['TEMPLATE_LIBRARY']) && !empty($templateData['TEMPLATE_LIBRARY'])){
	$loadCurrency = false;
	if (!empty($templateData['CURRENCIES']))
		$loadCurrency = Loader::includeModule('currency');
	CJSCore::Init($templateData['TEMPLATE_LIBRARY']);
	if ($loadCurrency){?>
	<script type="text/javascript">
		BX.Currency.setCurrencies(<? echo $templateData['CURRENCIES']; ?>);
	</script>
	<?}
}

global $arEditMeta;
if (empty($arEditMeta['description']['#MIN_PRICE_SECTION#']))    $arEditMeta['description']['#MIN_PRICE_SECTION#'] = $arResult['MIN_PRICE_SECTION'];
if (empty($arEditMeta['description']['#MIN_PRICE_SECTION#']))    $arEditMeta['description']['#MAX_PRICE_SECTION#'] = $arResult['MAX_PRICE_SECTION'];
if (empty($arEditMeta['description']['#COUNT_ELEMENT_SECTION#']))    $arEditMeta['description']['#COUNT_ELEMENT_SECTION#'] = $arResult['COUNT_ELEMENT_SECTION'];


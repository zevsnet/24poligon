<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $templateData */
/** @var @global CMain $APPLICATION */
use Bitrix\Main\Loader;
global $arTheme;

$arScripts = ['countdown', 'bonus_system'];

//	big data json answers
if(isset($arParams["BIG_DATA_MODE"]) && $arParams["BIG_DATA_MODE"] === 'Y'){
	$request = \Bitrix\Main\Context::getCurrent()->getRequest();
	if ($request->isAjaxRequest() && ($request->get('action') === 'deferredLoad'))
	{
		$content = ob_get_contents();
		ob_end_clean();

		list(, $itemsContainer) = explode('<!-- items-container -->', $content);

		$component::sendJsonAnswer(array(
			'items' => $itemsContainer,
		));
		
	}
	$arScripts[] = 'bigdata';
}

if (isset($arParams['SLIDE_ITEMS']) && $arParams['SLIDE_ITEMS']) {
	$arScripts[] = 'owl_carousel';?>
	<script>BX.ready(() => typeof InitOwlSlider === 'function' && InitOwlSlider())</script>
<?
}

if( CMax::GetFrontParametrValue("HOVER_TYPE_IMG") !== 'none' )
	$arScripts[] = 'animation_ext';

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
}?>
<script>typeof useCountdown === 'function' && useCountdown()</script>

<? if( count($arScripts) ): ?>
	<? \Aspro\Max\Functions\Extensions::initInPopup($arScripts); ?>
<? endif; ?>
<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $templateData */
/** @var @global CMain $APPLICATION */
use Bitrix\Main\Loader;
global $arTheme;


$arScripts = [];

//	big data json answers
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
if ($request->isAjaxRequest() && ($request->get('action') === 'deferredLoad') && $arParams['BIG_DATA_MODE'] == "Y")
{
	$content = ob_get_contents();
	ob_end_clean();

	list(, $itemsContainer) = explode('<!-- items-container -->', $content);

	$component::sendJsonAnswer(array(
		'items' => $itemsContainer,
	));
	
}

if ($arParams['BIG_DATA_MODE'] == "Y") {
	$arScripts[] = 'bigdata';
}

if (isset($arParams['SLIDE_ITEMS']) && $arParams['SLIDE_ITEMS'])
	$arScripts[] = 'owl_carousel';

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

<? if( count($arScripts) ): ?>
	<? \Aspro\Max\Functions\Extensions::init($arScripts); ?>
<? endif; ?>
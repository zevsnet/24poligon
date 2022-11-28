<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?
global $arTheme, $arRegion;
$IsViewedTypeLocal = $arTheme['VIEWED_TYPE']['VALUE'] === 'LOCAL';
if($arRegion)
{
	if($arRegion['LIST_PRICES'])
	{
		if(reset($arRegion['LIST_PRICES']) != 'component')
			$arParams['PRICE_CODE'] = array_keys($arRegion['LIST_PRICES']);
	}
	if($arRegion['LIST_STORES'])
	{
		if(reset($arRegion['LIST_STORES']) != 'component')
			$arParams['STORES'] = $arRegion['LIST_STORES'];
	}
}

$arViewedIDs=CMax::getViewedProducts((int)CSaleBasket::GetBasketUserID(false), SITE_ID);?>
<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("viewed-block");?>
	<?if($arViewedIDs){?>
		<div class="viewed_product_block <?=($arTheme["SHOW_BG_BLOCK"]["VALUE"] == "Y" ? "fill" : "no_fill");?>">
			<div class="wrapper_inner">
				<?$APPLICATION->IncludeComponent(
					"aspro:catalog.viewed.max",
					"main_horizontal",
					array(
						"TITLE_BLOCK" => GetMessage('VIEWED_BEFORE'),
						"SHOW_MEASURE" => "Y",
						"CACHE_TYPE" => "N",
					),
					false
				);?>
			</div>
		</div>
	<?}?>
<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("viewed-block", "");?>
<?
use Bitrix\Main\Type\Collection;
use Bitrix\Currency\CurrencyTable;
use Bitrix\Iblock;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();


if (!empty($arResult['ITEMS']))
{
	$arConvertParams = array();
	if ('Y' == $arParams['CONVERT_CURRENCY'])
	{
		if (!CModule::IncludeModule('currency'))
		{
			$arParams['CONVERT_CURRENCY'] = 'N';
			$arParams['CURRENCY_ID'] = '';
		}
		else
		{
			$arResultModules['currency'] = true;
			if($arResult['CURRENCY_ID'])
			{
				$arConvertParams['CURRENCY_ID'] = $arResult['CURRENCY_ID'];
			}
			else
			{
				$arCurrencyInfo = CCurrency::GetByID($arParams['CURRENCY_ID']);
				if (!(is_array($arCurrencyInfo) && !empty($arCurrencyInfo)))
				{
					$arParams['CONVERT_CURRENCY'] = 'N';
					$arParams['CURRENCY_ID'] = '';
				}
				else
				{
					$arParams['CURRENCY_ID'] = $arCurrencyInfo['CURRENCY'];
					$arConvertParams['CURRENCY_ID'] = $arCurrencyInfo['CURRENCY'];
				}
			}
		}
	}

	$arEmptyPreview = false;
	$strEmptyPreview = SITE_TEMPLATE_PATH . '/images/no_photo_medium.png';
	if (file_exists($_SERVER['DOCUMENT_ROOT'] . $strEmptyPreview))
	{
		$arSizes = getimagesize($_SERVER['DOCUMENT_ROOT'] . $strEmptyPreview);
		if (!empty($arSizes))
		{
			$arEmptyPreview = array(
				'SRC' => $strEmptyPreview,
				'WIDTH' => intval($arSizes[0]),
				'HEIGHT' => intval($arSizes[1])
			);
		}
		unset($arSizes);
	}
	unset($strEmptyPreview);

	$strBaseCurrency = '';
	$boolConvert = isset($arResult['CONVERT_CURRENCY']['CURRENCY_ID']);

	if (!$boolConvert)
		$strBaseCurrency = CCurrency::GetBaseCurrency();

	$catalogs = array();

	$arNewItemsList = array();

	foreach ($arResult['ITEMS'] as $key => $arItem)
	{
		$arItem['CATALOG_QUANTITY'] = (
		0 < $arItem['CATALOG_QUANTITY'] && is_float($arItem['CATALOG_MEASURE_RATIO'])
			? floatval($arItem['CATALOG_QUANTITY'])
			: intval($arItem['CATALOG_QUANTITY'])
		);
		$arItem['CATALOG'] = false;
		$arItem['CATALOG'] = true;
		if (!isset($arItem['CATALOG_TYPE']))
			$arItem['CATALOG_TYPE'] = CCatalogProduct::TYPE_PRODUCT;
		if (
			(CCatalogProduct::TYPE_PRODUCT == $arItem['CATALOG_TYPE'] || CCatalogProduct::TYPE_SKU == $arItem['CATALOG_TYPE'])
			&& !empty($arItem['OFFERS'])
		)
		{
			$arItem['CATALOG_TYPE'] = CCatalogProduct::TYPE_SKU;
		}
		switch ($arItem['CATALOG_TYPE'])
		{
			case CCatalogProduct::TYPE_SET:
				$arItem['OFFERS'] = array();
				$arItem['CATALOG_MEASURE_RATIO'] = 1;
				$arItem['CATALOG_QUANTITY'] = 0;
				$arItem['CHECK_QUANTITY'] = false;
				break;
			case CCatalogProduct::TYPE_SKU:
				break;
			case CCatalogProduct::TYPE_PRODUCT:
			default:
				$arItem['CHECK_QUANTITY'] = ('Y' == $arItem['CATALOG_QUANTITY_TRACE'] && 'N' == $arItem['CATALOG_CAN_BUY_ZERO']);
				break;
		}

		// Offers
		if ($arItem['CATALOG'] && isset($arItem['OFFERS']) && !empty($arItem['OFFERS']))
		{
			$arMatrixFields = $arSKUPropKeys;
			$arMatrix = array();

			$arNewOffers = array();
			$arItem['OFFERS_PROP'] = false;

			//set min price when USE_PRICE_COUNT
			if($arParams['USE_PRICE_COUNT'] == 'Y')
			{
				$arPriceTypeID = array();
				foreach($arItem['OFFERS'] as $keyOffer => $arOffer)
				{
					//format prices when USE_PRICE_COUNT
					if(function_exists('CatalogGetPriceTableEx') && (isset($arOffer['PRICE_MATRIX'])) && !$arOffer['PRICE_MATRIX'])
					{
						if($arOffer['PRICES'])
						{
							foreach($arOffer['PRICES'] as $priceKey => $arOfferPrice)
							{
								if($arOffer['CATALOG_GROUP_NAME_'.$arOfferPrice['PRICE_ID']])
								{
									$arPriceTypeID[] = $arOfferPrice['PRICE_ID'];
									$arOffer['PRICES'][$priceKey]['GROUP_NAME'] = $arOffer['CATALOG_GROUP_NAME_'.$arOfferPrice['PRICE_ID']];
								}
							}
						}
						$arOffer["PRICE_MATRIX"] = CatalogGetPriceTableEx($arOffer["ID"], 0, $arPriceTypeID, 'Y', $arConvertParams);
					}
					$arItem['OFFERS'][$keyOffer] = array_merge($arOffer, CMax::formatPriceMatrix($arOffer));
				}
			}

			$arItem['MIN_PRICE'] = CMax::getMinPriceFromOffersExt(
				$arItem['OFFERS'],
				$boolConvert ? $arResult['CONVERT_CURRENCY']['CURRENCY_ID'] : $strBaseCurrency
			);
		}

		if ($arItem['CATALOG'] && CCatalogProduct::TYPE_PRODUCT == $arItem['CATALOG_TYPE'])
		{
			CIBlockPriceTools::setRatioMinPrice($arItem, true);
		}

		//set min price when USE_PRICE_COUNT
		if($arParams['USE_PRICE_COUNT'] == 'Y' && !$arItem['OFFERS'])
		{
			$arItem["FIX_PRICE_MATRIX"] = CMax::checkPriceRangeExt($arItem);
		}
		//format prices when USE_PRICE_COUNT
		$arItem = array_merge($arItem, CMax::formatPriceMatrix($arItem));
		$arItem['LAST_ELEMENT'] = 'N';
		$arNewItemsList[$key] = $arItem;
	}
	$arNewItemsList[$key]['LAST_ELEMENT'] = 'Y';
	$arResult['ITEMS'] = $arNewItemsList;
	$arResult['DEFAULT_PICTURE'] = $arEmptyPreview;
}?>
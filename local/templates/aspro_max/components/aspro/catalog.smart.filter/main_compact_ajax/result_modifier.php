<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult['ITEMS'])
{
	$arParams["POPUP_POSITION"] = (isset($arParams["POPUP_POSITION"]) && in_array($arParams["POPUP_POSITION"], array("left", "right"))) ? $arParams["POPUP_POSITION"] : "left";

	// need to correct display prop stores_filter
	Aspro\Max\Stores\Property::filterSmartProp($arResult['ITEMS'], $arParams);

	foreach($arResult["ITEMS"] as $key => $arItem)
	{
		/*unset empty values*/
		if (
			(
			 ($arItem["DISPLAY_TYPE"] == "A" || isset($arItem["PRICE"]))
			 && ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0)
			)
			|| !$arItem["VALUES"]
		)
			unset($arResult["ITEMS"][$key]);
		/**/

		$arResult['ITEMS'][$key]['IS_PROP_INLINE'] = false;
		
		if( $arItem['PROPERTY_TYPE'] === 'L' ){
			$iPropValuesCount = CIBlockPropertyEnum::GetList([], [
				'PROPERTY_ID' => $arItem['ID'],
				'IBLOCK_ID' => $arItem['IBLOCK_ID']
			])->SelectedRowsCount();
			if($iPropValuesCount === 1){
				if(is_array($arResult["ITEMS"][$key]["VALUES"]))
					sort($arResult["ITEMS"][$key]["VALUES"]);
	
				if($arResult["ITEMS"][$key]["VALUES"])
					$arResult["ITEMS"][$key]["VALUES"][0]["VALUE"] = $arItem["NAME"];
				
				$arResult['ITEMS'][$key]['IS_PROP_INLINE'] = true;
			}
		}

		if(isset($arItem['PRICE']) && $arItem['PRICE'])
		{
			if (
				(isset($arItem['VALUES']['MIN']['HTML_VALUE']) && $arItem['VALUES']['MIN']['HTML_VALUE'])
				&& (isset($arItem['VALUES']['MAX']['HTML_VALUE']) && $arItem['VALUES']['MAX']['HTML_VALUE'])
			) {
				$arResult['PRICE_SET'] = 'Y';
				break;
			}
		}

		$i = 0;

		if($arItem['PROPERTY_TYPE'] == 'S' || $arItem['PROPERTY_TYPE'] == 'L' || $arItem['PROPERTY_TYPE'] == 'E')
		{
			foreach($arItem['VALUES'] as $arValue)
			{
				if(isset($arValue['CHECKED']) && $arValue['CHECKED'])
				{
					$arResult["ITEMS"][$key]['PROPERTY_SET'] = 'Y';
					++$i;
				}
			}

			if($i)
			{
				$arResult["ITEMS"][$key]['COUNT_SELECTED'] = $i;
			}
		}

		if($arItem['PROPERTY_TYPE'] == 'N')
		{
			foreach($arItem['VALUES'] as $arValue)
			{
				if(isset($arValue['HTML_VALUE']) && $arValue['HTML_VALUE'])
				{
					$arResult['ITEMS'][$key]['PROPERTY_SET'] = 'Y';
				}
			}
		}
	}
}

\Bitrix\Main\Localization\Loc::loadLanguageFile(__FILE__);

if (!$arResult['ITEMS']) {
	$arResult['EMPTY_ITEMS'] = true;
}

// sort
if ($arParams['SHOW_SORT']) {
	include 'sort.php';
}

global $sotbitFilterResult;
$sotbitFilterResult = $arResult;
<?
use \Bitrix\Main\Type\Collection;

$sortOrder = $arParams['SORT_ORDER'] == 'ASC' ? SORT_ASC : SORT_DESC;
$sortOrder2 = $arParams['SORT_ORDER_2'] == 'ASC' ? SORT_ASC : SORT_DESC;

if($arResult['SECTIONS']) {

	global $arRegion;
	
	/*add key - SECTION_ID*/
	$arTmpSections = [];
	foreach ($arResult['SECTIONS'] as $arSecion) {
		$arTmpSections[$arSecion['ID']]  =$arSecion;
	}
	$arResult['SECTIONS'] = $arTmpSections;
	unset($arTmpSections);
	/**/

	/*set region link*/
	if ($arParams['FILTER_NAME'] === 'arRegionLink' && $arRegion && \Bitrix\Main\Config\Option::get('aspro.max', 'REGIONALITY_FILTER_ITEM', 1000, SITE_ID) === 'Y') {

		/*set region filter section*/
		$arFilter = [
			'IBLOCK_ID' => $arParams['IBLOCK_ID'],
			[
				'LOGIC' => 'OR',
				['UF_REGION' => ''],
				['UF_REGION' => $arRegion['ID']],
			]
		];
		$arSelect = [
			'ID',
			'IBLOCK_ID',
			'UF_REGION'
		];
		$arSections = CMaxCache::CIBLockSection_GetList(
			array(
				'CACHE' => array(
					'TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']),
					'MULTI' => 'Y',
					'GROUP' => 'ID'
				)
			),
			$arFilter,
			false,
			$arSelect,
			false
		);

		if ($arSections) {
			foreach ($arResult['SECTIONS'] as $key => $arSecion) {
				if (!$arSections[$key]) {
					unset($arResult['SECTIONS'][$key]);
				}
			}
		} else {
			$arResult['SECTIONS'] = [];
		}
		/**/

		/*set region filter element*/
		/*if ($arResult['SECTIONS']) {
			$arSectionsIDs = array_keys($arResult['SECTIONS']);
			$arElementsFilter = array(
				'IBLOCK_ID' => $arParams['IBLOCK_ID'],
				'IBLOCK_SECTION_ID' => $arSectionsIDs,
				'ACTIVE' => 'Y',
				[
					'LOGIC' => 'OR',
					['PROPERTY_LINK_REGION' => $arRegion['ID']],
					['PROPERTY_LINK_REGION' => ''],
				]
			);
			$arElementsSelect = array(
				'ID',
				'IBLOCK_SECTION_ID',
				'PROPERTY_LINK_REGION',
				'NAME',
			);

			$arElements = CMaxCache::CIBLockElement_GetList(array('SORT' => 'ASC', 'MULTI'=> 'Y', 'CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => 'IBLOCK_SECTION_ID')), $arElementsFilter, false, false, $arElementsSelect);

			if($arElements) {
				foreach($arSections as $key => $arSection) {
					if (!$arElement[$arSection['ID']]) {
						unset($arResult['SECTIONS'][$key]);
					}
				}
			} else {
				$arResult['SECTIONS'] = [];
			}
		}*/
		/**/
	}
	/**/

	if ($arResult['SECTIONS']) {
    	\Bitrix\Main\Type\Collection::sortByColumn($arResult['SECTIONS'], array($arParams['SORT'] => $sortOrder, $arParams['SORT_2'] => $sortOrder2));
	}
}
?>
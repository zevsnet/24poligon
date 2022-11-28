<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?if ($arResult['ITEMS']) {
    $arSectionsID = $arSectionsRoot = $arSectionsItem = $arSectionsDepth = $arSectionsByRoot = [];
    $arItems = [];

    //get sections item
    foreach ($arResult['ITEMS'] as $arItem) {
        if ($arItem['IBLOCK_SECTION_ID']) {
            $arItem['IBLOCK_SECTION_ID'] = _GetElementSectionsArray($arItem['ID']);
            if (is_array($arItem['IBLOCK_SECTION_ID'])) {
                foreach ($arItem['IBLOCK_SECTION_ID'] as $id) {
                    $arSectionsID[] = $id;
                    $arItems[$id][] = $arItem;
                }
            } else {
                $arSectionsID[] = $arItem['IBLOCK_SECTION_ID'];
                $arItems[$arItem['IBLOCK_SECTION_ID']][] = $arItem;
            }
        }
    }

    if ($arItems) {
        unset($arResult['ITEMS']);
    }

    if ($arSectionsID) {
        //get section name
        $arSectionsItem = CMaxCache::CIBLockSection_GetList(
            [
                'CACHE' => [
                    'TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID'])
                ]
            ],
            [
                'ID' => $arSectionsID,
                'ACTIVE' => 'Y'
            ],
            false,
            ['ID', 'IBLOCK_ID', 'DEPTH_LEVEL', 'LEFT_MARGIN', 'RIGHT_MARGIN', 'NAME', 'SORT']
        );
         if ($arSectionsItem) {
             foreach ($arSectionsItem as $arSection) {
                 if ($arSection['DEPTH_LEVEL'] == 1) {
                     $arSectionsRoot[$arSection['ID']] = $arSection;
                 } else {
                    $arSectionsDepth[$arSection['ID']] = $arSection;
                 }
             }
             //get root section
             if ($arSectionsDepth) {
                foreach ($arSectionsDepth as $arSection) {
                    $arSectionTmp = CMaxCache::CIBLockSection_GetList(
                        [
                            'CACHE' => [
                                'TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']),
                                'MULTI' => 'N'
                            ]
                        ],
                        [
                            'IBLOCK_ID' => $arSection['IBLOCK_ID'],
                            '<=LEFT_BORDER' => $arSection['LEFT_MARGIN'],
                            '>=RIGHT_BORDER' => $arSection['RIGHT_MARGIN'],
                            'ACTIVE' => 'Y',
                            'DEPTH_LEVEL' => 1
                        ],
                        false,
                        ['ID', 'IBLOCK_ID', 'DEPTH_LEVEL', 'NAME', 'SORT']
                    );
                    if ($arSectionTmp) {
                        $arSectionsRoot[$arSectionTmp['ID']] = $arSectionTmp;
                        $arSectionsByRoot[$arSectionTmp['ID']][$arSection['ID']] = $arSection['ID'];
                    }
                }
             }
             //sort root sections
             if ($arSectionsRoot) {
                \Bitrix\Main\Type\Collection::sortByColumn($arSectionsRoot, ["SORT" => [SORT_NUMERIC, SORT_ASC], "ID" => [SORT_NUMERIC, SORT_ASC]]);

                foreach ($arSectionsRoot as $arSection) {
                    $arResult['SECTIONS'][$arSection['ID']] = $arSection;
                    if ($arSectionsByRoot[$arSection['ID']]) {
                        $arTmpItems = [];
                        foreach ($arSectionsByRoot[$arSection['ID']] as $key => $id) {
                            $arSectionPath = [$arSection['NAME']];
                            if (!$arTmpItems && $arItems[$id]) {
                                $arTmpItems = $arItems[$id];
                            } elseif ($arTmpItems && $arItems[$id]) {
                                $arTmpItems = array_merge($arTmpItems, $arItems[$id]);
                            }
                            $arSectionPath[] = $arSectionsDepth[$id]['NAME'];
                            $arResult['SECTIONS'][$arSection['ID']]['SECTION_PATH_ID'][$id] = implode('<span>&mdash;</span>', $arSectionPath);
                            $arResult['SECTIONS'][$arSection['ID']]['SECTION_PATH'] = implode('<span>&mdash;</span>', $arSectionPath);
                        }
                        $arResult['SECTIONS'][$arSection['ID']]['ITEMS'] = $arTmpItems;
                    } else {
                        $arSectionPath = [$arSection['NAME']];
                        $arResult['SECTIONS'][$arSection['ID']]['ITEMS'] = $arItems[$arSection['ID']];
                        $arResult['SECTIONS'][$arSection['ID']]['SECTION_PATH'] = implode('<span>&mdash;</span>', $arSectionPath);
                    }
                    $arResult['SECTIONS'][$arSection['ID']]['ITEMS_COUNT'] = count($arResult['SECTIONS'][$arSection['ID']]['ITEMS']);
                }
             }
        }
    } else {
        $arResult['SECTIONS'][0]['ITEMS'] = $arResult['ITEMS'];
        $arResult['SECTIONS'][0]['ITEMS_COUNT'] = 0;
        unset($arResult['ITEMS']);
    }
    global $arRegion;
    if ($arRegion) {
        if ($arRegion['LIST_PRICES']) {
            if (reset($arRegion['LIST_PRICES']) != 'component') {
                $arParams['PRICE_CODE'] = array_keys($arRegion['LIST_PRICES']);
            }
        }
    }

    if ($arParams['PRICE_CODE']) {
        foreach ($arParams['PRICE_CODE'] as $key => $price) {
            if (!$price) {
                unset($arParams['PRICE_CODE'][$key]);
            }
        }
    }
}

function _GetElementSectionsArray($ID){
    $arSections = array();
    $resGroups = CIBlockElement::GetElementGroups($ID, true, array("ID"));
    while($arGroup = $resGroups->Fetch()){
        $arSections[] = $arGroup["ID"];
    }
    return (!$arSections ? false : (count($arSections) == 1 ? current($arSections) : $arSections));
}

$arParams['FILTER_NAME'] = ($arParams['FILTER_NAME'] ? $arParams['FILTER_NAME'] : 'arGoodsFilter');
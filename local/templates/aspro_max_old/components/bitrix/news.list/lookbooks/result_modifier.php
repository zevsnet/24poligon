<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?if ($arResult['ITEMS']) {
    $sections = array();

    foreach($arResult['ITEMS'] as &$arItem) {
        if( !isset($sections[ $arItem['IBLOCK_SECTION_ID'] ]) ) {
            $res = CIBlockSection::GetNavChain($arParams['IBLOCK_ID'], $arItem['IBLOCK_SECTION_ID'], array('ID', 'NAME'));
            while($section = $res->Fetch()) {
                $sections[ $arItem['IBLOCK_SECTION_ID'] ] .= $section['NAME'].($section['ID'] == $arItem['IBLOCK_SECTION_ID'] ? '' : '&nbsp;&nbsp;<span>&mdash;</span>&nbsp;&nbsp;');
            }
        }
        
        $arItem['SECTION_PATH'] = $sections[ $arItem['IBLOCK_SECTION_ID'] ];
    }
    unset($arItem);

}?>
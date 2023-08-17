<?
/*if( !function_exists('sort_by_basket') ){
    function sort_by_basket($a, $b){
        if ($a['IN_BASKET'] == $b['IN_BASKET']) {
            return 0;
        }
        return ($a['IN_BASKET'] > $b['IN_BASKET']) ? -1 : 1;
    }
}

$services_in_basket = isset($arParams['SERVICES_IN_BASKET']) && is_array($arParams['SERVICES_IN_BASKET']) && count($arParams['SERVICES_IN_BASKET'])>0;

if($services_in_basket){
    $arServices = $arParams['SERVICES_IN_BASKET'];
    $counter = 0;
    foreach($arResult["ITEMS"] as $key => $arItem){
        if(isset($arServices[$arItem['ID']])){
            $arResult["ITEMS"][$key]['IN_BASKET'] = $counter;
            $counter--;
        } else {
            $arResult["ITEMS"][$key]['IN_BASKET'] = -9999999;
        }
    }
    uasort($arResult["ITEMS"], 'sort_by_basket');
}
*/
?>
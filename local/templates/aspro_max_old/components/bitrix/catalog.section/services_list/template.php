<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?use \Bitrix\Main\Web\Json,
    Bitrix\Main\Localization\Loc;?>
    
<?
$showElements = true;
$showAllInSlide = isset($arParams["SHOW_ALL_IN_SLIDE"]) && $arParams["SHOW_ALL_IN_SLIDE"] === 'Y';

$displayCount = isset($arParams["COUNT_SERVICES_IN_ANNOUNCE"]) ? (int)$arParams["COUNT_SERVICES_IN_ANNOUNCE"] : 0;
$count_in_basket = isset($arParams['SERVICES_IN_BASKET']) && is_array($arParams['SERVICES_IN_BASKET']) ? count($arParams['SERVICES_IN_BASKET']) : 0;
if($count_in_basket > 0){
    $displayCount = max($displayCount, $count_in_basket);
}

$noMoreElements = $displayCount && is_array($arResult["ITEMS"]) && $displayCount >= count($arResult["ITEMS"]);
if( !$showAllInSlide && $noMoreElements ) {
    $showElements = false;
}

$bShowOldPrice = $arParams["SHOW_OLD_PRICE"] !== 'N';
?>
<?if($arResult["ITEMS"] && $showElements):?>
    <?
    $basketUrl = CMax::GetFrontParametrValue('BASKET_PAGE_URL');
    $bCompact = isset($arParams["COMPACT_MODE"]) && $arParams["COMPACT_MODE"] === 'Y';
    ?>
    
    <div class="content_wrapper_block <?=$templateName;?> services_in_product <?=($bCompact ? 'services_compact' : '')?>">		
        <div class="services-items">
            <?            
            $counter = 1;
            if($count_in_basket > 0){
                $count_to_display = $displayCount - $count_in_basket >= 0 ? $displayCount - $count_in_basket : 0 ;
            } else {
                $count_to_display = $displayCount;
            }
            ?>
            <?foreach($arResult["ITEMS"] as $key => $arItem){?>
                <?$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));

                $item_id = $arItem["ID"];
                $strMeasure = '';                

                $str_postfix = (isset($arParams["PLACE_ID"]) ? '_'.$arParams["PLACE_ID"] : '');
                $arItem["strMainID"] = $this->GetEditAreaId($arItem['ID'])."_serv".$str_postfix;
                $arItemIDs=CMax::GetItemsIDs($arItem);

                $services_in_basket = isset($arParams['SERVICES_IN_BASKET'][$arItem['ID']]) && is_array($arParams['SERVICES_IN_BASKET'][$arItem['ID']]) && count($arParams['SERVICES_IN_BASKET'][$arItem['ID']])>0;

                $elementName = ((isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arItem['NAME']);                
                ?>

                <div class="services-item bordered rounded3 js-notice-block <?=($services_in_basket ? 'services_on' : '')?> <?=( !$services_in_basket && $showAllInSlide && $counter > $count_to_display ? 'hide_service' : 'show_service' )?> <?=($services_in_basket && $showAllInSlide ? 'order_top_service' : '')?>" data-item_id ="<?=$arItem["ID"]?>">
                    <div class="services-item__wrapper colored_theme_block_text" id="<?=$this->GetEditAreaId($arItem['ID']);?>_list_services">
                        <div class="services-item__inner flexbox flexbox--row">
                            <div class="services-item__info item_info">
                                <div class="switch_block onoff filter">
                                    <input type="checkbox" name="buy_switch_services" id="<?=$arItem["strMainID"].'_switch'?>" <?=($services_in_basket ? 'checked' : '')?>>
                                    <label for="<?=$arItem["strMainID"].'_switch'?>"> &nbsp;</label>
                                </div>
                                <div class="services-item__title js-notice-block__title">
                                    <span class="dark-color dotted" data-event="jqm" data-no-mobile="Y" data-param-form_id="fast_view_services" data-param-item_href=<?=urlencode($arItem["DETAIL_PAGE_URL"]);?> data-name="fast_view_services" data-param-iblock_id="<?=$arItem["IBLOCK_ID"];?>" data-param-id="<?=$arItem["ID"];?>" >
                                        <?=$elementName;?>
                                    </span>
                                </div> 
                                <div class="services-item__buy">
                                    <?
                                    $arAddToBasketData = array();
                                    $totalCount = 999;
                                    $arItem["CAN_BUY"] = true;
                                    $arParams['PRODUCT_PROPERTIES'] = array('BUY_PRODUCT_PROP');
                                    $arParams["PARTIAL_PRODUCT_PROPERTIES"] = 'Y';                                    
                                    
                                    if($services_in_basket){
                                        $arParams["EXACT_QUANTITY"] = $arParams['SERVICES_IN_BASKET'][$arItem['ID']]['QUANTITY'];
                                    } else {
                                        $arParams["EXACT_QUANTITY"] = 0;
                                    }
                                    
                                    $arAddToBasketData = CMax::GetAddToBasketArray($arItem, $totalCount, 1/*$arParams["DEFAULT_COUNT"]*/, $basketUrl, true, $arItemIDs["ALL_ITEM_IDS"], '', $arParams);
                                    ?>
                                    <div class="counter_wrapp services_counter">

                                        <?//if(($arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_DETAIL"] && $arAddToBasketData["ACTION"] == "ADD") && $arAddToBasketData["CAN_BUY"]):?> 
                                        <?$arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_LIST"] = 'Y';?> 
                                            <?=\Aspro\Functions\CAsproMax::showItemCounter($arAddToBasketData, $arItem["ID"], $arItemIDs, $arParams, '', '', true);?>
                                        <?//endif;?>

                                        <div id="<? echo $arItemIDs["ALL_ITEM_IDS"]['BASKET_ACTIONS']; ?>" class="button_block hidden">
                                            <!--noindex-->
                                                <?=$arAddToBasketData["HTML"]?>
                                            <!--/noindex-->
                                        </div>
                                    </div>
                                </div>                          
                            </div>
                            <div class="services-item__cost cost prices clearfix">
                                <?
                                $arParams['SHOW_POPUP_PRICE'] = 'Y';
                                $arParams['ONLY_POPUP_PRICE'] = 'Y';
                                ?>
                                <?if(!$services_in_basket):?>									
                                    <?if($arItem["PRICES"]){?>
                                        <?$min_price_id=0;?>
                                        <?\Aspro\Functions\CAsproMaxItem::showItemPrices($arParams, $arItem["PRICES"], $strMeasure, $min_price_id, 'Y');?>
                                    <?}?>
                                <?else:?>
                                    <div class="price_matrix_wrapper ">
                                        <div class="prices-wrapper">
                                            <div class="price font-bold font_mxs" >
                                                <span class="values_wrapper">
                                                    <span class="price_value"><?=$arParams['SERVICES_IN_BASKET'][$arItem['ID']]['SUM_FORMATED']?></span>
                                                </span>																			
                                            </div>
                                            <?if($bShowOldPrice && $arParams['SERVICES_IN_BASKET'][$arItem['ID']]['NEED_SHOW_OLD_SUM'] === 'Y'):?>
                                                <div class="price discount">
                                                    <span class="values_wrapper font_xs muted">
                                                        <span class="price_value"><?=$arParams['SERVICES_IN_BASKET'][$arItem['ID']]['SUM_FULL_PRICE_FORMATED']?></span>
                                                    </span>
                                                </div>
                                            <?endif;?>
                                        </div>                                        
                                    </div>
                                <?endif;?>

                            </div>
                        </div>
                    </div>
                </div>
                <?
                if(!$services_in_basket)
                    $counter++;
                ?>
            <?}?>
        </div>
        <?$needMoreServices = (int)$arResult['NAV_RESULT']->NavPageCount > 1;?>
        <?if(isset($arParams["SHOW_BUTTON_ALL"]) && $arParams["SHOW_BUTTON_ALL"] === 'Y' && $needMoreServices):?>
            <div class="more-services-link"><span class="choise colored_theme_text_with_hover font_sxs dotted" data-block=".js-scroll-services"><?=Loc::getMessage('ALL_BUY_SERVICES');?></span></div>
        <?endif;?>
        
        <?if(isset($arParams["SHOW_ALL_IN_SLIDE"]) && $arParams["SHOW_ALL_IN_SLIDE"] === 'Y' && !$noMoreElements):?>
            <div class="more-services-slide" data-open="<?=Loc::getMessage('ALL_BUY_SERVICES')?>" data-close="<?=Loc::getMessage('HIDE_BUY_SERVICES')?>"><span class="colored_theme_text_with_hover font_sxs dotted" ><?=Loc::getMessage('ALL_BUY_SERVICES');?></span></div>
        <?endif;?>
    </div>
<?endif;?>

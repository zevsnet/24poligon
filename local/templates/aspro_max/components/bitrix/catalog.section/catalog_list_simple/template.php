<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?use \Bitrix\Main\Web\Json;?>
<?if($arResult["ITEMS"]):?>
    <?
    $currencyList = '';
    if (!empty($arResult['CURRENCIES'])) {
        $templateLibrary[] = 'currency';
        $currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
    }
    $templateData = array(
        'TEMPLATE_LIBRARY' => $templateLibrary,
        'CURRENCIES' => $currencyList
    );
    unset($currencyList, $templateLibrary);
    ?>
    <div class="content_wrapper_block <?=$templateName;?>">		
        <div class="block-items">
            <?foreach($arResult["ITEMS"] as $key => $arItem){?>
                <?$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));

                $item_id = $arItem["ID"];
                $strMeasure = '';                

                $arItem["strMainID"] = $this->GetEditAreaId($arItem['ID'])."_fav";
                $arItemIDs=CMax::GetItemsIDs($arItem);

                if ($arParams["SHOW_MEASURE"] == "Y" && $arItem["CATALOG_MEASURE"]) {
                    if (isset($arItem["ITEM_MEASURE"]) && (is_array($arItem["ITEM_MEASURE"]) && $arItem["ITEM_MEASURE"]["TITLE"])) {
                        $strMeasure = $arItem["ITEM_MEASURE"]["TITLE"];
                    } else {
                        $arMeasure = CCatalogMeasure::getList(array(), array("ID" => $arItem["CATALOG_MEASURE"]), false, false, array())->GetNext();
                        $strMeasure = $arMeasure["SYMBOL_RUS"];
                    }
                }

                $elementName = ((isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arItem['NAME']);?>

                <div class="block-item bordered">
                    <div class="block-item__wrapper colored_theme_block_text" id="<?=$this->GetEditAreaId($arItem['ID']);?>_list_simple">
                        <div class="block-item__inner flexbox flexbox--row">
                            <div class="block-item__image block-item__image--wh80">
                                <div class="image-wrapper flexbox">
                                    <?\Aspro\Functions\CAsproMaxItem::showImg($arParams, $arItem, false);?>
                                </div>
                            </div>
                            <div class="block-item__info item_info">
                                <div class="block-item__title">
                                    <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="dark_link font_xs option-font-bold colored_theme_el_text"><span><?=$elementName;?></span></a>
                                </div>
                                <div class="cost prices clearfix hidden">
                                    <?if( $arItem["OFFERS"]){?>
                                        <div class="with_matrix <?=($arParams["SHOW_OLD_PRICE"]=="Y" ? 'with_old' : '');?>" style="display:none;">
                                            <div class="price price_value_block"><span class="values_wrapper"></span></div>
                                            <?if($arParams["SHOW_OLD_PRICE"]=="Y"):?>
                                                <div class="price discount"></div>
                                            <?endif;?>
                                            <?if($arParams["SHOW_DISCOUNT_PERCENT"]=="Y"){?>
                                                <div class="sale_block matrix" style="display:none;">
                                                    <div class="sale_wrapper">
                                                        <?if($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] != "Y"):?>
                                                            <div class="text">
                                                                <span class="title"><?=GetMessage("CATALOG_ECONOMY");?></span>
                                                                <span class="values_wrapper"></span>
                                                            </div>
                                                        <?else:?>
                                                            <div class="value">-<span></span>%</div>
                                                            <div class="text">
                                                                <span class="title"><?=GetMessage("CATALOG_ECONOMY");?></span>
                                                                <span class="values_wrapper"></span>
                                                            </div>
                                                        <?endif;?>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                </div>
                                            <?}?>
                                        </div>
                                        <div class="js_price_wrapper">
                                            <?if($arCurrentSKU){?>
                                                <?
                                                $item_id = $arCurrentSKU["ID"];
                                                $arCurrentSKU['PRICE_MATRIX'] = $arCurrentSKU['PRICE_MATRIX_RAW'];
                                                $arCurrentSKU['CATALOG_MEASURE_NAME'] = $arCurrentSKU['MEASURE'];
                                                if(isset($arCurrentSKU['PRICE_MATRIX']) && $arCurrentSKU['PRICE_MATRIX']) // USE_PRICE_COUNT
                                                {?>
                                                    <?if($arCurrentSKU['ITEM_PRICE_MODE'] == 'Q' && count($arCurrentSKU['PRICE_MATRIX']['ROWS']) > 1):?>
                                                        <?=CMax::showPriceRangeTop($arCurrentSKU, $arParams, GetMessage("CATALOG_ECONOMY"));?>
                                                    <?endif;?>
                                                    <?=CMax::showPriceMatrix($arCurrentSKU, $arParams, $strMeasure, $arAddToBasketData);?>
                                                    <?$arMatrixKey = array_keys($arCurrentSKU['PRICE_MATRIX']['MATRIX']);
                                                    $min_price_id=current($arMatrixKey);?>
                                                <?
                                                }
                                                else
                                                {
                                                    $arCountPricesCanAccess = 0;
                                                    $min_price_id=0;?>
                                                    <?\Aspro\Functions\CAsproMaxItem::showItemPrices($arParams, $arCurrentSKU["PRICES"], $strMeasure, $min_price_id, ($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] == "Y" ? "N" : "Y"));?>
                                                <?}?>
                                            <?}else{?>
                                                    <?\Aspro\Functions\CAsproMaxSku::showItemPrices($arParams, $arItem, $item_id, $min_price_id, array(), ($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] == "Y" ? "N" : "Y"));?>
                                            <?}?>
                                        </div>
                                    <?}else{?>
                                        <?
                                        $item_id = $arItem["ID"];
                                        if(isset($arItem['PRICE_MATRIX']) && $arItem['PRICE_MATRIX']) // USE_PRICE_COUNT
                                        {?>
                                            <?if($arItem['ITEM_PRICE_MODE'] == 'Q' && count($arItem['PRICE_MATRIX']['ROWS']) > 1):?>
                                                <?=CMax::showPriceRangeTop($arItem, $arParams, GetMessage("CATALOG_ECONOMY"));?>
                                            <?endif;?>
                                            <?=CMax::showPriceMatrix($arItem, $arParams, $strMeasure, $arAddToBasketData);?>
                                            <?$arMatrixKey = array_keys($arItem['PRICE_MATRIX']['MATRIX']);
                                            $min_price_id=current($arMatrixKey);?>
                                        <?
                                        }
                                        elseif($arItem["MIN_PRICE"])
                                        {
                                            $arCountPricesCanAccess = 0;
                                            $min_price_id=0;?>
                                            <?\Aspro\Functions\CAsproMaxItem::showItemPrices($arParams, $arItem["PRICES"], $strMeasure, $min_price_id, ($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] == "Y" ? "N" : "Y"));?>
                                        <?}?>
                                    <?}?>
                                </div>
                                <div class="block-item__cost cost prices clearfix">
                                    <?$arParams['SHOW_POPUP_PRICE'] = 'N'?>
									<?if($arItem["OFFERS"]):?>
										<?\Aspro\Functions\CAsproMaxSku::showItemPrices($arParams, $arItem, $item_id, $min_price_id, array(), 'Y');?>
									<?else:?>
										<?
										if(isset($arItem['PRICE_MATRIX']) && $arItem['PRICE_MATRIX']) // USE_PRICE_COUNT
										{?>
											<?if($arItem['ITEM_PRICE_MODE'] == 'Q' && count($arItem['PRICE_MATRIX']['ROWS']) > 1):?>
												<?=CMax::showPriceRangeTop($arItem, $arParams, GetMessage("CATALOG_ECONOMY"));?>
											<?endif;?>
											<?=CMax::showPriceMatrix($arItem, $arParams, $strMeasure, $arAddToBasketData);?>
										<?
										}
										elseif($arItem["PRICES"])
										{?>
											<?\Aspro\Functions\CAsproMaxItem::showItemPrices($arParams, $arItem["PRICES"], $strMeasure, $min_price_id, 'Y');?>
										<?}?>
									<?endif;?>
								</div>
                            </div>
                        </div>
                    </div>
                </div>
            <?}?>
        </div>
    </div>
<?else:?>
    <script>
        $('div[data-id="<?=$arParams['FILTER_ELEMENT']?>"] .col-md-9').removeClass('col-md-9').addClass('col-md-12')
        $('div[data-id="<?=$arParams['FILTER_ELEMENT']?>"] .col-md-3').remove()
    </script>
<?endif;?>

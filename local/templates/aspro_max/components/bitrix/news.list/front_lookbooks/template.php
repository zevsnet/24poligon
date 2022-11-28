<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<?use \Bitrix\Main\Localization\Loc;?>
<?if($arResult['ITEMS'] || $arResult['SECTIONS']):?>
    <div class="content_wrapper_block <?=$templateName;?>">
        <div class="maxwidth-theme only-on-front">
            <div class="tab_slider_wrapp lookbooks">
                <?if($arParams['TITLE_BLOCK'] || $arParams['TITLE_BLOCK_ALL']):?>
                    <div class="top_block">
                        <h3><?=$arParams['TITLE_BLOCK'];?></h3>
                        <div class="right_block_wrapper">
                            <?if ($arResult['SECTIONS']):?>
                                <div class="tabs-wrapper <?=$arParams['TITLE_BLOCK_ALL'] && $arParams['ALL_URL'] ? 'with_link' : ''?>">
                                    <ul class="tabs">
                                        <?$i=0;
                                        foreach($arResult["SECTIONS"] as $arSection):?>
                                            <li data-code="<?=$arSection['ID']?>" class="font_xs<?=(!$i ? ' cur active' : '')?>"><span class="muted777"><?=$arSection["NAME"];?></span></li>
                                            <?$i++;?>
                                        <?endforeach;?>
                                    </ul>
                                </div>
                            <?endif;?>
                            <?if($arParams['TITLE_BLOCK_ALL'] && $arParams['ALL_URL']):?>
                                <a href="<?=$arParams['ALL_URL'];?>" class="font_upper muted"><?=$arParams['TITLE_BLOCK_ALL'];?></a>
                            <?endif;?>
                        </div>
                    </div>
                <?endif;?>
                <?global $arTheme;?>
                <?if ($arResult['SECTIONS']):?>
                    <?$i = 0;?>
                    <?foreach ($arResult['SECTIONS'] as $arSection):?>
                        <div class="lookbook-wrapper mobile-adaptive <?=($i ? 'lookbook--hidden' : '')?>" data-code="<?=$arSection['ID']?>">
                            <?$i++?>
                            <?if ($arSection['ITEMS_COUNT'] > 1):?>
                                <div class="flexslider flexslider-init flexslider--absolute">
                                    <ul class="flex-direction-nav">
                                        <li class="flex-nav-prev"><span class="flex-prev js-click"></span></li><li class="flex-nav-next"><span class="flex-next js-click"></span></li>
                                    </ul>
                                </div>
                            <?endif;?>
                            <div class="swipeignore mobile-overflow c_<?=count($arSection['ITEMS']);?> mobile-margin-16 mobile-compact">
                                <?foreach($arSection['ITEMS'] as $j => $arItem):?>
                                    <?
                                    // edit/add/delete buttons for edit mode
                                    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
                                    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => Loc::getMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                                    // use detail link?
                                    $bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen($arItem['DETAIL_TEXT']) ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1) : true);

                                    // preview image
                                    $arItemImage = (strlen($arItem['FIELDS']['PREVIEW_PICTURE']['SRC']) ? $arItem['FIELDS']['PREVIEW_PICTURE'] : $arItem['FIELDS']['DETAIL_PICTURE']);
                                    $arImage = ($arItemImage ? CFile::ResizeImageGet($arItemImage, array('width' => 500, 'height' => 500), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true) : array());
                                    $imageSrc = ($arItemImage ? $arImage['src'] : '');

                                    $bPreviewText = (isset($arItem['PREVIEW_TEXT']) && strlen($arItem['PREVIEW_TEXT']));

                                    if (!$imageSrc) {
                                        $imageSrc = SITE_TEMPLATE_PATH.'/images/svg/noimage_content.svg';
                                    }?>
                                    <div class="bordered rounded-3 lookbook <?=($j ? '' : 'lookbook--active')?> item-width-322" id="<?=$this->GetEditAreaId($arItem['ID']);?>" data-id="<?=$arItem['ID']?>">
                                        <div class="item-views">
                                            <div class="row margin0 flexbox">
                                                <div class="col-md-<?=($arItem['PROPERTIES']['LINK_GOODS']['VALUE'] ? '9 col-xs-7' : '')?> col-xxs-12">
                                                    <div class="row margin0 flexbox">
                                                        <div class="col-md-6 col-xxs-12">
                                                            <div class="lookbook__picture-wrapper lookbook--pl-49 lookbook--pt-49 lookbook--pb-49">
                                                                <?if($bDetailLink):?><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?endif;?>
                                                                <?
                                                                $a_alt = (is_array($arItem["PREVIEW_PICTURE"]) && strlen($arItem["PREVIEW_PICTURE"]['DESCRIPTION']) ? $arItem["PREVIEW_PICTURE"]['DESCRIPTION'] : ($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $arItem["NAME"] ));
                                                                $a_title = (is_array($arItem["PREVIEW_PICTURE"]) && strlen($arItem["PREVIEW_PICTURE"]['DESCRIPTION']) ? $arItem["PREVIEW_PICTURE"]['DESCRIPTION'] : ($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] : $arItem["NAME"] ));
                                                                ?>
                                                                <img data-src="<?=$imageSrc;?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($imageSrc);?>" class="lookbook__picture lazy img-responsive lookbook--mh-500" alt="<?=$a_alt;?>" title="<?=$a_title;?>" />
                                                                <?if($bDetailLink):?></a><?endif;?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-xxs-12">
                                                            <div class="scrollbars scroll-deferred to-text lookbook--mh-600">
                                                                <div class="lookbook__info lookbook--pl-49 lookbook--pt-49 lookbook--pb-49 lookbook--pr-49">
                                                                    <div class="lookbook__info-wrapper">
                                                                        <?if ($arSection['SECTION_PATH']):?>
                                                                            <?$sectionPath = ($arSection['SECTION_PATH_ID'] && $arSection['SECTION_PATH_ID'][$arItem['IBLOCK_SECTION_ID']] ? $arSection['SECTION_PATH_ID'][$arItem['IBLOCK_SECTION_ID']] : $arSection['SECTION_PATH'])?>
                                                                            <div class="lookbook__info-section font_upper"><?=$sectionPath?></div>
                                                                        <?endif;?>
                                                                        <div class="lookbook__info-title font_lg option-font-bold">
                                                                            <?if($bDetailLink):?><a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="dark_link"><?endif;?>
                                                                            <?=$arItem['NAME'];?>
                                                                            <?if($bDetailLink):?></a><?endif;?>
                                                                        </div>
                                                                        <?$bShowDopText = ($bPreviewText || $arItem['DISPLAY_PROPERTIES'])?>
                                                                        <?if($bShowDopText):?>
                                                                            <div class="lookbook__info-text-more">
                                                                                <?if($bPreviewText):?>
                                                                                    <div class="lookbook__info-text">
                                                                                        <div class="darken"><?=$arItem['PREVIEW_TEXT']?></div>
                                                                                    </div>
                                                                                <?endif?>
                                                                                <?if($arItem['DISPLAY_PROPERTIES']):?>
                                                                                    <div class="lookbook__info-properties properties list">
                                                                                        <div class="properties__container properties">
                                                                                            <?foreach ($arItem['DISPLAY_PROPERTIES'] as $arProp):?>
                                                                                                <div class="properties__item lookbook__info-properties--mt-5 font_xs">
                                                                                                    <div class="properties__title muted properties__item--inline"><?=$arProp['NAME']?></div>
                                                                                                    <div class="properties__hr muted properties__item--inline">&mdash;</div>
                                                                                                    <div class="properties__value darken properties__item--inline"><?=$arProp['VALUE']?></div>
                                                                                                </div>
                                                                                            <?endforeach;?>
                                                                                        </div>
                                                                                    </div>
                                                                                <?endif?>
                                                                            </div>
                                                                        <?endif?>
                                                                    </div>
                                                                    <?if($bShowDopText):?>
                                                                        <div class="lookbook__info-toggle-wrapper">
                                                                            <span class="lookbook__info-toggle font_xs dotted" data-hide="<?=\Bitrix\Main\Localization\Loc::getMessage('DETAIL_LOOKBOOK_HIDE_TEXT');?>">
                                                                                <?=\Bitrix\Main\Localization\Loc::getMessage('DETAIL_LOOKBOOK_SHOW_TEXT');?>
                                                                            </span>
                                                                        </div>
                                                                    <?endif;?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?if ($arItem['PROPERTIES']['LINK_GOODS']['VALUE'] && $arItem['PROPERTIES']['LINK_GOODS']['LINK_IBLOCK_ID']):?>
                                                    <div class="col-md-3 col-xs-5 lookbook--bl-1 col-xxs-12">
                                                        <div class="scrollbars scroll-deferred lookbook--mh-600">
                                                            <div class="lookbook__items">
                                                                <?$GLOBALS[$arParams['FILTER_NAME']]['ID'] = $arItem['PROPERTIES']['LINK_GOODS']['VALUE']?>
                                                                <?$APPLICATION->IncludeComponent(
                                                                    "bitrix:catalog.section",
                                                                    "catalog_list_simple",
                                                                    [
                                                                        'IBLOCK_ID' => $arItem['PROPERTIES']['LINK_GOODS']['LINK_IBLOCK_ID'],
                                                                        'PRICE_CODE' => $arParams['PRICE_CODE'],
                                                                        'FILTER_NAME' => $arParams['FILTER_NAME'],
                                                                        'PROPERTIES' => [],
                                                                        'SHOW_OLD_PRICE' => 'Y',
                                                                        'CACHE_TYPE' => $arParams['CACHE_TYPE'],
                                                                        'CACHE_TIME' => $arParams['CACHE_TIME'],
                                                                        'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
                                                                        'FILTER_ELEMENT' => $arItem['ID'],
                                                                        'SHOW_ALL_WO_SECTION' => 'Y',
                                                                    ],
                                                                    false, array("HIDE_ICONS"=>"Y")
                                                                );?>
                                                                <?unset($GLOBALS[$arParams['FILTER_NAME']]['ID']);?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?endif;?>
                                            </div>
                                        </div>
                                        <?if($bDetailLink):?>
                                            <!-- noindex -->
                                            <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="btn btn-default btn-lg round-ignore lookbook__info-outerlink">
                                                <?=\Bitrix\Main\Localization\Loc::getMessage('DETAIL_LOOKBOOK_LINK');?>
                                                <?=CMax::showIconSvg("down", SITE_TEMPLATE_PATH.'/images/svg/catalog/arrow_quicklook.svg', '', '', true, false);?>
                                            </a>
                                            <!-- noindex -->
                                        <?endif;?>
                                    </div>
                               <?endforeach;?>
                           </div>
                        </div>
                    <?endforeach;?>
                <?endif;?>
            </div>
        </div>
    </div>
<?endif;?>
<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<? $this->setFrameMode(true); ?>
<? if ($arResult["ITEMS"]) { ?>
    <div class="news_akc_block clearfix">
        <div class="top_block">
            <?
            $title_block = ($arParams["TITLE_BLOCK"] ? $arParams["TITLE_BLOCK"] : GetMessage('AKC_TITLE'));
            $title_all_block = ($arParams["TITLE_BLOCK_ALL"] ? $arParams["TITLE_BLOCK_ALL"] : GetMessage('ALL_AKC'));
            $url = ($arParams["ALL_URL"] ? $arParams["ALL_URL"] : "sale/");
            $count = ceil(count($arResult["ITEMS"]) / 4);
            ?>
            <div class="title_block"><?= $title_block; ?></div>
            <a href="<?= SITE_DIR . $url; ?>"><?= $title_all_block; ?></a>
        </div>
        <? $col = 4;
        if ($arParams["LINE_ELEMENT_COUNT"] >= 3 && $arParams["LINE_ELEMENT_COUNT"] < 4) {
            $col = 3;
        } ?>
        <div class="news_wrapp rows_block">
            <div class="items">
                <? foreach ($arResult["ITEMS"] as $arItem) {
                    if ($key > 3) {
                        continue;
                    }
                    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'],
                        CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'],
                        CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"),
                        array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                    $img_source = '';
                    ?>
                    <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>">

                        <div class="item_block col-<?= $col; ?>">
                            <div id="<?= $this->GetEditAreaId($arItem['ID']); ?>" class="item inner_wrap">
                                <? if ($arItem["DETAIL_PICTURE"]) {
                                    $img_source = $arItem["DETAIL_PICTURE"];
                                } elseif ($arItem["PREVIEW_PICTURE"]) {
                                    $img_source = $arItem["PREVIEW_PICTURE"];
                                } ?>
                                <? if ($img_source) { ?>
                                    <div class="img">
                                        <? $img = CFile::ResizeImageGet($img_source,
                                            array("width" => 400, "height" => 270), BX_RESIZE_IMAGE_EXACT, true, false,
                                            false, 80); ?>
                                        <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>">
                                            <img src="<?= $img["src"] ?>" alt="<?= $arItem["NAME"]; ?>"/>
                                        </a>
                                    </div>
                                <? } ?>
                                <div class="info">
                                    <? if ($arParams["DISPLAY_DATE"] == "Y") { ?>
                                        <? if ($arItem["PROPERTIES"]["PERIOD"]["VALUE"]) { ?>
                                            <div class="date"><?= $arItem["PROPERTIES"]["PERIOD"]["VALUE"] ?></div>
                                        <? } elseif ($arItem["DISPLAY_ACTIVE_FROM"]) { ?>
                                            <div class="date"><?= $arItem["DISPLAY_ACTIVE_FROM"] ?></div>
                                        <? } ?>
                                    <? } ?>
                                    <div class="name" href="<?= $arItem["DETAIL_PAGE_URL"] ?>"><?= $arItem["NAME"] ?>
                                        <br>
                                    </div>

                                    <? if (strlen($arItem['PROPERTIES']["ADDRESS"]['VALUE']) && in_array('ADDRESS',
                                            $arParams["PROPERTY_CODE"])
                                    ): ?>
                                        <div class="store_text">
                                            <span class="title"><?= GetMessage('ADDRESS') ?></span>
                                            <span class="value"><?= $arItem['PROPERTIES']["ADDRESS"]['VALUE'] ?></span>
                                        </div>
                                        <div class="clear"></div>
                                    <? endif; ?>

                                    <? if ($arItem['PROPERTIES']["PHONE"]['VALUE']): ?>
                                        <div class="store_text">
                                            <span class="title"><?= GetMessage('PHONE') ?></span>
                                            <span class="value"><?= $arItem['PROPERTIES']["PHONE"]['VALUE'][0] ?></span>
                                        </div>
                                        <div class="clear"></div>
                                    <? endif; ?>
                                    <? if (strlen($arItem['PROPERTIES']["EMAIL"]['VALUE']) && in_array('EMAIL',
                                            $arParams["PROPERTY_CODE"])
                                    ): ?>
                                        <div class="store_text">
                                            <span class="title"><?= GetMessage('EMAIL') ?></span>
                                            <span class="value"><?= $arItem['PROPERTIES']["EMAIL"]['VALUE'] ?></span>
                                        </div>
                                        <div class="clear"></div>
                                    <? endif; ?>
                                    <? if ($arItem['PROPERTIES']["METRO"]['VALUE'] && in_array('METRO',
                                            $arParams["PROPERTY_CODE"])
                                    ): ?>
                                        <div class="store_text metro">
                                            <? foreach ($arItem['PROPERTIES']["METRO"]['VALUE'] as $metro): ?>
                                                <span class="value"><i></i><?= $metro ?></span>
                                            <? endforeach; ?>
                                        </div>
                                        <div class="clear"></div>
                                    <? endif; ?>
                                    <? if ($arItem['PROPERTIES']["SCHEDULE"]['VALUE'] && strlen($arItem['PROPERTIES']["SCHEDULE"]['VALUE']['TEXT']) && in_array('SCHEDULE',
                                            $arParams["PROPERTY_CODE"])
                                    ): ?>
                                        <div class="store_text">
                                            <span class="title clear"><?= GetMessage('SCHEDULE') ?></span>
                                            <span class="value"><?= ($arItem['PROPERTIES']["SCHEDULE"]['VALUE']['TYPE'] == 'html' ? htmlspecialchars_decode($arItem['PROPERTIES']["SCHEDULE"]['VALUE']['TEXT']) : nl2br($arItem['PROPERTIES']["SCHEDULE"]['VALUE']['TEXT'])) ?></span>
                                        </div>
                                        <div class="clear"></div>
                                    <? endif; ?>
                                </div>
                            </div>
                        </div>

                    </a>
                <? } ?>
            </div>
        </div>
    </div>
    <script>
        $('.news_akc_block .rows_block .item').sliceHeight();
    </script>
<? } ?>
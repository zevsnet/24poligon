<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<? $this->setFrameMode(true); ?>
<? if (count($arResult["ITEMS"])): ?>
    <div class="articles-list lists_block news <?= ($arParams["IS_VERTICAL"] == "Y" ? "vertical row" : "") ?> <?= ($arParams["SHOW_FAQ_BLOCK"] == "Y" ? "faq" : "") ?> ">
        <?
        foreach ($arResult["ITEMS"] as $arItem) {
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'],
                CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'],
                CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"),
                array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

            ?>
            <div class="item clearfix item_block" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                <div class="wrapper_inner_block">

                        <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="thumb"><img src="<?= $arItem["DETAIL_PICTURE"]["SRC"] ?>"
                                                                                       alt="<?= ($arItem["PREVIEW_PICTURE"]["ALT"] ? $arItem["PREVIEW_PICTURE"]["ALT"] : $arItem["NAME"]) ?>"
                                                                                       title="<?= ($arItem["PREVIEW_PICTURE"]["TITLE"] ? $arItem["PREVIEW_PICTURE"]["TITLE"] : $arItem["NAME"]) ?>"/></a>
                    
                    <div class="clear"></div>
                </div>
            </div>
        <? } ?>
    </div>
    <? if ($arParams["DISPLAY_BOTTOM_PAGER"]) { ?><?= $arResult["NAV_STRING"] ?><? } ?>
<? endif; ?>
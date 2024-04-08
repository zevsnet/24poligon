<?
$arResult = $arConfig['ITEM'];
?>

<div class="map_info_store pane_info_wrapper">
    <div class="pane_info clearfix">
        <?if ($arResult['PREVIEW_PICTURE_ID']):?>
            <div class="image"><img src="<?= CFile::GetPath($arResult['PREVIEW_PICTURE_ID']);?>" data-src></span></div>
        <?endif;?>
        <div class="body-info">
            <?if ($arResult['SECTION_NAME']):?>
                <div class="section-name font_xs"><?=$arResult['SECTION_NAME'];?></div>
            <?endif;?>
                <div class="title font_sm bold">
                    <?if($arResult['DETAIL_PAGE_LINK']):?>
                        <a class="dark_link" href="<?=$arResult['DETAIL_PAGE_LINK'];?>"><?=$arResult['NAME'];?></a>
                    <?else :?>
                        <div class="dark_link"><?= $arResult['NAME'];?></div>
                    <?endif;?>
                </div>
            <?if ($arResult['MAP_DOP_INFO']):?>
                <div class="info font_xs muted property"><?=$arResult['MAP_DOP_INFO'];?></div>
            <?endif;?>
        </div>
    </div>
</div>
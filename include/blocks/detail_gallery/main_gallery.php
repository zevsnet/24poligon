<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die();

/**
 * array from \Aspro\Functions\CAsproMax::showBlockHtml
 * 
 * @var array $arOptions = [
 *  'CONFIG' => array,
 *  'ITEMS' => array,
 *  'GALLERY_SETTINGS' => array,
 *  'STICKERS' => string,
 * ]
 */
$arOptions = $arConfig['PARAMS'];

?>

<div class="product-detail-gallery detail-gallery-big <?= $arOptions['CONFIG']['TOP_GALLERY_CLASS']; ?> swipeignore js-notice-block__image width100">
    <div class=" <?= $arOptions['CONFIG']['IS_MAGNIFIER'] ? '' : 'product-detail-gallery-sticky' ?> <?= $arOptions['CONFIG']['COUNT_IMAGES'] > 1 ? 'show-bottom-pagination' : ''?>">
        <div class="product-detail-gallery__container flexbox <?=($arOptions['CONFIG']['IS_VERTICAL_THUMBS'] ? ' product-detail-gallery__container--vertical flexbox--row' : 'flexbox--reverse');?> detail-gallery-big-wrapper">

            <?if($arOptions['CONFIG']['FIRST_SKU_PICTURE']):?>
                <link class="first_sku_picture" href="<?=$arOptions['CONFIG']['FIRST_SKU_PICTURE'];?>"/>
            <?endif;?>

            <link href="<?=$arOptions['CONFIG']['FIRST_PHOTO'];?>" itemprop="image"/>
            
            <div class="gallery-wrapper">
                <? // thumbs gallery ?>
                <?if($arOptions['CONFIG']['SHOW_THUMBS'] && ($arOptions['CONFIG']['COUNT_IMAGES'] > 1 || $arOptions['CONFIG']['POPUPVIDEO'] || $arOptions['CONFIG']['IS_CUSTOM_OFFERS'])):?>
                    <div class="detail-gallery-big-slider-thumbs">
                        <div class="detail-gallery-big-slider-thumbs__inner">
                            <?
                            \Aspro\Functions\CAsproMax::showBlockHtml([
                                'FILE' => 'detail_gallery/thumb_slider.php',
                                'PARAMS' => [
                                    'CONFIG' => $arOptions['CONFIG'],
                                    'GALLERY_SETTINGS' => $arOptions['GALLERY_SETTINGS'],
                                    'ITEMS' => $arOptions['ITEMS'],
                                ],
                            ]);
                            ?>
                            <?
                            \Aspro\Functions\CAsproMax::showBlockHtml([
                                'FILE' => 'detail_gallery/video_icon.php',
                                'PARAMS' => [
                                    'CONFIG' => $arOptions['CONFIG'],
                                ],
                            ]);
                            ?>
                        </div>
                    </div>
                <?endif;?>

                <? // main gallery ?>
                <div class="detail-gallery-big-slider-main">
                    <div class="detail-gallery-big-slider-main__inner">
                        <div class="detail-gallery-big-slider-main__ratio-wrap">
                            <div class="detail-gallery-big-slider-main__ratio-inner">
                                <div class="detail-stickers-wrap <?=$arOptions['CONFIG']['SHOW_THUMBS'] ? '' : 'detail-stickers-wrap--absolute'?>">
                                    <?if($arOptions['CONFIG']['NEED_STICKERS']):?>
                                        <?=$arOptions['STICKERS']?>
                                    <?endif;?>
                                    <?if(!$arOptions['CONFIG']['SHOW_THUMBS']):?>
                                        <?
                                        \Aspro\Functions\CAsproMax::showBlockHtml([
                                            'FILE' => 'detail_gallery/video_icon.php',
                                            'PARAMS' => [
                                                'CONFIG' => $arOptions['CONFIG'],
                                            ],
                                        ]);
                                        ?>
                                    <?endif;?>
                                </div>
                            
                                <?
                                \Aspro\Functions\CAsproMax::showBlockHtml([
                                    'FILE' => 'detail_gallery/big_slider.php',
                                    'PARAMS' => [
                                        'CONFIG' => $arOptions['CONFIG'],
                                        'GALLERY_SETTINGS' => $arOptions['GALLERY_SETTINGS'],
                                        'ITEMS' => $arOptions['ITEMS'],
                                        'MAGNIFIER_MODE' => false,
                                    ],
                                ]);
                                ?>
                            
                                <?if($arOptions['CONFIG']['IS_MAGNIFIER']):?>
                                    <?
                                    \Aspro\Functions\CAsproMax::showBlockHtml([
                                        'FILE' => 'detail_gallery/big_slider.php',
                                        'PARAMS' => [
                                            'CONFIG' => $arOptions['CONFIG'],
                                            'GALLERY_SETTINGS' => $arOptions['GALLERY_SETTINGS'],
                                            'ITEMS' => $arOptions['ITEMS'],
                                            'MAGNIFIER_MODE' => true,
                                        ],
                                    ]);
                                    ?>
                                <?endif;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>	
</div>
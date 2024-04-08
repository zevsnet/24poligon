<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die();

/**
 * array from \Aspro\Functions\CAsproMax::showBlockHtml
 * 
 * @var array $arOptions = [
 *  'CONFIG' => array,
 *  'ITEMS' => array,
 *  'GALLERY_SETTINGS' => array,
 * ]
 */
$arOptions = $arConfig['PARAMS'];
if($arConfig['PARAMS']['MAGNIFIER_MODE']){
    $arOptions['CONFIG']['IS_MAGNIFIER'] = false;
    $arOptions['CONFIG']['VIEW_IMG_TYPE'] = "POPUP";
}
?>

<div class="detail-gallery-big-slider <?= $arOptions['CONFIG']['IS_MAGNIFIER'] ? 'hidden-xs detail-gallery-big-slider--magnifier' : 'short-nav swiper slider-solution'; ?> slider-solution--show-nav-hover <?=$arConfig['PARAMS']['MAGNIFIER_MODE'] ? 'visible-xs' : ''?>" 
data-slide-class-list="<?= $arOptions['GALLERY_SETTINGS']['MAIN']['SLIDE_CLASS_LIST']; ?>"
<? if (!$arOptions['CONFIG']['IS_MAGNIFIER']): ?>
    data-plugin-options='<?= $arOptions['GALLERY_SETTINGS']['MAIN']['PLUGIN_OPTIONS']; ?>'
<? endif; ?>
>
    <?if($arOptions['ITEMS']){?>
        <div class="swiper-wrapper">
            <?foreach($arOptions['ITEMS'] as $i => $arImage){
                if($i && $arOptions['CONFIG']['IS_MAGNIFIER']):?>
                    <?continue;?>
                <?endif;?>
                <?$isEmpty=($arImage["SMALL"]["src"] ? false : true );?>
                <?
                $alt=$arImage["ALT"];
                $title=$arImage["TITLE"];
                ?>
                <div id="photo-<?=$i?>" class="<?= $arOptions['GALLERY_SETTINGS']['MAIN']['SLIDE_CLASS_LIST']; ?> <?=$isEmpty ? 'text-center detail-gallery-big__item--no-image' : ''?>">
                    <?if(!$isEmpty){?>
                        <a href="<?=($arOptions['CONFIG']['VIEW_IMG_TYPE'] == "POPUP" ? $arImage["BIG"]["src"] : "javascript:void(0)");?>" <?=($arOptions['CONFIG']['IS_MAGNIFIER'] ? '' : 'data-fancybox="'.($arOptions['CONFIG']['IS_FAST_VIEW'] ? 'gallery_fast' : 'gallery').'" data-thumb="'.$arImage["THUMB"]["src"].'"');?> class="detail-gallery-big__link <?=($arOptions['CONFIG']['VIEW_IMG_TYPE'] == "POPUP" ? "popup_link fancy" : "fancy_zoom");?> <?=$arOptions['CONFIG']['IS_MAGNIFIER'] ? 'line_link' : ''?>" title="<?=$title;?>">
                            <img class="detail-gallery-big__picture rounded3 <?=($arOptions['CONFIG']['IS_MAGNIFIER'] ? 'zoom_picture' : '')?>" <?=($arOptions['CONFIG']['IS_MAGNIFIER'] ? 'data-xoriginal="'.$arImage["BIG"]["src"].'" data-xoriginalwidth="'.$arImage["BIG"]["width"].'" data-xoriginalheight="'.$arImage["BIG"]["height"].'"' : '');?> <?=($i===0 ? 'data-src=""' : '')?> src="<?=$arImage["SMALL"]["src"]?>" data-xpreview="<?=$arImage['THUMB']['src'];?>"  alt="<?=$alt;?>" title="<?=$title;?>"/>
                        </a>
                    <?}else{?>
                        <span class="detail-gallery-big__link <?=$arOptions['CONFIG']['IS_MAGNIFIER'] ? 'line_link' : ''?>" >
                            <img class="detail-gallery-big__picture one rounded3 " src="<?=$arImage["SRC"]?>" alt="<?=$alt;?>" title="<?=$title;?>" />
                        </span>
                    <?}?>
                </div>
            <?}?>
        </div>
        
        <?if(!$arOptions['CONFIG']['IS_MAGNIFIER']):?>
            <div class="swiper-button-prev swiper-nav swiper-nav--hide-600"></div>
            <div class="swiper-button-next swiper-nav swiper-nav--hide-600"></div>
        <?endif;?>
    <?}?>
</div>
<?if(!$arOptions['CONFIG']['IS_MAGNIFIER']):?>
    <div class="swiper-pagination <?if($arOptions['CONFIG']['SHOW_THUMBS']):?>visible-xs<?endif;?> swiper-pagination--bottom swiper-pagionation-bullet--line-to-600"></div>
<?endif;?>
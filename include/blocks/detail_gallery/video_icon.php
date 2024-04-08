<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die();

/**
 * array from \Aspro\Functions\CAsproMax::showBlockHtml
 * 
 * @var array $arOptions = [
 *  'CONFIG' => array,
 * ]
 */
$arOptions = $arConfig['PARAMS'];
?>
<?if($arOptions['CONFIG']['POPUPVIDEO']):?>
    <div class="video-block popup_video sm"><a class="various video_link image dark_link" href="<?=$arOptions['CONFIG']['POPUPVIDEO'];?>" title="<?=\Bitrix\Main\Localization\Loc::getMessage("VIDEO")?>"><span class="play text-upper font_xs"><?//=Loc::getMessage("VIDEO")?></span></a></div>
<?endif;?>
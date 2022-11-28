<?php


namespace Poligon\Core\Aspro;


use Aspro\Functions\CAsproMax;
use Bitrix\Main\Loader;

if(Loader::includeModule('aspro.max')){
    return true;
}
class CAsproMaxCustom extends CAsproMax
{

    public static function showCalculateDeliveryBlock($productId, $arParams, $bSkipPreview = false){
        ?>
        <?if($productId > 0 && $arParams['CALCULATE_DELIVERY'] !== 'NOT'):?>
            <?
            $bIndexBot = (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && strpos($_SERVER['HTTP_USER_AGENT'], 'Lighthouse') !== false); // is indexed yandex/google bot
            $bWithPreview = $arParams['CALCULATE_DELIVERY'] === 'WITH_PREVIEW' && !$bSkipPreview && !$bIndexBot;
            ?>
            <?ob_start();?>
            <div class="calculate-delivery text-form muted777 muted ncolor<?=($bWithPreview ? ' with_preview' : '')?>">
                <?=\CMax::showIconSvg('delivery_calc', SITE_TEMPLATE_PATH.'/images/svg/catalog/delivery_calc.svg', '', '', true, false);?>
                <a class="dotted font_sxs" href="#delivery"><?=$arParams['EXPRESSION_FOR_CALCULATE_DELIVERY']?></a>
            </div>
            <?
            $html = ob_get_contents();
            ob_end_clean();

            echo $html;
            ?>
        <?endif;?>
        <?
    }
}
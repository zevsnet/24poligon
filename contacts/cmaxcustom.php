<?php

namespace Poligon\Core\Aspro;

use Bitrix\Main\Loader;
use CMax;

if (Loader::includeModule('aspro.max')) {
    return true;
}

class CMaxCustom extends CMax
{
    public static function showContactAddrContact(
        $txt = '',
        $wrapTable = true,
        $class = '',
        $icon = 'Addres_black.svg',
        $subclass = ''
    ) {
        global $arRegion, $APPLICATION;
        $iCalledID = ++$caddr_call;
        $bAddr = ($arRegion ? $arRegion['PROPERTY_ADDRESS_VALUE']['TEXT'] : self::checkContentFile(SITE_DIR . 'include/contacts-site-address.php'));
        $bRegionContact = (\Bitrix\Main\Config\Option::get(self::moduleID, 'SHOW_REGION_CONTACT', 'N') == 'Y');
        ?>
        <? if ($arRegion): ?>
            <? $frame = new \Bitrix\Main\Page\FrameHelper('header-allcaddr-block' . $iCalledID); ?>
            <? $frame->begin(); ?>
        <? endif; ?>
        <? if ($arRegion['PROPERTY_REGION_TAG_ADDRESS_MAIN_VALUE']): ?>
            <div class="property address">
                <div class="title font_upper muted"><?= 'Основной офис'; ?></div>
                <? if ($arRegion && $bRegionContact): ?>
                    <div itemprop="address" class="">
                        <?= $arRegion['PROPERTY_REGION_TAG_ADDRESS_MAIN_VALUE']['TEXT']; ?>
                    </div>
                <? endif; ?>
            </div>
        <? endif; ?>

        <?/* if ($bAddr):?>
            <div class="property address">
                <div class="title font_upper muted"><?= $txt; ?></div>
                <? if ($arRegion && $bRegionContact): ?>

                    <div itemprop="address" class="<?= ($class ? ' value darken ' . $class : '') ?>">
                        <?= $arRegion['PROPERTY_ADDRESS_VALUE']['TEXT']; ?>
                    </div>
                <? else: ?>
                    <div itemprop="address"
                         class="value darken"><? $APPLICATION->IncludeFile(SITE_DIR . "include/contacts-site-address.php",
                            Array(), Array("MODE" => "html", "NAME" => "address")); ?></div>
                <? endif; ?>
            </div>
        <? endif;*/ ?>
        <? if ($arRegion): ?>
            <? $frame->end(); ?>
        <? endif; ?>
        <?
    }

    public static function showContactSchedule(
        $txt = '',
        $wrapTable = true,
        $class = '',
        $icon = 'WorkingHours_lg.svg',
        $subclass = ''
    ) {
        global $arRegion, $APPLICATION;
        $iCalledID = ++$cshc_call;
        $bRegionContact = (\Bitrix\Main\Config\Option::get(self::moduleID, 'SHOW_REGION_CONTACT', 'N') == 'Y');
        $bAddr = ($arRegion && $bRegionContact && $arRegion['PROPERTY_REGION_TAG_SHEDULLE_VALUE']['TEXT'] ? $arRegion['PROPERTY_REGION_TAG_SHEDULLE_VALUE']['TEXT'] : self::checkContentFile(SITE_DIR . 'include/contacts-site-schedule.php'));
        ?>
        <? if ($arRegion): ?>
            <? $frame = new \Bitrix\Main\Page\FrameHelper('header-allcaddr-block' . $iCalledID); ?>
            <? $frame->begin(); ?>
        <? endif; ?>
        <? if ($bAddr): ?>
            <div class="property schedule">
                <div class="title font_upper muted"><?= $txt; ?></div>
                <? if ($arRegion && $arRegion['PROPERTY_REGION_TAG_SHEDULLE_VALUE']['TEXT'] && $bRegionContact): ?>
                    <div class="<?= ($class ? ' value darken ' . $class : '') ?>">
                        <?= $arRegion['PROPERTY_REGION_TAG_SHEDULLE_VALUE']['TEXT']; ?>
                        <div class="social-block">
                            <div class="wrap">
                            </div>
                        </div>
                    </div>
                <? else: ?>
                    <div class="value darken"><? $APPLICATION->IncludeFile(SITE_DIR . "include/contacts-site-schedule.php",
                            Array(), Array("MODE" => "html", "NAME" => "schedule")); ?></div>
                <? endif; ?>
            </div>
        <? endif; ?>
        <? if ($arRegion): ?>
            <? $frame->end(); ?>
        <? endif; ?>
        <?
    }

    public static function showContactPhonesOpt($txt = '', $wrapTable = true, $class = '', $icon = 'Phone_black2.svg', $subclass = ''){
        static $cphones_call;
        global $arRegion, $APPLICATION;

        $iCalledID = ++$cphones_call;
        $iCountPhones = ($arRegion ? count($arRegion['PHONES']) : self::checkContentFile(SITE_DIR.'include/contacts-site-phone-one.php'));
        $bRegionContact = (\Bitrix\Main\Config\Option::get(self::moduleID, 'SHOW_REGION_CONTACT', 'N') == 'Y');

        if($arRegion){
            $frame = new \Bitrix\Main\Page\FrameHelper('header-allcphones-block'.$iCalledID);
            $frame->begin();
        }
        ?>
        <?if($iCountPhones):?>
            <div class="property phone">
                <div class="title font_upper muted"><?=($txt ? $txt : Loc::getMessage('SPRAVKA'));?></div>
                <?if($arRegion && $bRegionContact):?>
                    <div class="<?=($class ? ' '.$class : '')?>">
                        <?for($i = 0; $i < $iCountPhones; ++$i):?>
                            <?
                            $phone = ($arRegion ? $arRegion['PHONES'][$i]['PHONE'] : $arBackParametrs['HEADER_PHONES_array_PHONE_VALUE_'.$i]);
                            $href = ($arRegion ? $arRegion['PHONES'][$i]['HREF'] : $arBackParametrs['HEADER_PHONES_array_PHONE_HREF_'.$i]);
                            if(!strlen($href)){
                                $href = 'javascript:;';
                            }

                            $description = ($arRegion ? $arRegion['PROPERTY_PHONES_DESCRIPTION'][$i] : $arBackParametrs['HEADER_PHONES_array_PHONE_DESCRIPTION_'.$i]);
                            $description = (!empty($description)) ? 'title="' . $description . '"' : '';
                            ?>
                            <div class="value darken" itemprop="telephone"><a <?=$description?> href="<?=$href?>"><?=$phone?></a></div>
                        <?endfor;?>
                    </div>
                <?else:?>
                    <div class="value darken" itemprop="telephone"><?$APPLICATION->IncludeFile(SITE_DIR."include/contacts-site-phone-one.php", Array(), Array("MODE" => "html", "NAME" => "Phone"));?></div>
                <?endif;?>
            </div>
        <?endif;?>
        <?
        if($arRegion){
            $frame->end();
        }
    }

    public static function showContactEmailOpt($txt = '', $wrapTable = true, $class = '', $icon = 'Email.svg', $subclass = ''){
        global $arRegion, $APPLICATION;
        $iCalledID = ++$cemail_call;
        $bEmail = ($arRegion ? $arRegion['PROPERTY_EMAIL_VALUE'] : self::checkContentFile(SITE_DIR.'include/contacts-site-email.php'));
        $bRegionContact = (\Bitrix\Main\Config\Option::get(self::moduleID, 'SHOW_REGION_CONTACT', 'N') == 'Y');
        ?>
        <?if($arRegion):?>
            <?$frame = new \Bitrix\Main\Page\FrameHelper('header-allcemail-block'.$iCalledID);?>
            <?$frame->begin();?>
        <?endif;?>
        <?if($bEmail): // count of phones?>

            <div class="property email">
                <div class="title font_upper muted"><?=($txt ? $txt : Loc::getMessage('SPRAVKA'));?></div>
                <?if($arRegion && $bRegionContact):?>
                    <div class="<?=($class ? ' '.$class : '')?>">
                        <?foreach($arRegion['PROPERTY_EMAIL_VALUE'] as $value):?>
                            <div class="value darken" itemprop="email">
                                <a href="mailto:<?=$value;?>"><?=$value;?></a>
                            </div>
                        <?endforeach;?>
                    </div>
                <?else:?>
                    <div class="value darken" itemprop="email"><?$APPLICATION->IncludeFile(SITE_DIR."include/contacts-site-email.php", Array(), Array("MODE" => "html", "NAME" => "email"));?></div>
                <?endif;?>
            </div>

        <?endif;?>
        <?if($arRegion):?>
            <?$frame->end();?>
        <?endif;?>
        <?
    }
}
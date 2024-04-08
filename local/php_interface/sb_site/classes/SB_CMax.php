<?php
namespace SB\Site;
use Bitrix\Main\Config\Option;
use Bitrix\Main\IO\File;
use Bitrix\Main\Loader;
use CFile;
use CHTTP;

if(!Loader::includeModule('aspro.max')){
    return 1;
}

class SB_CMax extends \CMax
{
    public static function getSliderForItemExt(&$item, $propertyCode, $addDetailToSlider, $encode = true,$sb_size =219)
    {
        $encode = ($encode === true);
        $result = array();

        if (!empty($item) && is_array($item))
        {

            if (
                '' != $propertyCode &&
                isset($item['PROPERTIES'][$propertyCode]) &&
                'F' == $item['PROPERTIES'][$propertyCode]['PROPERTY_TYPE']
            )
            {
                if ('MORE_PHOTO' == $propertyCode && isset($item['MORE_PHOTO']) && !empty($item['MORE_PHOTO']))
                {

                    foreach ($item['MORE_PHOTO'] as &$onePhoto)
                    {
                        $alt = ($onePhoto["DESCRIPTION"] ? $onePhoto["DESCRIPTION"] : ($item['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'] ? $item['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'] : $item["NAME"]));
                        $title = ($onePhoto["DESCRIPTION"] ? $onePhoto["DESCRIPTION"] : ($item['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'] ? $item['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'] : $item["NAME"]));
                        if($item['ALT_TITLE_GET'] == 'SEO')
                        {
                            $alt = ($item['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'] ? $item['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'] : $item["NAME"]);
                            $title = ($item['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'] ? $item['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'] : $item["NAME"]);
                        }
                        $result[] = array(
                            'ID' => (int)$onePhoto['ID'],
                            'SRC' => ($encode ? CHTTP::urnEncode($onePhoto['SRC'], 'utf-8') : $onePhoto['SRC']),
                            'WIDTH' => (int)$onePhoto['WIDTH'],
                            'HEIGHT' => (int)$onePhoto['HEIGHT'],
                            'ALT' => $alt,
                            'TITLE' => $title
                        );
                    }
                    unset($onePhoto);
                }
                else
                {
                    if (
                        isset($item['DISPLAY_PROPERTIES'][$propertyCode]['FILE_VALUE']) &&
                        !empty($item['DISPLAY_PROPERTIES'][$propertyCode]['FILE_VALUE'])
                    )
                    {
                        $fileValues = (
                        isset($item['DISPLAY_PROPERTIES'][$propertyCode]['FILE_VALUE']['ID']) ?
                            array(0 => $item['DISPLAY_PROPERTIES'][$propertyCode]['FILE_VALUE']) :
                            $item['DISPLAY_PROPERTIES'][$propertyCode]['FILE_VALUE']
                        );
                        foreach ($fileValues as &$oneFileValue)
                        {
                            $alt = ($oneFileValue["DESCRIPTION"] ? $oneFileValue["DESCRIPTION"] : ($item['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'] ? $item['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'] : $item["NAME"]));
                            $title = ($oneFileValue["DESCRIPTION"] ? $oneFileValue["DESCRIPTION"] : ($item['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'] ? $item['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'] : $item["NAME"]));
                            if($item['ALT_TITLE_GET'] == 'SEO')
                            {
                                $alt = ($item['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'] ? $item['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'] : $item["NAME"]);
                                $title = ($item['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'] ? $item['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'] : $item["NAME"]);
                            }
                            $result[] = array(
                                'ID' => (int)$oneFileValue['ID'],
                                'SRC' => ($encode ? CHTTP::urnEncode($oneFileValue['SRC'], 'utf-8') : $oneFileValue['SRC']),
                                'WIDTH' => (int)$oneFileValue['WIDTH'],
                                'HEIGHT' => (int)$oneFileValue['HEIGHT'],
                                'ALT' => $alt,
                                'TITLE' => $title
                            );
                        }
                        if (isset($oneFileValue))
                            unset($oneFileValue);
                    }
                    else
                    {

                        $propValues = $item['PROPERTIES'][$propertyCode]['VALUE'];
                        if (!is_array($propValues))
                            $propValues = array($propValues);

                        foreach ($propValues as &$oneValue)
                        {
                            $oneFileValue = CFile::GetFileArray($oneValue);
                            if (isset($oneFileValue['ID']))
                            {
                                $alt = ($oneFileValue["DESCRIPTION"] ? $oneFileValue["DESCRIPTION"] : ($item['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'] ? $item['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'] : $item["NAME"]));
                                $title = ($oneFileValue["DESCRIPTION"] ? $oneFileValue["DESCRIPTION"] : ($item['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'] ? $item['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'] : $item["NAME"]));
                                if($item['ALT_TITLE_GET'] == 'SEO')
                                {
                                    $alt = ($item['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'] ? $item['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'] : $item["NAME"]);
                                    $title = ($item['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'] ? $item['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'] : $item["NAME"]);
                                }
                                $result[] = array(
                                    'ID' => (int)$oneFileValue['ID'],
                                    'SRC' => ($encode ? CHTTP::urnEncode($oneFileValue['SRC'], 'utf-8') : $oneFileValue['SRC']),
                                    'WIDTH' => (int)$oneFileValue['WIDTH'],
                                    'HEIGHT' => (int)$oneFileValue['HEIGHT'],
                                    'ALT' => $alt,
                                    'TITLE' => $title
                                );
                            }
                        }
                        if (isset($oneValue))
                            unset($oneValue);
                    }
                }
            }

            if(isset($item['OFFERS']) && $item['OFFERS'] && !$addDetailToSlider){
                if(empty($result))
                    unset($item['DETAIL_PICTURE']);
            }

            if ($addDetailToSlider || empty($result))
            {

                if (!empty($item['DETAIL_PICTURE']))
                {
                    if (!is_array($item['DETAIL_PICTURE']))
                        $item['DETAIL_PICTURE'] = CFile::GetFileArray($item['DETAIL_PICTURE']);

                    if (isset($item['DETAIL_PICTURE']['ID']))
                    {
                        $alt = ($item['DETAIL_PICTURE']['DESCRIPTION'] ? $item['DETAIL_PICTURE']['DESCRIPTION'] : ($item['DETAIL_PICTURE']['ALT'] ? $item['DETAIL_PICTURE']['ALT'] : $item['NAME'] ));
                        $title = ($item['DETAIL_PICTURE']['DESCRIPTION'] ? $item['DETAIL_PICTURE']['DESCRIPTION'] : ($item['DETAIL_PICTURE']['TITLE'] ? $item['DETAIL_PICTURE']['TITLE'] : $item['NAME'] ));
                        if($item['ALT_TITLE_GET'] == 'SEO')
                        {
                            $alt = ($item['DETAIL_PICTURE']['ALT'] ? $item['DETAIL_PICTURE']['ALT'] : $item['NAME'] );
                            $title = ($item['DETAIL_PICTURE']['TITLE'] ? $item['DETAIL_PICTURE']['TITLE'] : $item['NAME'] );
                        }
                        $detailPictIds = array_column($result, 'ID');
                        if(!in_array((int)$item['DETAIL_PICTURE']['ID'], $detailPictIds)){
                            array_unshift(
                                $result,
                                array(
                                    'ID' => (int)$item['DETAIL_PICTURE']['ID'],
                                    'SRC' => ($encode ? CHTTP::urnEncode($item['DETAIL_PICTURE']['SRC'], 'utf-8') : $item['DETAIL_PICTURE']['SRC']),
                                    'WIDTH' => (int)$item['DETAIL_PICTURE']['WIDTH'],
                                    'HEIGHT' => (int)$item['DETAIL_PICTURE']['HEIGHT'],
                                    'ALT' => $alt,
                                    'TITLE' => $title
                                )
                            );
                        }
                    }
                    elseif($item['PICTURE'])
                    {
                        array_unshift(
                            $result,
                            array(
                                'SRC' => $item['PICTURE'],
                                'ALT' => $item['NAME'],
                                'TITLE' => $item['NAME']
                            )
                        );
                    }
                }
            }
        }

        foreach ($result as &$tmp_item) {
            $img = \SB\Site\General::getarWaterMark($sb_size, $tmp_item["ID"]);
            $tmp_item['SRC'] = $img['src'];
            $tmp_item['WIDTH'] = $img['width'];
            $tmp_item['HEIGHT'] = $img['height'];

        }
        return $result;
    }

    public static function drawFormField($FIELD_SID, $arQuestion){
        ?>
        <?$arQuestion["HTML_CODE"] = str_replace('name=', 'data-sid="'.$FIELD_SID.'" name=', $arQuestion["HTML_CODE"]);?>
        <?$arQuestion["HTML_CODE"] = str_replace('left', '', $arQuestion["HTML_CODE"]);?>
        <?$arQuestion["HTML_CODE"] = str_replace('size="0"', '', $arQuestion["HTML_CODE"]);?>
        <?if($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden'):?>
            <?=str_replace(['text'],'hidden',$arQuestion["HTML_CODE"]);?>
        <?else:?>
            <div class="form-control">
                <label><span><?=($arQuestion["REQUIRED"] == "Y" ? '&nbsp;<span class="star">*</span>' : '')?></span></label>
                <?
                if(strpos($arQuestion["HTML_CODE"], "class=") === false)
                    $arQuestion["HTML_CODE"] = str_replace('input', 'input class=""', $arQuestion["HTML_CODE"]);

                if(is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS']))
                    $arQuestion["HTML_CODE"] = str_replace('class="', 'class="error ', $arQuestion["HTML_CODE"]);

                if($arQuestion["REQUIRED"] == "Y")
                    $arQuestion["HTML_CODE"] = str_replace('name=', 'required name=', $arQuestion["HTML_CODE"]);

                if($arQuestion["STRUCTURE"][0]["FIELD_TYPE"] == "email")
                    $arQuestion["HTML_CODE"] = str_replace('type="text"', 'type="email" placeholder="mail@domen.com"', $arQuestion["HTML_CODE"]);

                if((strpos($arQuestion["HTML_CODE"], "phone") !== false) || (strpos(strToLower($FIELD_SID), "phone") !== false))
                    $arQuestion["HTML_CODE"] = str_replace('type="text"', 'type="tel"', $arQuestion["HTML_CODE"]);
                ?>
                <?if($FIELD_SID == 'RATING'):?>
                    <div class="votes_block nstar big with-text">
                        <div class="ratings">
                            <div class="inner_rating">
                                <?for($i=1;$i<=5;$i++):?>
                                    <div class="item-rating" data-message="<?=GetMessage('RATING_MESSAGE_'.$i)?>"><?=static::showIconSvg("star", SITE_TEMPLATE_PATH."/images/svg/star.svg");?></div>
                                <?endfor;?>
                            </div>
                        </div>
                        <div class="rating_message muted" data-message="<?=GetMessage('RATING_MESSAGE_0')?>"><?=GetMessage('RATING_MESSAGE_0')?></div>
                        <?=str_replace('type="text"', 'type="hidden"', $arQuestion["HTML_CODE"])?>
                    </div>
                <?else:?>
                    <?=$arQuestion["HTML_CODE"]?>
                <?endif;?>
            </div>
        <?endif;?>
        <?
    }

    public static function ShowLogoMain(){
        global $arSite,$APPLICATION;
        $arTheme = self::GetFrontParametrsValues(SITE_ID);
        $text = '<a href="'.SITE_DIR.'">';

        if($APPLICATION->GetCurPage() == '/index.php' || $APPLICATION->GetCurPage() == '/'){
            //Если главна то светлый баннер
            $text .= '<img  src="/images/logo_white.png" alt="'.$arSite["SITE_NAME"].'" title="'.$arSite["SITE_NAME"].'"  />';
        }
        else{
            if($arImg = unserialize(Option::get(self::moduleID, "LOGO_IMAGE", serialize(array()))))
                $text .= '<img src="'.CFile::GetPath($arImg[0]).'" alt="'.$arSite["SITE_NAME"].'" title="'.$arSite["SITE_NAME"].'" data-src="" />';
            elseif(self::checkContentFile(SITE_DIR.'/include/logo_svg.php'))
                $text .= File::getFileContents($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'/include/logo_svg.php');
            else
                $text .= '<img src="'.$arTheme["LOGO_IMAGE"].'" alt="'.$arSite["SITE_NAME"].'" title="'.$arSite["SITE_NAME"].'" data-src="" />';
        }
        $text .= '</a>';

        return $text;
    }
}
<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use Bitrix\Main\Localization\Loc;
?>
<?if($arParams['IS_AJAX']):?>
	<link rel="stylesheet" href="<?=$stylesPath = str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__).'/style.css';?>">
<?endif;?>
<?
$siteSelectorName = isset($arParams['SITE_SELECTOR_NAME']) ? $arParams['SITE_SELECTOR_NAME'] : '';
				
switch($siteSelectorName){
	case 'FROM_LANG': 
		$nameField = "LANG";
		break;
	case 'FROM_SITE_NAME':
		$nameField = "NAME";
		break;
	default:
		$nameField = "NAME";
		break;
}
?>
<?if($arResult['SITES']):?>
    <div class="menu middle mobile_sites">
        <ul>
            <li>
                    <a rel="nofollow" href="" class="dark-color parent">
                    <?=CMax::showSpriteIconSvg(SITE_TEMPLATE_PATH."/images/svg/header_icons_srite.svg#sites-select", "svg-inline sites-select mobile", ['WIDTH' => 17,'HEIGHT' => 17]);?>
                    <?foreach($arResult['SITES'] as $arSite):?>
                        <?if($arSite['CURRENT'] === 'Y'):?>
                            <span class="font_15"> <?=($nameField == 'LANG' ? ucfirst($arSite[$nameField]) : $arSite[$nameField] )?></span>
                        <?endif;?>   
                    <?endforeach;?>  
                    <span class="arrow">
                        <?=CMax::showIconSvg("triangle", SITE_TEMPLATE_PATH.'/images/svg/trianglearrow_right.svg', '', '', true, false);?>
                    </span>
                </a>
                
                <ul class="dropdown">
                    <li class="menu_back"><a href="" class="dark-color" rel="nofollow"><?=CMax::showIconSvg('back_arrow', SITE_TEMPLATE_PATH.'/images/svg/return_mm.svg')?><?=Loc::getMessage('MAX_T_MENU_BACK')?></a></li>
                    <li class="menu_title"><?=Loc::getMessage('T_'.$nameField)?></li>
                    <?foreach($arResult['SITES'] as $arSite):?>
                    <?$siteLink = '';
                            if(
                                (
                                    is_array($arSite['DOMAINS']) && 
                                    strlen($arSite['DOMAINS'][0])
                                ) || 
                                strlen($arSite['DOMAINS'])
                            ){
                                $siteLink = is_array($arSite['DOMAINS']) ? $arSite['DOMAINS'][0] : $arSite['DOMAINS'];
                                $siteLink .= $arSite['DIR'];

                                if(strpos($siteLink, 'http://') === false && strpos($siteLink, 'https://') === false){
                                    $siteLink = '//'.$siteLink;
                                }
                            }
                        ?>   
                        
                        <li><a rel="nofollow" href="<?=$siteLink?>" class="dark-color font_15"> <?=($nameField == 'LANG' ? ucfirst($arSite[$nameField]) : $arSite[$nameField] )?></a></li>
						
                    <?endforeach;?>
                </ul>
            </li>
        </ul>
    </div>
    <?endif;?>
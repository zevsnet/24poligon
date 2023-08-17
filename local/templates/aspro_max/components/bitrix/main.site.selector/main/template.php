<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode(true); ?>
<?if($arParams['IS_AJAX']):?>
	<link rel="stylesheet" href="<?=$stylesPath = str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__).'/style.css';?>">
<?endif;?>
<?
$typeName = CMax::GetFrontParametrValue('SITE_SELECTOR_NAME') === "FROM_LANG";

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
<div class="sites flexbox flexbox--row">
	<div class="sites__dropdown sites__dropdown<?=$typeName ? "--typeLang" : ""?>">
		<div class="dropdown dropdown--relative">
			<?
			$counter = 1;
			//echo '<pre>'.print_r([$arResult["SITES"]], true).'<pre>';
			foreach ($arResult["SITES"] as $key => $arSite):?>
				<?
				$siteLink = '';

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
				
				if($arSite["CURRENT"] == "Y" && !$arParams['ONLY_ICON']) {
					$arCurrent = $arSite;
					$arCurrentLink = $siteLink;
				}?>
				<?if($arSite["CURRENT"] == "Y"):?>
					<div class="sites__option <?=$counter == 1 ? 'sites__option--first' : ''?> <?=$counter == count($arResult["SITES"]) ? 'sites__option--last' : ''?> sites__option--current font_xs dark-color"><?=$arSite[$nameField]?></div>
				<?else:?>
					<a class="sites__option <?=$counter == 1 ? 'sites__option--first' : ''?> <?=$counter == count($arResult["SITES"]) ? 'sites__option--last' : ''?> font_xs dark-color" href="<?=$siteLink?>"><?=$arSite[$nameField]?></a>
				<?endif;?>
			<?
			$counter++;
			endforeach;?>
		</div>
	</div>

	

	<div class="sites__select flexbox flexbox--row">
		<span>
			<?=CMax::showSpriteIconSvg(SITE_TEMPLATE_PATH."/images/svg/header_icons_srite.svg#sites-select", "svg-inline sites-select", ['WIDTH' => 17,'HEIGHT' => 17]);?>
		</span>
		<?if(!$arParams['ONLY_ICON']):?>
			<div class="sites__current <?=($nameField === 'LANG') ? 'sites__current--upper' : '' ?> font_xs"><?=$arCurrent[$nameField]?></div>
			<?if( is_array($arResult["SITES"]) && count($arResult["SITES"]) > 1 ):?>
				<?=CMax::showSpriteIconSvg(SITE_TEMPLATE_PATH."/images/svg/header_icons_srite.svg#Triangle_down", "svg-inline svg-inline-down dpopdown opacity1 ", ['WIDTH' => 5,'HEIGHT' => 3]);?>
			<?endif;?>
		<?endif;?>
	</div>
</div>	
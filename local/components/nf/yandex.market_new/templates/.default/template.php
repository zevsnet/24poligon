<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? echo '<?xml version="1.0" encoding="' . LANG_CHARSET . '"?>'; ?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="<?=$arResult["DATE"]?>">
    <shop>
        <name><?=$arResult["SITE"]?></name>
        <company><?=$arResult["COMPANY"]?></company>
        <url><?="http://".$_SERVER["SERVER_NAME"]?></url>
		
        <currencies>
			<?if ( !empty($arResult["CURRENCIES"]) ):?>
				<?foreach($arResult["CURRENCIES"] as $k=>$cur):?>
					<?if(!empty($cur) && $cur != 'RUR'):?><currency id="<?=$cur?>"<?if ( $k == 0 ):?> rate="1"<?endif;?>/><?endif;?>
				<?endforeach;?>
			<?else:?>
				<currency id="<?=$arParams["CURRENCY"]?>" rate="1"/>
			<?endif;?>
        </currencies>
		
	<categories>
<?foreach($arResult["CATEGORIES"] as $arCategory):?>
		<category id="<?=$arCategory["ID"]?>"<?
if($arCategory["PARENT"])
	echo ' parentId="'. $arCategory['PARENT']. '"';
?>><?=$arCategory["NAME"]?></category>
<?endforeach;?>
	</categories>	
	<?if($arParams["LOCAL_DELIVERY_COST"]):?>
	<local_delivery_cost><?=$arParams["LOCAL_DELIVERY_COST"]?></local_delivery_cost>
	<?endif?>
        <offers>
        <?foreach($arResult["OFFER"] as $arOffer):?>
			<offer id="<?=$arOffer["ID"]?>" available="<?=$arOffer["AVAIBLE"]?>">
				<url><?=$arOffer["URL"]?></url>
				<price><?=$arOffer["PRICE"]?></price>
				<?if (!empty($arOffer["OLD_PRICE"])):?>
					<oldprice><?=$arOffer["OLD_PRICE"]?></oldprice>
				<?endif;?>
				
				<currencyId>
					<?if ( !empty($arOffer["CURRENCY"]) ):?>
						<?=$arOffer["CURRENCY"]?>
					<?else:?>
						<?=$arParams["CURRENCY"]?>
					<?endif;?>
				</currencyId>
				
				<categoryId><?=$arOffer["CATEGORY"]?></categoryId>
				
				<?if ( !empty( $arOffer["PICTURE"] ) ):?>
					<picture><?=$arOffer["PICTURE"]?></picture>
				<?endif;?>
				
				<?foreach ($arOffer["MORE_PHOTO"] as $pic):?>
					<picture><?=$pic?></picture>
				<?endforeach;?>
				
				<?if($arParams["LOCAL_DELIVERY_COST"]):?><delivery>true</delivery><?endif?>
				<name><?=$arOffer["MODEL"]?></name>
				<?if($arOffer["DESCRIPTION"]):?><description><?=$arOffer["DESCRIPTION"]?></description><?endif?>
				<?foreach($arParams["PARAMS"] as $k=>$v): if($arOffer["DISPLAY_PROPERTIES"][$v]["DISPLAY_VALUE"]):?>
				 <param name="<?=$arOffer["DISPLAY_PROPERTIES"][$v]["DISPLAY_NAME"]?>"><?=$arOffer["DISPLAY_PROPERTIES"][$v]["DISPLAY_VALUE"]?></param>
				<?endif; endforeach;?>
				<?if(!empty($arOffer["SALES_NOTES_OFFER"])):?>
				<sales_notes><?=$arOffer["SALES_NOTES_OFFER"]?></sales_notes>
				<? endif ?>
			</offer>
        <?endforeach;?>
        </offers>
    </shop>
</yml_catalog>


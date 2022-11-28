<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<? if ($arResult['STEP'] == 1): ?>
<?= '<?xml version="1.0" encoding="' . LANG_CHARSET . '"?>' ?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="<?= $arResult["DATE"] ?>">
	<shop>
		<name><?= $arResult["SITE"] ?></name>
		<company><?= $arResult["COMPANY"] ?></company>
		<url><?= "http://" . $_SERVER["SERVER_NAME"] ?></url>
		<currencies>
			<? if (!empty($arResult["CURRENCIES"])): ?>
				<? foreach ($arResult["CURRENCIES"] as $k => $cur): ?>
					<? if (!empty($cur) && $cur != 'RUR'): ?>
						<currency id="<?= $cur ?>"<? if ($k == 0): ?> rate="1"<? endif ?>/><? endif ?>
				<? endforeach ?>
			<? else: ?>
				<currency id="<?= $arParams["CURRENCY"] ?>" rate="1"/>
			<? endif ?>
		</currencies>
		<categories>
			<? foreach ($arResult["CATEGORIES"] as $arCategory): ?>
				<category id="<?= $arCategory["ID"] ?>"<?
				if ($arCategory["PARENT"]) {
					echo ' parentId="' . $arCategory['PARENT'] . '"';
				}
				?>><?= $arCategory["NAME"] ?></category>
			<? endforeach ?>
		</categories>
		<? if (strlen($arParams["LOCAL_DELIVERY_COST"])): ?>
			<local_delivery_cost><?= $arParams["LOCAL_DELIVERY_COST"] ?></local_delivery_cost>
		<? endif ?>
		<cpa><?= ('N' == $arParams["DEFAULT_CPA"]) ? 0 : 1 ?></cpa>
		<offers>
<? endif ?>
			<? foreach ($arResult["OFFER"] as $arOffer): ?>
				<offer id="<?= $arOffer["ID"] ?>" type="vendor.model" available="<?= $arOffer["AVAIBLE"] ?>">
					<url><?= $arOffer["URL"] ?></url>
					<price><?= $arOffer["PRICE"] ?></price>
					<? if (!empty($arOffer["OLD_PRICE"])): ?>
						<oldprice><?= $arOffer["OLD_PRICE"] ?></oldprice>
					<? endif ?>
					<currencyId>
						<? if (!empty($arOffer["CURRENCY"])): ?>
							<?= $arOffer["CURRENCY"] ?>
						<? else: ?>
							<?= $arParams["CURRENCY"] ?>
						<? endif ?>
					</currencyId>
					<categoryId><?= $arOffer["CATEGORY"] ?></categoryId>
					<? if (!empty($arOffer["PICTURE"])): ?>
						<picture><?= $arOffer["PICTURE"] ?></picture>
					<? endif ?>
					<? foreach ($arOffer["MORE_PHOTO"] as $pic): ?>
						<picture><?= $pic ?></picture>
					<? endforeach ?>
					<? if (strlen($arParams["LOCAL_DELIVERY_COST"])): ?>
						<delivery>true</delivery>
					<? endif ?>
					<? if (strlen($arParams["LOCAL_DELIVERY_COST"])): ?>
						<local_delivery_cost><?= $arParams["LOCAL_DELIVERY_COST"] ?></local_delivery_cost>
					<? endif ?>
                    <? if (!empty($arOffer["DISPLAY_PROPERTIES"][$arParams["DEVELOPER"]]["DISPLAY_VALUE"])): ?><?
                    ?><vendor><?= $arOffer["DISPLAY_PROPERTIES"][$arParams["DEVELOPER"]]["DISPLAY_VALUE"] ?></vendor><?else:?><?
                        ?><vendor>24poligon</vendor><?
                        ?><? endif ?><?
                    ?><country_of_origin><?= GetMessage('SB_VENDOR_DEFF') ?></country_of_origin><?
                    ?><manufacturer_warranty>true</manufacturer_warranty><?
                    ?><? if (!empty($arOffer["NAME"])): ?>
                        <name><?= $arOffer["NAME"] ?></name><?
                        ?><? endif ?><?
                    ?><? if (false && !empty($arOffer["PROPERTY_CML2_LINK_VALUE"])): ?><?
                        ?><barcode><?= $arOffer["PROPERTY_CML2_LINK_VALUE"] ?></barcode><?
                        ?><? endif ?><?
                    ?><? if (!empty($arOffer["DESCRIPTION"])): ?>
						<description><?= $arOffer["DESCRIPTION"] ?></description>
					<? endif ?>
					<? if (!empty($arOffer["SALES_NOTES_OFFER"])): ?>
						<sales_notes><?= $arOffer["SALES_NOTES_OFFER"] ?></sales_notes>
						<?else:?>
						<sales_notes>минимальная сумма заказа 500 руб.</sales_notes>
					<? endif ?>
					<? if ($arOffer["DISPLAY_PROPERTIES"][$arParams["COUNTRY"]]["DISPLAY_VALUE"]): ?>
						<country_of_origin><?= $arOffer["DISPLAY_PROPERTIES"][$arParams["COUNTRY"]]["DISPLAY_VALUE"] ?></country_of_origin>
					<? endif ?>
					<? foreach ($arParams["PARAMS"] as $k => $v):
						if ($arOffer["DISPLAY_PROPERTIES"][$v]["DISPLAY_VALUE"]): ?>
							<param name="<?= $arOffer["DISPLAY_PROPERTIES"][$v]["DISPLAY_NAME"] ?>"><?= $arOffer["DISPLAY_PROPERTIES"][$v]["DISPLAY_VALUE"] ?></param>
						<? endif;
					endforeach ?>
					<? if (isset($arOffer["DISPLAY_PROPERTIES"][$arParams["MANDATORY_CPA"]]["DISPLAY_VALUE"])): ?>
						<?
						$cpa = $arOffer["DISPLAY_PROPERTIES"][$arParams["MANDATORY_CPA"]]["DISPLAY_VALUE"];
						$cpa = $cpa == 'N' ? 0 : !!$cpa;
						?>
						<cpa><?= $cpa ? 1 : 0 ?></cpa>
					<? endif ?>
				</offer>
			<? endforeach ?>
<? if ($arResult['LAST_STEP']): ?>
		</offers>
	</shop>
</yml_catalog>
<? endif ?>
<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Localization\Loc;
?>

<style type="text/css">
	table { border-collapse: collapse; width: 900px; }
	table th, table td { border: 1px solid #aaa; text-align: center; padding: 5px 10px; vertical-align: middle; }
</style>

<center>
	<h1><?=Loc::getMessage("SPECIFICATION_TITLE")?></h1>
</center>

<table>
	<thead>
		<tr>
			<th>№</th>
			<th><?=Loc::getMessage("SPECIFICATION_COL_NAME")?></th>
			<th><?=Loc::getMessage("SPECIFICATION_COL_QUANTITY")?></th>
			<th><?=Loc::getMessage("SPECIFICATION_COL_ITEM_PRICE")?></th>
			<th><?=Loc::getMessage("SPECIFICATION_COL_ITEM_TOTAL_PRICE")?></th>
		</tr>
	</thead>
	<tbody>
		<?
		$index = 1;
		foreach($arResult["ITEMS"] as $item):?>
			<tr>
				<td><?=$index++?></td>
				<td style="text-align: left;">
					<span><?=$item["NAME"]?></span>
					<?if(!empty($item["PROPS"])):?>
						[
							<?
							$propIndex = 0;
							foreach($item["PROPS"] as $prop):?>
								<span><?=$prop["NAME"]?></span>: <?=$prop["VALUE"]?>
							<?
								if (++$propIndex < count($item["PROPS"])) echo ";";
							endforeach;?>
						]
					<?endif;?>
				</td>
				<td><?=$item["QUANTITY"]?> <?=$item["MEASURE_NAME"]?></td>
				<td>
					<?if($item["DISCOUNT_PRICE"] > 0):?>
						<s><?=SaleFormatCurrency($item["BASE_PRICE"], $item["CURRENCY"])?></s>
						<br />
					<?endif;?>
					<?=SaleFormatCurrency($item["PRICE"], $item["CURRENCY"])?>
				</td>
				<td>
					<?if($item["DISCOUNT_PRICE"] > 0):?>
						<s><?=SaleFormatCurrency($item["BASE_PRICE"] * $item["QUANTITY"], $item["CURRENCY"])?></s>
						<br />
					<?endif;?>
					<?=SaleFormatCurrency($item["FINAL_PRICE"], $item["CURRENCY"])?>
				</td>
			</tr>
		<?endforeach;?>
	</tbody>
</table>

<br />

<span><?=Loc::getMessage("SPECIFICATION_TOTAL_PRICE")?>: 
	<?if($arResult["TOTAL"]["DISCOUNT_PRICE"] > 0):?>
		<s><?=SaleFormatCurrency($arResult["TOTAL"]["BASE_PRICE"], $arResult["TOTAL"]["CURRENCY"])?></s>
	<?endif;?>
	<?=SaleFormatCurrency($arResult["TOTAL"]["PRICE"], $arResult["TOTAL"]["CURRENCY"])?>
</span>

<?if($arResult["TOTAL"]["WEIGHT"] > 0):?>
	<br /><span><?=Loc::getMessage("SPECIFICATION_TOTAL_WEIGHT")?>: <?=number_format($arResult["TOTAL"]["WEIGHT"], 0, "", " ")?> <?=Loc::getMessage("SPECIFICATION_TOTAL_WEIGHT_UNIT")?></span>
<?endif;?>
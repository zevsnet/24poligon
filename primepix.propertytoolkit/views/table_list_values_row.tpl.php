<tr id="<?=$arProp['VALUE_ID']?>" data-hash="<?=$hash?>">
	<td class="radio-td"><input type="radio" name="LEADING" value="<?=$arProp['VALUE_ID']?>"></td>
	<td class="check-td"><input type="checkbox" name="PROPS[]" value="<?=$arProp['VALUE_ID']?>"></td>
	<td class="id"><?=$arProp['ID']?></td>
	<td class="-edit-list-td edit-td" data-in-progress="false" data-type="name">
		<span><?=$arProp['NAME']?></span>
		<input name="prop" class="edit-input -edit-input" value="<?=$arProp["NAME"]?>">
	</td>
	<td class="value-id"><?=$arProp['VALUE_ID']?></td>
	<td class="-edit-list-td edit-td" data-in-progress="false" data-type="value"><span><?=trim($arProp["VALUE"])?></span><input name="value" class="edit-input -edit-input" value="<?=$arProp["VALUE"]?>" maxlength="255"></td>
	<td class="products -edit-products-td">
		<div class="-link-block"><?=$productsEditLinks?></div>
		<textarea class="-edit-input edit-input" name="links" value="<?=$productsEditIds?>"><?=$productsEditIds?></textarea>
	</td>
	<td>
		<a class="-edit-links edit-links" href="#editLinks" data-in-progress="false">
			<?=GetMessage($MODULE_ID . '_ACTION_LINKS_EDIT')?>
		</a>
	</td>
</tr>
<tr id="<?=$arProp['ID']?>">
	<td class="check-td"><input type="checkbox" name="PROPS[]" value="<?=$arProp['ID']?>"></td>
	<td class="id"><?=$arProp['PROPERTY_ID']?></td>
	<td class="-edit-list-td edit-td" data-in-progress="false" data-type="name">
		<span><?=$arProp['NAME']?></span>
		<input name="prop" class="edit-input -edit-input" value="<?=$arProp["NAME"]?>">
	</td>
	<td class="-edit-list-td edit-td" data-in-progress="false" data-type="value" data-id="<?=$arProp['ID']?>"><span><?=trim($arProp["VALUE"])?></span><input name="value" class="edit-input -edit-input" value="<?=$arProp["VALUE"]?>" maxlength="255"></td>
	<td class="products"><?=$productEditLink?></td>
</tr>
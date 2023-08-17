<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
CJSCore::Init(array("ajax"));
//Let's determine what value to display: rating or average ?
if($arParams["DISPLAY_AS_RATING"] == "vote_avg")
{
	if($arResult["PROPERTIES"]["vote_count"]["VALUE"])
		$DISPLAY_VALUE = round($arResult["PROPERTIES"]["vote_sum"]["VALUE"]/$arResult["PROPERTIES"]["vote_count"]["VALUE"], 2);
	else
		$DISPLAY_VALUE = 0;
}
else
	$DISPLAY_VALUE = $arResult["PROPERTIES"]["rating"]["VALUE"];
?>
<?if(intval($arResult["PROPERTIES"]["vote_count"]["VALUE"])):?>
	<div class="votes_block nstar" id="vote_<?echo $arResult["ID"]?>" itemprop="aggregateRating" data-summ="<?=(int)$arResult["PROPERTIES"]['vote_sum']['VALUE']?>" itemscope itemtype="http://schema.org/AggregateRating">
		<meta itemprop="ratingValue" content="<?=($DISPLAY_VALUE ? $DISPLAY_VALUE : 5)?>" />
		<meta itemprop="reviewCount" content="<?=(intval($arResult["PROPERTIES"]["vote_count"]["VALUE"]) > 0 ? intval($arResult["PROPERTIES"]["vote_count"]["VALUE"]) : 5)?>" />
		<meta itemprop="bestRating" content="<?=$arParams['MAX_VOTE']?>" />
		<meta itemprop="worstRating" content="0" />

<?else:?>
	<div class="votes_block nstar" id="vote_<?echo $arResult["ID"]?>" data-summ="<?=(int)$arResult["PROPERTIES"]['vote_sum']['VALUE']?>">
<?endif;?>

<script>
if(!window.voteScript) window.voteScript =
{
	trace_vote: function(div, flag)
	{
		var my_div;
		var r = div.id.match(/^vote_(\d+)_(\d+)$/);
		for(var i = r[2]; i >= 0; i--)
		{
			my_div = document.getElementById('vote_'+r[1]+'_'+i);
			if(my_div)
			{
				if(flag)
				{
					if(!my_div.saved_class)
						my_div.saved_className = my_div.className;
					if(my_div.className!='item-rating filed')
						my_div.className = 'item-rating filed';
				}
				else
				{
					if(my_div.saved_className && my_div.className != my_div.saved_className)
						my_div.className = my_div.saved_className;
				}
			}
		}
		i = r[2]+1;
		while(my_div = document.getElementById('vote_'+r[1]+'_'+i))
		{
			if(my_div.saved_className && my_div.className != my_div.saved_className)
				my_div.className = my_div.saved_className;
			i++;
		}
	},
	do_vote: function(div, parent_id, arParams)
	{
		var r = div.id.match(/^vote_(\d+)_(\d+)$/);

		var vote_id = r[1];
		var vote_value = r[2];

		function __handler(data)
		{
			var obContainer = document.getElementById(parent_id),
				count = ++vote_value,
				total_count_div = $('#vote_'+vote_id),
				new_count = 0;
			/*if (obContainer)
			{
				var obResult = document.createElement("DIV");
				obResult.innerHTML = data;
				obContainer.parentNode.replaceChild(obResult, obContainer);
			}*/
			new_count = Math.round((total_count_div.data('summ') + count)/(parseInt(total_count_div.find('meta[itemprop="reviewCount"]').attr('content'))+1));
			total_count_div.find('.inner_rating > div').removeClass('filed');
			for(var jj = 0; jj < new_count; jj++)
			{
				total_count_div.find('.inner_rating > div:eq('+jj+')').addClass('filed');
			}
			total_count_div.find('.inner_rating > div').removeAttr('onmouseover onmouseout onclick');
		}
		arParams['vote'] = 'Y';
		arParams['vote_id'] = vote_id;
		arParams['rating'] = vote_value;
		BX.ajax.post(
			'/bitrix/components/bitrix/iblock.vote/component.php',
			arParams,
			__handler
		);
	}
}
</script>
	<div class="ratings">
		<div class="inner_rating">
			<?if($arResult["VOTED"] || $arParams["READ_ONLY"]==="Y"):?>
				<?if($DISPLAY_VALUE):?>
					<?foreach($arResult["VOTE_NAMES"] as $i=>$name):?>
						<?if(round($DISPLAY_VALUE) > $i):?>
							<div id="vote_<?echo $arResult["ID"]?>_<?echo $i?>" class="item-rating filed" title="<?echo $name?>">
								<?=CMax::showIconSvg("star", SITE_TEMPLATE_PATH."/images/svg/catalog/star_small.svg");?>
							</div>
						<?else:?>
							<div id="vote_<?echo $arResult["ID"]?>_<?echo $i?>" class="item-rating" title="<?echo $name?>">
								<?=CMax::showIconSvg("star", SITE_TEMPLATE_PATH."/images/svg/catalog/star_small.svg");?>
							</div>
						<?endif?>
					<?endforeach?>
				<?else:?>
					<?foreach($arResult["VOTE_NAMES"] as $i=>$name):?>
						<div id="vote_<?echo $arResult["ID"]?>_<?echo $i?>" class="item-rating" title="<?echo $name?>">
							<?=CMax::showIconSvg("star", SITE_TEMPLATE_PATH."/images/svg/catalog/star_small.svg");?>
						</div>
					<?endforeach?>
				<?endif?>
			<?else:
				$onclick = "voteScript.do_vote(this, 'vote_".$arResult["ID"]."', ".$arResult["AJAX_PARAMS"].")";
				?>
				<?if($DISPLAY_VALUE):?>
					<?foreach($arResult["VOTE_NAMES"] as $i=>$name):?>
						<?if(round($DISPLAY_VALUE) > $i):?>
							<div id="vote_<?echo $arResult["ID"]?>_<?echo $i?>" class="item-rating filed" title="<?echo $name?>" onmouseover="voteScript.trace_vote(this, true);" onmouseout="voteScript.trace_vote(this, false)" onclick="<?echo htmlspecialcharsbx($onclick);?>"><?=CMax::showIconSvg("star", SITE_TEMPLATE_PATH."/images/svg/catalog/star_small.svg");?></div>
						<?else:?>
							<div id="vote_<?echo $arResult["ID"]?>_<?echo $i?>" class="item-rating" title="<?echo $name?>" onmouseover="voteScript.trace_vote(this, true);" onmouseout="voteScript.trace_vote(this, false)" onclick="<?echo htmlspecialcharsbx($onclick);?>"><?=CMax::showIconSvg("star", SITE_TEMPLATE_PATH."/images/svg/catalog/star_small.svg");?></div>
						<?endif?>
					<?endforeach?>
				<?else:?>
					<?foreach($arResult["VOTE_NAMES"] as $i=>$name):?>
						<div id="vote_<?echo $arResult["ID"]?>_<?echo $i?>" class="item-rating" title="<?echo $name?>" onmouseover="voteScript.trace_vote(this, true);" onmouseout="voteScript.trace_vote(this, false)" onclick="<?echo htmlspecialcharsbx($onclick);?>"><?=CMax::showIconSvg("star", SITE_TEMPLATE_PATH."/images/svg/catalog/star_small.svg");?></div>
					<?endforeach?>
				<?endif?>
			<?endif?>
		</div>
	</div>
</div>
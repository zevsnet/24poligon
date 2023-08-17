<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$frame = $this->createFrame()->begin();?>
<?
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

<??>
<div class="votes_block nstar">
	<div class="ratings">
		<div class="inner_rating">
			<?if($arResult["VOTED"] || $arParams["READ_ONLY"]==="Y"):?>
				<?if($DISPLAY_VALUE):?>
					<?foreach($arResult["VOTE_NAMES"] as $i=>$name):?>
						<?if(round($DISPLAY_VALUE) > $i):?>
							<div class="item-rating filed" title="<?echo $name?>"><?=CMax::showIconSvg("star", SITE_TEMPLATE_PATH."/images/svg/catalog/star_small.svg");?></div>
						<?else:?>
							<div class="item-rating" title="<?echo $name?>"><?=CMax::showIconSvg("star", SITE_TEMPLATE_PATH."/images/svg/catalog/star_small.svg");?></div>
						<?endif?>
					<?endforeach?>
				<?else:?>
					<?foreach($arResult["VOTE_NAMES"] as $i=>$name):?>
						<div class="item-rating" title="<?echo $name?>"><?=CMax::showIconSvg("star", SITE_TEMPLATE_PATH."/images/svg/catalog/star_small.svg");?></div>
					<?endforeach?>
				<?endif?>
			<?else:
				$onclick = "voteScript.do_vote(this, 'vote_".$arResult["ID"]."', ".$arResult["AJAX_PARAMS"].")";?>
				<?if($DISPLAY_VALUE):?>
					<?foreach($arResult["VOTE_NAMES"] as $i=>$name):?>
						<?if(round($DISPLAY_VALUE) > $i):?>
							<div class="item-rating filed" title="<?echo $name?>"><?=CMax::showIconSvg("star", SITE_TEMPLATE_PATH."/images/svg/catalog/star_small.svg");?></div>
						<?else:?>
							<div class="item-rating" title="<?echo $name?>"><?=CMax::showIconSvg("star", SITE_TEMPLATE_PATH."/images/svg/catalog/star_small.svg");?></div>
						<?endif?>
					<?endforeach?>
				<?else:?>
					<?foreach($arResult["VOTE_NAMES"] as $i=>$name):?>
						<div class="item-rating" title="<?echo $name?>"><?=CMax::showIconSvg("star", SITE_TEMPLATE_PATH."/images/svg/catalog/star_small.svg");?></div>
					<?endforeach?>
				<?endif?>
			<?endif?>
		</div>
	</div>
</div>
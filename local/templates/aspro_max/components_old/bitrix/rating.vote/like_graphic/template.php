<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeTemplateLangFile(__FILE__);
?>
<span class="ilike colored_theme_hover_text">
	<span class="bx-ilike-button <?=($arResult['VOTE_AVAILABLE'] == 'Y'? '': 'bx-ilike-button-disable')?>" id="bx-ilike-button-<?=htmlspecialcharsbx($arResult['VOTE_ID'])?>">
		<span class="all-wrapper">
			<span class="bx-ilike-left-wrap <?=($arResult['USER_HAS_VOTED'] == 'N'? '': 'bx-you-like')?>" <?=($arResult['VOTE_AVAILABLE'] == 'Y'? '': 'title="'.htmlspecialcharsbx($arResult['ALLOW_VOTE']['ERROR_MSG']).'"')?>>
				<span class="bx-ilike-left muted"><?=CMax::showIconSvg("thumbup", SITE_TEMPLATE_PATH.'/images/svg/thumbup.svg', '', '', true, false);?></span>
				<span class="bx-ilike-right-wrap">
					<span class="bx-ilike-right font_upper"><?=htmlspecialcharsEx($arResult['TOTAL_VOTES'])?></span>
				</span>
				<span class="bx-ilike-text font_upper">
					<?=($arResult['USER_HAS_VOTED'] == 'N'? GetMessage('LIKE'): GetMessage('DISLIKE'))?>
				</span>
			</span>
		</span>
	</span>
	<span class="bx-ilike-wrap-block" id="bx-ilike-popup-cont-<?=htmlspecialcharsbx($arResult['VOTE_ID'])?>" style="display:none;">
		<span class="bx-ilike-popup">
			<span class="bx-ilike-wait"></span>
		</span>
	</span>
</span>
<script type="text/javascript">
BX.ready(function() {	
<?if ($arResult['AJAX_MODE'] == 'Y'):?>
	BX.loadCSS('/bitrix/components/bitrix/rating.vote/templates/like/popup.css');
	BX.loadCSS('/bitrix/components/bitrix/rating.vote/templates/like_graphic/style.css');
	BX.loadScript('/bitrix/js/main/rating_like.js', function() {
<?endif;?>
		if (!window.RatingLikes && top.RatingLikes)
			RatingLikes = top.RatingLikes;
		RatingLikes.Set(
			'<?=CUtil::JSEscape($arResult['VOTE_ID'])?>',
			'<?=CUtil::JSEscape($arResult['ENTITY_TYPE_ID'])?>',
			'<?=IntVal($arResult['ENTITY_ID'])?>',
			'<?=CUtil::JSEscape($arResult['VOTE_AVAILABLE'])?>',
			'<?=$USER->GetId()?>',
			{'LIKE_Y' : '<?=htmlspecialcharsBx(CUtil::JSEscape(GetMessage('DISLIKE')))?>', 'LIKE_N' : '<?=htmlspecialcharsBx(CUtil::JSEscape(GetMessage('LIKE')))?>', 'LIKE_D' : '<?=htmlspecialcharsBx(CUtil::JSEscape(GetMessage('LIKE')))?>'},
			'standart',
			'<?=CUtil::JSEscape($arResult['PATH_TO_USER_PROFILE'])?>'
		);

		if (typeof(RatingLikesPullInit) == 'undefined')
		{
			RatingLikesPullInit = true;
			BX.addCustomEvent("onPullEvent-main", function(command,params) {
				if (command == 'rating_vote')
				{
					RatingLikes.LiveUpdate(params);
				}
			});
		}
<?if ($arResult['AJAX_MODE'] == 'Y'):?>
	});
<?endif;?>
});	
</script>
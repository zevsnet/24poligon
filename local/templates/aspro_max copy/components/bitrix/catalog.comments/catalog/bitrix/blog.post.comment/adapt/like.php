<?
global $USER;
if($USER->IsAuthorized()) {
	$userId = $USER->GetID();
}

if($userId) {
	global $USER_FIELD_MANAGER; 
	$ufId = ($userId % 1000).($comment['ID'] % 1000);
	$fields = $USER_FIELD_MANAGER->GetUserFields("BLOG_COMMENT_ID", $ufId);
	$fieldValueLike = $fields['UF_LIKE_ID']['VALUE'];
	$fieldValueLike = unserialize($fieldValueLike);

	if( isset($fieldValueLike[$userId]) ) {
		$valuelike = $fieldValueLike[$userId];
	} else {
		$valuelike = 'N';
	}

	$bActiveLike = $valuelike == 'Y';

	$fieldValueDisLike = $fields['UF_DISLIKE_ID']['VALUE'];
	$fieldValueDisLike = unserialize($fieldValueDisLike);

	if( isset($fieldValueDisLike[$userId]) ) {
		$valuedislike = $fieldValueDisLike[$userId];
	} else {
		$valuedislike = 'N';
	}

	$bActiveDisLike = $valuedislike == 'Y';
}
?>
<span class="rating-vote <?=$userId ? 'active' : ''?>" data-comment_id="<?=$comment['ID']?>" data-user_id="<?=$userId?>" data-ajax_url="<?=str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__).'/ajaxLike.php'?>">
	<a class="rating_vote plus <?=$userId ? '' : 'disable'?> <?=$bActiveLike ? 'active' : ''?>" data-action="plus" title="<?=GetMessage('LIKE')?>">
		<?=CMax::showIconSvg("plus", SITE_TEMPLATE_PATH."/images/svg/like_like.svg");?>
	</a>
	<span class="rating-vote-result like">
		<?=intval($comment['UF_ASPRO_COM_LIKE'])?>
	</span>

	<a class="rating_vote minus <?=$userId ? '' : 'disable'?> <?=$bActiveDisLike ? 'active' : ''?>" data-action="minus" title="<?=GetMessage('DISLIKE')?>">
		<?=CMax::showIconSvg("minus", SITE_TEMPLATE_PATH."/images/svg/like_dislike.svg");?>
	</a>

	<span class="rating-vote-result dislike">
		<?=intval($comment['UF_ASPRO_COM_DISLIKE'])?>
	</span>
</span>

<script type="text/javascript">

</script>
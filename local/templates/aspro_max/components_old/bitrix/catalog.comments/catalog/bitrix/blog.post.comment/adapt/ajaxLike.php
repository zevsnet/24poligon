<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
include('functions.php');

global $USER_FIELD_MANAGER; 

createField("BLOG_COMMENT", 'UF_ASPRO_COM_LIKE', 'integer');
createField("BLOG_COMMENT_ID", 'UF_LIKE_ID');
createField("BLOG_COMMENT", 'UF_ASPRO_COM_DISLIKE', 'integer');
createField("BLOG_COMMENT_ID", 'UF_DISLIKE_ID');

$ufId = ($_REQUEST['userId'] % 1000).($_REQUEST['commentId'] % 1000);

$fields = $USER_FIELD_MANAGER->GetUserFields("BLOG_COMMENT", $_REQUEST['commentId']);
$fieldValueId = $USER_FIELD_MANAGER->GetUserFields("BLOG_COMMENT_ID", $ufId);

/* LIKE */
$likeValue = ($fields['UF_ASPRO_COM_LIKE']['VALUE'] ? $fields['UF_ASPRO_COM_LIKE']['VALUE'] : 0);
$likeId = $fieldValueId['UF_LIKE_ID']['VALUE'];
$likeId = unserialize($likeId);

if( isset($likeId[$_REQUEST['userId']]) ) {
	$valueLike = $likeId[$_REQUEST['userId']];
} else {
	$likeId[$_REQUEST['userId']] = 'N';
	$valueLike = 'N';
}

$bCancelLike = $valueLike == 'Y';
/* LIKE */


/* DISLIKE */
$dislikeValue = ($fields['UF_ASPRO_COM_DISLIKE']['VALUE'] ? $fields['UF_ASPRO_COM_DISLIKE']['VALUE'] : 0);
$dislikeId = $fieldValueId['UF_DISLIKE_ID']['VALUE'];
$dislikeId = unserialize($dislikeId);

if( isset($dislikeId[$_REQUEST['userId']]) ) {
	$valueDisLike = $dislikeId[$_REQUEST['userId']];
} else {
	$dislikeId[$_REQUEST['userId']] = 'N';
	$valueDisLike = 'N';
}

$bCancelDisLike = $valueDisLike == 'Y';
/* DISLIKE */

if($bCancelLike) {
	$likeId[$_REQUEST['userId']] = 'N';
	$likeValue--;
	$result["SET_ACTIVE_LIKE"] = false;

	$USER_FIELD_MANAGER->Update("BLOG_COMMENT_ID", $ufId, array('UF_LIKE_ID' => serialize($likeId)));
	$USER_FIELD_MANAGER->Update("BLOG_COMMENT", $_REQUEST['commentId'], array('UF_ASPRO_COM_LIKE' => $likeValue));
}

if($bCancelDisLike) {
	$dislikeId[$_REQUEST['userId']] = 'N';
	$dislikeValue--;
	$result["SET_ACTIVE_DISLIKE"] = false;

	$USER_FIELD_MANAGER->Update("BLOG_COMMENT_ID", $ufId, array('UF_DISLIKE_ID' => serialize($dislikeId)));
	$USER_FIELD_MANAGER->Update("BLOG_COMMENT", $_REQUEST['commentId'], array('UF_ASPRO_COM_DISLIKE' => $dislikeValue));
}



if($_REQUEST['action'] == 'plus') {

	if(!$bCancelLike) {
		$likeId[$_REQUEST['userId']] = 'Y';
		$likeValue++;
		$result["SET_ACTIVE_LIKE"] = true;

		$USER_FIELD_MANAGER->Update("BLOG_COMMENT_ID", $ufId, array('UF_LIKE_ID' => serialize($likeId)));
		$USER_FIELD_MANAGER->Update("BLOG_COMMENT", $_REQUEST['commentId'], array('UF_ASPRO_COM_LIKE' => $likeValue));
	}

} else if($_REQUEST['action'] == 'minus') {

	if(!$bCancelDisLike) {
		$dislikeId[$_REQUEST['userId']] = 'Y';
		$dislikeValue++;
		$result["SET_ACTIVE_DISLIKE"] = true;

		$USER_FIELD_MANAGER->Update("BLOG_COMMENT_ID", $ufId, array('UF_DISLIKE_ID' => serialize($dislikeId)));
		$USER_FIELD_MANAGER->Update("BLOG_COMMENT", $_REQUEST['commentId'], array('UF_ASPRO_COM_DISLIKE' => $dislikeValue));
	}

}

$result["LIKE"] = intval($likeValue);
$result["DISLIKE"] = intval($dislikeValue);

?>
<?=\Bitrix\Main\Web\Json::encode($result)?>
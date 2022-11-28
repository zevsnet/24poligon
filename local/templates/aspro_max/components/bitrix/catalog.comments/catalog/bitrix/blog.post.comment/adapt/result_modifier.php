<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if (!function_exists("__MPF_ImageResizeHandler"))
{
	function __MPF_ImageResizeHandler(&$arCustomFile)
	{
		$arResizeParams = array("width" => 400, "height" => 400);

		if ((!is_array($arCustomFile)) || !isset($arCustomFile['fileID']))
			return false;

		$fileID = $arCustomFile['fileID'];

		$arFile = CFile::MakeFileArray($fileID);
		if (CFile::CheckImageFile($arFile) === null)
		{
			$aImgThumb = CFile::ResizeImageGet(
				$fileID,
				array("width" => 90, "height" => 90),
				BX_RESIZE_IMAGE_EXACT,
				true
			);
			$arCustomFile['img_thumb_src'] = $aImgThumb['src'];

			if (!empty($arResizeParams))
			{
				$aImgSource = CFile::ResizeImageGet(
					$fileID,
					array("width" => $arResizeParams["width"], "height" => $arResizeParams["height"]),
					BX_RESIZE_IMAGE_PROPORTIONAL,
					true
				);
				$arCustomFile['img_source_src'] = $aImgSource['src'];
				$arCustomFile['img_source_width'] = $aImgSource['width'];
				$arCustomFile['img_source_height'] = $aImgSource['height'];
			}
		}

	}
}

if (!empty($arParams["UPLOAD_FILE_PARAMS"]))
{
	$bNull = null;
	__MPF_ImageResizeHandler($bNull, $arParams["UPLOAD_FILE_PARAMS"]);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_REQUEST['mfi_mode']) && ($_REQUEST['mfi_mode'] == "upload"))
{
	AddEventHandler('main',  "main.file.input.upload", '__MPF_ImageResizeHandler');
}

include('functions.php');

createField("BLOG_COMMENT", 'UF_ASPRO_COM_LIKE', 'integer');
createField("BLOG_COMMENT", 'UF_ASPRO_COM_RATING', 'integer');
createField("BLOG_COMMENT", 'UF_ASPRO_COM_DISLIKE', 'integer');

$arOrder = Array();
$arFilter = Array("POST_ID" => $arParams["ID"], "BLOG_ID" => $arResult["Blog"]["ID"], 'PUBLISH_STATUS' => 'P');
$arSelectedFields = Array("ID", 'UF_ASPRO_COM_RATING', 'UF_ASPRO_COM_LIKE', 'UF_ASPRO_COM_DISLIKE');
$dbComment = CBlogComment::GetList($arOrder, $arFilter, false, false, $arSelectedFields);

while($comment = $dbComment->Fetch())
{
	if($comment['UF_ASPRO_COM_RATING']) {
		$arComments[$comment['ID']]['UF_ASPRO_COM_RATING'] = $comment['UF_ASPRO_COM_RATING'];
	}

	if($comment['UF_ASPRO_COM_LIKE']) {
		$arComments[$comment['ID']]['UF_ASPRO_COM_LIKE'] = $comment['UF_ASPRO_COM_LIKE'];
	}

	if($comment['UF_ASPRO_COM_DISLIKE']) {
		$arComments[$comment['ID']]['UF_ASPRO_COM_DISLIKE'] = $comment['UF_ASPRO_COM_DISLIKE'];
	}

	$arResult['REVIEWS_COUNT']++;
}

global $USER_FIELD_MANAGER;
$arParams['SORT_PROP'] = $_SESSION['REVIEW_SORT_PROP'] ? $_SESSION['REVIEW_SORT_PROP'] : 'UF_ASPRO_COM_RATING';
$arParams['SORT_ORDER'] = $_SESSION['REVIEW_SORT_ORDER'] ? $_SESSION['REVIEW_SORT_ORDER'] : 'SORT_DESC';

$arCommentsSort = array();
if($arResult["PagesComment"]) {
	$pagesCount = count($arResult["PagesComment"]);
	$countOnPage = $arParams["COMMENTS_COUNT"];

	foreach ($arResult["PagesComment"] as $page => &$comments) {
		foreach ($comments as &$comment) {
			$inputText = $comment['~POST_TEXT'];

			$pattern = '/<virtues>(.*?)<\/virtues>/s';
			preg_match($pattern, $inputText, $matches);
			$virtues = $matches[1];

			$pattern = '/<limitations>(.*?)<\/limitations>/s';
			preg_match($pattern, $inputText, $matches);
			$limitations = $matches[1];

			$pattern = '/<comment>(.*?)<\/comment>/s';
			preg_match($pattern, $inputText, $matches);
			$commentText = $matches[1];

			$comment['TEXT']['TYPE'] = 'PARENT';
			$comment['TEXT']['VIRTUES'] = $virtues;
			$comment['TEXT']['LIMITATIONS'] = $limitations;
			$comment['TEXT']['COMMENT'] = $commentText;

			$resImages = CBlogImage::GetList(array("ID"=>"DESC"), array('COMMENT_ID' => $comment['ID']));
			while($arImage = $resImages->Fetch()) {
				$comment['IMAGES'][] = $arImage;
			}

			if(isset($arComments[$comment['ID']])) {
				$comment['UF_ASPRO_COM_RATING'] = $arComments[$comment['ID']]['UF_ASPRO_COM_RATING'];
				$comment['UF_ASPRO_COM_LIKE'] = $arComments[$comment['ID']]['UF_ASPRO_COM_LIKE'];
				$comment['UF_ASPRO_COM_DISLIKE'] = $arComments[$comment['ID']]['UF_ASPRO_COM_DISLIKE'];
				if($comment['UF_ASPRO_COM_RATING']) {
					$arCommentsRating['COUNT']++;
				 	$arCommentsRating['VALUE'] += $arComments[$comment['ID']]['UF_ASPRO_COM_RATING'];
				}
			}

			$arCommentsSort[] = $comment;

		}
	}


	unset($comment);
	unset($comments);

	foreach ($arResult["CommentsResult"] as &$comments) {
		foreach ($comments as &$comment) {

			if(isset($arComments[$comment['ID']])) {
				$comment['UF_ASPRO_COM_LIKE'] = $arComments[$comment['ID']]['UF_ASPRO_COM_LIKE'];
				$comment['UF_ASPRO_COM_DISLIKE'] = $arComments[$comment['ID']]['UF_ASPRO_COM_DISLIKE'];
			}			

		}
	}

	if($arCommentsSort) {

		$arResult['REVIEWS_COUNT'] = count($arCommentsSort);

		if($arParams['SORT_PROP'] == 'DateFormated') {
			if($arParams['SORT_ORDER'] != 'SORT_DESC') {
				function sortByDate($a, $b) {
					$res = strtotime($a['DateFormated']) - strtotime($b['DateFormated']);
					return $res;
				}
			} else {
				function sortByDate($a, $b) {
					$res = strtotime($b['DateFormated']) - strtotime($a['DateFormated']);
					return $res;
				}
			}
			$result = usort($arCommentsSort, 'sortByDate');
		} else {
			$arParams['SORT_ORDER'] = $arParams['SORT_ORDER'] == 'SORT_DESC' ? SORT_DESC : SORT_ASC;
			\Bitrix\Main\Type\Collection::sortByColumn($arCommentsSort, array($arParams['SORT_PROP'] => array($arParams['SORT_ORDER']), "DATE_CREATE" => array(SORT_DESC)));
		}

		for($i = 0; $i < $pagesCount; $i++ ) {
			$arResult["PagesComment"][$i+1] = array_slice($arCommentsSort, $countOnPage * $i, $countOnPage);
		}

		$arResult["CommentsResult"][0] = $arResult["PagesComment"][$arParams["PAGEN"]];

		if($arCommentsRating['COUNT']) {
			$arResult['ALL_RATING_VALUE'] = round( $arCommentsRating['VALUE'] / $arCommentsRating['COUNT'], 1);
		}

	}


	unset($comment);
	unset($comments);

} else {
	foreach ($arResult["CommentsResult"] as &$comments) {
		foreach ($comments as &$comment) {
			$inputText = $comment['~POST_TEXT'];

			$pattern = '/<virtues>(.*?)<\/virtues>/s';
			preg_match($pattern, $inputText, $matches);
			$virtues = $matches[1];

			$pattern = '/<limitations>(.*?)<\/limitations>/s';
			preg_match($pattern, $inputText, $matches);
			$limitations = $matches[1];

			$pattern = '/<comment>(.*?)<\/comment>/s';
			preg_match($pattern, $inputText, $matches);
			$commentText = $matches[1];

			$comment['TEXT']['TYPE'] = 'PARENT';
			$comment['TEXT']['VIRTUES'] = $virtues;
			$comment['TEXT']['LIMITATIONS'] = $limitations;
			$comment['TEXT']['COMMENT'] = $commentText;

			$resImages = CBlogImage::GetList(array("ID"=>"DESC"), array('COMMENT_ID' => $comment['ID']));
			while($arImage = $resImages->Fetch()) {
				$comment['IMAGES'][] = $arImage;
			}

			if(isset($arComments[$comment['ID']])) {
				$comment['UF_ASPRO_COM_RATING'] = $arComments[$comment['ID']]['UF_ASPRO_COM_RATING'];
				$comment['UF_ASPRO_COM_LIKE'] = $arComments[$comment['ID']]['UF_ASPRO_COM_LIKE'];
				$comment['UF_ASPRO_COM_DISLIKE'] = $arComments[$comment['ID']]['UF_ASPRO_COM_DISLIKE'];
				if($comment['UF_ASPRO_COM_RATING']) {
					$arCommentsRating['COUNT']++;
				 	$arCommentsRating['VALUE'] += $arComments[$comment['ID']]['UF_ASPRO_COM_RATING'];
				}
			}

			if(!$comment['PARENT_ID']) {
				$arCommentsSort[] = $comment;
			}			

		}
	}

	if($arCommentsSort) {

		$arResult['REVIEWS_COUNT'] = count($arCommentsSort);

		if($arParams['SORT_PROP'] == 'DateFormated') {
			if($arParams['SORT_ORDER'] != 'SORT_DESC') {
				function sortByDate($a, $b) {
					$res = strtotime($a['DateFormated']) - strtotime($b['DateFormated']);
					return $res;
				}
			} else {
				function sortByDate($a, $b) {
					$res = strtotime($b['DateFormated']) - strtotime($a['DateFormated']);
					return $res;
				}
			}
			$result = usort($arCommentsSort, 'sortByDate');
		} else {
			$arParams['SORT_ORDER'] = $arParams['SORT_ORDER'] == 'SORT_DESC' ? SORT_DESC : SORT_ASC;
			\Bitrix\Main\Type\Collection::sortByColumn($arCommentsSort, array($arParams['SORT_PROP'] => array($arParams['SORT_ORDER']), "DATE_CREATE" => array(SORT_DESC)));
		}

		$arResult["CommentsResult"][0] = $arCommentsSort;

		if($arCommentsRating['COUNT']) {
			$arResult['ALL_RATING_VALUE'] = round( $arCommentsRating['VALUE'] / $arCommentsRating['COUNT'], 1);
		}

	}

	unset($comment);
	unset($comments);
}
?>
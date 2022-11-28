<?define("STATISTIC_SKIP_ACTIVITY_CHECK", "true");?>
<?define('STOP_STATISTICS', true);
define('PUBLIC_AJAX_MODE', true);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?
$context=\Bitrix\Main\Context::getCurrent();
$request=$context->getRequest();

$arSectionData = $request->getPost("sectionData");

if(!$iblockID = $arSectionData['iblockId']) {
	showStoriesError('no valid iblockId!');
}

if(!$sectionID = $arSectionData['sectionId']) {
	showStoriesError('no valid sectionId!');
}

$arSortData = $request->getPost("sortData");
$arSortData['SORT'] = $arSortData['sort'] ? $arSortData['sort'] : 'SORT';
$arSortData['SORT_ORDER'] = $arSortData['sortOrder'] ? $arSortData['sortOrder'] : 'ASC';
$arSortData['SORT_2'] = $arSortData['sort2'] ? $arSortData['sort2'] : 'ID';
$arSortData['SORT_ORDER_2'] = $arSortData['sort2Order'] ? $arSortData['sort2Order'] : 'ASC';

\Bitrix\Main\Loader::includeModule('iblock');

$arParams = array(
	'IBLOCK_ID' => $iblockID,
	'SORT_DATA' => $arSortData,
);
$arSections = CMax::getStoriesSections($arParams);
if($arSections) {
	echo \Bitrix\Main\Web\Json::encode($arSections);
} else {
	showStoriesError('no have sections!');
}

function showStoriesError($errorText) {
	$arResult = array(
		'error' => $errorText,
	);
	echo \Bitrix\Main\Web\Json::encode($arResult);
	die();
}
?>
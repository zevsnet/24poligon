<?define("STATISTIC_SKIP_ACTIVITY_CHECK", "true");?>
<?define('STOP_STATISTICS', true);
define('PUBLIC_AJAX_MODE', true);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?\Bitrix\Main\Loader::includeModule('aspro.max');?>
<?if($_POST["element"]){
	$filter = $_POST['filter'];
	if($filter) {
		$filter = \Bitrix\Main\Web\Json::decode($filter);
	}
	if( !$filter || !isset($filter['SECTION_CODE']) ) {
		if($_SESSION['FAST_VIEW_IS_CATALOG']) {
			if(!$filter['SECTION_ID'] && !$filter['SECTION_CODE']) {
				$filter['SECTION_ID'] = $_POST['section'];
			}
		} else {
			$filter['SECTION_ID'] = $_POST['section'];
		}
	}

	$filter['INCLUDE_SUBSECTIONS'] = 'Y';
	$filter['ACTIVE'] = 'Y';
	$filter['GLOBAL_ACTIVE'] = 'Y';

	if(!isset($filter['IBLOCK_ID'])){
		$filter['IBLOCK_ID'] = $_POST["iblock"];
	}

	$sort = $_POST['sort'];
	if($sort) {
		$sort = \Bitrix\Main\Web\Json::decode($sort);
		$arOrder = $sort;
	}
	$arOrder['CACHE'] = array( "TAG" => CMaxCache::GetIBlockCacheTag($_POST["iblock"]) );

	$elementId = $_POST["element"];
	$action = $_POST["action"];

	// echo '<pre>';
	// print_r($arOrder);
	// echo '</prE>';

	// echo '<pre>';
	// print_r($filter);
	// echo '</prE>';

	$elements = CMaxCache::CIblockElement_GetList($arOrder, $filter, false, false, array('DETAIL_PAGE_URL'));

	// echo '<pre>';
	// print_r($elements);
	// echo '</prE>';

	foreach ($elements as $key => $el) {
		if($el['ID'] == $elementId) {
			if($action == 'next') {
				if(isset($elements[$key+1])) {
					$result['DETAIL_PAGE_URL'] = $elements[$key+1]['DETAIL_PAGE_URL'];
				} else {
					$result['DETAIL_PAGE_URL'] = $elements[0]['DETAIL_PAGE_URL'];
				}
			} else {
				if(isset($elements[$key-1])) {
					$result['DETAIL_PAGE_URL'] = $elements[$key-1]['DETAIL_PAGE_URL'];
				} else {
					$result['DETAIL_PAGE_URL'] = end($elements)['DETAIL_PAGE_URL'];
				}
			}
		}
	}

	$_GET['iblock_id'] = $filter['IBLOCK_ID'];
	$_GET['item_href'] = $result['DETAIL_PAGE_URL'];
	$_GET['skip_preview'] = true;

	include('fast_view.php');
}?>
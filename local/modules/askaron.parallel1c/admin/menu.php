<?
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if($APPLICATION->GetGroupRight("askaron.parallel1c")!="D")
{
    CModule::IncludeModule('askaron.parallel1c');
	$aMenu = array(
		"parent_menu" => "global_menu_store",
		"section" => "askaron.parallel1c",
		"sort" => 1055,
        "module_id" => "askaron.parallel1c",
		"text" => Loc::getMessage("ASKARON_PARALLEL1C1_MENU_MAIN"),
		"title" => Loc::getMessage("ASKARON_PARALLEL1C1_MENU_MAIN_TITLE"),
		"url" => "askaron_parallel1c_exchange_admin.php?lang=".LANGUAGE_ID,
		"more_url" => Array(
//			"askaron_parallel1c_review_admin.php",
//			"askaron_parallel1c_review_edit.php",
//			"perfmon_table.php?lang=".LANGUAGE_ID."&table_name=b_askaron_parallel1c_exchange",
		),
		"icon" => "askaron_parallel1c_menu_icon",
		"page_icon" => "askaron_parallel1c_page_icon",
		"items_id" => "menu_askaron_parallel1c",
		"items" => array(
			array(
				"text" => Loc::getMessage("ASKARON_PARALLEL1C1_MENU_EXCHANGES"),
				"url" => "askaron_parallel1c_exchange_admin.php?lang=".LANGUAGE_ID,
				"more_url" => Array(
					"askaron_parallel1c_exchange_admin.php",
					"askaron_parallel1c_exchange_edit.php",
					"perfmon_table.php?lang=".LANGUAGE_ID."&table_name=b_askaron_parallel1c_exchange",
				),
				"title" => Loc::getMessage("ASKARON_PARALLEL1C1_MENU_EXCHANGES_TITLE"),
			),
			array(
				"text" => Loc::getMessage("ASKARON_PARALLEL1C1_SETTINGS_PAGE"),
				"url" => "settings.php?mid=askaron.parallel1c&lang=".LANGUAGE_ID."&mid_menu=2",
				"more_url" => Array(
				),
				"title" => Loc::getMessage("ASKARON_PARALLEL1C1_SETTINGS_PAGE_TITLE"),
			),
		)
	);
	return $aMenu;
}
return false;
?>

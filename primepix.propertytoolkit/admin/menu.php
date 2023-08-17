<?

IncludeModuleLangFile($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/primepix.propertytoolkit/admin/menu.php');

$aMenu[] = array(
	"parent_menu" => "global_menu_services",
	"section"     => "primepix_propertytoolkit",
	"sort"        => 9999,
	"text"        => GetMessage("primepix.propertytoolkit_TOP_MENU_ITEM"),
	"title"       => GetMessage("primepix.propertytoolkit_TOP_MENU_ITEM_TITLE"),
	"items_id"    => "primepix_propertytoolkit_menu",
	"icon"        => "util_menu_icon",
	"more_url"    => array("/bitrix/admin/ppt_list_values.php", "/bitrix/admin/ppt_string_values.php" , "/bitrix/admin/ppt_number_values.php"),
	"items"       => array(
		array(
			"text" => GetMessage("primepix.propertytoolkit_TOP_MENU_ITEM_LIST"),
			"url"  => "/bitrix/admin/ppt_list.php"
		),
		array(
			"text" => GetMessage("primepix.propertytoolkit_TOP_MENU_ITEM_NUMBER"),
			"url"  => "/bitrix/admin/ppt_numbers.php"
		),
		array(
			"text" => GetMessage("primepix.propertytoolkit_TOP_MENU_ITEM_STRING"),
			"url"  => "/bitrix/admin/ppt_strings.php"
		)
	)
);

return $aMenu;

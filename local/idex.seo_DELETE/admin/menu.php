<?
IncludeModuleLangFile(__FILE__);

if($APPLICATION->GetGroupRight("idex.seo") != "D") {
	$aMenu[] = array(
		"parent_menu" => "global_menu_services",
		"section" => "idex_seo",
		"text" => ' ' . GetMessage('IDEX_SEO_MODULE_NAME'),
		"title" => GetMessage('IDEX_SEO_MODULE_NAME'),
		"icon" => "idex_seo_menu_icon",
		"page_icon" => "idex_seo_page_icon", 		
		"items_id" => "idex_seo_menu",
		"url" => "idex_seo_page_list.php?lang=".LANGUAGE_ID,
		"items" => array(
			array(
				"text" => GetMessage('IDEX_SEO_PAGE_LIST'), 				
				"title" => GetMessage('IDEX_SEO_PAGE_LIST'), 	  
				"more_url" => array("idex_seo_page_add.php?lang=".LANGUAGE_ID), 
				"url" => "idex_seo_page_list.php?lang=".LANGUAGE_ID, 				
			)			
		)
	);	

	return $aMenu;
}
return false;
?>
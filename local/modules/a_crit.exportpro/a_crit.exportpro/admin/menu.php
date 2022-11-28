<?php

IncludeModuleLangFile( __FILE__ );

if( $APPLICATION->GetGroupRight( "a_crit.exportpro" ) != "D" ){
	$aMenu = array(
		"parent_menu" => "global_menu_acrit",
		"section" => GetMessage( "ACRIT_EXPORTPRO_SECTION" ),
		"sort" => 100,
		"text" => GetMessage( "ACRIT_EXPORTPRO_SECTION" ),
		"title" => GetMessage( "ACRIT_EXPORTPRO_MENU_TEXT" ),
		"url" => "",
		"icon" => "acrit_exportpro_menu_icon",
		"page_icon" => "",
		"items_id" => "menu_a_crit.exportpro",
		"items" => array(
			array(
				"text" => GetMessage( "ACRIT_EXPORTPRO_MENU_TITLE" ),
				"url" => "acrit_exportpro_list.php?lang=".LANGUAGE_ID,
				"more_url" => array(
                    "acrit_exportpro_list.php",
                    "acrit_exportpro_edit.php"
                ),
				"title" => GetMessage( "ACRIT_EXPORTPRO_MENU_TITLE" ),
			),
			array(
                "text" => GetMessage( "ACRIT_EXPORTPRO_MENU_PROFILE_EXPORT" ),
                "url" => "acrit_exportpro_export.php",
                "more_url" => array( "acrit_exportpro_export.php" ),
                "title" => GetMessage( "ACRIT_EXPORTPRO_MENU_PROFILE_EXPORT" )
            ),
            array(
                "text" => GetMessage( "ACRIT_EXPORTPRO_MENU_PROFILE_ANALYTICS" ),
                "url" => "acrit_exportpro_analytics.php",
                "more_url" => array( "acrit_exportpro_analytics.php" ),
                "title" => GetMessage( "ACRIT_EXPORTPRO_MENU_PROFILE_ANALYTICS" )
            ),
            array(
				"text" => GetMessage( "ACRIT_EXPORTPRO_MENU_PROFILE_ADVMANAGEMENT" ),
				"url" => "acrit_exportpro_advmanagement.php",
				"more_url" => array( "acrit_exportpro_advmanagement.php" ),
				"title" => GetMessage( "ACRIT_EXPORTPRO_MENU_PROFILE_ADVMANAGEMENT" )
			),
		)
	);
	return $aMenu;
}
return false;
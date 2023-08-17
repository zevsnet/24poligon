<div class="mobilemenu-v1 scroller">
	<div class="wrap">
		<?if(CMax::nlo('menu-mobile', 'class="loadings" style="height:47px;"')):?>
		<!-- noindex -->
		<?$APPLICATION->IncludeComponent(
			"bitrix:menu",
			"top_mobile",
			Array(
				"COMPONENT_TEMPLATE" => "top_mobile",
				"MENU_CACHE_TIME" => "3600000",
				"MENU_CACHE_TYPE" => "A",
				"MENU_CACHE_USE_GROUPS" => "N",
				"MENU_CACHE_GET_VARS" => array(
				),
				"DELAY" => "N",
				"MAX_LEVEL" => \Bitrix\Main\Config\Option::get("aspro.max", "MAX_DEPTH_MENU", 2),
				"ALLOW_MULTI_SELECT" => "Y",
				"ROOT_MENU_TYPE" => "top_content_multilevel",
				"CHILD_MENU_TYPE" => "left",
				"CACHE_SELECTED_ITEMS" => "N",
				"ALLOW_MULTI_SELECT" => "Y",
				"USE_EXT" => "Y"
			)
		);?>
		<!-- /noindex -->
		<?endif;?>
		<?CMax::nlo('menu-mobile');?>
		<?
		// show regions
		CMax::ShowMobileRegions();

		// show sites
		$arShowSites = \Aspro\Functions\CAsproMax::getShowSites();
		$countSites = count($arShowSites);
		if ($countSites > 1) : ?>
			<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
				array(
					"COMPONENT_TEMPLATE" => ".default",
					"PATH" => SITE_DIR."/include/header_include/site.selector.php",
					"AREA_FILE_SHOW" => "file",
					"AREA_FILE_SUFFIX" => "",
					"AREA_FILE_RECURSIVE" => "Y",
					"EDIT_TEMPLATE" => "include_area.php",
					"TEMPLATE_SITE_SELECTOR" => "mobile",
					"SITE_LIST" => $arShowSites,
				),
				false, array("HIDE_ICONS" => "Y")
			);?>
		<?endif;?>

		<?
		// show cabinet item
		CMax::ShowMobileMenuCabinet();

		// show basket item
		CMax::ShowMobileMenuBasket();

		// use module options for change contacts
		CMax::ShowMobileMenuContacts();
		?>
		<?$APPLICATION->IncludeComponent(
			"aspro:social.info.max",
			"",
			array(
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "3600000",
				"CACHE_GROUPS" => "N",
				"COMPONENT_TEMPLATE" => ".default"
			),
			false
		);?>
	</div>
</div>
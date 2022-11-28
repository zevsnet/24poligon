<?global $APPLICATION, $arRegion, $arSite, $arTheme, $bIndexBot, $is404, $isForm, $isIndex;?>
<?if($APPLICATION->GetProperty("viewed_show") == "Y" || $is404):?>
	<?$APPLICATION->IncludeComponent(
		"bitrix:main.include",
		"basket",
		array(
			"COMPONENT_TEMPLATE" => "basket",
			"PATH" => SITE_DIR."include/footer/comp_viewed.php",
			"AREA_FILE_SHOW" => "file",
			"AREA_FILE_SUFFIX" => "",
			"AREA_FILE_RECURSIVE" => "Y",
			"EDIT_TEMPLATE" => "standard.php",
			"PRICE_CODE" => array(
				0 => "BASE",
			),
			"STORES" => array(
				0 => "",
				1 => "",
			),
			"BIG_DATA_RCM_TYPE" => "bestsell"
		),
		false
	);?>
<?endif;?>
<?CMax::ShowPageType('footer');?>

<?include_once('top_footer_custom.php');?>

<!-- marketnig popups -->
<?$APPLICATION->IncludeComponent(
	"aspro:marketing.popup.max", 
	".default", 
	array(),
	false, array('HIDE_ICONS' => 'Y')
);?>
<!-- /marketnig popups -->
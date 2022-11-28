<?global $APPLICATION, $arRegion, $arSite, $arTheme, $bIndexBot, $is404, $isForm, $isIndex;?>

<?if(!$is404 && !$isForm && !$isIndex):?>
	<?$APPLICATION->ShowViewContent('section_bnr_content');?>
	<?if($APPLICATION->GetProperty("HIDETITLE") !== 'Y'):?>
		<!--title_content-->
		<?CMax::ShowPageType('page_title');?>
		<!--end-title_content-->
	<?endif;?>
	<?$APPLICATION->ShowViewContent('top_section_filter_content');?>
<?endif;?>

<?include_once('top_wraps_custom.php');?>
<?global $APPLICATION, $arRegion, $arSite, $arTheme, $bIndexBot;?>

<?if(!CMax::checkAjaxRequest()):?>
	<div id="panel"><?$APPLICATION->ShowPanel();?></div>
<?endif;?>
<?include_once('body_top_custom.php');?>
<?define("STATISTIC_SKIP_ACTIVITY_CHECK", "true");?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if(!CModule::IncludeModule("sale") || !CModule::IncludeModule("catalog") || !CModule::IncludeModule("iblock")){
	echo "failure";
	return;
}

if(\Bitrix\Main\Loader::IncludeModule('aspro.max'))
{
	$iblockID=(isset($_GET["iblockID"]) ? $_GET["iblockID"] : CMaxCache::$arIBlocks[SITE_ID]['aspro_max_catalog']['aspro_max_catalog'][0] );
	$arItems=CMax::getBasketItems($iblockID);

	?>
	<script type="text/javascript">
		var arBasketAspro = <? echo CUtil::PhpToJSObject($arItems, false, true); ?>;
		if(typeof obMaxPredictions === 'object'){
			obMaxPredictions.updateAll();
		}
	</script>
<?}?>
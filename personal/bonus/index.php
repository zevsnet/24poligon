<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Бонусный счет");
CJSCore::Init('aspro_font_awesome');
?>
<?//start logictim bonus?>
<?if(\CMax::GetFrontParametrValue("BONUS_SYSTEM") === 'LOGICTIM' && \CModule::IncludeModule('logictim.balls')):?>
	<?$APPLICATION->IncludeComponent(
		"logictim:bonus.history",
		"",
		Array(
			"FIELDS" => array("ID","DATE","NAME","OPERATION_SUM","BALLANCE_BEFORE","BALLANCE_AFTER"),
			"ORDER_LINK" => "N",
			"OPERATIONS_WAIT" => "Y",
			"ORDER_URL" => "/personal/order/",
			"PAGE_NAVIG_LIST" => "30",
			"PAGE_NAVIG_TEMP" => "arrows",
			"SORT" => "DESC"
		)
	);?>
<?endif;?>
<?//end logictim bonus?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
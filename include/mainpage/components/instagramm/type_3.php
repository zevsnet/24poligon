<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	define("STATISTIC_SKIP_ACTIVITY_CHECK", "true");
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
}?>
<?$APPLICATION->IncludeComponent(
	"aspro:wrapper.block.max",
	"front_instagramm",
	array(
		"COMPONENT_TEMPLATE" => "front_instagramm",
		"WIDE_BLOCK" => "Y",
		"NO_MARGIN" => "Y",
		"LINE_ELEMENT_COUNT" => "8",
		"PAGE_ELEMENT_COUNT" => "8",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "86400",
		"CACHE_GROUPS" => "N",
	),
	false
);?>
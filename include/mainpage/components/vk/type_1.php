<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	define("STATISTIC_SKIP_ACTIVITY_CHECK", "true");
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
}?>
<?$APPLICATION->IncludeComponent(
	"aspro:wrapper.block.max", 
	"front_vk", 
	array(
		"COMPONENT_TEMPLATE" => "front_vk",
		"VIEW_TYPE" => "HORIZONTAL",
		"WIDE_BLOCK" => "N",
		"NO_MARGIN" => "N",
		"LINE_ELEMENT_COUNT" => "4",
		"PAGE_ELEMENT_COUNT" => "4",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "86400",
		"CACHE_GROUPS" => "N",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>
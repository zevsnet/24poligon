<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$arScripts = ['swiper', 'swiper_main_styles', 'top_banner', 'countdown'];
if ($templateData['HAS_VIDEO']) {
	$arScripts[] = 'video_banner';
}
\Aspro\Max\Functions\Extensions::init($arScripts);
?>
<?include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/components/aspro/com.banners.max/common_files/epilog_action.php');?>
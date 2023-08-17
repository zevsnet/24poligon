<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
$arScripts = ['swiper'];

if( CMax::GetFrontParametrValue("HOVER_TYPE_IMG") !== 'none' )
	$arScripts[] = 'animation_ext';
\Aspro\Max\Functions\Extensions::init($arScripts);
?>
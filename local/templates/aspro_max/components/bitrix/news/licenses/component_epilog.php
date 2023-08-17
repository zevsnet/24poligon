<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
global $arTheme;
$arScripts = [];

if( $arTheme['HOVER_TYPE_IMG']['VALUE'] !== 'none' )
	$arScripts[] = 'animation_ext';
?>

<? if( count($arScripts) ): ?>
	<? \Aspro\Max\Functions\Extensions::init($arScripts); ?>
<? endif; ?>
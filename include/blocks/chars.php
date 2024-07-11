<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

global $arTheme, $APPLICATION;

$arOptions = $arConfig['PARAMS'];
$iblockId = $arOptions['IBLOCK_ID'];
$grupperProps = $arOptions['GRUPPER_PROPS'];
$component = $arConfig['PARENT_COMPONENT'] ?? false;

if ($grupperProps != 'NOT') {
	$arOptions['PROPERTIES_DISPLAY_TYPE'] = 'TABLE';

	if (
		(
			$grupperProps == 'GRUPPER' &&
			!\Bitrix\Main\Loader::includeModule('redsign.grupper')
		) ||
		(
			$grupperProps == 'WEBDEBUG' &&
			!\Bitrix\Main\Loader::includeModule('webdebug.utilities')
		) ||
		(
			$grupperProps == 'YENISITE_GRUPPER' &&
			!\Bitrix\Main\Loader::includeModule('yenisite.infoblockpropsplus')
		)
	) {
		$grupperProps = 'NOT';
	}
}
?>
<?if ($iblockId):?>
	<?if ($grupperProps == 'GRUPPER'):?>
		<div class="props_block bordered rounded-x">
			<div class="props_block__wrapper">
				<?$APPLICATION->IncludeComponent(
					"redsign:grupper.list",
					"",
					Array(
						"CACHE_TIME" => "3600000",
						"CACHE_TYPE" => "A",
						"COMPOSITE_FRAME_MODE" => "A",
						"COMPOSITE_FRAME_TYPE" => "AUTO",
						"DISPLAY_PROPERTIES" => $arOptions['CHARACTERISTICS'] ?? [],
					),
					$component,
					array('HIDE_ICONS' => 'Y')
				);?>
			</div>
		</div>
	<?elseif ($grupperProps == 'WEBDEBUG'):?>
		<div class="props_block bordered rounded-x">
			<div class="props_block__wrapper">
				<?$APPLICATION->IncludeComponent(
					"webdebug:propsorter",
					"linear",
					array(
						"IBLOCK_TYPE" => $arOptions['IBLOCK_TYPE'] ?? '',
						"IBLOCK_ID" => $iblockId,
						"PROPERTIES" => $arOptions['CHARACTERISTICS'] ?? [],
						"EXCLUDE_PROPERTIES" => array(),
						"WARNING_IF_EMPTY" => "N",
						"WARNING_IF_EMPTY_TEXT" => "",
						"NOGROUP_SHOW" => "Y",
						"NOGROUP_NAME" => "",
						"MULTIPLE_SEPARATOR" => ", ",
					),
					$component,
					array('HIDE_ICONS' => 'Y')
				);?>
			</div>
		</div>
	<?elseif ($grupperProps == 'YENISITE_GRUPPER'):?>
		<div class="props_block bordered rounded-x">
			<div class="props_block__wrapper">
				<?$APPLICATION->IncludeComponent(
					'yenisite:ipep.props_groups',
					'',
					array(
						'DISPLAY_PROPERTIES' => $arOptions['CHARACTERISTICS'] ?? [],
						'IBLOCK_ID' => $iblockId,
					),
					$component,
					array('HIDE_ICONS' => 'Y')
				)?>
			</div>
		</div>
	<?else:?>
		<div class="props_block">
			<div class="props_block__wrapper">
				<?$APPLICATION->IncludeComponent(
					'aspro:props.group.max',
					'',
					array(
						'DISPLAY_PROPERTIES' => $arOptions['CHARACTERISTICS'] ?? [],
						'IBLOCK_ID' => $iblockId,
						'OFFERS_IBLOCK_ID' => $arOptions['SKU_IBLOCK_ID'] ?? '',
						'OFFER_DISPLAY_PROPERTIES' => $arOptions['OFFER_PROP'] ?? [],
						'SHOW_HINTS' => $arOptions['SHOW_HINTS'] ?? 'N',
						'OFFERS_MODE' => $arOptions['OFFERS_MODE'] ?? 'N',
						'PROPERTIES_DISPLAY_TYPE' => $arOptions['PROPERTIES_DISPLAY_TYPE'],
					),
					$component,
					array('HIDE_ICONS' => 'Y')
				)?>
			</div>
		</div>
	<?endif;?>
<?endif;?>
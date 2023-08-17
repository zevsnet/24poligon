<?
use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

global $arTheme;
$this->setFrameMode(true);
?>
<?if(!$arResult['POPUP']):?>
	<?if(
		(
			$arResult['USE_REGIONALITY'] &&
			$arResult['CURRENT_REGION']
		) ||
		(
			!$arResult['USE_REGIONALITY'] &&
			$arResult['SHOW_CITY'] &&
			strlen($arResult['CURRENT_REGION_TITLE_IN_HEADER'])
		)
	):?>
		<div class="region_wrapper">
			<div class="io_wrapper">
				<?=CMax::showSpriteIconSvg(SITE_TEMPLATE_PATH."/images/svg/header_icons_srite.svg#location", "svg-inline-mark ", ['WIDTH' => 13,'HEIGHT' => 13]);?>
				<div class="city_title"><?=Loc::getMessage('CITY_TITLE');?></div>

				<?if (
					$arResult['USE_REGIONALITY'] &&
					$arResult['CURRENT_REGION']
				):?>
					<div class="js_city_chooser animate-load dark-color" data-event="jqm" data-name="city_chooser" data-param-url="<?=urlencode($APPLICATION->GetCurUri());?>" data-param-form_id="city_chooser">
						<span><?=$arResult['CURRENT_REGION_TITLE_IN_HEADER']?></span><span class="arrow"><?=CMax::showSpriteIconSvg(SITE_TEMPLATE_PATH."/images/svg/header_icons_srite.svg#Triangle_down", "down ", ['WIDTH' => 5,'HEIGHT' => 3]);?></span>
					</div>
				<?else:?>
					<div class="js_city_chooser no-pointer-events">
						<span><?=$arResult['CURRENT_REGION_TITLE_IN_HEADER']?></span>
					</div>
				<?endif;?>
			</div>

			<?if($arResult['SHOW_REGION_CONFIRM']):?>
				<div class="confirm_region">
					<span class="close colored_theme_hover_text " data-id="<?=$arResult['CURRENT_REGION']['ID'];?>"><?=CMax::showIconSvg('', SITE_TEMPLATE_PATH.'/images/svg/Close.svg', '', 'light-ignore')?></span>
					<?
					$href = 'data-href="'.$arResult['REGIONS'][$arResult['REAL_REGION']['ID']]['URL'].'"';
					if($arTheme['USE_REGIONALITY']['DEPENDENT_PARAMS']['REGIONALITY_TYPE']['VALUE'] == 'SUBDOMAIN' && ($arResult['SCHEME'].$_SERVER['HTTP_HOST'].$arResult['URI'] == $arResult['REGIONS'][$arResult['REAL_REGION']['ID']]['URL']))
					$href = '';?>
					<div class="title"><?=Loc::getMessage('CITY_TITLE')?> <?=$arResult['REAL_REGION_TITLE_IN_HEADER']?> ?</div>
					<div class="buttons">
						<span class="btn btn-default aprove" data-id="<?=$arResult['REAL_REGION']['ID'];?>" <?=$href;?>><?=Loc::getMessage('CITY_YES');?></span>
						<span class="btn btn-default white js_city_change"><?=Loc::getMessage('CITY_CHANGE');?></span>
					</div>
				</div>
			<?endif;?>
		</div>
	<?endif;?>
<?else:?>
	<div class="popup_regions <?=($arResult['ONLY_SEARCH_ROW'] ? 'only_search' : '')?>">
		<div class="h-search autocomplete-block" id="title-search-city">
			<div class="wrapper">
				<input id="search" class="autocomplete text" type="text" placeholder="<?=Loc::getMessage('CITY_PLACEHOLDER');?>">
				<div class="search_btn"><?=CMax::showIconSvg("search2", SITE_TEMPLATE_PATH."/images/svg/Search.svg");?></div>
			</div>

			<?if($arResult['FAVORITS']):?>
				<div class="favorits">
					<span class="title"><?=GetMessage('EXAMPLE_CITY');?></span>
					<div class="cities">
						<?foreach($arResult['FAVORITS'] as $arItem):?>
							<div class="item">
								<a href="<?=$arItem['URL'];?>" data-id="<?=$arItem['ID'];?>" data-locid="<?=$arItem['LOCATION_ID']?>" class="name"><?=$arItem['NAME'];?></a>
							</div>
						<?endforeach;?>
					</div>
				</div>
			<?endif;?>
		</div>

		<?if(!$arResult['ONLY_SEARCH_ROW']):?>
			<div class="items ext_view">
				<?if($arResult['REGIONS']):?>
					<div class="block regions level1">
						<div class="title"><?=GetMessage('OKRUG')?></div>
						<div class="items_block scrollblock"></div>
					</div>

					<div class="block regions level2">
						<div class="title"><?=GetMessage('REGION')?></div>
						<div class="items_block scrollblock"></div>
					</div>

					<div class="block cities">
						<div class="title"><?=Loc::getMessage('CITY');?></div>
						<div class="items_block scrollblock"></div>
					</div>
				<?endif;?>
			</div>
			<script>
			BX.message({
				OKRUG: '<?=GetMessageJS('OKRUG')?>',
				REGION: '<?=GetMessageJS('REGION')?>',
				CITY: '<?=GetMessageJS('CITY')?>',
			});
			</script>
		<?endif;?>
	</div>
<?endif;?>

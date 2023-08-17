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
					<div class="js_city_chooser dark-color list" data-param-url="<?=urlencode($APPLICATION->GetCurUri());?>" data-param-form_id="city_chooser">
						<span><?=$arResult['CURRENT_REGION_TITLE_IN_HEADER']?></span><span class="arrow"><?=CMax::showIconSvg("down", SITE_TEMPLATE_PATH."/images/svg/trianglearrow_down.svg");?></span>
					</div>
				<?else:?>
					<div class="js_city_chooser no-pointer-events">
						<span><?=$arResult['CURRENT_REGION_TITLE_IN_HEADER']?></span>
					</div>
				<?endif;?>
			</div>

			<?if (
				$arResult['USE_REGIONALITY'] &&
				$arResult['CURRENT_REGION']
			):?>
				<div class="dropdown">
					<div class="wrap">
						<?foreach($arResult['REGIONS'] as $id => $arItem):?>
							<div class="more_item <?=($id == $arResult['CURRENT_REGION']['ID'] ? 'current' : '');?>">
								<span data-region_id="<?=$arItem['ID']?>" data-href="<?=$arItem['URL'];?>"><?=$arItem['NAME'];?></span>
							</div>
						<?endforeach;?>
					</div>
				</div>
			<?endif;?>

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
<?endif;?>

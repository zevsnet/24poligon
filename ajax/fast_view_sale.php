<?define("STATISTIC_SKIP_ACTIVITY_CHECK", "true");?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?if(!$GLOBALS['bMobileForm']):?>
	<a href="#" class="close jqmClose"><?=CMax::showIconSvg('', SITE_TEMPLATE_PATH.'/images/svg/Close.svg')?></a>
<?endif;?>
<?
$context = \Bitrix\Main\Context::getCurrent();
$request = $context->getRequest();
?>
<div class="form popup-text-info">
	<?if($request['iblock_id'] && $request['id']):?>
		<?if(\Bitrix\Main\Loader::includeModule('aspro.max'))
		{
			$arFilter = array("IBLOCK_ID" => $request['iblock_id'], "ACTIVE"=>"Y", "ID" => $request["id"]);
			$arSelect = array("ID", "NAME", "DETAIL_TEXT", "PREVIEW_TEXT", "DETAIL_PAGE_URL", "ACTIVE_TO", "PROPERTY_PERIOD", "PROPERTY_SALE_NUMBER");
			$arItem = CMaxCache::CIBLockElement_GetList(array('CACHE' => array("TAG" => CMaxCache::GetIBlockCacheTag($request['iblock_id']), "MULTI" => "N")), $arFilter, false, false, $arSelect);
		}?>
		<?if($arItem):?>
			<div class="popup-text-info__title font_exlg"><?=$arItem["NAME"];?></div>
			<div class="popup-text-info__text">
				<?// date active period?>
				<?if($arItem['ACTIVE_TO'] || $arItem['PROPERTY_PERIOD_VALUE']):?>
					<div class="period-block popup-text-info__period muted ncolor <?=($arItem['ACTIVE_TO'] ? 'red' : '');?>">
						<?if($arItem['PROPERTY_SALE_NUMBER_VALUE'] || $arItem['ACTIVE_TO']):?>
							<div class="info-sticker-block static popup-text-info__timer">
								<?if($arItem['PROPERTY_SALE_NUMBER_VALUE']):?>
									<div class="sale-text font_sxs rounded2"><?=$arItem['PROPERTY_SALE_NUMBER_VALUE'];?></div>
								<?endif;?>
								<?if($arItem['ACTIVE_TO']):?>
									<?\Aspro\Functions\CAsproMax::showDiscountCounter(0, $arItem, array(), array(), '', 'compact red');?>
								<?endif;?>
							</div>
						<?endif;?>
						<div class="popup-text-info__date font_xs">
							<?=CMax::showIconSvg("sale", SITE_TEMPLATE_PATH.'/images/svg/icon_discount.svg', '', '', true, false);?>
							<?if(strlen($arItem['PROPERTY_PERIOD_VALUE'])):?>
								<span class="date"><?=$arItem['PROPERTY_PERIOD_VALUE']?></span>
							<?else:?>
								<span class="date">
									<?=CIBlockFormatProperties::DateFormat("j F Y", MakeTimeStamp($arItem["ACTIVE_TO"], CSite::GetDateFormat()))?></span>
							<?endif;?>
						</div>
					</div>
				<?endif;?>

				<?$obParser = new CTextParser;?>
				<?=$obParser->html_cut($arItem["DETAIL_TEXT"], 500);?>

				<div class="popup-text-info__btn">
					<a class="btn btn-default btn-lg" href="<?=$arItem["DETAIL_PAGE_URL"];?>"><?=\Bitrix\Main\Localization\Loc::getMessage("MORE_TEXT_LINK");?></a>
				</div>
			</div>
			<script>typeof useCountdown && useCountdown();</script>
		<?endif;?>
	<?else:?>
		ERROR
	<?endif;?>
</div>

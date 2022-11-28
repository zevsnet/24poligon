<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$frame = $this->createFrame()->begin();?>
<?
use \Bitrix\Main\Localization\Loc;
if(strlen($arResult["ERROR_MESSAGE"]) > 0){
	ShowError($arResult["ERROR_MESSAGE"]);
}
?>
<?if(count($arResult["STORES"]) > 0):?>
	<?
	// get shops
	$arShops = array();
	CModule::IncludeModule('iblock');
	$dbRes = CIBlock::GetList(array(), array('CODE' => 'aspro_max_shops', 'ACTIVE' => 'Y', 'SITE_ID' => SITE_ID));
	if ($arShospIblock = $dbRes->Fetch()){
		$dbRes = CIBlockElement::GetList(array(), array('ACTIVE' => 'Y', 'IBLOCK_ID' => $arShospIblock['ID']), false, false, array('ID', 'DETAIL_PAGE_URL', 'PROPERTY_STORE_ID'));
		while($arShop = $dbRes->GetNext()){
			$arShops[$arShop['PROPERTY_STORE_ID_VALUE']] = $arShop;
		}
	}
	?>
	<div class="stores_block_wrap">
		<div class="block_title text-upper font_xs font-bold"><?=Loc::getMessage('STORES_TITLE_ITEM')?><?=CMax::showIconSvg("close", SITE_TEMPLATE_PATH."/images/svg/Close.svg");?></div>
		<div class="block_wrap srollbar-custom">
			<div class="block_wrap_inner">
				<?$empty_count=0;
				$count_stores=count($arResult["STORES"]);?>
				<?foreach($arResult["STORES"] as $pid => $arProperty):
					$amount = (isset($arProperty['REAL_AMOUNT']) ? $arProperty['REAL_AMOUNT'] : $arProperty['AMOUNT']);
					if($arParams['SHOW_EMPTY_STORE'] == 'N' && $amount <= 0)
						$empty_count++;?>
					<div class="stores_block wo_image" <? echo ($arParams['SHOW_EMPTY_STORE'] == 'N' && $amount <= 0 ? 'style="display: none"' : ''); ?>>
						<div class="stores_text_wrapp">
							<div class="main_info">
								<?if (isset($arProperty["TITLE"])):?>
									<span>
									<?
									if($arParams['FIELDS'] && (in_array('TITLE', $arParams['FIELDS']) || in_array('ADDRESS', $arParams['FIELDS'])) ) {
										$setTitle = in_array('TITLE', $arParams['FIELDS']) && strlen($arProperty["TITLE"]);
										$setAddress = in_array('ADDRESS', $arParams['FIELDS']) && strlen($arProperty["ADDRESS"]);
										$storeName = ($setTitle ? $arProperty["TITLE"] : '');
										$storeName .= $setTitle && $setAddress ? ', ' : '';
										$storeName .= ($setAddress ? $arProperty["ADDRESS"] : '');
									} else {
										$storeName = $arProperty["TITLE"].(strlen($arProperty["ADDRESS"]) && strlen($arProperty["TITLE"]) ? ', ' : '').$arProperty["ADDRESS"];
									}
									?>
										<a class="title_stores font_xs dark_link" href="<?=$arProperty["URL"]?>" data-storehref="<?=$arProperty["URL"]?>" data-iblockhref="<?=$arShops[$arProperty['ID']]['DETAIL_PAGE_URL']?>"> <?=$storeName?></a>
									</span>
								<?endif;?>
								<?/*
								<?if(isset($arProperty["PHONE"])):?><div class="store_phone p10 font_xs"><?=Loc::getMessage('S_PHONE')?> <?=$arProperty["PHONE"]?></div><?endif;?>
								<?if(isset($arProperty["SCHEDULE"])):?><div class="schedule p10 font_xs"><?=Loc::getMessage('S_SCHEDULE')?>&nbsp;<?=str_replace("&lt;br/&gt;", "<br/>", $arProperty["SCHEDULE"]);?></div><?endif;?>
								<?if(isset($arProperty["EMAIL"]) && $arProperty["EMAIL"]):?><div class="email p10 font_xs"><?=GetMessage('S_EMAIL')?>&nbsp;<a href="<?='mailto:'.$arProperty["EMAIL"]?>"><?=$arProperty["EMAIL"];?></a></div><?endif;?>
								<?if (!empty($arProperty['USER_FIELDS']) && is_array($arProperty['USER_FIELDS'])){
									foreach ($arProperty['USER_FIELDS'] as $userField){
										if (isset($userField['CONTENT'])){
											?><span class="font_xs"><?=$userField['TITLE']?>: <?=$userField['CONTENT']?></span><br /><?
										}
									}
								}?>
								*/?>
								<?if ($arParams['SHOW_GENERAL_STORE_INFORMATION'] == "Y"){?>
									<?=Loc::getMessage('BALANCE')?>
								<?}?>
							</div>
						</div>
						<?
						$totalCount = CMax::CheckTypeCount($arProperty["NUM_AMOUNT"]);
						$arQuantityData = CMax::GetQuantityArray($totalCount);
						?>
						<?if(strlen($arQuantityData["TEXT"])):?>
							<?=$arQuantityData["HTML"]?>
						<?endif;?>
					</div>
				<?endforeach;?>
				<?if($empty_count==$count_stores){?>
					<div class="stores_block">
						<div class="stores_text_wrapp"><?=Loc::getMessage('NO_STORES')?></div>
					</div>
				<?}?>
			</div>
		</div>
		<div class="more-btn text-center">
			<a href="<?=$arParams['MORE_URL'];?>" class="font_upper colored_theme_hover_bg"><?=Loc::getMessage('MORE_LINK')?></a>
		</div>
	</div>
<?endif;?>
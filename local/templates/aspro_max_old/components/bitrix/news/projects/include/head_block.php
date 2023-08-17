<?if($arParams['TYPE_HEAD_BLOCK']=='years_mix' || $arParams['TYPE_HEAD_BLOCK']=='years_links'):?>
	<?$arItems = CMaxCache::CIBLockElement_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']))), $arItemFilter, false, false, array('ID', 'NAME', 'ACTIVE_FROM'));
	$arYears = array();//var_dump($arItemFilter);
	if($arItems)
	{
		foreach($arItems as $arItem)
		{
			if($arItem['ACTIVE_FROM'])
			{
				if($arDateTime = ParseDateTime($arItem['ACTIVE_FROM'], FORMAT_DATETIME))
					$arYears[$arDateTime['YYYY']] = $arDateTime['YYYY'];
			}
		}
		if($arYears)
		{
			if($arParams['USE_FILTER'] != 'N')
			{
				rsort($arYears);
				$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/mixitup.min.js');
				$bHasYear = (isset($_GET['year']) && (int)$_GET['year']);
				$year = ($bHasYear ? (int)$_GET['year'] : 0);?>
				<div class="select_head_wrap">
					<div class="menu_item_selected font_upper_md rounded3 bordered visible-xs font_xs darken"><span></span>
						<?=CMax::showIconSvg("down", SITE_TEMPLATE_PATH.'/images/svg/trianglearrow_down.svg', '', '', true, false);?>
					</div>
					<div class="head-block top bordered-block rounded3 clearfix srollbar-custom">
						<div class="item-link font_upper_md  <?=($bHasYear ? '' : 'active');?>">
							<div class="title">
								<?if($bHasYear || ($useDateLink && !$bHasYear)):?>
									<a class="btn-inline dark_link" href="<?=$arResult['FOLDER'];?>"><?=GetMessage('ALL_TIME');?></a>
								<?else:?>
									<span class="btn-inline darken" data-filter="all"><?=GetMessage('ALL_TIME');?></span>
								<?endif;?>
							</div>
						</div>
						<?foreach($arYears as $value):
							$bSelected = ($bHasYear && $value == $year);?>
							<div class="item-link font_upper_md <?=($bSelected ? 'active' : '');?>">
								<div class="title btn-inline darken">
									<?if(!$bHasYear && !$useDateLink):?>
										<span class="btn-inline darken" data-filter=".d-<?=$value?>"><?=$value;?></span>
									<?else:?>

										<?if($bSelected):?>
											<span class="btn-inline darken"><?=$value;?></span>
										<?else:?>
											<a class="btn-inline dark_link" href="<?=$APPLICATION->GetCurPageParam('year='.$value, array('year'));?>"><?=$value;?></a>
										<?endif;?>
									<?endif;?>
								</div>
							</div>
						<?endforeach;?>
					</div>
				</div>
					
				
				<?if(0):?><div class="select_wrapper visible-xs">
					<select class=" form-control" > 
						<option value="<?=$arResult['FOLDER']?>"<?=(!$year ? ' selected' : '')?>><?=GetMessage('ALL_TIME');?></option> 
						<?foreach($arYears as $value):?> 
							<? 
							$bSelected = ($bHasYear && $value == $year); 
							?> 
						<option data-mix_filter=".d-<?=$value?>" value="<?=$APPLICATION->GetCurPageParam('year='.$value, array('year'));?>"<?=($bSelected ? ' selected' : '')?>><?=$value?></option> 
						<?endforeach?> 
					</select>
				</div><?endif;?>
				<?
				if($bHasYear)
				{
					$GLOBALS[$arParams["FILTER_NAME"]][] = array(
						">=DATE_ACTIVE_FROM" => ConvertDateTime("01.01.".$year, FORMAT_DATE,''),
						"<DATE_ACTIVE_FROM" => ConvertDateTime("01.01.".($year+1), FORMAT_DATE,''),
					);
				}?>
			<?}
		}
	}?>
<?elseif($arParams['TYPE_HEAD_BLOCK']=='sections_mix' || $arParams['TYPE_HEAD_BLOCK']=='sections_links'):?>
		<?$arFilter = array('IBLOCK_ID'=>$arParams['IBLOCK_ID'], 'ACTIVE' => 'Y', 'DEPTH_LEVEL' => 1);
		$arSelect = array('ID', 'SORT', 'IBLOCK_ID', 'NAME', 'SECTION_PAGE_URL');
		$arParentSections = CMaxCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'ID' => 'ASC', 'CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'MULTI' => 'Y')), $arFilter, false, $arSelect);
		if($arParentSections)
		{
			$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/mixitup.min.js');
			$bHasSection = (isset($arSection['ID']) && $arSection['ID']);?>
			<div class="select_head_wrap">
				<div class="menu_item_selected font_upper_md rounded3 bordered visible-xs font_xs darken"><span></span>
					<?=CMax::showIconSvg("down", SITE_TEMPLATE_PATH.'/images/svg/trianglearrow_down.svg', '', '', true, false);?>
				</div>
				<div class="head-block with-tabs top controls clearfix srollbar-custom">

					<div class="item-link bordered rounded3 box-shadow <?=($bHasSection && !$useSectionsLink ? '' : 'active');?>">
						<div class="title font_upper_md">
							<?if($bHasSection && !$useSectionsLink):?>
								<a class="btn-inline black colored_theme_bg_before " href="<?=$arResult['FOLDER'];?>"><?=GetMessage('ALL_PROJECTS');?></a>
							<?else:?>
								<span class="btn-inline black colored_theme_bg_before " data-filter="all"><?=GetMessage('ALL_PROJECTS');?></span>
							<?endif;?>
						</div>
					</div>
					<?$cur_page = $GLOBALS['APPLICATION']->GetCurPage(true);
					$cur_page_no_index = $GLOBALS['APPLICATION']->GetCurPage(false);?>

					<?foreach($arParentSections as $arParentItem):?>
						<?$bSelected = ($bHasSection && CMenu::IsItemSelected($arParentItem['SECTION_PAGE_URL'], $cur_page, $cur_page_no_index));?>
						<div class="item-link bordered rounded3 box-shadow  <?=($bSelected ? 'active' : '');?>">
							<div class="title btn-inline black font_upper_md">
								<?if(!$bHasSection && !$useSectionsLink):?>
									<span class="btn-inline black colored_theme_bg_before " data-filter=".s-<?=$arParentItem['ID']?>"><?=$arParentItem['NAME'];?></span>
								<?else:?>
									<?if($bSelected):?>
										<span class="btn-inline black colored_theme_bg_before "><?=$arParentItem['NAME'];?></span>
									<?else:?>
										<a class="btn-inline black colored_theme_bg_before " href="<?=$arParentItem['SECTION_PAGE_URL'];?>"><?=$arParentItem['NAME'];?></a>
									<?endif;?>
								<?endif;?>
							</div>
						</div>
					<?endforeach;?>
				</div>
			</div>
			<?if(0):?><div class="select_wrapper visible-xs">
				<select class=" form-control"> 
					<option data-mix_filter="all" value="<?=$arResult['FOLDER']?>"<?=($bHasSection && !$useSectionsLink ? '' : ' selected')?>><?=GetMessage('ALL_PROJECTS');?></option> 
					<?foreach($arParentSections as $arParentItem):?> 
						<? 
						$bSelected = ($bHasSection && CMenu::IsItemSelected($arParentItem['SECTION_PAGE_URL'], $cur_page, $cur_page_no_index)); 
						?> 
					<option data-mix_filter=".s-<?=$arParentItem['ID']?>" value="<?=$arParentItem['SECTION_PAGE_URL'];?>"<?=($bSelected ? ' selected' : '')?>><?=$arParentItem['NAME'];?></option> 
					<?endforeach?> 
				</select>
			</div><?endif;?>

		<?}?>
<?endif?>
<script>
    /*$(document).ready(function(){ 
                    $('.head-block + .select_wrapper select').on('change', function(){ 
			var selectedOpt = $($(this).find('option:selected'));
			var buttonInHead = $('[data-filter="'+selectedOpt.attr('data-mix_filter') + '"]');
			if(buttonInHead.length){
			    buttonInHead.click();
			} else{
			    window.location.href = selectedOpt.val();
			}
                    }); 
                });
*/		

</script>

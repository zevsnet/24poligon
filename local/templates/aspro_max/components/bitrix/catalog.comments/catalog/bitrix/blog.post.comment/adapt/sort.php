<div class="reviews_sort">
	<?
	$arAvailableSort = array(
		array(
			'PROP' => 'UF_ASPRO_COM_RATING',
			'ORDER' => 'SORT_DESC',
			'MESSAGE' => 'RATING_DESC',
		),
		array(
			'PROP' => 'UF_ASPRO_COM_RATING',
			'ORDER' => 'SORT_ASC',
			'MESSAGE' => 'RATING_ASC',
		),
		array(
			'PROP' => 'DateFormated',
			'ORDER' => 'SORT_ASC',
			'MESSAGE' => 'DATE_ASC',
		),
		array(
			'PROP' => 'DateFormated',
			'ORDER' => 'SORT_DESC',
			'MESSAGE' => 'DATE_DESC',
		),
		array(
			'PROP' => 'UF_ASPRO_COM_LIKE',
			'ORDER' => 'SORT_DESC',
			'MESSAGE' => 'LIKE_DESC',
		),
	);
	$sort = $_SESSION['REVIEW_SORT_PROP'] ? $_SESSION['REVIEW_SORT_PROP'] : 'UF_ASPRO_COM_RATING';
	$sort_order= $_SESSION['REVIEW_SORT_ORDER'] ? $_SESSION['REVIEW_SORT_ORDER'] : 'SORT_DESC';

	foreach ($arAvailableSort as $value) {
		if($value['PROP'] == $sort && $value['ORDER'] == $sort_order) {
			$currentSort = $value;
		}
	}

	?>
	<div class="filter-panel sort_header">
		<!--noindex-->
			<div class="filter-panel__sort pull-left">
				
				<div class="dropdown-select">
					<div class="dropdown-select__title font_xs darken">
						<span>
							<?if($sort_order && $sort):?>
								<?if($currentSort):?>
									<?=\Bitrix\Main\Localization\Loc::getMessage($currentSort['MESSAGE'])?>
								<?endif;?>
							<?else:?>
								<?=\Bitrix\Main\Localization\Loc::getMessage('NOTHING_SELECTED');?>
							<?endif;?>
						</span>
						<?=CMax::showIconSvg("down", SITE_TEMPLATE_PATH.'/images/svg/trianglearrow_down.svg', '', '', true, false);?>
					</div>
					<div class="dropdown-select__list dropdown-menu-wrapper" role="menu">
						<div class="dropdown-menu-inner rounded3">
							<?foreach($arAvailableSort as $prop => $arVals):?>
								<div class="dropdown-select__list-item font_xs">
									<?if($bCurrentLink = ($sort == $arVals['PROP'] && $sort_order == $arVals['ORDER'])):?>
										<span class="dropdown-select__list-link dropdown-select__list-link--current">
									<?else:?>
										<span data-review_sort_ajax='{"sort": "<?=$arVals["PROP"]?>", "order": "<?=$arVals["ORDER"]?>", "reviews_sort": "Y", "ajax_url": "<?=str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__).'/ajax.php'?>"}' class="dropdown-select__list-link <?=$sort_order?> <?=$prop?> darken" >
									<?endif;?>

										<span><?=\Bitrix\Main\Localization\Loc::getMessage($arVals['MESSAGE'])?></span>

									</span>

								</div>
							<?endforeach;?>
						</div>
					</div>
				</div>

			</div>
			
			<div class="clearfix"></div>
		<!--/noindex-->
	</div>
</div>
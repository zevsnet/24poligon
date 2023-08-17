<?global $pathForAjax;?>
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

	$arFilterButtons = $arParams['REVIEW_FILTER_BUTTONS'] ?? [];
	$arAvailableFilter = [];
	$arSessionFilter = $_SESSION['filter'];

	if (in_array('RATING', $arFilterButtons)) {
		$arAvailableFilter['RATING'] = [
			"NAME" => GetMessage("T_FILTER_RATING"),
			"TYPE" => "LIST",
			"INPUT_TYPE" => "radio",
			"VALUES" => [
				'all' => GetMessage("T_FILTER_RATING"),
				'1' => GetMessage("T_FILTER_RATING_1"),
				'2' => GetMessage("T_FILTER_RATING_2"),
				'3' => GetMessage("T_FILTER_RATING_3"),
				'4' => GetMessage("T_FILTER_RATING_4"),
				'5' => GetMessage("T_FILTER_RATING_5"),
			],
			"CURRENT_VALUE" => isset($_SESSION['REVIEW_FILTER']) && isset($_SESSION['REVIEW_FILTER']['RATING']) ? htmlspecialcharsbx($_SESSION['REVIEW_FILTER']['RATING']) : 'all',
		];
	}
	if (in_array('PHOTO', $arFilterButtons)) {
		$arAvailableFilter['PHOTO'] = [
			"NAME" => GetMessage("T_FILTER_PHOTO"),
			"TYPE" => "CHECKBOX",
			"CURRENT_VALUE" => isset($_SESSION['REVIEW_FILTER']) && isset($_SESSION['REVIEW_FILTER']['PHOTO']) ? htmlspecialcharsbx($_SESSION['REVIEW_FILTER']['PHOTO']) : "N",
		];
	}
	if (in_array('TEXT', $arFilterButtons)) {
		$arAvailableFilter['TEXT'] = [
			"NAME" => GetMessage("T_FILTER_TEXT"),
			"TYPE" => "CHECKBOX",
			"CURRENT_VALUE" => isset($_SESSION['REVIEW_FILTER']) && isset($_SESSION['REVIEW_FILTER']['TEXT']) ? htmlspecialcharsbx($_SESSION['REVIEW_FILTER']['TEXT']) : "N",
		];
	}
	$bShowFilter = count($arAvailableFilter);
	?>
	<div class="filter-panel sort_header">
		<!--noindex-->
			<div class="filter-panel__sort">
				<? if ($bShowFilter): // Filter buttons ?>
					<form class="filter-panel__sort-form" method="POST" enctype="multipart/form-data">
						<input type="hidden" name="reviews_sort" value="Y" />
						<input type="hidden" name="reviews_filter" value="Y" />
						<input type="hidden" name="ajax_url" value="<?= $pathForAjax . '/ajax.php'; ?>">
				<? else: ?>
					<div class="filter-panel__sort-form flexbox flexbox--row flexbox--wrap">
				<? endif; ?>
					<div class="filter-panel__sort-form__inner flexbox flexbox--row flexbox--wrap">
						<div class="filter-panel__sort-form__item dropdown-select">
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
												<span data-review_sort_ajax='{"sort": "<?=$arVals["PROP"]?>", "order": "<?=$arVals["ORDER"]?>", "reviews_sort": "Y", "ajax_url": "<?=$pathForAjax.'/ajax.php'?>"}' class="dropdown-select__list-link <?=$sort_order?> <?=$prop?> darken" >
											<?endif;?>

												<span><?=\Bitrix\Main\Localization\Loc::getMessage($arVals['MESSAGE'])?></span>

											</span>

										</div>
									<?endforeach;?>
								</div>
							</div>
						</div>
				
					<? if ($bShowFilter): // Filter buttons ?>
						<? foreach ($arAvailableFilter as $filter => $arOption): ?>
							<? if ($arOption['TYPE'] === 'LIST'): // Filter type list ?>
								<div class="filter-panel__sort-form__item dropdown-select">
									<div class="dropdown-select__title font_xs darken">
										<span><?= $arOption['VALUES'][$arOption['CURRENT_VALUE']]; ?></span>
										<?=CMax::showIconSvg("down", SITE_TEMPLATE_PATH.'/images/svg/trianglearrow_down.svg', '', '', true, false);?>
									</div>
									
									<div class="dropdown-select__list dropdown-menu-wrapper" role="menu">
										<div class="dropdown-menu-inner rounded3">
											<?foreach($arOption['VALUES'] as $key => $value):?>
												<div class="dropdown-select__list-item font_xs<?= $arOption['INPUT_TYPE'] ? ' '.$arOption['INPUT_TYPE'] : ''; ?>">
													<input 
														id="filter-panel-<?= strtolower($filter); ?>-<?= $key; ?>" 
														name="filter[<?= $filter; ?>]<?= $arOption['INPUT_TYPE'] === 'checkbox' ? '[]' : ''; ?>" 
														type="<?= $arOption['INPUT_TYPE']; ?>" 
														value="<?= $key; ?>" 
														<?= $arOption['CURRENT_VALUE'] == $key ? 'checked' : ''; ?> 
													/>	
													<label class="dropdown-select__list-link <?= $arOption['CURRENT_VALUE'] == $key ? 'dropdown-select__list-link--current' : 'darken'; ?>" for="filter-panel-<?= strtolower($filter); ?>-<?= $key; ?>">
														<?= $value; ?>
													</label>
												</div>
											<?endforeach;?>
										</div>
									</div>
								</div>
							<? endif; ?>

							<? if ($arOption['TYPE'] === 'CHECKBOX'): // Filter type checkbox ?>
								<div class="filter-panel__sort-form__item filter label_block">
									<input 
										id="filter-panel-<?= strtolower($filter); ?>" 
										name="filter[<?= $filter; ?>]" 
										type="checkbox" 
										value="Y" 
										<?= $arOption['CURRENT_VALUE'] === 'Y' ? 'checked' : ''; ?> 
									/>
									<label for="filter-panel-<?= strtolower($filter); ?>"><?= $arOption['NAME']; ?></label>
								</div>
							<? endif; ?>
						<? endforeach; ?>
						</div>
					</form>
				<? else: ?>
						</div>
					</div>
				<? endif; ?>
			</div>
		<!--/noindex-->
	</div>
</div>
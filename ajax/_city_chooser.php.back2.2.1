<?
use Bitrix\Main\Localization\Loc,
	CMax as Solution,
	Aspro\Max\Functions\Extensions,
	Aspro\Max\Components\RegionalityList;

define('STATISTIC_SKIP_ACTIVITY_CHECK', true);
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

global $arTheme, $APPLICATION;

$APPLICATION->ShowAjaxHead();

Extensions::register();
Extensions::init('skeleton');
?>
<a href="#" class="close jqmClose"><?=Solution::showIconSvg('', SITE_TEMPLATE_PATH.'/images/svg/Close.svg')?></a>
<div class="form">
	<div class="form_head">
		<h2><?=Loc::getMessage('CITY_CHOISE');?></h2>
	</div>

	<?
	$arTheme = Solution::GetFrontParametrsValues(SITE_ID);
	$urlback = htmlspecialchars($_GET['url']);
	/*if($urlback)
		$urlback = urldecode($urlback)*/;

	$template = strtolower($arTheme["REGIONALITY_VIEW"]);
	if($arTheme["REGIONALITY_SEARCH_ROW"] == "Y" && $template == "select") {
		$template = "popup_regions";
	}
	?>
	<?$arResult = $APPLICATION->IncludeComponent(
		"aspro:regionality.list.max",
		$template,
		Array(
			"URL" => $urlback,
			"POPUP" => "Y",
		)
	);?>
	<script type="text/javascript">
	(function(){
		BX.loadScript('<?=SITE_TEMPLATE_PATH;?>/js/jquery-ui.min.js', autocompleteHandler);
			
		var regionsPopup = $('.popup_regions .autocomplete').closest('.popup');

		function autocompleteHandler(){
			$("#search").autocomplete({
				minLength: 2,
				appendTo : $('.popup_regions .autocomplete').parent(),
				source: function(request, callback){
					let componentAction = 'searchCities';
					let componentName = 'aspro:regionality.list.max';
					let sessid = BX.message('bitrix_sessid');
					let lang = BX.message('LANGUAGE_ID');
					let siteId = BX.message('SITE_ID');
					let url = '<?=$arResult['URI']?>';

					BX.ajax({
						url: '/bitrix/services/main/ajax.php?mode=ajax&c=' + encodeURIComponent(componentName) +'&action=' + componentAction + '&sessid=' + sessid + '&SITE_ID=' + siteId + '&siteId=' + siteId + '&url=' + encodeURIComponent(url) + '&term=' + encodeURIComponent(request.term) + '&lang=' + lang,
						method: 'POST',
						async: true,
						processData: true,
						scriptsRunFirst: true,
						emulateOnload: true,
						start: true,
						cache: false,
						dataType: 'json',
						onsuccess: function(response){
							if (
								typeof response === 'object' &&
								response &&
								typeof response.data === 'object' &&
								response.data &&
								response.data.cities
							) {
								callback(response.data.cities);
							}
						},
						onfailure: function(){
						}
					});
				},
				select: function(event, ui) {
					let regionId = ui.item.ID;
					if (regionId) {
						$(this).closest('.form').addClass('sending');

						$.cookie('current_region', regionId, {
							path: '/',
							domain: arAsproOptions['SITE_ADDRESS'],
						});

						let locationId = ui.item.LOCATION_ID;
						if (locationId) {
							$.cookie('current_location', locationId, {
								path: '/',
								domain: arAsproOptions['SITE_ADDRESS'],
							});
						}
						else {
							$.cookie('current_location', '', {
								expires: -1,
								path: '/',
								domain: arAsproOptions['SITE_ADDRESS'],
							});
						}

						let href = ui.item.URL;
						if (href) {
							location.href = href;
						}
					}

					$('#search').val(ui.item.NAME);

					return false;
				},
				focus: function(event, ui) {
					// !!!hack do not remove that
					setTimeout(function() {
						$('#search').val(ui.item.NAME);
					}, 0);
				},
				open: function(event, ui) {
					regionsPopup.addClass('no_scroll');
					regionsPopup.find('.popup_regions .items').addClass('fade');
				},
				close: function(event, ui) {
					regionsPopup.removeClass('no_scroll');
					regionsPopup.find('.popup_regions .items').removeClass('fade');
				}
			}).data('ui-autocomplete')._renderItem = function(ul, item){
				var path = item.PATH ? ' <span class="city-path">(' + item.PATH + ')</span>' : '';

				return $('<li>').append('<a href="' + item.URL + '" class="cityLink">' + item.NAME + path + '</a>').appendTo(ul);
			}
		}

		$('.popup_regions .items.ext_view').on('click', '.block.regions .item:not(.current)', function(e){
			let $this = $(this);
			let $parentBlock = $this.parent('.parent_block');

			if ($parentBlock.length) {
				if ($this.find('a').length) {
					// it`s a city without region in level2 block
					return;
				}
				
				let level2 = $this.data('id');
				
				if (!level2) {
					return;
				}
				
				$this.addClass('current').siblings().each(
					function(i, item) {
						// exclude cities in level 2 (Msc., St.P)
						if (!$(item).find('a').length) {
							$(item).removeClass('current');
						}
					}
				);
				
				let $oldCities = $('.popup_regions .items.ext_view .cities .items_block .parent_block.current');
				if ($oldCities) {
					$oldCities.removeClass('current shown').hide(); // hide() for compatible templates

					getLevelsAndCities.rowsSkeletons.cities = $oldCities.find('.item').length;
					if (getLevelsAndCities.rowsSkeletons.cities > 8) {
						getLevelsAndCities.rowsSkeletons.cities = 8;
					}
				}

				let $cities = $('.popup_regions .items.ext_view .cities .items_block .parent_block[data-id=' + level2 + ']');
				if ($cities.length) {
					$cities.addClass('current shown').show(); // show() for compatible templates
				}

				if ($this.hasClass('loaded')) {
					return;
				}
			}
			else {
				let level1 = $this.data('id');
				
				if (!level1) {
					return;
				}
				
				$this.addClass('current').siblings().removeClass('current');

				let $oldRegions = $('.popup_regions .items.ext_view .regions.level2 .items_block .parent_block.current');
				if ($oldRegions) {
					$oldRegions.removeClass('current shown');

					getLevelsAndCities.rowsSkeletons.level2 = $oldRegions.find('.item').length;
					if (getLevelsAndCities.rowsSkeletons.level2 > 8) {
						getLevelsAndCities.rowsSkeletons.level2 = 8;
					}
				}

				let $oldCities = $('.popup_regions .items.ext_view .cities .items_block .parent_block.current');
				if ($oldCities) {
					$oldCities.removeClass('current shown').hide(); // hide() for compatible templates

					getLevelsAndCities.rowsSkeletons.cities = $oldCities.find('.item').length;
					if (getLevelsAndCities.rowsSkeletons.cities > 8) {
						getLevelsAndCities.rowsSkeletons.cities = 8;
					}
				}
				
				let $regions = $('.popup_regions .items.ext_view .regions.level2 .items_block .parent_block[data-id=' + level1 + ']');
				if ($regions.length) {
					$('.popup_regions .items.ext_view .block.level2').show();
					$regions.addClass('current shown');

					$regions.find('.item.current').each(
						function(i, item) {
							// exclude cities in level 2 (Msc., St.P)
							if (!$(item).find('a').length) {
								$(item).removeClass('current').trigger('click');

								return false;
							}
						}
					);
				}

				if ($this.hasClass('loaded')) {					
					return;
				}
			}

			getLevelsAndCities();
		});

		$('.h-search .wrapper .search_btn').on('click', function(){
			var block = $(this).closest('.wrapper').find('#search');
			if(block.length){
				block.trigger('focus');
				block.data('ui-autocomplete').search(block.val());
			}
		});

		$('.popup_regions').on('click', '.item a[data-id]', function(e){
			e.preventDefault();

			let $this = $(this);
			let regionId = $this.data('id');

			if (regionId) {
				$(this).closest('.form').addClass('sending');

				$.cookie('current_region', regionId, {
					path: '/',
					domain: arAsproOptions['SITE_ADDRESS'],
				});

				let locationId = $this.data('locid');
				if (locationId) {
					$.cookie('current_location', locationId, {
						path: '/',
						domain: arAsproOptions['SITE_ADDRESS'],
					});
				}
				else {
					$.cookie('current_location', '', {
						expires: -1,
						path: '/',
						domain: arAsproOptions['SITE_ADDRESS'],
					});
				}
				
				let href = $this.attr('href');
				location.href = href;
			}
		});

		$('.popup_regions .items.only_city .cities').on('click', '.item.more_cities', function(e){
			$(this).addClass('loadings');
			getMainCities();
		});
	
		// send component action to get a page of cities
		function getMainCities() {
			let componentAction = 'getMainCities';
			let componentName = 'aspro:regionality.list.max';
			let sessid = BX.message('bitrix_sessid');
			let lang = BX.message('LANGUAGE_ID');
			let siteId = BX.message('SITE_ID');
			let url = '<?=$arResult['URI']?>';
			let	lastId = getMainCities.lastId;

			if (!$('.popup_regions .item.more_cities').length) {
				appendSkeleton($('.popup_regions .items.only_city .block.cities .items_block'), getMainCities.rowsSkeletons, getMainCities.columnsSkeletons);
			}

			BX.ajax({
				url: '/bitrix/services/main/ajax.php?mode=ajax&c=' + encodeURIComponent(componentName) +'&action=' + componentAction + '&sessid=' + sessid + '&SITE_ID=' + siteId + '&siteId=' + siteId + '&url=' + encodeURIComponent(url) + '&lastId=' + lastId + '&lang=' + lang,
				method: 'POST',
				async: true,
				processData: true,
				scriptsRunFirst: true,
				emulateOnload: true,
				start: true,
				cache: false,
				dataType: 'json',
				onsuccess: function(response){
					$('.popup_regions .skeleton-grid').remove();
					$('.popup_regions .item.more_cities').remove();

					if (
						typeof response === 'object' &&
						response &&
						typeof response.data === 'object' &&
						response.data
					) {
						if (typeof response.data.lastId !== 'undefined') {
							getMainCities.lastId = response.data.lastId;
						}

						if (
							response.data.cities &&
							Object.values(response.data.cities).length
						) {
							let itemsHtml = '';

							for (let i in response.data.cities) {
								let item = response.data.cities[i];
								let bCurrent = item.CURRENT == 1;

								itemsHtml += `
									<div class="item` + (bCurrent ? ' current' : '') + `">
										<a href="` + item.URL + `" data-id="` + item.ID + `" data-locid="` + (item.LOCATION_ID ? item.LOCATION_ID : '') + `" title="` + item.PATH + `" ` + (bCurrent ? ' class="dark_link"><span class="name">' : 'class="name dark_link">') + item.NAME + (bCurrent ? '</span>' : '') + `</a>
									</div>
								`;
							}

							if (response.data.more) {
								itemsHtml += '<div class="item more_cities"><span>' + '<?=Loc::getMessage('CITY_MORE')?>' + '</span></div>';
							}

							$('.popup_regions .items.only_city .block.cities .items_block').append(itemsHtml);
						}
					}
				},
				onfailure: function(){
					$('.popup_regions .skeleton-grid').remove();
					$('.popup_regions .item.more_cities').removeClass('loadings');
				}
			});
		}

		getMainCities.lastId = 0;
		getMainCities.columnsSkeletons = 4;
		getMainCities.rowsSkeletons = <?=(intval(ceil((count($arResult['REGIONS']) >= RegionalityList::CNT_MAIN_CITIES_IN_PAGE ? RegionalityList::CNT_MAIN_CITIES_IN_PAGE : count($arResult['REGIONS'])) / 4)) ?: 2)?>;

		// send component action to get a districts & regions & cities
		function getLevelsAndCities() {
			let componentAction = 'getLevelsAndCities';
			let componentName = 'aspro:regionality.list.max';
			let sessid = BX.message('bitrix_sessid');
			let lang = BX.message('LANGUAGE_ID');
			let siteId = BX.message('SITE_ID');
			let url = '<?=$arResult['URI']?>';
			
			let level1 = $('.popup_regions .items.ext_view .block.level1 .item.current[data-id]').data('id');
			if (!level1) {
				appendSkeleton($('.popup_regions .items.ext_view .block.level1 .items_block'), getLevelsAndCities.rowsSkeletons.level1, 1);
			}
			
			let level2 = $('.popup_regions .items.ext_view .block.level2 .parent_block.current .item.current[data-id]').data('id');
			if (!level2) {
				$('.popup_regions .items.ext_view .block.level2 .parent_block').removeClass('current shown');
				appendSkeleton($('.popup_regions .items.ext_view .block.level2 .items_block'), getLevelsAndCities.rowsSkeletons.level2, 1);
			}
			
			let city = $('.popup_regions .items.ext_view .block.cities .parent_block.current .item.current[data-id]').data('id');
			$('.popup_regions .items.ext_view .block.cities .parent_block').removeClass('current shown');
			appendSkeleton($('.popup_regions .items.ext_view .block.cities .items_block'), getLevelsAndCities.rowsSkeletons.cities, 1);

			BX.ajax({
				url: '/bitrix/services/main/ajax.php?mode=ajax&c=' + encodeURIComponent(componentName) +'&action=' + componentAction + '&sessid=' + sessid + '&SITE_ID=' + siteId + '&siteId=' + siteId + '&url=' + encodeURIComponent(url) + '&level1=' + (level1 ? encodeURIComponent(level1) : '') + '&level2=' + (level2 ? encodeURIComponent(level2) : '') + '&lang=' + lang,
				method: 'POST',
				async: true,
				processData: true,
				scriptsRunFirst: true,
				emulateOnload: true,
				start: true,
				cache: false,
				dataType: 'json',
				onsuccess: function(response){
					$('.popup_regions .items.ext_view .skeleton-grid').remove();

					if (
						typeof response === 'object' &&
						response &&
						typeof response.data === 'object' &&
						response.data
					) {						
						if (
							response.data.level1 &&
							Object.values(response.data.level1).length
						) {
							if (
								!response.data.level2 ||
								!Object.values(response.data.level2).length
							) {
								$('.popup_regions .items.ext_view .block.level1 .title').text = BX.message('REGION');
							}
								
							let level1Html = '';
							for (let i in response.data.level1) {
								let item = response.data.level1[i];
								let bCurrent = item.CURRENT == 1;

								if (bCurrent && !level1) {
									level1 = (item.ID ? item.ID : '') + (item.SECTION_ID ? 's' + item.SECTION_ID : '');
								}
								
								level1Html += `
									<div class="item dark_link` + (bCurrent ? ' current' : '') + `" data-id="` + (item.ID ? item.ID : '') + (item.SECTION_ID ? 's' + item.SECTION_ID : '') + `">
										<span>` + item.NAME + `</span>
									</div>
								`;
							}

							$('.popup_regions .items.ext_view .block.level1 .items_block').html(level1Html);
						}
						else if (!level1) {
							$('.popup_regions .items.ext_view .block.level1').remove();
						}
						
						if (
							response.data.level2 &&
							Object.values(response.data.level2).length
						) {
							$('.popup_regions .items.ext_view .block.level2').show();

							let level2Html = '';
							level2Html += '<div class="parent_block current shown" data-id="' + level1 + '">';

							for (let i in response.data.level2) {
								let item = response.data.level2[i];
								let bCurrent = item.CURRENT == 1;
								let bCity = !!item.URL;

								if (bCurrent && !level2 && !bCity) {
									level2 = (item.ID ? item.ID : '') + (item.SECTION_ID ? 's' + item.SECTION_ID : '');
								}

								if (bCity) {
									level2Html += `
										<div class="item` + (bCurrent ? ' current' : '') + `">`
											+ (
												bCurrent ? 
												`<a href="` + item.URL + `" data-id="` + item.ID + `" data-locid="` + (item.LOCATION_ID ? item.LOCATION_ID : '') + `"><span class="name dark_link">` : 
												`<a href="` + item.URL + `" data-id="` + item.ID + `" data-locid="` + (item.LOCATION_ID ? item.LOCATION_ID : '') + `" class="name dark_link">`
											)
											+ `<span class="mes-city">` + BX.message('CITY').toLowerCase() + `</span> ` + item.NAME
											+ (
												bCurrent ? 
												`</span></a>` : 
												`</a>`
											)
										+ `</div>
									`;
								}
								else {
									level2Html += `
										<div class="item dark_link` + (bCurrent ? ' current' : '') + `" data-id="` + (item.ID ? item.ID : '') + (item.SECTION_ID ? 's' + item.SECTION_ID : '') + `">
											<span>` + item.NAME + `</span>
										</div>
									`;
								}
							}

							level2Html += '</div>';
							
							$('.popup_regions .items.ext_view .block.level2 .items_block').append(level2Html);
						}
						else if(!level2) {
							$('.popup_regions .items.ext_view .block.level2').hide();
						}
						
						if (
							response.data.cities &&
							Object.values(response.data.cities).length
						) {
							let citiesHtml = '';
							citiesHtml += '<div class="parent_block current shown" data-id="' + level2 + '">';

							for (let i in response.data.cities) {
								let item = response.data.cities[i];
								let bCurrent = item.CURRENT == 1;

								// style="display:inline-block;" for compatible templates
								citiesHtml += `
									<div class="item` + (bCurrent ? ' current' : '') + `" style="display:block;">`
										+ (
											bCurrent ? 
											`<a href="` + item.URL + `" data-id="` + item.ID + `" data-locid="` + (item.LOCATION_ID ? item.LOCATION_ID : '') + `"><span class="name dark_link">` : 
											`<a href="` + item.URL + `" data-id="` + item.ID + `" data-locid="` + (item.LOCATION_ID ? item.LOCATION_ID : '') + `" class="name dark_link">`
										)
										+ item.NAME
										+ (
											bCurrent ? 
											`</span></a>` : 
											`</a>`
										)
									+ `</div>
								`;
							}

							citiesHtml += '</div>';

							$('.popup_regions .items.ext_view .block.cities .items_block').append(citiesHtml);
						}

						if (level1) {
							$('.popup_regions .items.ext_view .block.level1 .item.current[data-id=' + level1 + ']').addClass('loaded');
						}

						if (level2) {
							$('.popup_regions .items.ext_view .block.level2 .item.current[data-id=' + level2 + ']').addClass('loaded');
						}
					}
				},
				onfailure: function(){
					$('.popup_regions .items.ext_view .skeleton-grid').remove();
				}
			});
		}

		getLevelsAndCities.rowsSkeletons = {
			level1: 4,
			level2: 4,
			cities: 4,
		}

		function appendSkeleton($target, rows, cols) {
			if (
				$target &&
				$target.length
			) {
				let skeletonHtml = '<div class="skeleton-grid skeleton-grid--column" style="--repeat-column: ' + cols + '; --gap: 0 20px; --repeat-row: ' + rows + '; grid-template-rows: repeat(var(--repeat-row), 32px);">';

				let cnt = rows * cols;
				cnt = cnt > 0 ? cnt : 1;

				for (let i = 0; i < cnt; ++i) {
					skeletonHtml += '<div class="skeleton" style="height: 18px;"></div>';	
				}

				skeletonHtml += '';

				$target.append(skeletonHtml);
			}
		}

		if ($('.popup_regions .items.only_city .cities').length) {
			getMainCities();
		}
		else {
			getLevelsAndCities();
		}
	})();
	</script>
</div>

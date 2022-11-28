$(document).ready(function(){
	if(!('SelectOfferProp' in window) && typeof window.SelectOfferProp != 'function')
	{
		SelectOfferProp = function(){
			// return false;
			var _this = $(this),
				obParams = {},
				obSelect = {},
				objUrl = parseUrlQuery(),
				add_url = '',
				container = _this.closest('.bx_catalog_item_scu');

			/* request params */
			obParams = {
				'PARAMS': _this.closest('.js_wrapper_items').data('params'),
				'ID': container.data('offer_id'),
				'SITE_ID': container.data('site_id'),
				'LINK_ID': container.data('id')+'_'+_this.closest('.tab').data('code'),
				'IBLOCK_ID': container.data('offer_iblockid'),
				'PROPERTY_ID': container.data('propertyid'),
				'DEPTH': _this.closest('.item_wrapper').index(),
				'VALUE': _this.data('onevalue'),
				'CLASS': 'inner_content',
				'PICTURE': _this.closest('.catalog_item_wrapp').find('.thumb img').attr('src'),
				'ARTICLE_NAME': _this.closest('.catalog_item_wrapp').find('.article_block').data('name'),
				'ARTICLE_VALUE': _this.closest('.catalog_item_wrapp').find('.article_block').data('value'),
			}
			/**/

			if("clear_cache" in objUrl)
			{
				if(objUrl.clear_cache == "Y")
					add_url += "?clear_cache=Y";
			}

			/* save selected values */
			for (i = 0; i < obParams.DEPTH+1; i++)
			{
				strName = 'PROP_'+container.find('.item_wrapper:eq('+i+') > div').data('id');
				obSelect[strName] = container.find('.item_wrapper:eq('+i+') li.item.active').data('onevalue');
				obParams[strName] = container.find('.item_wrapper:eq('+i+') li.item.active').data('onevalue');
			}

			// obParams.SELECTED = JSON.stringify(obSelect);
			/**/
			
			_this.siblings().removeClass('active');
			_this.addClass('active');

			if(_this.attr('title'))
			{
				_this.closest('.item_wrapper').find('.show_class span').text(_this.attr('title'))
			}
			else
			{
				var img_row = _this.find(' > i');
				if(img_row.length && img_row.attr('title'))
					_this.closest('.item_wrapper').find('.show_class span').text(img_row.attr('title'))
			}

			/* get sku */
			$.ajax({
				url: arMaxOptions['SITE_DIR']+'ajax/js_item_detail.php'+add_url,
				type: 'POST',
				data: obParams,
			}).success(function(html){
				var ob = BX.processHTML(html);BX.ajax.processScripts(ob.SCRIPT);
			})
		}
		$(document).on('click', '.bx_catalog_item_scu li.item', SelectOfferProp)
	}

	window.tabsInitOnReady = function() {
		$('.tabs_wrapper').scrollTab({
			tabs_wrapper: '.tabs.ajax',
			arrows_css: {
				'top': '-1px',
			},
			width_grow: 3,
			onResize: function(options) {
				var top_wrapper = options.scrollTab.closest('.top_block');
				if(top_wrapper.length) {
					var tabs_wrapper = top_wrapper.find('.right_block_wrapper .tabs_wrapper');

					if(window.matchMedia('(max-width: 767px)').matches){
						tabs_wrapper.css({
							'width': '100%',
							'max-width': '',
						});
						return true;
					}

					var title = top_wrapper.find('h3');
					var right_link = top_wrapper.find('.right_block_wrapper > a');
					var all_width = top_wrapper[0].getBoundingClientRect().width;

					if(title.length) {
						all_width -= title.outerWidth(true);
					}

					if(right_link.length) {
						all_width -= right_link.outerWidth(true);
					}

					all_width -= Number.parseInt(tabs_wrapper.css('margin-right'));

					tabs_wrapper.css({
						'max-width': all_width,
						'width': '',
					});
				}
				options.width = all_width;
			}
		});

		var container = $('.content_wrapper_block.main');
		if(container.length) {
			var curTab = container.find('.tabs_content li.cur');
			if(curTab.length) {
				var bNav = curTab.find('.bottom_nav .module-pagination').length > 0;
				if(bNav) {
					container.removeClass('without-border');
				} else {
					container.addClass('without-border');
				}
			}
		}
	}
	
});

if (!window.JCCatalogSectionOnlyElement)
{

	window.JCCatalogSectionOnlyElement = function (arParams)
	{
		if (typeof arParams === 'object')
		{
			this.params = arParams;

			this.obProduct = null;
			this.set_quantity = 1;

			this.currentPriceMode = '';
			this.currentPrices = [];
			this.currentPriceSelected = 0;
			this.currentQuantityRanges = [];
			this.currentQuantityRangeSelected = 0;

			if (this.params.MESS)
			{
				this.mess = this.params.MESS;
			}

			this.init();
		}
	}
	window.JCCatalogSectionOnlyElement.prototype = {
		init: function()
		{
			var i = 0,
				j = 0,
				treeItems = null;

			this.obProduct = BX(this.params.ID);

			if(!!this.obProduct)
			{
				$(this.obProduct).find('.counter_wrapp .counter_block input').data('product', 'ob'+this.obProduct.id+'el');
				this.currentPriceMode = this.params.ITEM_PRICE_MODE;
				this.currentPrices = this.params.ITEM_PRICES;
				this.currentQuantityRanges = this.params.ITEM_QUANTITY_RANGES;
			}

		},

		setPriceAction: function()
		{
			this.set_quantity = this.params.MIN_QUANTITY_BUY;			
			if($(this.obProduct).find('input[name=quantity]').length)
				this.set_quantity = $(this.obProduct).find('input[name=quantity]').val();
			
			this.checkPriceRange(this.set_quantity);

			$(this.obProduct).find('.not_matrix').hide();
			$(this.obProduct).find('.with_matrix .price_value_block').html(getCurrentPrice(this.currentPrices[this.currentPriceSelected].PRICE, this.currentPrices[this.currentPriceSelected].CURRENCY, this.currentPrices[this.currentPriceSelected].PRINT_PRICE));

			if($(this.obProduct).find('.with_matrix .discount'))
			{
				$(this.obProduct).find('.with_matrix .discount').html(getCurrentPrice(this.currentPrices[this.currentPriceSelected].BASE_PRICE, this.currentPrices[this.currentPriceSelected].CURRENCY, this.currentPrices[this.currentPriceSelected].PRINT_BASE_PRICE));
			}

			if(this.params.SHOW_DISCOUNT_PERCENT_NUMBER == 'Y')
			{
				if(this.currentPrices[this.currentPriceSelected].PERCENT > 0 && this.currentPrices[this.currentPriceSelected].PERCENT < 100)
				{
					if(!$(this.obProduct).find('.with_matrix .sale_block .sale_wrapper .value').length)
						$('<div class="value"></div>').insertBefore($(this.obProduct).find('.with_matrix .sale_block .sale_wrapper .text'));

					$(this.obProduct).find('.with_matrix .sale_block .sale_wrapper .value').html('-<span>'+this.currentPrices[this.currentPriceSelected].PERCENT+'</span>%');
				}
				else
				{
					if($(this.obProduct).find('.with_matrix .sale_block .sale_wrapper .value').length)
						$(this.obProduct).find('.with_matrix .sale_block .sale_wrapper .value').remove();
				}
			}
			$(this.obProduct).find('.with_matrix .sale_block .text .values_wrapper').html(getCurrentPrice(this.currentPrices[this.currentPriceSelected].DISCOUNT, this.currentPrices[this.currentPriceSelected].CURRENCY, this.currentPrices[this.currentPriceSelected].PRINT_DISCOUNT));
			
			if('NOT_SHOW' in this.params && this.params.NOT_SHOW != 'Y')
				$(this.obProduct).find('.with_matrix').show();			

			if(arMaxOptions['THEME']['SHOW_TOTAL_SUMM'] == 'Y')
			{
				if(typeof this.currentPrices[this.currentPriceSelected] !== 'undefined')
					setPriceItem($(this.obProduct), this.set_quantity, this.currentPrices[this.currentPriceSelected].PRICE);
			}
		},

		checkPriceRange: function(quantity)
		{
			if (typeof quantity === 'undefined'|| this.currentPriceMode != 'Q')
				return;

			var range, found = false;
			
			for (var i in this.currentQuantityRanges)
			{
				if (this.currentQuantityRanges.hasOwnProperty(i))
				{
					range = this.currentQuantityRanges[i];

					if (
						parseInt(quantity) >= parseInt(range.SORT_FROM)
						&& (
							range.SORT_TO == 'INF'
							|| parseInt(quantity) <= parseInt(range.SORT_TO)
						)
					)
					{
						found = true;
						this.currentQuantityRangeSelected = range.HASH;
						break;
					}
				}
			}

			if (!found && (range = this.getMinPriceRange()))
			{
				this.currentQuantityRangeSelected = range.HASH;
			}

			for (var k in this.currentPrices)
			{
				if (this.currentPrices.hasOwnProperty(k))
				{
					if (this.currentPrices[k].QUANTITY_HASH == this.currentQuantityRangeSelected)
					{
						this.currentPriceSelected = k;
						break;
					}
				}
			}
		},

		getMinPriceRange: function()
		{
			var range;

			for (var i in this.currentQuantityRanges)
			{
				if (this.currentQuantityRanges.hasOwnProperty(i))
				{
					if (
						!range
						|| parseInt(this.currentQuantityRanges[i].SORT_FROM) < parseInt(range.SORT_FROM)
					)
					{
						range = this.currentQuantityRanges[i];
					}
				}
			}

			return range;
		}
	}
}
if(!funcDefined("sliceItemBlock")){
	sliceItemBlock = function(){
		$('.cur .catalog_block .catalog_item_wrapp.catalog_item .item_info .item-title').sliceHeight({item:'.cur .catalog_item:not(.big)', mobile: true});
		$('.cur .catalog_block .catalog_item_wrapp.catalog_item .item_info .sa_block').sliceHeight({item:'.cur .catalog_item:not(.big)', mobile: true});
		$('.cur .catalog_block .catalog_item_wrapp.catalog_item .item_info .cost.prices').sliceHeight({item:'.cur .catalog_item:not(.big)', mobile: true});
		$('.cur .catalog_block .catalog_item_wrapp.catalog_item').sliceHeight({classNull: '.footer_button', item:'.cur .catalog_item:not(.big)', mobile: true});

		InitCustomScrollBar();
	}
}
if(!funcDefined("sliceItemBlockSlide")){
	sliceItemBlockSlide = function(){
		$('.cur .catalog_block.owl-carousel .catalog_item_wrapp.catalog_item .item_info .item-title').sliceHeight({item:'.cur .catalog_item:not(.big)', mobile: true, autoslicecount: false, slice: 999});
		$('.cur .catalog_block.owl-carousel .catalog_item_wrapp.catalog_item .item_info .sa_block').sliceHeight({item:'.cur .catalog_item:not(.big)', mobile: true, autoslicecount: false, slice: 999});
		$('.cur .catalog_block.owl-carousel .catalog_item_wrapp.catalog_item .item_info .cost.prices').sliceHeight({item:'.cur .catalog_item:not(.big)', mobile: true, autoslicecount: false, slice: 999});
		// $('.cur .catalog_block .catalog_item_wrapp.catalog_item').sliceHeight({classNull: '.footer_button', item:'.cur .catalog_item:not(.big)', mobile: true});

		InitCustomScrollBar();
	}
}

$(document).ready(function(){
	if(!('SelectOfferProp' in window) && typeof window.SelectOfferProp != 'function')
	{
		SelectOfferProp1 = function(){
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
				'LINK_ID': container.data('id')+'_block',
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
});

(function (window) {
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
})(window);
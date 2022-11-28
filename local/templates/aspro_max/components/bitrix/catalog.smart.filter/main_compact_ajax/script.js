// fix double add event handler (composite + google PSI module)
if(!window.JCSmartFilterBinds){
	$(document).ready(function(){
		$(document).on('click', '.bx_filter.compact .bx_filter_parameters_box_title .delete_filter', function(){
			var _this = $(this);
			_this.closest('.bx_filter_parameters_box').find('input[type=text]').val('');
			_this.closest('.bx_filter_parameters_box').removeClass('set');
			_this.closest('.bx_filter_parameters_box').find('.pict label').removeClass('active');
			_this.closest('.bx_filter_parameters_box').find('.bx_filter_parameters_box_title .count_selected').text('');

			var t = false;
			_this.closest('.bx_filter_parameters_box').find('input').each(function(){
				var __this = this;
				$(__this).prop('checked', false);
				if(t){
					clearTimeout(t);
				}

				t = setTimeout(function(){
					smartFilter.reload(__this);
				}, 100);
			})
		});

		$(document).on('click', '.bx_filter.compact .bx_filter_block', function(e){
			e.stopPropagation();
		});

		$(document).on('click', '.bx_filter_button_box .bx_filter_search_reset', function(e){
			$(this).addClass('hidden');
			$('.bx_filter_search_reset.btn-link-text').click();
		});

		$(document).on('click', "#mobilefilter .bx_filter_parameters_box_title:not(.prices)", function(e){
			$(this).closest(".bx_filter_parameters_box").toggleClass("active");
		})

		$(document).on('click', ".filter-compact-block .bx_filter_parameters_box_title.prices.title", function(e){
			if(BX.PopupWindowManager.getCurrentPopup())
				BX.PopupWindowManager.getCurrentPopup().close();

			$(".bx_filter_block:not(.limited_block)").velocity('fadeOut', 100);
			$('.bx_filter_parameters_box').removeClass('active');
		})

		$(document).on('click', ".filter-compact-block .bx_filter_parameters_box_title:not(.prices)", function(e){
			var target = e.target;
			if(!$(e.target).hasClass('tooltip') && !$(e.target).hasClass('delete_filter') && !$(e.target).closest('.delete_filter').length){
				e.stopPropagation();

				var $box = $(this).closest(".bx_filter_parameters_box");
				if($box.hasClass("active")) {
					$(this).next(".bx_filter_block").stop().velocity('fadeOut', 100);
				}
				else{
					$(".bx_filter_block:not(.limited_block)").velocity('fadeOut', 100);
					$('.bx_filter_parameters_box').removeClass('active');

					var _this = $(this);
					setTimeout(function(){
						var boxOffsetLeft =_this.offset().left,
							boxWidth = _this.next(".bx_filter_block").outerWidth(),
							windowWidth = $(window).width();

						if(boxOffsetLeft + boxWidth > windowWidth){
							_this.next(".bx_filter_block").addClass('right');
						}
						else{
							_this.next(".bx_filter_block").removeClass('right');
						}
					}, 0);

					_this.next(".bx_filter_block").stop().velocity('transition.slideUpIn', {
						duration: 300,
						delay: 250,
					});
				}
				$box.toggleClass("active");
			}
		});

		$(document).on('click', '.bx_filter_block .bx_filter_button_box .btn', function(){
			$(this).closest('form').find('.bx_filter_search_button').click();
		});

		$(document).on('click', function(e){
			// $(".bx_filter_block:not(.limited_block)").slideUp(100);
			$(".filter-compact-block .bx_filter_block:not(.limited_block)").stop().velocity('fadeOut', 100);
			$('.filter-compact-block .bx_filter_parameters_box').removeClass('active');
		});
		$(document).click(function(e) {
			if(!$(e.target).hasClass('tooltip')){
				$('.bx_filter .hint.active').removeClass("active").find(".tooltip").slideUp(200);
			}
		});
		window.JCSmartFilterBinds = true;
	})
}
	function JCSmartFilter(ajaxURL, viewMode, params)
	{
		this.ajaxURL = ajaxURL;
		this.form = null;
		this.timer = null;
		this.cacheKey = '';
		this.cache = [];
		this.viewMode = viewMode;
		this.normal_url=false;
		this.reset=false;
		this.SEF_SET_FILTER_URL=params.SEF_SET_FILTER_URL;
		this.SEF_DEL_FILTER_URL=params.SEF_DEL_FILTER_URL;
		/*if (params && params.SEF_SET_FILTER_URL)
		{*/
			this.bindUrlToButton('set_filter', params.SEF_SET_FILTER_URL);
			this.sef = true;
		/*}
		if (params && params.SEF_DEL_FILTER_URL)
		{*/
			this.bindUrlToButton('del_filter', params.SEF_DEL_FILTER_URL);
		//}
		if(!params.SEF_DEL_FILTER_URL){
			this.normal_url=true;
		}
	}

	JCSmartFilter.prototype.keyup = function(input)
	{
		if(!!this.timer)
		{
			clearTimeout(this.timer);
		}
		if(!$(input).hasClass('disabled')){
			this.timer = setTimeout(BX.delegate(function(){
				this.reload(input);
			}, this), 500);
		}
	};

	JCSmartFilter.prototype.click = function(checkbox)
	{
		if(!!this.timer)
		{
			clearTimeout(this.timer);
		}

		this.timer = setTimeout(BX.delegate(function(){
			this.reload(checkbox);
		}, this), 500);
	};

	JCSmartFilter.prototype.reload = function(input)
	{
		if (this.cacheKey !== '')
		{
			//Postprone backend query
			if(!!this.timer)
			{
				clearTimeout(this.timer);
			}
			this.timer = setTimeout(BX.delegate(function(){
				this.reload(input);
			}, this), 1000);
			return;
		}
		this.cacheKey = '|';

		this.position = BX.pos(input, true);
		this.form = BX.findParent(input, {'tag':'form'});

		if (this.form)
		{
			if($('.middle > .container .ajax_load').length)
				$('.middle > .container .ajax_load').addClass('loading-state');

			var values = [];
			values[0] = {name: 'ajax', value: 'y'};
			this.gatherInputsValues(values, BX.findChildren(this.form, {'tag': new RegExp('^(input|select)$', 'i')}, true));

			$('.bx_filter_parameters_box').removeClass('set');

			for (var i = 0; i < values.length; i++){
				this.cacheKey += values[i].name + ':' + values[i].value + '|';
				$('input[name='+values[i].name+']').closest('.bx_filter_parameters_box').addClass('set');
			}

			if (this.cache[this.cacheKey])
			{
				this.curFilterinput = input;
				this.postHandler(this.cache[this.cacheKey], true);
			}
			else
			{
				if (this.sef)
				{
					var set_filter = BX('set_filter'),
						reset_filter = BX('del_filter');

					if(set_filter)
						set_filter.disabled = true;
					if(reset_filter)
						reset_filter.disabled = true;
				}

				this.curFilterinput = input;

				BX.ajax.loadJSON(
					this.ajaxURL,
					this.values2post(values),
					BX.delegate(this.postHandler, this)
				);
			}
		}

		var i = 0;
		$(input).closest('.bx_filter_block').find('input[type=checkbox], input[type=radio]').each(function(){
			if($(this).prop('checked') === true){
				++i;
			}
		}).promise().done(function(){
			if(i != 0){
				$(input).closest('.bx_filter_parameters_box').find('.bx_filter_parameters_box_title .count_selected').text(': '+i);
			}
			else{
				$(input).closest('.bx_filter_parameters_box').find('.bx_filter_parameters_box_title .count_selected').text('');
			}
		});
	};

	JCSmartFilter.prototype.updateItem = function (PID, arItem, reset)
	{
		if (arItem.PROPERTY_TYPE === 'N' || arItem.PRICE)
		{
			var trackBar = window['trackBar' + PID];
			if (!trackBar && arItem.ENCODED_ID)
				trackBar = window['trackBar' + arItem.ENCODED_ID];

			if (trackBar && arItem.VALUES)
			{
				if (arItem.VALUES.MIN)
				{
					if (arItem.VALUES.MIN.FILTERED_VALUE)
						trackBar.setMinFilteredValue(arItem.VALUES.MIN.FILTERED_VALUE);
					else
						trackBar.setMinFilteredValue(arItem.VALUES.MIN.VALUE);
				}

				if (arItem.VALUES.MAX)
				{
					if (arItem.VALUES.MAX.FILTERED_VALUE)
						trackBar.setMaxFilteredValue(arItem.VALUES.MAX.FILTERED_VALUE);
					else
						trackBar.setMaxFilteredValue(arItem.VALUES.MAX.VALUE);
				}
				if(reset=="Y"){
					trackBar.leftPercent=trackBar.rightPercent=0;
					$("#"+arItem.VALUES.MIN.CONTROL_ID).val('');
					$("#"+arItem.VALUES.MAX.CONTROL_ID).val('');
					$("#left_slider_"+arItem.ENCODED_ID).css({'left':"0%"});
					$("#colorUnavailableActive_"+arItem.ENCODED_ID).css({'left':"0%", 'right' : "0%"});
					$("#colorAvailableInactive_"+arItem.ENCODED_ID).css({'left':"0%", 'right' : "0%"});
					$("#colorAvailableActive_"+arItem.ENCODED_ID).css({'left':"0%", 'right' : "0%"});
					$("#right_slider_"+arItem.ENCODED_ID).css({'right':"0%"});
				}
			}
		}
		else if (arItem.VALUES)
		{
			for (var i in arItem.VALUES)
			{
				if (arItem.VALUES.hasOwnProperty(i))
				{
					var value = arItem.VALUES[i];
					var control = BX(value.CONTROL_ID);
					if (!!control)
					{
						var label = document.querySelector('[data-role="label_'+value.CONTROL_ID+'"]');
							input = document.querySelector('[id="'+value.CONTROL_ID+'"]');

							if (value.DISABLED)
						{
							if (label){
								BX.addClass(label, 'disabled');
								if(input){
									input.setAttribute('disabled','disabled');
									BX.addClass(input, 'disabled');
								}
							}else{
								BX.addClass(control.parentNode, 'disabled');
							}


						}
						else
						{
							if (label){
								BX.removeClass(label, 'disabled');
								if(input){
									input.removeAttribute('disabled');
									BX.removeClass(input, 'disabled');
								}
								// if(reset=="Y"){
									if($(control)){
										$(control).prop('disabled',false);
										$(control).removeClass('disabled');
									}
									$(label).find('span').removeClass('disabled');
								// }
							}else
								BX.removeClass(control.parentNode, 'disabled');
						}

						if(reset=="Y"){
							if($(control).attr("type")=="checkbox" || $(control).attr("type")=="radio"){
								if($(control).attr("checked")){
									$(control).prop('checked',false);
									// input.removeAttribute('checked');
								}
							}
						}

						if (value.hasOwnProperty('ELEMENT_COUNT'))
						{
							label = document.querySelector('[data-role="count_'+value.CONTROL_ID+'"]');
							if (label)
								label.innerHTML = value.ELEMENT_COUNT;
						}
					}
				}
			}
		}
	};

	JCSmartFilter.prototype.postHandlerAjax = function (result, fromCache)
	{
		$('#content').html(result);
	}

	JCSmartFilter.prototype.setUrlSortDisplay = function (url)
	{
		var arReplace_url='',
			strReplace_url='';

		$('.sort_btn').each(function(){
			arReplace_url=$(this).attr('href').split("?");
			arReplace_url[0]=url;
			strReplace_url=arReplace_url.join('?');

			$(this).attr('href', strReplace_url);
		})
	}

	JCSmartFilter.prototype.filterCatalog = function (url, set_disabled, reset, count)
	{
		if(window.History.enabled || window.history.pushState != null){
			window.History.pushState( null, document.title, decodeURIComponent(url) );
		}else{
			location.href = url;
		}

		// this.setUrlSortDisplay(url);

		if($('.middle > .container .ajax_load').length)
			$('.middle > .container .ajax_load').addClass('loading-state');

		$.ajax({
			url:url,
			type: "GET",
			data: {'ajax_get':'Y', 'ajax_get_filter':'Y'},
			success: function(html){
				if(reset && (!$('#wrapInlineFilter').length || !$('#wrapInlineFilter').is(':empty')))
				{
					$('.js-load-wrapper').html($(html).find('.js-load-wrapper').html());
					$('.top-content-block').html($(html).find('.top-content-block').html());

					if ('checkFilterLandgings' in window && typeof checkFilterLandgings == 'function') {
						checkFilterLandgings()
					}
				}
				else
				{
					var itemsHtml = $(html).find('.inner_wrapper').html();
					$('.middle > .container .inner_wrapper').html(itemsHtml);

					//ajax sort-block
					$('.sort_header').html($(html).find('.sort_header').html())

					//ajax top-block
					$('.top-content-block').html($(html).find('.top-content-block').html())

					if($(html).find('.filter_title').hasClass('active-filter'))
					{
						$('.filter_title').addClass('active-filter');
						$('.bx_filter_search_reset ').removeClass('hidden');
					}
					else
					{
						$('.filter_title').removeClass('active-filter');
						$('.bx_filter_search_reset ').addClass('hidden');
					}

					if(reset && $('#mobilefilter .bx_filter').length)
					{
						$("#mobilefilter").html('');
						$(html).find(".bx_filter").appendTo($("#mobilefilter"));
						$("#mobilefilter .bx_filter_parameters").addClass('scrollbar');

						if (window.matchMedia('(max-width: 767px)').matches) {
							$('#mobilefilter .bx_filter .scrollbar').addClass('mobile-scroll').removeClass('scroll-init');
							if($('#mobilefilter .bx_filter .mobile-scroll.scrollbar').length)
								$('#mobilefilter .bx_filter .mobile-scroll.scrollbar').mCustomScrollbar("destroy");
							if($('#mobilefilter .bx_filter .mobile-scroll.srollbar-custom').length)
								$('#mobilefilter .bx_filter .mobile-scroll.srollbar-custom').mCustomScrollbar("destroy");
						}

						$("#mobilefilter .bx_filter_parameters .bx_filter_parameters_box_title").addClass('colored_theme_hover_bg-block');
						InitCustomScrollBar();

						smartFilter.bindUrlToButton('set_filter', smartFilter.SEF_SET_FILTER_URL);
						smartFilter.bindUrlToButton('del_filter', smartFilter.SEF_DEL_FILTER_URL);

						mobileFilterNum(count);

						if(window.sortFilterPopup !== undefined && typeof(window.sortFilterPopup.destroy) == 'function' ) {
							window.sortFilterPopup.destroy();
						}
					}

					if ('checkFilterLandgings' in window && typeof checkFilterLandgings == 'function') {
						checkFilterLandgings()
					}
				}

				$('.fast_view_frame').remove();

				BX.onCustomEvent('onAjaxSuccessFilter');

				if(set_disabled=="Y"){
					var set_filter = BX('set_filter'),
						reset_filter = BX('del_filter');

					if(set_filter)
						set_filter.disabled = false;
					if(reset_filter)
						reset_filter.disabled = false;
				}

				var eventdata = {action:'jsLoadBlock'};
				BX.onCustomEvent('onCompleteAction', [eventdata, this]);

				if($('.middle > .container .ajax_load').length)
					$('.middle > .container .ajax_load').removeClass('loading-state');
			}
		})
	}

	JCSmartFilter.prototype.postHandler = function (result, fromCache)
	{
		var hrefFILTER, url, curProp;
		var modef = BX('modef');
		var modef_mobile = BX('modef_mobile');
		var modef_num = BX('modef_num');
		var modef_num_mobile = BX('modef_num_mobile');
		var reset="N";

		if ('RESET_FORM' in result){
			document.getElementsByClassName("smartfilter")[0].reset();
			reset="Y";
		}

		if (!!result && !!result.ITEMS)
		{
			for(var PID in result.ITEMS)
			{
				if (result.ITEMS.hasOwnProperty(PID))
				{
					this.updateItem(PID, result.ITEMS[PID], reset);
				}
			}

			if(reset=="Y"){
				if($(".bx_filter_select_block").length){
					$(".bx_filter_select_block").each(function(){
						var id=$(this).closest('.bx_filter_parameters_box').attr('property_id'),
							all_text=$(this).find('.input_wr_all input:first-child').data('title');
						$(this).find('.bx_filter_select_text').text(all_text);
						$(this).find('.bx_filter_select_popup li label').removeClass('selected');
					})
				}
			}
			if (!!modef && !!modef_num)
			{
				//modef_num.innerHTML = result.ELEMENT_COUNT;
				$('.bx_filter.compact .bx_filter_button_box .bx_filter_container_modef').html(BX.message('SELECTED')+result.ELEMENT_COUNT);
				$('.bx_filter.compact .bx_filter_button_box').show();
				//modef_num_mobile.innerHTML = result.ELEMENT_COUNT;
				if(typeof mobileFilterNum === 'function'){
	                mobileFilterNum(result.ELEMENT_COUNT);
	            }
				hrefFILTER = BX.findChildren(modef, {tag: 'A'}, true);
				hrefFILTER_mobile = BX.findChildren(modef_mobile, {tag: 'A'}, true);

				if (result.FILTER_URL && hrefFILTER)
				{
					hrefFILTER[0].href = BX.util.htmlspecialcharsback(result.FILTER_URL.replace('/filter/clear/apply/', '/'));
					hrefFILTER_mobile[0].href = BX.util.htmlspecialcharsback(result.FILTER_URL.replace('/filter/clear/apply/', '/'));
				}

				if (result.FILTER_AJAX_URL && result.COMPONENT_CONTAINER_ID)
				{
					BX.unbindAll(hrefFILTER[0]);
					BX.unbindAll(hrefFILTER_mobile[0]);
					BX.bind(hrefFILTER[0], 'click', function(e)
					{
						url = BX.util.htmlspecialcharsback(result.FILTER_AJAX_URL);
						BX.ajax.insertToNode(url, result.COMPONENT_CONTAINER_ID);
						return BX.PreventDefault(e);
					});
				}

				if (result.INSTANT_RELOAD && result.COMPONENT_CONTAINER_ID)
				{
					url = BX.util.htmlspecialcharsback(result.FILTER_AJAX_URL);
					BX.ajax.insertToNode(url, result.COMPONENT_CONTAINER_ID);
				}
				else
				{
					/*ajax update filter catalog items start*/
					url = BX.util.htmlspecialcharsback(result.FILTER_AJAX_URL);
					// BX.ajax.insertToNode(url+'?ajax_get=Y', "right_block_ajax");

					this.filterCatalog(url, "N", this.reset, result.ELEMENT_COUNT);

					/*ajax update filter catalog items end*/

					/*if (modef.style.display === 'none')
					{
						modef.style.display = 'inline-block';
						modef_mobile.style.display = 'inline-block';
					}
					if (this.viewMode == "vertical")
					{
						curProp = BX.findChild(BX.findParent(this.curFilterinput, {'class':'bx_filter_parameters_box'}), {'class':'bx_filter_container_modef'}, true, false);
						curProp.appendChild(modef);
					}*/

					if (result.SEF_SET_FILTER_URL)
					{
						this.bindUrlToButton('set_filter', result.SEF_SET_FILTER_URL);
					}else{
						this.bindUrlToButton('set_filter', url);
					}
				}
			}
		}

		if (this.sef)
		{
			var set_filter = BX('set_filter'),
				reset_filter = BX('del_filter');

			if(set_filter)
				set_filter.disabled = false;
			if(reset_filter)
				reset_filter.disabled = false;
		}

		if (!fromCache && this.cacheKey !== '')
		{
			this.cache[this.cacheKey] = result;
		}
		this.cacheKey = '';
	};

	JCSmartFilter.prototype.bindUrlToButton = function (buttonId, url)
	{
		var button = BX(buttonId);
		if (button)
		{
			var proxy = function(j, func)
			{
				return function()
				{
					return func(j);
				}
			};

			if (button.type == 'submit')
				button.type = 'button';

			$(button).data("href", url);
			BX.unbindAll(button);

			BX.bind(button, 'click', BX.proxy(function(){
				var url_filter=$(button).data('href'),
					id=$(button).attr('id');

				if($('.middle > .container .ajax_load').length)
					$('.middle > .container .ajax_load').addClass('loading-state');

				if(id=="del_filter"){
					var values = [],
						url_form =this.normal_url ? $('form.smartfilter').attr('action') : this.ajaxURL;
					values[0] = {name: 'ajax', value: 'y'};

					if(!this.normal_url){
						this.gatherInputsValues(values, BX.findChildren(document.getElementById("smartfilter"), {'attribute': 'hidden'}, true));
					}
					values[1] = {name: 'reset_form', value: 'y'};
					if (this.sef)
					{
						var set_filter = BX('set_filter'),
							reset_filter = BX('del_filter');

						if(set_filter)
							set_filter.disabled = true;
						if(reset_filter)
							reset_filter.disabled = true;
					}
					this.reset = true;
					BX.ajax.loadJSON(
						url_form,
						this.values2post(values),
						BX.delegate(this.postHandler, this)
					);
				}else{
					if(url_filter){
						if (this.sef)
						{
							var set_filter = BX('set_filter'),
								reset_filter = BX('del_filter');

							if(set_filter)
								set_filter.disabled = true;
							if(reset_filter)
								reset_filter.disabled = true;
						}

						this.filterCatalog(url_filter, "Y");
					}
				}
			}, this));
		}
	};

	JCSmartFilter.prototype.gatherInputsValues = function (values, elements)
	{
		if(elements)
		{
			for(var i = 0; i < elements.length; i++)
			{
				var el = elements[i];
				if (!el.type)
					continue;

				switch(el.type.toLowerCase())
				{
					case 'text':
					case 'textarea':
					case 'password':
					case 'hidden':
					case 'select-one':
						if(el.value.length)
							values[values.length] = {name : el.name, value : el.value};
						break;
					case 'radio':
					case 'checkbox':
						if(el.checked)
							values[values.length] = {name : el.name, value : el.value};
						break;
					case 'select-multiple':
						for (var j = 0; j < el.options.length; j++)
						{
							if (el.options[j].selected)
								values[values.length] = {name : el.name, value : el.options[j].value};
						}
						break;
					default:
						break;
				}
			}
		}
	};

	JCSmartFilter.prototype.values2post = function (values)
	{
		var post = [];
		var current = post;
		var i = 0;

		while(i < values.length)
		{
			var p = values[i].name.indexOf('[');
			if(p == -1)
			{
				current[values[i].name] = values[i].value;
				current = post;
				i++;
			}
			else
			{
				var name = values[i].name.substring(0, p);
				var rest = values[i].name.substring(p+1);
				if(!current[name])
					current[name] = [];

				var pp = rest.indexOf(']');
				if(pp == -1)
				{
					//Error - not balanced brackets
					current = post;
					i++;
				}
				else if(pp == 0)
				{
					//No index specified - so take the next integer
					current = current[name];
					values[i].name = '' + current.length;
				}
				else
				{
					//Now index name becomes and name and we go deeper into the array
					current = current[name];
					values[i].name = rest.substring(0, pp) + rest.substring(pp+1);
				}
			}
		}
		return post;
	};

	JCSmartFilter.prototype.hideFilterProps = function(element)
	{
		var obj = element.parentNode,
			filterBlock = obj.querySelector("[data-role='bx_filter_block']"),
			propAngle = obj.querySelector("[data-role='prop_angle']");

		if(BX.hasClass(obj, "bx-active"))
		{
			new BX.easing({
				duration : 300,
				start : { opacity: 1,  height: filterBlock.offsetHeight },
				finish : { opacity: 0, height:0 },
				transition : BX.easing.transitions.quart,
				step : function(state){
					filterBlock.style.opacity = state.opacity;
					filterBlock.style.height = state.height + "px";
				},
				complete : function() {
					filterBlock.setAttribute("style", "");
					BX.removeClass(obj, "bx-active");
				}
			}).animate();

			BX.addClass(propAngle, "fa-angle-down");
			BX.removeClass(propAngle, "fa-angle-up");
		}
		else
		{
			filterBlock.style.display = "block";
			filterBlock.style.opacity = 0;
			filterBlock.style.height = "auto";

			var obj_children_height = filterBlock.offsetHeight;
			filterBlock.style.height = 0;

			new BX.easing({
				duration : 300,
				start : { opacity: 0,  height: 0 },
				finish : { opacity: 1, height: obj_children_height },
				transition : BX.easing.transitions.quart,
				step : function(state){
					filterBlock.style.opacity = state.opacity;
					filterBlock.style.height = state.height + "px";
				},
				complete : function() {
				}
			}).animate();

			BX.addClass(obj, "bx-active");
			BX.removeClass(propAngle, "fa-angle-down");
			BX.addClass(propAngle, "fa-angle-up");
		}
	};

	JCSmartFilter.prototype.showDropDownPopup = function(element, popupId)
	{
		var contentNode = element.querySelector('[data-role="dropdownContent"]'),
			offset=$(element).offset();
		var el = BX.PopupWindowManager.create("smartFilterDropDown"+popupId, element, {
			autoHide: true,
			offsetLeft: 0,
			offsetTop: 0,
			overlay : false,
			draggable: {restrict:true},
			closeByEsc: true,
			content: contentNode
		});

		if(popupId == 'ASPRO_FILTER_SORT') {
			window.sortFilterPopup = el;
		}

		el.show();

		if(!$(element).closest('.bx_filter_parameters_box_container').find("#smartFilterDropDown"+popupId).length)
			$("#smartFilterDropDown"+popupId).insertAfter($(element).closest('.bx_filter_parameters_box_container'));

		$("#smartFilterDropDown"+popupId).addClass('srollbar-custom').css({'top':'auto', 'left':'auto'/*, 'width':$(element).css('width')*/});
		InitScrollBar();
	};

	JCSmartFilter.prototype.selectDropDownItem = function(element, controlId)
	{
		if(!BX.hasClass(element,'disabled')){
			this.keyup(BX(controlId));

			var wrapContainer = BX.findParent(BX(controlId), {className:"bx_filter_select_container"}, false);

			var currentOption = wrapContainer.querySelector('[data-role="currentOption"]');

			currentOption.innerHTML = element.innerHTML;
			$(element).closest('.bx_filter_select_popup').find('label').removeClass('selected');
			BX.addClass(element, "selected");

			if(BX.PopupWindowManager.getCurrentPopup())
				BX.PopupWindowManager.getCurrentPopup().close();
		}
	};

	BX.namespace("BX.Iblock.SmartFilter");
	BX.Iblock.SmartFilter = (function()
	{
		var SmartFilter = function(arParams)
		{
			if (typeof arParams === 'object')
			{
				this.leftSlider = BX(arParams.leftSlider);
				this.rightSlider = BX(arParams.rightSlider);
				this.tracker = BX(arParams.tracker);
				this.trackerWrap = BX(arParams.trackerWrap);

				this.minInput = BX(arParams.minInputId);
				this.maxInput = BX(arParams.maxInputId);

				this.minPrice = parseFloat(arParams.minPrice);
				this.maxPrice = parseFloat(arParams.maxPrice);

				this.curMinPrice = parseFloat(arParams.curMinPrice);
				this.curMaxPrice = parseFloat(arParams.curMaxPrice);

				this.fltMinPrice = arParams.fltMinPrice ? parseFloat(arParams.fltMinPrice) : parseFloat(arParams.curMinPrice);
				this.fltMaxPrice = arParams.fltMaxPrice ? parseFloat(arParams.fltMaxPrice) : parseFloat(arParams.curMaxPrice);

				this.precision = arParams.precision || 0;

				this.priceDiff = this.maxPrice - this.minPrice;

				this.leftPercent = arParams.leftPercent ? parseFloat(arParams.leftPercent) : 0;
				this.rightPercent = arParams.rightPercent ? parseFloat(arParams.rightPercent) : 0;

				this.fltMinPercent = 0;
				this.fltMaxPercent = 0;

				this.colorUnavailableActive = BX(arParams.colorUnavailableActive);//gray
				this.colorAvailableActive = BX(arParams.colorAvailableActive);//blue
				this.colorAvailableInactive = BX(arParams.colorAvailableInactive);//light blue

				this.isTouch = false;

				this.init();

				if ('ontouchstart' in document.documentElement || 'ontouchstart' in window)
				{
					this.isTouch = true;

					BX.bind(this.leftSlider, "touchstart", BX.proxy(function(event){
						this.onMoveLeftSlider(event)
					}, this));

					BX.bind(this.rightSlider, "touchstart", BX.proxy(function(event){
						this.onMoveRightSlider(event)
					}, this));

					BX.bind(this.colorAvailableActive, "touchstart", BX.proxy(function(event){
						this.onChangeLeftSlider(event);
					}, this));

					BX.bind(this.colorUnavailableActive, "touchstart", BX.proxy(function(event){
						this.onChangeLeftSlider(event);
					}, this));

					BX.bind(this.colorAvailableInactive, "touchstart", BX.proxy(function(event){
						this.onChangeLeftSlider(event);
					}, this));
				}
				else
				{
					BX.bind(this.leftSlider, "mousedown", BX.proxy(function(event){
						this.onMoveLeftSlider(event)
					}, this));

					BX.bind(this.rightSlider, "mousedown", BX.proxy(function(event){
						this.onMoveRightSlider(event)
					}, this));

					BX.bind(this.colorAvailableActive, "mousedown", BX.proxy(function(event){
						this.onChangeLeftSlider(event);
					}, this));

					BX.bind(this.colorUnavailableActive, "mousedown", BX.proxy(function(event){
						this.onChangeLeftSlider(event);
					}, this));

					BX.bind(this.colorAvailableInactive, "mousedown", BX.proxy(function(event){
						this.onChangeLeftSlider(event);
					}, this));
				}

				BX.bind(this.minInput, "keyup", BX.proxy(function(event){
					this.onInputChange();
				}, this));

				BX.bind(this.maxInput, "keyup", BX.proxy(function(event){
					this.onInputChange();
				}, this));
			}
		};

		SmartFilter.prototype.init = function()
		{
			var priceDiff;

			if (this.curMinPrice > this.minPrice)
			{
				priceDiff = this.curMinPrice - this.minPrice;
				this.leftPercent = (priceDiff*100)/this.priceDiff;

				this.leftSlider.style.left = this.leftPercent + "%";
				this.colorUnavailableActive.style.left = this.leftPercent + "%";
			}

			this.setMinFilteredValue(this.fltMinPrice);

			if (this.curMaxPrice < this.maxPrice)
			{
				priceDiff = this.maxPrice - this.curMaxPrice;
				this.rightPercent = (priceDiff*100)/this.priceDiff;

				this.rightSlider.style.right = this.rightPercent + "%";
				this.colorUnavailableActive.style.right = this.rightPercent + "%";
			}

			this.setMaxFilteredValue(this.fltMaxPrice);
		};

		SmartFilter.prototype.setMinFilteredValue = function (fltMinPrice)
		{
			this.fltMinPrice = parseFloat(fltMinPrice);
			if (this.fltMinPrice >= this.minPrice)
			{
				var priceDiff = this.fltMinPrice - this.minPrice;
				this.fltMinPercent = (priceDiff*100)/this.priceDiff;

				if (this.leftPercent > this.fltMinPercent)
					this.colorAvailableActive.style.left = this.leftPercent + "%";
				else
					this.colorAvailableActive.style.left = this.fltMinPercent + "%";

				this.colorAvailableInactive.style.left = this.fltMinPercent + "%";
			}
			else
			{
				this.colorAvailableActive.style.left = "0%";
				this.colorAvailableInactive.style.left = "0%";
			}
		};

		SmartFilter.prototype.setMaxFilteredValue = function (fltMaxPrice)
		{
			this.fltMaxPrice = parseFloat(fltMaxPrice);
			if (this.fltMaxPrice <= this.maxPrice)
			{
				var priceDiff = this.maxPrice - this.fltMaxPrice;
				this.fltMaxPercent = (priceDiff*100)/this.priceDiff;

				if (this.rightPercent > this.fltMaxPercent)
					this.colorAvailableActive.style.right = this.rightPercent + "%";
				else
					this.colorAvailableActive.style.right = this.fltMaxPercent + "%";

				this.colorAvailableInactive.style.right = this.fltMaxPercent + "%";
			}
			else
			{
				this.colorAvailableActive.style.right = "0%";
				this.colorAvailableInactive.style.right = "0%";
			}
		};

		SmartFilter.prototype.getXCoord = function(elem)
		{
			var box = elem.getBoundingClientRect();
			var body = document.body;
			var docElem = document.documentElement;

			var scrollLeft = window.pageXOffset || docElem.scrollLeft || body.scrollLeft;
			var clientLeft = docElem.clientLeft || body.clientLeft || 0;
			var left = box.left + scrollLeft - clientLeft;

			return Math.round(left);
		};

		SmartFilter.prototype.getPageX = function(e)
		{
			e = e || window.event;
			var pageX = null;

			if (this.isTouch && e.targetTouches[0] != null)
			{
				pageX = e.targetTouches[0].pageX;
			}
			else if (e.pageX != null)
			{
				pageX = e.pageX;
			}
			else if (e.clientX != null)
			{
				var html = document.documentElement;
				var body = document.body;

				pageX = e.clientX + (html.scrollLeft || body && body.scrollLeft || 0);
				pageX -= html.clientLeft || 0;
			}

			return pageX;
		};

		SmartFilter.prototype.recountMinPrice = function()
		{
			var newMinPrice = (this.priceDiff*this.leftPercent)/100;
			newMinPrice = (this.minPrice + newMinPrice).toFixed(this.precision);

			if (newMinPrice != this.minPrice)
				this.minInput.value = newMinPrice;
			else
				this.minInput.value = "";
			smartFilter.keyup(this.minInput);
		};

		SmartFilter.prototype.recountMaxPrice = function()
		{
			var newMaxPrice = (this.priceDiff*this.rightPercent)/100;
			newMaxPrice = (this.maxPrice - newMaxPrice).toFixed(this.precision);

			if (newMaxPrice != this.maxPrice)
				this.maxInput.value = newMaxPrice;
			else
				this.maxInput.value = "";
			smartFilter.keyup(this.maxInput);
		};

		SmartFilter.prototype.onInputChange = function ()
		{
			var priceDiff;
			if (this.minInput.value)
			{
				var leftInputValue = this.minInput.value;
				if (leftInputValue < this.minPrice)
					leftInputValue = this.minPrice;

				if (leftInputValue > this.maxPrice)
					leftInputValue = this.maxPrice;

				priceDiff = leftInputValue - this.minPrice;
				this.leftPercent = (priceDiff*100)/this.priceDiff;

				this.makeLeftSliderMove(false);
			}

			if (this.maxInput.value)
			{
				var rightInputValue = this.maxInput.value;
				if (rightInputValue < this.minPrice)
					rightInputValue = this.minPrice;

				if (rightInputValue > this.maxPrice)
					rightInputValue = this.maxPrice;

				priceDiff = this.maxPrice - rightInputValue;
				this.rightPercent = (priceDiff*100)/this.priceDiff;

				this.makeRightSliderMove(false);
			}
		};

		SmartFilter.prototype.makeLeftSliderMove = function(recountPrice)
		{
			recountPrice = (recountPrice !== false);

			this.leftSlider.style.left = this.leftPercent + "%";
			this.colorUnavailableActive.style.left = this.leftPercent + "%";

			var areBothSlidersMoving = false;
			if (this.leftPercent + this.rightPercent >= 100)
			{
				areBothSlidersMoving = true;
				this.rightPercent = 100 - this.leftPercent;
				this.rightSlider.style.right = this.rightPercent + "%";
				this.colorUnavailableActive.style.right = this.rightPercent + "%";
			}

			if (this.leftPercent >= this.fltMinPercent && this.leftPercent <= (100-this.fltMaxPercent))
			{
				this.colorAvailableActive.style.left = this.leftPercent + "%";
				if (areBothSlidersMoving)
				{
					this.colorAvailableActive.style.right = 100 - this.leftPercent + "%";
				}
			}
			else if(this.leftPercent <= this.fltMinPercent)
			{
				this.colorAvailableActive.style.left = this.fltMinPercent + "%";
				if (areBothSlidersMoving)
				{
					this.colorAvailableActive.style.right = 100 - this.fltMinPercent + "%";
				}
			}
			else if(this.leftPercent >= this.fltMaxPercent)
			{
				this.colorAvailableActive.style.left = 100-this.fltMaxPercent + "%";
				if (areBothSlidersMoving)
				{
					this.colorAvailableActive.style.right = this.fltMaxPercent + "%";
				}
			}

			if (recountPrice)
			{
				this.recountMinPrice();
				if (areBothSlidersMoving)
					this.recountMaxPrice();
			}
		};

		SmartFilter.prototype.countNewLeft = function(event)
		{
			var pageX = this.getPageX(event);

			var trackerXCoord = this.getXCoord(this.trackerWrap);
			var rightEdge = this.trackerWrap.offsetWidth;

			var newLeft = pageX - trackerXCoord;

			if (newLeft < 0)
				newLeft = 0;
			else if (newLeft > rightEdge)
				newLeft = rightEdge;

			return newLeft;
		};

		SmartFilter.prototype.onMoveLeftSlider = function(e)
		{
			if (!this.isTouch)
			{
				this.leftSlider.ondragstart = function() {
					return false;
				};
			}

			$('.bx_filter .bx_filter_parameters_box_container input').prop('disabled', false);

			if (!this.isTouch)
			{
				document.onmousemove = BX.proxy(function(event) {
					this.leftPercent = ((this.countNewLeft(event)*100)/this.trackerWrap.offsetWidth);
					this.makeLeftSliderMove();
				}, this);

				document.onmouseup = function() {
					document.onmousemove = document.onmouseup = null;
				};
			}
			else
			{
				var onMoveFunction = BX.proxy(function(event) {
					this.leftPercent = ((this.countNewLeft(event)*100)/this.trackerWrap.offsetWidth);
					this.makeLeftSliderMove();
				}, this);

				var onEndFunction = BX.proxy(function(event) {
					window.removeEventListener('touchmove', onMoveFunction, false);
					window.removeEventListener('touchend', onEndFunction, false);
					onMoveFunction = onEndFunction = null;
				}, this);

				window.addEventListener('touchmove', onMoveFunction, false);
				window.addEventListener('touchend', onEndFunction, false);

				/*document.ontouchmove = BX.proxy(function(event) {
					this.leftPercent = ((this.countNewLeft(event)*100)/this.trackerWrap.offsetWidth);
					this.makeLeftSliderMove();
				}, this);

				document.ontouchend = function() {
					document.ontouchmove = document.touchend = null;
				};*/
			}

			return false;
		};

		SmartFilter.prototype.makeRightSliderMove = function(recountPrice)
		{
			recountPrice = (recountPrice !== false);

			this.rightSlider.style.right = this.rightPercent + "%";
			this.colorUnavailableActive.style.right = this.rightPercent + "%";

			var areBothSlidersMoving = false;
			if (this.leftPercent + this.rightPercent >= 100)
			{
				areBothSlidersMoving = true;
				this.leftPercent = 100 - this.rightPercent;
				this.leftSlider.style.left = this.leftPercent + "%";
				this.colorUnavailableActive.style.left = this.leftPercent + "%";
			}

			if ((100-this.rightPercent) >= this.fltMinPercent && this.rightPercent >= this.fltMaxPercent)
			{
				this.colorAvailableActive.style.right = this.rightPercent + "%";
				if (areBothSlidersMoving)
				{
					this.colorAvailableActive.style.left = 100 - this.rightPercent + "%";
				}
			}
			else if(this.rightPercent <= this.fltMaxPercent)
			{
				this.colorAvailableActive.style.right = this.fltMaxPercent + "%";
				if (areBothSlidersMoving)
				{
					this.colorAvailableActive.style.left = 100 - this.fltMaxPercent + "%";
				}
			}
			else if((100-this.rightPercent) <= this.fltMinPercent)
			{
				this.colorAvailableActive.style.right = 100-this.fltMinPercent + "%";
				if (areBothSlidersMoving)
				{
					this.colorAvailableActive.style.left = this.fltMinPercent + "%";
				}
			}

			if (recountPrice)
			{
				this.recountMaxPrice();
				if (areBothSlidersMoving)
					this.recountMinPrice();
			}
		};

		SmartFilter.prototype.onMoveRightSlider = function(e)
		{
			if (!this.isTouch)
			{
				this.rightSlider.ondragstart = function() {
					return false;
				};
			}

			$('.bx_filter .bx_filter_parameters_box_container input').prop('disabled', false);

			if (!this.isTouch)
			{
				document.onmousemove = BX.proxy(function(event) {
					this.rightPercent = 100-(((this.countNewLeft(event))*100)/(this.trackerWrap.offsetWidth));
					this.makeRightSliderMove();
				}, this);

				document.onmouseup = function() {
					document.onmousemove = document.onmouseup = null;
				};
			}
			else
			{
				document.ontouchmove = BX.proxy(function(event) {
					this.rightPercent = 100-(((this.countNewLeft(event))*100)/(this.trackerWrap.offsetWidth));
					this.makeRightSliderMove();
				}, this);

				document.ontouchend = function() {
					document.ontouchmove = document.ontouchend = null;
				};
			}

			return false;
		};

		SmartFilter.prototype.onChangeLeftSlider = function(e)
		{
			if (!this.isTouch)
			{
				this.leftSlider.ondragstart = function() {
					//return false;
				};
			}

			if (!this.isTouch)
			{
				// document.onmousedown = BX.proxy(function(event) {
					var percent=((this.countNewLeft(event)*100)/this.trackerWrap.offsetWidth);
					if($(event.target).is(".bx_ui_slider_handle") || !$(event.target).is("[class^=bx_ui_slider]"))
						return;
					if(percent<50){
						this.leftPercent = percent+1;
						this.makeLeftSliderMove();
					}else{
						this.rightPercent = 100-percent;
						this.makeRightSliderMove();
					}
				// }, this);

				document.onmouseup = function() {
					document.onmousemove = document.onmouseup = null;
				};
			}
			else
			{
				var percent=((this.countNewLeft(e)*100)/this.trackerWrap.offsetWidth);
				if($(e.target).is(".bx_ui_slider_handle"))
					return;
				if(percent<50){
					this.leftPercent = percent;
					this.makeLeftSliderMove();
				}else{
					this.rightPercent = 100-percent;
					this.makeRightSliderMove();
				}

				/*document.ontouchend = BX.proxy(function(event) {
					var percent=((this.countNewLeft(event)*100)/this.trackerWrap.offsetWidth);
					if($(event.target).is(".bx_ui_slider_handle"))
						return;
					if(percent<50){
						this.leftPercent = percent;
						this.makeLeftSliderMove();
					}else{
						this.rightPercent = 100-percent;
						this.makeRightSliderMove();
					}
				}, this);

				document.ontouchend = function() {
					document.ontouchmove = document.touchend = null;
				};*/
			}

			return false;
		};

		return SmartFilter;
	})();

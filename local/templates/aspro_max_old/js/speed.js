var funcDefined = function(func){
	try
	{
		if(typeof func == 'function')
			return true;
		else
			return typeof window[func] === "function";
	}
	catch (e)
	{
		return false;
	}
}

CheckTopMenuDotted = function(){
	var menu = $('nav.mega-menu.sliced');

	if(window.matchMedia('(max-width:991px)').matches)
		return;

	if(menu.length)
	{
		menu.each(function(){
			if($(this).hasClass('initied'))
				return false;
				
			var menuMoreItem = $(this).find('td.js-dropdown');
			if($(this).parents('.collapse').css('display') == 'none'){
				return false;
			}

			var block = $(this).closest('div')[0];
			var block_w = $(this).closest('div')[0].getBoundingClientRect().width;

			//var block_i = $(this).closest('.menu-row__container').find(".menu-row__icons")[0].getBoundingClientRect().width;
			/*var block_w = $(this).closest('.menu-row__container').length
			? $(this).closest('.menu-row__container')[0].getBoundingClientRect().width
			: $(this).closest('div')[0].getBoundingClientRect().width;*/

			var items = $(this).find("tr>.menu-item");
			var menu_w = 0;
			var menuTable = $(this).find('table');
			menuTable.css('width', 'auto');
			items.each(function (i, el) {
			  menu_w += el.getBoundingClientRect().width;
			});
			var afterHide = false;

			while(menu_w > block_w) {
				menuItemOldSave = $(this).find('td').not('.nosave').last();
				if(menuItemOldSave.length){
					menuMoreItem.show();
					var oldClass = menuItemOldSave.attr('class');
					menuItemNewSave = '<li class="menu-item ' + (menuItemOldSave.hasClass('dropdown') ? 'dropdown-submenu ' : '') + (menuItemOldSave.hasClass('active') ? 'active ' : '') + '" data-hidewidth="' + menu_w + '" ' + (oldClass ? 'data-class="' + oldClass + '"' : '') + '>' + menuItemOldSave.find('.wrap').html() + '</li>';
					menuItemOldSave.remove();
					menuMoreItem.find('> .wrap > .dropdown-menu').prepend(menuItemNewSave);
					menu_w = 0;
					items.each(function (i, el) {
					  menu_w += el.getBoundingClientRect().width;
					});
					block_w = block.getBoundingClientRect().width;
					afterHide = true;
				}
				else{
					break;
				}
			}

			if(!afterHide) {
				do {
					var menuItemOldSaveCnt = menuMoreItem.find('.dropdown-menu').find('li').length;
					menuItemOldSave = menuMoreItem.find('.dropdown-menu').find('li').first();
					if(!menuItemOldSave.length) {
						menuMoreItem.hide();
						break;
					}
					else {
						var hideWidth = menuItemOldSave.attr('data-hidewidth');
						if(hideWidth > block_w) {
							break
						}
						else {
							var oldClass = menuItemOldSave.attr('data-class');
							menuItemNewSave = '<td class="' + (oldClass ? oldClass + ' ' : '') + '" data-hidewidth="' + block_w + '"><div class="wrap">' + menuItemOldSave.html() + '</div></td>';
							menuItemOldSave.remove();
							$(menuItemNewSave).insertBefore($(this).find('td.js-dropdown'));
							if(!menuItemOldSaveCnt) {
								menuMoreItem.hide();
								break;
							}
						}
					}
					menu_w = 0;
					items.each(function (i, el) {
					  menu_w += el.getBoundingClientRect().width;
					});
				}
				while(menu_w <= block_w);
			}
			$(this).find('td').css('visibility', 'visible');
			$(this).find('td').removeClass('unvisible');
			$(this).addClass('ovisible');
			$(this).addClass('initied');
			menuTable.css('width', '');
		})
	}
	return false;
}

if(!funcDefined("InitTopestMenuGummi")){
	InitTopestMenuGummi = function(){
		if(!window.isOnceInited){
			function _init(){
				var arItems = $menuTopest.find('>li:not(.more)');
				var cntItems = arItems.length;
				if(cntItems){
					var itemsWidth = 0;
					for(var i = 0; i < cntItems; ++i){
						var item = arItems.eq(i);
						if(item[0].getBoundingClientRect().width){
							var itemWidth = item[0].getBoundingClientRect().width + parseInt(item.css("margin-left"));
							arItemsHideWidth[i] = (itemsWidth += itemWidth) + (i !== (cntItems - 1) ? moreWidth : 0);
						}
					}
				}
			}
			
			function _gummi(){
				if(window.matchMedia('(max-width:991px)').matches)
					return;

				var $parent  = $menuTopest.parent();
				var bInFlex  = $parent.css('display') === 'flex';
				var block    = $parent.closest('.header__top-item');
				var useFlex1 = block.hasClass("dotted-flex-1");

				if(useFlex1){
					block.addClass('flex1');
				}

				if(bInFlex){
					var siblingsWidth = 0;
					var $siblings = $menuTopest.siblings().filter('div');
					for(var i = 0, cnt = $siblings.length; i < cnt; ++i){
						siblingsWidth += $siblings.eq(i)[0].getBoundingClientRect().width;
					}
					var parentWidth = $parent[0].getBoundingClientRect().width;
					var rowWidth = parentWidth - siblingsWidth;
				}
				else{
					var marginMenuSumm = parseInt(block.css("margin-left")) + parseInt(block.css("margin-right"));
					var rowWidth = $menuTopest[0].getBoundingClientRect().width - (moreWidth + marginMenuSumm);
				}

				var arItems = $menuTopest.find('>li:not(.more),li.more>.dropdown>li');
				var cntItems = arItems.length;
				if(cntItems){
					var bMore = false;
					for(var i = cntItems - 1; i >= 0; --i){
						var item = arItems.eq(i);
						var bInMore = item.parents('.more').length > 0;
						if(!bInMore){
							if(arItemsHideWidth[i] > rowWidth){
								if(!bMore){
									bMore = true;
									more.removeClass('hidden');
								}
								var clone = item.clone();
								clone.find('>a').addClass('dark_font');
								clone.prependTo(moreDropdown);
								item.addClass('hidden cloned');
								rowWidth = $menuTopest[0].getBoundingClientRect().width - moreWidth;
							}
						}
					}
					for(var i = 0; i < cntItems; ++i){
						var item = arItems.eq(i);
						var bInMore = item.parents('.more').length > 0;
						if(bInMore){
							if(arItemsHideWidth[i] <= rowWidth){
								if(i === (cntItems - 1)){
									bMore = false;
									more.addClass('hidden');
								}
								var clone = item.clone();
								clone.find('>a').removeClass('dark_font');
								clone.insertBefore(more);
								item.addClass('cloned');
							}
						}
					}
					$menuTopest.find('li.cloned').remove();
				}
				if(useFlex1){
					block.removeClass("flex1");
				}
			}

			var $menuTopest = $('.menu.topest');
			if($menuTopest.length)
			{
				try {
					var more = $menuTopest.find('>.more');
					var moreDropdown = more.find('>.dropdown');
					var moreWidth = more[0].getBoundingClientRect().width + parseInt(more.css("margin-left"));
					var arItemsHideWidth = [];

					ignoreResize.push(true);
					_init();
					_gummi();
					ignoreResize.pop();

					BX.addCustomEvent('onWindowResize', function(eventdata) {
						try{
							ignoreResize.push(true);
							_init()
							_gummi();
							$menuTopest.addClass('initied');
							$menuTopest.parent().addClass('initied');
						}
						catch(e){}
						finally{
							ignoreResize.pop();
						}
					});
				}catch(e){}
			}
		}
	}
}

if(!funcDefined("InitTopMenuGummi")){
	InitTopMenuGummi = function(){
		function _init(){
			var arItems = $topMenu.closest('.wrap_menu').find('.inc_menu .menu_top_block >li:not(.more)');
			var cntItems = arItems.length;
			if(cntItems){
				var itemsWidth = 0;
				for(var i = 0; i < cntItems; ++i){
					var item = arItems.eq(i);
						var itemWidth = item.actual('outerWidth');
						arItemsHideWidth[i] = (itemsWidth += itemWidth) + (i !== (cntItems - 1) ? moreWidth : 0);
				}
			}

		}

		function _gummi(){
			var rowWidth = $wrapMenu.actual('innerWidth') - $wrapMenuLeft.actual('innerWidth');
			var arItems = $topMenu.find('>li:not(.more):not(.catalog),li.more>.dropdown>li');
			var cntItems = arItems.length;

			if(cntItems){
				var bMore = false;
				for(var i = cntItems - 1; i >= 0; --i){
					var item = arItems.eq(i);
					var bInMore = item.parents('.more').length > 0;
					if(!bInMore){
						if(arItemsHideWidth[i] > rowWidth){
							if(!bMore){
								bMore = true;
								more.removeClass('hidden');
							}
							var clone = item.clone();
							clone.find('>.dropdown').removeAttr('style').removeClass('toleft');
							clone.find('>a').addClass('dark_font').removeAttr('style');
							clone.prependTo(moreDropdown);
							item.addClass('cloned');
						}
					}
				}
				for(var i = 0; i < cntItems; ++i){
					var item = arItems.eq(i);
					var bInMore = item.parents('.more').length > 0;
					if(bInMore){
						if(arItemsHideWidth[i] <= rowWidth){
							if(i === (cntItems - 1)){
								bMore = false;
								more.addClass('hidden');
							}
							var clone = item.clone();
							clone.find('>a').removeClass('dark_font');
							clone.insertBefore(more);
							item.addClass('cloned');
						}
					}
				}
				$topMenu.find('li.cloned').remove();

				var cntItemsVisible = $topMenu.find('>li:not(.more):not(.catalog)').length;
				var o = rowWidth - arItemsHideWidth[cntItemsVisible - 1];
				var itemsPaddingAdd = Math.floor(o / (cntItemsVisible + (more.hasClass('hidden') ? 0 : 1)));
				var itemsPadding_new = itemsPadding_min + itemsPaddingAdd;
				var itemsPadding_new_l = Math.floor(itemsPadding_new / 2);
				var itemsPadding_new_r = itemsPadding_new - itemsPadding_new_l;

				$topMenu.find('>li:not(.catalog):visible>a').each(function(){
					$(this).css({'padding-left': itemsPadding_new_l + 'px'});
					$(this).css({'padding-right': itemsPadding_new_r + 'px'});
				});

				var lastItemPadding_new = itemsPadding_new + o - (cntItemsVisible + (more.is(':visible') ? 1 : 0)) * itemsPaddingAdd;
				var lastItemPadding_new_l = Math.floor(lastItemPadding_new / 2);
				var lastItemPadding_new_r = lastItemPadding_new - lastItemPadding_new_l;
				$topMenu.find('>li:visible').last().find('>a').css({'padding-left': lastItemPadding_new_l + 'px'});
				$topMenu.find('>li:visible').last().find('>a').css({'padding-right': lastItemPadding_new_r + 'px'});
			}
			CheckTopMenuFullCatalogSubmenu();
		}

		var $topMenu = $('.menu_top_block');
		if($menuTopest.length)
		{
			var $wrapMenu = $topMenu.parents('.wrap_menu');
			var $wrapMenuLeft = $wrapMenu.find('.catalog_menu_ext');
			var more = $topMenu.find('>.more');
			var moreWidth = more.actual('outerWidth',{includeMargin: true});
			more.addClass('hidden');
			var arItemsHideWidth = [];
			var moreDropdown = more.find('>.dropdown');
			var itemsPadding = parseInt(more.find('>a').css('padding-left')) * 2;
			var itemsPadding_min = itemsPadding;


			setTimeout(function(){
				ignoreResize.push(true);
				_init();
				_gummi();
				ignoreResize.pop();
			}, 5000);

			BX.addCustomEvent('onWindowResize', function(eventdata) {
				try{
					ignoreResize.push(true);
					_gummi();
				}
				catch(e){}
				finally{
					ignoreResize.pop();
				}
			});

			/*BX.addCustomEvent('onTopPanelFixUnfix', function(eventdata) {
				ignoreResize.push(true);
				_gummi();
				ignoreResize.pop();
			});*/
		}
	}
}
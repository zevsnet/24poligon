$(document).ready(function(){
	BX.loadScript(
		[
		  arAsproOptions["SITE_TEMPLATE_PATH"] + "/css/jquery.mCustomScrollbar.min.css",
		  arAsproOptions["SITE_TEMPLATE_PATH"] + "/js/jquery.mousewheel.min.js",
		  arAsproOptions["SITE_TEMPLATE_PATH"] + "/js/jquery.mCustomScrollbar.js",
		],
		function () {
			$('.sidebar_menu .sidebar_menu_inner .menu-wrapper').mCustomScrollbar({
				mouseWheel: {
					scrollAmount: 150,
					preventDefault: true
				}
			})
		
			$('.sidebar_menu .sidebar_menu_inner .menu-wrapper .menu_top_block .menu > li.v_hover > .dropdown-block > .dropdown-inner').mCustomScrollbar({
				mouseWheel: {
					scrollAmount: 150,
					preventDefault: true
				}
			})
		}
	  );
	

	$(document).on('mouseenter', '.menu-wrapper .menu_top_block .menu > li.v_hover', function () {
		var _this = $(this),
			menu = _this.find('> .dropdown-block'),
			block2 = _this.find('.dropdown-block .dropdown-inner'),
			winHeight = $(window).height();

		block2.css('max-height', 'none');
		menu.find('.mCustomScrollBox').css('max-height', 'none');
		var pos = BX.pos(menu[0], true);
		if(pos.height)
		{
			if(_this.hasClass('m_line'))
			{
				if((winHeight - pos.top) < 200)
				{
					if(winHeight < pos.bottom)
					{
						menu.removeAttr('style');
						// block.css('margin-top',  "-" + (pos.bottom - winHeight) + "px");
						menu.css('margin-top',  "-" + (pos.bottom - winHeight + _this.height()) + "px");
						pos = BX.pos(_this.find('.dropdown-block')[0], true);
					}
				}
			}
		}
		else
		{
			block2.css('max-height', 'none');
		}

		$('body').addClass('menu-hovered');
		/*menu.velocity('stop').velocity({opacity: '1', display: 'block'}, {
			duration: 170,
			delay: 200,
			begin: function() {
				var headerFixed = $('#headerfixed.fixed');
				var fixedHeight = 0;
				var marginTop = Number.parseInt(menu.css('margin-top'));
				if(headerFixed.length) {
					fixedHeight = headerFixed[0].getBoundingClientRect().height;
				}
				var position = menu[0].getBoundingClientRect();
				if(position.y < fixedHeight) {
					menu.css('top', -position.y + fixedHeight - marginTop);
				}
				position = menu[0].getBoundingClientRect();
				if(position.bottom > winHeight) {
					menu.css('bottom', '0');
					if(position.height > winHeight - fixedHeight) {
						menu.css('top', fixedHeight - marginTop);
					}
				}
				var menuHeight = menu[0].getBoundingClientRect().height - menu.find('> .title')[0].getBoundingClientRect().height;
				if(block2[0].getBoundingClientRect().height > menuHeight) {
					block2.css('max-height', menuHeight);
				}
			},
			complete: function(){
				$('body').addClass('menu-hovered');

				if(!$('.shadow-block').length)
					$('<div class="shadow-block"></div>').appendTo($('body'));
				$('.shadow-block').velocity('stop').velocity('fadeIn', 200);
			}
		});*/

		_this.one('mouseleave', function () {
			$('body').removeClass('menu-hovered');
			/*menu.velocity('stop').velocity({opacity: '0'}, {
				duration: 100,
				delay: 10,
				complete: function(){
					menu.css('top', '');
					menu.css('bottom', '');
					menu.css('margin-top', '');
					$('.shadow-block').velocity('stop').velocity('fadeOut', {
						duration: 200,
						// delay: 100,
						complete: function(){
							$('body').removeClass('menu-hovered');
						}
					});
				}
			});*/
		});
	});
})
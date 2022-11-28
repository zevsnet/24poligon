$(document).ready(function(){
	$(document).on('click', ".scroll_btn", function(){
		scroll_block($('.ordered-block.goods_catalog'));
	});
    
	if($('.content-image').length && arMaxOptions['THEME']['STICKY_SIDEBAR'] != 'N')
	{
		window['sidebarImage']  = new StickySidebar('.content-image', {
			containerSelector: '.inner_wrapper_text',//'.detail_content_wrapper',
			innerWrapperSelector: '.sidebar__inner',
			topSpacing: 60,
			bottomSpacing: 20,
			resizeSensor: true,
		});

		if($('.detail_content_wrapper .content-image img').length)
		{
			$('.detail_content_wrapper .content-image img').load(function(){
				if(typeof window['sidebarImage'] !== 'undefined')
				{
					window['sidebarImage'].updateSticky();
				}
			})
		}
	}
	
	
	
	var menu_color = $('.banners-content .maxwidth-banner').data('text_color');


	if($('.wrapper1.long_banner_contents').length){
		if( menu_color == 'light')
		{
			$('.header_wrap').addClass('light-menu-color');
			if(arMaxOptions['THEME']['LOGO_IMAGE_LIGHT'] && $('.header_wrap .logo_and_menu-row .logo img').length)
			{
				$('.header_wrap .logo_and_menu-row .logo img').attr('src', arMaxOptions['THEME']['LOGO_IMAGE_LIGHT']);
			}
		}
		else
		{
			$('.header_wrap').removeClass('light-menu-color');
			if(arMaxOptions['THEME']['LOGO_IMAGE_LIGHT'] && $('.header_wrap .logo_and_menu-row .logo img').length)
			{
				$('.header_wrap .logo_and_menu-row .logo img').attr('src', arMaxOptions['THEME']['LOGO_IMAGE']);
			}
		}
	}
    
	$('.brands.owl-carousel.brands_slider .bordered  img.lazy').load(function(){
		$(window).resize();
	});
});


BX.addCustomEvent('onWindowResize', function(eventdata){
	try{
		if(typeof window['sidebarImage'] !== 'undefined')
		{
			if($('.wrapper1.with_left_block').length){
				if(window.matchMedia('(max-width: 1199px)').matches)
				{
					window['sidebarImage'].destroy();
				}
				else
				{
					window['sidebarImage'].bindEvents();
				}
			} else{
				if(window.matchMedia('(max-width: 991px)').matches)
				{
					window['sidebarImage'].destroy();
				}
				else
				{
					window['sidebarImage'].bindEvents();
				}
			}
			
		}
	}
	catch(e){}
	
});


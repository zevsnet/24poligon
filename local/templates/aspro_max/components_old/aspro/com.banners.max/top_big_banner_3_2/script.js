function checkNavColor(slider){
	var nav_color_flex = slider.find('.flex-active-slide').data('nav_color'),
		menu_color = slider.find('.flex-active-slide').data('text_color');
	if(nav_color_flex == 'dark')
		slider.find('.flex-control-nav').addClass('flex-dark');
	else
		slider.find('.flex-control-nav').removeClass('flex-dark');

	var eventdata = {slider: slider};
	BX.onCustomEvent('onSlide', [eventdata]);
}
function checkHeight(){}
$(document).ready(function(){
	if($('.top_slider_wrapp .flexslider').length){
		var config = {"controlNav": true, "animationLoop": true, "pauseOnHover" : true, "simulateTouch" : true, 'autoHeight': true};
		if(typeof(arMaxOptions['THEME']) != 'undefined'){
			var slideshowSpeed = Math.abs(parseInt(arMaxOptions['THEME']['BIGBANNER_SLIDESSHOWSPEED']));
			var animationSpeed = Math.abs(parseInt(arMaxOptions['THEME']['BIGBANNER_ANIMATIONSPEED']));
			var currentBannerIndex = 0;
			config["slideshow"] = (slideshowSpeed && arMaxOptions['THEME']['BIGBANNER_ANIMATIONTYPE'].length ? true : false);
			config["animation"] = (arMaxOptions['THEME']['BIGBANNER_ANIMATIONTYPE'] === 'FADE' ? 'fade' : 'slide');
			if(animationSpeed >= 0){
				config["animationSpeed"] = animationSpeed;
			}
			if(slideshowSpeed >= 0){
				config["slideshowSpeed"] = slideshowSpeed;
			}
			if(arMaxOptions['THEME']['BIGBANNER_ANIMATIONTYPE'] !== 'FADE'){
				config["direction"] = (arMaxOptions['THEME']['BIGBANNER_ANIMATIONTYPE'] === 'SLIDE_VERTICAL' ? 'vertical' : 'horizontal');
			}
			if($('.top_slider_wrapp .flexslider').find('ul.slides li.box').length < 2) {
				config['animationLoop'] = false;
			}

			if ('CURRENT_BANNER_INDEX' in arMaxOptions && arMaxOptions['CURRENT_BANNER_INDEX']) {
				currentBannerIndex = arMaxOptions['CURRENT_BANNER_INDEX'] - 1;
				if (currentBannerIndex < $('.top_slider_wrapp .flexslider .slides > li').length) {
					config['startAt'] = currentBannerIndex;
					config["slideshow"] = false;
				}
			}
			
			config.start = function(slider){
				checkNavColor(slider);
				var activeVideo = slider.find('.box.wvideo.flex-active-slide');
				if(activeVideo.length) {
					if(activeVideo.data('video_autoplay') == '1') {
						BX.onCustomEvent('onSlide', [{slider: slider}] );
					}
				}
				if(slider.count <= 1){
					slider.find('.flex-direction-nav li').addClass('flex-disabled');
				}
				$(slider).find('.flex-control-nav').css('opacity',1);
			}
			config.after = function(slider){
				// checkNavColor(slider);
				InitLazyLoad();
			}
			config.before = function(slider){
				setTimeout(function(){
					checkNavColor(slider);
					InitLazyLoad();
				}, 100);
			}
		}

		$(".top_slider_wrapp .flexslider").appear(function(){
			var $this = $(this);
			$this.flexslider(config);
		}, {accX: 0, accY: 150})
	}
	
	checkHeight();

	BX.addCustomEvent('onWindowResize', function(eventdata){
		try{
			ignoreResize.push(true);
			checkHeight();
			CoverPlayerHtml()
		}
		catch(e){}
		finally{
			ignoreResize.pop();
		}
	})
});
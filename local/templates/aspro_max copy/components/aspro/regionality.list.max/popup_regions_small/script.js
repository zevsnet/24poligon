$(document).ready(function(){
	$('.confirm_region .aprove').on('click', function(e){
		var _this = $(this);
		$.removeCookie('current_region');
		$.cookie('current_region', _this.data('id'), {path: '/',domain: arMaxOptions['SITE_ADDRESS']});

		$('.confirm_region').remove();
		if(typeof _this.data('href') !== 'undefined')
			location.href = _this.data('href');
	})

	$(document).on('click', '.confirm_region .close', function(){
		var _this = $(this);
		$.removeCookie('current_region');
		$.cookie('current_region', _this.data('id'), {path: '/',domain: arMaxOptions['SITE_ADDRESS']});
		$('.confirm_region').remove();
		$('.top_mobile_region .confirm_wrapper').remove();
	});
	
	$('.js_city_change').on('click', function(){
		var _this = $(this);
		_this.closest('.region_wrapper').find('.js_city_chooser').trigger('click');
		if(_this.closest('.top_mobile_region').length)
		{
			$('.burger').click();

			$('.mobile_regions > ul > li > a').click()
		}
		$('.confirm_region').remove();
	})
	$('.js_city_chooser').on('click', function(){
		var _this = $(this);
		$('.confirm_region').remove();
	})
});
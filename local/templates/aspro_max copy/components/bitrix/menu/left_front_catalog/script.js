$(document).ready(function(){
	CheckTopMenuFullCatalogSubmenu();

	$(document).on('mouseenter', '.menu_top_block .has-child.v_hover', function () {
		var _this = $(this),
			menu = _this.find('> .dropdown');

		menu.velocity('stop');

		if(menu.css('opacity') != 0)
		{
			menu.css('opacity' , '1');
		}
		else
		{
			menu.velocity('transition.fadeIn', {
				duration: 300,
				delay: 400
			});
		}

		_this.one('mouseleave', function () {

			menu.velocity('stop').velocity('transition.fadeOut', {
				duration: 150,
				delay: 300
			});
		});
	});
})
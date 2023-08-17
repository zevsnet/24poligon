$(document).ready(function(){
	$('.menu-row .mega-menu table td.wide_menu .customScrollbar').mCustomScrollbarDeferred({
		mouseWheel: {
			scrollAmount: 150,
			preventDefault: true
		}
	})
})
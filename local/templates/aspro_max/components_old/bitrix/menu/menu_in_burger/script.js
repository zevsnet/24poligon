$(document).ready(function(){
	$('.menu-row .mega-menu table td.wide_menu .dropdown-menu > .customScrollbar').mCustomScrollbar({
		mouseWheel: {
			scrollAmount: 150,
			preventDefault: true
		}
	})
})
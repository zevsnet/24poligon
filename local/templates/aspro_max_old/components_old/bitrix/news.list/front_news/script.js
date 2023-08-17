$(document).ready(function(){
	if($('.item-views.news2.half-block .item-wrapper.line_img .half-wrapper').length)
	{
		
		var scrollItemsType6 = function(){
			if(window.matchMedia('(min-width: 601px)').matches) {
				$('.item-views.news2.half-block .item-wrapper.line_img .half-wrapper').removeClass('destroyed').mCustomScrollbarDeferred({
					mouseWheel: {
						scrollAmount: 150,
						preventDefault: true
					}
				})
			} else {
				$('.item-views.news2.half-block .item-wrapper.line_img .half-wrapper').addClass('destroyed').mCustomScrollbar('destroy')
			}
		}

		// $(window).resize(throttle(scrollItemsType6, 200))
		$(window).resize(debounce(scrollItemsType6, 200))
	}

	var containerEl = document.querySelector('.mixitup-container');
	if(containerEl)
	{
		var config = {
			selectors:{
				target: '[data-ref="mixitup-target"]'
			},
			animation:{
				effects: 'fade scale stagger(50ms)' // Set a 'stagger' effect for the loading animation
			},
			load:{
				filter: 'none' // Ensure all targets start from hidden (i.e. display: none;)
			},
			animation:{
				duration: 350
			},
			controls:{
				scope: 'local'
			},
			callbacks: {
				onMixStart:function(state) {
				},
				onMixEnd:function() {
					InitLazyLoad();
				}
			}
		};
		var mixer = mixitup(containerEl, config);

		// Add a class to the container to remove 'visibility: hidden;' from targets. This
	    // prevents any flickr of content before the page's JavaScript has loaded.

	    containerEl.classList.add('mixitup-ready');

	    // Show all targets in the container

	    mixer.show()
		.then(function(){
			// Remove the stagger effect for any subsequent operations
			mixer.configure({
				animation: {
					effects: 'fade scale'
				}
			});
		});
	}

})
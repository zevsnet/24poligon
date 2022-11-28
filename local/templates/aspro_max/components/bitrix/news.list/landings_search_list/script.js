$(document).ready(function(){
	$(document).on('click', '.landings-list__item--js-more', function(){
		if($(this).closest('.from_smartseo').length){
			return;
		}

		var $this = $(this),
			block = $this.find('> span'),
			dataOpened = $this.data('opened'),
			thisText = block.text()
			dataText = block.data('text'),
			showCount = $this.data('visible')
			item = $this.closest('.landings-list__info').find('.landings-list__item.hidden').get();

		var items = item.filter(function(item1, index){
			return ++index <= showCount
		})
		if (items) {
			items.forEach(function(item, index){
				$(item).removeClass('hidden');
			})
		}
		if (!item.length) {
			$this.closest('.landings-list__info').find('.landings-list__item.js-hidden').addClass('hidden');
			block.data('text', thisText).text(dataText);
			$this.removeClass('opened').data('opened', 'N');
		} else if (item.length <= showCount) {
			block.data('text', thisText).text(dataText);
			$this.addClass('opened').data('opened', 'Y');
		}
	});

	$(document).on('click', '.landings-list__clear-filter', function(){
		if($(this).closest('.from_smartseo').length){
			return;
		}

		$('.bx_filter_search_reset').trigger('click');
	})
});
$(document).ready(function(){
	$(document).on('click', '.block_container .items .item.initied', function(){
		var _this = $(this),
			itemID = _this.data('id'),
			animationTime = 200;

		_this.closest('.block_container').find('.top_block').fadeOut(animationTime);
		_this.closest('.items').fadeOut(animationTime, function(){
			_this.closest('.block_container').find('.detail_items').fadeIn(animationTime);
			_this.closest('.block_container').find('.detail_items .item[data-id='+itemID+']').fadeIn(animationTime);

			var arCoordinates = _this.data('coordinates').split(',');

			if(typeof map !== undefined)
				map.setCenter([arCoordinates[0], arCoordinates[1]], 15);
		});
	});

	$(document).on('click', '.block_container .top-close', function(){
		var _this = $(this).closest('.block_container').find('.detail_items .item:visible'),
			animationTime = 200;
		_this.fadeOut(animationTime);
		_this.closest('.block_container').find('.detail_items').fadeOut(animationTime, function(){
			_this.closest('.block_container').find('.items').fadeIn(animationTime);
			_this.closest('.block_container').find('.top_block').fadeIn(animationTime);

			if(typeof map !== undefined && typeof clusterer !== undefined)
			{
				map.setBounds(clusterer.getBounds(), {
					zoomMargin: 40,
					// checkZoomRange: true
				});
			}
		});
	});
})
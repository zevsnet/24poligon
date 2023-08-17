var basketTimeout;
var totalSum;

function delete_all_items(type){
	var index=(type=="delay" ? "2" : "1");
	if(type == "na")
		index = 4;
	$.post( arMaxOptions['SITE_DIR']+'ajax/showBasketHover.php', 'PARAMS='+$("#basket_form").find("input#fly_basket_params").val()+'&TYPE='+index+'&CLEAR_ALL=Y', $.proxy(function( data ) {
		basketTop('reload');

		$('.in-cart').hide();
		$('.in-cart').each(function(){
			if ($(this).closest('.counter_wrapp').find('.counter_block').length) {
				$(this).closest('.button_block').removeClass('wide')
			}
		})
		$('.to-cart').show();
		$('.to-cart').removeClass("clicked");
		$('.counter_block').closest('.counter_block_inner').show();
		$('.counter_block').show();
		$('.wish_item.added').hide();
		$('.wish_item:not(.added)').show();
		$('.wish_item.to').show();
		$('.wish_item.in').hide();
		$('.banner_buttons.with_actions .wraps_buttons .basket_item_add').removeClass('added');
		$('.banner_buttons.with_actions .wraps_buttons .wish_item_add').removeClass('added');

		var eventdata = {action:'loadBasket'};
		BX.onCustomEvent('onCompleteAction', [eventdata]);

	}));
}

function deleteProduct(basketId, itemSection, item, th){
	function _deleteProduct(basketId, itemSection, product_id){
		arStatusBasketAspro = {};
		//$.post( arMaxOptions['SITE_DIR']+'ajax/item.php', 'delete_item=Y&item='+product_id, $.proxy(function(){
		$.post( arMaxOptions['SITE_DIR']+'ajax/item.php', 'product_id='+ product_id +'&delete_basket_id='+basketId, $.proxy(function(){
			basketTop('reload');
			$('.to-cart[data-item='+product_id+']').removeClass("clicked");
			reloadBasketCounters();
		}));
	}
	var product_id=th.attr("product-id");
	if(checkCounters()){
		delFromBasketCounter(item);
		setTimeout(function(){
			_deleteProduct(basketId, itemSection, product_id);
		}, 100);
	}
	else{
		_deleteProduct(basketId, itemSection, product_id);
	}
}
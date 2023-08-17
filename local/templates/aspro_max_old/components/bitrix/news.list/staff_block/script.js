/*function TemplateScript(){
	$('.item-views.staff1 .item .post').sliceHeight();
	$('.item-views.staff1 .item .title').sliceHeight();
	$('.item-views.staff1 .item>.wrap').sliceHeight();
	
	//$('.item-views.services-items.type_2 .item').each(function(){
	//	var itemID = $(this).data('id');
	//	
	//	window['hoverItem'+itemID] = false;
	//});	
	
	$('.item-views.staff1 .items .item').hover(function(){
		var block = $(this).find('.bottom-block'),
			itemID = $(this).closest('.item').data('id');

		clearTimeout(window['hoverItem'+itemID]);
		block.show();
		var blockHeight = block.outerHeight(true, true) - 1;
		
		block.closest('.body-info').css('margin-top', -blockHeight);
	},
	function(){
		var block = $(this).find('.bottom-block'),
			itemID = $(this).closest('.item').data('id');
		
		block.closest('.body-info').css('margin-top', '0');
		block.css('opaity', 0);
		window['hoverItem'+itemID] = setTimeout(function(){
			block.hide();
		}, 200);
	});
}*/
function SetMaxHeightsForStaff(){
	window['maxTopHeight'] = $('.item-views.staff1 .top-block-wrapper').getMaxHeights(true) + parseInt($('.item-views.staff1 .item .body-info').css('padding-top'));
	$('.item-views.staff1 .item > .wrap').css('padding-bottom',window['maxTopHeight']);
	$('.item-views.staff1 .item > .wrap .body-info').css('height',window['maxTopHeight']);
	$('.item-views.staff1 .item:hover');
	var hoveredItem = $('.item-views.staff1 .item:hover');
	$(hoveredItem).trigger('mouseenter');
}


$(document).ready(function(){
    
	
	
	$(window).on("resize", SetMaxHeightsForStaff);	
    
	SetMaxHeightsForStaff();
	InitScrollBar($('.staff-srollbar-custom'));
	//var newHeight = $('.top-block-wrapper').outerHeight()+$('.middle-props.bottom-block').outerHeight();
	$('.item-views.staff1 .items .item').hover(function(){
		var bodyInfo = $(this).find('.body-info');
		//console.log(bodyInfo.css('padding-bottom'));
		var bodyInfoPadding = parseInt(bodyInfo.css('padding-top')) + parseInt(bodyInfo.css('padding-bottom'));
		var newHeight = $(this).find('.top-block-wrapper').outerHeight() + $(this).find('.middle-props.bottom-block').outerHeight() + bodyInfoPadding;
		bodyInfo.css('height', newHeight);
		
		var itemID = $(this).closest('.item').data('id');
		var itemScroll = $(this).closest('.item').find('.mCSB_scrollTools_vertical');
		
		window['hoverItem'+itemID] = setTimeout(function(){
			itemScroll.css('width','5px');
		}, 350);

		/*clearTimeout(window['hoverItem'+itemID]);
		block.show();
		var blockHeight = block.outerHeight(true, true) - 1;
		
		block.closest('.body-info').css('margin-top', -blockHeight);*/
	},
	function(){
		var itemID = $(this).closest('.item').data('id');
		//var bodyInfo = $(this).find('.body-info').css('height', '30%');
		var bodyInfo = $(this).find('.body-info').css('height', window['maxTopHeight']);
		
		var itemScroll = $(this).closest('.item').find('.mCSB_scrollTools_vertical');
		//console.log(itemScroll);
		itemScroll.css('width','0');
		clearTimeout(window['hoverItem'+itemID]);
		/*var block = $(this).find('.bottom-block'),
			itemID = $(this).closest('.item').data('id');
		
		block.closest('.body-info').css('margin-top', '0');
		block.css('opaity', 0);
		window['hoverItem'+itemID] = setTimeout(function(){
			block.hide();
		}, 200);*/
	});
	
	/*TemplateScript();
	BX.addCustomEvent('onCompleteActionComponent', function(eventdata, _this){
		setTimeout(function(){
			TemplateScript();
		}, 50);
	});*/
});
$(document).ready(function(){
	BX.addCustomEvent('onCompleteAction',function (params) {
		if(typeof params.action != 'undefined' && params.action=='loadForm' && $('#popup_iframe_wrapper #one_click_buy_form').length>0){
			$('#popup_iframe_wrapper  .pop-up-title').html('Оформление заказа');
			$('#popup_iframe_wrapper #one_click_buy_form #one_click_buy_form_button span').html('Оформить');
			if($('#one_click_buy_id_EMAIL').val()==''){
				$('#one_click_buy_id_EMAIL').val('oneclickbuy@24poligon.ru');
			}	
			
			$('#one_click_buy_form .form-control.bg:nth-child(3)').hide();
			
		}
		
	});	
	if($('.personal-link.logined').length>0){
		$('.banner.FOOTER').hide();
		$('.banner.TOP_HEADER').hide();
	}
});

var CIdexSeo = {
	loading: false,
	bxDialog: false,
	postUrl: "/bitrix/js/idex.seo/ajax.php",
	ShowDialog: function(){
		if(!CIdexSeo.bxDialog) {
			CIdexSeo.CreateDialog();
		}

		CIdexSeo.ShowLoader();		
		$.post(CIdexSeo.postUrl, {url: window.location.href}, function(data){
			CIdexSeo.HideLoader();
			if(data.status == 'OK') {
				$('#idex_seo_block').html(data.html);
			} else {
				 CIdexSeo.Notify('Ошибка работы модуля');
			}
		}, 'json').fail(function(){CIdexSeo.HideLoader('Ошибка работы модуля');});
		CIdexSeo.bxDialog.Show();
		
		$('#idex_seo_notify').appendTo('.bx-core-dialog .dialog-foot');
		
	},
	CreateDialog: function() {
		
		this.bxDialog = new BX.CDialog({  // Всплывающее битриксовское окно
			title: "Редактирование SEO параметров",
			content: "<div id='idex_seo_block'></div><span id='idex_seo_notify' style='position: absolute; max-width: 500px; top: -30px; height: 20px; padding-left: 10px;'></span>",
			icon: 'head-block', 
			resizable: true, //возможность изменения размера
			draggable: true,//возможность перетаскивания
			height: '640',//высота
			width: '700'//ширина
		});
		
		this.bxDialog.SetButtons([//добавление кнопок для окна
			{
				'title': 'Сохранить',
				'action': function(){		
					if(CIdexSeo.loading) { CIdexSeo.Notify('Подождите окончания загрузки'); return; }
					CIdexSeo.ShowLoader();
					
					obButton = this;
					$('#idex_seo_action').val('save');
					$.ajax({ 
						type: "POST", 
						url: CIdexSeo.postUrl, 
						data: $('#idex_seo_form').serialize(), 
						dataType:'json',
						success: function(data) { 							
							CIdexSeo.HideLoader();
							if(data.status == 'OK') {
								obButton.parentWindow.Close();
								alert('Данные сохранены');
								window.location.reload(true);
							}
						},
						error: function(data){
							CIdexSeo.HideLoader('Ошибка сохранения, попробуйте еще раз');							
						}
					});		
				}
			},{
				'title': 'Закрыть окно',
				'action': function(){
					this.parentWindow.Close(); 
				}
			}
		]);		
				
		
	},
	Delete: function(id) {
		if(confirm('Удалить записи для данного адреса?')) {
			CIdexSeo.ShowLoader();
			$.post(CIdexSeo.postUrl, {id: id, action: 'delete'}, function(data){
				CIdexSeo.HideLoader();
				if(data.status == 'OK') {
					CIdexSeo.bxDialog.Close();
					alert('Данные удалены');
				} else {
					 CIdexSeo.Notify('Ошибка работы модуля');
				}
			}, 'json').error(function(){CIdexSeo.HideLoader('Ошибка работы модуля');});
		}
	},
	ShowLoader: function(){
		CIdexSeo.Notify('<span class="idex_seo_loader" style="padding-left: 20px;">Загрузка...</span>');
		CIdexSeo.loading = true;
	},
	HideLoader: function(text){
		if(typeof(text) == 'undefined') {
			text = '';
		}
		CIdexSeo.Notify(text);
		CIdexSeo.loading = false;
	},
	Notify: function(text) {
		$('#idex_seo_notify').html(text);
	},	
	AddHtmlRow: function(){
		lastInput = $('tr.html_id_row:last');
		_input = $(lastInput).clone();
		newHtml = _input.html().replace(/(HTML_BLOCK_ID\[|HTML_BLOCK_TEXT\[)([0-9]+)/gim, function(match, p1, p2) {   
			p2 = parseInt(p2) + 1;
			return p1 + p2;
		});
		_input.html(newHtml);
		_input.find('input, textarea').val('');
		_input.insertAfter(lastInput);
	}	
}
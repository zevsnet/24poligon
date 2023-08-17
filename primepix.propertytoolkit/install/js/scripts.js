$(function() {

	var PXPT_AJAX_HANDLER = '/bitrix/admin/ppt_actions.php',
		PROPS_TYPE = $('.-type-select').data('type'),
		IBLOCK_ID  = $('.-select-form').find('select[name="iblock"]').find('option:selected').val(),
		IS_FOOTER  = $('.-footer-panel').length,
		LOCATION   = $('.-type-select').find('option:selected').data('url'),
		$FORM      = $('.properties-form'),
		$CONTAINER = $('.-toolkit-list-container'),
		DIALOG     = null;

	function removeRows(propsIDs) {

		$.each(propsIDs, function(index) {
			var id = propsIDs[index];
			$FORM
				.find('#' + id)
				.remove();
		});
	}

	function mergePropsRows(props, leading, valsCount) {

		var $leading = $FORM.find('#' + leading),
			vqty     = 0,
			pqty     = 0;

		$.each(props, function() {

			var $prop = $FORM.find('#' + this),
				$vqty = $prop.find('.vqty'),
				$pqty = $prop.find('.pqty');

			vqty += parseInt( $vqty.text() );
			pqty += parseInt( $pqty.text() );

			if (this != leading) {
				$prop.remove();
			}
			
		});

		if (typeof(valsCount) != 'undefined') {
			vqty = valsCount;
		}

		$leading.find('.vqty').text(vqty);
		$leading.find('.pqty').text(pqty);

		selectRow($leading, false);
		$('.check-td').add('.radio-td').find('input').prop('checked', false);
		showMergeLink(false)
	}

	function mergeValuesRows(props, leading) {

		var $leading  = $FORM.find('#' + leading),
			sList     = $.map(props, function(val) {
				return '#' + val;
			}),
			lines2del = $( sList.join(',') ).not($leading),
			set       = {};

		lines2del.add($leading).find('.product')
			.each(function() {
				set[ $(this).data().id ] = this;
			});
		lines2del.remove();

		// set contains unique products
		var html2Insert = $.map(set, function(el) {
			return $('<div>').append($(el).clone()).html();
		}).join(', ');

		$FORM.find('.check-td, .radio-td').find('input').prop('checked', false);
		$leading.find('.products').html(html2Insert);

		selectRow($leading, false);
		showMergeLink(false)

	}

	function inProgress(state) {

		var $footerItems   = $('.-footer-panel').find('.adm-btn, input'),
			$selectItems   = $('.-select-form').find('select, input'),
			$message       = $('.-message-box'),
			$loader        = $('.-ajax-loader'),
			$hintCont      = $('.-hint-container'),
			$progressArray = [ $('.edit-td'), $('.-edit-links'), $('.-convert-single'), $('.-merge') ];


		if (state == false) {

			$loader.hide();

			if ($footerItems.length) {
				$footerItems.prop('disabled', false);
				$footerItems.removeClass('disabled');
			}
			if ($selectItems.length) {
				$selectItems.prop('disabled', false);
			}
			if ($hintCont.length) {
				$hintCont.hide();
			}

			$.each($progressArray, function() {
				if ($(this).length) {
					$(this).data('in-progress', false);
				}
			})

		} else {

			if (!$message.is(':hidden')) {
				$message.hide();
			}
			$loader.show();

			if ($footerItems.length) {
				$footerItems.prop('disabled',true);
				$footerItems.addClass('disabled');
			}
			if ($selectItems.length) {
				$selectItems.prop('disabled',true);
			}
			if ($hintCont.length) {
				$hintCont.hide();
			}

			$.each($progressArray, function() {
				if ($(this).length) {
					$(this).data('in-progress', true);
				}
			})

		}
	}

	function showMessage(text) {

		$('.-message-box')
			.html( text )
			.show()
			.delay(5000)
			.fadeOut('fast');
	}

	function showCancelButton(state) {

		var $cancel = $('.-cancel'); 

		if (state == true) {
			$cancel.show();
		} else {
			$cancel.hide();
		}

	}

	function showMergeLink(offset) {

		var $mergeLink = $('.-merge');

		if (offset == false) {

			if ( !$('input[type="radio"]:checked').length ) {
				$mergeLink.hide();
			}
			
		} else {

			var parentTop  = $CONTAINER.offset().top,
				parentLeft = $CONTAINER.offset().left,
				top        = offset.top  - parentTop,
				left       = offset.left - parentLeft + 65;

			$mergeLink.css({
				'top'  : top,
				'left' : left,
			});
			$mergeLink.show();
		}
	}

	function selectRow(prop, state) {
		if (state == true) {
			prop.addClass('selected')
		} else {
			prop.removeClass('selected')
		}
		
	}

	function showHint(selector, state) {

		var hintContent = selector.html();

		if (state) {
			if (hintContent && hintContent.length > 0) {
				$('.-hint-container').html(hintContent).show();
			}
		} else {
			$('.-hint-container').html('').hide();
		}
	}

	function moveHint(e, reverse) {

		var $hint      = $('.-hint-container'),
			parentTop  = $CONTAINER.offset().top,
			parentLeft = $CONTAINER.offset().left,
			top        = e.pageY - parentTop  + $hint.outerHeight() - $hint.innerHeight(),
			left       = e.pageX - parentLeft - $hint.outerWidth();

		if (reverse) {
			top  = e.pageY - parentTop  - $hint.innerHeight(),
			left = e.pageX - parentLeft - $hint.outerWidth() - 3;
		}

		$hint.css('top', top);
		$hint.css('left', left);

	}

	function setFooterPanel() {

		var $panel       = $('.-footer-panel'),
			panelHeight  = $panel.height() + 18,
			parentTop    = $CONTAINER.offset().top,
			windHeight   = $(window).height(),
			scrollTop    = $(window).scrollTop(),
			admFooterTop = $('.adm-footer-wrap').offset().top,
			selfTop      = windHeight  + scrollTop - parentTop - panelHeight;

			if (scrollTop + windHeight > admFooterTop) {
				$panel.css('top', admFooterTop - 220);
				return false;
			}
			$panel.css({
				'width' : $('.props-table').width(),
				'top'   : selfTop
			});
		
	}

	function setError(input, state) {
		if (state == true) {
			input.addClass('error').focus();
		} else {
			input.removeClass('error').blur();
		}
	}

	function submitSelect() {

		BX.showWait();
		$('.-select-form-submit').prop('disabled', true);

		var $form   = $('.-select-form'),
			href    = $form.find('.-type-select').find('option:selected').data('url'),
			params  = {
				types   : $form.find('select[name=types]').find('option:selected').val(),
				iblock  : $form.find('select[name=iblock]').find('option:selected').val(),
				element : $form.find('input[name=element]').val()
			};

		window.location.href = href + '?' + $.map(params, function(val, key) { 
			return (!val ? null : [key,val].join('=')); 
		}).join('&');
	}

	function checkKey(self, inner, e) {

		if (e.type == 'keydown') {

			if (e.keyCode == 27) {
				inner.show()
				self.hide();
			}
			if (e.keyCode != 13) {
				inProgress(false);
				return true;
			}
		}

		return false;
	} 

	function setDialogEvents() {

		var $dlgWrap = $(DIALOG.DIV);

		$dlgWrap
			.on('click', '.-new-prop-save', function(e) {
				e.preventDefault();

				var $name      = $dlgWrap.find('.-name'),
					$code      = $dlgWrap.find('.-code'),
					$loader    = $dlgWrap.find('.-cpf-loader'),
					$message   = $dlgWrap.find('.-form-message'),
					code       = $code.val(),
					name       = $name.val(),
					type       = $dlgWrap.find('.-type').find('option:selected').val();
					codeRegxp  = new RegExp('^[a-zA-Z0-9_]+$'),
					multiple   = $dlgWrap.find('.-mult').prop('checked')     ? 'Y' : 'N',
					required   = $dlgWrap.find('.-required').prop('checked') ? 'Y' : 'N',
					errFlag    = false;

				$dlgWrap.find('.error').removeClass('error');

				if (!name.length) {
					$name.addClass('error');
					errFlag = true;
				}

				if (!code.length) {
					$code.addClass('error');
					errFlag = true;
				}

				if (errFlag) {
					$message.text(BX.message('ppt_not_enougth_values'));
					return false;
				}

				if (!codeRegxp.test(code)) {
					$code.addClass('error');
					$message.text(BX.message('ppt_not_valid_code'));
					return false;
				}

				$loader.show();
				$.ajax({
						url: PXPT_AJAX_HANDLER,
						type: 'POST',
						data: {
							ACTION      : 'CREATE_PROP',
							TYPE        : PROPS_TYPE,
							IBLOCK_ID   : IBLOCK_ID,
							PARAMS : {
								NAME        : name,
								CODE        : code,
								TYPE        : type,
								MULTIPLE    : multiple,
								IS_REQUIRED : required,
							}
						},
						dataType: 'json'
					}).done(function(response){

						if (response && response == 'code_already_exist') {
							$message.text(BX.message('ppt_code_already_exist'))
						} else {
							showMessage(BX.message('ppt_create_prop_success'))
							DIALOG.Close();
							if (type == PROPS_TYPE.substr(0, 1)) {
								window.location.reload();
							}	
						}


					}).fail(function(jqXHR, textStatus) {
						alert(textStatus);
					}).always(function() {
						$loader.hide();
					});

			});
	}


	// colorize
	var colorList  = ['lightblue', 'lightcyan', 'lightcoral', 'lightpink', 'lightgreen', 'lightgray', 'lightgoldenrodyellow', 'lightsalmon', 'lightskyblue', 'lightyellow'],
		type       = $(this).hasClass('-properties') ? 'properties' : 'values',
		doneValues = {};

	if ('values' == type) {
		var z = $FORM.find('table tbody>tr'),
			count = 0;

		z.each(function() {
			var hash = $(this).data('hash'),
				selector = '[data-hash="HASH"]'.replace(/HASH/, hash);

			// already done
			if (hash in doneValues) {
				return true;
			}

			// this is unique value
			if (z.filter( selector ).length <= 1) {
				return true;
			}

			doneValues[ hash ] = colorList[count];
			// not enough colors
			if (!doneValues[ hash ]) {
				count = 0;
				doneValues[ hash ] = colorList[count];
			}

			z.filter( selector ).css('backgroundColor', doneValues[ hash ]);
			count++;
		});
	}

	// set sorting
	if ($FORM.find('table').length) {

		var table    = $FORM.find('table').get(0),
			headers  = $FORM.find('.adm-list-table-header').find('td'),
			hash     =  window.location.hash;

		if (hash.length > 0) {

			var hashArr = hash.split('/'),
				number  = hashArr[0] ? hashArr[0].split('=').pop() : false,
				type    = hashArr[1] ? hashArr[1].split('=').pop() : false;

			if (number && type) {

				headers
					.removeClass('sort-up sort-down')
					.eq(number)
					.addClass(type == 'down' ? 'sort-up' : 'sort-down');
			}
		

		}


		new Tablesort(table);

		headers.not('.no-sort')
			.on('mousedown', function() {
				headers.removeClass('sort-up sort-down');
				BX.showWait();
			})

		table.addEventListener('afterSort', function(e) {

			BX.closeWait();

			var sortColumn = $(table).find('.sort-up, .sort-down'),
				sortNum    = sortColumn.index(),
				sortType   = sortColumn.hasClass('sort-up') ? 'down' : 'up';

			window.location.hash = 'COL=' + sortNum + '/TYPE=' + sortType;
		});
	}
	
	// set footer pos
	if (IS_FOOTER) {
		setFooterPanel();
	}
	
	// set events
	$('.-toolkit-list-container')
		.on('click', '.-open-full-screen', function(e) {

			e.preventDefault();
			$('.adm-resizer-btn').trigger('click', setFooterPanel());
		})
		.on('click', '.-select-form-submit', submitSelect)
		.on('keydown', '.-element-filter', function(e) {
			if (e.keyCode == 13) {
				submitSelect();
			}
		})
		.on('click', '.check-td', function(e) {

			var chbox = $(this).find('input[type=checkbox]').get(0),
				radio = $(this).prev().find('input[type=radio]').get(0),
				row   = $(this).parents('tr'),
				rows  = $FORM.find('tr').not(':first'),
				count = 0;

			selectRow(row, true);
			chbox.checked = !chbox.checked;
			if (radio) {
				if (!radio.checked && !chbox.checked) {
					selectRow(row, false);
				}
			} else if (!chbox.checked){
				selectRow(row, false);
			}

		})
		.on('click', 'input[type=checkbox]', function(e) {
			e.stopPropagation();

			if ($(this).hasClass('.-check-all')) {
				return false;
			}

			var chbox = $(this).get(0),
				radio = $(this).parent('td').prev().find('input[type=radio]').get(0),
				row   = $(this).parents('tr'),
				rows  = $FORM.find('tr').not(':first');

			selectRow(row, true);

			if (radio) {
				if (!radio.checked && !chbox.checked) {
					selectRow(row, false);
				}
			} else if (!chbox.checked){
				selectRow(row, false);
			}

		})
		.on('click', '.-check-all', function(e) {

			var $self   = $(this),
				$rows   = $FORM.find('tr').not(':first'),
				$checks = $rows.find('input[type=checkbox]'),
				checked = $self.prop('checked');
				
			$checks.prop('checked', checked);

			if (checked) {
				$rows.addClass('selected');
			} else {
				$rows.removeClass('selected');
			}
			

		})
		.on('click', '.radio-td', function(e) {
			e.stopPropagation();

			var radio  = $(this).find('input[type=radio]').get(0),
				chbox  = $(this).closest('td').next().find('input[type=checkbox]').get(0),
				rows   = $FORM.find('tr').not(':first'),
				row    = $(this).closest('tr'),
				offset = $(this).offset(),
				count  = 0;

			showMergeLink(offset);
			radio.checked = true;
			chbox.checked = true;

			rows.each(function() {

				var radio = $(this).find('input[type=radio]').get(0),
					chbox = $(this).find('input[type=checkbox]').get(0);

				if (!radio.checked && !chbox.checked) {
					selectRow(row, false);
				}

			});

			selectRow(row, true);
		
		})
		.on('click', 'input[type=radio]', function(e) {

			e.stopPropagation();
			var $parent = $(this).parent(),
				chbox   = $parent.closest('td').next().find('input[type=checkbox]').get(0),
				rows    = $FORM.find('tr').not(':first'),
				row     = $(this).parents('tr'),
				offset  = $parent.offset(),
				count   = 0; 

			selectRow(row, true);
			showMergeLink(offset);
			chbox.checked = true;

			rows.each(function() {

				var radio = $(this).find('input[type=radio]').get(0),
					chbox = $(this).find('input[type=checkbox]').get(0);

				if (!chbox.checked && !radio.checked) {
					selectRow($(this), false);
				}

			});
		})
		.on('change', '.-type-select', function() {

			var $form  = $('.-select-form');
				action = $(this).find('option:selected').data('url');

			$form.attr('action', action);
		})
		.on('click', '.-merge', function(evt) {
			evt.preventDefault();

			var $self    = $(this),
				type     = $self.hasClass('-properties') ? 'properties' : 'values',
				dataArr  = $FORM.serializeArray(),
				items    = {},
				errMsg   = [];

			if ($self.data('in-progress') == true) {
				return false;
			}
			
			if (type == 'properties') {
				var notLeadErr = BX.message('ppt_not_leading_prop'),
					noPropsErr = BX.message('ppt_need_two_or_more_props_for_merge'),
					conMsg     = BX.message('ppt_merge_props_confirm');
			} else {
				var notLeadErr = BX.message('ppt_not_leading_value'),
					noPropsErr = BX.message('ppt_need_two_or_more_values_for_merge'),
					conMsg     = BX.message('ppt_merge_values_confirm');
			}

			// check if there are props selected and leading prop selected
			$.each(dataArr, function() {
				if (/\[\]$/.test(this.name)) {

					var name = this.name.replace(/\[\]$/g, '');
					if ('undefined' == typeof items[ name ]) {
						items[ name ] = [];
					}
					items[ name ].push(this.value);

				} else {
					items[ this.name ] = this.value;
				}
			});

			if ( !('LEADING' in items) ) {
				errMsg.push(notLeadErr);
			}

			if ( !('PROPS' in items) || items['PROPS'].length <= 1) {
				errMsg.push(noPropsErr);	
			}

			if (errMsg.length) {
				alert( errMsg.join('\n') );
				return false;
			}

			if (confirm(conMsg)) {

				inProgress(true);
				
				if (type == 'values') {
					dataArr.push({name: 'ACTION', value: 'MERGE_VALUES'});
					dataArr.push({name: 'PROPERTY_ID', value: $(this).data('prop-id') });
				} else {
					dataArr.push({name: 'ACTION', value: 'MERGE_PROPS'});
					dataArr.push({name: 'TYPE', value: PROPS_TYPE});
				}

				$.ajax({
					url: PXPT_AJAX_HANDLER,
					type: 'POST',
					dataType: 'json',
					data: dataArr,
				}).done(function(response) {

					if (response) {
						showMessage(BX.message('ppt_merge_success'));

						if ('properties' == type) {
							mergePropsRows(items.PROPS, items.LEADING, response.count);
						} else {
							mergeValuesRows(items.PROPS, items.LEADING)
						}

						showCancelButton(true);
					}
				}).fail(function(jqXHR, textStatus) {
					alert(textStatus);
					window.location.reload();
				}).always(function() {
					inProgress(false);
				});
			}
		})
		.on('click', '.-merge-all-values', function(evt) {
			evt.preventDefault();

			var $values   = $FORM.find('tr').not(':first'),
				hashList  = {},
				valGroups = []; 


			// check for same props values
			$values.each(function() {
				var hash = $(this).data('hash'),
					id   = $(this).attr('id');

				if (!hashList.hasOwnProperty(hash)) {
					hashList[hash] = [];
				}

				hashList[hash].push(id);
			});

			$.each(hashList, function(key, value) { 
				if (value.length > 1) { 
					valGroups.push(value);
				} 
			});

			if (confirm(BX.message('ppt_merge_all_values_confirm'))) {

				inProgress(true);
					
				$.ajax({
					url: PXPT_AJAX_HANDLER,
					type: 'POST',
					data: {
						ACTION : 'MERGE_ALL_VALUES',
						TYPE   : 'LIST',
						GROUPS : valGroups
					},
				}).done(function(response) {
					if (response) {
						showMessage(BX.message('ppt_merge_all_values_success'));
						window.location.reload();
					}
				}).fail(function(jqXHR, textStatus) {
					alert(textStatus);
					window.location.reload();
				}).always(function() {
					inProgress(false);
				});;
				
			}
		})
		.on('click', '.-delete', function(e) {
			e.preventDefault();

			var $self   = $(this),
				dataArr = $FORM.serializeArray(),
				type    = $self.hasClass('-properties') ? 'properties' : 'values',
				items   = [];

			if (type == 'properties') {
				var errMsg = BX.message('ppt_no_props_for_delete'),
					conMsg = BX.message('ppt_delete_props_confirm'),
					sucMsg = BX.message('ppt_delete_props_success');
			} else {
				var errMsg = BX.message('ppt_no_values_for_delete'),
					conMsg = BX.message('ppt_delete_values_confirm'),
					sucMsg = BX.message('ppt_delete_values_success');
			}

			$.each(dataArr, function() {
				if (/\[\]$/.test(this.name)) {

					var name = this.name.replace(/\[\]$/g, '');
					if ('undefined' == typeof items[ name ]) {
						items[ name ] = [];
					}
					items[ name ].push(this.value);

				} else {
					items[ this.name ] = this.value;
				}
			});

			if ( !('PROPS' in items) || items['PROPS'].length <= 0) {
				alert(errMsg);
				return false;	
			}

			if (confirm(conMsg)) {

				if (type == 'values') {
					dataArr.push({name: 'ACTION', value: 'DELETE_VALUES'});
					dataArr.push({name: 'PROPERTY_ID', value: $self.data('prop-id') });
				} else {
					dataArr.push({name: 'ACTION', value: 'DELETE_PROPS'});
				}
				dataArr.push({name: 'TYPE', value: PROPS_TYPE });

				inProgress(true);

				$.ajax({
					url: PXPT_AJAX_HANDLER,
					type: 'POST',
					data: dataArr,
					dataType: 'json'
				}).done(function(response){
					if (response) {
						removeRows(response);
						showMessage(sucMsg);
						showCancelButton(true);
						showMergeLink(false);
					}
				}).fail(function(jqXHR, textStatus) {
					alert(textStatus);
					window.location.reload();
				}).always(function() {
					inProgress(false);
				});

			}

		})
		.on('click', '.-delete-unused-values', function(e) {
			e.preventDefault();

			var $props    = $FORM.find('tr').not(':first'),
				propsIDs  = [];

			if (confirm(BX.message('ppt_delete_empty_values_confirm'))) {

				$props.each(function() {
					propsIDs.push( this.id );
				})

				inProgress(true);

				$.ajax({
					url : PXPT_AJAX_HANDLER,
					type: 'POST',
					data: {
						PROPS  : propsIDs,
						ACTION : 'DELETE_UNUSED_VALUES',
						TYPE   : 'LIST'
					},
					dataType: 'json'
				}).done(function(response){
					if (response) {
						showMessage(BX.message('ppt_delete_empty_values_success'));
						window.location.reload();
					}
				}).fail(function(jqXHR, textStatus) {
					alert(textStatus);
					window.location.reload();
				}).always(function() {
					inProgress(false);
				});

			}

		})
		.on('click', '.-delete-empty-props', function(e) {
			e.preventDefault();

			var $self     = $(this),
				$props    = $FORM.find('[data-empty="true"]'),
				propsIDs  = [];

			if (confirm(BX.message('ppt_delete_empty_props_confirm'))) {

				$props.each(function() {
					propsIDs.push( this.id );
				})
		
				inProgress(true);

				$.ajax({
					url : PXPT_AJAX_HANDLER,
					type: 'POST',
					data: {
						PROPS  : propsIDs,
						ACTION : 'DELETE_PROPS',
						TYPE   : PROPS_TYPE
					},
					dataType : 'json'
				}).done(function(response){

					if (response) {
						removeRows(response);
						showMessage(BX.message('ppt_delete_empty_props_success'));
						showCancelButton(true);
						showMergeLink(false);
						$self.hide();
					}
				}).fail(function(jqXHR, textStatus) {
					alert(textStatus);
					window.location.reload();
				}).always(function() {
					inProgress(false);
				});

			}
		})
		.on('click', '.-convert-single', function(e) {
			e.preventDefault();

			var $self       = $(this),
				$convertAll = $('.-convert-all[data-action="'+ $self.data('action') +'"]'),
				confirmMsg  = PROPS_TYPE == 'STRING' && $self.data('action') == 'CONVERT_TO_LIST'
					? BX.message('ppt_convert_confirm_str_to_list')
					: BX.message('ppt_convert_confirm');

			if ($self.data('in-progress') == true) {
				return false;
			}
				
			if (confirm(confirmMsg)) {

				inProgress(true);
				$.ajax({
					url: PXPT_AJAX_HANDLER,
					type: 'POST',
					data: {
						PROPS  : $self.data('id'),
						ACTION : $self.data('action'),
						TYPE   : PROPS_TYPE
					},
					dataType: 'json' 
				}).done(function(response){

					if (response) {

						removeRows(response);

						var $convertLinks = $('.convert a[data-action="'+ $self.data('action') +'"]');
						
						if ($convertLinks.length == 1) {
							$convertAll.hide();
						} else {
							$convertAll
								.find('.-count')
								.html($convertLinks.length);
						}
						showCancelButton(true);
						showMessage(BX.message('ppt_convert_success'));
					}
				}).fail(function(jqXHR, textStatus) {
					alert(textStatus);
					window.location.reload();
				}).always(function() {
					inProgress(false);
				});

			}

		})
		.on('click', '.-convert-all', function(e) {
			e.preventDefault();

			var $self         = $(this),
				$convertLinks = $('.convert a[data-action="'+ $self.data('action') +'"]'),
				propsIDs      = [];

			
			$convertLinks.each(function() {
				propsIDs.push( $(this).data('id') );
			});

			if (confirm(BX.message('ppt_convert_all_confirm'))) {

				inProgress(true);

				$.ajax({
					url: PXPT_AJAX_HANDLER,
					type: 'POST',
					data: {
						PROPS  : propsIDs,
						ACTION : $self.data('action'),
						TYPE   : PROPS_TYPE 
					},
					dataType: 'json'
				}).done(function(response){
					removeRows(response);
					showMessage(BX.message('ppt_convert_all_success'));
					showCancelButton(true);
					showMergeLink(false);
					$('.-convert-all').hide();
				}).fail(function(jqXHR, textStatus) {
					alert(textStatus);
					window.location.reload();
				}).always(function() {
					inProgress(false);
				});

			}

		})
		.on('click', '.-edit-list-td, .-edit-single-td', function(e) {
			e.preventDefault();

			var $self       = $(this),
				$inner      = $self.find('span'),
				$editInput  = $self.find('.-edit-input');

			if ( $self.data('in-progress') == false) {

				$inner.hide();
				$editInput.show();
				$editInput.focus();
		
			}

		})
		.on('keydown blur','.-edit-list-td input', function(e) {

			var $self       = $(this),
				$parent     = $self.closest('td'),
				$inner      = $self.siblings('span'),
				$editTDs    = $('.-edit-list-td'),
				innerVal    = $inner.text(),
				object      = $parent.data('type'),
				value       = $(this).val();

			if (checkKey($self, $inner, e)) {
				return true;
			}

			$self.removeClass('error');

			if (object == 'name') {

				$('td[data-type="name"]').find('span').text(value)
				var id     = $parent.prev().text(),
					resMsg = BX.message('ppt_edit_name_success');

			} else {

				if (PROPS_TYPE == 'NUMBER' && isNaN(value)) {

					$self.addClass('error');
					showMessage(BX.message('ppt_not_valid_number_value_edit'));
					return false;
				}
				var id     = PROPS_TYPE == 'LIST' ? $parent.prev().text() : $parent.data('id'),
					resMsg = BX.message('ppt_edit_value_success');
			}

			$inner.text(value).show()
			$self.hide();

			if (value != innerVal) {

				inProgress(true);

				$.ajax({
					url: PXPT_AJAX_HANDLER,
					type: 'POST',
					data: {
						PROPEL  : parseInt(id),
						PROPVAL : value,
						OBJECT  : object,
						TYPE    : PROPS_TYPE,
						ACTION  : 'EDIT'
					}
				}).done(function(response) {
					showMessage(resMsg);
					showCancelButton(true);
				}).fail(function(jqXHR, textStatus) {
					alert(textStatus);
					window.location.reload();
				}).always(function() {
					inProgress(false);
				});
			} else {
				inProgress(false);
			}
		})
		.on('keydown blur', '.-edit-single-td input', function(e) {

			var $self       = $(this),
				$parent     = $self.closest('td'),
				$inner      = $self.siblings('span'),
				innerVal    = $inner.text();

			if (checkKey($self, $inner, e)) {
				return true;
			}

			$inner.text( $self.val() ).show()
			$(this).hide();
			if (type == 'name') {
				$('td[data-type="name"]')
					.find('span')
					.text( $self.val() )
			}

			if ( $(this).val() != innerVal ) {

				var value = $(this).val(),
					id = parseInt( $parent.prev().text() );
			
				inProgress(true);

				$.ajax({
					url: PXPT_AJAX_HANDLER,
					type: 'POST',
					data: {
						PROPEL  : id,
						PROPVAL : value,
						OBJECT  : 'name',
						ACTION  : 'EDIT',
						TYPE    : PROPS_TYPE
					}
				}).done(function(response) {
					showMessage(BX.message('ppt_edit_name_success'));
					showCancelButton(true);
				}).fail(function(jqXHR, textStatus) {
					alert(textStatus);
					window.location.reload();
				}).always(function() {
					inProgress(false);
				});
			} else {
				inProgress(false);
			}
		})
		.on('click', '.-edit-links', function(e) {
			e.preventDefault();

			var $self       = $(this),
				$prev       = $self.closest('td').prev(),
				$editInput  = $prev.find('.-edit-input'),
				$inner      = $prev.find('.-link-block');

			if ( $self.data('in-progress') == false) {

				$inner.hide();
				$editInput.show();
				$editInput.focus();
				setFooterPanel();
		
			}
		})
		.on('keydown blur', '.-edit-products-td textarea', function(e) {

			var $self       = $(this),
				$parent     = $self.closest('td'),
				$row        = $parent.closest('tr'),
				$inner      = $self.siblings('div'),
				$products   = $inner.find('.product'),
				newProducts = $self.val().split(','),
				valueEnumId = parseInt( $row.prop('id') ),
				propertyId  = parseInt( $row.find('.id').text() )
				productsIds = [],
				noValid     = false;

			if (checkKey($self, $inner, e)) {
				return true;
			}

			$products.each(function() {
				productsIds.push($(this).data('id'))
			})
			productsIds = productsIds.join(',');

			$.each(newProducts, function(key, value) {
				if (isNaN(value)) {
					$self.addClass('error');
					alert(BX.message('ppt_invalid_links'))
					noValid = true;
				}
			})
			if (noValid) {
				return false;
			}

			$self.hide();
			$inner.show();

			if ( $self.val() != productsIds ) {
			
				inProgress(true);
				$inner.text($self.val());
				$self.removeClass('error')

				$.ajax({
					url: PXPT_AJAX_HANDLER,
					type: 'POST',
					data: {
						PROPEL  : propertyId,
						PROPVAL : valueEnumId,
						LINKS   : newProducts,
						ACTION  : 'EDIT_LINKS',
						TYPE    : PROPS_TYPE
					},
					dataType: 'json'
				}).done(function(response) {

					if (response && response.length) {
						showMessage(BX.message('ppt_invalid_products_ids') + response.join(','));
						inProgress(false);
					} else {
						showMessage(BX.message('ppt_edit_links_success'));
						window.location.reload();
					}

				}).fail(function(jqXHR, textStatus) {
					alert(textStatus);
					window.location.reload();
				})
			} else {
				inProgress(false);
			}
		})
		.on('click', '.-move', function(e) {
			e.preventDefault();

			var $prop2move  = $('.-prop2move'),
				valsPropID  = $(this).data('prop-id'),
				prop2moveID = $prop2move.val(),
				dataArr     = $FORM.serializeArray(),
				errMsg      = [],
				items       = [];

			$.each(dataArr, function() {
				if (/\[\]$/.test(this.name)) {

					var name = this.name.replace(/\[\]$/g, '');
					if ('undefined' == typeof items[ name ]) {
						items[ name ] = [];
					}
					items[ name ].push(this.value);

				} else {
					items[ this.name ] = this.value;
				}
			});

			if ( !('PROPS' in items)) {
				errMsg.push(BX.message('ppt_no_values_2move'));	
			}

			if (!/^(\d)+$/.test(prop2moveID)) {
				errMsg.push(BX.message('ppt_invalid_prop_id'));
				setError($prop2move, true);
			}

			if (valsPropID == prop2moveID) {
				errMsg.push(BX.message('ppt_invalid_prop_2move'));
				setError($prop2move, true);
			}

			if (errMsg.length) {
				alert( errMsg.join('\n') );
				return false;
			}

			if (confirm(BX.message('ppt_move_values_confirm'))) {

				inProgress(true);
				setError($prop2move, false);

				$.ajax({
					url: PXPT_AJAX_HANDLER,
					type: 'POST',
					data: {
						ACTION : 'MOVE_VALUES',
						TYPE   : PROPS_TYPE,
						PROPS  : items.PROPS,
						PROP_FROM_MOVE : valsPropID,
						PROP_TO_MOVE   : prop2moveID
					},
					dataType: 'json'
				}).done(function(response){
					if (response && response == 'not_found') {

						alert(BX.message('ppt_invalid_prop_2move_resp'));
						setError($prop2move, true);	

					} else if (response && typeof response == 'string') {

						alert(BX.message('ppt_' + response));
						setError($prop2move, true);

					} else {

						removeRows(response);
						showMessage(BX.message('ppt_move_values_success'));
						showCancelButton(true);
						setError($prop2move, false);
					}
				}).fail(function(jqXHR, textStatus) {
					alert(textStatus);
					window.location.reload();
				}).always(function() {
					showMergeLink(false);
					inProgress(false);
				});

			}

		})
		.on('click', '.-clear-all-same-links', function(e) {
			e.preventDefault();

			var $self    = $(this),
				$props   = $FORM.find('tr[data-same="true"]'),
				propsIds = [];

			
			$props.each(function() {
				propsIds.push( $(this).prop('id') );
			});

			if (confirm(BX.message('ppt_clear_all_same_confirm'))) {

				inProgress(true);

				$.ajax({
					url: PXPT_AJAX_HANDLER,
					type: 'POST',
					data: {
						PROPS  : propsIds,
						ACTION : $self.data('action'),
						TYPE   : PROPS_TYPE 
					},
					dataType: 'json'
				}).done(function(response){
					showMessage(BX.message('ppt_clear_all_same_success'));
					showCancelButton(true);
					$self.hide();
				}).fail(function(jqXHR, textStatus) {
					alert(textStatus);
					window.location.reload();
				}).always(function() {
					inProgress(false);
				});

			}

		})
		.on('click', '.-open-popup', function(e) {
			e.preventDefault();

			if (DIALOG == null) {

				DIALOG = new BX.CDialog({
					title   : BX.message('ppt_popup_title'),
					width   : 400,
					height  : 200,
					icon    : 'head-block',
					resize  : false, 
					buttons : ['<input type="button" value="Сохранить" class="-new-prop-save" /> \
					<div class="-cpf-loader cpf-loader"></div>']

				});

				setDialogEvents();
			}

			var typesSelect = '<select name="type" class="type -type"> \
						<option value="L">#list#</option> \
						<option value="N">#number#</option> \
						<option value="S">#string#</option> \
					</select>'
					.replace('#list#',   BX.message('ppt_popup_prop_list'))
					.replace('#number#', BX.message('ppt_popup_prop_number'))
					.replace('#string#', BX.message('ppt_popup_prop_string'))

			var content = '<form class="create-prop-form -create-prop-form"><table> \
					<tr><td><label>#name#</label></td><td><input name="name" class="name -name"></td></tr> \
					<tr><td><label>#code#</label></td><td><input name="code" class="code -code" value="PPT_"></td></tr> \
					<tr><td><label>#type#</label></td><td>#select#</td></tr> \
					<tr><td><label>#multiple#</label></td><td><input type="checkbox" value="Y" name="multiple" class="-mult"></td></tr> \
					<tr><td><label>#required#</label></td><td><input type="checkbox" value="Y" name="required" class="-required"></td></tr> \
					</table><p class="-form-message form-message"></p> \
				</form>'
				.replace('#name#',     BX.message('ppt_popup_prop_name'))
				.replace('#code#',     BX.message('ppt_popup_prop_code'))
				.replace('#type#',     BX.message('ppt_popup_prop_type'))
				.replace('#multiple#', BX.message('ppt_popup_prop_multiple'))
				.replace('#required#', BX.message('ppt_popup_prop_required'))
				.replace('#select#',   typesSelect);

			DIALOG.SetContent(content);
			DIALOG.Show();

		})
		.on('click', '.-cancel', function(e) {
			e.preventDefault();
			
			if (confirm(BX.message('ppt_cancel_action_confirm'))) {

				inProgress(true);
				$.ajax({
					url: PXPT_AJAX_HANDLER,
					type: 'POST',
					data: {
						ACTION  : 'CANCEL',
						TYPE    : PROPS_TYPE
					}
				}).done(function(response){
					if (response != "error") {
						showMessage(BX.message('ppt_cancel_action_success'));
						window.location.reload();
					} else {
						alert(BX.message('ppt_file_not_found'));
					}
				
				}).fail(function(jqXHR, textStatus) {
					alert(textStatus);
					window.location.reload();
				}).always(function() {
					inProgress(false);
				});

			}

		});

	$('.-view-products')
		.hover(function(e) {
			showHint($(this).next('div'), true);
		}, function(e) {
			showHint($(this).next('div'), false);
		})
		.on('mousemove', function(e) {
			moveHint(e, false);
		});

	$('.-view-values').parent()
		.hover(function(e) {
			showHint($(this).find('div'), true);
		}, function(e) {
			showHint($(this).find('div'), false);
		})
		.on('mousemove', function(e) {
			moveHint(e, false);
		});

	$('.-view-help')
		.hover(function(e) {
			showHint($(this).find('div'), true);
		}, function(e) {
			showHint($(this).find('div'), false);
		})
		.on('mousemove', function(e) {
			moveHint(e, true);
		});

	// update footer pos
	if (IS_FOOTER) {

		$(window)
			.on('scroll', function(){
				setFooterPanel();
			});

		BX.addCustomEvent(BX.adminMenu, 'onAdminMenuResize', function() {
			var $admWorkArea = $('.adm-workarea-page');

			$admWorkArea.css('overflow-x', 'hidden');
			setFooterPanel();
			$admWorkArea.css('overflow-x', 'visible');
		});
	}

	

});
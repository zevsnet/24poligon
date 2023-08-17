$.fn.phoneOrLogin = function(callback){
	if($(this).length){
		$(this).each(function(){
			init(this, callback);
		});
	}

	function init(input, callback){
		var $input = $(input);
		var keyTimeOut = false;

		if(typeof $input.data('phonecode') === 'undefined'){
			$input.data({
				phonecode: ''
			});

			$input.data('code', '');

			$input.bind({
				// not use keyup, because it is not work through teamviewer
				keydown: function(e){
					if(e.which == 255){
						return;
					}

					var that = this;
					if(keyTimeOut){
						clearTimeout(keyTimeOut);
					}
					keyTimeOut = setTimeout(function(){
						markPhoneOrLogin(that, callback);
					}, 50);
				},
				blur: function(e){
					var bEmpty = !$input.val().length;

					if(arAsproOptions['THEME']['PHONE_MASK'].length){
						var a = arAsproOptions['THEME']['PHONE_MASK'].replace(/9/g, '_');
						bEmpty |= a == $input.val();
					}

					// only on blur !
					if(bEmpty){
						if($input.hasClass('required')){
							$input.parent().find('label.error').html(BX.message('JS_REQUIRED'));
						}

						$input.inputmask('mask', {'mask': ''});
						$input.data('code', '');
					}

					markPhoneOrLogin(this, callback);
				},
				paste: function(e){
					var that = this;
					if(keyTimeOut){
						clearTimeout(keyTimeOut);
					}
					keyTimeOut = setTimeout(function(){
						markPhoneOrLogin(that, callback);
					}, 50);
				},
				cut: function(e){
					var that = this;
					if(keyTimeOut){
						clearTimeout(keyTimeOut);
					}
					keyTimeOut = setTimeout(function(){
						markPhoneOrLogin(that, callback);
					}, 50);
				}
			});

			markPhoneOrLogin(input, callback);
		}
	}

	function testPhoneOrLogin(val, code){
		var clearPhone = val.replace(/[\s-_()]/g, '');
		var clearLogin = val.replace(/[\s()]/g, '');
		var bPossiblePhone = false;
		var bPossibleEmail = false;

		if(clearPhone.length){
			bPossiblePhone = clearPhone.match(/^\+?\d*$/) ? true : false;
			if(!bPossiblePhone){
				bPossibleEmail = clearLogin.indexOf('@') !== -1;
			}
		}

		return {
			value: val,
			clearPhone: clearPhone,
			bPossiblePhone: bPossiblePhone,
			clearLogin: clearLogin,
			bPossibleEmail: bPossibleEmail,
		};
	}

	function markPhoneOrLogin(input, callback){
		var $input = $(input);
		var $parent = $input.closest('.phone_or_login');
		var val = $input.val();
		var code = $input.data('code');
		var test = testPhoneOrLogin(val, code);

		if(val.length){
			if(test.bPossiblePhone){
				if($parent.hasClass('phone_or_login-phone')){
					if(arAsproOptions['THEME']['PHONE_MASK'].length && code.length){
						var phone_code_mask = arAsproOptions['THEME']['PHONE_MASK'].replace(/\\9/g, '#').replace(/[^0-8+#]/g, '').replace(/#/g, '9');
						if(phone_code_mask.length){
							var pattern = '[+?]';
							var val_ = code + $input.inputmask('unmaskedvalue');
							var newcode = '';
							for(var i = 0; i < val_.length; ++i){
								var char = val_.charAt(i);
								if(char === '+' && !i){
									newcode += char;
									continue;
								}

								var tmp = pattern + ((char === '8' && (!i || (i == 1 && newcode === '+'))) ? '[78]' : char);
								var reg = new RegExp('^' + tmp);
								if(phone_code_mask.match(reg)){
									pattern = tmp;
									newcode += char;
								}
								else{
									break;
								}
							}

							$input.data('code', newcode);
							if(code != newcode){
								$input.val(val_);
								var now_is_phone_but_login_possible_mask = arAsproOptions['THEME']['PHONE_MASK'].replace(/\\9/g, '#').replace(/9/g, '*').replace(/#/g, '\\9');
								$input.inputmask('mask', {'mask': now_is_phone_but_login_possible_mask});
							}
						}
					}
				}
				else{
					$input.removeClass('email').addClass('phone');
					$parent.removeClass('phone_or_login-login').removeClass('phone_or_login-email').addClass('phone_or_login-phone');

					if(arAsproOptions['THEME']['PHONE_MASK'].length){
						var phone_code_mask = arAsproOptions['THEME']['PHONE_MASK'].replace(/\\9/g, '#').replace(/[^0-8+#]/g, '').replace(/#/g, '9');
						if(phone_code_mask.length){
							var pattern = '[+?]';
							var val_ = val;
							var newcode = '';
							for(var i = 0; i < val_.length; ++i){
								var char = val_.charAt(i);
								if(char === '+' && !i){
									newcode += char;
									continue;
								}

								var tmp = pattern + ((char === '8' && (!i || (i == 1 && newcode === '+'))) ? '[78]' : char);
								var reg = new RegExp('^' + tmp);
								if(phone_code_mask.match(reg)){
									pattern = tmp;
									newcode += char;
								}
								else{
									break;
								}
							}

							$input.data('code', newcode);

							if(phone_code_mask.match(new RegExp('^' + pattern + '$'))){
								$input.val(val_.substr(newcode.length));
							}
						}

						var now_is_phone_but_login_possible_mask = arAsproOptions['THEME']['PHONE_MASK'].replace(/\\9/g, '#').replace(/9/g, '*').replace(/#/g, '\\9');
						$input.inputmask(
							'mask', 
							{
								'mask': now_is_phone_but_login_possible_mask,
								definitions: {
									'*': {
										validator: '.*',
									}
								}
							}
						);
					}
				}
			}
			else{
				if(test.bPossibleEmail){
					if(!$parent.hasClass('phone_or_login-email')){
						$input.removeClass('phone').addClass('email');
						$parent.removeClass('phone_or_login-phone').removeClass('phone_or_login-login').addClass('phone_or_login-email');
						if(arAsproOptions['THEME']['PHONE_MASK'].length){
							$input.inputmask('mask', {'mask': ''});
							$(input).val(code + $(input).val());
						}
					}
				}
				else{
					if(!$parent.hasClass('phone_or_login-login')){
						$input.removeClass('phone').removeClass('email');
						$parent.removeClass('phone_or_login-phone').removeClass('phone_or_login-email').addClass('phone_or_login-login');
						if(arAsproOptions['THEME']['PHONE_MASK'].length){
							$input.inputmask('mask', {'mask': ''});
							$(input).val(code + $(input).val());
						}
					}
				}
			}
		}
		else{
			if(
				$parent.hasClass('phone_or_login-login') ||
				$parent.hasClass('phone_or_login-email') ||
				$parent.hasClass('phone_or_login-phone')
			){
				$parent.removeClass('phone_or_login-phone').removeClass('phone_or_login-login').removeClass('phone_or_login-email');
				$input.removeClass('phone').removeClass('email');
				$input.inputmask('mask', {'mask': ''});
				$input.data('code', '');
			}
		}

		if(typeof callback === 'function'){
			callback(input, test);
		}
	}
}
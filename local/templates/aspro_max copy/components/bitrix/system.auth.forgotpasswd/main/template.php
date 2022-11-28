<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(false);

if(isset($APPLICATION->arAuthResult)){
	$arResult['ERROR_MESSAGE'] = $APPLICATION->arAuthResult;
}

global $arTheme;

$bEmailAsLogin = $arTheme['LOGIN_EQUAL_EMAIL']['VALUE'] === 'Y';
$bByPhoneRequest = $arResult['PHONE_REGISTRATION'] && isset($_POST['USER_PHONE_NUMBER']) && isset($_POST['send_account_info']);
?>
<div class="forgotpasswd-page pk-page">
	<?if($arResult['ERROR_MESSAGE']):?>
		<div class="alert <?=($arResult['ERROR_MESSAGE']['TYPE'] === 'OK' ? 'alert-success' : 'alert-danger')?> compact"><?=$arResult['ERROR_MESSAGE']['MESSAGE']?></div>
	<?endif;?>
	<?if(!$arResult['ERROR_MESSAGE'] || $arResult['ERROR_MESSAGE']['TYPE'] != 'OK'):?>
		<div class="form">
			<form id="forgotpasswd-page-form" method="post" action="<?=POST_FORM_ACTION_URI?>" name="bform">
				<div class="top-text">
					<?if($arResult['PHONE_REGISTRATION']):?>
						<?$APPLICATION->IncludeFile(SITE_DIR.'include/forgotpasswd_phone_description.php', Array(), Array("MODE" => "html", "NAME" => ""));?>
					<?else:?>
						<?$APPLICATION->IncludeFile(SITE_DIR.'include/forgotpasswd_description.php', Array(), Array("MODE" => "html", "NAME" => ""));?>
					<?endif;?>
				</div>
				<?if($arResult['BACKURL'] <> ''):?>
					<input type="hidden" name="backurl" value="<?=$arResult['BACKURL']?>" />
				<?endif;?>
				<input type="hidden" name="AUTH_FORM" value="Y">
				<input type="hidden" name="TYPE" value="SEND_PWD">
				<div class="form_body">
					<?if($arResult['PHONE_REGISTRATION']):?>
						<div class="form-control phone_or_login">
							<label for="FORGOTPASSWD_PHONE_OR_LOGIN"class=""><span><?=GetMessage('forgot_pass_phone_number_or_login')?>&nbsp;<span class="star">*</span></span></label>
							<label for="FORGOTPASSWD_PHONE_OR_LOGIN"class=""><span><?=GetMessage('forgot_pass_login')?>&nbsp;<span class="star">*</span></span></label>
							<label for="FORGOTPASSWD_PHONE_OR_LOGIN"class=""><span><?=GetMessage('forgot_pass_phone_number')?>&nbsp;<span class="star">*</span></span></label>
							<input id="FORGOTPASSWD_PHONE_OR_LOGIN" class="required" type="text" name="FORGOTPASSWD_PHONE_OR_LOGIN" maxlength="255" autocomplete="off" />
							<?=CMax::showIconSvg('cabinet', SITE_TEMPLATE_PATH.'/images/svg/inputlogin.svg', '', 'colored');?>
							<?=CMax::showIconSvg('cabinet', SITE_TEMPLATE_PATH.'/images/svg/inputphone.svg', '', 'colored');?>
							<div class="text-block"><?=GetMessage('forgot_pass_login_note')?></div>
							<div class="text-block"><?=GetMessage('forgot_pass_phone_number_note')?></div>
						</div>
					<?else:?>
						<div class="form-control">
							<label for="FORGOTPASSWD_USER_LOGIN"><span><?=GetMessage('AUTH_LOGIN')?>&nbsp;<span class="star">*</span></span></label>
							<input id="FORGOTPASSWD_USER_LOGIN" type="<?=($bEmailAsLogin ? 'email' : 'text')?>" name="USER_LOGIN" required maxlength="255" autocomplete="off" />
							<input type="hidden" name="USER_EMAIL" maxlength="255" autocomplete="off" />
							<div class="text-block"><?=GetMessage('forgot_pass_login_note')?></div>
						</div>
					<?endif;?>

					<?if($arResult['USE_CAPTCHA']):?>
						<div class="clearboth"></div>
						<div class="form-control captcha-row clearfix">
							<label for="FORGOTPASSWD_CAPTCHA"><span><?=GetMessage('CAPTCHA_PROMT')?>&nbsp;<span class="star">*</span></span></label>
							<div class="captcha_image">
								<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult['CAPTCHA_CODE']?>" border="0" />
								<input type="hidden" name="captcha_sid" value="<?=$arResult['CAPTCHA_CODE']?>" />
								<div class="captcha_reload"><?=GetMessage('RELOAD')?></div>
							</div>
							<div class="captcha_input">
								<input id="FORGOTPASSWD_CAPTCHA" type="text" class="inputtext captcha" name="captcha_word" size="30" maxlength="50" value="" required />
							</div>
						</div>
						<div class="clearboth"></div>
					<?endif;?>
				</div>
				<div class="form_footer">
					<button class="btn btn-default btn-lg" type="submit" name="send_account_info" value="Y"><span><?=GetMessage('RETRIEVE')?></span></button>
					<div class="clearboth"></div>
				</div>
			</form>
		</div>
	<?endif;?>
	<script>
	$(document).ready(function(){
		$('#forgotpasswd-page-form').validate({
			highlight: function(element){
				$(element).parent().addClass('error');
			},
			unhighlight: function(element){
				$(element).parent().removeClass('error');
			},
			submitHandler: function(form){
				if($(form).valid()){
					var $button = $(form).find('button[type=submit]');
					if($button.length){
						if(!$button.hasClass('loadings')){
		  					$button.addClass('loadings');

							var eventdata = {type: 'form_submit', form: form, form_name: 'FORGOT'};
							BX.onCustomEvent('onSubmitForm', [eventdata]);
						}
		  			}
				}
			},
			errorPlacement: function(error, element){
				error.insertBefore(element);
			},
		});

		setTimeout(function(){
			$('#forgotpasswd-page-form').find('input:visible').eq(0).focus();
		}, 50);

		$('#forgotpasswd-page-form .phone_or_login input').phoneOrLogin(function(input, test){
			var $form = $(input).closest('form');
			if(test.bPossiblePhone){
				if(!$form.find('input[name=USER_PHONE_NUMBER]').length){
					$form.find('input[name=USER_LOGIN],input[name=USER_EMAIL]').remove();
					$form.prepend('<input type="hidden" name="USER_PHONE_NUMBER" />');
				}
				$form.find('input[name=USER_PHONE_NUMBER]').val(test.value);
			}
			else{
				if(!$form.find('input[name=USER_LOGIN]').length){
					$form.find('input[name=USER_PHONE_NUMBER],input[name=USER_EMAIL]').remove();
					$form.prepend('<input type="hidden" name="USER_LOGIN" />');
					$form.prepend('<input type="hidden" name="USER_EMAIL" />');
				}
				$form.find('input[name=USER_LOGIN]').val(test.value);
			}
		});
	});
	</script>
</div>
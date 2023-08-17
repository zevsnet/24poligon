<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(false);

if($arResult['PHONE_REGISTRATION']){
	CJSCore::Init('phone_auth');
}

if(isset($APPLICATION->arAuthResult)){
	$arResult['ERROR_MESSAGE'] = $APPLICATION->arAuthResult;

	if($arResult['ERROR_MESSAGE']['TYPE'] === 'OK'){
		unset($_SESSION['system.auth.changepasswd']); // /bitrix/components/bitrix/system.auth.forgotpasswd/component.php:25
	}
}
$lastLogin = (isset($_SESSION['lastLoginSave']) ? $_SESSION['lastLoginSave'] : ($arResult["LAST_LOGIN"]?:''));
global $arTheme;
?>
<div class="changepasswd-page pk-page">
	<?if($arResult['ERROR_MESSAGE']):?>
		<div class="alert <?=($arResult['ERROR_MESSAGE']['TYPE'] === 'OK' ? 'alert-success' : 'alert-danger')?> compact"><?=$arResult['ERROR_MESSAGE']['MESSAGE'].($arResult['ERROR_MESSAGE']['TYPE'] === 'OK' ? GetMessage('CHANGE_SUCCESS') : '')?></div>
	<?else:?>
		<?
		if(isset($_POST['LAST_LOGIN']) && empty($_POST['LAST_LOGIN'])){
			$arResult['ERRORS']['LAST_LOGIN'] = GetMessage('REQUIRED_FIELD');
		}
		if(isset($_POST['USER_PASSWORD']) && strlen($_POST['USER_PASSWORD']) < 6){
			$arResult['ERRORS']['USER_PASSWORD'] = GetMessage('PASSWORD_MIN_LENGTH_2');
		}
		if(isset($_POST['USER_PASSWORD']) && empty($_POST['USER_PASSWORD'])){
			$arResult['ERRORS']['USER_PASSWORD'] = GetMessage('REQUIRED_FIELD');
		}
		if(isset($_POST['USER_CONFIRM_PASSWORD']) && strlen($_POST['USER_CONFIRM_PASSWORD']) < 6 ){
			$arResult['ERRORS']['USER_CONFIRM_PASSWORD'] = GetMessage('PASSWORD_MIN_LENGTH_2');
		}
		if(isset($_POST['USER_CONFIRM_PASSWORD']) && empty($_POST['USER_CONFIRM_PASSWORD'])){
			$arResult['ERRORS']['USER_CONFIRM_PASSWORD'] = GetMessage('REQUIRED_FIELD');
		}
		if($_POST['USER_PASSWORD'] != $_POST['USER_CONFIRM_PASSWORD']){
			$arResult['ERRORS']['USER_CONFIRM_PASSWORD'] = GetMessage('WRONG_PASSWORD_CONFIRM');
		}
		?>
		<?if($arResult['PHONE_REGISTRATION']):?>
			<div class="alert alert-success compact"><?=GetMessage('change_pass_code_sent')?></div>
		<?endif;?>
	<?endif;?>
	<?if(!$arResult['ERROR_MESSAGE'] || $arResult['ERROR_MESSAGE']['TYPE'] !== 'OK'):?>
	    <div class="form">
	        <form id="changepasswd-page-form" method="post" action="<?=POST_FORM_ACTION_URI?>" name="bform">
				<?if($arResult['BACKURL'] <> ''):?>
					<input type="hidden" name="backurl" value="<?=$arResult['BACKURL']?>" />
				<?endif;?>
				<input type="hidden" name="AUTH_FORM" value="Y">
				<input type="hidden" name="TYPE" value="CHANGE_PWD">
				<div class="form_body">
					<?if($arResult['PHONE_REGISTRATION']):?>
						<div class="form-control">
							<label><span><?=GetMessage('change_pass_phone_number')?>&nbsp;<span class="star">*</span></span></label>
							<input type="text" value="<?=htmlspecialcharsbx($arResult['USER_PHONE_NUMBER'])?>" class="bx-auth-input" disabled required />
							<input type="hidden" name="USER_PHONE_NUMBER" value="<?=htmlspecialcharsbx($arResult['USER_PHONE_NUMBER'])?>" />
						</div>
						<div class="form-control">
							<label><span><?=GetMessage('change_pass_code')?>&nbsp;<span class="star">*</span></span></label>
							<input type="text" name="USER_CHECKWORD" required maxlength="50" value="<?=$arResult['USER_CHECKWORD']?>" class="bx-auth-input"  />
						</div>
					<?else:?>
			            <div class="form-control">
			                <label><span><?=GetMessage('change_pass_login')?>&nbsp;<span class="star">*</span></span></label>
							<input type="text" maxlength="50" value="<?= $lastLogin; ?>" class="bx-auth-input  <?=($_POST && empty($_POST['USER_LOGIN']) ? 'error': '')?>" disabled required />
							<input type="hidden" name="USER_LOGIN" value="<?= $lastLogin; ?>" />
			            </div>

						<?if($arResult["USE_PASSWORD"]):?>
							<div class="form-control">
								<label for="USER_CURRENT_PASSWORD"><span><?=GetMessage('AUTH_CURRENT_PASSWORD')?>&nbsp;<span class="star">*</span></span></label>
								<input type="password" name="USER_CURRENT_PASSWORD" maxlength="50" id="USER_CURRENT_PASSWORD" value="<?=$arResult["USER_CURRENT_PASSWORD"]?>" required class="bx-auth-input <?=( isset($arResult["ERRORS"]) && array_key_exists( "USER_CURRENT_PASSWORD", $arResult["ERRORS"] ))? "error": ''?>" />
							</div>
						<?else:?>
							<input type="hidden" name="USER_CHECKWORD" maxlength="50" value="<?=$arResult['USER_CHECKWORD']?>" />
						<?endif;?>
					<?endif;?>
					<div class="form-control">
						<label><span><?=GetMessage('AUTH_NEW_PASSWORD_REQ')?>&nbsp;<span class="star">*</span></span></label>
						<input type="password" name="USER_PASSWORD" maxlength="50" id="pass" required value="<?=$arResult['USER_PASSWORD']?>" class="bx-auth-input <?=(isset($arResult['ERRORS']) && array_key_exists('USER_PASSWORD', $arResult['ERRORS']) ? 'error': '')?>" />
						<div class="text-block"><?=GetMessage('PASSWORD_MIN_LENGTH')?></div>
					</div>
		            <div class="form-control">
		                <label><span><?=GetMessage('AUTH_NEW_PASSWORD_CONFIRM')?>&nbsp;<span class="star">*</span></span></label>
						<input type="password" name="USER_CONFIRM_PASSWORD" maxlength="50" required value="<?=$arResult['USER_CONFIRM_PASSWORD']?>" class="bx-auth-input <?=(isset($arResult['ERRORS']) && array_key_exists('USER_CONFIRM_PASSWORD', $arResult['ERRORS']) ? 'error': '')?>"  />
		            </div>
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
					<div class="line-block form_footer__bottom">
						<div class="line-block__item">
						<button class="btn btn-default btn-lg" type="submit" name="change_pwd" value="<?=GetMessage('AUTH_CHANGE')?>"><span><?=GetMessage('CHANGE_PASSWORD')?></span></button>
						</div>
						<div class="line-block__item">
							<?$APPLICATION->IncludeFile(SITE_DIR."include/required_message.php", Array(), Array("MODE" => "html"));?>
						</div>
					</div>
				</div>
	    	</form>
	    	<?if($arResult['PHONE_REGISTRATION']):?>
		    	<div id="bx_chpass_error" style="display:none"><?ShowError('error')?></div>
				<div id="bx_chpass_resend"></div>
			<?endif;?>
	    </div>
		<script>
		$(document).ready(function(){
			$('#changepasswd-page-form').validate({
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
				rules:{
					USER_CONFIRM_PASSWORD: {equalTo: '#pass'},
					<?if($arTheme['LOGIN_EQUAL_EMAIL']['VALUE'] === 'Y'):?>
					USER_LOGIN: {email: true}
					<?endif;?>
				}, messages:{USER_CONFIRM_PASSWORD: {equalTo: '<?=GetMessage('PASSWORDS_DONT_MATCH')?>'}}
			});

			setTimeout(function(){
				$('#changepasswd-page-form').find('input:visible').eq(0).focus();
			}, 50);
		})
		</script>
		<?if($arResult['PHONE_REGISTRATION']):?>
			<script>
			document.bform.USER_CHECKWORD.focus();

			new BX.PhoneAuth({
				containerId: 'bx_chpass_resend',
				errorContainerId: 'bx_chpass_error',
				interval: <?=$arResult['PHONE_CODE_RESEND_INTERVAL']?>,
				data:
					<?=CUtil::PhpToJSObject([
						'signedData' => $arResult['SIGNED_DATA']
					])?>,
				onError:
					function(response)
					{
						var errorNode = BX('bx_chpass_error');
						errorNode.innerHTML = '';
						for(var i = 0; i < response.errors.length; i++)
						{
							errorNode.innerHTML = errorNode.innerHTML + BX.util.htmlspecialchars(response.errors[i].message) + '<br />';
						}
						errorNode.style.display = '';
					}
			});
			</script>
		<?else:?>
			<script>document.bform.USER_PASSWORD.focus();</script>
		<?endif;?>
	<?endif;?>
</div>
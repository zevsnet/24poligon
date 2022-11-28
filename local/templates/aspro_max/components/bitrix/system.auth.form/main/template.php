<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(false);

Aspro\Max\PhoneAuth::modifyResult($arResult, $arParams);

if($arResult['PHONE_AUTH_PARAMS']['USE']){
	echo CJSCore::Init('phone_auth', true);
}
?>
<?/*<link rel="stylesheet" type="text/css" href="/bitrix/js/socialservices/css/ss.css">*/?>
<?if($arResult['FORM_TYPE'] === 'login'):?>
	<div id="ajax_auth" class="auth-page pk-page">
		<div class="auth_wrapp">
			<div class="wrap_md1">
				<div class="form">
					<form id="auth-page-form" name="system_auth_form<?=$arResult['RND']?>" method="post" target="_top" action="<?=$arParams['AUTH_URL']?>?login=yes">
						<?if($arResult['BACKURL'] <> ''):?>
							<input type="hidden" name="backurl" value="<?=$arResult['BACKURL']?>" />
						<?endif;?>
						<?foreach($arResult['POST'] as $key => $value):?>
							<?if(!in_array($key, array('captcha_word', 'Login', 'Login1'))):?>
								<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
							<?endif;?>
						<?endforeach;?>
						<input type="hidden" name="AUTH_FORM" value="Y" />
						<input type="hidden" name="TYPE" value="AUTH" />
						<input type="hidden" name="POPUP_AUTH" value="<?=$arParams['POPUP_AUTH']?>" />

						<div class="form_body">
							<?if($arResult['ERROR']):?>
								<div class="alert alert-danger compact"><?=$arResult['ERROR_MESSAGE']['MESSAGE']?></div>
							<?elseif($arResult['SHOW_SMS_FIELD']):?>
								<div class="alert alert-success compact"><?=GetMessage('auth_code_sent')?></div>
							<?endif;?>
							<?if($arResult['PHONE_AUTH_PARAMS']['USE']):?>
								<?if($arResult['SHOW_SMS_FIELD']):?>
									<input type="hidden" name="USER_PHONE_NUMBER" value="<?=htmlspecialcharsbx($arResult['USER_PHONE_NUMBER'])?>" />
									<input type="hidden" name="SIGNED_DATA" value="<?=htmlspecialcharsbx($arResult["SIGNED_DATA"])?>" />
									<div class="form-control">
										<label for="SMS_CODE_POPUP"><span><?=GetMessage('auth_sms_code')?>&nbsp;<span class="required-star">*</span></span></label>
										<input type="text" name="SMS_CODE" id="SMS_CODE_POPUP" class="form-control" maxlength="50" value="<?=htmlspecialcharsbx($arResult['SMS_CODE'])?>" autocomplete="off" tabindex="1" required />
									</div>
								<?else:?>
									<div class="form-control phone_or_login">
										<label for="AUTH_PHONE_OR_LOGIN"class=""><span><?=GetMessage('auth_phone_number_or_login')?>&nbsp;<span class="star">*</span></span></label>
										<label for="AUTH_PHONE_OR_LOGIN"class=""><span><?=GetMessage('auth_login')?>&nbsp;<span class="star">*</span></span></label>
										<label for="AUTH_PHONE_OR_LOGIN"class=""><span><?=GetMessage('auth_phone_number')?>&nbsp;<span class="star">*</span></span></label>
										<input id="AUTH_PHONE_OR_LOGIN" class="required" type="text" name="AUTH_PHONE_OR_LOGIN" maxlength="50" autocomplete="off" value="" tabindex="1" />
										<?=CMax::showIconSvg('cabinet', SITE_TEMPLATE_PATH.'/images/svg/inputlogin.svg', '', 'colored');?>
										<?=CMax::showIconSvg('cabinet', SITE_TEMPLATE_PATH.'/images/svg/inputphone.svg', '', 'colored');?>
									</div>
									<div class="form-control">
										<label for="USER_PASSWORD_POPUP"><span><?=GetMessage('auth_password2')?>&nbsp;<span class="required-star">*</span></span></label>
										<input type="password" name="USER_PASSWORD" id="USER_PASSWORD_POPUP" class="required password" maxlength="50" value="" autocomplete="off" tabindex="2"/>
									</div>
								<?endif;?>
							<?else:?>
								<div class="form-control">
									<label for="USER_LOGIN_POPUP"><span><?=GetMessage('auth_login')?>&nbsp;<span class="required-star">*</span></span></label>
									<input type="text" name="USER_LOGIN" id="USER_LOGIN_POPUP" class="required" maxlength="50" value="<?=$arResult['USER_LOGIN']?>" autocomplete="on" tabindex="1"/>
								</div>
								<div class="form-control">
									<label for="USER_PASSWORD_POPUP"><span><?=GetMessage('auth_password')?>&nbsp;<span class="required-star">*</span></span></label>
									<input type="password" name="USER_PASSWORD" id="USER_PASSWORD_POPUP" class="required password" maxlength="50" value="" autocomplete="off" tabindex="2"/>
								</div>
							<?endif;?>

							<?if($arResult['CAPTCHA_CODE']):?>
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
							<div class="filter block">
								<div class="prompt remember pull-left onoff">
									<input type="checkbox" id="USER_REMEMBER_frm" name="USER_REMEMBER" value="Y" tabindex="5" <?=(isset($_REQUEST['USER_REMEMBER']) && $_REQUEST['USER_REMEMBER'] === 'Y' ? 'checked' : '')?> />
									<label for="USER_REMEMBER_frm" title="<?=GetMessage('AUTH_REMEMBER_ME')?>" tabindex="5"><?=GetMessage('AUTH_REMEMBER_SHORT')?></label>
								</div>
								<?if(!$arResult['SHOW_SMS_FIELD']):?>
									<a class="forgot pull-right" href="<?=$arResult['AUTH_FORGOT_PASSWORD_URL']?>" tabindex="4"><?=GetMessage('AUTH_FORGOT_PASSWORD_2')?></a>
								<?endif;?>
								<div class="clearfix"></div>
							</div>
							<div class="buttons clearfix">
								<?if($arResult['PHONE_AUTH_PARAMS']['USE']):?>
									<?if($arResult['SHOW_SMS_FIELD']):?>
										<button class="btn btn-default btn-lg" type="submit" name="Login1" value="Y" tabindex="2"><span><?=GetMessage('AUTH_LOGIN_BUTTON')?></span></button>
									<?else:?>
										<button class="btn btn-default btn-lg" type="submit" name="Login1" value="Y" tabindex="2"><span><?=GetMessage('auth_password_continue')?></span></button>
										<button class="btn btn-default btn-lg hidden" type="submit" name="Login1" value="Y" tabindex="2"><span><?=GetMessage('AUTH_LOGIN_BUTTON')?></span></button>
										<button class="btn btn-default btn-lg hidden" type="submit" name="Login1" value="Y" tabindex="2"><span><?=GetMessage('auth_get_sms_code')?></span></button>
										<!--noindex--><a href="<?=$arResult['AUTH_REGISTER_URL'];?>" rel="nofollow" class="btn btn-transparent-border-color btn-lg pull-right register" tabindex="6"><?=GetMessage('AUTH_REGISTER_NEW')?></a><!--/noindex-->
									<?endif;?>
								<?else:?>
									<button class="btn btn-default btn-lg" type="submit" name="Login1" value="Y" tabindex="3"><span><?=GetMessage('AUTH_LOGIN_BUTTON')?></span></button>
									<!--noindex--><a href="<?=$arResult['AUTH_REGISTER_URL'];?>" rel="nofollow" class="btn btn-transparent-border-color btn-lg pull-right register" tabindex="6"><?=GetMessage('AUTH_REGISTER_NEW')?></a><!--/noindex-->
								<?endif;?>
								<input type="hidden" name="Login" value="Y" />
								<div class="clearboth"></div>
							</div>
							<?if($arResult['PHONE_AUTH_PARAMS']['USE'] && !$arResult['SHOW_SMS_FIELD']):?>
								<div class="licence_block hidden"><label><?$APPLICATION->IncludeFile(SITE_DIR."include/auth_phone_licenses_text.php", Array(), Array("MODE" => "html", "NAME" => "LICENSES"));?></label></div>
							<?endif;?>
							<?if($arResult['SHOW_SMS_FIELD']):?>
								<?$rand = rand(1, 99);?>
								<div id="bx_auth_error<?=$rand?>" style="display:none;"><?ShowError("error")?></div>
								<div id="bx_auth_resend<?=$rand?>"></div>
								<script>
								new BX.PhoneAuth({
									containerId: 'bx_auth_resend<?=$rand?>',
									errorContainerId: 'bx_auth_error<?=$rand?>',
									interval: <?=$arResult['PHONE_CODE_RESEND_INTERVAL']?>,
									data:
										<?=CUtil::PhpToJSObject([
											'signedData' => $arResult['SIGNED_DATA'],
										])?>,
									onError:
										function(response)
										{
											var errorDiv = BX('bx_auth_error<?=$rand?>');
											var errorNode = BX.findChildByClassName(errorDiv, 'errortext');
											errorNode.innerHTML = '';
											for(var i = 0; i < response.errors.length; i++)
											{
												errorNode.innerHTML = errorNode.innerHTML + BX.util.htmlspecialchars(response.errors[i].message) + '<br>';
											}
											errorDiv.style.display = '';
										}
								});
								</script>
							<?endif;?>
						</div>
					</form>
					<?if($arResult["AUTH_SERVICES"]):?>
						<div class="reg-new social_block">
							<div class="soc-avt">
								<div class="title"><?=GetMessage("SOCSERV_AS_USER_FORM");?></div>
								<?$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "icons",
									array(
										"AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
										"AUTH_URL" => SITE_DIR."auth/?login=yes",
										"POST" => $arResult["POST"],
										"SUFFIX" => "form",
									),
									$component, array("HIDE_ICONS"=>"Y")
								);
								?>
							</div>
						</div>
					<?endif;?>
				</div>
			</div>
		</div>
		<script>
		$(document).ready(function(){
			$('form[name=bx_auth_servicesform]').validate();
			$('.auth_wrapp .form_body a').removeAttr('onclick');

			$('#auth-page-form').validate({
				rules: {
					USER_LOGIN: {
						required: true
					}
				},
				submitHandler: function(form){
					var $form = $(form);
					if($form.valid()){
						/*var eventdata = {type: 'form_submit', form: form, form_name: 'AUTH'};
						BX.onCustomEvent('onSubmitForm', [eventdata]);*/

						var bCaptchaInvisible = false;
						if(window.renderRecaptchaById && window.asproRecaptcha && window.asproRecaptcha.key){
							if(window.asproRecaptcha.params.recaptchaSize == 'invisible' && $form.find('.g-recaptcha').length){
								if(!$form.find('.g-recaptcha-response').val()){
									if(typeof grecaptcha != 'undefined'){
										// there need to remove the second recaptcha on sibligs form
										$form.find('.g-recaptcha').remove();

										bCaptchaInvisible = true;
										grecaptcha.execute($form.find('.g-recaptcha').data('widgetid'));
									}
								}
							}
						}

						if(!bCaptchaInvisible){
							if($form.find('input[name=AUTH_PHONE_OR_LOGIN]').length && $form.find('input[name=USER_LOGIN]').length && $form.find('input[name=USER_PASSWORD]').length && !$form.find('input[name=USER_PASSWORD]').val().length){
								$form.find('input[name=AUTH_PHONE_OR_LOGIN]').closest('.form-control').hide();
								$form.find('input[name=USER_PASSWORD]').closest('.form-control').fadeIn();
								$form.find('input[name=USER_PASSWORD]').focus();
								$form.find('.form_footer .buttons button[type="submit"]').addClass('hidden').eq(1).removeClass('hidden');
							}
							else{
								var $button = $form.find('button[type=submit]:visible');
								if($button.length){
									if(!$button.hasClass('loadings')){
		  								$button.addClass('loadings');
		  								$form.closest('.form').addClass('sending');

										$.ajax({
											type: 'POST',
											url: $form.attr('action'),
											data: $form.serializeArray()
										}).done(function(html){
											if($(html).find('.alert').length){
												$('#ajax_auth').parent().html(html);
											}
											else{
												BX.reload(false);
											}
										});
									}
								}
							}
						}
					}
				},
				errorPlacement: function(error, element){
					$(error).attr('alt', $(error).text());
					$(error).attr('title', $(error).text());
					error.insertBefore(element);
				}
			});
		});

		setTimeout(function(){
			$('#auth-page-form').find('input:visible').eq(0).focus();
		}, 50);

		$('#auth-page-form .phone_or_login input').phoneOrLogin(function(input, test){
			var $form = $(input).closest('form');
			if(test.bPossiblePhone){
				if(!$form.find('input[name=USER_PHONE_NUMBER]').length){
					$form.find('input[name=USER_LOGIN]').remove();
					$form.find('input[name=USER_PASSWORD]').val('');
					$form.find('input[name=USER_PASSWORD]').prop('disabled', true);
					$form.find('.licence_block').removeClass('hidden')
					$form.find('.forgot').addClass('hidden')
					$form.prepend('<input type="hidden" name="USER_PHONE_NUMBER" />');
					$form.find('.form_footer .buttons button[type="submit"]').addClass('hidden').eq(2).removeClass('hidden');
				}
				$form.find('input[name=USER_PHONE_NUMBER]').val(test.value);
			}
			else{
				if(!$form.find('input[name=USER_LOGIN]').length){
					$form.find('input[name=USER_PHONE_NUMBER]').remove();
					$form.find('input[name=USER_PASSWORD]').prop('disabled', false);
					$form.find('.licence_block').addClass('hidden')
					$form.find('.forgot').removeClass('hidden');
					$form.prepend('<input type="hidden" name="USER_LOGIN" />');
					$form.find('.form_footer .buttons button[type="submit"]').addClass('hidden').eq(0).removeClass('hidden');
				}
				$form.find('input[name=USER_LOGIN]').val(test.value);
			}
		});
		</script>
	</div>
<?else:?>
	<script>
	BX.reload(true);
	</script>
<?endif;?>

<?// need pageobject.js for BX.reload()?>
<script>
BX.loadScript(['<?=Bitrix\Main\Page\Asset::getInstance()->getFullAssetPath('/bitrix/js/main/pageobject/pageobject.js')?>']);
</script>
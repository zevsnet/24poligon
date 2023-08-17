<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(false);

if($arResult['SHOW_SMS_FIELD']){
	CJSCore::Init('phone_auth');
}

global $arTheme;

// get phone auth params
list($bPhoneAuthSupported, $bPhoneAuthShow, $bPhoneAuthRequired, $bPhoneAuthUse) = Aspro\Max\PhoneAuth::getOptions();
?>
<div class="registraion-page pk-page">
	<?if($USER->IsAuthorized()):?>
		<p><?=GetMessage('MAIN_REGISTER_AUTH')?></p>
	<?else:?>
		<?if($arResult['ERRORS']):?>
			<?
			foreach($arResult['ERRORS'] as $key => $error){
				if(intval($key) == 0 && $key !== 0){
					$arResult['ERRORS'][$key] = str_replace('#FIELD_NAME#', $key.'&quot;'.GetMessage('REGISTER_FIELD_'.$key).'&quot;', $error);
				}
			}
			?>
			<div class="alert alert-danger compact"><?ShowError(implode('<br />', $arResult['ERRORS']))?></div>
		<?elseif($arResult['SHOW_SMS_FIELD']):?>
			<div class="alert alert-success compact"><?=GetMessage('main_register_sms_sended')?></div>
		<?endif;?>
	<?endif;?>

	<?if(empty($arResult['ERRORS']) && !empty($_POST['register_submit_button']) && $arResult['USE_EMAIL_CONFIRMATION'] === 'N' && !$arResult['SHOW_SMS_FIELD']):?>
		<?LocalRedirect(SITE_DIR.'personal/');?>
	<?elseif(empty($arResult['ERRORS']) && !empty($_POST['register_submit_button']) && $arResult['USE_EMAIL_CONFIRMATION'] === 'Y'):?>
		<div class="alert alert-success compact"><?=GetMessage('REGISTER_EMAIL_WILL_BE_SENT')?></div>
	<?else:?>
		<?if($arResult['SHOW_SMS_FIELD']):?>
			<div class="form">
				<form id="registraion-page-form" method="post" action="<?=POST_FORM_ACTION_URI?>" name="regform">
					<?if($arResult["BACKURL"] <> ''):?>
						<input type="hidden" name="backurl" value="<?=$arResult['BACKURL']?>" />
					<?endif;?>
					<input type="hidden" name="SIGNED_DATA" value="<?=htmlspecialcharsbx($arResult['SIGNED_DATA'])?>" />
					<div class="form_body">
						<div class="form-control">
							<?
							if(array_key_exists('SMS_CODE', $arResult['ERRORS'])){
								$class = 'class="error"';
							}
							?>
							<label for="input_SMS_CODE"><?=GetMessage('REGISTER_FIELD_SMS_CODE')?> <span class="star">*</span></label>
							<input id="input_SMS_CODE" class="required" size="30" type="text" name="SMS_CODE" value="<?=htmlspecialcharsbx($arResult['SMS_CODE'])?>" autocomplete="off" <?=$class?> />
							<div class="text-block"><?=GetMessage('REGISTER_FIELD_TEXT_SMS_CODE')?></div>
						</div>
					</div>
					<div class="form_footer">
						<button class="btn btn-default btn-lg" type="submit" name="code_submit_button" value="Y"><?=GetMessage('main_register_sms_send')?></button>
						<div class="clearboth"></div>
					</div>
				</form>
				<div id="bx_register_error" style="display:none"><?ShowError('error')?></div>
				<div id="bx_register_resend"></div>
				<script>
				$(document).ready(function(){
					$("form#registraion-page-form").validate();
				});

				new BX.PhoneAuth({
					containerId: 'bx_register_resend',
					errorContainerId: 'bx_register_error',
					interval: <?=$arResult['PHONE_CODE_RESEND_INTERVAL']?>,
					data:
						<?=CUtil::PhpToJSObject([
							'signedData' => $arResult['SIGNED_DATA'],
						])?>,
					onError:
						function(response)
						{
							var errorDiv = BX('bx_register_error');
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
			</div>
		<?else:?>
			<div class="form">
				<form id="registraion-page-form" method="post" action="<?=POST_FORM_ACTION_URI?>" name="regform" enctype="multipart/form-data" >
					<div class="top-text">
						<?$APPLICATION->IncludeFile(SITE_DIR."include/register_description.php", Array(), Array("MODE" => "html", "NAME" => ""));?>
					</div>
					<?if($arResult['BACKURL'] <> ''):?>
						<input type="hidden" name="backurl" value="<?=$arResult['BACKURL']?>" />
					<?endif;?>
					<input type="hidden" name="register_submit_button" value="reg" />
					<?
					$arTmpField=$arFields=$arUFields=array();
					$arTmpField=array_combine($arResult['SHOW_FIELDS'], $arResult['SHOW_FIELDS']);
					unset($arTmpField["PASSWORD"]);
					unset($arTmpField["CONFIRM_PASSWORD"]);

					if($arResult["USER_PROPERTIES"]["SHOW"] == "Y"){
						foreach($arParams["USER_PROPERTY"] as $name){
							$arUFields[$name]=$arResult["USER_PROPERTIES"]["DATA"][$name];
						}
					}

					if($arParams["SHOW_FIELDS"]){
						foreach($arParams["SHOW_FIELDS"] as $name){
							$arFields[$arTmpField[$name]]=$name;
						}
					}else{
						$arFields=$arTmpField;
					}
					$arFields["LOGIN"]="LOGIN";
					$arFields["PASSWORD"]="PASSWORD";
					$arFields["CONFIRM_PASSWORD"]="CONFIRM_PASSWORD";
					if($arTheme["PERSONAL_ONEFIO"]["VALUE"] == "Y")
					{
						if(isset($arFields["LAST_NAME"]))
							unset($arFields["LAST_NAME"]);
						if(isset($arFields["SECOND_NAME"]))
							unset($arFields["SECOND_NAME"]);
					}
					?>
					<div class="form_body">
						<?foreach($arFields as $FIELD):?>
							<?if($FIELD === 'PHONE_NUMBER'):?>
								<?continue;?>
							<?endif;?>

							<?if(($FIELD != "LOGIN" && $arTheme["LOGIN_EQUAL_EMAIL"]["VALUE"] == "Y") || $arTheme["LOGIN_EQUAL_EMAIL"]["VALUE"] != "Y"):?>
								<div class="form-control">
									<?
									$text = GetMessage("REGISTER_FIELD_".$FIELD);
									if($FIELD === "NAME"){
										if($arTheme["PERSONAL_ONEFIO"]["VALUE"] == "Y"){
											$text = GetMessage("REGISTER_FIELD_FIO");
										}
									}
									?>
									<label for="input_<?=$FIELD;?>"><span><?=$text?>&nbsp;<?if($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y"):?><span class="star">*</span><?endif;?></span></label>
							<?endif;?>
									<?if( array_key_exists( $FIELD, $arResult['ERRORS'] ) ):?>
										<?$class='class="error"'?>
									<?endif;?>

									<?switch ($FIELD){
										case "PASSWORD":?>
											<input size="30" type="password" id="input_<?=$FIELD;?>" name="REGISTER[<?=$FIELD?>]" required value="<?=$arResult["VALUES"][$FIELD]?>" autocomplete="off" class="password <?=(array_key_exists( $FIELD, $arResult['ERRORS'] ))? 'error': ''?>"  />

										<?break;
										case "CONFIRM_PASSWORD":?>
											<input size="30" type="password" id="input_<?=$FIELD;?>" name="REGISTER[<?=$FIELD?>]" required value="<?=$arResult["VALUES"][$FIELD]?>" autocomplete="off" class="confirm_password <?=(array_key_exists( $FIELD, $arResult['ERRORS'] ))? 'error': ''?>" />

										<?break;
										case "PERSONAL_GENDER":?>
											<select name="REGISTER[<?=$FIELD?>]" id="input_<?=$FIELD;?>">
												<option value=""><?=GetMessage("USER_DONT_KNOW")?></option>
												<option value="M"<?=$arResult["VALUES"][$FIELD] == "M" ? " selected=\"selected\"" : ""?>><?=GetMessage("USER_MALE")?></option>
												<option value="F"<?=$arResult["VALUES"][$FIELD] == "F" ? " selected=\"selected\"" : ""?>><?=GetMessage("USER_FEMALE")?></option>
											</select>
											<?break;
										case "PERSONAL_COUNTRY":
										case "WORK_COUNTRY":?>
											<select name="REGISTER[<?=$FIELD?>]" id="input_<?=$FIELD;?>">
												<?foreach ($arResult["COUNTRIES"]["reference_id"] as $key => $value){?>
													<option value="<?=$value?>"<?if ($value == $arResult["VALUES"][$FIELD]):?> selected="selected"<?endif?>><?=$arResult["COUNTRIES"]["reference"][$key]?></option>
												<?}?>
											</select>
											<?break;
										case "PERSONAL_PHOTO":
										case "WORK_LOGO":?>
											<input size="30" type="file" id="input_<?=$FIELD;?>" name="REGISTER_FILES_<?=$FIELD?>" />
											<?break;
										case "PERSONAL_NOTES":
										case "WORK_NOTES":?>
											<textarea cols="30" rows="5" id="input_<?=$FIELD;?>" name="REGISTER[<?=$FIELD?>]"><?=$arResult["VALUES"][$FIELD]?></textarea>

										<?case "PERSONAL_STREET":?>
											<textarea cols="30" rows="5" id="input_<?=$FIELD;?>" name="REGISTER[<?=$FIELD?>]"><?=$arResult["VALUES"][$FIELD]?></textarea>
											<?break;?>
										<?case "EMAIL":?>
											<input size="30" type="email" id="input_<?=$FIELD;?>" name="REGISTER[<?=$FIELD?>]" <?=($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y" ? "required": "");?> value="<?=$arResult["VALUES"][$FIELD]?>" <?=$class?> id="emails"/>
										<?break;?>
										<?case "NAME":?>
											<input size="30" type="text" id="input_<?=$FIELD;?>" name="REGISTER[<?=$FIELD?>]" <?=($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y" ? "required": "");?> value="<?=htmlspecialcharsbx($_REQUEST["REGISTER"]["NAME"])?>" <?=$class?>/>
										<?break;?>
										<?case "PERSONAL_PHONE":?>
											<input size="30" type="tel" id="input_<?=$FIELD;?>" name="REGISTER[<?=$FIELD?>]" class="phone_input <?=(array_key_exists( $FIELD, $arResult['ERRORS'] ))? 'error': ''?>" <?=($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y" ? "required": "");?> value="<?=$arResult["VALUES"][$FIELD]?>" />
										<?break;?>
										<?break;
										default:?>
											<?// hide login?>
											<input size="30" id="input_<?=$FIELD;?>" <?=(($FIELD == "LOGIN" && $arTheme["LOGIN_EQUAL_EMAIL"]["VALUE"] == "Y") ? 'type="hidden" value="1"' : 'type="text"');?> name="REGISTER[<?=$FIELD?>]" value="<?=$arResult["VALUES"][$FIELD]?>" />
											<?if ($FIELD == "PERSONAL_BIRTHDAY"){?>
												<?$APPLICATION->IncludeComponent(
													'bitrix:main.calendar',
													'',
													array(
														'SHOW_INPUT' => 'N',
														'FORM_NAME' => 'regform',
														'INPUT_NAME' => 'REGISTER[PERSONAL_BIRTHDAY]',
														'SHOW_TIME' => 'N'
													),
													null,
													array("HIDE_ICONS"=>"Y")
												);?>
											<?}?>
											<?break;?>
									<?}?>
							<?if(($FIELD != "LOGIN" && $arTheme["LOGIN_EQUAL_EMAIL"]["VALUE"] == "Y") || $arTheme["LOGIN_EQUAL_EMAIL"]["VALUE"] != "Y"):?>
									<?if(array_key_exists( $FIELD, $arResult['ERRORS'] ) ):?>
										<label class="error"><?=GetMessage("REGISTER_FILL_IT")?></label>
									<?endif;?>
									<?if(strlen($text = GetMessage("REGISTER_FIELD_TEXT_".($arTheme["LOGIN_EQUAL_EMAIL"]["VALUE"] != "Y" && $FIELD == 'EMAIL' ? $FIELD.'_SHORT' : $FIELD)))):?>
										<div class="text-block"><?=$text?></div>
									<?endif;?>
								</div>
							<?endif;?>
						<?endforeach;?>

						<?if($arUFields):?>
							<?foreach($arUFields as $arUField):?>
								<div class="form-control">
									<label><span><?=$arUField["EDIT_FORM_LABEL"];?>&nbsp;<?if ($arUField["MANDATORY"] == "Y"):?><span class="star">*</span><?endif;?></span></label>
									<?$APPLICATION->IncludeComponent(
									"bitrix:system.field.edit",
									$arUField["USER_TYPE"]["USER_TYPE_ID"],
									array("bVarsFromForm" => $arResult["bVarsFromForm"], "arUserField" => $arUField, "form_name" => "regform"), null, array("HIDE_ICONS"=>"Y"));?>
								</div>
							<?endforeach;?>
						<?endif;?>

						<?if($arResult['USE_CAPTCHA'] === 'Y'):?>
							<div class="clearboth"></div>
							<div class="form-control captcha-row clearfix">
								<label><span><?=GetMessage('REGISTER_CAPTCHA_PROMT')?>&nbsp;<span class="star">*</span></span></label>
								<div class="captcha_image">
									<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult['CAPTCHA_CODE']?>" border="0" />
									<input type="hidden" name="captcha_sid" value="<?=$arResult['CAPTCHA_CODE']?>" />
									<div class="captcha_reload"><?=GetMessage('RELOAD')?></div>
								</div>
								<div class="captcha_input">
									<input type="text" class="inputtext captcha" name="captcha_word" size="30" maxlength="50" value="" required />
								</div>
							</div>
							<div class="clearboth"></div>
						<?endif;?>
					</div>
					<div class="form_footer">
						<?if($arTheme['SHOW_LICENCE']['VALUE'] === 'Y'):?>
							<div class="licence_block filter label_block onoff">
								<input type="checkbox" id="licenses_register" <?=($arTheme['SHOW_LICENCE']['DEPENDENT_PARAMS']['LICENCE_CHECKED']['VALUE'] == 'Y' ? 'checked' : '');?> name="licenses_register" required value="Y">
								<label for="licenses_register">
									<?$APPLICATION->IncludeFile(SITE_DIR."include/licenses_text.php", Array(), Array("MODE" => "html", "NAME" => "LICENSES")); ?>
								</label>
							</div>
						<?endif;?>
						<div class="line-block form_footer__bottom">
							<div class="line-block__item">
								<button class="btn btn-default btn-lg" type="submit" name="register_submit_button1" value="Y"><?=GetMessage('REGISTER_REGISTER')?></button>
							</div>
							<div class="line-block__item">
								<?$APPLICATION->IncludeFile(SITE_DIR."include/required_message.php", Array(), Array("MODE" => "html"));?>
							</div>
						</div>
					</div>
				</form>
				<script>
				$(document).ready(function(){
					<?if($bPhoneAuthSupported && $bPhoneAuthShow):?>
					$('#registraion-page-form').submit(function(){
						$(this).find('[name=PHONE_NUMBER]').remove();
						var $phone = $('#input_PERSONAL_PHONE');
						if($phone.length){
							var phone = $phone.val();
							if(phone.length){
								$(this).append('<input type="hidden" name="REGISTER[PHONE_NUMBER]" value="' + phone + '" />');
							}
						}
					});
					<?endif;?>

					$('#registraion-page-form').validate({
						rules:{emails: 'email'},
						highlight: function( element ){
							$(element).parent().addClass('error');
						},
						unhighlight: function( element ){
							$(element).parent().removeClass('error');
						},
						submitHandler: function( form ){
							if($(form).valid()){
								var $button = $(form).find('button[type=submit]');
								if($button.length){
									if(!$button.hasClass('loadings')){
					  					$button.addClass('loadings');

										var eventdata = {type: 'form_submit', form: form, form_name: 'REGISTER'};
										BX.onCustomEvent('onSubmitForm', [eventdata]);
									}
					  			}
							}
						},
						errorPlacement: function(error, element){
							error.insertBefore(element);
						},
						messages:{
					      licenses_register: {
					        required : BX.message('JS_REQUIRED_LICENSES')
					      }
						}
					});

					$('#input_LOGIN').rules("add", {
						required: true,
						minlength: 3,
						messages:{
					    	minlength: jQuery.validator.format(BX.message('LOGIN_LEN'))
						}
					});

					$('form[name=bx_auth_servicesform_inline]').validate();

					setTimeout(function(){
						$('#registraion-page-form').find('input:visible').eq(0).focus();
					}, 50);

				});
				if (typeof appAspro === 'object' && appAspro && appAspro.phone) {
					appAspro.phone.init($('#registraion-page-form input.phone_input'), {
						coutriesData: '<?=CMax::$arParametrsList['FORMS']['OPTIONS']['USE_INTL_PHONE']['DEPENDENT_PARAMS']['PHONE_CITIES']['TYPE_SELECT']['SRC']?>',
						mask: arAsproOptions['THEME']['PHONE_MASK'],
						onlyCountries: '<?=CMax::GetFrontParametrValue('PHONE_CITIES');?>',
						preferredCountries: '<?=CMax::GetFrontParametrValue('PHONE_CITIES_FAVORITE');?>'
					})
				}
				</script>
			</div>
			<div class="social_block">
				<?$APPLICATION->IncludeComponent(
					"bitrix:system.auth.form",
					"popup",
					array(
						"TITLE" => "Авторизация",
						"PROFILE_URL" => $arParams["PATH_TO_PERSONAL"],
						"SHOW_ERRORS" => "Y",
						"POPUP_AUTH" => "Y"
					)
				);?>
			</div>
		<?endif;?>
	<?endif;?>
</div>
<?$arScripts = ['phone_input']?>
<?if (CMax::GetFrontParametrValue('USE_INTL_PHONE') === 'Y'):?>
	<?$arScripts[] = 'intl_phone_input'?>
<?elseif (CMax::GetFrontParametrValue('PHONE_MASK')):?>
	<?$arScripts[] = 'phone_mask'?>
<?endif;?>
<?\Aspro\Max\Functions\Extensions::init($arScripts);?>
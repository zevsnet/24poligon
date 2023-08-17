<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?
if (!strlen($arResult["FORM_NOTE"]) && $arParams['IGNORE_AJAX_HEAD'] !== 'Y') {
	$GLOBALS['APPLICATION']->ShowAjaxHead();
}
?>
<a href="#" class="close jqmClose"><?=CMax::showIconSvg('', SITE_TEMPLATE_PATH.'/images/svg/Close.svg')?></a>
<div class="form <?=$arResult["arForm"]["SID"]?>  ">
	<!--noindex-->
	<div class="form_head">
		<?if($arResult["isFormTitle"] == "Y"):?>
			<h2><?=$arResult["FORM_TITLE"]?></h2>
		<?endif;?>
		<?if($arResult["isFormDescription"] == "Y"):?>
			<div class="form_desc"><?=$arResult["FORM_DESCRIPTION"]?></div>
		<?endif;?>
	</div>
	<?if(strlen($arResult["FORM_NOTE"])){?>
		<div class="form_result <?=($arResult["isFormErrors"] == "Y" ? 'error' : 'success')?>">
			<?if($arResult["isFormErrors"] == "Y"):?>
				<?=$arResult["FORM_ERRORS_TEXT"]?>
			<?else:?>
				<?=CMax::showIconSvg(' colored', SITE_TEMPLATE_PATH.'/images/svg/success.svg')?>
				<span class="success_text">
					<?$successNoteFile = SITE_DIR."include/form/success_{$arResult["arForm"]["SID"]}.php";?>
					<?if(file_exists($_SERVER["DOCUMENT_ROOT"].$successNoteFile)):?>
					<?$APPLICATION->IncludeFile($successNoteFile, array(), array("MODE" => "html", "NAME" => "Form success note"));?>
					<?else:?>
						<?=GetMessage("FORM_SUCCESS");?>
					<?endif;?>
				</span>
				<script>
					if(arMaxOptions['THEME']['USE_FORMS_GOALS'] !== 'NONE')
					{
						var eventdata = {goal: 'goal_webform_success' + (arMaxOptions['THEME']['USE_FORMS_GOALS'] === 'COMMON' ? '' : '_<?=$arResult["arForm"]["ID"]?>')};
						BX.onCustomEvent('onCounterGoals', [eventdata]);
					}
					$(window).scroll();
				</script>
				<div class="close-btn-wrapper">
					<div class="btn btn-default btn-lg jqmClose"><?=GetMessage('FORM_CLOSE')?></div>
				</div>
			<?endif;?>
		</div>
	<?}else{?>
		<?if($arResult["isFormErrors"] == "Y"):?>
			<div class="form_body error"><?=$arResult["FORM_ERRORS_TEXT"]?></div>
		<?endif;?>
		<?=$arResult["FORM_HEADER"]?>
		<?=bitrix_sessid_post();?>
		<div class="form_body">
			<?if(is_array($arResult["QUESTIONS"])):?>
				<?foreach($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion):?>
					<?CMax::drawFormField($FIELD_SID, $arQuestion);?>
				<?endforeach;?>
			<?endif;?>
			<div class="clearboth"></div>
			<?$bHiddenCaptcha = (isset($arParams["HIDDEN_CAPTCHA"]) ? $arParams["HIDDEN_CAPTCHA"] : COption::GetOptionString("aspro.max", "HIDDEN_CAPTCHA", "Y"));?>
			<?if($arResult["isUseCaptcha"] == "Y"):?>
				<div class="form-control captcha-row clearfix">
					<label><span><?=GetMessage("FORM_CAPRCHE_TITLE")?>&nbsp;<span class="star">*</span></span></label>
					<div class="captcha_image">
						<img src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialcharsbx($arResult["CAPTCHACode"])?>" border="0" />
						<input type="hidden" name="captcha_sid" value="<?=htmlspecialcharsbx($arResult["CAPTCHACode"])?>" />
						<div class="captcha_reload"></div>
					</div>
					<div class="captcha_input">
						<input type="text" class="inputtext captcha" name="captcha_word" size="30" maxlength="50" value="" required />
					</div>
				</div>
			<?elseif($bHiddenCaptcha == "Y"):?>
				<textarea name="nspm" style="display:none;"></textarea>
			<?endif;?>
			<div class="clearboth"></div>
		</div>
		<div class="form_footer">
			<?$bShowLicenses = (isset($arParams["SHOW_LICENCE"]) ? $arParams["SHOW_LICENCE"] : COption::GetOptionString("aspro.max", "SHOW_LICENCE", "Y"));?>
			<?if($bShowLicenses == "Y"):?>
				<input type="hidden" name="aspro_max_form_validate" />
				<div class="licence_block filter onoff label_block">
					<input type="checkbox" id="licenses_popup" name="licenses_popup" <?=(COption::GetOptionString("aspro.max", "LICENCE_CHECKED", "N") == "Y" ? "checked" : "");?> required value="Y">
					<label for="licenses_popup">
						<?$APPLICATION->IncludeFile(SITE_DIR."include/licenses_text.php", Array(), Array("MODE" => "html", "NAME" => "LICENSES")); ?>
					</label>
				</div>
			<?endif;?>
			<div class="line-block form_footer__bottom">
				<div class="line-block__item">
					<button type="submit" class="btn btn-lg btn-default"><span><?=$arResult["arForm"]["BUTTON"]?></span></button>
				</div>
				<div class="line-block__item">
					<?$APPLICATION->IncludeFile(SITE_DIR."include/required_message.php", Array(), Array("MODE" => "html"));?>
				</div>
			</div>
			<input type="hidden" class="btn btn-default" value="<?=$arResult["arForm"]["BUTTON"]?>" name="web_form_submit">
		</div>
		<?=$arResult["FORM_FOOTER"]?>
	<?}?>
	<!--/noindex-->
	<script type="text/javascript">
	$(document).ready(function(){

		$('form[name="<?=$arResult["arForm"]["VARNAME"]?>"]').validate({
			highlight: function( element ){
				$(element).parent().addClass('error');
			},
			unhighlight: function( element ){
				$(element).parent().removeClass('error');
			},
			submitHandler: function( form ){
				if( $('form[name="<?=$arResult["arForm"]["VARNAME"]?>"]').valid() ){
					setTimeout(function() {
						$(form).find('button[type="submit"]').attr("disabled", "disabled");
					}, 500);
					var eventdata = {type: 'form_submit', form: form, form_name: '<?=$arResult["arForm"]["VARNAME"]?>'};
					BX.onCustomEvent('onSubmitForm', [eventdata]);
				}
			},
			errorPlacement: function( error, element ){
				error.insertBefore(element);
			},
			messages:{
		      licenses_popup: {
		        required : BX.message('JS_REQUIRED_LICENSES')
		      }
			}
		});

		$('input[type=file]:not(".uniform-ignore")').uniform({fileButtonHtml: BX.message('JS_FILE_BUTTON_NAME'), fileDefaultHtml: BX.message('JS_FILE_DEFAULT')});
		$(document).on('change', 'input[type=file]', function(){
			if($(this).val())
			{
				$(this).closest('.uploader').addClass('files_add');
			}
			else
			{
				$(this).closest('.uploader').removeClass('files_add');
			}
		})
		$('.form .add_file').on('click', function(){
			var index = $(this).closest('.input').find('input[type=file]').length+1;

			$(this).closest('.form-group').find('.input').append('<input type="file" id="POPUP_FILE" name="FILE_n'+index+'"   class="inputfile" value="" />');
			//$('<input type="file" id="POPUP_FILE" name="FILE_n'+index+'"   class="inputfile" value="" />').closest()($(this));
			$('input[type=file]:not(".uniform-ignore")').uniform({fileButtonHtml: BX.message('JS_FILE_BUTTON_NAME'), fileDefaultHtml: BX.message('JS_FILE_DEFAULT')});
		});

		$('.form .add_file').on('click', function(){
			var index = $(this).closest('.input').find('input[type=file]').length+1;

			$(this).closest('.form-group').find('.input').append('<input type="file" id="POPUP_FILE" name="FILE_n'+index+'"   class="inputfile" value="" />');
			//$('<input type="file" id="POPUP_FILE" name="FILE_n'+index+'"   class="inputfile" value="" />').closest()($(this));
			$('input[type=file]:not(".uniform-ignore")').uniform({fileButtonHtml: BX.message('JS_FILE_BUTTON_NAME'), fileDefaultHtml: BX.message('JS_FILE_DEFAULT')});
		});

		$('.form .add_text').on('click', function(){
			var input = $(this).closest('.form-group').find('input[type=text]').first(),
				index = $(this).closest('.form-group').find('input[type=text]').length,
				name = input.attr('id').split('POPUP_')[1];

			$(this).closest('.form-group').find('.input').append('<input type="text" id="POPUP_'+name+'" name="'+name+'['+index+']"  class="form-control " value="" />');
		});
			
		// $('.popup').jqmAddClose('a.jqmClose');
		$('.jqmClose').on('click', function(e){
			e.preventDefault();
			$(this).closest('.jqmWindow').jqmHide();
		})
		$('.popup').jqmAddClose('button[name="web_form_reset"]');
	});
	</script>
</div>
<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
    <script src="https://www.google.com/recaptcha/api.js"></script>
<?$frame = $this->createFrame()->begin('')?>
<?
$bLeftAndRight = false;
if(is_array($arResult["QUESTIONS"])){
	foreach($arResult["QUESTIONS"] as $arQuestion){
		if($arQuestion["STRUCTURE"][0]["FIELD_PARAM"] == 'left'){
			$bLeftAndRight = true;
			break;
		}
	}
}
?>
<div class="form inline <?=$arResult["arForm"]["SID"]?>">
	<!--noindex-->
	<div class="form_head">
		<?if($arResult["isFormTitle"] == "Y"):?>
			<h4><?=$arResult["FORM_TITLE"]?></h4>
		<?endif;?>
		<?if($arResult["isFormDescription"] == "Y"):?>
			<div class="form_desc"><?=$arResult["FORM_DESCRIPTION"]?></div>
		<?endif;?>
	</div>
	<?if($arResult["isFormErrors"] == "Y" || strlen($arResult["FORM_NOTE"])):?>
		<div class="form_result <?=($arResult["isFormErrors"] == "Y" ? 'error' : 'success')?>">
			<?if($arResult["isFormErrors"] == "Y"):?>
				<?=$arResult["FORM_ERRORS_TEXT"]?>
			<?else:?>
				<?$successNoteFile = SITE_DIR."include/form/success_{$arResult["arForm"]["SID"]}.php";?>
				<?if(file_exists($_SERVER["DOCUMENT_ROOT"].$successNoteFile)):?>
				<?$APPLICATION->IncludeFile($successNoteFile, array(), array("MODE" => "html", "NAME" => "Form success note"));?>
				<?else:?>
					<?=GetMessage("FORM_SUCCESS");?>
				<?endif;?>
			<?endif;?>
		</div>
	<?endif;?>
	<?=$arResult["FORM_HEADER"]?>
	<?=bitrix_sessid_post();?>
	<div class="form_body">
		<?if(is_array($arResult["QUESTIONS"])):?>
			<?if(!$bLeftAndRight):?>
				<?foreach($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion):?>
					<?COptimus::drawFormField($FIELD_SID, $arQuestion);?>
				<?endforeach;?>
			<?else:?>
				<div class="form_left">
					<?foreach($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion):?>
						<?if($arQuestion["STRUCTURE"][0]["FIELD_PARAM"] == 'left'):?>
							<?COptimus::drawFormField($FIELD_SID, $arQuestion);?>
						<?endif;?>
					<?endforeach;?>
				</div>
				<div class="form_right">
					<?foreach($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion):?>
						<?if($arQuestion["STRUCTURE"][0]["FIELD_PARAM"] != 'left'):?>
							<?COptimus::drawFormField($FIELD_SID, $arQuestion);?>
						<?endif;?>
					<?endforeach;?>
				</div>
			<?endif;?>
		<?endif;?>
		<div class="clearboth"></div>
<!--		--><?//if($arResult["isUseCaptcha"] == "Y"):?>
            <div class="g-recaptcha" data-sitekey="6LeoppoUAAAAABMxNIvFwAa3iwA_RF8zbMJYI_gL"></div>

<!--		--><?//endif;?>
		<div class="clearboth"></div>
	</div>
    <?$APPLICATION->IncludeComponent(
        "bitrix:main.userconsent.request",
        "",
        Array(
            "AUTO_SAVE" => "Y",
            "ID" => "1",
            "IS_CHECKED" => "Y",
            "IS_LOADED" => "N"
        )
    );?>
	<div class="form_footer">

		<button type="submit" class="button medium" value="submit" name="web_form_submit" ><span><?=$arResult["arForm"]["BUTTON"]?></span></button>
		<button type="reset" class="button medium transparent" value="reset" name="web_form_reset" ><span><?=GetMessage('FORM_RESET')?></span></button>
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
						form.submit();
						setTimeout(function() {
							$(form).find('button[type="submit"]').attr("disabled", "disabled");
						}, 300);
					}
				},
				errorPlacement: function( error, element ){
					error.insertBefore(element);
				}
			});
			
			if(arOptimusOptions['THEME']['PHONE_MASK'].length){
				var base_mask = arOptimusOptions['THEME']['PHONE_MASK'].replace( /(\d)/g, '_' );
				$('form[name=<?=$arResult["arForm"]["VARNAME"]?>] input.phone, form[name=<?=$arResult["arForm"]["VARNAME"]?>] input[data-sid=PHONE]').inputmask('mask', {'mask': arOptimusOptions['THEME']['PHONE_MASK'] });
				$('form[name=<?=$arResult["arForm"]["VARNAME"]?>] input.phone, form[name=<?=$arResult["arForm"]["VARNAME"]?>] input[data-sid=PHONE]').blur(function(){
					if( $(this).val() == base_mask || $(this).val() == '' ){
						if( $(this).hasClass('required') ){
							$(this).parent().find('label.error').html(BX.message('JS_REQUIRED'));
						}
					}
				});
			}
		});
		</script>
	</div>
	<?=$arResult["FORM_FOOTER"]?>
	<!--/noindex-->
</div>
    <script type="text/javascript">
        var onloadCallback = function() {
            alert("grecaptcha is ready!");
        };
    </script>
    <script src="https://www.google.com/recaptcha/api.js?onload=reCaptch2&render=explicit"
            async defer>
    </script>
    <script>

        var reCaptch2 = function(token) {
            var data = new FormData();
            data.append("token", token);
            data.append("action", 'contacts');

            fetch('/ajax/Main/reCaptcha3', {
                method: 'POST',
                body: data
            }).then(function(response) {

                response.json().then(function(data) {

                    if (!data['status'])
                        $('.form.inline.FEEDBACK').remove()
                });
            });
        }
    </script>
<?$frame->end()?>
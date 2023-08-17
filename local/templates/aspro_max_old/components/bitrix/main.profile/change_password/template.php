<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
	global $USER;
	$rsUser = CUser::GetByID($USER->GetID());
	$arUser = $rsUser->Fetch();
	if ($arUser["EXTERNAL_AUTH_ID"]=="socservices") { LocalRedirect(SITE_DIR."personal/personal-data/"); }
?>
<div class="module-form-block-wr lk-page border_block passw">
	<?if($arResult["strProfileError"]):?>
		<?//ShowError($arResult["strProfileError"]);?>
		<div class="alert alert-danger compact"><?=$arResult["strProfileError"]?></div>
	<?endif;?>
	<?if($arResult['DATA_SAVED'] === 'Y'):?>
		<div class="alert alert-success compact"><?=GetMessage('PROFILE_DATA_SAVED')?></div>
	<?endif;?>
	<div class="form-block-wr">
		<form method="post" name="form1" action="<?=$arResult["FORM_TARGET"]?>?" enctype="multipart/form-data">
			<?=$arResult["BX_SESSION_CHECK"]?>
			<input type="hidden" name="LOGIN" maxlength="50" value="<?=$arResult["arUser"]["LOGIN"]?>" />
			<input type="hidden" name="EMAIL" maxlength="50" placeholder="name@company.ru" value="<?=$arResult["arUser"]["EMAIL"]?>" />

			<input type="hidden" name="lang" value="<?=LANG?>" />
			<input type="hidden" name="ID" value=<?=$arResult["ID"]?> />

			<div class="form-control">
				<div class="wrap_md">
					<div class="iblock label_block">
						<label><?=GetMessage('NEW_PASSWORD_REQ')?><span class="star">*</span></label>
						<input required type="password" name="NEW_PASSWORD" maxlength="50" value="" id='pass' autocomplete="off" class="bx-auth-input password" />
					</div>
					<div class="iblock text_block">
						<div class="pr"><?=GetMessage("PASSWORD_MIN_LENGTH")?></div>
					</div>
				</div>
			</div>

			<div class="form-control">
				<div class="wrap_md">
					<div class="iblock label_block">
						<label><?=GetMessage('NEW_PASSWORD_CONFIRM')?><span class="star">*</span></label>
						<input required type="password" name="NEW_PASSWORD_CONFIRM" maxlength="50" value="" autocomplete="off" />
					</div>
				</div>
			</div>

			<div class="but-r">
				<div class="line-block form_footer__bottom">
					<div class="line-block__item">
						<button class="btn btn-default btn-lg" type="submit" name="save" value="<?=(($arResult["ID"]>0) ? GetMessage("SAVE") : GetMessage("ADD"))?>"><span><?=(($arResult["ID"]>0) ? GetMessage("SAVE") : GetMessage("ADD"))?></span></button>
					</div>
					<div class="line-block__item">
						<?$APPLICATION->IncludeFile(SITE_DIR."include/required_message.php", Array(), Array("MODE" => "html"));?>
					</div>
				</div>
			</div>
		</form>

	</div>
	<script>
	$(document).ready(function(){
		$('.form-block-wr form').validate({
			rules:{NEW_PASSWORD_CONFIRM: {equalTo: '#pass'}},
			messages:{NEW_PASSWORD_CONFIRM: {equalTo: '<?=GetMessage('PASSWORDS_DOES_NOT_MATCH')?>'}}
		});
	})
	</script>
</div>
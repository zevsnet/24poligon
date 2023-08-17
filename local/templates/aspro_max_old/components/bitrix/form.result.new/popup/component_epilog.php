<?global $USER;?>
<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("form-block".$arParams["WEB_FORM_ID"]);?>
<?if($USER->IsAuthorized()):?>
	<?
	$dbRes = CUser::GetList(($by = "id"), ($order = "asc"), array("ID" => $USER->GetID()), array("FIELDS" => array("ID", "PERSONAL_PHONE")));
	$arUser = $dbRes->Fetch();
	?>
	<script type="text/javascript">
	// $(document).ready(function() {
		try{
			$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=CLIENT_NAME], .form.<?=$arResult["arForm"]["SID"]?> input[data-sid=FIO], .form.<?=$arResult["arForm"]["SID"]?> input[data-sid=NAME]').val('<?=$USER->GetFullName()?>');
			$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=PHONE]').val('<?=$arUser['PERSONAL_PHONE']?>');
			$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=EMAIL]').val('<?=$USER->GetEmail()?>');
			$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid=PHONE]').trigger('change');
		}
		catch(e){
		}
	// });
	</script>
<?endif;?>
<script type="text/javascript">
	if (typeof appAspro === 'object' && appAspro && appAspro.phone) {
		appAspro.phone.init($('form[name=<?=$arResult["arForm"]["VARNAME"]?>] input.phone'), {
			coutriesData: '<?=CMax::$arParametrsList['FORMS']['OPTIONS']['USE_INTL_PHONE']['DEPENDENT_PARAMS']['PHONE_CITIES']['TYPE_SELECT']['SRC']?>',
			mask: arAsproOptions['THEME']['PHONE_MASK'],
			onlyCountries: '<?=CMax::GetFrontParametrValue('PHONE_CITIES');?>',
			preferredCountries: '<?=CMax::GetFrontParametrValue('PHONE_CITIES_FAVORITE');?>'
		})
	}
	$(document).ready(function() {
		$('.form.<?=$arResult["arForm"]["SID"]?> input[data-sid="PRODUCT_NAME"]').attr('value', $('h1').text());
	});
</script>
<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("form-block".$arParams["WEB_FORM_ID"], "");?>

<?$arScripts = ['phone_input']?>
<?if (CMax::GetFrontParametrValue('USE_INTL_PHONE') === 'Y'):?>
	<?$arScripts[] = 'intl_phone_input'?>
<?elseif (CMax::GetFrontParametrValue('PHONE_MASK')):?>
	<?$arScripts[] = 'phone_mask'?>
<?endif;?>

<?\Aspro\Max\Functions\Extensions::initInPopup($arScripts);?>
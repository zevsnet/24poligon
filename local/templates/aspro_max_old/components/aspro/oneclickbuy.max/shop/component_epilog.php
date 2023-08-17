<?global $USER;?>
<?if($USER->IsAuthorized()):?>
	<?
	$dbRes = CUser::GetList(($by = "id"), ($order = "asc"), array("ID" => $USER->GetID()), array("FIELDS" => array("ID", "PERSONAL_PHONE")));
	$arUser = $dbRes->Fetch();
	?>
	<script type="text/javascript">
	// $(document).ready(function() {
		$('#one_click_buy_id_FIO').val('<?=$USER->GetFullName()?>');
		$('#one_click_buy_id_PHONE').val('<?=$arUser['PERSONAL_PHONE']?>');
		$('#one_click_buy_id_EMAIL').val('<?=$USER->GetEmail()?>');
	// });
	</script>
<?endif;?>
<script type="text/javascript">
	if (typeof appAspro === 'object' && appAspro && appAspro.phone) {
		appAspro.phone.init($('#one_click_buy_id_PHONE'), {
			coutriesData: '<?=CMax::$arParametrsList['FORMS']['OPTIONS']['USE_INTL_PHONE']['DEPENDENT_PARAMS']['PHONE_CITIES']['TYPE_SELECT']['SRC']?>',
			mask: arAsproOptions['THEME']['PHONE_MASK'],
			onlyCountries: '<?=CMax::GetFrontParametrValue('PHONE_CITIES');?>',
			preferredCountries: '<?=CMax::GetFrontParametrValue('PHONE_CITIES_FAVORITE');?>'
		})
	}
</script>

<?$arScripts = ['phone_input']?>
<?if (CMax::GetFrontParametrValue('USE_INTL_PHONE') === 'Y'):?>
	<?$arScripts[] = 'intl_phone_input'?>
<?elseif (CMax::GetFrontParametrValue('PHONE_MASK')):?>
	<?$arScripts[] = 'phone_mask'?>
<?endif;?>

<?\Aspro\Max\Functions\Extensions::initInPopup($arScripts);?>
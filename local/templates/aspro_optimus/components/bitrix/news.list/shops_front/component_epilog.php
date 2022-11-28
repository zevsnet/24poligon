<?global $TEMPLATE_OPTIONS;?>
<script type="text/javascript">
$(document).ready(function() {
	$('.stores.news').parents('.block_wr').addClass('<?=strtolower($TEMPLATE_OPTIONS["STORES"]["CURRENT_VALUE"])?>');
});
</script>
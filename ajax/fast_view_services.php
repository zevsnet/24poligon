<?define("STATISTIC_SKIP_ACTIVITY_CHECK", "true");?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
if(isset($_GET['iblock_id']) && $_GET['iblock_id'])
{
	$url = htmlspecialcharsbx(urldecode($_GET['item_href']));
?>

	<script>
		var objUrl = parseUrlQuery(),
			add_url = '<?=(strpos($url, '?') !== false ? '&' : '?')?>FAST_VIEW=Y';
		if('clear_cache' in objUrl)
		{
			if(objUrl.clear_cache == 'Y')
				add_url += '&clear_cache=Y';
		}
		$('.fast_view_services').addClass('loading_block');
		BX.ajax({
			url: <?var_export($url)?>+add_url,
			method: 'POST',
			data: BX.ajax.prepareData({'FAST_VIEW':'Y'}),
			dataType: 'html',
			processData: false,
			start: true,
			headers: [{'name': 'X-Requested-With', 'value': 'XMLHttpRequest'}],
			onfailure: function(data) {
				alert('Error connecting server');
			},
			onsuccess: function(html){
				var ob = BX.processHTML(html);				
				// inject
				BX('fast_view_services').innerHTML = ob.HTML;
				BX.ajax.processScripts(ob.SCRIPT);	
			}
		})
		<?if(!$GLOBALS['bMobileForm']):?>
			$(document).on('click', '.jqmClose', function(e){
				e.preventDefault();
				$(this).closest('.jqmWindow').jqmHide();
			})
		<?endif;?>
	</script>
	<div id="fast_view_services"></div>
<?}?>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
\Bitrix\Main\Data\StaticHtmlCache::getInstance()->markNonCacheable();
$APPLICATION->SetTitle("Заполнение формы");
?>
<style type="text/css">
section.page-top {display:none;}
.form.inline{margin-top:14px;}
.form .form-header{border-top: 1px solid #f5f5f5 !important;padding: 15px 0px 9px 0px;}
.catalog-delivery,.basket-share{margin-top:30px;}
body .popup-intro .pop-up-title{padding-top:34px;padding-bottom:26px;font-size:1.333em;line-height:1.126em;color:#333;font-weight:normal;}
.popup-intro{padding-right:49px;padding-left:16px;}
.form-wr{padding-left:16px;padding-right:16px;}
.TABLES_SIZE_frame{padding-left:16px;padding-right:16px;padding-top:35px;}
.inline.close ~ div .form_head{padding-right:29px;}
.inline.close ~ div .catalog-delivery-title,.inline.close ~ div .basket-share-title{padding-right:48px;}
</style>
<?
CMax::GetFrontParametrValue('', SITE_ID);

$form_id = isset($_REQUEST["form_id"]) ? $_REQUEST["form_id"] : 1;
if(\Bitrix\Main\Loader::includeModule("aspro.max"))
	CMax::GetValidFormIDForSite($form_id);
$successMessage = ($isCallBack ? "<p>Наш менеджер перезвонит вам в ближайшее время.</p><p>Спасибо за ваше обращение!</p>" : "Спасибо! Ваше сообщение отправлено!");
$arDataTrigger = json_decode((isset($_REQUEST["data-trigger"]) ? $_REQUEST["data-trigger"] : '{}'), true); // allways UTF-8
$url_sizes = (htmlspecialchars($_REQUEST['url']) ? htmlspecialchars($_REQUEST['url']) : '');

$bAuth = isset($_REQUEST['type']) && $_REQUEST['type'] == 'auth';
$backUrl = $bAuth ? false : (isset($_REQUEST['backurl']) ? $_REQUEST['backurl'] : ($_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : false));
$GLOBALS['bMobileForm'] = true;
$GLOBALS['backUrl'] = $backUrl;
?>
<?if($backUrl):?>
	<a href="<?=$backUrl?>" title="Вернуться назад" class="inline close jqmClose"><span><?=CMax::showIconSvg('', SITE_TEMPLATE_PATH.'/images/svg/Close.svg')?></span></a>
	<script>
	$(document).ready(function(){
		var $form = $('.middle .maxwidth-theme form');
		if($form.length){
			if(!$form.find('input[name="backurl"]').length){
				$form.prepend('<input type="hidden" name="backurl" />');
			}

			$form.find('input[name="backurl"]').val('<?=$backUrl?>');
		}
	});
	</script>
<?endif;?>
<?if($bAuth):?>
	<?include_once('../ajax/auth.php');?>
<?elseif(isset($_REQUEST['type']) && $_REQUEST['type'] == 'subscribe'):?>
	<?include_once('../ajax/subscribe_news.php');?>
<?elseif(isset($_REQUEST['type']) && $_REQUEST['type'] == 'review'):?>
	<?include_once('../ajax/review.php');?>
<?elseif($form_id == 'fast_view'):?>
	<?include_once('../ajax/fast_view.php');?>
<?elseif($form_id == 'fast_view_sale'):?>
	<?include_once('../ajax/fast_view_sale.php');?>
<?elseif($form_id == 'TABLES_SIZE'):?>
	<div class="TABLES_SIZE_frame swipeignore">
	<div class="form">
		<div class="form_head">
			<h2><?=\Bitrix\Main\Localization\Loc::getMessage('TABLES_SIZE_TITLE');?></h2>
		</div>
		<div class="form_body">
			<?include('../'.$url_sizes);?>
		</div>
	</div>
	</div>
<?elseif($form_id == 'delivery'):?>
	<?include('../ajax/delivery.php');?>
<?elseif($form_id == 'share_basket'):?>
	<?include('../ajax/share_basket.php');?>
<?elseif(isset($_REQUEST['type']) && $_REQUEST['type'] == 'marketing'):?>
	<?include('marketing.php');?>
<?elseif($form_id == 'city_chooser'):?>
	<?\Bitrix\Main\Data\StaticHtmlCache::getInstance()->markNonCacheable();
$APPLICATION->SetTitle("Выбор города");?>
	<?include_once('../ajax/city_chooser.php');?>
<?elseif($form_id == 'ocb'):?>
	<style type="text/css">
	.popup-intro .pop-up-title{padding: 30px 0px;font-size: 20px;font-weight: bold;color:#222;}
	</style>
	<?if($_REQUEST['name'] == 'one_click_buy'):?>
		<?include_once('../ajax/one_click_buy.php');?>
	<?else:?>
		<?include_once('../ajax/one_click_buy_basket.php');?>
	<?endif;?>
<?elseif($form_id):?>
	<?
	$APPLICATION->IncludeComponent(
		"bitrix:form",
		"inline",
		Array(
			"AJAX_MODE" => "Y",
			"SEF_MODE" => "N",
			"WEB_FORM_ID" => $form_id,
			"START_PAGE" => "new",
			"SHOW_LIST_PAGE" => "N",
			"SHOW_EDIT_PAGE" => "N",
			"SHOW_VIEW_PAGE" => "N",
			"SUCCESS_URL" => "",
			"SHOW_ANSWER_VALUE" => "N",
			"SHOW_ADDITIONAL" => "N",
			"SHOW_STATUS" => "N",
			"EDIT_ADDITIONAL" => "N",
			"EDIT_STATUS" => "Y",
			"HIDE_SUCCESS" => "Y",
			"NOT_SHOW_FILTER" => "",
			"NOT_SHOW_TABLE" => "",
			"CHAIN_ITEM_TEXT" => "",
			"CHAIN_ITEM_LINK" => "",
			"IGNORE_CUSTOM_TEMPLATE" => "N",
			"USE_EXTENDED_ERRORS" => "Y",
			"CACHE_GROUPS" => "N",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "3600000",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"SHOW_LICENCE" => CMax::GetFrontParametrValue('SHOW_LICENCE'),
			"HIDDEN_CAPTCHA" => CMax::GetFrontParametrValue('HIDDEN_CAPTCHA'),
			"AJAX_OPTION_HISTORY" => "N",
			"VARIABLE_ALIASES" => Array(
				"action" => "action"
			)
		)
	);?>
	<?if($arDataTrigger && strlen($name)):?>
		<script type="text/javascript">
		var name = '<?=$name?>';
		var arTriggerAttrs = <?=json_encode($arDataTrigger)?>;
		$(document).ready(function() {
			$('.jqmClose').click(function(){
				history.back();
			})
			$.each(arTriggerAttrs, function(index, val){
				if( /^data\-autoload\-(.+)$/.test(index)){
					var key = index.match(/^data\-autoload\-(.+)$/)[1];
					var el = $('input[data-sid="'+key.toUpperCase()+'"]');
					if(el.closest('.form-group').length)
						el.closest('.form-group').addClass('input-filed');
					el.val(val).attr('readonly', 'readonly').attr('title', val);
				}
			});

			if(name == 'order_product'){
				if(arTriggerAttrs['data-product'].length){
					$('input[name="PRODUCT"]').val(arTriggerAttrs['data-product']).attr('readonly', 'readonly').attr('title', arTriggerAttrs['data-product']);
				}
			}
		});
		</script>
	<?endif;?>
<?else:?>
	<div class="alert alert-warning">Не указан ID формы</div>
	<?CMax::goto404Page();?>
<?endif;?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
<!-- noindex -->
<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$bShowBasketPrint = trim(COption::GetOptionString("aspro.mshop", "SHOW_BASKET_PRINT", "N", SITE_ID)) === "Y";
$hrefBasketPrint = (!$arParams['INNER'] ? $APPLICATION->GetCurUri('print=', false) : $_SERVER['HTTP_REFERER'].(strpos($_SERVER['HTTP_REFERER'], '?') !== false ? '&' : '?').'print=');
?>
<?if($arParams['INNER']!==true):?>
	<div id="basket-replace" class="ajax_reload">
<?endif;?>

<script src="<?=(((COption::GetOptionString('main', 'use_minified_assets', 'N', SITE_ID) === 'Y') && file_exists($_SERVER['DOCUMENT_ROOT'].$templateFolder.'/script.min.js')) ? $templateFolder.'/script.min.js' : $templateFolder.'/script.js')?>" type="text/javascript"></script>
<?
	include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/functions.php");
	$arUrls = Array("delete" => $APPLICATION->GetCurPage()."?action=delete&id=#ID#",
					"delay" => $APPLICATION->GetCurPage()."?action=delay&id=#ID#",
					"add" => $APPLICATION->GetCurPage()."?action=add&id=#ID#");
?>
<?if(strlen($arResult["ERROR_MESSAGE"]) <= 0):?>
	<?
		if (is_array($arResult["WARNING_MESSAGE"]) && !empty($arResult["WARNING_MESSAGE"])) {
			foreach ($arResult["WARNING_MESSAGE"] as $msg) {
				echo ShowError($msg);
			}
		}

		$normalCount    = count($arResult["ITEMS"]["AnDelCanBuy"]);
		$delayCount     = count($arResult["ITEMS"]["DelDelCanBuy"]);

		$arMenu = array(
			array("ID"=>"AnDelCanBuy", "TITLE"=>GetMessage("SALE_BASKET_ITEMS"), "COUNT"=>$normalCount, "SELECTED" => true , "FILE"=>"/basket_items.php")
		);

		if ($delayCount) {
			$arMenu[] = array("ID"=>"DelDelCanBuy", "TITLE"=>GetMessage("SALE_BASKET_ITEMS_DELAYED"), "COUNT"=>$delayCount, "FILE"=>"/basket_items_delayed.php");
		}

		if($_REQUEST["section"]=="delay"){
			foreach($arMenu as $key => $arElement) {
				if ($arElement["ID"]=="DelDelCanBuy") {
					$arMenu[$key]["SELECTED"]=true;
				} else {
					$arMenu[$key]["SELECTED"]=false;
				}
			}
		}
		$paramsString = urlencode(serialize($arParams));

?>

	<form method="post" action="<?=POST_FORM_ACTION_URI?>" name="basket_form" id="basket_form" class="basket_wrapp">
		<input id="main_basket_params" type="hidden" name="PARAMS" value='<?=$paramsString?>' />
		<input id="cur_page" type="hidden" name="CUR_PAGE" value='<?=$APPLICATION->GetCurPage()?>' />
		<div id="basket_sort" class="basket_sort">
			<ul class="tabs">
				<?foreach($arMenu as $key => $arElement){?>
					<li<?=($arElement["SELECTED"] ? ' class="cur"' : '');?> item-section="<?=$arElement["ID"]?>" data-hash="tab_<?=$arElement["ID"]?>"  data-type="<?=$arElement["ID"]?>">
						<div class="wrap_li">
							<span><?=$arElement["TITLE"]?></span>
							<span class="quantity">&nbsp;(<span class="count"><?=$arElement["COUNT"]?></span>)</span>
						</div>
					</li>
				<?}?>
			</ul>
			<span class="wrap_remove_button">
				<?if($normalCount){?>
					<span class="button grey_br transparent remove_all_basket AnDelCanBuy cur" data-type="basket" data-href="<?=$APPLICATION->GetCurPage();?>"><?=GetMessage('CLEAR_ALL_BASKET')?></span>
				<?}?>
				<?if($delayCount){?>
					<span class="button grey_br transparent remove_all_basket DelDelCanBuy" data-type="delay" data-href="<?=$APPLICATION->GetCurPage();?>"><?=GetMessage('CLEAR_ALL_BASKET')?></span>
				<?}?>
			</span>
		</div>
		<ul class="tabs_content basket">
			<?foreach($arMenu as $key => $arElement){?>
				<li <?=($arElement["SELECTED"] ? ' class="cur"' : '');?> item-section="<?=$arElement["ID"]?>"><?include($_SERVER["DOCUMENT_ROOT"].$templateFolder.$arElement["FILE"]);?></li>
			<?}?>
		</ul>
	</form>

	<script>

		$("#basket_form").ready(function(){
			if (!$(".tabs > li.cur").length)
			{
				$.cookie("MSHOP_BASKET_OPEN_TAB",  $(".tabs_content > li").first().attr("item-section"));
				$(".tabs > li").first().addClass("cur");
				$(".tabs_content > li").first().addClass("cur");
			}
		});

		$(window).load(function(){
			if(location.hash){
				var hash = location.hash.split( '#' )[1];
				$(".tabs li[data-hash="+hash+"]").trigger('click');
			}
		});

		$(window).on('popstate', function(){
			var hash = location.hash.split('#')[1];
			$(".tabs li[data-hash="+hash+"]").trigger('click');
		});

		$(".tabs > li").live("click", function(){
			if (!$(this).is(".cur")){
				$.cookie("MSHOP_BASKET_OPEN_TAB",  $(this).attr("item-section"));
				$(this).siblings().removeClass("cur");
				$(this).addClass("cur");
				$(".tabs_content > li").removeClass("cur");
				$(".basket_sort .remove_all_basket").removeClass("cur");
				$(".tabs_content > li:eq("+$(this).index()+")").addClass("cur");
				$(".basket_sort .remove_all_basket."+$(this).data('type')).addClass("cur");
				location.hash="#"+$(this).data("hash");
			}
		});

		<?if($arParams["AJAX_MODE_CUSTOM"] == "Y"):?>
			$("#basket_form").ready(function(){
				$('form[name^=basket_form] .apply-button').click(function(e){
					e.preventDefault();
					if($(this).closest('.input_coupon').find('input').val().length){
						$('form[name^=basket_form]').prepend('<input type="hidden" name="BasketRefresh" value="Y" />');
						jsAjaxUtil.ShowLocalWaitWindow( 'id', 'basket_form', true );
						$.ajax({
							url: arOptimusOptions['SITE_DIR']+'basket/',
							type: 'POST',
							data: $("form[name^=basket_form]").serialize(),
							complete: function() {
								jsAjaxUtil.CloseLocalWaitWindow( 'id', 'basket_form', true );
							},
							success: function(html) {
								$("#basket-replace").html(html);
							}
						});
					}
					else{
						$(this).closest('.input_coupon').find('input').addClass('error');
						if(!$(this).closest('.input_coupon').find('.input label.error').size()){
							$("<label class='error'>"+BX.message("INPUT_COUPON")+"</label>").insertBefore($(this).closest('.input_coupon').find('input'));
						}
					}
				});

				$('.bx_ordercart_coupon .del_btn').click(function(e){
					$('form[name^=basket_form]').prepend('<input type="hidden" name="BasketRefresh" value="Y" />');
					$('form[name^=basket_form]').prepend('<input type="hidden" name="delete_coupon" value="'+$(this).data('coupon')+'" />');
					jsAjaxUtil.ShowLocalWaitWindow( 'id', 'basket_form', true );
					$.ajax({
						url: arOptimusOptions['SITE_DIR']+'basket/',
						type: 'POST',
						data: $("form[name^=basket_form]").serialize(),
						complete: function() {
							jsAjaxUtil.CloseLocalWaitWindow( 'id', 'basket_form', true );
						},
						success: function(html) {
							$("#basket-replace").html(html);
						}
					});
				})

				$('.basket_sort .remove_all_basket').click(function(e){
					if(!$(this).hasClass('disabled')){
						$(this).addClass('disabled');
						delete_all_items($(this).data("type"), $(".tabs_content li:eq("+$(this).index()+")").attr("item-section"), 350, $(this).data('href'));
					}
					$(this).removeClass('disabled');
				})


				$('form[name^=basket_form] .counter_block input[type=text]').change( function(e){
					e.preventDefault();
					// updateQuantity($(this).attr("id"), $(this).attr("data-id"), $(this).attr("step"));
				});

				$('form[name^=basket_form] .remove-cell .remove').on("click", function(e){
					e.preventDefault();
					jsAjaxUtil.ShowLocalWaitWindow( 'id', 'basket_form', true );
					var row = $(this).parents("tr").first();
					row.fadeTo(100 , 0.05, function() {});
					deleteProduct($(this).parents("tr[data-id]").attr('data-id'), $(this).parents("li").attr("item-section"), $(this).closest("tr").data('product_id'));
					jsAjaxUtil.CloseLocalWaitWindow( 'id', 'basket_form', true );
				});

				$('form[name^=basket_form] .delay .wish_item').click(function(e){
					e.preventDefault();
					var row = $(this).parents("tr").first();
					row.fadeTo(100 , 0.05, function() {});
					delayProduct($(this).parents("tr[data-id]").attr('data-id'), $(this).parents("li").attr("item-section"));
				})

				$('form[name^=basket_form] .add .wish_item').click(function(e){
					e.preventDefault();
					var basketId = $(this).parents("tr[data-id]").attr('data-id');
					var controlId =  "QUANTITY_INPUT_"+basketId;
					var ratio =  $(this).parents("tr[data-id]").find("#"+controlId).attr("step");
					var quantity =  $(this).parents("tr[data-id]").find("#"+controlId).attr("value");
					var row = $(this).parents("tr").first();
					row.fadeTo(100 , 0.05, function() {});
					addProduct(basketId, $(this).parents("li").attr("item-section"));
				})
			});
		<?endif;?>
	</script>

<?else:?>
	<div id="basket_form">
		<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items.php");?>
	</div>
<?endif;?>

<?if($arParams['INNER']!==true):?>
	</div>
<?endif;?>

<?if($_REQUEST && isset($_REQUEST['print']) && $bShowBasketPrint):?>
	<div class="basket_print_desc">
		<?$APPLICATION->IncludeFile(SITE_DIR."include/basket_print_desc.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("BASKET_PRINT_TEXT")));?>
		<script>
		$(document).ready(function() {
			window.print();
		});
		</script>
	</div>
<?endif;?>
<!-- /noindex -->
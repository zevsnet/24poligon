<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$catalogIblockID = \Bitrix\Main\Config\Option::get('aspro.max', 'CATALOG_IBLOCK_ID', CMaxCache::$arIBlocks[SITE_ID]['aspro_max_catalog']['aspro_max_catalog'][0]);
$normalCount = count($arResult["ITEMS"]["AnDelCanBuy"]);
$delayCount = count($arResult["ITEMS"]["DelDelCanBuy"]);
$subscribeCount = count($arResult["ITEMS"]["ProdSubscribe"]);
$naCount = count($arResult["ITEMS"]["nAnCanBuy"]);

if(is_array($_SESSION["CATALOG_COMPARE_LIST"][$catalogIblockID]["ITEMS"]))
	$compareCount = count($_SESSION["CATALOG_COMPARE_LIST"][$catalogIblockID]["ITEMS"]);
else
	$compareCount = 0;

$arParamsExport=$arParams;
$paramsString = urlencode(serialize($arParamsExport));

// update basket counters
\Bitrix\Main\Loader::includeModule('aspro.max');
$title_basket =  ($normalCount ? GetMessage("BASKET_COUNT", array("#PRICE#" => $arResult['allSum_FORMATED'])) : GetMessage("EMPTY_BLOCK_BASKET"));
$title_delay = ($delayCount ? GetMessage("BASKET_DELAY_COUNT", array("#PRICE#" => $arResult["DELAY_PRICE"]["SUMM_FORMATED"])) : GetMessage("EMPTY_BLOCK_DELAY"));

$arCounters = CMax::updateBasketCounters(array('READY' => array('COUNT' => $normalCount, 'TITLE' => $title_basket, 'HREF' => $arParams["PATH_TO_BASKET"]), 'DELAY' => array('COUNT' => $delayCount, 'TITLE' => $title_delay, 'HREF' => $arParams["PATH_TO_BASKET"].'#delayed'), 'COMPARE' => array('COUNT' => $compareCount, 'HREF' => $arParams["PATH_TO_COMPARE"]), 'PERSONAL' => array('HREF' => $arParams["PATH_TO_AUTH"])));
?>

<div class="wrap_cont">
	<?$frame = $this->createFrame()->begin('');?>
	<input type="hidden" name="total_price" value="<?=$arResult['allSum_FORMATED']?>" />
	<input type="hidden" name="total_discount_price" value="<?=$arResult['allSum_FORMATED']?>" />
	<input type="hidden" name="total_count" value="<?=$normalCount;?>" />

	<?if($_POST['firstTime']):?>
		<script src="<?=(((COption::GetOptionString('main', 'use_minified_assets', 'N', $siteID) === 'Y') && file_exists($_SERVER['DOCUMENT_ROOT'].$templateFolder.'/script.min.js')) ? $templateFolder.'/script.min.js' : $templateFolder.'/script.js')?>" type="text/javascript"></script>
	<?endif;?>
	<?
		include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/functions.php");
		$arUrls = Array("delete" => SITE_DIR."ajax/show_basket_fly.php?action=delete&id=#ID#",
						"delay" => SITE_DIR."ajax/show_basket_fly.php?action=delay&id=#ID#",
						"add" => SITE_DIR."ajax/show_basket_fly.php?action=add&id=#ID#");


		if (is_array($arResult["WARNING_MESSAGE"]) && !empty($arResult["WARNING_MESSAGE"])) { foreach ($arResult["WARNING_MESSAGE"] as $v) { echo ShowError($v); } }

		$arMenu = array(array("ID"=>"AnDelCanBuy", "TITLE"=>GetMessage("SALE_BASKET_ITEMS"), "COUNT"=>$normalCount, "FILE"=>"/basket_items.php"));
	?>
	
		<?$arError = CMax::checkAllowDelivery($arResult["allSum"],CSaleLang::GetLangCurrency(SITE_ID));?>
		<form method="post" action="<?=POST_FORM_ACTION_URI?>" name="basket_form" id="basket_form" class="basket_wrapp">
			<?if (strlen($arResult["ERROR_MESSAGE"]) <= 0){?>
				<ul class="tabs_content basket">
					<?foreach($arMenu as $key => $arElement){?>
						<li class="<?=($arElement["SELECTED"] ? ' cur' : '');?><?=($arError["ERROR"] ? ' min-price' : '');?>" item-section="<?=$arElement["ID"]?>"><?include($_SERVER["DOCUMENT_ROOT"].$templateFolder.$arElement["FILE"]);?></li>
					<?}?>
				</ul>
			<?}else{?>
				<ul class="tabs_content basket"><li class="cur" item-section="AnDelCanBuy"><?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items.php");?></ul>
			<?}?>
			<input id="fly_basket_params" type="hidden" name="PARAMS" value='<?=$paramsString?>' />
		</form>

		<script>
			InitScrollBar();
			
			if (typeof updateBottomIconsPanel === 'function') {
				updateBottomIconsPanel(<?=CUtil::PhpToJSObject($arCounters)?>);
			}

			<?if ($arParams["AJAX_MODE_CUSTOM"]=="Y"):?>
				var animateRow = function(row)
				{
					$(row).find("td.thumb-cell img").css({"maxHeight": "inherit", "maxWidth": "inherit"}).fadeTo(50, 0);
					var columns = $(row).find("td");
					$(columns).wrapInner('<div class="slide"></div>');
					$(row).find(".summ-cell").wrapInner('<div class="slide"></div>');
					setTimeout(function(){$(columns).animate({"paddingTop": 0, "paddingBottom": 0}, 50)}, 0);
					$(columns).find(".slide").slideUp(333);
				}

				$("#basket_form").ready(function()
				{
					$('form[name^=basket_form] .counter_block input[type=text]').change( function(e)
					{
						e.preventDefault();
						// updateQuantity($(this).attr("id"), $(this).attr("data-id"), $(this).attr("step"));
					});

					$('.basket_action .remove_all_basket').click(function(e){
						e.preventDefault();
						if(!$(this).hasClass('disabled')){
							$(this).addClass('disabled');
							delete_all_items($(this).data("type"), $(this).closest("li").attr("item-section"), 333);
						}
						$(this).removeClass('disabled');
						reloadBasketCounters();
					})

					$('form[name^=basket_form] .remove').unbind('click').click(function(e){
						e.preventDefault();
						var row = $(this).parents(".item").first();
						row.fadeTo(100 , 0.05, function() {});
						deleteProduct($(this).parents(".item[data-id]").attr('data-id'), $(this).parents("li").attr("item-section"), $(this).parents(".item[data-id]").attr('product-id'), $(this).parents(".item[data-id]"));
						markProductRemoveBasket($(this).parents(".item[data-id]").attr('product-id'));
						return false;
					});
				});
			<?endif;?>
		</script>
		<?if(\Bitrix\Main\Loader::includeModule("currency"))
		{
			CJSCore::Init(array('currency'));
			$currencyFormat = CCurrencyLang::GetFormatDescription(CSaleLang::GetLangCurrency(SITE_ID));
		}
		?>
		<script type="text/javascript">
			<?if(is_array($currencyFormat)):?>
				function jsPriceFormat(_number){
					BX.Currency.setCurrencyFormat('<?=CSaleLang::GetLangCurrency(SITE_ID);?>', <? echo CUtil::PhpToJSObject($currencyFormat, false, true); ?>);
					return BX.Currency.currencyFormat(_number, '<?=CSaleLang::GetLangCurrency(SITE_ID);?>', true);
				}
			<?endif;?>
		</script>
	<?$frame->end();?>
</div>
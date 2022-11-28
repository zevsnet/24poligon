<?if($_GET["itemID"]){
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	header('Content-type: application/json');
	static $arAddToBasketOptions, $bUserAuthorized;
	$class=($_GET["classButton"] ? $_GET["classButton"] : "small");
	if($arAddToBasketOptions === NULL){
		$arAddToBasketOptions = array(
			"BUYMISSINGGOODS" => COption::GetOptionString("aspro.mshop", "BUYMISSINGGOODS", "ADD", SITE_ID),
			"EXPRESSION_ORDER_BUTTON" => COption::GetOptionString("aspro.mshop", "EXPRESSION_ORDER_BUTTON", GetMessage("EXPRESSION_ORDER_BUTTON_DEFAULT"), SITE_ID),
			"EXPRESSION_ORDER_TEXT" => COption::GetOptionString("aspro.mshop", "EXPRESSION_ORDER_TEXT", GetMessage("EXPRESSION_ORDER_TEXT_DEFAULT"), SITE_ID),
			"EXPRESSION_SUBSCRIBE_BUTTON" => COption::GetOptionString("aspro.mshop", "EXPRESSION_SUBSCRIBE_BUTTON", GetMessage("EXPRESSION_SUBSCRIBE_BUTTON_DEFAULT"), SITE_ID),
			"EXPRESSION_SUBSCRIBED_BUTTON" => COption::GetOptionString("aspro.mshop", "EXPRESSION_SUBSCRIBED_BUTTON", GetMessage("EXPRESSION_SUBSCRIBED_BUTTON_DEFAULT"), SITE_ID),
		);
		global $USER;
		$bUserAuthorized = $USER->IsAuthorized();
	}
	$one_click_html='';
	if($arAddToBasketOptions["BUYMISSINGGOODS"] == "ADD" || $_GET["itemBuy"]=="Y"){
		$buttonText = array(GetMessage('EXPRESSION_ADDTOBASKET_BUTTON_DEFAULT'), GetMessage('EXPRESSION_ADDEDTOBASKET_BUTTON_DEFAULT'));
		$buttonHTML = '<span class="'.$class.' read_more to-cart button" id="'.$_GET['buyLink'].'" data-item="'.$_GET["itemID"].'"><i></i><span>'.$buttonText[0].'</span></span><a rel="nofollow" id="'.$_GET['basketLink'].'" href="'.$_GET['basketLinkURL'].'" class="'.$class.' in-cart button" data-item="'.$_GET["itemID"].'"  style="display:none;"><i></i><span>'.$buttonText[1].'</span></a>';
	}elseif($arAddToBasketOptions["BUYMISSINGGOODS"] == "SUBSCRIBE" && $_GET["itemSubscribe"] == "Y"){
		$buttonText = array($arAddToBasketOptions['EXPRESSION_SUBSCRIBE_BUTTON'], $arAddToBasketOptions['EXPRESSION_SUBSCRIBED_BUTTON']);
		$buttonHTML = '<span class="'.$class.' transparent to-subscribe'.(!$bUserAuthorized ? ' auth' : '').' button" id="'.$_GET['item']['SUBSCRIBE_ID'].'" rel="nofollow" data-item="'.$_GET["itemID"].'"  alt="'.$_GET["itemName"].'"><i></i><span>'.$buttonText[0].'</span></span><span class="'.$class.' transparent in-subscribe button" rel="nofollow" style="display:none;" id="'.$_GET['item']['SUBSCRIBED_ID'].'" data-item="'.$_GET["itemID"].'" alt="'.$_GET["itemName"].'"><i></i><span>'.$buttonText[1].'</span></span>';
	}
	elseif($arAddToBasketOptions["BUYMISSINGGOODS"] == "ORDER"){
		$buttonText = array($arAddToBasketOptions['EXPRESSION_ORDER_BUTTON']);
		$buttonHTML = '<span class="'.$class.' to-order button transparent" data-name="'.$_GET["itemName"].'" data-item="'.$_GET["itemID"].'"><i></i><span>'.$buttonText[0].'</span></span>';
		if($arAddToBasketOptions['EXPRESSION_ORDER_TEXT']){
			$buttonHTML .='<div class="more_text">'.$arAddToBasketOptions['EXPRESSION_ORDER_TEXT'].'</div>';
		}
	}
	if($arAddToBasketData["ACTION"] !== "NOTHING" && $_GET["itemBuy"]=="Y" && (isset($_GET["oneClick"]) && $_GET["oneClick"]=="Y")){
		$one_click_html='<span class="transparent '.$class.' type_block button one_click" data-item="'.$_GET["itemID"].'" data-iblockID="'.$_GET["itemIBlockID"].'" data-quantity="'.($_GET["itemMaxQuantity"] >= $_GET["defaultCount"] ? $_GET["defaultCount"] : $_GET["itemMaxQuantity"]).'" onclick="oneClickBuy('.$_GET["itemID"].', '.$_GET["itemIBlockID"].', this)">';
		$one_click_html.='<span>'.$_GET["oneClickTxt"].'</span>';
		$one_click_html.='</span>';
	}
	echo json_encode( array("MESSAGES" => $arAddToBasketOptions, "HTML" => $buttonHTML, "ONE_CLICK_HTML" => $one_click_html) );
}
?>
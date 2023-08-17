<?global $arTheme, $USER;
$bHideOnNarrow = $arTheme['BIGBANNER_HIDEONNARROW']['VALUE'] === 'Y';?>
<div class="top_slider_wrapp maxwidth-banner<?=($bHideOnNarrow ? ' hidden_narrow' : '')?>">
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.flexslider-min.js',true)?> 
	<div class="flexslider hovers">
		<ul class="slides">
			<?$bBannerLight = $bShowH1 = false;?>
			<?$strTypeHitProp = \Bitrix\Main\Config\Option::get('aspro.max', 'ITEM_STICKER_CLASS_SOURCE', 'PROPERTY_VALUE');?>
			<?foreach($arResult["ITEMS"][$arParams["BANNER_TYPE_THEME"]]["ITEMS"] as $i => $arItem):?>
				<?
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				$background = is_array($arItem["DETAIL_PICTURE"]) ? $arItem["DETAIL_PICTURE"]["SRC"] : $this->GetFolder()."/images/background.jpg";
				$target = $arItem["PROPERTIES"]["TARGETS"]["VALUE_XML_ID"];
				$arItem["NAME"] = strip_tags($arItem["~NAME"]);

				if(!$i && ($arItem["PROPERTIES"]["DARK_MENU_COLOR"]["VALUE"] != "Y"))
					$bBannerLight = true;

				// video options
				$videoSource = strlen($arItem['PROPERTIES']['VIDEO_SOURCE']['VALUE_XML_ID']) ? $arItem['PROPERTIES']['VIDEO_SOURCE']['VALUE_XML_ID'] : 'LINK';
				$videoSrc = $arItem['PROPERTIES']['VIDEO_SRC']['VALUE'];
				if($videoFileID = $arItem['PROPERTIES']['VIDEO']['VALUE']){
					$videoFileSrc = CFile::GetPath($videoFileID);
				}
				$videoPlayer = $videoPlayerSrc = '';
				if($bShowVideo = $arItem['PROPERTIES']['SHOW_VIDEO']['VALUE_XML_ID'] === 'YES' && ($videoSource == 'LINK' ? strlen($videoSrc) : strlen($videoFileSrc))){
					$colorSubstrates = ($arItem['PROPERTIES']['COLOR_SUBSTRATES']['VALUE_XML_ID'] ? $arItem['PROPERTIES']['COLOR_SUBSTRATES']['VALUE_XML_ID'] : '');
					$buttonVideoText = $arItem['PROPERTIES']['BUTTON_VIDEO_TEXT']['VALUE'];
					$bVideoLoop = $arItem['PROPERTIES']['VIDEO_LOOP']['VALUE_XML_ID'] === 'YES';
					$bVideoDisableSound = $arItem['PROPERTIES']['VIDEO_DISABLE_SOUND']['VALUE_XML_ID'] === 'YES';
					$bVideoAutoStart = $arItem['PROPERTIES']['VIDEO_AUTOSTART']['VALUE_XML_ID'] === 'YES';
					$bVideoCover = $arItem['PROPERTIES']['VIDEO_COVER']['VALUE_XML_ID'] === 'YES';
					$bVideoUnderText = $arItem['PROPERTIES']['VIDEO_UNDER_TEXT']['VALUE_XML_ID'] === 'YES';
					if(strlen($videoSrc) && $videoSource === 'LINK'){
						// videoSrc available values
						// YOTUBE:
						// https://youtu.be/WxUOLN933Ko
						// <iframe width="560" height="315" src="https://www.youtube.com/embed/WxUOLN933Ko" frameborder="0" allowfullscreen></iframe>
						// VIMEO:
						// https://vimeo.com/211336204
						// <iframe src="https://player.vimeo.com/video/211336204?title=0&byline=0&portrait=0" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
						// RUTUBE:
						// <iframe width="720" height="405" src="//rutube.ru/play/embed/10314281" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowfullscreen></iframe>
						
						$videoPlayer = 'YOUTUBE';
						$videoSrc = htmlspecialchars_decode($videoSrc);
						if(strpos($videoSrc, 'iframe') !== false){
							$re = '/<iframe.*src=\"(.*)\".*><\/iframe>/isU';
							preg_match_all($re, $videoSrc, $arMatch);
							$videoSrc = $arMatch[1][0];
						}
						$videoPlayerSrc = $videoSrc;

						switch($videoSrc){
							case(($v = strpos($videoSrc, 'vimeo.com/')) !== false):
								$videoPlayer = 'VIMEO';
								if(strpos($videoSrc, 'player.vimeo.com/') === false){
									$videoPlayerSrc = str_replace('vimeo.com/', 'player.vimeo.com/', $videoPlayerSrc);
								}
								if(strpos($videoSrc, 'vimeo.com/video/') === false){
									$videoPlayerSrc = str_replace('vimeo.com/', 'vimeo.com/video/', $videoPlayerSrc);
								}
								break;
							case(($v = strpos($videoSrc, 'rutube.ru/')) !== false):
								$videoPlayer = 'RUTUBE';
								break;
							case(strpos($videoSrc, 'watch?') !== false && ($v = strpos($videoSrc, 'v=')) !== false):
								$videoPlayerSrc = 'https://www.youtube.com/embed/'.substr($videoSrc, $v + 2, 11);
								break;
							case(strpos($videoSrc, 'youtu.be/') !== false && $v = strpos($videoSrc, 'youtu.be/')):
								$videoPlayerSrc = 'https://www.youtube.com/embed/'.substr($videoSrc, $v + 9, 11);
								break;
							case(strpos($videoSrc, 'embed/') !== false && $v = strpos($videoSrc, 'embed/')):
								$videoPlayerSrc = 'https://www.youtube.com/embed/'.substr($videoSrc, $v + 6, 11);
								break;
						}

						$bVideoPlayerYoutube = $videoPlayer === 'YOUTUBE';
						$bVideoPlayerVimeo = $videoPlayer === 'VIMEO';
						$bVideoPlayerRutube = $videoPlayer === 'RUTUBE';

						if(strlen($videoPlayerSrc)){
							$videoPlayerSrc = trim($videoPlayerSrc.
								($bVideoPlayerYoutube ? '?autoplay=1&enablejsapi=1&controls=0&showinfo=0&rel=0&disablekb=1&iv_load_policy=3' :
								($bVideoPlayerVimeo ? '?autoplay=1&badge=0&byline=0&portrait=0&title=0' :
								($bVideoPlayerRutube ? '?quality=1&autoStart=0&sTitle=false&sAuthor=false&platform=someplatform' : '')))
							);
						}
					}
					else{
						$videoPlayer = 'HTML5';
						$videoPlayerSrc = $videoFileSrc;
					}
				}?>
				<li class="lazy box<?=($arItem["PROPERTIES"]["TEXTCOLOR"]["VALUE_XML_ID"] ? " ".$arItem["PROPERTIES"]["TEXTCOLOR"]["VALUE_XML_ID"] : "");?><?=($arItem["PROPERTIES"]["TEXT_POSITION"]["VALUE_XML_ID"] ? " ".$arItem["PROPERTIES"]["TEXT_POSITION"]["VALUE_XML_ID"] : " left");?><?=($bShowVideo ? ' wvideo' : '');?>" data-nav_color="<?=($arItem["PROPERTIES"]["NAV_COLOR"]["VALUE_XML_ID"] ? $arItem["PROPERTIES"]["NAV_COLOR"]["VALUE_XML_ID"] : "");?>" data-text_color="<?=($arItem["PROPERTIES"]["DARK_MENU_COLOR"]["VALUE"] != "Y" ? "light" : "");?>" data-slide_index="<?=$i?>" <?=($bShowVideo ? ' data-video_source="'.$videoSource.'"' : '')?><?=(strlen($videoPlayer) ? ' data-video_player="'.$videoPlayer.'"' : '')?><?=(strlen($videoPlayerSrc) ? ' data-video_src="'.$videoPlayerSrc.'"' : '')?><?=($bVideoAutoStart ? ' data-video_autoplay="1"' : '')?><?=($bVideoDisableSound ? ' data-video_disable_sound="1"' : '')?><?=($bVideoLoop ? ' data-video_loop="1"' : '')?><?=($bVideoCover ? ' data-video_cover="1"' : '')?> id="<?=$this->GetEditAreaId($arItem['ID']);?>" style="background-image: url('<?=\Aspro\Functions\CAsproMax::showBlankImg($background)?>');" data-src="<?=$background;?>">
					<?if($arItem["PROPERTIES"]["URL_STRING"]["VALUE"]):?>
						<a class="target" href="<?=$arItem["PROPERTIES"]["URL_STRING"]["VALUE"]?>" <?=(strlen($target) ? 'target="'.$target.'"' : '')?>></a>
					<?endif;?>
					<div class="wrapper_inner">	
						<? 
						$position = "0% 100%";
						if($arItem["PROPERTIES"]["TEXT_POSITION"]["VALUE_XML_ID"])
						{
							if($arItem["PROPERTIES"]["TEXT_POSITION"]["VALUE_XML_ID"] == "left")
								$position = "100% 100%";
							elseif($arItem["PROPERTIES"]["TEXT_POSITION"]["VALUE_XML_ID"] == "right")
								$position = "0% 100%";
							else
								$position = "center center";									
						}
						?>
						<table>
							<tbody><tr>
								<?if($arItem["PROPERTIES"]["TEXT_POSITION"]["VALUE_XML_ID"] != "image"):?>
									<?ob_start();?>
										<td class="text <?=$arItem["PROPERTIES"]["TEXT_POSITION"]["VALUE_XML_ID"];?>">
											<?if($arItem["PROPERTIES"]["LINK_ITEM"]["VALUE"]):?>
												<?
												$hitProp = (isset($arParams["HIT_PROP"]) ? $arParams["HIT_PROP"] : "HIT");
												$saleProp = (isset($arParams["SALE_PROP"]) ? $arParams["SALE_PROP"] : "SALE_TEXT");

												$arSelect = array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", "PROPERTY_vote_count", "PROPERTY_rating", "PROPERTY_vote_sum", "CATALOG_TYPE");
												if($hitProp)
													$arSelect[] = "PROPERTY_".$hitProp;
												if($saleProp)
													$arSelect[] = "PROPERTY_".$saleProp;

												$arPricesID = array();
												if(!$arParams["PRICE_CODE_IDS"])
												{
													$dbPriceType = \CCatalogGroup::GetList(
														array("SORT" => "ASC"),
														array("NAME" => $arParams["PRICE_CODE"])
														);
													while($arPriceType = $dbPriceType->Fetch())
													{
														$arParams["PRICE_CODE_IDS"][] = array(
															"ID" => $arPriceType["ID"]
														);
													}
												}
												if($arParams["PRICE_CODE_IDS"])
												{
													foreach($arParams["PRICE_CODE_IDS"] as $arPrices)
													{
														$arSelect[] = "CATALOG_GROUP_".$arPrices["ID"];
														$arPricesID[] = $arPrices["ID"];
													}
												}

												$arProduct = CMaxCache::CIBLockElement_GetList(array('CACHE' => array("MULTI" => "N", "TAG" => CMaxCache::GetIBlockCacheTag($arItem["PROPERTIES"]["LINK_ITEM"]["LINK_IBLOCK_ID"]))), array("IBLOCK_ID" => $arItem["PROPERTIES"]["LINK_ITEM"]["LINK_IBLOCK_ID"], "ACTIVE"=>"Y", "ACTIVE_DATE" => "Y", "ID" => $arItem["PROPERTIES"]["LINK_ITEM"]["VALUE"]), false, false, $arSelect);
												$arPriceList = \Aspro\Functions\CAsproMax::getPriceList($arProduct["ID"], $arPricesID, 1, true);
												?>
												<div class="banner_title">
													<?if((($hitProp && $arProduct['PROPERTY_'.$hitProp.'_VALUE']) || ($saleProp && $arProduct['PROPERTY_'.$saleProp.'_VALUE'])) && $arItem["PROPERTIES"]["SHOW_STICKERS"]["VALUE"] == "Y"):?>
														<div class="stickers">
															<?if($saleProp && $arProduct['PROPERTY_'.$saleProp.'_VALUE']):?>
																<div class="sticker_sale_text"><?=$arProduct['PROPERTY_'.$saleProp.'_VALUE']?></div>
															<?endif;?>
															<?if($hitProp && $arProduct['PROPERTY_'.$hitProp.'_VALUE']):?>
																<?foreach((array)$arProduct['PROPERTY_'.$hitProp.'_VALUE'] as $key => $value):?>
																	<?
																	$enumID = ((is_array($arProduct['PROPERTY_'.$hitProp.'_ENUM_ID'])) ? $arProduct['PROPERTY_'.$hitProp.'_ENUM_ID'][$key] : $arProduct['PROPERTY_'.$hitProp.'_ENUM_ID']);
																	$arTmpEnum = CIBlockPropertyEnum::GetByID($enumID);?>
																	<div class="sticker_<?=($strTypeHitProp == "PROPERTY_VALUE" ? CUtil::translit($value, 'ru') : strtolower($arTmpEnum["XML_ID"]));?>"><?=$value?></div>
																<?endforeach;?>
															<?endif;?>
														</div>
													<?endif;?>

													<?if($arItem['PROPERTIES']['TITLE_H1']['VALUE'] == "Y" && !$bShowH1):?>
														<?$bShowH1 = true;?>
														<h1 class="head-title">
													<?else:?>
														<span class="head-title">
													<?endif;?>

														<?if($arProduct["DETAIL_PAGE_URL"]):?>
															<a href="<?=$arProduct["DETAIL_PAGE_URL"]?>" <?=(strlen($target) ? 'target="'.$target.'"' : '')?>>
														<?endif;?>
														<?=$arProduct["NAME"];?>
														<?if($arProduct["DETAIL_PAGE_URL"]):?>
															</a>
														<?endif;?>
													
													<?if($arItem['PROPERTIES']['TITLE_H1']['VALUE'] == "Y" && !$bShowH1):?>
														</h1>
													<?else:?>
														</span>
													<?endif;?>
													<?
													$bHasOffers = (isset($arProduct["CATALOG_TYPE"]) && $arProduct["CATALOG_TYPE"] == 3);

													if($bHasOffers)
													{
														$arSelect = array("ID", "IBLOCK_ID", "NAME", "CATALOG_QUANTITY");												
														$arOffers = CMaxCache::CIBLockElement_GetList(array('CACHE' => array("MULTI" => "Y", "TAG" => CMaxCache::GetIBlockCacheTag($arItem["PROPERTIES"]["LINK_ITEM"]["LINK_IBLOCK_ID"]))), array("PROPERTY_CML2_LINK" => $arProduct["ID"], "ACTIVE"=>"Y", "ACTIVE_DATE" => "Y"), false, false, $arSelect);
														$arProduct["OFFERS"] = $arOffers;
													}

													$arPrice = CCatalogProduct::GetOptimalPrice($arProduct["ID"], 1, $USER->GetUserGroupArray(), 'N', $arPriceList);
													$totalCount = CMax::GetTotalCount($arProduct, $arParams);
													$arQuantityData = CMax::GetQuantityArray($totalCount, array('ID' => $arProduct["ID"]), "N", (($arProduct["OFFERS"] || $arProduct['CATALOG_TYPE'] == CCatalogProduct::TYPE_SET || !$arResult['STORES_COUNT']) ? false : true) );
													$strMeasure = '';
													if($arProduct["CATALOG_MEASURE"])
													{
														$arMeasure = CCatalogMeasure::getList(array(), array("ID" => $arProduct["CATALOG_MEASURE"]), false, false, array())->GetNext();
														$strMeasure = $arMeasure["SYMBOL_RUS"];
													}?>

													<?if($arQuantityData["HTML"] || $arItem['PROPERTIES']['SHOW_RATING']['VALUE'] == "Y"):?>
														<div class="votes_block nstar">
															<?if($arItem['PROPERTIES']['SHOW_RATING']['VALUE'] == "Y"):?>
																<div class="ratings">
																	<div class="inner_rating">
																		<?for($i=1;$i<=5;$i++):?>
																			<div class="item-rating <?=(round($arProduct["PROPERTY_RATING_VALUE"]) >= $i ? "filed" : "");?>"><?=CMax::showIconSvg("star", SITE_TEMPLATE_PATH."/images/svg/star.svg");?></div>
																		<?endfor;?>
																	</div>
																</div>
															<?endif;?>
															<div class="sa_block">
																<?if($arQuantityData["HTML"]):?>
																	<?=$arQuantityData["HTML"];?>
																<?endif;?>
															</div>
														</div>
													<?endif;?>

													<?if($arItem['PROPERTIES']['SHOW_DATE_SALE']['VALUE'] == "Y"):?>
														<?\Aspro\Functions\CAsproMax::showDiscountCounter($totalCount, $arPrice["DISCOUNT"], $arQuantityData, $arProduct, $strMeasure);?>
													<?endif;?>

													<?if($arPrice["RESULT_PRICE"] && $arItem['PROPERTIES']['SHOW_PRICES']['VALUE'] == "Y"):?>
														<?
														$price = $arPrice["RESULT_PRICE"]["DISCOUNT_PRICE"];
														$arFormatPrice = $arPrice["RESULT_PRICE"];
														$arCurrencyParams = array();
														if($arParams["CONVERT_CURRENCY"] != "Y" && $arPrice["RESULT_PRICE"]["CURRENCY"] != $arPrice["PRICE"]["CURRENCY"])
														{
															$price = roundEx(CCurrencyRates::ConvertCurrency($arPrice["RESULT_PRICE"]["DISCOUNT_PRICE"], $arPrice["RESULT_PRICE"]["CURRENCY"], $arPrice["PRICE"]["CURRENCY"]),CATALOG_VALUE_PRECISION);
															$arFormatPrice = $arPrice["PRICE"];
														}
														if($arParams["CONVERT_CURRENCY"] == "Y" && $arParams["CURRENCY_ID"])
														{
															$arCurrencyInfo = CCurrency::GetByID($arParams["CURRENCY_ID"]);
															if (is_array($arCurrencyInfo) && !empty($arCurrencyInfo))
															{
																$arCurrencyParams["CURRENCY_ID"] = $arCurrencyInfo["CURRENCY"];
																$price = CCurrencyRates::ConvertCurrency($arPrice["RESULT_PRICE"]["DISCOUNT_PRICE"], $arPrice["RESULT_PRICE"]["CURRENCY"], $arCurrencyParams["CURRENCY_ID"]);
																$arFormatPrice["CURRENCY"] = $arCurrencyParams["CURRENCY_ID"];
															}
														}
														?>
														<div class="prices">
															<span class="price font_lg">
																<span class="values_wrapper"><?=($bHasOffers ? GetMessage("FROM")." " : "");?><?=\Aspro\Functions\CAsproMaxItem::getCurrentPrice($price, $arFormatPrice, false);?></span>
																<?if($strMeasure):?><span class="price_measure">/<?=$strMeasure?></span><?endif;?>
															</span>
															<?if($arItem['PROPERTIES']['SHOW_OLD_PRICE']['VALUE'] == "Y" && ($arPrice["RESULT_PRICE"]["BASE_PRICE"] != $arPrice["RESULT_PRICE"]["DISCOUNT_PRICE"])):?>
																<span class="price price_old font_sm">
																	<?if($arCurrencyParams)
																		$arPrice["RESULT_PRICE"]["BASE_PRICE"] = CCurrencyRates::ConvertCurrency($arPrice["RESULT_PRICE"]["BASE_PRICE"], $arPrice["RESULT_PRICE"]["CURRENCY"], $arCurrencyParams["CURRENCY_ID"])?>
																	<span class="values_wrapper"><?=($bHasOffers ? GetMessage("FROM")." " : "");?><?=\Aspro\Functions\CAsproMaxItem::getCurrentPrice($arPrice["RESULT_PRICE"]["BASE_PRICE"], $arFormatPrice, false);?></span>
																	<?if($strMeasure):?><span class="price_measure">/<?=$strMeasure?></span><?endif;?>
																</span>
															<?endif;?>
														</div>
														<?if($arItem['PROPERTIES']['SHOW_DISCOUNT']['VALUE'] == "Y" && $arPrice["RESULT_PRICE"]["DISCOUNT"]):?>
															<div class="sale_block">
																<div class="sale-number rounded2 font_xxs">
																	<div class="value">-<span><?=$arPrice["RESULT_PRICE"]["PERCENT"]?></span>%</div>
																	<div class="inner-sale rounded1">
																		<span><?=GetMessage("CATALOG_ITEM_ECONOMY");?></span>
																		<span class="price">
																			<?if($arCurrencyParams)
																			$arPrice["RESULT_PRICE"]["DISCOUNT"] = CCurrencyRates::ConvertCurrency($arPrice["RESULT_PRICE"]["DISCOUNT"], $arPrice["RESULT_PRICE"]["CURRENCY"], $arCurrencyParams["CURRENCY_ID"])?>
																			<span class="values_wrapper"><?=\Aspro\Functions\CAsproMaxItem::getCurrentPrice($arPrice["RESULT_PRICE"]["DISCOUNT"], $arFormatPrice, false);?></span>
																		</span>
																	</div>
																</div>
															</div>
														<?endif;?>
													<?endif;?>
												</div>

												<div class="banner_buttons with_actions <?=$arProduct["CATALOG_TYPE"];?>">
													<a href="<?=$arProduct["DETAIL_PAGE_URL"]?>" class="<?=!empty($arItem["PROPERTIES"]["BUTTON1CLASS"]["VALUE"]) ? $arItem["PROPERTIES"]["BUTTON1CLASS"]["VALUE"] : "btn btn-default btn-lg"?>" <?=(strlen($target) ? 'target="'.$target.'"' : '')?>>
														<?=$arItem["PROPERTIES"]["BUTTON1TEXT"]["VALUE"]?>
													</a>
													<?if($arItem['PROPERTIES']['SHOW_BUTTONS']['VALUE'] == "Y"):?>
														<div class="wraps_buttons" data-id="<?=$arProduct["ID"];?>" data-iblockid="<?=$arProduct["IBLOCK_ID"];?>">
															<?$arAllPrices = \CIBlockPriceTools::GetCatalogPrices(false, $arParams["PRICE_CODE"]);
															$arProduct["CAN_BUY"] = CIBlockPriceTools::CanBuy($arProduct["IBLOCK_ID"], $arAllPrices, $arProduct);
															?>
															<?if($arPrice && $arProduct["CATALOG_TYPE"] == 1):?>
																<?if($arProduct["CAN_BUY"]):?>
																	<div class="wrap colored_theme_hover_bg option-round  basket_item_add" data-title="<?=$arTheme["EXPRESSION_ADDTOBASKET_BUTTON_DEFAULT"]["VALUE"];?>" title="<?=$arTheme["EXPRESSION_ADDTOBASKET_BUTTON_DEFAULT"]["VALUE"];?>" data-href="<?=$arTheme["BASKET_PAGE_URL"]["VALUE"];?>" data-title2="<?=$arTheme["EXPRESSION_ADDEDTOBASKET_BUTTON_DEFAULT"]["VALUE"];?>">
																		<?=CMax::showIconSvg("basket ", SITE_TEMPLATE_PATH."/images/svg/basket.svg");?>
																		<?=CMax::showIconSvg("basket-added", SITE_TEMPLATE_PATH."/images/svg/inbasket.svg");?>
																	</div>
																<?endif;?>
																<div class="wrap colored_theme_hover_bg option-round  wish_item_add" data-title="<?=GetMessage("CATALOG_ITEM_DELAY");?>" title="<?=GetMessage("CATALOG_ITEM_DELAY");?>" data-title2="<?=GetMessage("CATALOG_ITEM_DELAYED");?>">
																	<?=CMax::showIconSvg("wish ", SITE_TEMPLATE_PATH."/images/svg/chosen.svg");?>
																</div>
															<?endif;?>
															<?if($arTheme['CATALOG_COMPARE']['VALUE'] != 'N'):?>
																<div class="wrap colored_theme_hover_bg option-round  compare_item_add" data-title="<?=GetMessage("CATALOG_ITEM_COMPARE");?>" title="<?=GetMessage("CATALOG_ITEM_COMPARE");?>" data-title2="<?=GetMessage("CATALOG_ITEM_COMPARED");?>">
																	<?=CMax::showIconSvg("compare ", SITE_TEMPLATE_PATH."/images/svg/compare.svg");?>
																</div>
															<?endif;?>
														</div>
													<?endif;?>
												</div>
											<?else:?>
												<?
													$bShowButton1 = (strlen($arItem['PROPERTIES']['BUTTON1TEXT']['VALUE']) && strlen($arItem['PROPERTIES']['BUTTON1LINK']['VALUE']));
													$bShowButton2 = (strlen($arItem['PROPERTIES']['BUTTON2TEXT']['VALUE']) && strlen($arItem['PROPERTIES']['BUTTON2LINK']['VALUE']));
												?>
												<?if($arItem["NAME"]):?>
													<div class="banner_title">
														<?if(strlen($arItem['PROPERTIES']['TOP_TEXT']['VALUE'])):?>
															<div class="section font_upper_md"><?=$arItem['PROPERTIES']['TOP_TEXT']['VALUE']?></div>
														<?endif?>

														<?if($arItem['PROPERTIES']['TITLE_H1']['VALUE'] == "Y" && !$bShowH1):?>
															<?$bShowH1 = true;?>
															<h1 class="head-title">
														<?else:?>
															<span class="head-title">
														<?endif;?>

															<?if($arItem["PROPERTIES"]["URL_STRING"]["VALUE"]):?>
																<a href="<?=$arItem["PROPERTIES"]["URL_STRING"]["VALUE"]?>" <?=(strlen($target) ? 'target="'.$target.'"' : '')?>>
															<?endif;?>
															<?=strip_tags($arItem["~NAME"], "<br><br/>");?>
															<?if($arItem["PROPERTIES"]["URL_STRING"]["VALUE"]):?>
																</a>
															<?endif;?>
														
														<?if($arItem['PROPERTIES']['TITLE_H1']['VALUE'] == "Y" && !$bShowH1):?>
															</h1>
														<?else:?>
															</span>
														<?endif;?>

													</div>
												<?endif;?>
												<?if($arItem["PREVIEW_TEXT"]):?>
													<div class="banner_text"><?=$arItem["PREVIEW_TEXT"];?></div>
												<?endif;?>
												<?if($bShowButton1 || $bShowButton2 || ($bShowVideo && !$bVideoAutoStart)):?>
													<div class="banner_buttons">
														<?if($bShowVideo && !$bVideoAutoStart && !$bShowButton1 && !$bShowButton2):?>
															<span class="play btn-video small <?=(strlen($arItem['PROPERTIES']['BUTTON_VIDEO_CLASS']['VALUE_XML_ID']) ? $arItem['PROPERTIES']['BUTTON_VIDEO_CLASS']['VALUE_XML_ID'] : 'btn-default')?>" title="<?=$buttonVideoText?>"></span>
														<?elseif($bShowButton1 || $bShowButton2):?>
															<?if($bShowVideo && !$bVideoAutoStart):?>
																<span class="btn <?=(strlen($arItem['PROPERTIES']['BUTTON_VIDEO_CLASS']['VALUE_XML_ID']) ? $arItem['PROPERTIES']['BUTTON_VIDEO_CLASS']['VALUE_XML_ID'] : 'btn-default')?> btn-video" title="<?=$buttonVideoText?>"></span>
															<?endif;?>
															<?if($bShowButton1):?>
																<a href="<?=$arItem["PROPERTIES"]["BUTTON1LINK"]["VALUE"]?>" class="<?=!empty($arItem["PROPERTIES"]["BUTTON1CLASS"]["VALUE"]) ? $arItem["PROPERTIES"]["BUTTON1CLASS"]["VALUE"] : "btn btn-default btn-lg"?>" <?=(strlen($target) ? 'target="'.$target.'"' : '')?>>
																	<?=$arItem["PROPERTIES"]["BUTTON1TEXT"]["VALUE"]?>
																</a>
															<?endif;?>
															<?if($bShowButton2):?>
																<a href="<?=$arItem["PROPERTIES"]["BUTTON2LINK"]["VALUE"]?>" class="<?=!empty( $arItem["PROPERTIES"]["BUTTON2CLASS"]["VALUE"]) ? $arItem["PROPERTIES"]["BUTTON2CLASS"]["VALUE"] : "btn btn-transparent-border-color btn-lg"?>" <?=(strlen($target) ? 'target="'.$target.'"' : '')?>>
																	<?=$arItem["PROPERTIES"]["BUTTON2TEXT"]["VALUE"]?>
																</a>
															<?endif;?>
														<?endif;?>
													</div>
												<?endif;?>
											<?endif;?>
										</td>
									<?$text = ob_get_clean();?>
								<?endif;?>
								<?ob_start();?>
									<?$bHasVideo = ($bShowVideo && !$bVideoAutoStart && $arItem["PROPERTIES"]["TEXT_POSITION"]["VALUE_XML_ID"] == "image");?>
									<td class="img <?=($bHasVideo ? 'with_video' : '');?>">
										<?if($bHasVideo):?>
											<div class="video_block">
												<span class="play btn btn-video  <?=(strlen($arItem['PROPERTIES']['BUTTON_VIDEO_CLASS']['VALUE_XML_ID']) ? $arItem['PROPERTIES']['BUTTON_VIDEO_CLASS']['VALUE_XML_ID'] : 'btn-default')?>" title="<?=$buttonVideoText?>"></span>
											</div>
										<?elseif($arItem["PREVIEW_PICTURE"]):?>
											<?if(!empty($arItem["PROPERTIES"]["URL_STRING"]["VALUE"])):?>
												<a href="<?=$arItem["PROPERTIES"]["URL_STRING"]["VALUE"]?>" <?=(strlen($target) ? 'target="'.$target.'"' : '')?>>
											<?endif;?>
											<img class="lazy plaxy" data-src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($arItem['PREVIEW_PICTURE']['SRC'])?>" alt="<?=($arItem['PREVIEW_PICTURE']['ALT'] ? $arItem['PREVIEW_PICTURE']['ALT'] : $arItem['NAME'])?>" title="<?=($arItem['PREVIEW_PICTURE']['TITLE'] ? $arItem['PREVIEW_PICTURE']['TITLE'] : $arItem['NAME'])?>" />
											<?if(!empty($arItem["PROPERTIES"]["URL_STRING"]["VALUE"])):?>
												</a>
											<?endif;?>
										<?endif;?>									
									</td>
								<?$image = ob_get_clean();?>
								<? 
								if($arItem["PROPERTIES"]["TEXT_POSITION"]["VALUE_XML_ID"]){
									if($arItem["PROPERTIES"]["TEXT_POSITION"]["VALUE_XML_ID"] == "left"){
										echo $text.$image;
									}
									elseif($arItem["PROPERTIES"]["TEXT_POSITION"]["VALUE_XML_ID"] == "right"){
										echo $image.$text;
									}
									elseif($arItem["PROPERTIES"]["TEXT_POSITION"]["VALUE_XML_ID"] == "center"){
										echo $text;
									}
									elseif($arItem["PROPERTIES"]["TEXT_POSITION"]["VALUE_XML_ID"] == "image"){
										echo $image;
									}
								}
								else{
									echo $text.$image;
								}
								?>
							</tr></tbody>
						</table>
					</div>
				</li>
			<?endforeach;?>
		</ul>
	</div>
</div>

<?if($bBannerLight)
{
	$templateData["BANNER_LIGHT"] = true;
}?>

<?if($bInitYoutubeJSApi):?>
	<script type="text/javascript">
	BX.ready(function(){
		var tag = document.createElement('script');
		tag.src = "https://www.youtube.com/iframe_api";
		var firstScriptTag = document.getElementsByTagName('script')[0];
		firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
	});
	</script>
<?endif;?>
<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
$this->setFrameMode(true);

$bImage = (isset($arResult["FIELDS"]["DETAIL_PICTURE"]) && strlen($arResult["FIELDS"]["DETAIL_PICTURE"]['SRC']) ? true : false);
?>



<div class="detail <?=($templateName = $component->{"__parent"}->{"__template"}->{"__name"})?> vacancy-detail1">
	<div class="content">
		<?if($bImage || $arResult["DISPLAY_PROPERTIES"]):?>
			<div class="top_content ">
				<?if($bImage):?>
					<div class="image"><img class="img-responsive" src="<?=$arResult["FIELDS"]["DETAIL_PICTURE"]['SRC']?>" alt="<?=($arResult["FIELDS"]["DETAIL_PICTURE"]['ALT'] ? $arResult["FIELDS"]["DETAIL_PICTURE"]['ALT'] : $arResult['NAME']);?>" title="<?=($arResult["FIELDS"]["DETAIL_PICTURE"]['TITLE'] ? $arResult["FIELDS"]["DETAIL_PICTURE"]['TITLE'] : $arResult['NAME']);?>" /></div>
				<?endif;?>
				<?if($arResult["DISPLAY_PROPERTIES"]):?>
					<div class="properties bordered <?=(!$bImage ? 'rounded3': '')?>">
						<div class="row">
							<?$i = 0;?>
							<?foreach($arResult["DISPLAY_PROPERTIES"] as $PCODE => $arProperty):?>
								<?
								if($arProperty['PROPERTY_TYPE'] == 'E' || $arProperty['PROPERTY_TYPE'] == 'G')
									continue;
								?>
								<div class="property <?=strtolower($PCODE);?> col-md-3 col-sm-3 col-xs-6">
									<div class="title-prop font_upper muted"><?=$arProperty['NAME'];?></div>
									<div class="value darken">
										<?if(is_array($arProperty["DISPLAY_VALUE"])):?>
											<?$val = implode(",  ", $arProperty["DISPLAY_VALUE"]);?>
										<?else:?>
											<?$val = $arProperty["DISPLAY_VALUE"];?>
										<?endif;?>
										<?if($PCODE == "SITE"):?>
											<!--noindex-->
											<a href="<?=(strpos($arProperty['VALUE'], 'http') === false ? 'http://' : '').$arProperty['VALUE'];?>" rel="nofollow" target="_blank">
												<?=$arProperty['VALUE'];?>
											</a>
											<!--/noindex-->
										<?elseif($PCODE == "EMAIL"):?>
											<a href="mailto:<?=$val?>"><?=$val?></a>
										<?else:?>
											<?=$val?>
										<?endif;?>
									</div>
								</div>
								<?++$i;?>
							<?endforeach;?>
						</div>
					</div>
				<?endif;?>
			</div>
		<?endif;?>
		<?if(isset($arResult["FIELDS"]["DETAIL_TEXT"]) && strlen($arResult["FIELDS"]["DETAIL_TEXT"])):?>
			<div class="text">
				<?if($arResult["DETAIL_TEXT_TYPE"] == "text"):?>
					<p><?=$arResult["FIELDS"]["DETAIL_TEXT"];?></p>
				<?else:?>
					<?=$arResult["FIELDS"]["DETAIL_TEXT"];?>
				<?endif;?>
			</div>
		<?endif;?>
		<?if($arParams['FORM'] == 'Y'):?>
			<div class="add_resume">
				<div class="button_wrap">
					<span><span class="btn btn-default btn-lg animate-load" data-event="jqm" data-name="resume" data-param-form_id="<?=$arParams["FORM_ID"]?>" data-autoload-POST="<?=CMax::formatJsName($arResult['NAME']);?>" data-autohide=""><?=$arParams["FORM_BUTTON_TITLE"];?></span></span>
				</div>
			</div>
		<?endif;?>
		
	</div>
</div>

<?if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true ) die();?>
<div class="search-page-wrap">
	<div class="searchinput">
		<form action="" method="get">
			<div class="form-control">
			<?if( $arParams["USE_SUGGEST"] === "Y" ){
				if(strlen($arResult["REQUEST"]["~QUERY"]) && is_object($arResult["NAV_RESULT"]))
				{
					$arResult["FILTER_MD5"] = $arResult["NAV_RESULT"]->GetFilterMD5();
					$obSearchSuggest = new CSearchSuggest($arResult["FILTER_MD5"], $arResult["REQUEST"]["~QUERY"]);
					$obSearchSuggest->SetResultCount($arResult["NAV_RESULT"]->NavRecordCount);
				}
				?>
				<?$APPLICATION->IncludeComponent(
					"bitrix:search.suggest.input",
					"",
					array(
						"NAME" => "q",
						"VALUE" => $arResult["REQUEST"]["~QUERY"],
						"INPUT_SIZE" => 40,
						"DROPDOWN_SIZE" => 10,
						"FILTER_MD5" => $arResult["FILTER_MD5"],
					),
					$component, array("HIDE_ICONS" => "Y")
				);?>
			<?}else{?>
				<input class="q" type="text" name="q" value="<?=$arResult["REQUEST"]["QUERY"]?>" size="40" />
			<?}?>
		<?if( $arParams["SHOW_WHERE"] ){?>
			&nbsp;<select class="where" name="where">
				<option value=""><?=GetMessage("SEARCH_ALL")?></option>
				<?foreach($arResult["DROPDOWN"] as $key=>$value):?>
					<option value="<?=$key?>"<?if($arResult["REQUEST"]["WHERE"]==$key) echo " selected"?>><?=$value?></option>
				<?endforeach?>
			</select>
		<?}?>
			</div>
			<?/*<button class="btn btn-search" type="submit" name="s" value="<?=GetMessage("SEARCH_GO")?>"><i class="svg svg-search svg-black"></i></button>*/?>
			<button type="submit" class="btn btn-default btn-lg"><?=GetMessage("SEARCH_GO")?></button>
			<input type="hidden" name="how" value="<?=$arResult["REQUEST"]["HOW"]=="d"? "d": "r"?>" />
		<?if( $arParams["SHOW_WHEN"] ){?>
			<div style="clear: both;"></div>
			<div id="search_params" class="search-page-params">
				<?$APPLICATION->IncludeComponent(
					'bitrix:main.calendar',
					'',
					array(
						'SHOW_INPUT' => 'Y',
						'INPUT_NAME' => 'from',
						'INPUT_VALUE' => $arResult["REQUEST"]["~FROM"],
						'INPUT_NAME_FINISH' => 'to',
						'INPUT_VALUE_FINISH' =>$arResult["REQUEST"]["~TO"],
						'INPUT_ADDITIONAL_ATTR' => 'size="10"',
					),
					null,
					array('HIDE_ICONS' => 'Y')
				);?>
			</div>
		<?}?>
		</form>
	</div>
	<?if( isset( $arResult["REQUEST"]["ORIGINAL_QUERY"] ) ){?>
		<div class="search-language-guess">
			<?=GetMessage("CT_BSP_KEYBOARD_WARNING", array("#query#"=>'<a href="'.$arResult["ORIGINAL_QUERY_URL"].'">'.$arResult["REQUEST"]["ORIGINAL_QUERY"].'</a>'))?>
		</div><br /><?
	}?>
	<?if($arResult["REQUEST"]["QUERY"] === false && $arResult["REQUEST"]["TAGS"] === false):?>
	<?elseif($arResult["ERROR_CODE"]!=0):?>
		<p><?=GetMessage("SEARCH_ERROR")?></p>
		<?ShowError($arResult["ERROR_TEXT"]);?>
		<p><?=GetMessage("SEARCH_CORRECT_AND_CONTINUE")?></p>
		
		<p><?=GetMessage("SEARCH_SINTAX")?><br /><b><?=GetMessage("SEARCH_LOGIC")?></b></p>
		<table border="0" cellpadding="5">
			<tr>
				<td align="center" valign="top"><?=GetMessage("SEARCH_OPERATOR")?></td><td valign="top"><?=GetMessage("SEARCH_SYNONIM")?></td>
				<td><?=GetMessage("SEARCH_DESCRIPTION")?></td>
			</tr>
			<tr>
				<td align="center" valign="top"><?=GetMessage("SEARCH_AND")?></td><td valign="top">and, &amp;, +</td>
				<td><?=GetMessage("SEARCH_AND_ALT")?></td>
			</tr>
			<tr>
				<td align="center" valign="top"><?=GetMessage("SEARCH_OR")?></td><td valign="top">or, |</td>
				<td><?=GetMessage("SEARCH_OR_ALT")?></td>
			</tr>
			<tr>
				<td align="center" valign="top"><?=GetMessage("SEARCH_NOT")?></td><td valign="top">not, ~</td>
				<td><?=GetMessage("SEARCH_NOT_ALT")?></td>
			</tr>
			<tr>
				<td align="center" valign="top">( )</td>
				<td valign="top">&nbsp;</td>
				<td><?=GetMessage("SEARCH_BRACKETS_ALT")?></td>
			</tr>
		</table>
	<?elseif(count($arResult["SEARCH"])>0):?>
		<?//sort?>
		<div class="dropdown-select">
			<div class="dropdown-select__title font_xs darken">
				<span>
					<?if($arResult["REQUEST"]["HOW"]=="d"):?>
						<?=GetMessage("SEARCH_BY_DATE")?>
					<?else:?>
						<?=GetMessage("SEARCH_BY_RANK")?>
					<?endif;?>
				</span>
				<?=CMax::showIconSvg("down", SITE_TEMPLATE_PATH.'/images/svg/trianglearrow_down.svg', '', '', true, false);?>
			</div>
			<div class="dropdown-select__list dropdown-menu-wrapper" role="menu">
				<div class="dropdown-menu-inner rounded3">
					<div class="dropdown-select__list-item font_xs">
						<?if($arResult["REQUEST"]["HOW"]!="d"):?>
							<span class="dropdown-select__list-link dropdown-select__list-link--current">
						<?else:?>
							<a href="<?=$arResult["URL"]?>&amp;how=r<?=$arResult["REQUEST"]["FROM"]? '&amp;from='.$arResult["REQUEST"]["FROM"]: ''?><?=$arResult["REQUEST"]["TO"]? '&amp;to='.$arResult["REQUEST"]["TO"]: ''?>" class="dropdown-select__list-link <?=$sort_order?> <?=$key?> darken" rel="nofollow">
						<?endif;?>
							<span><?=GetMessage("SEARCH_BY_RANK")?></span>
						<?if($arResult["REQUEST"]["HOW"]!="d"):?>
							</span>
						<?else:?>
							</a>
						<?endif;?>
					</div>
					<div class="dropdown-select__list-item font_xs">
						<?if($arResult["REQUEST"]["HOW"]=="d"):?>
							<span class="dropdown-select__list-link dropdown-select__list-link--current">
						<?else:?>
							<a href="<?=$arResult["URL"]?>&amp;how=d<?=$arResult["REQUEST"]["FROM"]? '&amp;from='.$arResult["REQUEST"]["FROM"]: ''?><?=$arResult["REQUEST"]["TO"]? '&amp;to='.$arResult["REQUEST"]["TO"]: ''?>" class="dropdown-select__list-link <?=$sort_order?> <?=$key?> darken" rel="nofollow">
						<?endif;?>
							<span><?=GetMessage("SEARCH_BY_DATE")?></span>
						<?if($arResult["REQUEST"]["HOW"]=="d"):?>
							</span>
						<?else:?>
							</a>
						<?endif;?>
					</div>
				</div>
			</div>
		</div>

		<?if($arParams["DISPLAY_TOP_PAGER"] != "N") echo $arResult["NAV_STRING"]?>
		<div class="items">
			<?foreach($arResult["SEARCH"] as $arItem):?>
				<div class="bordered rounded3 item colored_theme_hover_bg-block">
					<?if($arItem["CHAIN_PATH"]):?>
						<ul class="path"><?=$arItem["CHAIN_PATH"]?></ul>
					<?endif;?>
					<a href="<?=$arItem["URL"]?>" class="dark_link font_mlg title"><?=$arItem["TITLE_FORMATED"]?></a>
					<div class="text"><?=$arItem["BODY_FORMATED"]?></div>
					<a href="<?=$arItem['URL']?>" class="arrow_link colored_theme_hover_bg-el bordered-block rounded3 muted" title="<?=GetMessage('TO_ALL')?>"><?=CMax::showIconSvg("right-arrow", SITE_TEMPLATE_PATH.'/images/svg/arrow_right_list.svg', '', '');?></a>
					<?if (
						$arParams["SHOW_RATING"] == "Y"
						&& strlen($arItem["RATING_TYPE_ID"]) > 0
						&& $arItem["RATING_ENTITY_ID"] > 0
					):?>
						<div class="search-item-rate"><?
							$APPLICATION->IncludeComponent(
								"bitrix:rating.vote", $arParams["RATING_TYPE"],
								Array(
									"ENTITY_TYPE_ID" => $arItem["RATING_TYPE_ID"],
									"ENTITY_ID" => $arItem["RATING_ENTITY_ID"],
									"OWNER_ID" => $arItem["USER_ID"],
									"USER_VOTE" => $arItem["RATING_USER_VOTE_VALUE"],
									"USER_HAS_VOTED" => $arItem["RATING_USER_VOTE_VALUE"] == 0? 'N': 'Y',
									"TOTAL_VOTES" => $arItem["RATING_TOTAL_VOTES"],
									"TOTAL_POSITIVE_VOTES" => $arItem["RATING_TOTAL_POSITIVE_VOTES"],
									"TOTAL_NEGATIVE_VOTES" => $arItem["RATING_TOTAL_NEGATIVE_VOTES"],
									"TOTAL_VALUE" => $arItem["RATING_TOTAL_VALUE"],
									"PATH_TO_USER_PROFILE" => $arParams["~PATH_TO_USER_PROFILE"],
								),
								$component,
								array("HIDE_ICONS" => "Y")
							);?>
						</div>
					<?endif;?>
				</div>
			<?endforeach;?>
		</div>
		<?if($arParams["DISPLAY_BOTTOM_PAGER"] != "N") echo $arResult["NAV_STRING"]?>
		<br />
		
	<?else:?>
		<div class="alert alert-danger"><?ShowNote(GetMessage("SEARCH_NOTHING_TO_FOUND"));?></div>
	<?endif;?>
</div>
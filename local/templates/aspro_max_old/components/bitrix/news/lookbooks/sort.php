<?
$arDisplays = array("block", "list", "table");


if($arParams["ONLY_ELEMENT_DISPLAY_VARIANT"] == 'Y' && $arElement["DISPLAY_TEMPLATE"]){
	$display = $arElement["DISPLAY_TEMPLATE"];
	$hideVariantButtons = true;
} else{
	if(array_key_exists("display", $_REQUEST) || (array_key_exists("display", $_SESSION)) || $arParams["DEFAULT_LIST_TEMPLATE"]){
		if($_REQUEST["display"] && (in_array(trim($_REQUEST["display"]), $arDisplays))){
			$display = trim($_REQUEST["display"]);
			$_SESSION["display"]=trim($_REQUEST["display"]);
		}
		elseif($_SESSION["display"] && (in_array(trim($_SESSION["display"]), $arDisplays))){
			$display = $_SESSION["display"];
		}
		elseif($arElement["DISPLAY_TEMPLATE"]){ 

	    	$display = $arElement["DISPLAY_TEMPLATE"]; 
		}
		else{
			$display = $arParams["DEFAULT_LIST_TEMPLATE"];
		}
	}
	else{
		$display = "block";
	}
}
	
$template = "catalog_".$display;
//var_dump( $arParams["DEFAULT_LIST_TEMPLATE"]);//die();
/*if($arTheme['HEADER_TYPE']['VALUE'] == 28 || $arTheme['HEADER_TYPE']['VALUE'] == 29)
{
	$APPLICATION->SetPageProperty("HIDE_LEFT_BLOCK", "Y");
	$arTheme['LEFT_BLOCK_CATALOG_SECTIONS']['VALUE'] = 'N';
}*/

//$bHideLeftBlock = ($arTheme['LEFT_BLOCK_CATALOG_SECTIONS']['VALUE'] == 'N');
//$bHideLeftBlock = $isHideLeftBlock;
$bShowFilter = false;
$arTheme["FILTER_VIEW"]["VALUE"] = 'VERTICAL';
?>
<?/*if($bShowFilter):?>
	<div class="adaptive_filter">
		<a class="filter_opener<?=($_REQUEST['set_filter'] === 'y' ? ' active num' : '')?>"><i></i><span><?=GetMessage("CATALOG_SMART_FILTER_TITLE")?></span></a>
	</div>
<?endif;*/?>
<div class="filter-panel sort_header view_<?=$display?>">
	<?if($bShowFilter):?>
		<div class="filter-panel__filter pull-left filter-<?=strtolower($arTheme['FILTER_VIEW']['VALUE']);?> <?=($bHideLeftBlock ? 'filter-panel__filter--visible' : '');?>">
			<div class="bx-filter-title filter_title <?=($bActiveFilter && $bActiveFilter[1] != 'clear' ? 'active-filter' : '')?>">
				<?=CMax::showIconSvg("icon", SITE_TEMPLATE_PATH.'/images/svg/catalog/filter.svg', '', '', true, false);?>
				<span class="font_upper_md font-bold darken"><?=\Bitrix\Main\Localization\Loc::getMessage("CATALOG_SMART_FILTER_TITLE");?></span>
			</div>
			<div class="controls-hr"></div>
		</div>
	<?endif;?>
	<!--noindex-->
		<div class="filter-panel__sort pull-left ">
			<?
			$arAvailableSort = array();
			$arSorts = $arParams["SORT_BUTTONS"];
			if(in_array("POPULARITY", $arSorts)){
				$arAvailableSort["SHOWS"] = array("SHOWS", "desc");
			}
			if(in_array("NAME", $arSorts)){
				$arAvailableSort["NAME"] = array("NAME", "asc");
			}
			if(in_array("PRICE", $arSorts)){
				$arSortPrices = $arParams["SORT_PRICES"];
				if($arSortPrices == "MINIMUM_PRICE" || $arSortPrices == "MAXIMUM_PRICE"){
					$arAvailableSort["PRICE"] = array("PROPERTY_".$arSortPrices, "desc");
				}
				else{
					if($arSortPrices == "REGION_PRICE")
					{
						global $arRegion;
						if($arRegion)
						{
							if(!$arRegion["PROPERTY_SORT_REGION_PRICE_VALUE"] || $arRegion["PROPERTY_SORT_REGION_PRICE_VALUE"] == "component")
							{
								$price = CCatalogGroup::GetList(array(), array("NAME" => $arParams["SORT_REGION_PRICE"]), false, false, array("ID", "NAME"))->GetNext();
								$arAvailableSort["PRICE"] = array("CATALOG_PRICE_".$price["ID"], "desc");
							}
							else
							{
								$arAvailableSort["PRICE"] = array("CATALOG_PRICE_".$arRegion["PROPERTY_SORT_REGION_PRICE_VALUE"], "desc");
							}
						}
						else
						{
							$price_name = ($arParams["SORT_REGION_PRICE"] ? $arParams["SORT_REGION_PRICE"] : "BASE");
							$price = CCatalogGroup::GetList(array(), array("NAME" => $price_name), false, false, array("ID", "NAME"))->GetNext();
							$arAvailableSort["PRICE"] = array("CATALOG_PRICE_".$price["ID"], "desc");
						}
					}
					else
					{
						$price = CCatalogGroup::GetList(array(), array("NAME" => $arParams["SORT_PRICES"]), false, false, array("ID", "NAME"))->GetNext();
						$arAvailableSort["PRICE"] = array("CATALOG_PRICE_".$price["ID"], "desc");
					}
				}
			}
			if(in_array("QUANTITY", $arSorts)){
				$arAvailableSort["CATALOG_AVAILABLE"] = array("QUANTITY", "desc");
			}
			$sort = "SHOWS";
			if((array_key_exists("sort", $_REQUEST) && array_key_exists(ToUpper($_REQUEST["sort"]), $arAvailableSort)) || (array_key_exists("sort", $_SESSION) && array_key_exists(ToUpper($_SESSION["sort"]), $arAvailableSort)) || $arParams["ELEMENT_SORT_FIELD"]){
				if($_REQUEST["sort"]){
					$sort = ToUpper($_REQUEST["sort"]);
					$_SESSION["sort"] = ToUpper($_REQUEST["sort"]);
				}
				elseif($_SESSION["sort"]){
					$sort = ToUpper($_SESSION["sort"]);
				}
				else{
					$sort = ToUpper($arParams["ELEMENT_SORT_FIELD"]);
				}
			}

			$sort_order=$arAvailableSort[$sort][1];
			if((array_key_exists("order", $_REQUEST) && in_array(ToLower($_REQUEST["order"]), Array("asc", "desc"))) || (array_key_exists("order", $_REQUEST) && in_array(ToLower($_REQUEST["order"]), Array("asc", "desc")) ) || $arParams["ELEMENT_SORT_ORDER"]){
				if($_REQUEST["order"]){
					$sort_order = $_REQUEST["order"];
					$_SESSION["order"] = $_REQUEST["order"];
				}
				elseif($_SESSION["order"]){
					$sort_order = $_SESSION["order"];
				}
				else{
					$sort_order = ToLower($arParams["ELEMENT_SORT_ORDER"]);
				}
			}
			$arDelUrlParams = array('sort', 'order', 'control_ajax', 'ajax_get_filter', 'linerow', 'display');
			?>
			<?if($arAvailableSort):?>
				<div class="dropdown-select">
					<div class="dropdown-select__title font_xs darken">
						<span>
							<?if($sort_order && $sort):?>
								<?=\Bitrix\Main\Localization\Loc::getMessage('SECT_SORT_'.$sort).\Bitrix\Main\Localization\Loc::getMessage('SECT_ORDER_'.$sort_order)?>
							<?else:?>
								<?=\Bitrix\Main\Localization\Loc::getMessage('NOTHING_SELECTED');?>
							<?endif;?>
						</span>
						<?=CMax::showIconSvg("down", SITE_TEMPLATE_PATH.'/images/svg/trianglearrow_down.svg', '', '', true, false);?>
					</div>
					<div class="dropdown-select__list dropdown-menu-wrapper" role="menu">
						<div class="dropdown-menu-inner rounded3">
							<?$arOrder = ['desc', 'asc']?>
							<?foreach($arAvailableSort as $key => $arVals):?>
								<?foreach($arOrder as $value):?>
									<div class="dropdown-select__list-item font_xs">
										<?$newSort = $sort_order == 'desc' ? 'asc' : 'desc';
										$current_url = $APPLICATION->GetCurPageParam('sort='.$key.'&order='.$value, $arDelUrlParams);
										$url = $current_url;?>
										<?if($bCurrentLink = ($sort == $key && $sort_order == $value)):?>
											<span class="dropdown-select__list-link dropdown-select__list-link--current">
										<?else:?>
											<a href="<?=$url;?>" class="dropdown-select__list-link <?=$sort_order?> <?=$key?> darken <?=($arParams['AJAX_FILTER_CATALOG'] == 'Y' ? ' js-load-link' : '');?>" data-url="<?=$url;?>" rel="nofollow">
										<?endif;?>
											<span><?=\Bitrix\Main\Localization\Loc::getMessage('SECT_SORT_'.$key).\Bitrix\Main\Localization\Loc::getMessage('SECT_ORDER_'.$value)?></span>
										<?if($bCurrentLink):?>
											</span>
										<?else:?>
											</a>
										<?endif;?>
									</div>
								<?endforeach?>
							<?endforeach;?>
						</div>
					</div>
				</div>
			<?endif;?>
			<?
			if($sort == "PRICE"){
				$sort = $arAvailableSort["PRICE"][0];
			}
			if($sort == "CATALOG_AVAILABLE"){
				$sort = CMax::checkVersionModule('20.100.0', 'main') ? "CATALOG_AVAILABLE" : "CATALOG_QUANTITY";
			}
			?>
		</div>
		<div class="filter-panel__view controls-view pull-right <?=($hideVariantButtons ? 'hidden' : '')?>">
			<?foreach($arDisplays as $displayType):?>
				<?
				$current_url = '';
				$current_url = $APPLICATION->GetCurPageParam('display='.$displayType, $arDelUrlParams);
				// $url = str_replace('+', '%2B', $current_url);
				$url = $current_url;
				?>
				<?if($display == $displayType):?>
					<span title="<?=\Bitrix\Main\Localization\Loc::getMessage("SECT_DISPLAY_".strtoupper($displayType))?>" class="controls-view__link controls-view__link--<?=$displayType?> controls-view__link--current"><?=CMax::showIconSvg("type", SITE_TEMPLATE_PATH.'/images/svg/catalog/'.$displayType.'type.svg', '', '', true, false);?></span>
				<?else:?>
					<a rel="nofollow" href="<?=$url;?>" data-url="<?=$url?>" title="<?=\Bitrix\Main\Localization\Loc::getMessage("SECT_DISPLAY_".strtoupper($displayType))?>" class="controls-view__link controls-view__link--<?=$displayType?> muted<?=($arParams['AJAX_FILTER_CATALOG'] == 'Y' ? ' js-load-link' : '');?>"><?=CMax::showIconSvg("type", SITE_TEMPLATE_PATH.'/images/svg/catalog/'.$displayType.'type.svg', '', '', true, false);?></a>
				<?endif;?>
			<?endforeach;?>
		</div>
		<?if($display == 'block'):?>
			<div class="filter-panel__view controls-linecount pull-right">
				<?$arLineCount = [3,4];?>
				<?if(array_key_exists("linerow", $_REQUEST) || (array_key_exists("linerow", $_SESSION)) || $arParams["LINE_ELEMENT_COUNT"])
				{
					if($_REQUEST["linerow"] && (in_array(trim($_REQUEST["linerow"]), $arLineCount)))
					{
						$linerow = trim($_REQUEST["linerow"]);
						$_SESSION["linerow"]=trim($_REQUEST["linerow"]);
					}
					elseif($_SESSION["linerow"] && (in_array(trim($_SESSION["linerow"]), $arLineCount)))
					{
						$linerow = $_SESSION["linerow"];
					}
					elseif($arParams["LINE_ELEMENT_COUNT"] && (in_array(trim($arParams["LINE_ELEMENT_COUNT"]), $arLineCount)))
					{
						$linerow = $arParams["LINE_ELEMENT_COUNT"];
					}
					else
					{
						$linerow = 4;
					}
				}
				else
				{
					$linerow = 4;
				}?>

				<?foreach($arLineCount as $value):?>
					<?
					$current_url = '';
					$current_url = $APPLICATION->GetCurPageParam('linerow='.$value, $arDelUrlParams);
					$url = str_replace('+', '%2B', $current_url);
					?>
					<?if($linerow == $value):?>
						<span title="<?=\Bitrix\Main\Localization\Loc::getMessage("SECT_DISPLAY_".$value)?>" class="controls-view__link controls-view__link--current"><?=CMax::showIconSvg("type", SITE_TEMPLATE_PATH.'/images/svg/catalog/'.$value.'inarow.svg', '', '', true, false);?></span>
					<?else:?>
						<a rel="nofollow" href="<?=$url;?>" data-url="<?=$url?>" title="<?=\Bitrix\Main\Localization\Loc::getMessage("SECT_DISPLAY_".$value)?>" class="controls-view__link muted<?=($arParams['AJAX_FILTER_CATALOG'] == 'Y' ? ' js-load-link' : '');?>"><?=CMax::showIconSvg("type", SITE_TEMPLATE_PATH.'/images/svg/catalog/'.$value.'inarow.svg', '', '', true, false);?></a>
					<?endif;?>
				<?endforeach;?>
				<div class="controls-hr"></div>
			</div>
		<?endif;?>
		<div class="clearfix"></div>
	<!--/noindex-->
</div>

<?
/*$arDisplays = array("block", "list", "table");
if(array_key_exists("display", $_REQUEST) || (array_key_exists("display", $_SESSION)) || $arParams["DEFAULT_LIST_TEMPLATE"]){
	if($_REQUEST["display"] && (in_array(trim($_REQUEST["display"]), $arDisplays))){
		$display = trim($_REQUEST["display"]);
		$_SESSION["display"]=trim($_REQUEST["display"]);
	}
	elseif($_SESSION["display"] && (in_array(trim($_SESSION["display"]), $arDisplays))){
		$display = $_SESSION["display"];
	}
	elseif($arSection["DISPLAY"]){
		$display = $arSection["DISPLAY"];
	}
	else{
		$display = $arParams["DEFAULT_LIST_TEMPLATE"];
	}
}
else{
	$display = "block";
}
$template = "catalog_".$display;
?>

<div class="sort_header view_<?=$display?>">
	<!--noindex-->
		<div class="sort_filter <?=($arTheme['MOBILE_FILTER_COMPACT']['VALUE'] === 'Y' ? 'mobile_filter_compact' : '')?>">
			<?
			$arAvailableSort = array();
			if(!$arParams["SORT_BUTTONS"])
			{
				$arParams["SORT_BUTTONS"] = array(
					"POPULARITY" => "POPULARITY",
					"NAME" => "NAME",
					"PRICE" => "PRICE",
				);
			}
			$arSorts = $arParams["SORT_BUTTONS"];
			if(in_array("POPULARITY", $arSorts)){
				$arAvailableSort["SHOWS"] = array("SHOWS", "desc");
			}
			if(in_array("NAME", $arSorts)){
				$arAvailableSort["NAME"] = array("NAME", "asc");
			}
			if(in_array("PRICE", $arSorts)){
				$arSortPrices = $arParams["SORT_PRICES"];
				if($arSortPrices == "MINIMUM_PRICE" || $arSortPrices == "MAXIMUM_PRICE"){
					$arAvailableSort["PRICE"] = array("PROPERTY_".$arSortPrices, "desc");
				}
				else{
					if($arSortPrices == "REGION_PRICE")
					{
						global $arRegion;
						if($arRegion)
						{
							if(!$arRegion["PROPERTY_SORT_REGION_PRICE_VALUE"] || $arRegion["PROPERTY_SORT_REGION_PRICE_VALUE"] == "component")
							{
								$price = CCatalogGroup::GetList(array(), array("NAME" => $arParams["SORT_REGION_PRICE"]), false, false, array("ID", "NAME"))->GetNext();
								$arAvailableSort["PRICE"] = array("CATALOG_PRICE_".$price["ID"], "desc");
							}
							else
							{
								$arAvailableSort["PRICE"] = array("CATALOG_PRICE_".$arRegion["PROPERTY_SORT_REGION_PRICE_VALUE"], "desc");
							}
						}
						else
						{
							$price_name = ($arParams["SORT_REGION_PRICE"] ? $arParams["SORT_REGION_PRICE"] : "BASE");
							$price = CCatalogGroup::GetList(array(), array("NAME" => $price_name), false, false, array("ID", "NAME"))->GetNext();
							$arAvailableSort["PRICE"] = array("CATALOG_PRICE_".$price["ID"], "desc");
						}
					}
					else
					{
						$price = CCatalogGroup::GetList(array(), array("NAME" => $arParams["SORT_PRICES"]), false, false, array("ID", "NAME"))->GetNext();
						$arAvailableSort["PRICE"] = array("CATALOG_PRICE_".$price["ID"], "desc");
					}
				}
			}
			if(in_array("QUANTITY", $arSorts)){
				$arAvailableSort["CATALOG_AVAILABLE"] = array("QUANTITY", "desc");
			}
			$sort = "SHOWS";
			if((array_key_exists("sort", $_REQUEST) && array_key_exists(ToUpper($_REQUEST["sort"]), $arAvailableSort)) || (array_key_exists("sort", $_SESSION) && array_key_exists(ToUpper($_SESSION["sort"]), $arAvailableSort)) || $arParams["ELEMENT_SORT_FIELD"]){
				if($_REQUEST["sort"]){
					$sort = ToUpper($_REQUEST["sort"]);
					$_SESSION["sort"] = ToUpper($_REQUEST["sort"]);
				}
				elseif($_SESSION["sort"]){
					$sort = ToUpper($_SESSION["sort"]);
				}
				else{
					$sort = ToUpper($arParams["ELEMENT_SORT_FIELD"]);
				}
			}
			$sort_order=$arAvailableSort[$sort][1];
			if((array_key_exists("order", $_REQUEST) && in_array(ToLower($_REQUEST["order"]), Array("asc", "desc"))) || (array_key_exists("order", $_REQUEST) && in_array(ToLower($_REQUEST["order"]), Array("asc", "desc")) ) || $arParams["ELEMENT_SORT_ORDER"]){
				if($_REQUEST["order"]){
					$sort_order = $_REQUEST["order"];
					$_SESSION["order"] = $_REQUEST["order"];
				}
				elseif($_SESSION["order"]){
					$sort_order = $_SESSION["order"];
				}
				else{
					$sort_order = ToLower($arParams["ELEMENT_SORT_ORDER"]);
				}
			}
			?>
			<?foreach($arAvailableSort as $key => $val):?>
				<?$newSort = $sort_order == 'desc' ? 'asc' : 'desc';
				$current_url = $APPLICATION->GetCurPageParam('sort='.$key.'&order='.$newSort, 	array('sort', 'order'));
				$url = str_replace('+', '%2B', $current_url);?>
				<a href="<?=$url;?>" class="sort_btn <?=($sort == $key ? 'current' : '')?> <?=$sort_order?> <?=$key?>" rel="nofollow">
					<i class="icon" title="<?=GetMessage('SECT_SORT_'.$key)?>"></i><span><?=GetMessage('SECT_SORT_'.$key)?></span><i class="arr icons_fa"></i>
				</a>
			<?endforeach;?>
			<?
			if($sort == "PRICE"){
				$sort = $arAvailableSort["PRICE"][0];
			}
			if($sort == "CATALOG_AVAILABLE"){
				$sort = "CATALOG_QUANTITY";
			}
			?>
		</div>
		<div class="sort_display">
			<?foreach($arDisplays as $displayType):?>
				<?
				$current_url = '';
				$current_url = $APPLICATION->GetCurPageParam('display='.$displayType, 	array('display', 'ajax_get', 'ajax_get_filter'));
				$url = str_replace('+', '%2B', $current_url);
				?>
				<a rel="nofollow" href="<?=$url;?>" class="sort_btn <?=$displayType?> <?=($display == $displayType ? 'current' : '')?>"><i title="<?=GetMessage("SECT_DISPLAY_".strtoupper($displayType))?>"></i></a>
			<?endforeach;?>
		</div>
		<div class="clearfix"></div>
	<!--/noindex-->
</div>
*/?>
<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
global $arTheme, $arRegion, $bLongHeader, $bColoredHeader;

$arRegions = CMaxRegionality::getRegions();
$bIncludeRegionsList = $arRegions || ($arTheme['USE_REGIONALITY']['VALUE'] !== 'Y' && $arTheme['USE_REGIONALITY']['DEPENDENT_PARAMS']['REGIONALITY_IPCITY_IN_HEADER']['VALUE'] !== 'N');

if($arRegion)
   $bPhone = ($arRegion['PHONES'] ? true : false);
else
   $bPhone = ((int)$arTheme['HEADER_PHONES'] ? true : false);

$logoClass = ($arTheme['COLORED_LOGO']['VALUE'] !== 'Y' ? '' : ' colored');
$bLongHeader = true;
$bColoredHeader = true;
?>
<div class="top-block top-block-v1 fix-logo2 header-wrapper">
   <div class="maxwidth-theme logo_and_menu-row logo_top_white icons_top">
      <div class="wrapp_block logo-row">
         <div class="items-wrapper header__top-inner">
            <?if($bIncludeRegionsList):?>
               <div class="header__top-item">
                  <div class="top-description no-title">
                     <?\Aspro\Functions\CAsproMax::showRegionList();?>
                  </div>
               </div>
            <?endif;?>
            <div class="header__top-item dotted-flex-1 hide-dotted">
               <div class="menus">
                  <?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
                     array(
                     	"COMPONENT_TEMPLATE" => ".default",
                     	"PATH" => SITE_DIR."include/menu/menu.topest.php",
                     	"AREA_FILE_SHOW" => "file",
                     	"AREA_FILE_SUFFIX" => "",
                     	"AREA_FILE_RECURSIVE" => "Y",
                     	"EDIT_TEMPLATE" => "include_area.php"
                     ),
                     false
                     );?>
               </div>
            </div>
            <div class="header__top-item ">
               <div class="line-block line-block--40 line-block--40-1200">
                  <?$arShowSites = \Aspro\Functions\CAsproMax::getShowSites();?>
                  <?$countSites = count($arShowSites);?>
                  <?if ($countSites > 1) :?>
                  <div class="line-block__item no-shrinked">
                     <div class="wrap_icon inner-table-block">
                        <?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
                           array(
                           	"COMPONENT_TEMPLATE" => ".default",
                           	"PATH" => SITE_DIR."/include/header_include/site.selector.php",
                              "SITE_LIST" => $arShowSites,
                           	"AREA_FILE_SHOW" => "file",
                           	"AREA_FILE_SUFFIX" => "",
                           	"AREA_FILE_RECURSIVE" => "Y",
                           	"EDIT_TEMPLATE" => "include_area.php",
                           ),
                           false, array("HIDE_ICONS" => "Y")
                           );?>
                     </div>
                  </div>
                  <?endif;?>
                  <div class="line-block__item no-shrinked ">
                     <div class="show-fixed top-ctrl">
                        <div class="personal_wrap">
                           <div class="wrap_icon inner-table-block person">
                              <?=CMax::ShowCabinetLink(true, true, 'big');?>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="header-wrapper fix-logo2 header-v8">
   <div class="logo_and_menu-row header__top-part">
      <div class="maxwidth-theme logo-row">
         <div class="header__top-inner">
            <div class="logo-block  floated header__top-item no-shrinked">
               <div class="logo<?=$logoClass?>">
                  <?=CMax::ShowLogo();?>
            </div>
            </div>
            <div class="header__top-item phone-wrapper">
               <div class="float_wrapper fix-block ">
                  <div class="wrap_icon inner-table-block">
                     <div class="phone-block blocks icons fontUp">
                        <?if($bPhone):?>
                        <?CMax::ShowHeaderPhones('');?>
                        <?endif?>
                        <?$callbackExploded = explode(',', $arTheme['SHOW_CALLBACK']['VALUE']);
                           if( in_array('HEADER', $callbackExploded) ):?>
                        <div class="inline-block">
                           <span class="callback-block animate-load colored font_upper_xs" data-event="jqm" data-param-form_id="CALLBACK" data-name="callback"><?=GetMessage("CALLBACK")?></span>
                        </div>
                        <?endif;?>
                     </div>
                  </div>
               </div>
            </div>
            <div class="header__top-item flex1 float_wrapper fix-block">
               <div class="search_wrap ">
                  <div class="search-block inner-table-block">
                     <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        Array(
                        	"AREA_FILE_SHOW" => "file",
                        	"PATH" => SITE_DIR."include/top_page/search.title.catalog.php",
                        	"EDIT_TEMPLATE" => "include_area.php",
                        	'SEARCH_ICON' => 'Y',
                        ),
                        false, array("HIDE_ICONS" => "Y")
                        );?>
                  </div>
               </div>
            </div>
            <?if (CMax::GetFrontParametrValue("ORDER_BASKET_VIEW") === "NORMAL"):?>
               <div class="right-icons  wb line-block__item header__top-item">
                  <div class="line-block__item line-block line-block--40 line-block--40-1200 flexbox--justify-end">
                     <?=CMax::ShowBasketWithCompareLink('', 'big', '', 'wrap_icon wrap_basket baskets');?>
                  </div>   
               </div>
            <?endif;?>
         </div>
      </div>
   </div>
   <div class="menu-row middle-block bg<?=strtolower($arTheme["MENU_COLOR"]["VALUE"]);?>">
      <div class="maxwidth-theme">
         <div class="row">
            <div class="col-md-12">
               <div class="menu-only">
                  <nav class="mega-menu sliced">
                     <?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
                        array(
                        	"COMPONENT_TEMPLATE" => ".default",
                        	"PATH" => SITE_DIR."include/menu/menu.top_catalog_wide.php",
                        	"AREA_FILE_SHOW" => "file",
                        	"AREA_FILE_SUFFIX" => "",
                        	"AREA_FILE_RECURSIVE" => "Y",
                        	"EDIT_TEMPLATE" => "include_area.php"
                        	),
                        	false, array("HIDE_ICONS" => "Y")
                        );?>
                  </nav>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="line-row visible-xs"></div>
</div>
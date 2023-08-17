<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?global $arMainPageOrder; //global array for order blocks?>
<?global $arTheme, $dopBodyClass;?>
<?if($arMainPageOrder && is_array($arMainPageOrder)):?>
	<?foreach($arMainPageOrder as $key => $optionCode):?>
		<?$strTemplateName = $arTheme['TEMPLATE_PARAMS'][$arTheme['INDEX_TYPE']['VALUE']][$arTheme['INDEX_TYPE']['VALUE'].'_'.$optionCode.'_TEMPLATE']['VALUE'];?>
		<?$subtype = strtolower($optionCode);?>
		
		<?$dopBodyClass .= ' '.$optionCode.'_'.$strTemplateName;?>

		<?//BIG_BANNER_INDEX?>
		<?if($optionCode == "BIG_BANNER_INDEX"):?>
			<?global $bShowBigBanners, $bBigBannersIndexClass;?>
			<?if($bShowBigBanners):?>
				<?$bIndexLongBigBanner = ($strTemplateName != "type_1" && $strTemplateName != "type_4")?>
				<?if(!$bIndexLongBigBanner):?>
					<?$dopBodyClass .= ' right_mainpage_banner';?>
				<?endif;?>

				<?if($bIndexLongBigBanner):?>
					<?ob_start();?>
						<div class="middle">
				<?endif;?>

				<div class="drag-block grey container <?=$optionCode?> <?=$bBigBannersIndexClass?>" data-class="<?=$subtype?>_drag" data-order="<?=++$key;?>">
					<?=CMax::ShowPageType('mainpage', $subtype, $strTemplateName);?>
				</div>

				<?if($bIndexLongBigBanner):?>
						</div>
					<?$html = ob_get_contents();
					ob_end_clean();?>
					<?$APPLICATION->AddViewContent('front_top_big_banner',$html);?>
				<?endif;?>
			<?endif;?>
		<?endif;?>

		<?//STORIES?>
		<?if($optionCode == "STORIES"):?>
			<?global $bShowStories, $bStoriesIndexClass;?>
			<?if($bShowStories):?>
				<div class="drag-block container <?=$optionCode?> <?=$bStoriesIndexClass;?>" data-class="<?=$subtype?>_drag" data-order="<?=++$key;?>">
					<?=CMax::ShowPageType('mainpage', $subtype, $strTemplateName, true);?>
				</div>
			<?endif;?>
		<?endif;?>

		<?//TIZERS_INDEX?>
		<?if($optionCode == "TIZERS"):?>
			<?global $bShowTizers, $bTizersIndexClass;?>
			<?if($bShowTizers):?>
				<div class="drag-block container <?=$optionCode?> <?=$bTizersIndexClass;?>" data-class="<?=$subtype?>_drag" data-order="<?=++$key;?>">
					<?=CMax::ShowPageType('mainpage', $subtype, $strTemplateName);?>
				</div>
			<?endif;?>
		<?endif;?>

		<?//CATALOG_SECTIONS?>
		<?if($optionCode == "CATALOG_SECTIONS"):?>
			<?global $bShowCatalogSections, $bCatalogSectionsIndexClass;?>
			<?if($bShowCatalogSections):?>
				<div class="drag-block container <?=$optionCode?> <?=$bCatalogSectionsIndexClass;?> js-load-block loader_circle" data-class="<?=$subtype?>_drag" data-order="<?=++$key;?>" data-file="<?=SITE_DIR;?>include/mainpage/components/<?=$subtype;?>/<?=$strTemplateName;?>.php">
					<?=CMax::ShowPageType('mainpage', $subtype, $strTemplateName);?>
				</div>
			<?endif;?>
		<?endif;?>

		<?//CATALOG_TAB?>
		<?if($optionCode == "CATALOG_TAB"):?>
			<?global $bShowCatalogTab, $bCatalogTabIndexClass;?>
			<?if($bShowCatalogTab):?>
				<div class="drag-block container grey <?=$optionCode?> <?=$bCatalogTabIndexClass;?> js-load-block loader_circle" data-class="<?=$subtype?>_drag" data-order="<?=++$key;?>" data-file="<?=SITE_DIR;?>include/mainpage/components/<?=$subtype;?>/<?=$strTemplateName;?>.php">
					<?=CMax::ShowPageType('mainpage', $subtype, $strTemplateName, true);?>
				</div>
			<?endif;?>
		<?endif;?>

		<?//MIDDLE_ADV?>
		<?if($optionCode == "MIDDLE_ADV"):?>
			<?global $bShowMiddleAdvBottomBanner, $bMiddleAdvIndexClass;?>
			<?if($bShowMiddleAdvBottomBanner):?>
				<div class="drag-block container <?=$optionCode?> <?=$bMiddleAdvIndexClass;?>" data-class="<?=$subtype?>_drag" data-order="<?=++$key;?>">
					<?=CMax::ShowPageType('mainpage', $subtype, $strTemplateName);?>
				</div>
			<?endif;?>
		<?endif;?>

		<?//FLOAT_BANNERS?>
		<?if($optionCode == "FLOAT_BANNERS"):?>
			<?global $bShowFloatBanners, $bFloatBannersIndexClass;?>
			<?if($bShowFloatBanners):?>
				<div class="drag-block container <?=$optionCode?> <?=$bFloatBannersIndexClass;?>" data-class="<?=$subtype?>_drag" data-order="<?=++$key;?>">
					<?=CMax::ShowPageType('mainpage', $subtype, $strTemplateName);?>
				</div>
			<?endif;?>
		<?endif;?>

		<?//SALE?>
		<?if($optionCode == "SALE"):?>
			<?global $bShowSale, $bSaleIndexClass;?>
			<?if($bShowSale):?>
				<div class="drag-block container grey <?=$optionCode?> <?=$bSaleIndexClass;?>" data-class="<?=$subtype?>_drag" data-order="<?=++$key;?>">
					<?=CMax::ShowPageType('mainpage', $subtype, $strTemplateName, true);?>
				</div>
			<?endif;?>
		<?endif;?>

		<?//COLLECTIONS?>
		<?if($optionCode == "COLLECTIONS"):?>
			<?global $bShowCollection, $bCollectionIndexClass;?>
			<?if($bShowCollection):?>
				<div class="drag-block container grey <?=$optionCode?> <?=$bCollectionIndexClass;?>" data-class="<?=$subtype?>_drag" data-order="<?=++$key;?>">
					<?=CMax::ShowPageType('mainpage', $subtype, $strTemplateName, true);?>
				</div>
			<?endif;?>
		<?endif;?>

		<?//LOOKBOOKS?>
		<?if($optionCode == "LOOKBOOKS"):?>
			<?global $bShowLookbook, $bLookbookIndexClass;?>
			<?if($bShowLookbook):?>
				<div class="drag-block container grey <?=$optionCode?> <?=$bLookbookIndexClass;?>" data-class="<?=$subtype?>_drag" data-order="<?=++$key;?>">
					<?=CMax::ShowPageType('mainpage', $subtype, $strTemplateName, true);?>
				</div>
			<?endif;?>
		<?endif;?>

		<?//REVIEWS?>
		<?if($optionCode == "REVIEWS"):?>
			<?global $bShowReview, $bReviewIndexClass;?>
			<?if($bShowReview):?>
				<div class="drag-block container grey <?=$optionCode?> <?=$bReviewIndexClass;?>" data-class="<?=$subtype?>_drag" data-order="<?=++$key;?>">
					<?=CMax::ShowPageType('mainpage', $subtype, $strTemplateName);?>
				</div>
			<?endif;?>
		<?endif;?>

		<?//NEWS?>
		<?if($optionCode == "NEWS"):?>
			<?global $bShowNews, $bNewsIndexClass;?>
			<?if($bShowNews):?>
				<div class="drag-block container grey <?=$optionCode?> <?=$bNewsIndexClass;?>" data-class="<?=$subtype?>_drag" data-order="<?=++$key;?>">
					<?=CMax::ShowPageType('mainpage', $subtype, $strTemplateName, true);?>
				</div>
			<?endif;?>
		<?endif;?>

		<?//BLOG?>
		<?if($optionCode == "BLOG"):?>
			<?global $bShowBlog, $bBlogIndexClass;?>
			<?if($bShowBlog):?>
				<div class="drag-block container <?=$optionCode?> <?=$bBlogIndexClass;?>" data-class="<?=$subtype?>_drag" data-order="<?=++$key;?>">
					<?=CMax::ShowPageType('mainpage', $subtype, $strTemplateName, true);?>
				</div>
			<?endif;?>
		<?endif;?>

		<?//BOTTOM_BANNERS?>
		<?if($optionCode == "BOTTOM_BANNERS"):?>
			<?global $bShowBottomBanner, $bBottomBannersIndexClass;?>
			<?if($bShowBottomBanner):?>
				<div class="drag-block container <?=$optionCode?> <?=$bBottomBannersIndexClass;?>" data-class="<?=$subtype?>_drag" data-order="<?=++$key;?>">
					<?=CMax::ShowPageType('mainpage', $subtype, $strTemplateName);?>
				</div>
			<?endif;?>
		<?endif;?>

		<?//COMPANY_TEXT?>
		<?if($optionCode == "COMPANY_TEXT"):?>
			<?global $bShowCompany, $bCompanyTextIndexClass;?>
			<?if($bShowCompany):?>
				<div class="drag-block container <?=$optionCode?> <?=$bCompanyTextIndexClass;?>" data-class="<?=$subtype?>_drag" data-order="<?=++$key;?>">
					<?=CMax::ShowPageType('mainpage', $subtype, $strTemplateName);?>
				</div>
			<?endif;?>
		<?endif;?>

		<?//MAPS?>
		<?if($optionCode == "MAPS"):?>
			<?global $bShowMaps, $bMapsIndexClass;?>
			<?if($bShowMaps):?>
				<div class="drag-block container <?=$optionCode?> <?=$bMapsIndexClass;?> js-load-block loader_circle" data-class="<?=$subtype?>_drag" data-order="<?=++$key;?>" data-file="<?=SITE_DIR;?>include/mainpage/components/<?=$subtype;?>/<?=$strTemplateName;?>.php">
					<?=CMax::ShowPageType('mainpage', $subtype, $strTemplateName);?>
				</div>
			<?endif;?>
		<?endif;?>

		<?//FAVORIT_ITEM?>
		<?if($optionCode == "FAVORIT_ITEM"):?>
			<?global $bShowFavoritItem, $bFavoritItemIndexClass;?>
			<?if($bShowFavoritItem):?>
				<div class="drag-block container <?=$optionCode?> <?=$bFavoritItemIndexClass;?> js-load-block loader_circle" data-class="<?=$subtype?>_drag" data-order="<?=++$key;?>" data-file="<?=SITE_DIR;?>include/mainpage/components/<?=$subtype;?>/<?=$strTemplateName;?>.php">
					<?=CMax::ShowPageType('mainpage', $subtype, $strTemplateName);?>
				</div>
			<?endif;?>
		<?endif;?>

		<?//BRANDS?>
		<?if($optionCode == "BRANDS"):?>
			<?global $bShowBrands, $bBrandsIndexClass;?>
			<?if($bShowBrands):?>
				<div class="drag-block container <?=$optionCode?> <?=$bBrandsIndexClass;?>" data-class="<?=$subtype?>_drag" data-order="<?=++$key;?>">
					<?=CMax::ShowPageType('mainpage', $subtype, $strTemplateName, true);?>
				</div>
			<?endif;?>
		<?endif;?>

		<?//INSTAGRAMM?>
		<?if($optionCode == "INSTAGRAMM"):?>
			<?global $bShowInstagramm, $bInstagrammIndexClass;?>
			<?if($bShowInstagramm):?>
				<div class="drag-block container <?=$optionCode?> <?=$bInstagrammIndexClass;?> js-load-block loader_circle" data-class="<?=$subtype?>_drag" data-order="<?=++$key;?>" data-file="<?=SITE_DIR;?>include/mainpage/components/<?=$subtype;?>/<?=$strTemplateName;?>.php">
					<?=CMax::ShowPageType('mainpage', $subtype, $strTemplateName);?>
				</div>
			<?endif;?>
		<?endif;?>

		<?//VK?>
		<?if($optionCode === "VK"):?>
			<?global $bShowVK, $bVKIndexClass;?>
			<?if($bShowVK):?>
				<div class="drag-block container <?=$optionCode?> <?=$bVKIndexClass;?> js-load-block loader_circle" data-class="<?=$subtype?>_drag" data-order="<?=++$key;?>" data-file="<?=SITE_DIR;?>include/mainpage/components/<?=$subtype;?>/<?=$strTemplateName;?>.php">
					<?=CMax::ShowPageType('mainpage', $subtype, $strTemplateName);?>
				</div>
			<?endif;?>
		<?endif;?>
	<?endforeach;?>
<?endif;?>
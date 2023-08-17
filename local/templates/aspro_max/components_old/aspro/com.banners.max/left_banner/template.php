<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<div class="banners_column">
	<div class="small_banners_block">
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<?
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			$img = (($arItem["PREVIEW_PICTURE"] || $arItem["DETAIL_PICTURE"]) ? CFile::ResizeImageGet(($arItem["PREVIEW_PICTURE"] ? $arItem["PREVIEW_PICTURE"] : $arItem["DETAIL_PICTURE"]), array("width" => 400, "height" => 400), BX_RESIZE_IMAGE_PROPRTIONAL_ALT , true) : false);

			$bUrl = (isset($arItem['PROPERTIES']['URL_STRING']) && $arItem['PROPERTIES']['URL_STRING']['VALUE']);
			$sUrl = ($bUrl ? str_replace('//', '/', SITE_DIR.$arItem['PROPERTIES']['URL_STRING']['VALUE']) : '');
			?>
			<?if($img):?>
				<div class="advt_banner shine" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
					<?if($sUrl):?>
						<a href="<?=$sUrl?>" <?=($arItem["PROPERTIES"]["TARGETS"]["VALUE_XML_ID"] ? "target='".$arItem["PROPERTIES"]["TARGETS"]["VALUE_XML_ID"]."'" : "");?>>
					<?endif;?>
						<img src="<?=$img["src"]?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" />
					<?if($sUrl):?>
						</a>
					<?endif;?>
				</div>
			<?endif;?>
		<?endforeach;?>
	</div>
</div>
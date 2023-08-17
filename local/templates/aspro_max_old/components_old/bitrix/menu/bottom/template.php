<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?
$this->setFrameMode(true);
$colmd = 12;
$colsm = 12;
// print_r($arResult);
?>
<?if($arResult):?>
	<?
	if(!function_exists("ShowSubItems2")){
		function ShowSubItems2($arItem){
			?>
			
			<?
		}
	}
	?>
	<div class="bottom-menu">
		<div class="items">
			<?$indexSection = $arParams['ROOT_MENU_TYPE'];?>
			<?$lastIndex = count($arResult) - 1;?>
			<?foreach($arResult as $i => $arItem):?>
				<?if($i === 1 && !$arItem["CHILD"]):?>
					<div id="<?=$indexSection?>" class="wrap panel-collapse wrap_compact_mobile">
				<?endif;?>
				<?$bLink = strlen($arItem['LINK']);?>
				<?$bAccordion = ($i === 0 && !$arItem["CHILD"]) || $arItem["CHILD"];?>
				<?$bChilds = $arItem["CHILD"] || ( $i == 0 && isset($arResult[1]) );?>
 				<div class="item <?=$i?> <?=$bChilds ? 'childs' : ''?> <?=($arItem["SELECTED"] ? " active" : "")?> <?=($bAccordion ? " accordion-close" : "")?> " <?=($bAccordion ? 'data-parent="#'.$indexSection.'" data-target="#'.$indexSection.'"' : '')?> >
					<div class="title">
						<?if($bLink):?>
							<a href="<?=$arItem['LINK']?>"><?=$arItem['TEXT']?></a>
						<?else:?>
							<span><?=$arItem['TEXT']?></span>
						<?endif;?>
					</div>
					<?if($bAccordion):?>
						<div class="compact_arrow">
	                    	<?=CMax::showIconSvg("down colored_theme_hover_bg-el", SITE_TEMPLATE_PATH.'/images/svg/arrow_catalogcloser.svg', '', '', true, false);?>
						</div>
					<?endif;?>
				</div>
				<?if($arItem["CHILD"]):?>
					<div id="<?=$indexSection?>" class="wrap panel-collapse wrap_compact_mobile">
						<?foreach($arItem["CHILD"] as $arSubItem):?>
							<?
							if($arSubItem['PARAMS']['TYPE'] == 'PRODUCT'){
								$bProductItem = true;
								continue;
							}
							?>
							<?$bLink = strlen($arSubItem['LINK']);?>
							<div class="item<?=($arSubItem["SELECTED"] ? " active" : "")?>">
								<div class="title">
									<?if($bLink):?>
										<a href="<?=$arSubItem['LINK']?>"><?=$arSubItem['TEXT']?></a>
									<?else:?>
										<span><?=$arSubItem['TEXT']?></span>
									<?endif;?>
								</div>
							</div>
						<?endforeach;?>
					</div>
				<?endif;?>
				<?if($i && $i === $lastIndex && !$arItem["CHILD"]):?>
					</div>
				<?endif;?>
			<?endforeach;?>
		</div>
	</div>
<?endif;?>
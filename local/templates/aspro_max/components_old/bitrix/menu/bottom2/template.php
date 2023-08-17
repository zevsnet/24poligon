<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?
$this->setFrameMode(true);
$colmd = 12;
$colsm = 12;
?>
<?if($arResult):?>
	<?
	if(!function_exists("ShowSubItems2")){
		function ShowSubItems2($arItem){
			?>
			<?if($arItem["CHILD"]):?>
				<?$noMoreSubMenuOnThisDepth = false;
				$count = count($arItem["CHILD"]);?>
				<?$lastIndex = count($arItem["CHILD"]) - 1;?>
				
				<?foreach($arItem["CHILD"] as $i => $arSubItem):?>
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
					<?/*if(!$noMoreSubMenuOnThisDepth):?>
						<?ShowSubItems($arSubItem);?>
					<?endif;*/?>
					<?$noMoreSubMenuOnThisDepth |= CMax::isChildsSelected($arSubItem["CHILD"]);?>
				<?endforeach;?>
				
			<?endif;?>
			<?
		}
	}
	// print_r($arResult);
	?>
	<div class="bottom-menu second">
		<div class="items">
			<?$lastIndex = count($arResult) - 1;?>
			<?foreach($arResult as $i => $arItem):?>
				<?$bLink = strlen($arItem['LINK']);?>
				<div class="item<?=($arItem["SELECTED"] ? " active" : "")?>">
					<div class="title">
						<?if($bLink):?>
							<a href="<?=$arItem['LINK']?>"><?=$arItem['TEXT']?></a>
						<?else:?>
							<span><?=$arItem['TEXT']?></span>
						<?endif;?>
					</div>
				</div>
				<?ShowSubItems2($arItem);?>
			<?endforeach;?>
		</div>
	</div>
<?endif;?>
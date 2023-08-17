<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?if($arResult):?>

<div class="menu_top_block menu-type1">
	<ul class="left_menu dropdown">
		<?foreach($arResult as $arItem):?>
			<?
			$exitItem = false;
			$arItem["CHILD"] = $arItem["CHILD"] ?? [];
			?>
			<?if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) continue;?>
			<li class="v_bottom item <?if($arItem["SELECTED"]){?> current opened <?}?> <?=($arItem["CHILD"] ? "has-child" :"");?> <?//=($arItem["CHILD"] ? "has-childs" :"");?> item <?=(isset($arItem["PARAMS"]["class"]) && strlen($arItem["PARAMS"]["class"]) ? $arItem["PARAMS"]["class"] : '')?>">
				<?
				if( isset($arItem["PARAMS"]["class"]) && $arItem["PARAMS"]["class"] === 'exit' ){
					$arItem["LINK"].= '&'.bitrix_sessid_get();
					$exitItem = true;
				}
				?>

				<a class="icons_fa<?=($arItem["CHILD"] ? " parent" : "");?> rounded2 bordered darken" href="<?=$arItem["LINK"]?>">
					<?if($exitItem):?>
						<?=CMax::showIconSvg("exit", SITE_TEMPLATE_PATH.'/images/svg/exit.svg', '', '', true, false);?>
					<?endif;?>
				

					<?if($arItem["CHILD"]):?>
						<?=CMax::showSpriteIconSvg(SITE_TEMPLATE_PATH."/images/svg/trianglearrow_sprite.svg#trianglearrow_down", "svg-inline-down", ['WIDTH' => 5,'HEIGHT' => 3, 'INLINE' => 'N']);?>
					<?endif;?>
					<span class="name"><?=$arItem["TEXT"]?></span>
					<span class="toggle_block"></span>
				</a>
				<?if($arItem["CHILD"]){?>
					<div class="child_container dropdown">
						<div class="child_wrapp">
							<ul class="child">
								<?foreach($arItem["CHILD"] as $arChildItem){?>
									<li class="menu_item hover_color_theme <?if($arChildItem["SELECTED"]){?> current <?}?>"><a href="<?=$arChildItem["LINK"];?>"><?=$arChildItem["TEXT"];?></a></li>
								<?}?>
							</ul>
						</div>
					</div>
				<?}?>
			</li>
		<?endforeach;?>
	</ul>
</div>
	<script>
		$('.left_menu').ready(function(){
			$('.left_menu > li').each(function(){
				if($(this).find('.child_container li.current').length){
					$(this).addClass('current opened');
				}
			});
		})
	</script>

<?endif;?>
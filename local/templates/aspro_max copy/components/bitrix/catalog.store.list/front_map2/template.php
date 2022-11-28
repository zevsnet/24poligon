<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<?
$templateData = array(
	'MAP_ITEMS' => $arResult['MAP_ITEMS']
);
?>
<?if($arResult['MAP_ITEMS']):?>
	<div class="content_wrapper_block map_type_2 <?=$templateName;?>">
		<div class="maxwidth-theme">
	<div class="wrapper_block with_title title_left">
		<?if($arParams['TITLE_BLOCK'] || $arParams['TITLE_BLOCK_ALL']):?>
			<div class="top_block">
				<h3><?=$arParams['TITLE_BLOCK'];?></h3>
				<a href="<?=SITE_DIR.$arParams['ALL_URL'];?>" class="pull-right font_upper"><?=$arParams['TITLE_BLOCK_ALL'] ;?></a>
			</div>
		<?endif;?>
		<?$nCountItems = count($arResult['MAP_ITEMS']);?>
		<div class="block_container bordered <?=($nCountItems == 1 ? 'one' : '');?>">
			<div class="items" <?=($nCountItems == 1 ? "style='display:none;'" : "")?>>
				<div class="items-inner">
					<?foreach($arResult['MAP_ITEMS'] as $arItem):?>
						<div class="item" data-coordinates="<?=$arItem['GPS_N'].','.$arItem['GPS_S'];?>" data-id="<?=$arItem['ID']?>">
							<div class="title option-font-bold font_sm"><?=$arItem['NAME']?></div>
							<?if($arItem['PHONE']):?>
								<div class="phones">
									<?if(is_array($arItem['PHONE'])):?>
										<?foreach($arItem['PHONE'] as $value):?>
											<div class="value"><a class="muted font_xs" rel= "nofollow" href="tel:<?=str_replace(array(' ', ',', '-', '(', ')'), '', $value)?>"><?=$value;?></a></div>
										<?endforeach;?>
									<?else:?>
										<div class="value"><a class="muted font_xs" rel= "nofollow" href="tel:<?=str_replace(array(' ', ',', '-', '(', ')'), '', $arItem['PHONE'])?>"><?=$arItem['PHONE'];?></a></div>
									<?endif;?>
								</div>
							<?endif;?>
						</div>
					<?endforeach;?>
				</div>
			</div>
			<div class="detail_items" <?=($nCountItems == 1 ? "style='display:block;'" : "")?>>
				<?foreach($arResult['MAP_ITEMS'] as $arItem):?>
					<div class="item" <?=($nCountItems == 1 ? "style='display:block;'" : "")?> data-coordinates="<?=$arItem['GPS_N'].','.$arItem['GPS_S'];?>" data-id="<?=$arItem['ID']?>">
						<?=CMax::prepareItemMapHtml($arItem, "Y");?>
						<div class="top-close muted svg">
							<svg class="svg-close" width="14" height="14" viewBox="0 0 14 14"><path data-name="Rounded Rectangle 568 copy 16" class="cls-1" d="M1009.4,953l5.32,5.315a0.987,0.987,0,0,1,0,1.4,1,1,0,0,1-1.41,0L1008,954.4l-5.32,5.315a0.991,0.991,0,0,1-1.4-1.4L1006.6,953l-5.32-5.315a0.991,0.991,0,0,1,1.4-1.4l5.32,5.315,5.31-5.315a1,1,0,0,1,1.41,0,0.987,0.987,0,0,1,0,1.4Z" transform="translate(-1001 -946)"></path></svg>
						</div>
						<div class="buttons_block">
							<span class="btn btn-transparent-border-color btn-sm animate-load" data-event="jqm" data-param-form_id="ASK" data-name="question"><?=GetMessage('SEND_MESSAGE_BUTTON');?>
							</span>
						</div>
					</div>
				<?endforeach;?>
			</div>
		</div>
	</div>
<?endif;?>
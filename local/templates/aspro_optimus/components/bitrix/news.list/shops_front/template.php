<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?if($arResult["ITEMS"]):?>
	<div class="block_wr parrallax-shops" >
		<div class="bg_map"></div>
		<div class="wrapper_inner">
			<div class="stores news">
				<div class="top_block">
					<?
					$title_block=($arParams["TITLE_BLOCK"] ? $arParams["TITLE_BLOCK"] : GetMessage('STORES_TITLE'));
					$listurl = str_replace('//', '/', str_replace(array('#'.'SITE_DIR'.'#'), array(SITE_DIR), $arResult['LIST_PAGE_URL']));
					$count= ceil(count($arResult["ITEMS"]) / 3);
					?>
					<div class="title_block"><?=$title_block?></div>
					<a href="<?=$listurl?>"><?=GetMessage('ALL_STORES')?></a>
				</div>
				<div class="stores_list">
					<div class="stores_navigation slider_navigation"></div>
					<ul class="stores_list_wr wr">
						<?foreach($arResult["ITEMS"] as $arItem):?>
							<li class="item">
								<div class="wrapp_block">
									<?if(in_array('NAME', $arParams['FIELD_CODE'])):?>
										<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><span class="icon"></span><span class="text"><?=$arItem["NAME"]?></span></a>
									<?endif;?>
									<?if(strlen($arItem['PROPERTIES']["ADDRESS"]['VALUE']) && in_array('ADDRESS', $arParams["PROPERTY_CODE"])):?>
										<div class="store_text">
											<span class="title"><?=GetMessage('ADDRESS')?></span>
											<span class="value"><?=$arItem['PROPERTIES']["ADDRESS"]['VALUE']?></span>
										</div>
										<div class="clear"></div>
									<?endif;?>
									<?if(strlen($arItem['PROPERTIES']["PHONE"]['VALUE']) && in_array('PHONE', $arParams["PROPERTY_CODE"])):?>
										<div class="store_text">
											<span class="title"><?=GetMessage('PHONE')?></span>
											<span class="value"><?=$arItem['PROPERTIES']["PHONE"]['VALUE']?></span>
										</div>
										<div class="clear"></div>
									<?endif;?>
									<?if(strlen($arItem['PROPERTIES']["EMAIL"]['VALUE']) && in_array('EMAIL', $arParams["PROPERTY_CODE"])):?>
										<div class="store_text">
											<span class="title"><?=GetMessage('EMAIL')?></span>
											<span class="value"><?=$arItem['PROPERTIES']["EMAIL"]['VALUE']?></span>
										</div>
										<div class="clear"></div>
									<?endif;?>
									<?if($arItem['PROPERTIES']["METRO"]['VALUE'] && in_array('METRO', $arParams["PROPERTY_CODE"])):?>
										<div class="store_text metro">
											<?foreach($arItem['PROPERTIES']["METRO"]['VALUE'] as $metro):?>
												<span class="value"><i></i><?=$metro?></span>
											<?endforeach;?>
										</div>
										<div class="clear"></div>
									<?endif;?>
									<?if($arItem['PROPERTIES']["SCHEDULE"]['VALUE'] && strlen($arItem['PROPERTIES']["SCHEDULE"]['VALUE']['TEXT']) && in_array('SCHEDULE', $arParams["PROPERTY_CODE"])):?>
										<div class="store_text">
											<span class="title clear"><?=GetMessage('SCHEDULE')?></span>
											<span class="value"><?=($arItem['PROPERTIES']["SCHEDULE"]['VALUE']['TYPE'] == 'html' ? htmlspecialchars_decode($arItem['PROPERTIES']["SCHEDULE"]['VALUE']['TEXT']) : nl2br($arItem['PROPERTIES']["SCHEDULE"]['VALUE']['TEXT']))?></span>
										</div>
										<div class="clear"></div>
									<?endif;?>
								</div>
							</li>
						<?endforeach;?>
					</ul>
					<ul class="flex-control-nav flex-control-paging">
						<?for($i = 1; $i <= $count; ++$i):?>
							<li>
								<a></a>
							</li>
						<?endfor;?>
					</ul>
				</div>
				<div class="all_map">
					<a href="<?=$listurl?>" class="wrapp_block">
						<div class="icon"></div>
						<div class="text"><?=GetMessage('ALL_STORES_ON_MAP')?></div>
					</a>
				</div>
			</div>
		</div>
	</div>
<?endif;?>


<script>
	$(window).scroll(function() {
		var x = $(window).scrollTop()/$(document).height();			
		x=parseInt(-x * 360);		
		$('.parrallax-shops .bg_map').stop().animate({'background-position-x': 'center', 'background-position-y':  x + 'px' }, 700, 'swing');				
	});

	var timeoutSlide;
	InitFlexSlider = function() {
		var flexsliderItemWidth = 268,
			flexsliderItemMargin = 20;
		$(".stores .stores_list").flexslider({
			animation: "slide",
			selector: ".stores_list_wr > li",
			slideshow: false,
			slideshowSpeed: 6000,
			animationSpeed: 600,
			directionNav: true,
			pauseOnHover: true,
			animationLoop: true, 
			controlsContainer: ".stores_navigation",
			itemWidth: flexsliderItemWidth,
			itemMargin: flexsliderItemMargin, 
			start:function(slider){
				$('.flex-control-nav li a').on('touchend', function(){
					$(this).addClass('touch');
				})
				slider.find('li').css('opacity', 1);
			}
		});
		$('.stores').equalize({children: '.wrapp_block', reset: true});
	}
	$(document).ready(function(){
		$(window).resize(function(){
			clearTimeout(timeoutSlide);
			timeoutSlide = setTimeout(InitFlexSlider(), 50);
			$('.stores .flex-viewport .stores_list_wr').equalize({children: '.item'});
		})
	});
</script>
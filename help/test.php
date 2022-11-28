<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Помощь");
?>
<div class="block_w1">
<div class="stores1 news">
	<div class="stores_list1">
		<div class="flexslider unstyled row" data-plugin-options='{"animation": "slide", "directionNav": true, "controlNav" :true, "animationLoop": true, "slideshow": false}'>
		<ul class="stores_list_wr wr">
								<li class="item" style="width: 216px; float: left; display: block; opacity: 1;">
			<div class="wrapp_block" style="height: 76px;">
				<a href="contacts/stores/1/"><span class="icon"></span><span class="text">г. Москва</span></a>
														<div class="store_text">
						<span class="title">Адрес:</span>
						<span class="value">ул. Большая, 7/10</span>
					</div>
					<div class="clear"></div>
																							<div class="store_text">
						<span class="title">Телефон:</span>
						<span class="value">+7 (888) 987-65-43</span>
					</div>
					<div class="clear"></div>
												</div>
		</li>
								<li class="item" style="width: 216px; float: left; display: block; opacity: 1;">
			<div class="wrapp_block" style="height: 76px;">
				<a href="contacts/stores/5/"><span class="icon"></span><span class="text">г. Москва</span></a>
														<div class="store_text">
						<span class="title">Адрес:</span>
						<span class="value">ул. Охотный Ряд, 2</span>
					</div>
					<div class="clear"></div>
																							<div class="store_text">
						<span class="title">Телефон:</span>
						<span class="value">+7 (888) 987-65-43</span>
					</div>
					<div class="clear"></div>
												</div>
		</li>
								<li class="item" style="width: 216px; float: left; display: block; opacity: 1;">
			<div class="wrapp_block" style="height: 76px;">
				<a href="contacts/stores/3/"><span class="icon"></span><span class="text">г. Нижний Новгород</span></a>
														<div class="store_text">
						<span class="title">Адрес:</span>
						<span class="value">пр. Ленина, 12</span>
					</div>
					<div class="clear"></div>
																							<div class="store_text">
						<span class="title">Телефон:</span>
						<span class="value">+7 (888) 987-65-43</span>
					</div>
					<div class="clear"></div>
												</div>
		</li>
								<li class="item" style="width: 216px; float: left; display: block; opacity: 1;">
			<div class="wrapp_block" style="height: 76px;">
				<a href="contacts/stores/2/"><span class="icon"></span><span class="text">г. Санкт-Петербург</span></a>
														<div class="store_text">
						<span class="title">Адрес:</span>
						<span class="value">Невский проспект, 35</span>
					</div>
					<div class="clear"></div>
																							<div class="store_text">
						<span class="title">Телефон:</span>
						<span class="value">+7 (888) 987-65-43</span>
					</div>
					<div class="clear"></div>
												</div>
		</li>
								<li class="item" style="width: 216px; float: left; display: block; opacity: 1;">
			<div class="wrapp_block" style="height: 76px;">
				<a href="contacts/stores/4/"><span class="icon"></span><span class="text">г. Челябинск</span></a>
														<div class="store_text">
						<span class="title">Адрес:</span>
						<span class="value">ул. Артиллерийская, 11</span>
					</div>
					<div class="clear"></div>
																							<div class="store_text">
						<span class="title">Телефон:</span>
						<span class="value">+7 (888) 987-65-43</span>
					</div>
					<div class="clear"></div>
												</div>
		</li>
						</ul>
		</div>
		<ul class="flex-control-nav flex-control-paging">
						<?for($i=1;$i<=$count;$i++){?>
							<li>
								<a></a>
							</li>
						<?}?>
					</ul>
	</div>
</div>
</div>
<script>
	InitFlexSlider1 = function() {
		var flexsliderItemWidth = 268,
			flexsliderItemMargin = 20;
		$(".stores .stores_list").flexslider({
			animation: "slide",
			selector: ".stores_list_wr > li",
			slideshow: false,
			slideshowSpeed: 6000,
			animationSpeed: 600,
			directionNav: true,
			//controlNav: false,
			pauseOnHover: true,
			animationLoop: true, 
			controlsContainer: ".stores_navigation",
			itemWidth: flexsliderItemWidth,
			itemMargin: flexsliderItemMargin, 
			manualControls: ".block_wr .flex-control-nav.flex-control-paging li a"
		});
		$('.stores').equalize({children: '.wrapp_block', reset: true});
	}
	getGridSize = function(counts) {
		var z = parseInt($('.body_media').css('top'));
		return (z == 2 ? counts[0] : z == 1 ? counts[1] : counts[2]);
	}
	CheckFlexSlider = function(){
		$('.flexslider').each(function(){
			var slider = $(this);
			slider.resize();
			/*var counts = slider.data('flexslider').vars.counts;
			if(typeof(counts) != 'undefined'){
				var cnt = getGridSize(counts);
				var to0 = (cnt != slider.data('flexslider').vars.minItems || cnt != slider.data('flexslider').vars.maxItems || cnt != slider.data('flexslider').vars.move);
				if(to0){
					slider.data('flexslider').vars.minItems = cnt;
					slider.data('flexslider').vars.maxItems = cnt;
					slider.data('flexslider').vars.move = cnt;
					slider.flexslider(0);
					slider.resize();
					slider.resize(); // twise!
				}
			}*/
		});
	}
	$(document).ready(function(){
		$(window).resize(function(){
			// InitFlexSlider();
			//CheckFlexSlider();
		})
	})
</script>
<?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"news_akc_slider_test", 
	array(
		"IBLOCK_TYPE" => "aspro_kshop_content",
		"IBLOCK_ID" => "12",
		"NEWS_COUNT" => "20",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_ORDER1" => "DESC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"FILTER_NAME" => "",
		"FIELD_CODE" => array(
			0 => "DETAIL_PICTURE",
			1 => "",
		),
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "N",
		"PREVIEW_TRUNCATE_LEN" => "140",
		"ACTIVE_DATE_FORMAT" => "j F Y",
		"SET_TITLE" => "N",
		"SET_STATUS_404" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" => "Y",
		"PAGER_TEMPLATE" => "",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"PAGER_TITLE" => "",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"COMPONENT_TEMPLATE" => "news_akc_slider",
		"SET_BROWSER_TITLE" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_META_DESCRIPTION" => "N",
		"TITLE_BLOCK" => "Действующие акции",
		"ALL_URL" => "sale/"
	),
	false
);?>
 <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
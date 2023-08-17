<?
$bUseMap = CMax::GetFrontParametrValue('CONTACTS_USE_MAP', SITE_ID) != 'N';
$bUseFeedback = CMax::GetFrontParametrValue('CONTACTS_USE_FEEDBACK', SITE_ID) != 'N';
$showMap = $bUseMap;

use Poligon\Core\Aspro\CMaxCustom; ?>

<?CMax::ShowPageType('page_title');?>
<?global $USER;
if(!$USER->IsAdmin()):?>
<div class="wrapper_inner_half row flexbox shop-detail1 clearfix" itemscope itemtype="http://schema.org/Organization">
	<div class="item item-shop-detail1  <?=($showMap ? 'col-md-6' : 'col-md-12')?>">
		<div class="left_block_store <?=($showMap ? '' : 'margin0')?>">
			<div class="top_block">
				<?CMax::showContactImg();?>
				
				<?if($arPhotos):?>
					<!-- noindex-->
					<div class="gallery_wrap swipeignore">
						<?//gallery?>
						<div class="big-gallery-block text-center">
						    <div class="owl-carousel owl-theme owl-bg-nav short-nav" data-slider="content-detail-gallery__slider" data-plugin-options='{"items": "1", "autoplay" : false, "autoplayTimeout" : "3000", "smartSpeed":1000, "dots": true, "nav": true, "loop": false, "rewind":true, "margin": 10}'>
							<?foreach($arPhotos as $i => $arPhoto):?>
							    <div class="item">
								<a href="<?=$arPhoto['ORIGINAL']?>" class="fancy" data-fancybox="item_slider" target="_blank" title="<?=$arPhoto['DESCRIPTION']?>">
									<img data-src="<?=$arPhoto['PREVIEW']['src']?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($arPhoto['PREVIEW']['src']);?>" class="img-responsive inline lazy" alt="<?=$arPhoto['DESCRIPTION']?>" />
								</a>
							    </div>
							<?endforeach;?>
						    </div>
						</div>
					</div>
					<!-- /noindex-->
				<?endif;?>
			</div>
			<div class="bottom_block">
				
				

				<div class="properties clearfix sb_border_block">
					<div class="col-md-12 col-sm-12 pb-3"><span class="h3">Адрес в #REGION_NAME_DECLINE_PP#</span></div>
					<div class="col-md-6 col-sm-6">
						<?//CMaxCustom::showContactAddrContact('Адрес', false,'sb_address_color');?>
						<?=CMax::showAddress('address blocks')?>
					</div>
					<div class="col-md-6 col-sm-6">
						<?CMax::showContactPhones('Телефон', false);?>
						<?CMax::showContactEmail('E-mail', false);?>
					</div>
				</div>


				<div class="properties clearfix sb_border_block">
          <div class="col-md-12 col-sm-12 pb-3"><span class="h3">Основной офис компании</span></div>
					<div class="col-md-6 col-sm-6">
						<?CMaxCustom::showContactAddrContact('Адрес', false,'sb_address_color');?>
						<?=CMax::showAddress('address blocks')?>
					</div>
					<div class="col-md-6 col-sm-6">
						<?CMax::showContactPhones('Телефон', false);?>
						<?CMax::showContactEmail('E-mail', false);?>
					</div>
				</div>
				
				
        <div class="properties clearfix sb_border_block">
            <div class="col-md-12 col-sm-12 pb-3"><span class="h3">Отдел по обслуживанию оптовых и юр.лиц</span></div>
	<div class="col-md-6 col-sm-6">
                <div class="property phone">
                    <div class="title font_upper muted">Телефон</div>
                    <div class="">
                        <div class="value darken" itemprop="telephone"><a title="звонок бесплатный по РФ" href="tel:+79676123562">+7 967 612-35-62</a></div>
                    </div>
                </div>
	</div>
	<div class="col-md-6 col-sm-6">

                <div class="property email">
                    <div class="title font_upper muted">E-mail</div>
                    <div class="">
                        <div class="value darken" itemprop="email">
                            <a href="mailto:ra@24poligon.ru">ra@24poligon.ru</a>
                        </div>
                    </div>
                </div>
					</div>
				</div>
				<div class="social-block">
					<div class="wrap">
						<?$APPLICATION->IncludeComponent(
						    "aspro:social.info.max",
						    ".default",
						    array(
						        "CACHE_TYPE" => "A",
						        "CACHE_TIME" => "3600000",
						        "CACHE_GROUPS" => "N",
						        "TITLE_BLOCK" => "",
						        "COMPONENT_TEMPLATE" => ".default",
						    ),
						    false, array("HIDE_ICONS" => "Y")
						);?>
					</div>
				</div>
				<div class="feedback item">
					<div class="wrap">
						<?//if($arShop['DESCRIPTION']):?>
							<?CMax::showContactDesc();?>
						<?//endif;?>
						<?if($bUseFeedback):?>
							<div class="button_wrap">
								<span>
									<span class="btn  btn-transparent-border-color white  animate-load" data-event="jqm" data-param-form_id="ASK" data-name="contacts">Написать сообщение</span>
								</span>
							</div>
						<?endif;?>
					</div>
				</div>
			</div>
			<div class="clearboth"></div>
		</div>
	</div>
	<?if($showMap):?>
		<div class="item col-md-6 map-full padding0">
			<div class="right_block_store contacts_map">
				<?$APPLICATION->IncludeFile(SITE_DIR."include/contacts-site-map.php", Array(), Array("MODE" => "html", "TEMPLATE" => "include_area.php", "NAME" => "Карта"));?>
			</div>
		</div>
	<?endif;?>
	<?//hidden text for validate microdata?>
	<div class="hidden">
		<?global $arSite;?>
		<span itemprop="name"><?=$arSite["NAME"];?></span>
	</div>
</div>
<?else:?>

<div class="contacts-v2">
<?$APPLICATION->IncludeComponent(
	"bitrix:news", 
	"mx_contacts", 
	array(
		"IBLOCK_TYPE" => "aspro_max_content",
		"IBLOCK_ID" => "165",
		"NEWS_COUNT" => "20",
		"USE_SEARCH" => "N",
		"USE_RSS" => "Y",
		"USE_RATING" => "N",
		"USE_CATEGORIES" => "N",
		"USE_FILTER" => "Y",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_ORDER1" => "DESC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"CHECK_DATES" => "Y",
		"SEF_MODE" => "Y",
		"SEF_FOLDER" => "/contacts/",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "100000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "N",
		"SET_TITLE" => "Y",
		"SET_STATUS_404" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"USE_PERMISSIONS" => "N",
		"PREVIEW_TRUNCATE_LEN" => "",
		"LIST_ACTIVE_DATE_FORMAT" => "j F Y",
		"LIST_FIELD_CODE" => array(
			0 => "NAME",
			1 => "PREVIEW_TEXT",
			2 => "PREVIEW_PICTURE",
			3 => "DATE_ACTIVE_FROM",
			4 => "",
		),
		"LIST_PROPERTY_CODE" => array(
			0 => "",
			1 => "PERIOD",
			2 => "REDIRECT",
			3 => "",
		),
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"DISPLAY_NAME" => "N",
		"META_KEYWORDS" => "-",
		"META_DESCRIPTION" => "-",
		"BROWSER_TITLE" => "-",
		"DETAIL_ACTIVE_DATE_FORMAT" => "j F Y",
		"DETAIL_FIELD_CODE" => array(
			0 => "PREVIEW_TEXT",
			1 => "DETAIL_TEXT",
			2 => "DETAIL_PICTURE",
			3 => "DATE_ACTIVE_FROM",
			4 => "",
		),
		"DETAIL_PROPERTY_CODE" => array(
			0 => "",
			1 => "FORM_QUESTION",
			2 => "FORM_ORDER",
			3 => "PHOTOPOS",
			4 => "LINK_GOODS",
			5 => "LINK_SERVICES",
			6 => "LINK_STUDY",
			7 => "VIDEO",
			8 => "PHOTOS",
			9 => "DOCUMENTS",
			10 => "",
		),
		"DETAIL_DISPLAY_TOP_PAGER" => "N",
		"DETAIL_DISPLAY_BOTTOM_PAGER" => "Y",
		"DETAIL_PAGER_TITLE" => "Страница",
		"DETAIL_PAGER_TEMPLATE" => "",
		"DETAIL_PAGER_SHOW_ALL" => "Y",
		"PAGER_TEMPLATE" => ".default",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Новости",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"IMAGE_POSITION" => "left",
		"USE_SHARE" => "Y",
		"AJAX_OPTION_ADDITIONAL" => "",
		"USE_REVIEW" => "N",
		"ADD_ELEMENT_CHAIN" => "Y",
		"SHOW_DETAIL_LINK" => "Y",
		"S_ASK_QUESTION" => "",
		"S_ORDER_SERVISE" => "",
		"T_GALLERY" => "",
		"T_DOCS" => "",
		"T_GOODS" => "",
		"T_SERVICES" => "",
		"T_STUDY" => "",
		"COMPONENT_TEMPLATE" => "contacts",
		"SET_LAST_MODIFIED" => "N",
		"T_VIDEO" => "",
		"DETAIL_SET_CANONICAL_URL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => "",
		"NUM_NEWS" => "20",
		"NUM_DAYS" => "30",
		"YANDEX" => "N",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"SECTIONS_TYPE_VIEW" => "sections_1",
		"SECTION_TYPE_VIEW" => "section_1",
		"SECTION_ELEMENTS_TYPE_VIEW" => "list_elements_2",
		"ELEMENT_TYPE_VIEW" => "element_1",
		"S_ORDER_SERVICE" => "",
		"T_PROJECTS" => "",
		"T_REVIEWS" => "",
		"T_STAFF" => "",
		"IMAGE_CATALOG_POSITION" => "left",
		"SHOW_SECTION_PREVIEW_DESCRIPTION" => "Y",
		"SHOW_SECTION_DESCRIPTION" => "Y",
		"LINE_ELEMENT_COUNT" => "3",
		"LINE_ELEMENT_COUNT_LIST" => "3",
		"SHOW_CHILD_SECTIONS" => "N",
		"GALLERY_TYPE" => "small",
		"INCLUDE_SUBSECTIONS" => "Y",
		"FORM_ID_ORDER_SERVISE" => "",
		"T_MAX_LINK" => "",
		"T_PREV_LINK" => "",
		"SHOW_MAX_ELEMENT" => "N",
		"IMAGE_WIDE" => "N",
		"SHOW_FILTER_DATE" => "Y",
		"FILTER_NAME" => "arRegionLink",
		"FILTER_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"DETAIL_STRICT_SECTION_CHECK" => "N",
		"VIEW_TYPE" => "list",
		"SHOW_TABS" => "Y",
		"SHOW_ASK_QUESTION_BLOCK" => "Y",
		"STRICT_SECTION_CHECK" => "N",
		"SHOW_TOP_MAP" => "N",
		"SEF_URL_TEMPLATES" => array(
			"news" => "",
			"section" => "",
			"detail" => "stores/#ELEMENT_ID#/",
			"rss" => "rss/",
			"rss_section" => "#SECTION_ID#/rss/",
		)
	),
	false
);?>
</div>

<?endif?>
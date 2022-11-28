<? if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") die();

use SB\Site\General; ?>
<? IncludeTemplateLangFile(__FILE__); ?>
<? if (!COptimus::IsMainPage()): ?>
    </div> <? // .container?>
<? endif; ?>
</div>


<? if (!COptimus::IsOrderPage() && !COptimus::IsBasketPage() && !General::IsOrderNewPage()): ?>
    </div> <? // .right_block?>
<? endif; ?>
<? if (COptimus::IsMainPage()): ?>
    <? $APPLICATION->IncludeComponent("bitrix:main.include", ".default",
        array(
            "COMPONENT_TEMPLATE" => ".default",
            "PATH" => SITE_DIR . "include/mainpage/comp_brands.php",
            "AREA_FILE_SHOW" => "file",
            "AREA_FILE_SUFFIX" => "",
            "AREA_FILE_RECURSIVE" => "Y",
            "EDIT_TEMPLATE" => "standard.php"
        ),
        false
    ); ?>
<? endif; ?>
</div> <? // .wrapper_inner?>

</div> <? // #content?>

</div><? // .wrapper?>
<footer id="footer">
    <div class="footer_inner <?= strtolower($TEMPLATE_OPTIONS["BGCOLOR_THEME_FOOTER_SIDE"]["CURRENT_VALUE"]); ?>">

        <? if ($APPLICATION->GetProperty("viewed_show") == "Y" || defined("ERROR_404")): ?>
            <? $APPLICATION->IncludeComponent("bitrix:main.include", ".default",
                array(
                    "COMPONENT_TEMPLATE" => ".default",
                    "PATH" => SITE_DIR . "include/footer/comp_viewed.php",
                    "AREA_FILE_SHOW" => "file",
                    "AREA_FILE_SUFFIX" => "",
                    "AREA_FILE_RECURSIVE" => "Y",
                    "EDIT_TEMPLATE" => "standard.php"
                ),
                false
            ); ?>
        <? endif; ?>
        <div class="wrapper_inner">
            <div class="footer_bottom_inner">
                <div class="left_block">
                    <? $APPLICATION->IncludeComponent("bitrix:main.include", ".default",
                        array(
                            "COMPONENT_TEMPLATE" => ".default",
                            "PATH" => SITE_DIR . "include/footer/copyright.php",
                            "AREA_FILE_SHOW" => "file",
                            "AREA_FILE_SUFFIX" => "",
                            "AREA_FILE_RECURSIVE" => "Y",
                            "EDIT_TEMPLATE" => "standard.php"
                        ),
                        false
                    ); ?>
                    <div id="bx-composite-banner"></div>
                </div>
                <div class="right_block">
                    <div class="middle">
                        <div class="rows_block">
                            <div class="item_block col-75 menus">
                                <? $APPLICATION->IncludeComponent("bitrix:menu", "bottom_submenu_top", array(
                                    "ROOT_MENU_TYPE" => "bottom",
                                    "MENU_CACHE_TYPE" => "Y",
                                    "MENU_CACHE_TIME" => "3600000",
                                    "MENU_CACHE_USE_GROUPS" => "N",
                                    "MENU_CACHE_GET_VARS" => array(),
                                    "MAX_LEVEL" => "1",
                                    "USE_EXT" => "N",
                                    "DELAY" => "N",
                                    "ALLOW_MULTI_SELECT" => "N"
                                ), false
                                ); ?>
                                <div class="rows_block">
                                    <div class="item_block col-3">
                                        <? $APPLICATION->IncludeComponent("bitrix:menu", "bottom_submenu", array(
                                            "ROOT_MENU_TYPE" => "bottom_company",
                                            "MENU_CACHE_TYPE" => "Y",
                                            "MENU_CACHE_TIME" => "3600000",
                                            "MENU_CACHE_USE_GROUPS" => "N",
                                            "MENU_CACHE_GET_VARS" => array(),
                                            "MAX_LEVEL" => "1",
                                            "USE_EXT" => "N",
                                            "DELAY" => "N",
                                            "ALLOW_MULTI_SELECT" => "N"
                                        ), false
                                        ); ?>
                                    </div>
                                    <div class="item_block col-3">
                                        <? $APPLICATION->IncludeComponent("bitrix:menu", "bottom_submenu", array(
                                            "ROOT_MENU_TYPE" => "bottom_info",
                                            "MENU_CACHE_TYPE" => "Y",
                                            "MENU_CACHE_TIME" => "3600000",
                                            "MENU_CACHE_USE_GROUPS" => "N",
                                            "MENU_CACHE_GET_VARS" => array(),
                                            "MAX_LEVEL" => "1",
                                            "USE_EXT" => "N",
                                            "DELAY" => "N",
                                            "ALLOW_MULTI_SELECT" => "N"
                                        ), false
                                        ); ?>
                                    </div>
                                    <div class="item_block col-3">
                                        <? $APPLICATION->IncludeComponent("bitrix:menu", "bottom_submenu", array(
                                            "ROOT_MENU_TYPE" => "bottom_help",
                                            "MENU_CACHE_TYPE" => "Y",
                                            "MENU_CACHE_TIME" => "3600000",
                                            "MENU_CACHE_USE_GROUPS" => "N",
                                            "MENU_CACHE_GET_VARS" => array(),
                                            "MAX_LEVEL" => "1",
                                            "USE_EXT" => "N",
                                            "DELAY" => "N",
                                            "ALLOW_MULTI_SELECT" => "N"
                                        ), false
                                        ); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="item_block col-4 soc">
                                <div class="soc_wrapper">
                                    <div class="phones">
                                        <div class="phone_block">
													<span class="phone_wrap">
<!--														<span class="icons fa fa-phone"></span>-->
														<span>
															<? $APPLICATION->IncludeComponent("bitrix:main.include", ".default",
                                                                array(
                                                                    "COMPONENT_TEMPLATE" => ".default",
                                                                    "PATH" => SITE_DIR . "include/phone.php",
                                                                    "AREA_FILE_SHOW" => "file",
                                                                    "AREA_FILE_SUFFIX" => "",
                                                                    "AREA_FILE_RECURSIVE" => "Y",
                                                                    "EDIT_TEMPLATE" => "standard.php"
                                                                ),
                                                                false
                                                            ); ?>
														</span>
													</span>
                                            <!--                                            <span class="order_wrap_btn">-->
                                            <!--														<span onclick="yaCounter43218779.reachGoal('perezvoni'); return true;"-->
                                            <!--                                                              class="callback_btn button ">--><? //= GetMessage('CALLBACK') ?><!--</span>-->
                                            <!--													</span>-->
                                        </div>
                                    </div>
                                    <div class="social_wrapper">
                                        <div class="social">
                                            <? /*$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
														array(
															"COMPONENT_TEMPLATE" => ".default",
															"PATH" => SITE_DIR."include/footer/social.info.optimus.default.php",
															"AREA_FILE_SHOW" => "file",
															"AREA_FILE_SUFFIX" => "",
															"AREA_FILE_RECURSIVE" => "Y",
															"EDIT_TEMPLATE" => "standard.php"
														),
														false
													);*/ ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mobile_copy">
                <? $APPLICATION->IncludeComponent("bitrix:main.include", ".default",
                    array(
                        "COMPONENT_TEMPLATE" => ".default",
                        "PATH" => SITE_DIR . "include/footer/copyright.php",
                        "AREA_FILE_SHOW" => "file",
                        "AREA_FILE_SUFFIX" => "",
                        "AREA_FILE_RECURSIVE" => "Y",
                        "EDIT_TEMPLATE" => "standard.php"
                    ),
                    false
                ); ?>
            </div>
            <? $APPLICATION->IncludeFile(SITE_DIR . "include/bottom_include1.php", Array(), Array("MODE" => "text", "NAME" => GetMessage("ARBITRARY_1"))); ?>
            <? $APPLICATION->IncludeFile(SITE_DIR . "include/bottom_include2.php", Array(), Array("MODE" => "text", "NAME" => GetMessage("ARBITRARY_2"))); ?>
        </div>
    </div>
</footer>
<? //$APPLICATION->IncludeFile(SITE_DIR."include_arear/footer/modals.php", Array(), Array("MODE" => "html", "NAME" => "Email"));?>
<? $APPLICATION->IncludeFile(SITE_DIR . "local/include/jivo.php", Array(), Array("MODE" => "text", "NAME" => GetMessage("ARBITRARY_1"))); ?>
<? $APPLICATION->IncludeFile(SITE_DIR . "local/include/metrika.php", [], []); ?>
<?
COptimus::setFooterTitle();
COptimus::showFooterBasket();
?>
<!-- Rating@Mail.ru counter -->
<script type="text/javascript">
var _tmr = window._tmr || (window._tmr = []);
_tmr.push({id: "3138872", type: "pageView", start: (new Date()).getTime()});
(function (d, w, id) {
  if (d.getElementById(id)) return;
  var ts = d.createElement("script"); ts.type = "text/javascript"; ts.async = true; ts.id = id;
  ts.src = "https://top-fwz1.mail.ru/js/code.js";
  var f = function () {var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ts, s);};
  if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); }
})(document, window, "topmailru-code");
</script><noscript><div>
<img src="https://top-fwz1.mail.ru/counter?id=3138872;js=na" style="border:0;position:absolute;left:-9999px;" alt="Top.Mail.Ru" />
</div></noscript>
<!-- //Rating@Mail.ru counter -->
<!-- CLEANTALK template addon -->
<?php $frame = (new \Bitrix\Main\Page\FrameHelper("cleantalk_frame"))->begin(); if(CModule::IncludeModule("cleantalk.antispam")) echo CleantalkAntispam::FormAddon(); $frame->end(); ?>
<!-- /CLEANTALK template addon -->
</body>
<? $assets = \Bitrix\Main\Page\Asset::getInstance();
$assets->addJs(SITE_TEMPLATE_PATH . "/js/jquery.sticky.js");
$assets->addJs(SITE_TEMPLATE_PATH . "/js/swiper.js");
$assets->addJs(SITE_TEMPLATE_PATH . "/js/fm.revealator.jquery.min.js"); ?>
<? if (CModule::IncludeModule("aspro.optimus")) {
    COptimus::StartJS(SITE_ID);
}
?>
</html>
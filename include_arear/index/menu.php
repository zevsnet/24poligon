<!-- BEGIN MENU -->
<section id="menu-area">
    <nav class="navbar navbar-default" role="navigation">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4">

                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>

                        <? $APPLICATION->IncludeComponent("bitrix:main.include", "", Array(
                            "AREA_FILE_SHOW" => "file",
                            "PATH"           => "/include_arear/header/logo.php",
                            "EDIT_TEMPLATE"  => ""
                        ), false); ?>

                    </div>
                </div>

                <div class="col-lg-8 col-md-8">
                    <? $APPLICATION->IncludeComponent("bitrix:menu", "z_menu", array(
                        "ROOT_MENU_TYPE"        => "top",
                        "MENU_CACHE_TYPE"       => "A",
                        "MENU_CACHE_TIME"       => "3600",
                        "MENU_CACHE_USE_GROUPS" => "Y",
                        "MENU_CACHE_GET_VARS"   => array(),
                        "MAX_LEVEL"             => "1",
                        "CHILD_MENU_TYPE"       => "left",
                        "USE_EXT"               => "N",
                        "DELAY"                 => "N",
                        "ALLOW_MULTI_SELECT"    => "N"
                    ), false); ?>

                    <? /*<a href="#" id="search-icon">
                <i class="fa fa-search">
                </i>
            </a>*/ ?>
                </div>
            </div>


        </div>
    </nav>
</section>
<!-- END MENU -->
<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
    <div>
        <h2>Обратитесь в военторг «Полигон»</h2>
        <p>Учтем все ваши требования и поможем найти подходящие милитари-товары</p>
        <div class="sb_row hidden-xs">
            <div class="sb-col-md-4 sb_text_center"><img src="medal.png" alt=""></div>
            <div class="sb-col-md-4 sb_text_center"><img src="handshake.png" alt=""></div>
            <div class="sb-col-md-4 sb_text_center"><img src="coupon.png" alt=""></div>
        </div>
        <div class="sb_row hidden-xs">

            <div class="sb-col-md-4">
                <b>Стойкая к износу продукция</b><br>
                Выберите любую из представленных в каталоге позиций и убедитесь в ее надежности. В ассортименте 3000 наименований товаров и снаряжения из прочных материалов с повышенными прочностными характеристиками, например, ткани Airtex, «Оксфорд»,
                «Рип-стоп» и другие. Действует гарантия, пожалуйста, проверяйте комплектацию и целостность при получении заказа.
            </div>
            <div class="sb-col-md-4">
                <b>Специальные условия для оптовиков</b><br>
                Хотите стать партнером «Полигона» – обратитесь к менеджерам по телефону 8 800 600-53-06. Заключаем партнерские отношения с представителями охранных агентств, военными учебными заведениями, владельцами компаний, занимающихся розничной
                продажей снаряжения. Обсудим варианты поставки и оплаты, найдем взаимовыгодный вариант сотрудничества
            </div>
            <div class="sb-col-md-4">
                <b>Постоянно действующие скидки</b><br>
                Сэкономьте свои средства – купите тактическое снаряжение и военные товары со скидкой. Снижаем стоимость на 10–50 %, каждый клиент сможет найти интересные предложения. Узнать о действующих акциях вы можете а разделе «Распродажа». Также у нас
                есть бонусные карты и подарочные сертификаты на любую сумму. Минимальный заказ – всего 500 рублей
            </div>
        </div>

        <div class="sb_row hidden-md" style="margin: 0;">
            <div class="sb_text_center"><img src="medal.png" alt=""></div>
            <div>
                <b>Стойкая к износу продукция</b><br>
                Выберите любую из представленных в каталоге позиций и убедитесь в ее надежности. В ассортименте 3000 наименований товаров и снаряжения из прочных материалов с повышенными прочностными характеристиками, например, ткани Airtex, «Оксфорд»,
                «Рип-стоп» и другие. Действует гарантия, пожалуйста, проверяйте комплектацию и целостность при получении заказа.
            </div>
        </div>
        <div class="sb_row hidden-md">
            <div class="sb-col-md-4 sb_text_center"><img src="handshake.png" alt=""></div>
            <div class="sb-col-md-4">
                <b>Специальные условия для оптовиков</b><br>
                Хотите стать партнером «Полигона» – обратитесь к менеджерам по телефону 8 800 600-53-06. Заключаем партнерские отношения с представителями охранных агентств, военными учебными заведениями, владельцами компаний, занимающихся розничной
                продажей снаряжения. Обсудим варианты поставки и оплаты, найдем взаимовыгодный вариант сотрудничества
            </div>
        </div>
        <div class="sb_row hidden-md">
            <div class="sb-col-md-4 sb_text_center"><img src="coupon.png" alt=""></div>
            <div class="sb-col-md-4">
                <b>Постоянно действующие скидки</b><br>
                Сэкономьте свои средства – купите тактическое снаряжение и военные товары со скидкой. Снижаем стоимость на 10–50 %, каждый клиент сможет найти интересные предложения. Узнать о действующих акциях вы можете а разделе «Распродажа». Также у нас
                есть бонусные карты и подарочные сертификаты на любую сумму. Минимальный заказ – всего 500 рублей
            </div>
        </div>


        <br>
        <strong>В нашем интернет-магазине военных товаров есть доставка по всей России и странам ТС</strong>
        <p>Отправим посылку Почтой России – менеджер сообщит стоимость по телефону</p>
        <a href="/catalog/"> Перейти в каталог</a>

    </div>

    <style>
        h1 {
            font-size: 24px !important;
        }

        h2 {
            margin-bottom: 0 !important;
        }

        .sb_title_h2 {
            text-align: center;
        }

        .sb_row {
            display: flex;
            width: 100%;
            margin-left: -15px;
            margin-right: -15px;
        }

        .sb-col-md-4 {
            width: 29%;
            display: inline-block;
            padding-left: 15px;
            padding-right: 15px;
        }

        .sb_text_center {
            text-align: center;
        }

        .hidden-md {
            display: none;
        }

        @media (max-width: 425px) {
            .title_block {
                margin: 0;
            }

            .sb-col-md-4 {
                width: 100%;
            }

            h1 {
                padding: 0;
            }

            .hidden-xs {
                display: none;
            }

            .hidden-md {
                display: block;
            }
        }
    </style>
<? $APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "optimus",
    array(
        "IBLOCK_TYPE" => "aspro_optimus_content",
        "IBLOCK_ID" => "118",
        "NEWS_COUNT" => "4",
        "SORT_BY1" => "SORT",
        "SORT_ORDER1" => "ASC",
        "SORT_BY2" => "ID",
        "SORT_ORDER2" => "DESC",
        "FILTER_NAME" => "",
        "FIELD_CODE" => array(
            0 => "",
            1 => "",
        ),
        "PROPERTY_CODE" => array(
            0 => "LINK",
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
        "CACHE_FILTER" => "Y",
        "CACHE_GROUPS" => "N",
        "PREVIEW_TRUNCATE_LEN" => "",
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
        "COMPONENT_TEMPLATE" => "optimus",
        "SET_BROWSER_TITLE" => "Y",
        "SET_META_KEYWORDS" => "Y",
        "SET_META_DESCRIPTION" => "Y",
        "SET_LAST_MODIFIED" => "N",
        "PAGER_BASE_LINK_ENABLE" => "N",
        "SHOW_404" => "N",
        "MESSAGE_404" => ""
    ),
    false,
    array(
        "ACTIVE_COMPONENT" => "N"
    )

); ?>